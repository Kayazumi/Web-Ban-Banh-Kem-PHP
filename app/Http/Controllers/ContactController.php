<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
class ContactController extends Controller
{
    /**
     * Khách hàng gửi liên hệ (YÊU CẦU ĐĂNG NHẬP)
     */
    public function store(Request $request)
    {
        // ✅ Kiểm tra đăng nhập
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để gửi liên hệ'
            ], 401);
        }

        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // ✅ Lưu thông tin SNAPSHOT từ form vào database
            $contact = Contact::create([
                // Lưu customer_id từ user đã đăng nhập
                'customer_id' => Auth::id(),
                
                // ✅ QUAN TRỌNG: Lưu thông tin từ FORM, không lấy từ users table
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'subject' => $request->subject,
                'message' => $request->message,
                'status' => 'pending'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Gửi tin nhắn thành công! Chúng tôi sẽ liên hệ lại sớm nhất.',
                'data' => $contact
            ], 201);

        } catch (\Exception $e) {
            Log::error('Contact form error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi gửi tin nhắn',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}