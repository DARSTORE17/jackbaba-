<?php

use Illuminate\Support\Facades\Route;

// Customer Routes (all require authentication)
Route::middleware(['auth'])->group(function () {
    Route::get('/customer/dashboard', [App\Http\Controllers\CustomerController::class, 'dashboard'])->name('customer.dashboard');
    Route::get('/customer/orders', [App\Http\Controllers\CustomerController::class, 'orders'])->name('customer.orders');
    Route::patch('/customer/orders/{order}/cancel', [App\Http\Controllers\CustomerController::class, 'cancelOrder'])->name('customer.orders.cancel');
    Route::patch('/customer/orders/{order}/items/{orderItem}', [App\Http\Controllers\CustomerController::class, 'updateOrderItem'])->name('customer.orders.items.update');
    Route::delete('/customer/orders/{order}/items/{orderItem}', [App\Http\Controllers\CustomerController::class, 'removeOrderItem'])->name('customer.orders.items.remove');
    Route::get('/customer/order/{order}', [App\Http\Controllers\CustomerController::class, 'orderDetails'])->name('customer.order.details');
    Route::get('/customer/profile', [App\Http\Controllers\CustomerController::class, 'profile'])->name('customer.profile');
    Route::put('/customer/profile', [App\Http\Controllers\CustomerController::class, 'updateProfile'])->name('customer.profile.update');
    Route::get('/customer/wishlist', [App\Http\Controllers\WishlistController::class, 'index'])->name('customer.wishlist');
    Route::get('/customer/addresses', [App\Http\Controllers\CustomerController::class, 'addresses'])->name('customer.addresses');
    Route::get('/customer/support', [App\Http\Controllers\CustomerController::class, 'support'])->name('customer.support');

    // Wishlist API routes
    Route::post('/wishlist/toggle', [App\Http\Controllers\WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::post('/wishlist/check', [App\Http\Controllers\WishlistController::class, 'check'])->name('wishlist.check');
    Route::delete('/wishlist/{id}', [App\Http\Controllers\WishlistController::class, 'destroy'])->name('wishlist.destroy');
});
