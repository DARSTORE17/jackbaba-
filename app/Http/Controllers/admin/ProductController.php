<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\admin\AdminController;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductDescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ProductController extends AdminController
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $products = Product::with(['category', 'seller'])
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%')
                      ->orWhere('slug', 'like', '%'.$search.'%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.products', compact('products', 'search'));
    }

    public function edit($id)
    {
        $product = Product::with(['category', 'seller', 'description', 'media'])->findOrFail($id);
        $categories = Category::all();

        return view('admin.product-edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:191',
            'old_price' => 'nullable|numeric|min:0',
            'new_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'specifications' => 'nullable|string',
            'details' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $product->update([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'old_price' => $request->old_price,
                'new_price' => $request->new_price,
                'discount' => $request->old_price ? round((($request->old_price - $request->new_price) / $request->old_price) * 100) : 0,
                'stock' => $request->stock,
            ]);

            if ($request->hasFile('thumbnail')) {
                if ($product->thumbnail && Storage::exists('public/' . $product->thumbnail)) {
                    Storage::delete('public/' . $product->thumbnail);
                }
                $thumbnailPath = $request->file('thumbnail')->store('products/thumbnails', 'public');
                $product->update(['thumbnail' => $thumbnailPath]);
            }

            // Update or create description
            ProductDescription::updateOrCreate(
                ['product_id' => $product->id],
                [
                    'description' => $request->description,
                    'specifications' => $request->specifications,
                    'details' => $request->details,
                ]
            );

            DB::commit();

            return redirect()->route('admin.products')->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update product: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        DB::beginTransaction();

        try {
            if ($product->thumbnail && Storage::exists('public/' . $product->thumbnail)) {
                Storage::delete('public/' . $product->thumbnail);
            }

            $product->media()->delete();
            $product->description()->delete();
            $product->delete();

            DB::commit();

            return redirect()->route('admin.products')->with('success', 'Product has been removed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to delete product: ' . $e->getMessage());
        }
    }
}
