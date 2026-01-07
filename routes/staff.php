<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Staff\ProfileController;
use App\Http\Controllers\Staff\StaffOrderController;

Route::middleware(['auth', 'staff'])->prefix('staff')->name('staff.')->group(function () {

    // Profile (đã có)
Route::get('/profile', [ProfileController::class, 'index'])->name('profile');

    // Trang quản lý đơn hàng
Route::get('/orders', [StaffOrderController::class, 'index'])->name('orders.index');

// Trang khiếu nại của nhân viên
Route::get('/complaints', function() { return view('staff.complaints.index'); })->name('complaints.index');
// API Routes cho Orders
Route::prefix('api')->group(function () {
    Route::get('/orders', [StaffOrderController::class, 'apiIndex']);
    Route::get('/orders/{orderCode}', [StaffOrderController::class, 'apiShow']);
    Route::put('/orders/{orderCode}/status', [StaffOrderController::class, 'apiUpdateStatus']);
    Route::put('/orders/{orderCode}/note', [StaffOrderController::class, 'apiUpdateNote']);
});

    // API Routes cho Complaints
Route::prefix('api')->group(function () {
    Route::get('/complaints', [App\Http\Controllers\Staff\StaffComplaintController::class, 'apiIndex']);
    Route::get('/complaints/{id}', [App\Http\Controllers\Staff\StaffComplaintController::class, 'apiShow']);
    Route::post('/complaints/{id}/respond', [App\Http\Controllers\Staff\StaffComplaintController::class, 'apiUpdate']);
});

});