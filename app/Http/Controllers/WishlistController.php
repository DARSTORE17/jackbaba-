<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{

    /**
     * Display the user's wishlist.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Use Wishlist model to query with user relationship
        $wishlistItems = Wishlist::where('user_id', $user->id)
            ->with(['product.media', 'product.category'])
            ->whereHas('product', function($query) {
                $query->where('stock', '>', 0); // Only show products with stock
            })
            ->get();

        return view('wishlist', compact('wishlistItems'));
    }

    /**
     * Toggle product in/out of wishlist.
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $productId = $request->product_id;

        // Check if product is already in wishlist
        $existingWishlistItem = Wishlist::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();

        if ($existingWishlistItem) {
            // Remove from wishlist
            $existingWishlistItem->delete();
            return response()->json([
                'success' => true,
                'action' => 'removed',
                'message' => 'Product removed from wishlist',
                'in_wishlist' => false
            ]);
        } else {
            // Add to wishlist
            Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $productId
            ]);

            return response()->json([
                'success' => true,
                'action' => 'added',
                'message' => 'Product added to wishlist',
                'in_wishlist' => true
            ]);
        }
    }

    /**
     * Check if product is in user's wishlist.
     */
    public function check(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $inWishlist = Wishlist::where('user_id', $user->id)
            ->where('product_id', $request->product_id)
            ->exists();

        return response()->json([
            'in_wishlist' => $inWishlist
        ]);
    }

    /**
     * Remove specific item from wishlist.
     */
    public function destroy($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $wishlistItem = Wishlist::where('user_id', $user->id)
            ->where('id', $id)
            ->firstOrFail();

        $wishlistItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item removed from wishlist'
        ]);
    }
}
