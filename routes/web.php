<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ContactController;

// 1. TRANG CHỦ - Luôn hiển thị trang chủ cho mọi người
Route::get('/', function () {
    if (Auth::check() && Auth::user()->role === 'staff') {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    }
    return app()->call(HomeController::class . '@index');
})->name('home');
Route::get('/products', [HomeController::class, 'products'])->name('products');
Route::get('/products/{id}', [HomeController::class, 'productDetail'])->name('products.detail');

// Order routes
Route::post('/orders', [\App\Http\Controllers\OrderController::class, 'store'])->name('orders.store')->middleware('auth');
Route::get('/orders/{id}/success', [\App\Http\Controllers\OrderController::class, 'success'])->name('orders.success')->middleware('auth');
// Redirect old my-orders route to order.history (backward compatibility)
Route::get('/my-orders', function () {
    return redirect()->route('order.history');
})->middleware('auth');


// DEBUG: Temporary route to check order/user data
Route::get('/debug-orders', function () {
    $user = Auth::user();
    $orders = \App\Models\Order::where('customer_id', $user->UserID)
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->get();

    $allOrders = \App\Models\Order::orderBy('created_at', 'desc')->take(5)->get();

    return view('debug-orders', compact('user', 'orders', 'allOrders'));
})->middleware('auth');


// Guest view route: open public site without exposing authenticated user data to JS/layout
Route::get('/guest', [HomeController::class, 'guest'])->name('guest.home');

// 2. AUTHENTICATION - Cho khách chưa đăng nhập
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
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

        $latestCreatedAt = DB::table('orders')
            ->whereNotNull('created_at')
            ->orderBy('created_at', 'desc')
            ->value('created_at');

        $defaultYear = $latestCreatedAt ? (int)\Carbon\Carbon::parse($latestCreatedAt)->format('Y') : (int)date('Y');
        $defaultMonth = $latestCreatedAt ? (int)\Carbon\Carbon::parse($latestCreatedAt)->format('n') : 1;

        $ordersForMonthly = DB::table('orders')
            ->select('created_at', 'final_amount')
            ->whereNotNull('created_at')
            ->whereYear('created_at', $defaultYear)
            ->get();

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
        ksort($monthly);

        $topProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', 'products.ProductID')
            ->selectRaw('products.product_name as name, SUM(order_items.quantity) as qty, SUM(order_items.subtotal) as revenue')
            ->groupBy('products.product_name')
            ->orderByDesc('qty')
            ->limit(10)
            ->get();

        $laravelUser = Auth::user();
        return view('admin.dashboard', compact('totalRevenue', 'totalOrders', 'deliveredCount', 'newCustomers', 'monthly', 'topProducts', 'laravelUser', 'defaultYear', 'defaultMonth'));
    })->name('dashboard');

    Route::get('/reports/monthly', [\App\Http\Controllers\Admin\OrderController::class, 'monthlyRevenue']);
    Route::get('/reports/products', [\App\Http\Controllers\Admin\OrderController::class, 'productBreakdown']);

    Route::get('/promotions', function () {
        return view('admin.promotions');
    })->name('promotions');

    Route::get('/users', function () {
        return view('admin.users');
    })->name('users');

    Route::get('/products', function () {
        return view('admin.products');
    })->name('products');

    Route::get('/orders', function () {
        return view('admin.orders');
    })->name('orders');

    Route::get('/complaints', function () {
        return view('admin.complaints');
    })->name('complaints');

    // ❌ XÓA 2 DÒNG NÀY - Không cần route API riêng cho admin
    // Route::post('/api/profile', [ProfileController::class, 'update'])->name('api.profile.update');
    // Route::post('/api/password', [ProfileController::class, 'changePassword'])->name('api.password.update');
});

// 5. ROUTE CHUNG CHO NGƯỜI DÙNG ĐÃ ĐĂNG NHẬP (CUSTOMER / USER THƯỜNG)
Route::middleware('auth')->group(function () {
    Route::get('/profile', function () {
        $user = Auth::user();
        if ($user->role === 'staff') {
            return redirect()->route('staff.profile');
        }
        return view('profile');
    })->name('profile');

    // ✅ API cập nhật hồ sơ chung cho mọi user (Admin, Staff, Customer)
    // QUAN TRỌNG: Không cần withoutMiddleware vì không có middleware 'admin' ở đây
    Route::post('/api/user/profile', [ProfileController::class, 'update'])
        ->name('user.profile.update');

    Route::post('/api/user/password', [ProfileController::class, 'changePassword'])
        ->name('user.password.update');

    Route::get('/cart', function () {
        return view('cart');
    })->name('cart');

    Route::get('/orders', [HomeController::class, 'checkout'])->name('orders');
    Route::get('/oderdetail', [HomeController::class, 'history'])->name('order.history');
    Route::get('/oderdetail/{id}', [HomeController::class, 'orderDetail'])->name('order.details');
    Route::post('/orders/{id}/submit-complaint', [\App\Http\Controllers\OrderController::class, 'submitComplaint'])->name('orders.submit-complaint');

    Route::get('/fix-db-schema', function () {
        try {
            Illuminate\Support\Facades\Schema::table('contacts', function ($table) {
                if (!Illuminate\Support\Facades\Schema::hasColumn('contacts', 'name')) {
                    $table->string('name')->nullable()->after('customer_id');
                }
                if (!Illuminate\Support\Facades\Schema::hasColumn('contacts', 'email')) {
                    $table->string('email')->nullable()->after('name');
                }
                $table->unsignedBigInteger('customer_id')->nullable()->change();
            });
            return "Schema fixed successfully!";
        } catch (\Exception $e) {
            return "Error: " . $e->getMessage();
        }
    });

    Route::post('/api/contacts', [ContactController::class, 'store']);
});

// 6. ROUTE CHO NHÂN VIÊN (STAFF)
require __DIR__ . '/staff.php';
