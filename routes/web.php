<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\auth\AuthController;

Route::get('/', function () {
    $featuredCategories = \App\Models\Category::whereNull('seller_id')
        ->orderBy('name')
        ->take(4)
        ->get();

    return view('home', compact('featuredCategories'));
});


Route::get('/about', function () {
    return view('about');
});



// Shop Routes
Route::get('/categories', [\App\Http\Controllers\ShopController::class, 'categories'])->name('categories');
Route::get('/shop', [\App\Http\Controllers\ShopController::class, 'index'])->name('shop');
Route::get('/shop/{slug}', [\App\Http\Controllers\ShopController::class, 'show'])->name('shop.show');
Route::get('/category/{slug}', [\App\Http\Controllers\ShopController::class, 'category'])->name('category.show');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

require __DIR__.'/shop.php';
require __DIR__.'/seller.php';
require __DIR__.'/customer.php';
require __DIR__.'/admin.php';
