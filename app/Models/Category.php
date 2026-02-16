<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'name',
        'description',
        'slug',
        'image',
        'status',
    ];

    protected $guarded = ['id'];

    // Automatically create a slug from the name
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('name')) {
                 $category->slug = Str::slug($category->name);
            }
        });
    }

     public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get the child categories.
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // --- এই নতুন ফাংশনটি যোগ করুন ---
    public function brands()
    {
        return $this->hasMany(Brand::class, 'category_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
