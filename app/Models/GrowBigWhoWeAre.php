<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class GrowBigWhoWeAre extends Model
{
    use HasFactory;

    protected $fillable = [
        'image', 'title', 'subtitle_one', 'subtitle_two','button_name', 
        'subtitle_three', 'short_description', 'edit_count_text'
    ];

    public function listItems()
    {
        return $this->hasMany(WhoWeAreList::class, 'who_we_are_id');
    }
}
