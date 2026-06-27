<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductUpdateController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'body'       => ['required', 'string', 'max:1000'],
        ]);

        $product = $request->user()->products()->findOrFail($data['product_id']);

        $product->updates()->create([
            'user_id' => $request->user()->id,
            'body'    => $data['body'],
        ]);

        return back()->with('success', 'Update posted!');
    }
}
