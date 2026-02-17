<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class WhoWeAreList extends Model
{
    use HasFactory;

    protected $fillable = ['who_we_are_id', 'icon', 'title', 'short_description'];

    public function whoWeAre()
    {
        return $this->belongsTo(GrowBigWhoWeAre::class, 'who_we_are_id');
    }
}
