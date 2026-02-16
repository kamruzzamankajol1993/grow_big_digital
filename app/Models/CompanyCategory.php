<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CompanyCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'parent_id', // নতুন ফিল্ড
        'name',
        'slug',
        'description',
        'image',
        'status',
    ];

    // কোম্পানি রিলেশন
    public function company()
    {
        return $this->belongsTo(Brand::class, 'company_id');
    }

    // Parent Category রিলেশন
    public function parent()
    {
        return $this->belongsTo(CompanyCategory::class, 'parent_id');
    }

    // Child Categories রিলেশন
    public function children()
    {
        return $this->hasMany(CompanyCategory::class, 'parent_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($item) {
            if (empty($item->slug)) { $item->slug = Str::slug($item->name); }
        });
        static::updating(function ($item) {
             if ($item->isDirty('name')) { $item->slug = Str::slug($item->name); }
        });
    }

    public function products()
{
    // AssignCategory মডেলের মাধ্যমে প্রোডাক্ট রিলেশন
    // আমরা ধরে নিচ্ছি AssignCategory টেবিলে 'category_id' দিয়ে লিংক করা আছে
    return $this->belongsToMany(Product::class, 'assign_categories', 'category_id', 'product_id');
}
}