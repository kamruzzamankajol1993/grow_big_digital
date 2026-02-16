<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Brand;
use App\Models\MainBrand;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\SubSubcategory;
use App\Models\Fabric;
use App\Models\Unit;
use App\Models\ExtraCategory;
use App\Models\Color;
use App\Models\Size;
use App\Models\AnimationCategory;
use App\Models\SizeChart;
use App\Models\AssignChart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\File;
use App\Models\ProductVariant;
use App\Models\AssignCategory;
use Illuminate\Validation\ValidationException;
use App\Imports\ProductsImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\CompanyCategory;
class ProductController extends Controller
{

    public function exportVariantsStock()
{
    try {
        $filename = 'product_variant_stock_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        // ১. রিলেশনশিপ দিয়ে কালার লোড করা হচ্ছে (যাতে color_id থেকে নাম পাওয়া যায়)
        $products = Product::with(['variants.color'])->where('status', 1)->get();

        // ২. সব সাইজের নাম ID সহকারে নিয়ে আসা হচ্ছে (লুকআপ করার জন্য)
        // এটি [1 => 'M', 2 => 'L', 3 => 'XL'] এমন ফরম্যাটে ডাটা আনবে
        $allSizes = Size::pluck('name', 'id');

        $callback = function() use ($products, $allSizes) {
            $file = fopen('php://output', 'w');
            
            // এক্সেল হেডার
            fputcsv($file, ['Product Name', 'Color', 'Size', 'Quantity']);

            foreach ($products as $product) {
                if ($product->variants->isNotEmpty()) {
                    foreach ($product->variants as $variant) {
                        
                        // এখানে color_id এর বদলে রিলেশন ব্যবহার করে কালারের নাম নেওয়া হচ্ছে
                        $colorName = $variant->color ? $variant->color->name : 'No Color';
                        
                        $variantSizes = $variant->sizes; 

                        if (is_array($variantSizes)) {
                            foreach ($variantSizes as $sizeItem) {
                                
                                $sizeId = $sizeItem['size_id'] ?? null;
                                $quantity = $sizeItem['quantity'] ?? 0;
                                
                                // এখানে size_id দিয়ে $allSizes অ্যারে থেকে নাম খুঁজে নেওয়া হচ্ছে
                                $sizeName = $allSizes[$sizeId] ?? 'N/A';

                                fputcsv($file, [
                                    $product->name,
                                    $colorName,      // আইডির বদলে নাম যাবে
                                    $sizeName,       // আইডির বদলে নাম যাবে
                                    $quantity
                                ]);
                            }
                        }
                    }
                }
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Could not export data.');
    }
}
    private function getProductData()
    {
        try {
        return [
            'brands' => Brand::where('status', 1)->get(),
            'mainbrands' => MainBrand::where('status', 1)->get(),
             'categories' => Category::with('children')->whereNull('parent_id')->where('status', 1)->get(),
          
        ];

        } catch (\Exception $e) {
            Log::error('Error in getProductData: ' . $e->getMessage());
            // Re-throw exception to be caught by the calling public method
            throw $e;
        }
    }

    // AJAX method to get subcategories
    public function getSubcategories($categoryId)
    {
        try {
        return response()->json(Subcategory::where('category_id', $categoryId)->where('status', 1)->get());
    } catch (\Exception $e) {
            Log::error('Error getting subcategories: ' . $e->getMessage());
            return response()->json(['error' => 'Could not fetch subcategories.'], 500);
        }
    }

    public function downloadSample()
    {
        $filename = 'product_import_sample.csv';
        
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        // Updated Columns List with 'image'
        $columns = [
            'company_name', 
            'company_category', 
            'category_name', 
            'product_name', 
            'description', 
            'specification', 
            'buying_price', 
            'selling_price', 
            'discount_price',
            'image' // New Column
        ];

        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            
            // Add Header Row
            fputcsv($file, $columns);

            // Add Dummy Data Row with Image URL
            fputcsv($file, [
                'Nike',           
                'Men',            
                'Shoes',          
                'Running Shoe',   
                'Comfortable running shoes', 
                'Size: 42, Color: Red',      
                '500',            
                '800',            
                '750',
                'https://example.com/shoe.jpg' // Sample Image URL
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function import(Request $request) 
    {

        set_time_limit(0);
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);
 
        try {
            Excel::import(new ProductsImport, $request->file('file'));
            return redirect()->back()->with('success', 'Products imported successfully!');
        } catch (\Exception $e) {
            Log::error('Import Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error importing products: ' . $e->getMessage());
        }
    }

    // AJAX method to get sub-subcategories
    public function getSubSubcategories($subcategoryId)
    {
        try {
        return response()->json(SubSubcategory::where('subcategory_id', $subcategoryId)->where('status', 1)->get());
         } catch (\Exception $e) {
            Log::error('Error getting sub-subcategories: ' . $e->getMessage());
            return response()->json(['error' => 'Could not fetch sub-subcategories.'], 500);
        }
    }

    // AJAX method to get size chart entries
    public function getSizeChartEntries($id)
    {
       try { 
        return response()->json(SizeChart::with('entries')->findOrFail($id));
        } catch (\Exception $e) {
            Log::error('Error getting size chart entries: ' . $e->getMessage());
            return response()->json(['error' => 'Could not fetch size chart entries.'], 500);
        }
    }

    public function getBrandsByCategory($categoryId)
    {
        try {
            // ক্যাটাগরি অনুযায়ী ব্র্যান্ড ফিল্টার (Brand টেবিলে category_id থাকতে হবে)
            $brands = Brand::where('category_id', $categoryId)->where('status', 1)->get(['id', 'name']);
            return response()->json($brands);
        } catch (\Exception $e) {
            return response()->json([]);
        }
    }

    // --- UPDATED: Generate Tree Structure for Company Categories ---
    private function generateCategoryTreeHtml($categories, $parentId = null)
    {
        $html = '';
        // Filter categories belonging to the current parent (or root if null)
        $children = $categories->where('parent_id', $parentId);

        foreach ($children as $category) {
            // Check if this category has children in the collection
            $hasChildren = $categories->where('parent_id', $category->id)->isNotEmpty();

            $html .= '<li class="category-tree-item list-unstyled">';
            $html .= '<div class="form-check">';
            $html .= '<input class="form-check-input" type="checkbox" name="category_ids[]" value="'.$category->id.'" id="comp-cat-'.$category->id.'">';
            $html .= '<label class="form-check-label" for="comp-cat-'.$category->id.'">';
            
            // Toggle Icon logic
            if ($hasChildren) {
                $html .= '<i class="toggle-icon fas fa-plus-square me-1"></i>';
            } else {
                $html .= '<span style="display:inline-block; width:16px;"></span>';
            }
            
            $html .= $category->name;
            $html .= '</label>';
            $html .= '</div>';

            // Recursive call for children
            if ($hasChildren) {
                $html .= '<ul class="category-tree-child list-unstyled" style="display: none;">';
                $html .= $this->generateCategoryTreeHtml($categories, $category->id);
                $html .= '</ul>';
            }

            $html .= '</li>';
        }

        return $html;
    }

    // --- UPDATED: AJAX method to get company categories by brand with Tree ---
    public function getCompanyCategoriesByBrand($brandId)
    {
        try {
            // Fetch ALL active categories for this brand
            $companyCategories = CompanyCategory::where('company_id', $brandId)
                                                ->where('status', 1)
                                                ->get();
            
            $html = '';
            if ($companyCategories->count() > 0) {
                // Start generating tree from Root categories (parent_id = null)
                $html = $this->generateCategoryTreeHtml($companyCategories, null);
            } else {
                $html = '<li class="text-muted small">No categories found for this company.</li>';
            }

            return response()->json(['html' => $html]);

        } catch (\Exception $e) {
            Log::error('Error fetching company categories: ' . $e->getMessage());
            return response()->json(['html' => '']);
        }
    }


     public function index()
    {

         try {
       
        $categories = Category::where('status', 1)->orderBy('name')->get(); // MODIFIED: Get categories for the filter
        return view('admin.product.index', compact('categories')); // MODIFIED: Pass categories to the view
         } catch (\Exception $e) {
            Log::error('Error loading product index page: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Could not load product page.');
        }
    }

    public function data(Request $request)
    {
        try {
        $query = Product::with(['category', 'brand']);

         // --- NEW: Advanced Filtering Logic ---
        if ($request->filled('product_name')) {
            $query->where('name', 'like', '%' . $request->product_name . '%');
        }

        if ($request->filled('product_code')) {
            $query->where('product_code', 'like', '%' . $request->product_code . '%');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id); // এখন ডাইরেক্ট কলামে আছে
        }
        // --- END: Advanced Filtering Logic ---

        $sort = $request->get('sort', 'id');
        $direction = $request->get('direction', 'desc');
        $query->orderBy($sort, $direction);

        $products = $query->paginate(10);

        return response()->json([
            'data' => $products->items(),
            'total' => $products->total(),
            'current_page' => $products->currentPage(),
            'last_page' => $products->lastPage(),
        ]);
         } catch (\Exception $e) {
            Log::error('Error fetching product data for table: ' . $e->getMessage());
            return response()->json(['error' => 'Could not fetch product data.'], 500);
        }
    }


   

    public function create()
    {
        // ক্যাটাগরি শুধু প্যারেন্টগুলো লোড হবে ড্রপডাউনের জন্য
        $categories = Category::where('status', 1)->get(); 
        // ব্র্যান্ড ইনিশিয়ালি ফাঁকা থাকবে, ক্যাটাগরি সিলেক্ট করলে আসবে
        $brands = []; 
        
        return view('admin.product.create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'product_code' => 'nullable|string|unique:products,product_code',
            'category_id' => 'required|exists:categories,id', // Main Category Required
            'brand_id' => 'nullable|exists:brands,id',       // Company Nullable
            'base_price' => 'nullable|numeric|min:0',        // Base Price Nullable
            'purchase_price' => 'required|numeric|min:0',
            // discount_price validation relaxed since base_price is nullable
            'discount_price' => 'nullable|numeric', 
            'thumbnail_image.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'category_ids' => 'nullable|array', // Company Categories Nullable
        ]);

        DB::beginTransaction();
        try {
            // 1. Image Upload (Multiple)
            $thumbnailPaths = [];
            $mainPaths = [];
            
            if ($request->hasFile('thumbnail_image')) {
                foreach ($request->file('thumbnail_image') as $image) {
                    $thumbnailPaths[] = $this->uploadImageMobile($image, 'products/thumbnails');
                    $mainPaths[] = $this->uploadImage($image, 'products/thumbnails');
                }
            }

            // 2. Create Product
            $product = Product::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'product_code' => $request->product_code,
                'brand_id' => $request->brand_id,       
                'category_id' => $request->category_id, 
                'description' => $request->description,
                'specification' => $request->specification,
                'base_price' => $request->base_price,
                'purchase_price' => $request->purchase_price,
                'discount_price' => $request->discount_price,
                'thumbnail_image' => $thumbnailPaths,
                'main_image' => $mainPaths,
                'status' => $request->has('status') ? 1 : 0,
            ]);

            // 3. Assign Company Categories (Only if selected)
            if ($request->has('category_ids')) {
                foreach ($request->category_ids as $compCatId) {
                    $compCatName = \App\Models\CompanyCategory::where('id', $compCatId)->value('name') ?? 'N/A';

                    AssignCategory::create([
                        'product_id' => $product->id,
                        'category_id' => $compCatId, 
                        'category_name' => $compCatName,
                        'type' => 'company_category'
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('product.index')->with('success', 'Product created successfully.');

        } catch (ValidationException $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing product: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong while saving the product.')->withInput();
        }
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'product_code' => 'nullable|string|unique:products,product_code,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',       // Company Nullable
            'base_price' => 'nullable|numeric|min:0',        // Base Price Nullable
            'purchase_price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric',
            'thumbnail_image.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'category_ids' => 'nullable|array',
            'delete_images' => 'nullable|array',
        ]);

        DB::beginTransaction();
        try {
            // 1. Handle Images
            $existingThumbnails = $product->thumbnail_image ?? [];
            $existingMains = $product->main_image ?? [];

            if ($request->has('delete_images')) {
                foreach ($request->input('delete_images') as $pathToDelete) {
                    $index = array_search($pathToDelete, $existingThumbnails);
                    if ($index !== false) {
                        $this->deleteImage($existingThumbnails[$index]);
                        $this->deleteImage($existingMains[$index] ?? null);
                        unset($existingThumbnails[$index]);
                        unset($existingMains[$index]);
                    }
                }
            }

            $finalThumbnails = array_values($existingThumbnails);
            $finalMains = array_values($existingMains);

            if ($request->hasFile('thumbnail_image')) {
                foreach ($request->file('thumbnail_image') as $image) {
                    $finalThumbnails[] = $this->uploadImageMobile($image, 'products/thumbnails');
                    $finalMains[] = $this->uploadImage($image, 'products/thumbnails');
                }
            }

            // 2. Update Product
            $product->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'product_code' => $request->product_code,
                'brand_id' => $request->brand_id,
                'category_id' => $request->category_id,
                'description' => $request->description,
                'specification' => $request->specification,
                'base_price' => $request->base_price,
                'purchase_price' => $request->purchase_price,
                'discount_price' => $request->discount_price,
                'thumbnail_image' => $finalThumbnails,
                'main_image' => $finalMains,
                'status' => $request->has('status') ? 1 : 0,
            ]);

            // 3. Update Company Categories
            AssignCategory::where('product_id', $product->id)
                          ->where('type', 'company_category')
                          ->delete();

            if ($request->has('category_ids')) {
                foreach ($request->category_ids as $compCatId) {
                    $compCatName = \App\Models\CompanyCategory::where('id', $compCatId)->value('name') ?? 'N/A';

                    AssignCategory::create([
                        'product_id' => $product->id,
                        'category_id' => $compCatId,
                        'category_name' => $compCatName,
                        'type' => 'company_category'
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('product.index')->with('success', 'Product updated successfully.');

        } catch (ValidationException $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating product: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong while updating the product.')->withInput();
        }
    }

    public function show(Product $product)
    {
        try {
            // Eager load relationships
            $product->load([
                'brand',        // The Company
                'category',     // The Main Category
                'assigns' => function ($query) {
                    // Filter assigns to only show Company Categories
                    $query->where('type', 'company_category');
                }
            ]);
            
            return view('admin.product.show', compact('product'));

        } catch (\Exception $e) {
            Log::error('Error showing product details: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Could not load product details.');
        }
    }

 public function edit(Product $product)
    {
        $categories = Category::where('status', 1)->get();
        
        // এডিট মোডে সিলেক্টেড ক্যাটাগরি অনুযায়ী ব্র্যান্ড লোড করতে হবে
        $brands = Brand::where('category_id', $product->category_id)->where('status', 1)->get();

        // সিলেক্টেড কোম্পানি ক্যাটাগরি আইডিগুলো বের করা
        $assignedCategoryIds = AssignCategory::where('product_id', $product->id)
                                             ->where('type', 'company_category')
                                             ->pluck('category_id')
                                             ->toArray();

        return view('admin.product.edit', compact('product', 'categories', 'brands', 'assignedCategoryIds'));
    }
    

     private function uploadRealImage($image, $directory)
    {
        $imageName = Str::uuid() . '.webp';
        $destinationPath = public_path('uploads/' . $directory);
        if (!File::isDirectory($destinationPath)) File::makeDirectory($destinationPath, 0777, true, true);
        Image::read($image->getRealPath())->save($destinationPath . '/' . $imageName, 100);
        return $directory . '/' . $imageName;
    }
 private function getAllCategoryIdsWithParents(array $selectedIds): array
    {

        try {
        $allIds = collect($selectedIds);
        $categories = Category::with('parent')->findMany($selectedIds);

        foreach ($categories as $category) {
            $current = $category;
            // Traverse up the tree until there is no parent
            while ($current->parent) {
                $allIds->push($current->parent->id);
                $current = $current->parent;
            }
        }

        // Return a unique, flat array of all IDs
        return $allIds->unique()->values()->all();
        } catch (\Exception $e) {
            Log::error('Error in getAllCategoryIdsWithParents: ' . $e->getMessage());
            throw $e;
        }
    }
    public function destroy(Product $product)
    {
        try {
        DB::transaction(function () use ($product) {
            foreach ($product->variants as $variant) {
                $this->deleteImage($variant->variant_image);
            }
            $this->deleteImage($product->thumbnail_image);
            $this->deleteImage($product->main_image);
            $product->delete();
        });

        return response()->json(['message' => 'Product deleted successfully.']);
        } catch (\Exception $e) {
            Log::error('Error deleting product: ' . $e->getMessage());
            return response()->json(['error' => 'Could not delete the product.'], 500);
        }
    }
    
    public function ajax_products_delete(Request $request) {

        try {
        
       $id = $request->id;
    // Attempt to find the product by its ID
    $product = Product::find($id);

    // Check if the product exists. If not, return a 404 Not Found response.
    if (!$product) {
        return response()->json(['message' => 'Product not found.'], 404);
    }

    // Use a database transaction to ensure all operations succeed or fail together.
    DB::transaction(function () use ($product) {
        // Delete images for each product variant
        foreach ($product->variants as $variant) {
            $this->deleteImage($variant->variant_image);
        }

        // Delete the main and thumbnail images
        $this->deleteImage($product->thumbnail_image);
        $this->deleteImage($product->main_image);

        // Delete the product record itself, but only after its images are successfully deleted
        $product->delete();
    });

    // If the transaction completes without errors, return a success message.
    return response()->json(['message' => 'Product deleted successfully.']);

      } catch (\Exception $e) {
            Log::error('Error deleting product via AJAX: ' . $e->getMessage());
            return response()->json(['error' => 'Could not delete the product.'], 500);
        }
}

    private function uploadImage($image, $directory)
    {
        try {
        $imageName = Str::uuid() . '.' . 'webp';
        $destinationPath = public_path('uploads/' . $directory);
        if (!File::isDirectory($destinationPath)) {
            File::makeDirectory($destinationPath, 0777, true, true);
        }
        Image::read($image->getRealPath())->resize(600, 600, function ($c) {
            $c->aspectRatio(); $c->upsize();
        })->save($destinationPath . '/' . $imageName);
        return $directory . '/' . $imageName;
         } catch (\Exception $e) {
            Log::error('Error in uploadImage: ' . $e->getMessage());
            throw $e;
        }
    }

    private function uploadImageMobile($image, $directory)
    {
        try {
        $imageName = Str::uuid() . '.' . 'webp';
        $destinationPath = public_path('uploads/' . $directory);
        if (!File::isDirectory($destinationPath)) {
            File::makeDirectory($destinationPath, 0777, true, true);
        }
        Image::read($image->getRealPath())->resize(300, 300, function ($c) {
            $c->aspectRatio(); $c->upsize();
        })->save($destinationPath . '/' . $imageName);
        return $directory . '/' . $imageName;
        } catch (\Exception $e) {
            Log::error('Error in uploadImageMobile: ' . $e->getMessage());
            throw $e;
        }
    }

    private function deleteImage($paths)
    {
         try {
        if (is_array($paths)) {
            foreach ($paths as $path) {
                if ($path && File::exists(public_path('uploads/' . $path))) {
                    File::delete(public_path('uploads/' . $path));
                }
            }
        } elseif (is_string($paths)) {
            if ($paths && File::exists(public_path('uploads/' . $paths))) {
                File::delete(public_path('uploads/' . $paths));
            }
        }
        } catch (\Exception $e) {
            Log::error('Error deleting image: ' . $e->getMessage());
            // We don't re-throw here because a failed image deletion might not be a critical error.
        }
    }

    public function bulkStatusUpdate(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:products,id',
                'status' => 'required|boolean',
            ]);

            $productIds = $request->input('ids');
            $status = $request->input('status');

            Product::whereIn('id', $productIds)->update(['status' => $status]);

            return response()->json(['message' => 'Product statuses updated successfully.']);

        } catch (ValidationException $e) {
            return response()->json(['message' => 'Invalid data provided.'], 422);
        } catch (\Exception $e) {
            Log::error('Error in bulk status update: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred.'], 500);
        }
    }
    
}
