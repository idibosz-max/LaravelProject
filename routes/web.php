<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes – DIB Productions
|--------------------------------------------------------------------------
*/

// ── Home ─────────────────────────────────────────────────────────────────────
Route::get('/', function () {
    $featured = \App\Models\Product::with('category')->active()->featured()->limit(8)->get();
    $categories = \App\Models\Category::all();
    return view('home', compact('featured', 'categories'));
})->name('home');

// ── Products ─────────────────────────────────────────────────────────────────
Route::get('/products',          [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/search',            [ProductController::class, 'search'])->name('products.search');

// ── Cart ─────────────────────────────────────────────────────────────────────
Route::get('/cart',                   [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add',              [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/{productId}',     [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{productId}',    [CartController::class, 'remove'])->name('cart.remove');

// Cart API (for React component)
Route::get('/api/cart',              [CartController::class, 'apiIndex'])->name('api.cart.index');
Route::post('/api/cart/add',         [CartController::class, 'apiAdd'])->name('api.cart.add');

// ── Checkout (auth required) ──────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/checkout',                    [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/payment',           [CheckoutController::class, 'payment'])->name('checkout.payment');
    Route::post('/checkout/store',             [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/success/{order}',    [CheckoutController::class, 'success'])->name('checkout.success');
});

// ── Admin (auth + admin role) ─────────────────────────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/',          [Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('products',  Admin\ProductController::class);
    Route::resource('categories', Admin\CategoryController::class);
    Route::get('orders',         [Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [Admin\OrderController::class, 'show'])->name('orders.show');
    Route::patch('orders/{order}/status', [Admin\OrderController::class, 'updateStatus'])->name('orders.status');
});

// ── Auth routes (Laravel Breeze) ─────────────────────────────────────────────
require __DIR__ . '/auth.php';
