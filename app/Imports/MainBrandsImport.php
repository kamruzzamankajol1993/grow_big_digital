<?php

namespace App\Imports;
use App\Models\Brand;
use App\Models\MainBrand;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MainBrandsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Expected Headers: brand_name, description

        if (empty($row['brand_name'])) {
            return null;
        }

        return MainBrand::firstOrCreate(
            ['name' => trim($row['brand_name'])], // Check uniqueness by name
            [
                'slug'        => Str::slug($row['brand_name']),
                'description' => $row['description'] ?? null,
                'status'      => 1, // Default active
            ]
        );
    }
}