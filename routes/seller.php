<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('seller')->group(function () {
    Route::get('/dashboard', function () {
        return view('seller.dashboard');
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
