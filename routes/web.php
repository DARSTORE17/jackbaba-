<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\auth\AuthController;
use App\Models\Product;

Route::get('/', function () {
    $featuredCategories = \App\Models\Category::whereNull('seller_id')
        ->orderBy('name')
        ->take(6)
        ->get();

    $homeFeaturedProducts = Product::where('is_advertised', true)
        ->orderByDesc('rate')
        ->take(4)
        ->get();

    if ($homeFeaturedProducts->isEmpty()) {
        $homeFeaturedProducts = Product::orderByDesc('rate')
            ->take(4)
            ->get();
    }

    $homeBestSellers = Product::whereNull('seller_id')
        ->orderByDesc('rate')
        ->take(4)
        ->get();

    $homeNewArrivals = Product::whereNull('seller_id')
        ->orderByDesc('created_at')
        ->take(4)
        ->get();

    return view('home', compact('featuredCategories', 'homeFeaturedProducts', 'homeBestSellers', 'homeNewArrivals'));
});


Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::get('/privacy-policy', function () {
    return view('legal.page', [
        'pageTitle' => 'Privacy Policy',
        'pageHeading' => 'Our Commitment to Privacy',
        'pageContent' => [
            'At Bravus Market, we respect your privacy and are committed to protecting your personal information. We only collect data that helps us provide better service and a more relevant shopping experience.',
            'We do not sell your personal information to third parties without your consent. Information collected may include your name, email address, shipping details, and order history in order to fulfill orders and respond to customer support requests.',
            'We use industry-standard security measures to protect your data. If you have questions about how your information is used, please contact us through the contact page.',
        ],
    ]);
})->name('privacy.policy');

Route::get('/terms-and-conditions', function () {
    return view('legal.page', [
        'pageTitle' => 'Terms & Conditions',
        'pageHeading' => 'Terms and Conditions',
        'pageContent' => [
            'These terms and conditions govern your use of Bravus Market. By using our site, you agree to comply with these terms and any policies referenced here.',
            'All products are sold subject to availability and our right to refuse or cancel orders at our discretion. Prices, promotions, and product information may change without prior notice.',
            'Your use of our site is subject to local laws and regulations. If you need clarification on any terms, please contact our support team.',
        ],
    ]);
})->name('terms.conditions');

Route::get('/refund-policy', function () {
    return view('legal.page', [
        'pageTitle' => 'Refund Policy',
        'pageHeading' => 'Refund Policy',
        'pageContent' => [
            'If you are not satisfied with your purchase, please contact us within the refund period stated at checkout. Refunds are issued according to the condition of the returned item and our refund guidelines.',
            'Products must be returned in their original packaging with all accessories included. We may request proof of purchase before processing a refund.',
            'Refunds are usually processed within 7-10 business days after we receive the returned item. If you have questions about your refund, contact our support team for assistance.',
        ],
    ]);
})->name('refund.policy');

Route::get('/delivery-information', function () {
    return view('legal.page', [
        'pageTitle' => 'Delivery Information',
        'pageHeading' => 'Delivery Information',
        'pageContent' => [
            'We offer nationwide delivery across Tanzania. Delivery times depend on your location and are displayed during checkout for each order.',
            'Orders are processed quickly after payment confirmation. Once shipped, you will receive tracking details so you can follow your package until it arrives.',
            'If you have questions about delivery, returns, or shipping fees, please contact our customer support team and we will gladly assist you.',
        ],
    ]);
})->name('delivery.information');



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
