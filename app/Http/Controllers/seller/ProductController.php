<?php

namespace App\Http\Controllers\seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductMedia;
use App\Models\ProductDescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
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

    protected function findSellerProduct($id)
    {
        return Product::where('seller_id', Auth::id())->find($id);
    }

    // Display all products with pagination
    public function index(Request $request)
    {
        $search = $request->input('search');
        $category_id = $request->input('category_id');

        $query = Product::with(['category', 'media'])
            ->where('seller_id', Auth::id())
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('slug', 'like', '%' . $search . '%');
            })
            ->when($category_id, function ($q) use ($category_id) {
                $q->where('category_id', $category_id);
            })
            ->orderBy('created_at', 'desc');

        $products = $query->paginate(5);

        $categories = Category::where(function ($q) {
            $q->where('seller_id', Auth::id())
              ->orWhereNull('seller_id');
        })->get();

        return view('seller.products', compact('products', 'categories'));
    }

    // Store a new product
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:191',
            'old_price' => 'nullable|numeric|min:0',
            'new_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_advertised' => 'boolean',
            'description' => 'nullable|string',
            'specifications' => 'nullable|string',
            'details' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            // Generate unique slug
            $slug = Str::slug($request->name);
            $originalSlug = $slug;
            $counter = 1;

            while (Product::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            // Create product
            $product = Product::create([
                'seller_id' => Auth::id(),
                'category_id' => $request->category_id,
                'name' => $request->name,
                'slug' => $slug,
                'old_price' => $request->old_price,
                'new_price' => $request->new_price,
                'discount' => $request->old_price ? round((($request->old_price - $request->new_price) / $request->old_price) * 100) : 0,
                'rate' => 0,
                'stock' => $request->stock,
                'is_advertised' => $request->is_advertised ?? false,
            ]);

            // Handle thumbnail upload
            if ($request->hasFile('thumbnail')) {
                $thumbnailPath = $request->file('thumbnail')->store('products/thumbnails', 'public');
                $product->update(['thumbnail' => $thumbnailPath]);
            }

            // Create product description
            ProductDescription::create([
                'product_id' => $product->id,
                'description' => $request->description,
                'specifications' => $request->specifications,
                'details' => $request->details,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Product created successfully!',
                'product' => $product->load(['category', 'media'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create product: ' . $e->getMessage()
            ], 500);
        }
    }

    // Show product details
    public function show($id)
    {
        $product = $this->findSellerProduct($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        $product->load(['category', 'media', 'description']);

        return response()->json([
            'success' => true,
            'product' => $product
        ]);
    }

    // Update product
    public function update(Request $request, $id)
    {
        $product = $this->findSellerProduct($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        // Check if this is a rating-only update
        if ($request->has('rate') && count($request->all()) === 2 && $request->has('_method')) {
            // Only update rating
            $validator = Validator::make($request->all(), [
                'rate' => 'required|integer|min:1|max:5',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $product->update([
                'rate' => $request->rate,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Product rating updated successfully!',
                'product' => $product->load(['category', 'media'])
            ]);
        }

        // Full product update
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:191',
            'old_price' => 'nullable|numeric|min:0',
            'new_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_advertised' => 'boolean',
            'description' => 'nullable|string',
            'specifications' => 'nullable|string',
            'details' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            // Generate unique slug (exclude current product)
            $slug = Str::slug($request->name);
            $originalSlug = $slug;
            $counter = 1;

            while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            // Update product
            $product->update([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'slug' => $slug,
                'old_price' => $request->old_price,
                'new_price' => $request->new_price,
                'discount' => $request->old_price ? round((($request->old_price - $request->new_price) / $request->old_price) * 100) : 0,
                'stock' => $request->stock,
                'is_advertised' => $request->is_advertised ?? false,
                'rate' => $request->rate ?? $product->rate ?? 0,
            ]);

            // Handle thumbnail upload
            if ($request->hasFile('thumbnail')) {
                // Delete old thumbnail if exists
                if ($product->thumbnail && Storage::exists('public/' . $product->thumbnail)) {
                    Storage::delete('public/' . $product->thumbnail);
                }

                $thumbnailPath = $request->file('thumbnail')->store('products/thumbnails', 'public');
                $product->update(['thumbnail' => $thumbnailPath]);
            }

            // Update product description
            $product->description()->updateOrCreate(
                ['product_id' => $product->id],
                [
                    'description' => $request->description,
                    'specifications' => $request->specifications,
                    'details' => $request->details,
                ]
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully!',
                'product' => $product->load(['category', 'media'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update product: ' . $e->getMessage()
            ], 500);
        }
    }

    // Delete product
    public function destroy($id)
    {
        $product = $this->findSellerProduct($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        DB::beginTransaction();

        try {
            // Delete thumbnail if exists
            if ($product->thumbnail && Storage::exists('public/' . $product->thumbnail)) {
                Storage::delete('public/' . $product->thumbnail);
            }

            // Delete related media
            $product->media()->delete();

            // Delete description
            $product->description()->delete();

            // Delete product
            $product->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete product: ' . $e->getMessage()
            ], 500);
        }
    }

    // Show product media management page
    public function media($id)
    {
        $product = $this->findSellerProduct($id);

        if (!$product) {
            abort(404, 'Product not found');
        }

        return view('seller.product-media', compact('product'));
    }

    // Upload media files
    public function uploadMedia(Request $request, $productId)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpeg,png,jpg,gif,mp4,avi|max:10240', // 10MB max
            'type' => 'required|string|in:image,video'
        ]);

        $product = $this->findSellerProduct($productId);
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found'], 404);
        }

        try {
            $file = $request->file('file');
            $type = $request->type;
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            // Store file
            $path = $file->storeAs('products/media', $filename, 'public');

            // First uncheck all primary media for this product
            ProductMedia::where('product_id', $productId)->update(['is_primary' => false]);

            // Create media record
            $media = ProductMedia::create([
                'product_id' => $productId,
                'type' => $type,
                'file_path' => $path,
                'is_primary' => true, // Set first upload as primary by default
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Media uploaded successfully',
                'media' => $media
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }

    // Set media as primary
    public function setPrimaryMedia(Request $request, $productId, $mediaId)
    {
        $product = $this->findSellerProduct($productId);
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found'], 404);
        }

        $media = ProductMedia::where('product_id', $productId)->find($mediaId);
        if (!$media) {
            return response()->json(['success' => false, 'message' => 'Media not found'], 404);
        }

        try {
            // First uncheck all primary media for this product
            ProductMedia::where('product_id', $productId)->update(['is_primary' => false]);

            // Set this media as primary
            $media->update(['is_primary' => true]);

            return response()->json([
                'success' => true,
                'message' => 'Media set as primary successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to set primary: ' . $e->getMessage()
            ], 500);
        }
    }

    // Delete media
    public function deleteMedia($productId, $mediaId)
    {
        $product = $this->findSellerProduct($productId);
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found'], 404);
        }

        $media = ProductMedia::where('product_id', $productId)->find($mediaId);
        if (!$media) {
            return response()->json(['success' => false, 'message' => 'Media not found'], 404);
        }

        try {
            // Delete file from storage
            if (Storage::disk('public')->exists($media->file_path)) {
                Storage::disk('public')->delete($media->file_path);
            }

            // Delete media record
            $media->delete();

            return response()->json([
                'success' => true,
                'message' => 'Media deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Delete failed: ' . $e->getMessage()
            ], 500);
        }
    }

    // Toggle advertised status
    public function toggleAdvertised($id)
    {
        $product = $this->findSellerProduct($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        $product->update([
            'is_advertised' => !$product->is_advertised
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Advertised status updated!',
            'is_advertised' => $product->is_advertised
        ]);
    }
}
