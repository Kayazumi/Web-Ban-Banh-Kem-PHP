<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Carbon\Carbon;

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

            /** @var \App\Models\User $user */
            $user = Auth::user();

            // Create Sanctum token for API access
            $token = $user->createToken('api-token')->plainTextToken;

            // Check if there's an intended redirect URL (for customers only)
            $intendedUrl = $request->input('redirect_url');
            
            // Security: Only allow relative URLs to prevent open redirect
            if ($intendedUrl && !preg_match('/^\/[^\/]/', $intendedUrl)) {
                $intendedUrl = null;
            }

            $redirect = match ($user->role) {
                'admin' => route('admin.dashboard'),
                'staff' => route('staff.profile'),
                default => $intendedUrl ?: route('home'),
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

    // Đăng xuất
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

    // Hiển thị trang đăng ký
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Xử lý đăng ký tài khoản
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

        return response()->json([
            'success' => true,
            'message' => 'Tài khoản của bạn đã được tạo!',
            'data' => [
                'redirect' => route('login')
            ]
        ], 201);
    }

    // ========== FORGOT PASSWORD FUNCTIONALITY ==========

    /**
     * Hiển thị form quên mật khẩu
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Gửi link reset mật khẩu qua email
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ], [
            'email.required' => 'Vui lòng nhập địa chỉ email',
            'email.email' => 'Địa chỉ email không hợp lệ',
            'email.exists' => 'Email này không tồn tại trong hệ thống'
        ]);

        // Tạo token
        $token = Str::random(64);

        // Xóa token cũ (nếu có)
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Lưu token mới
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => Hash::make($token),
            'created_at' => Carbon::now()
        ]);

        // Tạo link reset
        $resetLink = route('password.reset', ['token' => $token, 'email' => $request->email]);

        // Gửi email
        try {
            Mail::send('emails.reset-password', ['resetLink' => $resetLink], function($message) use ($request) {
                $message->to($request->email);
                $message->subject('Đặt lại mật khẩu - La Cuisine Ngọt');
            });

            return response()->json([
                'success' => true,
                'message' => 'Chúng tôi đã gửi link đặt lại mật khẩu đến email của bạn!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi khi gửi email. Vui lòng thử lại sau.'
            ], 500);
        }
    }

    /**
     * Hiển thị form reset mật khẩu
     */
    public function showResetPasswordForm(Request $request, $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    /**
     * Xử lý reset mật khẩu
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed'
        ], [
            'email.required' => 'Vui lòng nhập email',
            'email.exists' => 'Email không tồn tại',
            'password.required' => 'Vui lòng nhập mật khẩu mới',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp'
        ]);

        // Kiểm tra token
        $passwordReset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$passwordReset) {
            return back()->withErrors(['email' => 'Link đặt lại mật khẩu không hợp lệ']);
        }

        // Kiểm tra token có khớp không
        if (!Hash::check($request->token, $passwordReset->token)) {
            return back()->withErrors(['email' => 'Link đặt lại mật khẩu không hợp lệ']);
        }

        // Kiểm tra token đã hết hạn chưa (24 giờ)
        if (Carbon::parse($passwordReset->created_at)->addHours(24)->isPast()) {
            return back()->withErrors(['email' => 'Link đặt lại mật khẩu đã hết hạn']);
        }

        // Cập nhật mật khẩu mới
        $user = User::where('email', $request->email)->first();
        $user->password_hash = Hash::make($request->password);
        $user->save();

        // Xóa token đã sử dụng
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('status', 'Mật khẩu của bạn đã được đặt lại thành công!');
    }
}