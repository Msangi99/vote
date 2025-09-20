<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $table = "teams";
    protected $fillable = [
        'name',
        'league_id',
        'coach_name',
    ];

    public function players(){
        return $this->hasMany(Player::class);
    }

    public function league(){
        return $this->belongsTo(League::class);
    }
}
