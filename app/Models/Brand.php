<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', // নতুন কলাম
        'name',
        'slug',
        'logo',
        'description',
        'status',
    ];

    // ক্যাটাগরি রিলেশন
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($brand) {
            if (empty($brand->slug)) {
                $brand->slug = Str::slug($brand->name);
            }
        });
    }

    public function companyCategories()
{
    // CompanyCategory মডেলে 'company_id' ফরেন কি হিসেবে আছে
    return $this->hasMany(CompanyCategory::class, 'company_id');
}
}