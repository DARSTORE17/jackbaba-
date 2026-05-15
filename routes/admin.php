<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\SellerController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\ProfileController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\SettingsController;
use App\Http\Controllers\admin\DatabaseController;
use App\Http\Controllers\admin\SecurityController;

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

    Route::get('/database', [DatabaseController::class, 'index'])->name('admin.database');
    Route::post('/database/backup', [DatabaseController::class, 'backup'])->name('admin.database.backup');
    Route::get('/database/{table}/edit', [DatabaseController::class, 'editTable'])->name('admin.database.edit');
    Route::put('/database/{table}', [DatabaseController::class, 'updateTable'])->name('admin.database.update');
    Route::post('/database/format', [DatabaseController::class, 'format'])->name('admin.database.format');
    Route::post('/database/import', [DatabaseController::class, 'import'])->name('admin.database.import');

    Route::get('/security', [SecurityController::class, 'index'])->name('admin.security.index');
    Route::get('/security/{log}', [SecurityController::class, 'show'])->name('admin.security.show');
    Route::delete('/security/{log}', [SecurityController::class, 'destroy'])->name('admin.security.destroy');
    Route::post('/security/clear', [SecurityController::class, 'clear'])->name('admin.security.clear');
    Route::get('/security/export', [SecurityController::class, 'export'])->name('admin.security.export');
Route::get('/security/test', function () {
    $securityLogger = app(\App\Services\SecurityLogger::class);

    // Test different security events
    $securityLogger->logEvent('test_sql_injection', 'high', 'Test SQL injection detected: SELECT * FROM users');
    $securityLogger->logEvent('test_xss_attempt', 'medium', 'Test XSS attempt detected: <script>alert("xss")</script>');
    $securityLogger->logEvent('test_suspicious_pattern', 'low', 'Test suspicious pattern detected');

    return response()->json(['message' => 'Test security events logged successfully']);
})->name('admin.security.test');
});
