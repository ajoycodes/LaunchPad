<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Notification;
use App\Models\Product;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $data = $request->validate([
            'body'      => ['required', 'string', 'max:1000'],
            'parent_id' => ['nullable', 'exists:comments,id'],
            'is_roast'  => ['boolean'],
        ]);

        $user = $request->user();

        $product->comments()->create([
            'user_id'   => $user->id,
            'parent_id' => $data['parent_id'] ?? null,
            'body'      => $data['body'],
            'is_roast'  => $data['is_roast'] ?? false,
        ]);

        if ($product->user_id !== $user->id) {
            Notification::send(
                $product->user_id,
                'comment',
                "{$user->name} commented on your product "{$product->name}".",
                route('products.show', $product)
            );
        }

        return back()->with('success', 'Comment posted.');
    }

    public function destroy(Request $request, Comment $comment)
    {
        $user = $request->user();

        if ($user->id !== $comment->user_id && ! $user->isAdmin()) {
            abort(403);
        }

        $comment->delete();

        return back()->with('success', 'Comment deleted.');
    }
}
