<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UpvoteController extends Controller
{
    public function toggle(Request $request, Product $product): JsonResponse
    {
        $user     = $request->user();
        $existing = $product->upvotes()->where('user_id', $user->id)->first();

        if ($existing) {
            $existing->delete();
            $upvoted = false;
        } else {
            $product->upvotes()->create(['user_id' => $user->id]);
            $upvoted = true;
        }

        return response()->json([
            'upvoted' => $upvoted,
            'count'   => $product->upvotes()->count(),
        ]);
    }
}
