<?php

namespace App\Http\Controllers\admin;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends AdminController
{
    public function index()
    {
        $sellerCount = User::where('role', 'seller')->count();
        $customerCount = User::where('role', 'customer')->count();
        $productCount = Product::count();
        $orderCount = Order::count();
        $categoryCount = Category::whereNull('seller_id')->count();
        $recentSellers = User::where('role', 'seller')->latest()->limit(5)->get();

        return view('admin.dashboard', compact(
            'sellerCount',
            'customerCount',
            'productCount',
            'orderCount',
            'categoryCount',
            'recentSellers'
        ));
    }
}
