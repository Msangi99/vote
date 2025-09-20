<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $fillable = [
        'user_id',
        'match_id',
        'category_id',
        'player_id',
        'team_id',
        'coach_name',
        'price',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function match()
    {
        return $this->belongsTo(Metch::class, 'match_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function coach()
    {
        return $this->belongsTo(Team::class);
    }
}
