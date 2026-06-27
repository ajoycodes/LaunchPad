<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'user_id', 'name', 'slug', 'tagline', 'description',
        'logo', 'category_id', 'website_url', 'demo_url', 'github_url',
        'status', 'is_roast_enabled', 'is_featured', 'launch_date', 'views_count',
    ];

    protected $casts = [
        'is_roast_enabled' => 'boolean',
        'is_featured'      => 'boolean',
        'launch_date'      => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Product $product): void {
            if (empty($product->slug)) {
                $product->slug = static::uniqueSlug($product->name);
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

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tags');
    }

    public function screenshots()
    {
        return $this->hasMany(ProductScreenshot::class)->orderBy('order');
    }

    public function upvotes()
    {
        return $this->hasMany(Upvote::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function updates()
    {
        return $this->hasMany(ProductUpdate::class)->orderByDesc('created_at');
    }

    public function isUpvotedBy(User $user): bool
    {
        return $this->upvotes()->where('user_id', $user->id)->exists();
    }
}
