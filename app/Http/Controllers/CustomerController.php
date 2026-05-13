<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    /**
     * Display customer dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();

        // Get order statistics
        $totalOrders = Order::where('user_id', $user->id)->count();
        $pendingOrders = Order::where('user_id', $user->id)->where('status', 'pending')->count();
        $completedOrders = Order::where('user_id', $user->id)->where('status', 'completed')->count();
        $totalSpent = Order::where('user_id', $user->id)->sum('total_amount');

        // Get recent orders
        $recentOrders = Order::where('user_id', $user->id)
            ->with('orderItems')
            ->orderBy('ordered_at', 'desc')
            ->take(5)
            ->get();

        // Get cart items count
        $cartItemsCount = DB::table('carts')
            ->join('cart_items', 'carts.id', '=', 'cart_items.cart_id')
            ->where('carts.user_id', $user->id)
            ->sum('cart_items.quantity');

        // Get featured products (you can customize this logic)
        $featuredProducts = Product::where('stock', '>', 0)
            ->where('is_advertised', true)
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('customer.dashboard', compact(
            'totalOrders',
            'pendingOrders',
            'completedOrders',
            'totalSpent',
            'recentOrders',
            'cartItemsCount',
            'featuredProducts'
        ));
    }

    /**
     * Display customer orders
     */
    public function orders(Request $request)
    {
        $user = Auth::user();

        $query = Order::where('user_id', $user->id)
            ->with('orderItems.product.media', 'orderItems.product.seller')
            ->orderBy('ordered_at', 'desc');

        // Filter by status if provided
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(10);

        return view('customer.orders', compact('orders'));
    }

    /**
     * Cancel an order
     */
    public function cancelOrder(Order $order)
    {
        // Ensure user owns this order
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if order can be cancelled
        if (!$order->canBeCancelled()) {
            return back()->with('error', 'This order cannot be cancelled.');
        }

        $order->load('orderItems.product');
        foreach ($order->orderItems as $item) {
            if ($item->product) {
                $item->product->increment('stock', $item->quantity);
            }
        }

        // Update order status to cancelled
        $order->update(['status' => 'cancelled']);

        return back()->with('success', 'Order cancelled successfully.');
    }

    /**
     * Update order item quantity
     */
    public function updateOrderItem(Request $request, Order $order, OrderItem $orderItem)
    {
        // Ensure user owns this order
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Ensure order item belongs to this order
        if ($orderItem->order_id !== $order->id) {
            abort(403);
        }

        // Check if order can be updated
        if (!$order->canBeUpdated()) {
            return response()->json(['error' => 'This order cannot be updated.'], 403);
        }

        $availableQuantity = $orderItem->product->stock + $orderItem->quantity;

        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $availableQuantity,
        ]);

        $quantity = $request->quantity;
        $oldQuantity = $orderItem->quantity;
        $quantityDifference = $quantity - $oldQuantity;

        if ($quantityDifference > 0) {
            $orderItem->product->decrement('stock', $quantityDifference);
        } elseif ($quantityDifference < 0) {
            $orderItem->product->increment('stock', abs($quantityDifference));
        }

        // Update the order item
        $orderItem->update([
            'quantity' => $quantity,
            'total_price' => $orderItem->price * $quantity,
        ]);

        // Recalculate order totals
        $this->recalculateOrderTotals($order);

        return response()->json([
            'success' => true,
            'item_total' => number_format($orderItem->total, 2),
            'order_total' => number_format($order->total_amount, 2),
        ]);
    }

    /**
     * Remove order item
     */
    public function removeOrderItem(Order $order, OrderItem $orderItem)
    {
        // Ensure user owns this order
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Ensure order item belongs to this order
        if ($orderItem->order_id !== $order->id) {
            abort(403);
        }

        // Check if order can be updated
        if (!$order->canBeUpdated()) {
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'This order cannot be updated.'], 403);
            }
            return back()->with('error', 'This order cannot be updated.');
        }

        if ($orderItem->product) {
            $orderItem->product->increment('stock', $orderItem->quantity);
        }

        // Delete the order item
        $orderItem->delete();

        // If no items left, cancel the order
        if ($order->orderItems()->count() === 0) {
            $order->update(['status' => 'cancelled']);
            if (request()->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Order cancelled as no items remained.']);
            }
            return back()->with('success', 'Order cancelled as no items remained.');
        }

        // Recalculate order totals
        $this->recalculateOrderTotals($order);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Item removed from order successfully.',
                'order_total' => number_format($order->total_amount, 2)
            ]);
        }
        return back()->with('success', 'Item removed from order successfully.');
    }

    /**
     * Recalculate order totals
     */
    private function recalculateOrderTotals(Order $order)
    {
        $subtotal = $order->orderItems->sum('total_price');
        $taxAmount = $subtotal * 0.18; // Assuming 18% tax, adjust as needed
        $totalAmount = $subtotal + $taxAmount + $order->shipping_cost - $order->discount_amount;

        $order->update([
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
        ]);
    }

    /**
     * Display order details
     */
    public function orderDetails(Order $order)
    {
        // Ensure user owns this order
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load('orderItems.product.media', 'orderItems.product.seller', 'orderAddresses');

        // Recalculate totals if subtotal is 0 (for existing orders)
        if ($order->subtotal == 0 && $order->orderItems->count() > 0) {
            $this->recalculateOrderTotals($order);
            // Reload the order with updated totals
            $order->refresh();
        }

        return view('customer.order-details', compact('order'));
    }

    /**
     * Display customer profile
     */
    public function profile()
    {
        $user = Auth::user();

        return view('customer.profile', compact('user'));
    }

    /**
     * Update customer profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:40'],
            'passport' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'password' => ['nullable', 'confirmed', 'min:8'],
        ]);

        $profileData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
        ];

        if ($request->hasFile('passport')) {
            if (!empty($user->passport)) {
                Storage::disk('public')->delete($user->passport);
            }

            $profileData['passport'] = $request->file('passport')->store('profiles', 'public');
        }

        if (!empty($validated['password'])) {
            $profileData['password'] = Hash::make($validated['password']);
        }

        $user->forceFill($profileData)->save();

        return redirect()->route('customer.profile')->with('success', 'Profile updated successfully.');
    }

    /**
     * Display wishlist
     */
    public function wishlist()
    {
        $user = Auth::user();

        // For now, return empty wishlist until we implement wishlist functionality
        $wishlist = collect();

        return view('customer.wishlist', compact('wishlist'));
    }

    /**
     * Display addresses management
     */
    public function addresses()
    {
        $user = Auth::user();

        $addresses = $user->addresses()
            ->orderByDesc('is_default')
            ->latest()
            ->get();

        return view('customer.addresses', compact('addresses'));
    }

    /**
     * Display support page
     */
    public function support()
    {
        return view('customer.support');
    }
}
