<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Staff\ProfileController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\DB;

// 1. TRANG CHỦ - Luôn hiển thị trang chủ cho mọi người
Route::get('/', [HomeController::class, 'index'])->name('home');

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

// 4. ROUTE DÀNH CHO ADMIN
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        // summary metrics
        $totalRevenue = DB::table('orders')->sum('final_amount') ?: 0;
        $totalOrders = DB::table('orders')->count();
        $deliveredCount = DB::table('orders')->where('order_status', 'delivery_successful')->count();
        $newCustomers = DB::table('users')->where('role', 'customer')->count();

        // monthly revenue for current year (computed in PHP for DB compatibility)
        // choose default year: latest year that has orders, otherwise current year
        $yearRow = DB::table('orders')
            ->selectRaw("MAX(strftime('%Y', created_at)) as max_year")
            ->whereNotNull('created_at')
            ->first();
        $defaultYear = $yearRow && $yearRow->max_year ? (int)$yearRow->max_year : (int)date('Y');

        $ordersForMonthly = DB::table('orders')
            ->select('created_at','final_amount')
            ->whereNotNull('created_at')
            ->whereYear('created_at', $defaultYear)
            ->get();
        // choose default month: latest month with orders in defaultYear, otherwise 1
        $monthRow = DB::table('orders')
            ->selectRaw("MAX(strftime('%m', created_at)) as max_month")
            ->whereYear('created_at', $defaultYear)
            ->first();
        $defaultMonth = $monthRow && $monthRow->max_month ? (int)$monthRow->max_month : 1;
        $monthly = [];
        foreach ($ordersForMonthly as $o) {
            try {
                $m = \Carbon\Carbon::parse($o->created_at)->format('Y-m');
            } catch (\Exception $e) {
                continue;
            }
            if (!isset($monthly[$m])) $monthly[$m] = 0;
            $monthly[$m] += (float) $o->final_amount;
        }
        // keep ordered by month
        ksort($monthly);

        // top products by quantity sold
        $topProducts = DB::table('order_items')
            ->join('products','order_items.product_id','products.ProductID')
            ->selectRaw('products.product_name as name, SUM(order_items.quantity) as qty, SUM(order_items.subtotal) as revenue')
            ->groupBy('products.product_name')
            ->orderByDesc('qty')
            ->limit(10)
            ->get();

        $laravelUser = Auth::user();
        return view('admin.dashboard', compact('totalRevenue','totalOrders','deliveredCount','newCustomers','monthly','topProducts','laravelUser','defaultYear','defaultMonth'));
    })->name('dashboard');

    // Reports endpoints (session-authenticated) for dashboard charts
    Route::get('/reports/monthly', [\App\Http\Controllers\Admin\OrderController::class, 'monthlyRevenue']);
    Route::get('/reports/products', [\App\Http\Controllers\Admin\OrderController::class, 'productBreakdown']);
    // Admin promotions page (web)
    Route::get('/promotions', function () {
        return view('admin.promotions');
    })->middleware(['auth','admin'])->name('promotions');

    Route::get('/users', function () {
        return view('admin.users');
    })->name('users');
    
    // Admin: products management (web)
    Route::get('/products', function () {
        return view('admin.products');
    })->name('products');

    // Admin: orders management (web)
    Route::get('/orders', function () {
        return view('admin.orders');
    })->name('orders');
    
    // Reports removed
});

// 5. ROUTE CHUNG CHO NGƯỜI DÙNG ĐÃ ĐĂNG NHẬP (CUSTOMER / USER THƯỜNG)
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
