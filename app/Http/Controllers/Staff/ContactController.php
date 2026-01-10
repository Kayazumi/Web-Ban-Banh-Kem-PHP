<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    /**
     * Hiển thị trang quản lý liên hệ
     */
    public function index()
    {
        return view('staff.contacts.index');
    }

    /**
     * API: Lấy danh sách liên hệ
     */
    public function apiIndex()
    {
        try {
            // ✅ Lấy TRỰC TIẾP từ bảng contacts (không JOIN users)
            // Vì đã lưu snapshot name, email, phone trong contacts
            $contacts = Contact::select(
                'contacts.ContactID',
                'contacts.customer_id',
                'contacts.name as customer_name',      // ✅ Từ form
                'contacts.email as customer_email',    // ✅ Từ form
                'contacts.phone as customer_phone',    // ✅ Từ form
                'contacts.subject as Subject',
                'contacts.message as Message',
                'contacts.status as Status',
                'contacts.created_at as CreatedAt',
                'contacts.responded_at as RespondedAt',
                'staff.full_name as staff_name'
            )
            ->leftJoin('users as staff', 'contacts.responded_by', '=', 'staff.UserID')
            ->orderBy('contacts.created_at', 'desc')
            ->get();

            return response()->json([
                'success' => true,
                'contacts' => $contacts
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi tải danh sách: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Xem chi tiết liên hệ
     */
    public function apiShow($id)
    {
        try {
            // ✅ SỬA: Dùng select EXPLICIT với ALIAS GIỐNG apiIndex (uppercase đầu) để nhất quán keys trong JSON
            $contact = Contact::select(
                'contacts.ContactID',
                'contacts.customer_id',
                'contacts.name as customer_name',
                'contacts.email as customer_email',
                'contacts.phone as customer_phone',
                'contacts.subject as Subject',
                'contacts.message as Message',
                'contacts.status as Status',
                'contacts.created_at as CreatedAt',
                'contacts.responded_at as RespondedAt',
                'staff.full_name as staff_name'
            )
            ->leftJoin('users as staff', 'contacts.responded_by', '=', 'staff.UserID')
            ->where('contacts.ContactID', $id)
            ->firstOrFail();

            return response()->json([
                'success' => true,
                'contact' => $contact
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy liên hệ'
            ], 404);
        }
    }

    /**
     * API: Đánh dấu đã phản hồi
     */
    public function apiUpdateStatus($id, Request $request)
    {
        try {
            $contact = Contact::findOrFail($id);
            
            // Cập nhật trạng thái
            $contact->update([
                'status' => 'responded',
                'responded_at' => now(),
                'responded_by' => Auth::id()
            ]);

            // Lấy tên staff để trả về
            $contact->staff_name = Auth::user()->full_name;

            return response()->json([
                'success' => true,
                'message' => 'Đã đánh dấu phản hồi thành công',
                'contact' => $contact
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }
}