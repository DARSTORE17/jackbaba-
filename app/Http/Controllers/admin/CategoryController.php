<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\admin\AdminController;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends AdminController
{
    public function index()
    {
        $categories = Category::withCount('products')
            ->whereNull('seller_id')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.categories', compact('categories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191|unique:categories,name',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $category = Category::create([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name),
            'description' => $request->description,
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories/images', 'public');
            $category->update(['image' => $imagePath]);
        }

        return redirect()->route('admin.categories')->with('success', 'Category saved successfully.');
    }

    public function update(Request $request, $id)
    {
        $category = Category::whereNull('seller_id')->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $category->update([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name),
            'description' => $request->description,
        ]);

        if ($request->hasFile('image')) {
            if ($category->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($category->image)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($category->image);
            }

            $imagePath = $request->file('image')->store('categories/images', 'public');
            $category->update(['image' => $imagePath]);
        }

        return redirect()->route('admin.categories')->with('success', 'Category updated successfully.');
    }

    public function destroy($id)
    {
        $category = Category::whereNull('seller_id')->withCount('products')->findOrFail($id);

        if ($category->products_count > 0) {
            return redirect()->route('admin.categories')->with('error', 'Remove products from this category before deleting it.');
        }

        $category->delete();

        return redirect()->route('admin.categories')->with('success', 'Category deleted successfully.');
    }
}
