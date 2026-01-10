<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\ComplaintResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class StaffComplaintController extends Controller
{
    /**
     * API: Danh sách khiếu nại
     */
    public function apiIndex()
    {
        try {
            $complaints = Complaint::with(['customer', 'order'])
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($complaint) {
                    return [
                        'id' => $complaint->ComplaintID,
                        'complaint_id' => $complaint->ComplaintID,
                        'ComplaintID' => $complaint->ComplaintID,
                        'complaint_code' => $complaint->complaint_code,
                        'order' => $complaint->order ? [
                            'order_code' => $complaint->order->order_code
                        ] : null,
                        'customer' => $complaint->customer ? [
                            'full_name' => $complaint->customer->full_name,
                            'phone' => $complaint->customer->phone,
                            'email' => $complaint->customer->email
                        ] : null,
                        'title' => $complaint->title,
                        'content' => $complaint->content,
                        'status' => $complaint->status,
                        'created_at' => $complaint->created_at
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $complaints
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Chi tiết khiếu nại
     */
    public function apiShow($id)
    {
        try {
            $complaint = Complaint::with(['customer', 'order', 'responses'])
                ->findOrFail($id);

            // ✅ Lấy phản hồi mới nhất từ bảng complaint_responses
            $latestResponse = $complaint->responses()
                ->where('user_type', 'staff')
                ->latest()
                ->first();

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $complaint->ComplaintID,
                    'complaint_code' => $complaint->complaint_code,
                    'order' => $complaint->order,
                    'customer' => $complaint->customer,
                    'title' => $complaint->title,
                    'content' => $complaint->content,
                    'status' => $complaint->status,
                    'response' => $latestResponse ? $latestResponse->content : '', // ✅ Hiển thị response cũ
                    'created_at' => $complaint->created_at
                ],
                'current_staff' => [
                    'id' => Auth::id(),
                    'full_name' => Auth::user()->full_name
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 404);
        }
    }

    /**
     * ✅ API: Phản hồi khiếu nại (LƯU VÀO complaint_responses)
     */
        /**
     * API: Phản hồi khiếu nại (lưu tạm hoặc gửi khách)
     */
    public function apiUpdate($id, Request $request)
    {
        DB::beginTransaction();

        try {
            $complaint = Complaint::findOrFail($id);

            // ✅ SỬA: Check nếu đã resolved, không cho update nữa
            if ($complaint->status === 'resolved') {
                return response()->json([
                    'success' => false,
                    'message' => 'Khiếu nại đã được giải quyết, không thể chỉnh sửa nữa!'
                ], 403);
            }

            $validated = $request->validate([
                'response_content' => 'required|string|min:10',
                'send_to_customer' => 'boolean'  // true: gửi khách, false: lưu tạm
            ]);

            // ✅ SỬA: Lưu response với updateOrCreate để tránh duplicate (update nếu đã có cho staff này)
            ComplaintResponse::updateOrCreate(
                [
                    'complaint_id' => $id,
                    'user_id' => Auth::id(),
                    'user_type' => 'staff'
                ],
                [
                    'content' => $validated['response_content'],
                    // TODO: Nếu cần field 'is_draft', add đây: 'is_draft' => !$request->send_to_customer
                ]
            );

            // ✅ SỬA: Update status complaints dựa trên send_to_customer
            $newStatus = $request->send_to_customer ? 'resolved' : 'processing';
            $complaint->update([
                'status' => $newStatus,
                'resolution' => $validated['response_content'],  // Lưu resolution = response cuối
                'resolved_at' => $request->send_to_customer ? now() : null
                // TODO: Nếu có compensation, add validate và update đây
            ]);

            // 3. Gửi email nếu send_to_customer = true
            if ($request->send_to_customer) {
                // TODO: Thêm logic gửi email (giữ nguyên)
                // Mail::to($complaint->customer->email)->send(new ComplaintResponseMail(...));
                
                Log::info('Email sẽ gửi đến: ' . $complaint->customer->email);
                Log::info('Nội dung: ' . $validated['response_content']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $request->send_to_customer 
                    ? 'Đã gửi phản hồi cho khách hàng và giải quyết khiếu nại!'
                    : 'Đã lưu tạm phản hồi thành công (trạng thái: đang xử lý)!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }
}