<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrowBigTestimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'name',
        'designation',
        'short_description',
        'link',
    ];
}