<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;
use App\Imports\BrandsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use App\Models\Category;
class BrandController extends Controller
{
   
public function import(Request $request)
    {
        // Timeout বাড়িয়ে দেওয়া হয়েছে কারণ ইমেজ ডাউনলোড করতে সময় লাগতে পারে
        set_time_limit(0); 

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new BrandsImport, $request->file('file'));
            return redirect()->back()->with('success', 'Companies imported successfully!');
        } catch (\Exception $e) {
            Log::error('Company Import Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error importing companies: ' . $e->getMessage());
        }
    }

    public function downloadSample()
    {
        $filename = 'company_import_sample.csv';
        
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        // নতুন কলাম 'image' যুক্ত করা হলো
        $columns = ['company_name', 'category_name', 'description', 'image'];

        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            // Sample Data তে ইমেজের লিংক দেওয়া হলো
            fputcsv($file, [
                'Nike', 
                'Sports', 
                'Leading sports brand', 
                'https://example.com/nike-logo.png' // ডামি ইমেজ লিংক
            ]); 
            fputcsv($file, [
                'Adidas', 
                'Fashion', 
                'Global sportswear manufacturer', 
                'https://example.com/adidas-logo.jpg'
            ]); 
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
     public function index()


    {
        
        $categories = Category::where('status', 1)->select('id', 'name')->get();
        return view('admin.brand.index', compact('categories'));
    }

    public function data(Request $request)
    {
        // রিলেশনসহ কুয়েরি করা হচ্ছে (with category)
        $query = Brand::with('category');

        if ($request->filled('search')) {
            $query->where('name', 'like', $request->search . '%');
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
    // Load category relationship
    $brand = Brand::with('category')->findOrFail($id);

    // If request comes from AJAX (Edit Modal), return JSON
    if (request()->ajax()) {
        return response()->json($brand);
    }

    // Otherwise return the Show View
    return view('admin.brand.show', compact('brand'));
}

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:brands,name',
            'category_id' => 'nullable|exists:categories,id', // ভ্যালিডেশন
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('logo')) {
            $image = $request->file('logo');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('uploads/brands');

            if (!File::isDirectory($destinationPath)) {
                File::makeDirectory($destinationPath, 0777, true, true);
            }

            Image::read($image->getRealPath())->resize(300, 300, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save($destinationPath.'/'.$imageName);

            $path = 'uploads/brands/'.$imageName;
        }

        Brand::create([
            'category_id' => $request->category_id, // ডাটা সেভ
            'name' => $request->name,
            'description' => $request->description,
            'slug' => Str::slug($request->name),
            'logo' => $path,
        ]);

        return redirect()->back()->with('success', 'Brand created successfully!');
    }

    public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);

        $request->validate([
            'name' => 'required|string|unique:brands,name,' . $brand->id,
            'category_id' => 'nullable|exists:categories,id', // ভ্যালিডেশন
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $path = $brand->logo;
        if ($request->hasFile('logo')) {
            if ($brand->logo && File::exists(public_path('uploads/'.$brand->logo))) {
                File::delete(public_path('uploads/'.$brand->logo));
            }

            $image = $request->file('logo');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('uploads/brands');

            Image::read($image->getRealPath())->resize(300, 300, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save($destinationPath.'/'.$imageName);

            $path = 'uploads/brands/'.$imageName;
        }

        $brand->update([
            'category_id' => $request->category_id, // আপডেট
            'name' => $request->name,
            'description' => $request->description,
            'slug' => Str::slug($request->name),
            'logo' => $path,
            'status' => $request->status,
        ]);

        return response()->json(['message' => 'Brand updated successfully']);
    }

    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);

        // Delete logo from uploads
        if ($brand->logo && File::exists(public_path('uploads/'.$brand->logo))) {
            File::delete(public_path('uploads/'.$brand->logo));
        }

        $brand->delete();
        // CommonController::addToLog('brandDelete');
        return redirect()->route('brand.index')->with('success', 'Brand deleted successfully!');
    }
}
