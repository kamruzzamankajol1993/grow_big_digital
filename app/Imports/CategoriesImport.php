<?php

namespace App\Imports;

use App\Models\Category;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CategoriesImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Expected Headers: category_name, parent_category_name, description

        if (empty($row['category_name'])) {
            return null;
        }

        // 1. Handle Parent Category Lookup
        $parentId = null;
        if (!empty($row['parent_category_name'])) {
            // Try to find the parent category by name
            $parent = Category::where('name', trim($row['parent_category_name']))->first();
            if ($parent) {
                $parentId = $parent->id;
            }
        }

        // 2. Create or Find the Category
        return Category::firstOrCreate(
            ['name' => trim($row['category_name'])], // Check uniqueness by name
            [
                'slug'        => Str::slug($row['category_name']),
                'parent_id'   => $parentId,
                'description' => $row['description'] ?? null,
                'status'      => 1,
            ]
        );
    }
}