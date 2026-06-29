<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BattleVote extends Model
{
    public $timestamps = false;

    protected $fillable = ['user_id', 'battle_id', 'voted_for'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function battle()
    {
        return $this->belongsTo(Battle::class);
    }
}
