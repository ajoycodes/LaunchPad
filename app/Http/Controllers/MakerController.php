<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class MakerController extends Controller
{
    public function show(string $username)
    {
        $maker = User::where('username', $username)->firstOrFail();

        $products = $maker->products()
            ->where('status', 'approved')
            ->with(['category', 'tags'])
            ->withCount('upvotes')
            ->orderByDesc('launch_date')
            ->get();

        $badges = $maker->badges;

        $publicCollections = $maker->collections()
            ->where('is_public', true)
            ->withCount('products')
            ->get();

        $totalUpvotes = $products->sum('upvotes_count');

        return view('makers.profile', compact('maker', 'products', 'badges', 'publicCollections', 'totalUpvotes'));
    }
}
