<?php

namespace App\Http\Controllers\seller;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::check() || Auth::user()->role !== 'seller') {
                abort(403);
            }
            return $next($request);
        });
    }

    protected function findSellerCategory($id)
    {
        return Category::withCount('products')
            ->where('seller_id', Auth::id())
            ->find($id);
    }

    // Display seller's categories
    public function index()
    {
        $categories = Category::with('products')
            ->where('seller_id', Auth::id())
            ->withCount('products')
            ->orderBy('name')
            ->paginate(12);

        return view('seller.categories', compact('categories'));
    }

    // Store a new category
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191|unique:categories,name,NULL,id,seller_id,' . Auth::id(),
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $category = Category::create([
                'seller_id' => Auth::id(),
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
            ]);

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('categories/images', 'public');
                $category->update(['image' => $imagePath]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Category created successfully!',
                'category' => $category->load('products')->loadCount('products')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create category: ' . $e->getMessage()
            ], 500);
        }
    }

    // Show category details
    public function show($id)
    {
        $category = $this->findSellerCategory($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'category' => $category
        ]);
    }

    // Update category
    public function update(Request $request, $id)
    {
        $category = $this->findSellerCategory($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191|unique:categories,name,' . $id . ',id,seller_id,' . Auth::id(),
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $category->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
            ]);

            if ($request->hasFile('image')) {
                if ($category->image && Storage::disk('public')->exists($category->image)) {
                    Storage::disk('public')->delete($category->image);
                }

                $imagePath = $request->file('image')->store('categories/images', 'public');
                $category->update(['image' => $imagePath]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully!',
                'category' => $category->load('products')->loadCount('products')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update category: ' . $e->getMessage()
            ], 500);
        }
    }

    // Delete category
    public function destroy($id)
    {
        $category = $this->findSellerCategory($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        // Check if category has products
        if ($category->products_count > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete category that contains products. Please remove all products first.'
            ], 422);
        }

        try {
            $category->delete();

            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete category: ' . $e->getMessage()
            ], 500);
        }
    }
}
