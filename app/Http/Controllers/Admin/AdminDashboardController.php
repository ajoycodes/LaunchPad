<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Upvote;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $pendingCount  = Product::where('status', 'pending')->count();
        $totalUsers    = User::count();
        $upvotesToday  = Upvote::whereDate('created_at', today())->count();

        // New signups last 7 days
        $signupsRaw = User::where('created_at', '>=', now()->subDays(6))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date');

        // Submissions last 7 days
        $submissionsRaw = Product::where('created_at', '>=', now()->subDays(6))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date');

        $chartDates      = [];
        $signupCounts    = [];
        $submissionCounts = [];

        for ($i = 6; $i >= 0; $i--) {
            $date                 = now()->subDays($i)->format('Y-m-d');
            $chartDates[]         = now()->subDays($i)->format('M j');
            $signupCounts[]       = $signupsRaw[$date] ?? 0;
            $submissionCounts[]   = $submissionsRaw[$date] ?? 0;
        }

        return view('admin.dashboard', compact(
            'totalProducts', 'pendingCount', 'totalUsers', 'upvotesToday',
            'chartDates', 'signupCounts', 'submissionCounts'
        ));
    }
}
