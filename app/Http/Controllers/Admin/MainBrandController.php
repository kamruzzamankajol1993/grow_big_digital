<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\MainBrand;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;
use App\Imports\MainBrandsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
class MainBrandController extends Controller
{
     
public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new MainBrandsImport, $request->file('file'));
            return redirect()->back()->with('success', 'Brands imported successfully!');
        } catch (\Exception $e) {
            Log::error('Brand Import Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error importing brands: ' . $e->getMessage());
        }
    }

    public function downloadSample()
    {
        $filename = 'brand_import_sample.csv';
        
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['brand_name', 'description'];

        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            // Sample Data
            fputcsv($file, ['Nike', 'Leading sports brand']); 
            fputcsv($file, ['Adidas', 'Global sportswear manufacturer']); 
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
     public function index(): View
    {
        // CommonController::addToLog('brandView'); // Assuming you have this helper
        return view('admin.main_brand.index');
    }

    public function data(Request $request)
    {
        $query = MainBrand::query();

        if ($request->filled('search')) {
            $query->where('name', 'like',$request->search . '%');
        }

        $sort = $request->get('sort', 'id');
        $direction = $request->get('direction', 'desc');
        $query->orderBy($sort, $direction);

        $brands = $query->paginate(10);

        return response()->json([
            'data' => $brands->items(),
            'total' => $brands->total(),
            'current_page' => $brands->currentPage(),
            'last_page' => $brands->lastPage(),
        ]);
    }

    public function show($id)
    {
        $brand = MainBrand::findOrFail($id);
        return response()->json($brand);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:brands,name',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('logo')) {
            $image = $request->file('logo');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('uploads/brands');

            // Ensure the directory exists
            if (!File::isDirectory($destinationPath)) {
                File::makeDirectory($destinationPath, 0777, true, true);
            }

            // Use Intervention Image to resize and save
            Image::read($image->getRealPath())->resize(300, 300, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save($destinationPath.'/'.$imageName);

            $path = 'uploads/brands/'.$imageName;
        }

        MainBrand::create([
            'name' => $request->name,
            'description' => $request->description,
            'slug' => Str::slug($request->name),
            'logo' => $path,
        ]);

        // CommonController::addToLog('brandStore');
        return redirect()->back()->with('success', 'Brand created successfully!');
    }

    public function update(Request $request, $id)
    {
        $brand = MainBrand::findOrFail($id);

        $request->validate([
            'name' => 'required|string|unique:main_brands,name,' . $brand->id,
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $path = $brand->logo;
        if ($request->hasFile('logo')) {
            // Delete old logo if it exists
            if ($brand->logo && File::exists(public_path('uploads/'.$brand->logo))) {
                File::delete(public_path('uploads/'.$brand->logo));
            }

            $image = $request->file('logo');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('uploads/brands');

            // Use Intervention Image to resize and save
            Image::read($image->getRealPath())->resize(300, 300, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save($destinationPath.'/'.$imageName);

            $path = 'uploads/brands/'.$imageName;
        }

        $brand->update([
            'name' => $request->name,
            'description' => $request->description,
            'slug' => Str::slug($request->name),
            'logo' => $path,
            'status' => $request->status,
        ]);

        // CommonController::addToLog('brandUpdate');
        return response()->json(['message' => 'Brand updated successfully']);
    }

    public function destroy($id)
    {
        $brand = MainBrand::findOrFail($id);

        // Delete logo from uploads
        if ($brand->logo && File::exists(public_path('uploads/'.$brand->logo))) {
            File::delete(public_path('uploads/'.$brand->logo));
        }

        $brand->delete();
        // CommonController::addToLog('brandDelete');
        return redirect()->route('main_brand.index')->with('success', 'Brand deleted successfully!');
    }
}
