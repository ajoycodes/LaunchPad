<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ProductController;
use App\Models\Notification;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminProductController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');

        $query = Product::with(['user', 'category'])->withCount(['upvotes', 'comments']);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $products = $query->orderByDesc('created_at')->paginate(30)->withQueryString();

        $counts = [
            'all'       => Product::count(),
            'pending'   => Product::where('status', 'pending')->count(),
            'approved'  => Product::where('status', 'approved')->count(),
            'rejected'  => Product::where('status', 'rejected')->count(),
            'scheduled' => Product::where('status', 'scheduled')->count(),
        ];

        return view('admin.products', compact('products', 'status', 'counts'));
    }

    public function approve(Product $product)
    {
        $product->update([
            'status'      => 'approved',
            'launch_date' => $product->launch_date ?? now(),
        ]);

        ProductController::awardBadges($product->user);

        Notification::send(
            $product->user_id,
            'approved',
            "Your product '{$product->name}' has been approved and is now live!",
            route('products.show', $product)
        );

        return back()->with('success', "'{$product->name}' approved.");
    }

    public function reject(Product $product)
    {
        $product->update(['status' => 'rejected']);

        Notification::send(
            $product->user_id,
            'rejected',
            "Your product '{$product->name}' was not approved at this time.",
            route('dashboard')
        );

        return back()->with('success', "'{$product->name}' rejected.");
    }

    public function feature(Product $product)
    {
        $product->update(['is_featured' => !$product->is_featured]);

        return back()->with('success', $product->is_featured ? "'{$product->name}' featured." : "'{$product->name}' unfeatured.");
    }

    public function destroy(Product $product)
    {
        if ($product->logo) {
            Storage::disk('public')->delete($product->logo);
        }
        foreach ($product->screenshots as $shot) {
            Storage::disk('public')->delete($shot->image_path);
        }
        $product->delete();

        return back()->with('success', 'Product deleted.');
    }
}
