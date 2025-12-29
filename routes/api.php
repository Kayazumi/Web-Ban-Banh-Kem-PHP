<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\UserController as AdminUserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Authentication API routes
Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login');
    Route::post('/register', 'register');
    Route::post('/logout', 'logout')->middleware('auth');
    Route::get('/user', 'user')->middleware('auth');
});

// Public API routes
Route::controller(ProductController::class)->group(function () {
    Route::get('/products', 'index');
    Route::get('/products/featured', 'featured');
    Route::get('/products/search', 'search');
    Route::get('/products/{id}', 'show');
    Route::get('/categories', 'categories');
});

// Protected API routes (require authentication)
Route::middleware('auth')->group(function () {
    // Cart API
    Route::controller(CartController::class)->group(function () {
        Route::get('/cart', 'index');
        Route::post('/cart', 'store');
        Route::put('/cart/{id}', 'update');
        Route::delete('/cart/{id}', 'destroy');
        Route::delete('/cart', 'clear');
    });

    // Order API
    Route::controller(OrderController::class)->group(function () {
        Route::get('/orders', 'index');
        Route::post('/orders', 'store');
        Route::get('/orders/{id}', 'show');
        Route::post('/orders/{id}/cancel', 'cancel');
    });
});

// Admin API routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // Admin Product API
    Route::apiResource('/products', AdminProductController::class);

    // Admin Order API
    Route::get('/orders', [AdminOrderController::class, 'index']);
    Route::get('/orders/{id}', [AdminOrderController::class, 'show']);
    Route::put('/orders/{id}/status', [AdminOrderController::class, 'updateStatus']);
    Route::get('/orders/statistics', [AdminOrderController::class, 'statistics']);

    // Admin User API
    Route::apiResource('/users', AdminUserController::class);
    Route::put('/users/{id}/status', [AdminUserController::class, 'updateStatus']);
    Route::put('/users/{id}/password', [AdminUserController::class, 'changePassword']);
});
