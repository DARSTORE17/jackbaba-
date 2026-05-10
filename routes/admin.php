<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\SellerController;
use App\Http\Controllers\admin\CategoryController;

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('/sellers', [SellerController::class, 'index'])->name('admin.sellers');
    Route::post('/sellers', [SellerController::class, 'store'])->name('admin.sellers.store');
    Route::put('/sellers/{id}', [SellerController::class, 'update'])->name('admin.sellers.update');
    Route::delete('/sellers/{id}', [SellerController::class, 'destroy'])->name('admin.sellers.destroy');

    Route::get('/categories', [CategoryController::class, 'index'])->name('admin.categories');
    Route::post('/categories', [CategoryController::class, 'store'])->name('admin.categories.store');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');

    Route::get('/products', [\App\Http\Controllers\admin\ProductController::class, 'index'])->name('admin.products');
    Route::get('/products/{id}/edit', [\App\Http\Controllers\admin\ProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('/products/{id}', [\App\Http\Controllers\admin\ProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/products/{id}', [\App\Http\Controllers\admin\ProductController::class, 'destroy'])->name('admin.products.destroy');

    Route::get('/settings', function () {
        return view('admin.settings');
    })->name('admin.settings');
});
