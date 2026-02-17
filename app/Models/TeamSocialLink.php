<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class TeamSocialLink extends Model
{
    use HasFactory;

    protected $fillable = ['team_id', 'title', 'link'];

    public function team()
    {
        return $this->belongsTo(GrowBigTeam::class);
    }
}
