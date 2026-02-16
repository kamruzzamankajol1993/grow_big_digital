<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyCategory;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image; // ইমেজ লাইব্রেরি
use App\Imports\CompanyCategoriesImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
class CompanyCategoryController extends Controller
{

    // --- নতুন: এক্সেল ইম্পোর্ট মেথড ---
    public function import(Request $request)
    {

        set_time_limit(0);
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new CompanyCategoriesImport, $request->file('file'));
            return redirect()->back()->with('success', 'Categories imported successfully!');
        } catch (\Exception $e) {
            Log::error('Category Import Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error importing: ' . $e->getMessage());
        }
    }

   public function show($id)
{
    $category = CompanyCategory::with(['company', 'parent'])->findOrFail($id);

    // If AJAX request (Edit Modal), return JSON
    if (request()->ajax()) {
        return response()->json($category);
    }

    // Otherwise return View (Show Page)
    return view('admin.company_category.show', compact('category'));
}

public function downloadSample()
{
    $filename = 'company_category_import_sample.csv';
    
    $headers = [
        "Content-type"        => "text/csv",
        "Content-Disposition" => "attachment; filename=$filename",
        "Pragma"              => "no-cache",
        "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
        "Expires"             => "0"
    ];

    // Added 'image' column
    $columns = ['company_name', 'category_name', 'parent_category', 'description', 'image'];

    $callback = function() use ($columns) {
        $file = fopen('php://output', 'w');
        fputcsv($file, $columns);

        // Sample Row
        fputcsv($file, [
            'Nike', 
            'Shoes', 
            '', 
            'Description here', 
            'https://example.com/image.jpg'
        ]); 
        
        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

   public function index()
    {
        $companies = Brand::where('status', 1)->get();
        return view('admin.company_category.index', compact('companies'));
    }

    // --- NEW: AJAX Method to get categories by company ---
    public function getCategoriesByCompany($companyId)
    {
        try {
            $categories = CompanyCategory::where('company_id', $companyId)
                                         ->where('status', 1)
                                         ->select('id', 'name')
                                         ->get();
            return response()->json($categories);
        } catch (\Exception $e) {
            return response()->json([]);
        }
    }

    public function data(Request $request)
    {
        // Parent সহ লোড করা হচ্ছে
        $query = CompanyCategory::with(['company', 'parent']);

        if ($request->filled('search')) {
            $query->where('name', 'like', $request->search . '%')
                  ->orWhereHas('company', function($q) use ($request) {
                      $q->where('name', 'like', $request->search . '%');
                  });
        }

        $sort = $request->get('sort', 'id');
        $direction = $request->get('direction', 'desc');
        $query->orderBy($sort, $direction);

        $data = $query->paginate(10);

        return response()->json([
            'data' => $data->items(),
            'total' => $data->total(),
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:brands,id',
            'parent_id' => 'nullable|exists:company_categories,id', // ভ্যালিডেশন
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'nullable|string',
        ]);

        $path = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('uploads/company_category');
            if (!File::isDirectory($destinationPath)) File::makeDirectory($destinationPath, 0777, true, true);
            Image::read($image->getRealPath())->resize(400, 400, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save($destinationPath.'/'.$imageName);
            $path = 'uploads/company_category/'.$imageName;
        }

        CompanyCategory::create([
            'company_id' => $request->company_id,
            'parent_id' => $request->parent_id, // সেভ
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'image' => $path,
            'description' => $request->description,
            'status' => 1,
        ]);

        return redirect()->back()->with('success', 'Created successfully!');
    }

   

    public function update(Request $request, $id)
    {
        $category = CompanyCategory::findOrFail($id);

        $request->validate([
            'company_id' => 'required|exists:brands,id',
            'parent_id' => 'nullable|exists:company_categories,id',
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        // Prevent selecting self as parent
        if ($request->parent_id == $id) {
            return response()->json(['message' => 'A category cannot be its own parent.'], 422);
        }

        $path = $category->image;
        if ($request->hasFile('image')) {
            if ($category->image && File::exists(public_path($category->image))) {
                File::delete(public_path($category->image));
            }
            $image = $request->file('image');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('uploads/company_category');
            if (!File::isDirectory($destinationPath)) File::makeDirectory($destinationPath, 0777, true, true);
            Image::read($image->getRealPath())->resize(400, 400, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save($destinationPath.'/'.$imageName);
            $path = 'uploads/company_category/'.$imageName;
        }

        $category->update([
            'company_id' => $request->company_id,
            'parent_id' => $request->parent_id, // আপডেট
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'image' => $path,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return response()->json(['message' => 'Updated successfully']);
    }

    public function destroy($id)
    {
        $category = CompanyCategory::findOrFail($id);
        if ($category->image && File::exists(public_path($category->image))) {
            File::delete(public_path($category->image));
        }
        $category->delete();
        return redirect()->back()->with('success', 'Deleted successfully!');
    }
}