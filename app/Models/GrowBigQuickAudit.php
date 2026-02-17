<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrowBigQuickAudit extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'service',
        'is_read',
        'profile_or_social_url',
        'email',
    ];
}
