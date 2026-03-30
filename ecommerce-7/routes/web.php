<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

Route::get('/contoh', [App\Http\Controllers\ContohController::class, 'index']);

Route::prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        echo "Admin Dashboard";
    });
Route::resource('products', ProductController::class);
});

Route::get('/products', function () {
    return view('product');
});

Route::get('/product_detail/{slug}', [HomeController::class, 'productDetail'])->name('product.detail');

Route::get('/checkout', function () {
    return view('checkout');
});
