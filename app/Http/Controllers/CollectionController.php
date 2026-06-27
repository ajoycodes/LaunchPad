<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    public function index()
    {
        $collections = Collection::where('is_public', true)
            ->with('user')
            ->withCount(['products', 'followers'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('collections.index', compact('collections'));
    }

    public function show(string $slug)
    {
        $collection = Collection::where('slug', $slug)->firstOrFail();

        if (!$collection->is_public && auth()->id() !== $collection->user_id) {
            abort(403);
        }

        $collection->load(['user', 'products' => function ($q) {
            $q->where('status', 'approved')->with(['user', 'category', 'tags'])->withCount('upvotes');
        }]);
        $collection->loadCount('followers');

        $isFollowing = auth()->check() && $collection->isFollowedBy(auth()->user());

        return view('collections.show', compact('collection', 'isFollowing'));
    }

    public function create()
    {
        return view('collections.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'is_public'   => ['boolean'],
        ]);

        $collection = $request->user()->collections()->create($data);

        return redirect()->route('collections.show', $collection->slug)
            ->with('success', 'Collection created!');
    }

    public function addProduct(Request $request, Collection $collection)
    {
        abort_unless(auth()->id() === $collection->user_id, 403);

        $request->validate(['product_id' => ['required', 'exists:products,id']]);

        $collection->products()->syncWithoutDetaching([$request->product_id => ['added_at' => now()]]);

        return response()->json(['added' => true]);
    }

    public function follow(Request $request, Collection $collection)
    {
        $user = $request->user();

        if ($collection->followers()->where('user_id', $user->id)->exists()) {
            $collection->followers()->detach($user->id);
            $following = false;
        } else {
            $collection->followers()->attach($user->id);
            $following = true;
        }

        return response()->json([
            'following' => $following,
            'count'     => $collection->followers()->count(),
        ]);
    }

    public function destroy(Request $request, Collection $collection)
    {
        abort_unless(auth()->id() === $collection->user_id, 403);

        $collection->delete();

        return redirect()->route('collections.index')->with('success', 'Collection deleted.');
    }
}
