<?php

namespace App\Imports;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Log;

class BrandsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Expected Headers: company_name, category_name, description, image

        // নাম না থাকলে স্কিপ করবে
        if (empty($row['company_name'])) {
            return null;
        }

        // ১. ক্যাটাগরি নাম দিয়ে টেবিল থেকে ID খুঁজে বের করা
        $categoryId = null;
        if (!empty($row['category_name'])) {
            $categoryName = trim($row['category_name']);
            $category = Category::where('name', $categoryName)->first();

            if ($category) {
                $categoryId = $category->id;
            }
        }

        // ২. ইমেজ ডাউনলোড এবং প্রসেসিং লজিক
        $logoPath = null;
        if (!empty($row['image'])) {
            try {
                $imageUrl = $row['image'];
                // ইমেজের কন্টেন্ট ডাউনলোড করা
                $imageContents = file_get_contents($imageUrl);

                if ($imageContents) {
                    // ফোল্ডার পাথ চেক ও তৈরি করা
                    $destinationPath = public_path('uploads/brands');
                    if (!File::isDirectory($destinationPath)) {
                        File::makeDirectory($destinationPath, 0777, true, true);
                    }

                    // ইউনিক নাম তৈরি (time + uniqid ব্যবহার করা হয়েছে যাতে ডুপ্লিকেট না হয়)
                    $imageName = time() . '_' . uniqid() . '.webp'; // আমরা webp বা jpg ফরম্যাটে সেভ করতে পারি

                    // Intervention Image দিয়ে রিসাইজ এবং সেভ করা (কন্ট্রোলারের লজিক অনুযায়ী 300x300)
                    Image::read($imageContents)->resize(300, 300, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })->save($destinationPath . '/' . $imageName);

                    // ডাটাবেসে সেভ করার পাথ
                    $logoPath = 'uploads/brands/' . $imageName;
                }
            } catch (\Exception $e) {
                // ইমেজ ডাউনলোডে সমস্যা হলে লগ এ রাখা হবে, কিন্তু ইম্পোর্ট বন্ধ হবে না
                Log::error('Brand Import Image Error (' . $row['company_name'] . '): ' . $e->getMessage());
            }
        }

        // ৩. ব্র্যান্ড তৈরি বা আপডেট করা
        // firstOrCreate ব্যবহার করলে যদি ব্র্যান্ড আগে থেকেই থাকে তবে লোগো আপডেট হবে না।
        // আপনি চাইলে updateOrCreate ব্যবহার করতে পারেন যদি ইম্পোর্টের মাধ্যমে লোগো আপডেট করতে চান।
        // বর্তমান লজিক অনুযায়ী নতুন ব্র্যান্ড তৈরির সময় লোগো সেট হবে।
        return Brand::firstOrCreate(
            ['name' => trim($row['company_name'])],
            [
                'category_id' => $categoryId,
                'slug'        => Str::slug($row['company_name']),
                'description' => $row['description'] ?? null,
                'logo'        => $logoPath, // ইমেজের পাথ
                'status'      => 1,
            ]
        );
    }
}