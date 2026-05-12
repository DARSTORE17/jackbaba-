<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\SellerController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\ProfileController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\SettingsController;

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('admin.profile.update');

    Route::get('/users', [UserController::class, 'index'])->name('admin.users');
    Route::get('/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');

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

    Route::get('/settings', [SettingsController::class, 'edit'])->name('admin.settings');
    Route::post('/settings', [SettingsController::class, 'update'])->name('admin.settings.update');
});
