<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'image',
        'video',
        'video_link',
        'title',
        'subtitle',
        'description',
        'video_type',
    ];

    /**
     * Get the service associated with the portfolio item.
     */
    public function service()
    {
        return $this->belongsTo(GrowBigService::class);
    }
}
