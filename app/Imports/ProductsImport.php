<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\AssignCategory;
use App\Models\CompanyCategory;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Log;

class ProductsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Expected Excel Headers: 
        // company_name, company_category, category_name, product_name, description, specification, buying_price, selling_price, discount_price, image

        // --- Helper: Price Sanitizer (Remove commas) ---
        $sanitizePrice = function ($price) {
            if (empty($price)) return 0;
            // Remove commas and spaces, then convert to float
            $cleaned = str_replace([',', ' '], '', $price);
            return is_numeric($cleaned) ? (float)$cleaned : 0;
        };

        // 1. Handle Brand (Company Name) - Nullable
        $brandId = null;
        if (!empty($row['company_name'])) {
            $brand = Brand::firstOrCreate(
                ['name' => trim($row['company_name'])], 
                [
                    'slug' => Str::slug($row['company_name']),
                    'status' => 1
                ]
            );
            $brandId = $brand->id;
        }

        // 2. Handle Company Category - Nullable
        $companyCategoryId = null;
        $companyCategoryName = null;
        if ($brandId && !empty($row['company_category'])) {
            $compCatName = trim($row['company_category']);
            $companyCategory = CompanyCategory::firstOrCreate(
                [
                    'name' => $compCatName, 
                    'company_id' => $brandId
                ], 
                [
                    'slug' => Str::slug($compCatName),
                    'status' => 1
                ]
            );
            $companyCategoryId = $companyCategory->id;
            $companyCategoryName = $companyCategory->name;
        }

        // 3. Handle Main Category
        $categoryId = null;
        if (!empty($row['category_name'])) {
            $category = Category::firstOrCreate(
                ['name' => trim($row['category_name'])], 
                [
                    'slug' => Str::slug($row['category_name']),
                    'status' => 1
                ]
            );
            $categoryId = $category->id;
        }

        // 4. Handle Image Download
        $imageArray = [];
        // if (!empty($row['image'])) {
        //     try {
        //         $imageUrl = $row['image'];
        //         // Use @ to suppress warnings for invalid URLs
        //         $imageContents = @file_get_contents($imageUrl);

        //         if ($imageContents) {
        //             $destinationPath = public_path('uploads/products/thumbnails');
                    
        //             // Create directory if it doesn't exist
        //             if (!File::isDirectory($destinationPath)) {
        //                 File::makeDirectory($destinationPath, 0777, true, true);
        //             }

        //             // Generate Unique Name
        //             $imageName = time() . '_' . uniqid() . '.webp';

        //             // Resize to 600x600 (Standard for products) using Intervention Image
        //             Image::read($imageContents)->resize(600, 600, function ($constraint) {
        //                 $constraint->aspectRatio();
        //                 $constraint->upsize();
        //             })->save($destinationPath . '/' . $imageName);

        //             // Path to store in DB
        //             $imagePath = 'products/thumbnails/' . $imageName;
                    
        //             // Product images are stored as an array in your system
        //             $imageArray[] = $imagePath;
        //         }
        //     } catch (\Exception $e) {
        //         Log::error('Product Import Image Error (' . $row['product_name'] . '): ' . $e->getMessage());
        //     }
        // }

        // 5. Generate Product Code (Unique)
        // প্রোডাক্ট নেম থেকে প্রিফিক্স নেওয়া হচ্ছে
        $prefix = isset($row['product_name']) ? strtoupper(substr($row['product_name'], 0, 3)) : 'PRD';
        // স্পেশাল ক্যারেক্টার রিমুভ করা
        $prefix = preg_replace('/[^A-Z0-9]/', '', $prefix);
        
        // uniqid() ব্যবহার করে ইউনিক কোড তৈরি (সময় এবং র‍্যান্ডম ভ্যালুর উপর ভিত্তি করে)
        $productCode = $prefix . '-' . strtoupper(uniqid()) . rand(10, 99);

        // 6. Create Product
        $product = Product::create([
            'name'              => $row['product_name'],
            'slug'              => Str::slug($row['product_name']),
            'product_code'      => $productCode,
            'brand_id'          => $brandId,
            'category_id'       => $categoryId,
            'description'       => $row['description'] ?? null,
            'specification'     => $row['specification'] ?? null,
            
            // --- PRICE COLUMNS (With Sanitization) ---
            'purchase_price'    => $sanitizePrice($row['buying_price'] ?? 0),    
            'base_price'        => $sanitizePrice($row['selling_price'] ?? 0),
            'discount_price'    => $sanitizePrice($row['discount_price'] ?? null),

            'status'            => 1,
            // Store the downloaded image array
            'thumbnail_image'   => $imageArray, 
            'main_image'        => $imageArray, // Using same image for main view
            'real_image'        => [],
        ]);

        // 7. Assign Company Category
        if ($companyCategoryId) {
            AssignCategory::create([
                'product_id'    => $product->id,
                'category_id'   => $companyCategoryId,
                'category_name' => $companyCategoryName,
                'type'          => 'company_category'
            ]);
        }

        return $product;
    }
}