<?php

namespace App\Imports;

use App\Models\CompanyCategory;
use App\Models\Brand;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Log;

class CompanyCategoriesImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Expected Headers: company_name, category_name, parent_category, description, image

        // 1. Skip if required fields are missing
        if (empty($row['category_name']) || empty($row['company_name'])) {
            return null;
        }

        // 2. Find or Create Company
        $companyName = trim($row['company_name']);
        $company = Brand::firstOrCreate(
            ['name' => $companyName],
            ['slug' => Str::slug($companyName), 'status' => 1]
        );

        // 3. Handle Parent Category
        $parentId = null;
        if (isset($row['parent_category']) && !empty($row['parent_category'])) {
            $parentName = trim($row['parent_category']);
            $parentCategory = CompanyCategory::firstOrCreate(
                [
                    'company_id' => $company->id,
                    'name'       => $parentName,
                ],
                [
                    'slug'        => Str::slug($parentName),
                    'status'      => 1
                ]
            );
            $parentId = $parentCategory->id;
        }

        // 4. Handle Image Download
        $imagePath = null;
        if (!empty($row['image'])) {
            try {
                $imageUrl = $row['image'];
                $imageContents = file_get_contents($imageUrl);

                if ($imageContents) {
                    $destinationPath = public_path('uploads/company_category');
                    if (!File::isDirectory($destinationPath)) {
                        File::makeDirectory($destinationPath, 0777, true, true);
                    }

                    $imageName = time() . '_' . uniqid() . '.webp';
                    
                    // Resize to 400x400 as per your standard
                    Image::read($imageContents)->resize(400, 400, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })->save($destinationPath . '/' . $imageName);

                    $imagePath = 'uploads/company_category/' . $imageName;
                }
            } catch (\Exception $e) {
                Log::error('Company Category Import Image Error: ' . $e->getMessage());
            }
        }

        // 5. Create or Update Category
        return CompanyCategory::firstOrCreate(
            [
                'company_id' => $company->id,
                'name'       => trim($row['category_name']),
            ],
            [
                'parent_id'   => $parentId,
                'slug'        => Str::slug($row['category_name']),
                'description' => $row['description'] ?? null,
                'image'       => $imagePath, // Save image path
                'status'      => 1,
            ]
        );
    }
}