<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use App\Models\Notification;
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

            if ($product->user_id !== $user->id) {
                Notification::send(
                    $product->user_id,
                    'upvote',
                    "{$user->name} upvoted your product "{$product->name}".",
                    route('products.show', $product)
                );
            }

            $this->maybeAwardTop5Badge($product);
            $this->maybeAwardCommunityFavBadge($product);
        }

        return response()->json([
            'upvoted' => $upvoted,
            'count'   => $product->upvotes()->count(),
        ]);
    }

    private function maybeAwardTop5Badge(Product $product): void
    {
        $rank = Product::where('status', 'approved')
            ->whereDate('launch_date', today())
            ->withCount('upvotes')
            ->orderByDesc('upvotes_count')
            ->pluck('id')
            ->search($product->id);

        if ($rank !== false && $rank < 5) {
            Badge::firstOrCreate(
                ['user_id' => $product->user_id, 'type' => 'top5_day'],
                ['label' => 'Top 5 Today', 'icon' => 'star', 'earned_at' => now()]
            );
        }
    }

    private function maybeAwardCommunityFavBadge(Product $product): void
    {
        $count = $product->upvotes()->count();

        if ($count >= 50) {
            Badge::firstOrCreate(
                ['user_id' => $product->user_id, 'type' => 'community_fav'],
                ['label' => 'Community Fav', 'icon' => 'heart', 'earned_at' => now()]
            );
        }
    }
}
