<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::resource('cart', CartController::class);
Route::resource('checkout', CheckoutController::class);
Route::post('make-order', [OrderController::class, 'store'])->name('make.order');
Route::get('order/{order_number}', [OrderController::class, 'show'])->name('orders.show');


Route::get('/products', function () {
    return view('product');
});

Route::get('/product_detail/{slug}', [HomeController::class, 'productDetail'])->name('product.detail');

// Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');

Route::middleware('auth')->group(function () {          

    Route::middleware('admin')->group(function () {     
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::prefix('admin')->name('admin.')->group(function () {
            Route::resource('products', ProductController::class);
            Route::resource('product-categories', ProductCategoryController::class);
        });
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
