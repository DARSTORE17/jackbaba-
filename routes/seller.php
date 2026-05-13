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

        $monthlyRows = $monthlyStats->keyBy('month');
        $monthlyChart = collect(range(11, 0))->map(function ($monthsAgo) use ($monthlyRows) {
            $date = now()->subMonths($monthsAgo);
            $key = $date->format('Y-m');
            $row = $monthlyRows->get($key);

            return [
                'label' => $date->format('M Y'),
                'revenue' => $row ? (float) $row->revenue : 0,
                'orders' => $row ? (int) $row->orders_count : 0,
            ];
        });

        $statusBreakdown = Order::whereHas('orderItems.product', function ($query) use ($sellerId) {
            $query->where('seller_id', $sellerId);
        })
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get();

        $lowStockCount = Product::where('seller_id', $sellerId)->where('stock', '<=', 10)->count();

        // Total products cost (estimate: assuming product has cost field, else use 30% of price)
        $totalProductsCost = Product::where('seller_id', $sellerId)->sum('new_price') * 0.3; // Estimate 30% as cost

        // Calculate profit/loss
        $profit = $totalRevenue - $totalProductsCost;
        $profitMargin = $totalRevenue > 0 ? round(($profit / $totalRevenue) * 100, 2) : 0;

        return view('seller.dashboard', compact(
            'productCount', 'categoryCount', 'orderCount', 'recentProducts', 
            'recentOrders', 'totalRevenue', 'topProducts', 'monthlyStats',
            'monthlyChart', 'statusBreakdown', 'lowStockCount',
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
        $sellerId = Auth::id();

        $sellerOrderQuery = Order::whereHas('orderItems.product', function ($query) use ($sellerId) {
            $query->where('seller_id', $sellerId);
        });

        $totalRevenue = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.seller_id', $sellerId)
            ->sum('order_items.total_price');

        $completedRevenue = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('products.seller_id', $sellerId)
            ->where('orders.status', 'completed')
            ->sum('order_items.total_price');

        $totalOrders = (clone $sellerOrderQuery)->count();
        $totalCustomers = (clone $sellerOrderQuery)->distinct('user_id')->count('user_id');
        $averageRating = Product::where('seller_id', $sellerId)->where('rate', '>', 0)->avg('rate') ?? 0;
        $productCount = Product::where('seller_id', $sellerId)->count();
        $lowStockCount = Product::where('seller_id', $sellerId)->where('stock', '<=', 10)->count();
        $itemsSold = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.seller_id', $sellerId)
            ->sum('order_items.quantity');

        $monthlyRows = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('products.seller_id', $sellerId)
            ->where('orders.created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->select(
                DB::raw('DATE_FORMAT(orders.created_at, "%Y-%m") as month_key'),
                DB::raw('SUM(order_items.total_price) as revenue'),
                DB::raw('COUNT(DISTINCT orders.id) as orders_count')
            )
            ->groupBy(DB::raw('DATE_FORMAT(orders.created_at, "%Y-%m")'))
            ->orderBy(DB::raw('DATE_FORMAT(orders.created_at, "%Y-%m")'))
            ->get()
            ->keyBy('month_key');

        $monthlyStats = collect(range(11, 0))->map(function ($monthsAgo) use ($monthlyRows) {
            $date = now()->subMonths($monthsAgo);
            $key = $date->format('Y-m');
            $row = $monthlyRows->get($key);

            return [
                'label' => $date->format('M Y'),
                'revenue' => $row ? (float) $row->revenue : 0,
                'orders' => $row ? (int) $row->orders_count : 0,
            ];
        });

        $topProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.seller_id', $sellerId)
            ->select(
                'products.name',
                DB::raw('SUM(order_items.quantity) as quantity'),
                DB::raw('SUM(order_items.total_price) as revenue')
            )
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('revenue')
            ->limit(8)
            ->get();

        $statusBreakdown = (clone $sellerOrderQuery)
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->orderByDesc('total')
            ->get();

        $recentOrders = (clone $sellerOrderQuery)
            ->with(['user', 'orderItems.product'])
            ->latest()
            ->take(8)
            ->get();

        return view('seller.analytics', compact(
            'totalRevenue',
            'completedRevenue',
            'totalOrders',
            'totalCustomers',
            'averageRating',
            'productCount',
            'lowStockCount',
            'itemsSold',
            'monthlyStats',
            'topProducts',
            'statusBreakdown',
            'recentOrders'
        ));
    })->name('seller.analytics');

    Route::get('/categories', [\App\Http\Controllers\seller\CategoryController::class, 'index'])->name('seller.categories');
    Route::post('/categories', [\App\Http\Controllers\seller\CategoryController::class, 'store'])->name('seller.categories.store');
    Route::get('/categories/{id}', [\App\Http\Controllers\seller\CategoryController::class, 'show'])->name('seller.categories.show');
    Route::put('/categories/{id}', [\App\Http\Controllers\seller\CategoryController::class, 'update'])->name('seller.categories.update');
    Route::delete('/categories/{id}', [\App\Http\Controllers\seller\CategoryController::class, 'destroy'])->name('seller.categories.destroy');

    Route::get('/my-store', function () {
        $seller = Auth::user();
        $sellerId = Auth::id();
        $productCount = Product::where('seller_id', $sellerId)->count();
        $orderCount = Order::whereHas('orderItems.product', function ($query) use ($sellerId) {
            $query->where('seller_id', $sellerId);
        })->count();
        $revenue = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('products.seller_id', $sellerId)
            ->where('orders.status', '!=', 'cancelled')
            ->sum('order_items.total_price');
        $recentProducts = Product::where('seller_id', $sellerId)->latest()->take(5)->get();

        $soldByProduct = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('products.seller_id', $sellerId)
            ->where('orders.status', '!=', 'cancelled')
            ->select('products.id', DB::raw('SUM(order_items.quantity) as sold_quantity'), DB::raw('SUM(order_items.total_price) as revenue'))
            ->groupBy('products.id');

        $stockReport = Product::query()
            ->leftJoinSub($soldByProduct, 'sales', function ($join) {
                $join->on('products.id', '=', 'sales.id');
            })
            ->where('products.seller_id', $sellerId)
            ->select(
                'products.id',
                'products.name',
                'products.stock',
                'products.initial_stock',
                'products.new_price',
                DB::raw('COALESCE(sales.sold_quantity, 0) as sold_quantity'),
                DB::raw('COALESCE(sales.revenue, 0) as revenue')
            )
            ->orderByDesc('sold_quantity')
            ->orderBy('products.name')
            ->get();

        $totalInitialStock = $stockReport->sum('initial_stock');
        $totalCurrentStock = $stockReport->sum('stock');
        $totalSoldQuantity = $stockReport->sum('sold_quantity');

        $salesTrend = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('products.seller_id', $sellerId)
            ->where('orders.status', '!=', 'cancelled')
            ->where('orders.ordered_at', '>=', now()->subDays(13)->startOfDay())
            ->select(
                DB::raw('DATE(orders.ordered_at) as sale_date'),
                DB::raw('SUM(order_items.quantity) as quantity'),
                DB::raw('SUM(order_items.total_price) as revenue')
            )
            ->groupBy(DB::raw('DATE(orders.ordered_at)'))
            ->orderBy('sale_date')
            ->get()
            ->keyBy('sale_date');

        $salesTrend = collect(range(13, 0))->map(function ($daysAgo) use ($salesTrend) {
            $date = now()->subDays($daysAgo);
            $key = $date->toDateString();
            $row = $salesTrend->get($key);

            return [
                'label' => $date->format('M d'),
                'quantity' => $row ? (int) $row->quantity : 0,
                'revenue' => $row ? (float) $row->revenue : 0,
            ];
        });

        $recentSales = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->leftJoin('users', 'orders.user_id', '=', 'users.id')
            ->leftJoin('order_addresses', function ($join) {
                $join->on('orders.id', '=', 'order_addresses.order_id')
                    ->where('order_addresses.type', '=', 'shipping');
            })
            ->where('products.seller_id', $sellerId)
            ->where('orders.status', '!=', 'cancelled')
            ->select(
                'orders.id as order_id',
                'orders.order_number',
                'orders.ordered_at',
                'orders.status',
                'products.name as product_name',
                'order_items.quantity',
                'order_items.total_price',
                DB::raw('COALESCE(users.name, CONCAT(order_addresses.first_name, " ", order_addresses.last_name), "Guest Customer") as buyer_name'),
                DB::raw('COALESCE(users.email, order_addresses.email, "No email") as buyer_email'),
                DB::raw('COALESCE(users.phone, order_addresses.phone, "No phone") as buyer_phone')
            )
            ->orderByDesc('orders.ordered_at')
            ->limit(12)
            ->get();

        return view('seller.my-store', compact(
            'seller',
            'productCount',
            'orderCount',
            'revenue',
            'recentProducts',
            'stockReport',
            'totalInitialStock',
            'totalCurrentStock',
            'totalSoldQuantity',
            'salesTrend',
            'recentSales'
        ));
    })->name('seller.my-store');

    Route::get('/settings', [\App\Http\Controllers\seller\SettingsController::class, 'edit'])->name('seller.settings');
    Route::put('/settings', [\App\Http\Controllers\seller\SettingsController::class, 'update'])->name('seller.settings.update');
});
