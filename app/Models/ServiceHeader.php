<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceHeader extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle_one',
        'subtitle_two',
    ];
}