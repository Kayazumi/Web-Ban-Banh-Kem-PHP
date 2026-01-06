<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Staff\ProfileController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;

// 1. TRANG CHỦ - Luôn hiển thị trang chủ cho mọi người
Route::get('/', function () {
    return view('home');
})->name('home');

// 2. AUTHENTICATION - Cho khách chưa đăng nhập
Route::middleware('guest')->group(function () {
    // Hiển thị form đăng nhập
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

    // Nếu bạn có đăng ký
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');

    // QUAN TRỌNG: Route xử lý AJAX login - ĐỔI THÀNH /login
    Route::post('/login', [AuthController::class, 'login']);
});

// 3. Đăng xuất - Dành cho người đã đăng nhập
Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// 4. ROUTE DÀNH CHO NHÂN VIÊN (STAFF)
Route::middleware(['auth', 'staff'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/api/profile', [ProfileController::class, 'update']);
    Route::post('/api/password', [ProfileController::class, 'changePassword']);
});

// 5. ROUTE DÀNH CHO ADMIN
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::get('/users', function () {
        return view('admin.users');
    })->name('users');
});

// 6. ROUTE CHUNG CHO NGƯỜI DÙNG ĐÃ ĐĂNG NHẬP (CUSTOMER / USER THƯỜNG)
Route::middleware('auth')->group(function () {
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');

    Route::get('/cart', function () {
        return view('cart');
    })->name('cart');

    Route::get('/orders', function () {
        return view('orders.index');
    })->name('orders.index');
});

Route::get('/', [HomeController::class, 'index'])->name('home');