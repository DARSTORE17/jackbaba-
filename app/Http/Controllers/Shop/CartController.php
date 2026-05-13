<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Display the cart
     */
    public function index()
    {
        $cart = $this->getCart();
        $cartItems = $cart ? $cart->cartItems()->with(['product.media', 'product.seller'])->get() : collect();

        $totals = $this->calculateCartTotals($cartItems);
        $subtotal = $totals['subtotal'];
        $taxAmount = $totals['tax_amount'];
        $shippingCost = $totals['shipping_cost'];
        $total = $totals['total'];
        $taxSummary = $totals['tax_summary'];
        $deliverySummary = $totals['delivery_summary'];

        return view('shop.cart', compact('cartItems', 'subtotal', 'taxAmount', 'shippingCost', 'total', 'taxSummary', 'deliverySummary'));
    }

    /**
     * Add item to cart
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);
        $cart = $this->getOrCreateCart();

        // Check if item already exists in cart
        $existingItem = $cart->cartItems()->where('product_id', $request->product_id)->first();

        if ($existingItem) {
            // Product already in cart - don't add again, just return info
            return response()->json([
                'success' => false,
                'message' => 'This product is already in your cart',
                'already_in_cart' => true,
                'cart_count' => $cart->cartItems()->sum('quantity')
            ]);
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'price' => $product->new_price
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Product added to cart successfully',
                'cart_count' => $cart->cartItems()->sum('quantity')
            ]);
        }
    }

    /**
     * Update cart item
     */
    public function update(Request $request, CartItem $cartItem)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:99'
        ]);

        // Check if cart belongs to current user/session
        if (!$this->cartBelongsToUser($cartItem->cart)) {
            abort(403);
        }

        $cartItem->update(['quantity' => $request->quantity]);

        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully'
        ]);
    }

    /**
     * Remove item from cart
     */
    public function remove(CartItem $cartItem)
    {
        // Check if cart belongs to current user/session
        if (!$this->cartBelongsToUser($cartItem->cart)) {
            abort(403);
        }

        $cartItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart'
        ]);
    }

    /**
     * Clear entire cart
     */
    public function clear()
    {
        $cart = $this->getCart();
        if ($cart) {
            $cart->cartItems()->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully'
        ]);
    }

    /**
     * Get or create cart for current user/session
     */
    private function getOrCreateCart()
    {
        if (Auth::check()) {
            return Cart::firstOrCreate(['user_id' => Auth::id()]);
        } else {
            $sessionId = Session::getId();
            return Cart::firstOrCreate(['session_id' => $sessionId]);
        }
    }

    /**
     * Get current user's cart
     */
    private function getCart()
    {
        if (Auth::check()) {
            return Cart::where('user_id', Auth::id())->first();
        } else {
            $sessionId = Session::getId();
            return Cart::where('session_id', $sessionId)->first();
        }
    }

    private function calculateCartTotals($cartItems): array
    {
        $subtotal = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $taxAmount = 0;
        $shippingCost = 0;
        $taxSummary = [];
        $deliverySummary = [];

        foreach ($cartItems->groupBy(fn ($item) => $item->product->seller?->id ?: 'store') as $items) {
            $seller = $items->first()->product->seller;
            $sellerName = $seller->name ?? 'Bravus Market';
            $sellerTax = 0;

            foreach ($items as $item) {
                $lineSubtotal = $item->price * $item->quantity;
                $sellerTax += $item->product->vat_enabled
                    ? $lineSubtotal * (((float) $item->product->vat_rate) / 100)
                    : 0;
            }

            $deliveryPayment = $items->contains(fn ($item) => $item->product->delivery_payment === 'customer')
                ? 'customer'
                : 'free';
            $deliveryFee = $deliveryPayment === 'customer'
                ? $items->where('product.delivery_payment', 'customer')->max(fn ($item) => (float) $item->product->delivery_fee)
                : 0;

            $taxAmount += $sellerTax;
            $shippingCost += $deliveryFee;
            $taxSummary[] = ['seller' => $sellerName, 'rate' => 'Product based', 'amount' => $sellerTax];
            $deliverySummary[] = ['seller' => $sellerName, 'payment' => $deliveryPayment, 'fee' => $deliveryFee];
        }

        return [
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'shipping_cost' => $shippingCost,
            'total' => $subtotal + $taxAmount + $shippingCost,
            'tax_summary' => $taxSummary,
            'delivery_summary' => $deliverySummary,
        ];
    }

    /**
     * Check if cart belongs to current user/session
     */
    private function cartBelongsToUser(Cart $cart)
    {
        if (Auth::check()) {
            return $cart->user_id === Auth::id();
        } else {
            return $cart->session_id === Session::getId();
        }
    }
}
