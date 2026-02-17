<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class GrowBigTeam extends Model
{
    use HasFactory;

    protected $fillable = ['image', 'name', 'designation', 'skills'];

    protected $casts = [
        'skills' => 'array', // Automatically handles JSON conversion
    ];

    public function socialLinks()
    {
        return $this->hasMany(TeamSocialLink::class, 'team_id');
    }
}
