<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Battle;
use App\Models\Product;
use Illuminate\Http\Request;

class AdminBattleController extends Controller
{
    public function create()
    {
        $products = Product::where('status', 'approved')
            ->with('user')
            ->orderBy('name')
            ->get();

        return view('admin.battles.create', compact('products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_a_id' => 'required|exists:products,id',
            'product_b_id' => 'required|exists:products,id|different:product_a_id',
            'starts_at'    => 'required|date|after_or_equal:today',
            'ends_at'      => 'required|date|after:starts_at',
        ]);

        $data['votes_a'] = 0;
        $data['votes_b'] = 0;

        Battle::create($data);

        return redirect()->route('battles.show')->with('success', 'Battle created.');
    }
}
