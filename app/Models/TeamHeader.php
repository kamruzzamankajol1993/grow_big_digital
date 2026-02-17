<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class TeamHeader extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle_one',
        'subtitle_two',
    ];
}
