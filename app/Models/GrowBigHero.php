<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrowBigHero extends Model
{
    use HasFactory;

    protected $fillable = [
        'main_title', 'subtitle', 'button_name_one', 'button_name_two',
        'member_one_image', 'member_one_name', 'member_one_designation', 'member_one_icon',
        'member_two_image', 'member_two_name', 'member_two_designation', 'member_two_icon',
        'member_three_image', 'member_three_name', 'member_three_designation', 'member_three_icon',
        'success_count', 'success_text', 'success_icon',
        'client_count', 'client_text', 'client_icon',
        'positive_count', 'positive_text', 'positive_icon'
    ];
}