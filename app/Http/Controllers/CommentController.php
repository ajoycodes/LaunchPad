<?php

namespace App\Http\Controllers;

use App\Models\Comment;
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

        $product->comments()->create([
            'user_id'   => $request->user()->id,
            'parent_id' => $data['parent_id'] ?? null,
            'body'      => $data['body'],
            'is_roast'  => $data['is_roast'] ?? false,
        ]);

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
