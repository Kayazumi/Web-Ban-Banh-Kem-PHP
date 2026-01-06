<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class StaffMiddleware
{
    /**
     * Kiểm tra quyền truy cập của nhân viên.
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Vui lòng đăng nhập để tiếp tục.'], 401);
            }
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($user->role === 'staff' || $user->role === 'admin') {
            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Bạn không có quyền truy cập vùng này.'], 403);
        }

        return redirect('/')->with('error', 'Bạn không có quyền truy cập trang nhân viên.');
    }
}
