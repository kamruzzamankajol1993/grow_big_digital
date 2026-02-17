<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class PortfolioHeader extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle_one',
        'subtitle_two',
    ];
}
