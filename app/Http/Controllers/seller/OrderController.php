<?php

namespace App\Http\Controllers\seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'orderItems.product']);

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
            $order = Order::with(['user', 'orderItems.product'])->findOrFail($id);

            // Format order items safely
            $formattedOrderItems = $order->orderItems->map(function($item) {
                return [
                    'id' => $item->id,
                    'product' => $item->product ? [
                        'name' => $item->product->name ?: 'Unknown Product'
                    ] : ['name' => 'Product Not Found'],
                    'quantity' => $item->quantity ?? 0,
                    'price' => $item->price ?? 0,
                    'total' => ($item->price ?? 0) * ($item->quantity ?? 0)
                ];
            });

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
                    'subtotal' => $order->subtotal ?? $order->total_amount ?? 0,
                    'shipping_cost' => $order->shipping_cost ?? 0,
                    'tax_amount' => $order->tax_amount ?? 0,
                    'status' => $order->status ?? 'pending',
                    'payment_status' => $order->payment_status ?? 'pending',
                    'created_at' => $order->created_at,
                    'notes' => $order->notes ?? '',
                    'order_address' => null // Temporarily set to null
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
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);

        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Order status updated successfully',
            'order' => $order
        ]);
    }
}
