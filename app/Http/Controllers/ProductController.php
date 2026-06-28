<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Badge;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $tags       = Tag::orderBy('name')->get();

        return view('products.create', compact('categories', 'tags'));
    }

    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
        }

        $product = Product::create([
            'user_id'          => $request->user()->id,
            'name'             => $data['name'],
            'tagline'          => $data['tagline'],
            'description'      => $data['description'],
            'category_id'      => $data['category_id'],
            'website_url'      => $data['website_url'] ?? null,
            'demo_url'         => $data['demo_url'] ?? null,
            'github_url'       => $data['github_url'] ?? null,
            'logo'             => $logoPath,
            'is_roast_enabled' => $request->boolean('is_roast_enabled'),
            'launch_date'      => $data['launch_type'] === 'scheduled' ? $data['launch_date'] : now(),
            'status'           => 'pending',
        ]);

        if (!empty($data['tags'])) {
            $product->tags()->sync($data['tags']);
        }

        if ($request->hasFile('screenshots')) {
            $order = 0;
            foreach ($request->file('screenshots') as $file) {
                $path = $file->store('screenshots', 'public');
                $product->screenshots()->create(['image_path' => $path, 'order' => $order++]);
            }
        }

        return redirect()->route('products.show', $product)
            ->with('success', 'Your product has been submitted for review!');
    }

    public function show(Product $product)
    {
        $product->loadMissing(['user', 'category', 'tags', 'screenshots']);
        $product->loadCount('upvotes');
        $product->increment('views_count');

        $comments = $product->comments()
            ->with(['user', 'replies.user'])
            ->whereNull('parent_id')
            ->where('is_roast', false)
            ->orderBy('created_at')
            ->get();

        $roastComments = $product->is_roast_enabled
            ? $product->comments()
                ->with(['user', 'replies.user'])
                ->whereNull('parent_id')
                ->where('is_roast', true)
                ->orderBy('created_at')
                ->get()
            : collect();

        $buildLog = $product->updates()->latest()->get();

        return view('products.show', compact('product', 'comments', 'roastComments', 'buildLog'));
    }

    public function edit(Product $product)
    {
        $this->authorize('update', $product);

        $categories = Category::orderBy('name')->get();
        $tags       = Tag::orderBy('name')->get();

        return view('products.edit', compact('product', 'categories', 'tags'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            if ($product->logo) {
                Storage::disk('public')->delete($product->logo);
            }
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $product->update([
            'name'             => $data['name'],
            'tagline'          => $data['tagline'],
            'description'      => $data['description'],
            'category_id'      => $data['category_id'],
            'website_url'      => $data['website_url'] ?? null,
            'demo_url'         => $data['demo_url'] ?? null,
            'github_url'       => $data['github_url'] ?? null,
            'logo'             => $data['logo'] ?? $product->logo,
            'is_roast_enabled' => $request->boolean('is_roast_enabled'),
            'launch_date'      => $data['launch_type'] === 'scheduled' ? $data['launch_date'] : $product->launch_date,
        ]);

        $product->tags()->sync($data['tags'] ?? []);

        if ($request->hasFile('screenshots')) {
            $order = $product->screenshots()->max('order') + 1;
            foreach ($request->file('screenshots') as $file) {
                $path = $file->store('screenshots', 'public');
                $product->screenshots()->create(['image_path' => $path, 'order' => $order++]);
            }
        }

        return redirect()->route('products.show', $product)
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Request $request, Product $product)
    {
        $this->authorize('delete', $product);

        if ($product->logo) {
            Storage::disk('public')->delete($product->logo);
        }

        foreach ($product->screenshots as $shot) {
            Storage::disk('public')->delete($shot->image_path);
        }

        $product->delete();

        return redirect()->route('home')
            ->with('success', 'Product deleted.');
    }

    public static function awardBadges(User $user): void
    {
        $approvedCount = $user->products()->where('status', 'approved')->count();

        if ($approvedCount >= 1) {
            Badge::firstOrCreate(
                ['user_id' => $user->id, 'type' => 'first_launch'],
                ['label' => 'First Launch', 'icon' => 'rocket', 'earned_at' => now()]
            );
        }

        if ($approvedCount >= 3) {
            Badge::firstOrCreate(
                ['user_id' => $user->id, 'type' => 'streak_3'],
                ['label' => '3 Launches', 'icon' => 'flame', 'earned_at' => now()]
            );
        }

        $upvoteThreshold = $user->products()
            ->where('status', 'approved')
            ->withCount('upvotes')
            ->having('upvotes_count', '>=', 50)
            ->exists();

        if ($upvoteThreshold) {
            Badge::firstOrCreate(
                ['user_id' => $user->id, 'type' => 'community_fav'],
                ['label' => 'Community Fav', 'icon' => 'heart', 'earned_at' => now()]
            );
        }
    }
}
