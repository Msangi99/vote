<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $table = "players";
    protected $fillable = [
        'name',
        'team_id',
        'position',
        'votes',
        'match_id',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
