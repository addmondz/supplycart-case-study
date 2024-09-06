<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->middleware('auth')->group(function () {
    Route::prefix('product')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('product.list');
        Route::post('/add-to-cart', [ProductController::class, 'addToCart'])->name('product.addToCart');
    });
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'listCartProducts'])->name('cart.listCartProducts');
        Route::put('/update-quantity', [CartController::class, 'updateQuantity'])->name('cart.updateQuantity');
    });
    Route::prefix('order')->group(function () {
        Route::post('/', [OrderController::class, 'create'])->name('order.create');
        Route::get('/', [OrderController::class, 'listOrderProducts'])->name('order.listOrderProducts');
    });

    Route::prefix('brand')->group(function () {
        Route::get('/', [BrandController::class, 'list'])->name('brand.list');
    });
    Route::prefix('category')->group(function () {
        Route::get('/', [CategoryController::class, 'list'])->name('brand.list');
    });
});
