<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class League extends Model
{
    protected $table = "leagues";

    protected $fillable = [
        'name',
    ];

    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    public function matches()
    {
        return $this->hasManyThrough(
            Metch::class, // The final model we wish to access
            Team::class,  // The intermediate model
            'league_id',  // Foreign key on the teams table...
            'home',       // Foreign key on the matches table...
            'id',         // Local key on the leagues table...
            'id'          // Local key on the teams table...
        )->orWhereHas('awayTeam', function ($query) {
            $query->where('league_id', $this->id);
        });
    }
}
