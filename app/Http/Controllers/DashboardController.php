<?php

namespace App\Http\Controllers;

use App\Models\Upvote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $products = $user->products()
            ->with(['category'])
            ->withCount(['upvotes', 'comments', 'updates'])
            ->orderByDesc('created_at')
            ->get();

        $totalViews    = $products->sum('views_count');
        $totalUpvotes  = $products->sum('upvotes_count');
        $totalProducts = $products->count();
        $totalComments = $products->sum('comments_count');

        $productIds = $products->pluck('id');

        // Upvotes per day over last 30 days
        $upvoteHistory = Upvote::whereIn('product_id', $productIds)
            ->where('created_at', '>=', now()->subDays(29))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date');

        $upvoteDates  = [];
        $upvoteCounts = [];
        for ($i = 29; $i >= 0; $i--) {
            $date           = now()->subDays($i)->format('Y-m-d');
            $upvoteDates[]  = now()->subDays($i)->format('M j');
            $upvoteCounts[] = $upvoteHistory[$date] ?? 0;
        }

        // Views per product (top 10)
        $viewsData = $products->sortByDesc('views_count')
            ->take(10)
            ->map(fn($p) => ['name' => $p->name, 'views' => $p->views_count]);

        return view('dashboard.index', compact(
            'products',
            'totalViews', 'totalUpvotes', 'totalProducts', 'totalComments',
            'upvoteDates', 'upvoteCounts',
            'viewsData'
        ));
    }
}
