<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LaunchCalendarController extends Controller
{
    public function index()
    {
        $products = \App\Models\Product::where('status', 'scheduled')
            ->where('launch_date', '>', now())
            ->with(['user', 'category'])
            ->withCount('upvotes')
            ->orderBy('launch_date')
            ->get();

        return view('launch-calendar.index', compact('products'));
    }
}
