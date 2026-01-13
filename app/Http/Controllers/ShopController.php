<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'media', 'description']);

        // Only show products with stock > 0
        $query->where('stock', '>', 0);

        // Search
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('slug', 'like', '%' . $search . '%')
                  ->orWhereHas('description', function($desc) use ($search) {
                      $desc->where('description', 'like', '%' . $search . '%')
                          ->orWhere('specifications', 'like', '%' . $search . '%')
                          ->orWhere('details', 'like', '%' . $search . '%');
                  });
            });
        }
        // Category filter
        if ($request->has('category') && !empty($request->category)) {
            $query->where('category_id', (int)$request->category);
        }

        // Rating filter
        if ($request->has('rating') && !empty($request->rating)) {
            $query->where('rate', '>=', $request->rating);
        }

        // Discount filter
        if ($request->has('on_sale') && $request->on_sale == '1') {
            $query->where('discount', '>', 0);
        }



        $products = $query->paginate(24)->withQueryString();

        $categories = Category::withCount(['products' => function ($query) {
            $query->where('stock', '>', 0);
        }])->orderBy('name')->get();

        return view('shop', compact('products', 'categories'));
    }

    public function show($slug)
    {
        $product = Product::with(['category', 'media', 'description'])
                         ->where('slug', $slug)
                         ->firstOrFail();

        // Get related products from same category
        $relatedProducts = Product::with(['category', 'media'])
                                ->where('category_id', $product->category_id)
                                ->where('id', '!=', $product->id)
                                ->where('stock', '>', 0)
                                ->take(4)
                                ->get();

        return view('shop.show', compact('product', 'relatedProducts'));
    }

    public function categories(Request $request)
    {
        $query = Category::withCount(['products' => function ($query) {
            $query->where('stock', '>', 0);
        }]);

        // Search
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('slug', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        $categories = $query->orderBy('name')->get();

        return view('categories', compact('categories'));
    }

    public function category(Request $request, $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $query = Product::with(['category', 'media', 'description'])
                       ->where('category_id', $category->id)
                       ->where('stock', '>', 0);

        // Search within category
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('slug', 'like', '%' . $search . '%')
                  ->orWhereHas('description', function($desc) use ($search) {
                      $desc->where('description', 'like', '%' . $search . '%')
                          ->orWhere('specifications', 'like', '%' . $search . '%')
                          ->orWhere('details', 'like', '%' . $search . '%');
                  });
            });
        }

        // Rating filter
        if ($request->has('rating') && !empty($request->rating)) {
            $query->where('rate', '>=', $request->rating);
        }

        // Discount filter
        if ($request->has('on_sale') && $request->on_sale == '1') {
            $query->where('discount', '>', 0);
        }

        // Sorting
        if ($request->has('sort_by') && $request->has('sort_order')) {
            $sortBy = $request->sort_by;
            $sortOrder = $request->sort_order;

            if (in_array($sortBy, ['name', 'new_price', 'created_at', 'rate'])) {
                $query->orderBy($sortBy, $sortOrder);
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(24)->withQueryString();

        $categories = Category::withCount(['products' => function ($query) {
            $query->where('stock', '>', 0);
        }])->orderBy('name')->get();

        return view('category', compact('category', 'products', 'categories'));
    }
}
