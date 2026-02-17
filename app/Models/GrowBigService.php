<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrowBigService extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'icon',
        'name',
        'short_description',
        'status'
    ];

    /**
     * Get the parent service.
     */
    public function parent()
    {
        return $this->belongsTo(GrowBigService::class, 'parent_id');
    }

    /**
     * Get the sub-services (children).
     */
    public function children()
    {
        return $this->hasMany(GrowBigService::class, 'parent_id');
    }
}
