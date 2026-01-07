<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\ComplaintResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StaffComplaintController extends Controller
{
    // Lấy danh sách khiếu nại (JSON)
    public function apiIndex(Request $request) {
        $query = Complaint::with(['customer', 'order']);

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('complaint_code', 'like', "%$search%")
                  ->orWhere('title', 'like', "%$search%")
                  ->orWhereHas('customer', function($sq) use ($search) {
                      $sq->where('full_name', 'like', "%$search%");
                  });
            });
        }

        $complaints = $query->orderBy('created_at', 'desc')->get();
        return response()->json(['success' => true, 'data' => $complaints]);
    }

    // Lấy chi tiết 1 khiếu nại và thông tin nhân viên đang trực
    public function apiShow($id) {
        $complaint = Complaint::with(['customer', 'responses.user'])->find($id);
        
        if (!$complaint) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy khiếu nại'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $complaint,
            'current_staff' => [
                'id' => Auth::id(),
                'full_name' => Auth::user()->full_name
            ]
        ]);
    }

    // Cập nhật phản hồi và trạng thái
    public function apiUpdate(Request $request, $id) {
    $request->validate([
        'status' => 'required|in:pending,processing,resolved',
        'response_content' => 'required|string|min:5',
        'send_to_customer' => 'sometimes|boolean'
    ]);

    DB::beginTransaction();
    try {
        $complaint = Complaint::findOrFail($id);

        $sendToCustomer = $request->boolean('send_to_customer', false);

        // Nếu gửi khách thì buộc phải là resolved
        $finalStatus = $sendToCustomer ? 'resolved' : $request->status;

        $complaint->update([
            'status' => $finalStatus,
            'assigned_to' => Auth::id(),
            'resolved_at' => $finalStatus === 'resolved' ? now() : null
        ]);

        ComplaintResponse::create([
            'complaint_id' => $id,
            'user_id' => Auth::id(),
            'user_type' => 'staff',
            'content' => $request->response_content
            // Nếu muốn lưu lịch sử đã gửi: thêm cột sent_to_customer sau
        ]);

        // === NƠI BẠN SẼ GỬI EMAIL/THÔNG BÁO CHO KHÁCH SAU NÀY ===
        if ($sendToCustomer) {
            // TODO: Gửi mail, SMS, hoặc push notification ở đây
            // Ví dụ: Mail::to($complaint->customer->email)->send(...);
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => $sendToCustomer
                ? 'Đã gửi phản hồi cho khách hàng!'
                : 'Đã lưu phản hồi tạm!'
        ]);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}
}