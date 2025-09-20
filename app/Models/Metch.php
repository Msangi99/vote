<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Metch extends Model
{
    protected $table = "matches";
    protected $fillable = [
        'away',
        'home',
        'match_date',
    ];

    public function homeTeam()
    {
        return $this->belongsTo(Team::class, 'home', 'id');
    }

    public function awayTeam()
    {
        return $this->belongsTo(Team::class, 'away', 'id');
    }
}
