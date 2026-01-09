<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Staff\ProfileController;
use App\Http\Controllers\Staff\StaffOrderController;
use App\Http\Controllers\Staff\StaffComplaintController;
use App\Http\Controllers\Staff\ContactController;

// MỞ NHÓM: Tất cả các route bên trong này sẽ có tiền tố tên là 'staff.'
Route::middleware(['auth', 'staff'])->prefix('staff')->name('staff.')->group(function () {

    // 1. Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');

    // 2. Trang quản lý (Web Views)
    Route::get('/orders', [StaffOrderController::class, 'index'])->name('orders.index');
    Route::get('/complaints', function() { return view('staff.complaints.index'); })->name('complaints.index');
    Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');

    // 3. Toàn bộ API Routes
    Route::prefix('api')->group(function () {
        
        // API cho Profile
        Route::match(['PUT', 'POST'], '/profile', [ProfileController::class, 'update'])->name('api.profile.update');
        Route::post('/password', [ProfileController::class, 'changePassword'])->name('api.profile.password');

        // API cho Orders
        Route::get('/orders', [StaffOrderController::class, 'apiIndex']);
        Route::get('/orders/{orderCode}', [StaffOrderController::class, 'apiShow']);
        Route::put('/orders/{orderCode}/status', [StaffOrderController::class, 'apiUpdateStatus']);
        Route::put('/orders/{orderCode}/note', [StaffOrderController::class, 'apiUpdateNote']);

        // API cho Complaints
        Route::get('/complaints', [StaffComplaintController::class, 'apiIndex']);
        Route::get('/complaints/{id}', [StaffComplaintController::class, 'apiShow']);
        Route::post('/complaints/{id}/respond', [StaffComplaintController::class, 'apiUpdate']);

        // API cho Contacts
        Route::get('/contacts', [ContactController::class, 'apiIndex']);
        Route::get('/contacts/{id}', [ContactController::class, 'apiShow']);
        Route::put('/contacts/{id}/status', [ContactController::class, 'apiUpdateStatus']);
    });

}); // ĐÓNG NHÓM ở cuối cùng này