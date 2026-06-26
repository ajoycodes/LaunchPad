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

        $totalUpvotes = $products->sum('upvotes_count');

        return view('makers.profile', compact('maker', 'products', 'badges', 'totalUpvotes'));
    }
}
