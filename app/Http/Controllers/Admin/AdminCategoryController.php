<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->orderBy('name')->get();

        return view('admin.categories', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:60|unique:categories,name',
            'icon' => 'required|string|max:60',
        ]);

        $data['slug'] = Str::slug($data['name']);

        Category::create($data);

        return back()->with('success', "Category '{$data['name']}' created.");
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => 'required|string|max:60|unique:categories,name,' . $category->id,
            'icon' => 'required|string|max:60',
        ]);

        $data['slug'] = Str::slug($data['name']);

        $category->update($data);

        return back()->with('success', "Category updated.");
    }

    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            return back()->with('error', "Cannot delete a category that has products.");
        }

        $category->delete();

        return back()->with('success', "Category deleted.");
    }
}
