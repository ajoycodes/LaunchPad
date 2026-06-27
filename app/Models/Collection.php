<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Collection extends Model
{
    protected $fillable = ['user_id', 'name', 'slug', 'description', 'is_public'];

    protected $casts = ['is_public' => 'boolean'];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function (Collection $collection): void {
            if (empty($collection->slug)) {
                $collection->slug = static::uniqueSlug($collection->name);
            }
        });
    }

    private static function uniqueSlug(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i    = 1;
        while (static::where('slug', $slug)->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }
        return $slug;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'collection_products')
            ->withPivot('added_at')
            ->orderByDesc('collection_products.added_at');
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'collection_follows')
            ->withPivot('created_at');
    }

    public function isFollowedBy(User $user): bool
    {
        return $this->followers()->where('user_id', $user->id)->exists();
    }
}
