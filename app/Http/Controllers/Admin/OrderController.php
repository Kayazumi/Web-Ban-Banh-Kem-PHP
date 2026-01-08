<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
            // Use the authenticated user's id (works with custom primaryKey)
            'changed_by' => Auth::id() ?? null,
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

    /**
     * Monthly revenue for a given year (returns map month => revenue)
     */
    public function monthlyRevenue(Request $request)
    {
        $year = (int) $request->get('year', date('Y'));

        // initialize result with 12 months for the year, default 0
        $result = [];
        for ($m = 1; $m <= 12; $m++) {
            $key = sprintf('%04d-%02d', $year, $m);
            $result[$key] = 0.0;
        }

        $driver = DB::connection()->getDriverName();
        $dateExpr = ($driver === 'sqlite')
            ? "strftime('%Y-%m', created_at)"
            : "DATE_FORMAT(created_at, '%Y-%m')";

        $rows = DB::table('orders')
            ->selectRaw("$dateExpr as month, SUM(final_amount) as revenue")
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        foreach ($rows as $r) {
            if (isset($result[$r->month])) {
                $result[$r->month] = (float) $r->revenue;
            }
        }

        return response()->json(['success' => true, 'data' => ['monthly' => $result]]);
    }

    /**
     * Product breakdown for a specific year-month (format YYYY-MM)
     */
    public function productBreakdown(Request $request)
    {
        $year = (int) $request->get('year', date('Y'));
        $month = (int) $request->get('month', date('n'));

        $start = \Carbon\Carbon::create($year, $month, 1)->startOfMonth()->toDateTimeString();
        $end = \Carbon\Carbon::create($year, $month, 1)->endOfMonth()->toDateTimeString();

        $rows = DB::table('order_items')
            ->join('orders', 'order_items.order_id', 'orders.OrderID')
            ->join('products', 'order_items.product_id', 'products.ProductID')
            ->selectRaw('products.product_name as name, SUM(order_items.quantity) as qty, SUM(order_items.subtotal) as revenue')
            ->whereBetween('orders.created_at', [$start, $end])
            ->groupBy('products.product_name')
            ->orderByDesc('qty')
            ->get();

        $names = $rows->pluck('name')->toArray();
        $qtys = $rows->pluck('qty')->map(function($v){ return (int)$v; })->toArray();
        $revenues = $rows->pluck('revenue')->map(function($v){ return (float)$v; })->toArray();

        return response()->json(['success' => true, 'data' => ['names' => $names, 'qtys' => $qtys, 'revenues' => $revenues]]);
    }
}
