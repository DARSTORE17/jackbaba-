<?php

namespace App\Http\Controllers\seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
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

    public function index(Request $request)
    {
        $sellerId = Auth::id();
        $query = Order::with(['user', 'orderItems.product'])
            ->whereHas('orderItems.product', function ($productQuery) use ($sellerId) {
                $productQuery->where('seller_id', $sellerId);
            });

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhere('status', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Date range filter
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(5);

        return view('seller.orders', compact('orders'));
    }

    public function show($id)
    {
        try {
            $sellerId = Auth::id();
            $order = Order::with(['user', 'orderItems.product', 'orderAddresses'])
                ->whereHas('orderItems.product', function ($productQuery) use ($sellerId) {
                    $productQuery->where('seller_id', $sellerId);
                })
                ->findOrFail($id);

            // Format order items safely
            $sellerItems = $order->orderItems->filter(function ($item) use ($sellerId) {
                return $item->product && (int) $item->product->seller_id === (int) $sellerId;
            });
            $formattedOrderItems = $sellerItems->map(function($item) {
                return [
                    'id' => $item->id,
                    'product' => $item->product ? [
                        'name' => $item->product->name ?: 'Unknown Product'
                    ] : ['name' => 'Product Not Found'],
                    'quantity' => $item->quantity ?? 0,
                    'price' => $item->unit_price ?? 0,
                    'total' => $item->total_price ?? (($item->unit_price ?? 0) * ($item->quantity ?? 0))
                ];
            });
            $shippingAddress = $order->orderAddresses->firstWhere('type', 'shipping');
            $sellerSubtotal = $sellerItems->sum('total_price');

            return response()->json([
                'success' => true,
                'order' => [
                    'id' => $order->id,
                'user' => $order->user ? [
                    'first_name' => $order->user->name,
                    'last_name' => '',
                    'name' => $order->user->name,
                    'email' => $order->user->email,
                    'phone' => $order->user->phone ?? ''
                ] : ['name' => 'Unknown', 'email' => 'unknown@example.com', 'phone' => ''],
                    'order_items' => $formattedOrderItems->toArray(),
                    'total_amount' => $order->total_amount ?? 0,
                    'seller_subtotal' => $sellerSubtotal,
                    'subtotal' => $order->subtotal ?? $order->total_amount ?? 0,
                    'shipping_cost' => $order->shipping_cost ?? 0,
                    'tax_amount' => $order->tax_amount ?? 0,
                    'status' => $order->status ?? 'pending',
                    'payment_status' => $order->payment_status ?? 'pending',
                    'created_at' => $order->created_at,
                    'notes' => $order->customer_notes ?? '',
                    'order_address' => $shippingAddress ? [
                        'first_name' => $shippingAddress->first_name,
                        'last_name' => $shippingAddress->last_name,
                        'address' => $shippingAddress->street_address,
                        'city' => $shippingAddress->city,
                        'state' => $shippingAddress->state,
                        'zip_code' => $shippingAddress->postal_code,
                        'country' => $shippingAddress->country,
                        'phone' => $shippingAddress->phone,
                    ] : null
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading order details: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,preparing,ready_for_pickup,shipped,delivered,completed,cancelled'
        ]);

        $status = match ($request->status) {
            'shipped' => 'ready_for_pickup',
            'delivered' => 'completed',
            default => $request->status,
        };

        $sellerId = Auth::id();
        $order = Order::with('orderItems.product')->whereHas('orderItems.product', function ($productQuery) use ($sellerId) {
            $productQuery->where('seller_id', $sellerId);
        })->findOrFail($id);

        DB::transaction(function () use ($order, $status) {
            if ($status === 'cancelled' && $order->status !== 'cancelled') {
                foreach ($order->orderItems as $item) {
                    if ($item->product) {
                        $item->product->increment('stock', $item->quantity);
                    }
                }
            }

            $order->update(['status' => $status]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Order status updated successfully',
            'order' => $order
        ]);
    }
}
