<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

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

            // Create Sanctum token for API access
            $token = $user->createToken('api-token')->plainTextToken;

            $redirect = match ($user->role) {
                // After admin login, land on product management page
                'admin' => route('admin.products'),
                'staff' => route('staff.profile'),
                default => route('home'),
            };

            return response()->json([
                'success' => true,
                'message' => 'Đăng nhập thành công!',
                'data' => [
                    'redirect' => $redirect,
                    'token' => $token,
                    'user' => [
                        'id' => $user->UserID,
                        'username' => $user->username,
                        'role' => $user->role,
                        'full_name' => $user->full_name
                    ]
                ]
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

    /**
     * Hiển thị trang đăng ký
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Xử lý đăng ký tài khoản
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username',
            'email' => 'required|email|max:150|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        $fullName = trim($request->first_name . ' ' . $request->last_name);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password_hash' => Hash::make($request->password),
            'full_name' => $fullName,
            'phone' => $request->phone,
            'address' => $request->address,
            'role' => 'customer',
            'status' => 'active',
        ]);
        // Don't auto-login; return success and redirect to login page
        return response()->json([
            'success' => true,
            'message' => 'Tài khoản của bạn đã được tạo!',
            'data' => [
                'redirect' => route('login')
            ]
        ], 201);
    }
}