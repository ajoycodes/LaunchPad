<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
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

        return view('products.show', compact('product', 'comments', 'roastComments'));
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
}
