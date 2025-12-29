<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Display a listing of orders.
     */
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'staff']);

        // Apply filters
        if ($request->has('status') && !empty($request->status)) {
            $query->where('order_status', $request->status);
        }

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_code', 'LIKE', "%{$search}%")
                  ->orWhere('customer_name', 'LIKE', "%{$search}%")
                  ->orWhere('customer_email', 'LIKE', "%{$search}%")
                  ->orWhere('customer_phone', 'LIKE', "%{$search}%");
            });
        }

        $orders = $query->orderBy('created_at', 'desc')
                        ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => [
                'orders' => $orders->items()
            ],
            'pagination' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
            ]
        ]);
    }

    /**
     * Display the specified order.
     */
    public function show($id)
    {
        $order = Order::with(['customer', 'staff', 'orderItems.product', 'statusHistory'])
                     ->find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy đơn hàng'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'order' => $order
            ]
        ]);
    }

    /**
     * Update order status.
     */
    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,order_received,preparing,delivering,delivery_successful,delivery_failed,cancelled',
            'note' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 400);
        }

        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy đơn hàng'
            ], 404);
        }

        $oldStatus = $order->order_status;
        $newStatus = $request->status;

        if ($oldStatus === $newStatus) {
            return response()->json([
                'success' => false,
                'message' => 'Trạng thái mới phải khác trạng thái hiện tại'
            ], 400);
        }

        // Update order status
        $updateData = ['order_status' => $newStatus];

        // Set timestamps based on status
        if ($newStatus === 'order_received') {
            $updateData['confirmed_at'] = now();
        } elseif ($newStatus === 'delivery_successful') {
            $updateData['completed_at'] = now();
        } elseif (in_array($newStatus, ['delivery_failed', 'cancelled'])) {
            $updateData['cancelled_at'] = now();
            $updateData['cancellation_reason'] = $request->note;
        }

        $order->update($updateData);

        // Create status history record
        OrderStatusHistory::create([
            'order_id' => $order->OrderID,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'changed_by' => auth()->id(),
            'note' => $request->note
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật trạng thái đơn hàng thành công',
            'data' => [
                'order' => $order
            ]
        ]);
    }

    /**
     * Get order statistics.
     */
    public function statistics(Request $request)
    {
        $period = $request->get('period', 'month'); // month, week, day

        $query = Order::query();

        // Apply date filter based on period
        switch ($period) {
            case 'week':
                $query->where('created_at', '>=', now()->startOfWeek());
                break;
            case 'month':
                $query->where('created_at', '>=', now()->startOfMonth());
                break;
            case 'day':
                $query->whereDate('created_at', today());
                break;
        }

        $stats = [
            'total_orders' => (clone $query)->count(),
            'total_revenue' => (clone $query)->where('order_status', 'delivery_successful')->sum('final_amount'),
            'pending_orders' => (clone $query)->where('order_status', 'pending')->count(),
            'completed_orders' => (clone $query)->where('order_status', 'delivery_successful')->count(),
            'cancelled_orders' => (clone $query)->whereIn('order_status', ['cancelled', 'delivery_failed'])->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
