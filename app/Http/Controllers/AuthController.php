<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Show register form
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Handle login
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 400);
        }

        $credentials = $request->only('username', 'password');

        // Try to find user by username or email
        $user = User::where('username', $credentials['username'])
                   ->orWhere('email', $credentials['username'])
                   ->first();

        if (!$user || !Hash::check($credentials['password'], $user->password_hash)) {
            return response()->json([
                'success' => false,
                'message' => 'Tên đăng nhập hoặc mật khẩu không chính xác'
            ], 401);
        }

        // Check if account is active
        if ($user->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Tài khoản đã bị khóa'
            ], 403);
        }

        // Update last login
        $user->update(['last_login' => now()]);

        // Login user
        Auth::login($user);

        // Return user data
        return response()->json([
            'success' => true,
            'message' => 'Đăng nhập thành công',
            'data' => [
                'user' => $user,
                'redirect' => $user->isAdmin() ? route('admin.dashboard') : route('home')
            ]
        ]);
    }

    /**
     * Handle register
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|unique:users,username|max:50',
            'email' => 'required|email|unique:users,email|max:100',
            'password' => 'required|string|min:6|confirmed',
            'full_name' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 400);
        }

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password_hash' => Hash::make($request->password),
            'full_name' => $request->full_name,
            'phone' => $request->phone,
            'address' => $request->address,
            'role' => 'customer',
            'status' => 'active',
        ]);

        Auth::login($user);

        return response()->json([
            'success' => true,
            'message' => 'Đăng ký thành công',
            'data' => [
                'user' => $user,
                'redirect' => route('home')
            ]
        ]);
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'success' => true,
            'message' => 'Đăng xuất thành công'
        ]);
    }

    /**
     * Get current authenticated user
     */
    public function user(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'user' => Auth::user()
            ]
        ]);
    }
}
