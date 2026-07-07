<?php

namespace App\Http\Controllers;

use App\Models\Battle;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $tab     = $request->query('tab', 'today');
        $catSlug = $request->query('category');
        $search  = $request->query('q');

        $query = Product::where('status', 'approved')
            ->with(['user', 'category', 'tags'])
            ->withCount('upvotes');

        match ($tab) {
            'week'    => $query->where('launch_date', '>=', now()->subDays(7)),
            'alltime' => $query,
            default   => $query->whereDate('launch_date', today()),
        };

        if ($catSlug) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $catSlug));
        }

        if ($search) {
            $query->where(fn ($q) => $q
                ->where('name', 'like', "%{$search}%")
                ->orWhere('tagline', 'like', "%{$search}%")
            );
        }

        $products = $query->orderByDesc('upvotes_count')->orderByDesc('created_at')->paginate(20)->withQueryString();

        $categories = Category::orderBy('name')->get();

        $featured = Product::where('status', 'approved')
            ->where('is_featured', true)
            ->with(['user', 'category'])
            ->latest()
            ->first();

        // has() + withCount() (rather than withCount()->having()) keeps this
        // portable: SQLite rejects a HAVING clause with no GROUP BY or
        // top-level aggregate, which withCount()'s subquery column isn't.
        $popularTags = Tag::has('products')
            ->withCount('products')
            ->orderByDesc('products_count')
            ->limit(10)
            ->get();

        $recentMakerProducts = fn ($q) => $q
            ->where('status', 'approved')
            ->where('launch_date', '>=', now()->subDays(7));

        $topMakers = User::where('role', 'maker')
            ->whereHas('products', $recentMakerProducts)
            ->withCount(['products' => $recentMakerProducts])
            ->orderByDesc('products_count')
            ->limit(5)
            ->get();

        $activeBattle = Battle::current();

        return view('home.index', compact(
            'products', 'categories', 'tab', 'search', 'catSlug',
            'featured', 'popularTags', 'topMakers', 'activeBattle'
        ));
    }
}
