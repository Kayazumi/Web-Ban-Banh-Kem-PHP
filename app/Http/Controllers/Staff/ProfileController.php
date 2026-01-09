<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    // Hiển thị trang giao diện hồ sơ
    public function index()
    {
        return view('staff.profile.index');
    }

    // API Cập nhật thông tin cá nhân
    public function update(UpdateProfileRequest $request)
    {
        /** @var \App\Models\User $user */ // Sửa lỗi gạch đỏ chữ update
        $user = Auth::user();

        $user->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật thành công!',
            'data' => new UserResource($user)
        ]);
    }

    // API Đổi mật khẩu
    public function changePassword(UpdatePasswordRequest $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Kiểm tra mật khẩu cũ
        if (!Hash::check($request->oldPassword, $user->password_hash)) {
            return response()->json(['success' => false, 'message' => 'Mật khẩu cũ không đúng'], 401);
        }

        // Lưu mật khẩu mới
        $user->update(['password_hash' => Hash::make($request->newPassword)]);

        return response()->json(['success' => true, 'message' => 'Đổi mật khẩu thành công!']);
    }
}
