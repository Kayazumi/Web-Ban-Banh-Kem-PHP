<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ComplaintController extends Controller
{
    /**
     * Display a listing of complaints.
     */
    public function index(Request $request)
    {
        $query = Complaint::with(['order', 'customer']);

        // Apply status filter
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Apply search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('complaint_code', 'LIKE', "%{$search}%")
                  ->orWhereHas('order', function($q) use ($search) {
                      $q->where('order_code', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('customer', function($q) use ($search) {
                      $q->where('full_name', 'LIKE', "%{$search}%");
                  });
            });
        }

        $complaints = $query->orderBy('created_at', 'desc')
                           ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => [
                'complaints' => $complaints->items()
            ],
            'pagination' => [
                'current_page' => $complaints->currentPage(),
                'last_page' => $complaints->lastPage(),
                'per_page' => $complaints->perPage(),
                'total' => $complaints->total(),
            ]
        ]);
    }

    /**
     * Display the specified complaint.
     */
    public function show($id)
    {
        $complaint = Complaint::with(['order', 'customer', 'responses'])->find($id);

        if (!$complaint) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy khiếu nại'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'complaint' => $complaint
            ]
        ]);
    }

    /**
     * Update complaint status.
     */
    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,processing,resolved,closed'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 400);
        }

        $complaint = Complaint::find($id);

        if (!$complaint) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy khiếu nại'
            ], 404);
        }

        $updateData = ['status' => $request->status];
        
        if ($request->status === 'resolved') {
            $updateData['resolved_at'] = now();
        }

        $complaint->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật trạng thái thành công',
            'data' => [
                'complaint' => $complaint
            ]
        ]);
    }

    /**
     * Update complaint resolution.
     */
    public function updateResolution(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'resolution' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 400);
        }

        $complaint = Complaint::find($id);

        if (!$complaint) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy khiếu nại'
            ], 404);
        }

        $complaint->update([
            'resolution' => $request->resolution,
            'status' => 'resolved',
            'resolved_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật giải quyết thành công',
            'data' => [
                'complaint' => $complaint
            ]
        ]);
    }
}
