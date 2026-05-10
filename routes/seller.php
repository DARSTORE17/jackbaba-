<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;

Route::middleware(['auth'])->prefix('seller')->group(function () {
    Route::get('/dashboard', function () {
        $sellerId = Auth::id();

        // Get only this seller's data
        $productCount = Product::where('seller_id', $sellerId)->count();
        $categoryCount = Category::where('seller_id', $sellerId)->count();
        $orderCount = Order::whereHas('orderItems.product', function ($query) use ($sellerId) {
            $query->where('seller_id', $sellerId);
        })->count();

        // Recent products (seller's only)
        $recentProducts = Product::where('seller_id', $sellerId)
            ->latest()
            ->limit(5)
            ->get();

        // Recent orders (seller's only)
        $recentOrders = Order::whereHas('orderItems.product', function ($query) use ($sellerId) {
            $query->where('seller_id', $sellerId);
        })->latest()->limit(5)->get();

        // Total revenue (seller's only)
        $totalRevenue = \DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.seller_id', $sellerId)
            ->sum('order_items.total_price');

        // Top selling products (seller's only)
        $topProducts = \DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.seller_id', $sellerId)
            ->select('products.id', 'products.name', \DB::raw('SUM(order_items.quantity) as total_sold'), \DB::raw('SUM(order_items.total_price) as revenue'))
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();

        // Monthly revenue and profit/loss (last 12 months)
        $monthlyStats = \DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('products.seller_id', $sellerId)
            ->select(
                \DB::raw('DATE_FORMAT(orders.created_at, "%Y-%m") as month'),
                \DB::raw('SUM(order_items.total_price) as revenue'),
                \DB::raw('COUNT(DISTINCT orders.id) as orders_count')
            )
            ->groupBy(\DB::raw('DATE_FORMAT(orders.created_at, "%Y-%m")'))
            ->orderBy(\DB::raw('DATE_FORMAT(orders.created_at, "%Y-%m")'), 'desc')
            ->limit(12)
            ->get();

        // Total products cost (estimate: assuming product has cost field, else use 30% of price)
        $totalProductsCost = Product::where('seller_id', $sellerId)->sum('new_price') * 0.3; // Estimate 30% as cost

        // Calculate profit/loss
        $profit = $totalRevenue - $totalProductsCost;
        $profitMargin = $totalRevenue > 0 ? round(($profit / $totalRevenue) * 100, 2) : 0;

        return view('seller.dashboard', compact(
            'productCount', 'categoryCount', 'orderCount', 'recentProducts', 
            'recentOrders', 'totalRevenue', 'topProducts', 'monthlyStats',
            'totalProductsCost', 'profit', 'profitMargin'
        ));
    })->name('seller.dashboard');

    Route::get('/products', [\App\Http\Controllers\seller\ProductController::class, 'index'])->name('seller.products');
    Route::post('/products', [\App\Http\Controllers\seller\ProductController::class, 'store'])->name('seller.products.store');
    Route::get('/products/{id}', [\App\Http\Controllers\seller\ProductController::class, 'show'])->name('seller.products.show');
    Route::get('/products/{id}/media', [\App\Http\Controllers\seller\ProductController::class, 'media'])->name('seller.products.media');
    Route::post('/products/{productId}/media/upload', [\App\Http\Controllers\seller\ProductController::class, 'uploadMedia'])->name('seller.products.media.upload');
    Route::patch('/products/{productId}/media/{mediaId}/primary', [\App\Http\Controllers\seller\ProductController::class, 'setPrimaryMedia'])->name('seller.products.media.primary');
    Route::delete('/products/{productId}/media/{mediaId}', [\App\Http\Controllers\seller\ProductController::class, 'deleteMedia'])->name('seller.products.media.delete');
    Route::put('/products/{id}', [\App\Http\Controllers\seller\ProductController::class, 'update'])->name('seller.products.update');
    Route::delete('/products/{id}', [\App\Http\Controllers\seller\ProductController::class, 'destroy'])->name('seller.products.destroy');
    Route::patch('/products/{id}/toggle-advertised', [\App\Http\Controllers\seller\ProductController::class, 'toggleAdvertised'])->name('seller.products.toggleAdvertised');

    Route::get('/orders', [\App\Http\Controllers\seller\OrderController::class, 'index'])->name('seller.orders');
    Route::get('/orders/{id}', [\App\Http\Controllers\seller\OrderController::class, 'show'])->name('seller.orders.show');
    Route::patch('/orders/{id}/status', [\App\Http\Controllers\seller\OrderController::class, 'updateStatus'])->name('seller.orders.updateStatus');

    Route::get('/customers', [\App\Http\Controllers\seller\CustomerController::class, 'index'])->name('seller.customers');
    Route::post('/customers', [\App\Http\Controllers\seller\CustomerController::class, 'store'])->name('seller.customers.store');
    Route::get('/customers/{id}', [\App\Http\Controllers\seller\CustomerController::class, 'show'])->name('seller.customers.show');
    Route::put('/customers/{id}', [\App\Http\Controllers\seller\CustomerController::class, 'update'])->name('seller.customers.update');
    Route::delete('/customers/{id}', [\App\Http\Controllers\seller\CustomerController::class, 'destroy'])->name('seller.customers.destroy');

    Route::get('/analytics', function () {
        return view('seller.analytics');
    })->name('seller.analytics');

    Route::get('/categories', [\App\Http\Controllers\seller\CategoryController::class, 'index'])->name('seller.categories');
    Route::post('/categories', [\App\Http\Controllers\seller\CategoryController::class, 'store'])->name('seller.categories.store');
    Route::get('/categories/{id}', [\App\Http\Controllers\seller\CategoryController::class, 'show'])->name('seller.categories.show');
    Route::put('/categories/{id}', [\App\Http\Controllers\seller\CategoryController::class, 'update'])->name('seller.categories.update');
    Route::delete('/categories/{id}', [\App\Http\Controllers\seller\CategoryController::class, 'destroy'])->name('seller.categories.destroy');

    Route::get('/my-store', function () {
        return view('seller.my-store');
    })->name('seller.my-store');

    Route::get('/settings', function () {
        return view('seller.settings');
    })->name('seller.settings');
});
