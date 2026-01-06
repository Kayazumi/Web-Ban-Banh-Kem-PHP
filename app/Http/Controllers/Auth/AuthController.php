<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Hiển thị trang đăng nhập
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Xử lý đăng nhập bằng AJAX (trả JSON)
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            $request->session()->regenerate();

            $user = Auth::user();

            $redirect = match ($user->role) {
                'admin' => route('admin.dashboard'),
                'staff' => route('staff.profile'),
                default => route('home'),
            };

            return response()->json([
                'success' => true,
                'message' => 'Đăng nhập thành công!',
                'data' => ['redirect' => $redirect]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Tên đăng nhập hoặc mật khẩu không đúng.'
        ], 401);
    }

    // Đăng xuất (hỗ trợ cả AJAX và thường)
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'redirect' => route('login')
            ]);
        }

        return redirect('/login');
    }
}