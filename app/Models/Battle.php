<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Battle extends Model
{
    protected $table    = 'maker_battles';
    protected $fillable = ['product_a_id', 'product_b_id', 'starts_at', 'ends_at', 'votes_a', 'votes_b'];
    protected $casts    = ['starts_at' => 'datetime', 'ends_at' => 'datetime'];

    public function productA()
    {
        return $this->belongsTo(Product::class, 'product_a_id');
    }

    public function productB()
    {
        return $this->belongsTo(Product::class, 'product_b_id');
    }

    public function votes()
    {
        return $this->hasMany(BattleVote::class);
    }

    public function isActive(): bool
    {
        return now()->between($this->starts_at, $this->ends_at);
    }

    public function userVote(int $userId): ?BattleVote
    {
        return $this->votes()->where('user_id', $userId)->first();
    }

    public function totalVotes(): int
    {
        return $this->votes_a + $this->votes_b;
    }

    public function percentA(): float
    {
        $total = $this->totalVotes();
        return $total > 0 ? round(($this->votes_a / $total) * 100, 1) : 50;
    }

    public function percentB(): float
    {
        $total = $this->totalVotes();
        return $total > 0 ? round(($this->votes_b / $total) * 100, 1) : 50;
    }

    public static function current(): ?self
    {
        return static::where('starts_at', '<=', now())
            ->where('ends_at', '>=', now())
            ->with(['productA.user', 'productB.user'])
            ->latest('starts_at')
            ->first();
    }
}
