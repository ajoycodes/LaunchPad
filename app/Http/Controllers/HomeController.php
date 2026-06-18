<?php

namespace App\Http\Controllers;

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
            ->with(['user', 'category', 'tags']);

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

        $products = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        $categories = Category::orderBy('name')->get();

        $featured = Product::where('status', 'approved')
            ->where('is_featured', true)
            ->with(['user', 'category'])
            ->latest()
            ->first();

        $popularTags = Tag::withCount('products')
            ->having('products_count', '>', 0)
            ->orderByDesc('products_count')
            ->limit(10)
            ->get();

        $topMakers = User::where('role', 'maker')
            ->withCount(['products' => fn ($q) => $q
                ->where('status', 'approved')
                ->where('launch_date', '>=', now()->subDays(7))
            ])
            ->having('products_count', '>', 0)
            ->orderByDesc('products_count')
            ->limit(5)
            ->get();

        return view('home.index', compact(
            'products', 'categories', 'tab', 'search', 'catSlug',
            'featured', 'popularTags', 'topMakers'
        ));
    }
}
