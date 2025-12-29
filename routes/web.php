<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;

// Public routes
Route::get('/', function () {
    return view('home');
})->name('home');

// Authentication routes
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login')->middleware('guest');
    Route::post('/login', 'login')->name('login.post')->middleware('guest');
    Route::get('/register', 'showRegisterForm')->name('register')->middleware('guest');
    Route::post('/register', 'register')->name('register.post')->middleware('guest');
    Route::post('/logout', 'logout')->name('logout')->middleware('auth');
});

// Protected routes (require authentication)
Route::middleware('auth')->group(function () {
    // User profile
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');

    // Cart routes
    Route::get('/cart', function () {
        return view('cart');
    })->name('cart');

    // Order routes
    Route::get('/orders', function () {
        return view('orders');
    })->name('orders.index');

    Route::get('/orders/{id}', function ($id) {
        return view('order-detail', ['orderId' => $id]);
    })->name('orders.show');

    // Product routes
    Route::get('/products', function () {
        return view('products');
    })->name('products.index');

    Route::get('/products/{id}', function ($id) {
        return view('product-detail', ['productId' => $id]);
    })->name('products.show');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::get('/products', function () {
        return view('admin.products');
    })->name('products');

    Route::get('/orders', function () {
        return view('admin.orders');
    })->name('orders');

    Route::get('/users', function () {
        return view('admin.users');
    })->name('users');
});
