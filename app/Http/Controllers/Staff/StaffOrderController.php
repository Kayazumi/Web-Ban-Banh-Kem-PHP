<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StaffOrderController extends Controller
{
    /**
     * Hiển thị view danh sách đơn hàng
     */
    public function index()
    {
        return view('staff.orders.index');
    }

    /**
     * API: Lấy danh sách đơn hàng
     */
    public function apiIndex(Request $request)
    {
        try {
            $query = Order::with('customer')
            ->where('payment_status', 'paid')
                ->orderBy('created_at', 'desc');

            // Filter theo trạng thái
            if ($request->filled('status') && $request->status !== 'all') {
                $query->where('order_status', $request->status);
            }

            // Search theo mã đơn hoặc tên khách hàng
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('order_code', 'like', "%{$search}%")
                      ->orWhereHas('customer', function($q) use ($search) {
                          $q->where('full_name', 'like', "%{$search}%");
                      });
                });
            }

            $orders = $query->get()->map(function($order) {
                return [
                    'OrderID' => $order->order_code,
                    'customer_name' => $order->customer->full_name ?? 'N/A',
                    'customer_phone' => $order->customer_phone,
                    'TotalAmount' => $order->final_amount,
                    'Status' => $order->order_status,
                    'OrderDate' => $order->created_at,
                    'Phone' => $order->customer_phone,
                    'Address' => $order->shipping_address,
                    'note' => $order->note,
                    'payment_method' => $this->getPaymentMethodLabel($order->payment_method),
                ];
            });

            return response()->json([
                'success' => true,
                'orders' => $orders
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching orders: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Không thể tải danh sách đơn hàng'
            ], 500);
        }
    }

    /**
     * API: Lấy chi tiết đơn hàng
     */
    public function apiShow($orderCode)
    {
        try {
            $order = Order::with(['customer', 'orderItems.product'])
                ->where('order_code', $orderCode)
                ->first();

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy đơn hàng'
                ], 404);
            }

            $orderData = [
                'OrderID' => $order->order_code,
                'customer_name' => $order->customer->full_name ?? 'N/A',
                'customer_phone' => $order->customer_phone,
                'customer_email' => $order->customer_email,
                'Phone' => $order->customer_phone,
                'Address' => $order->shipping_address . ', ' . $order->ward . ', ' . $order->district . ', ' . $order->city,
                'OrderDate' => $order->created_at,
                'TotalAmount' => $order->final_amount,
                'payment_method' => $this->getPaymentMethodLabel($order->payment_method),
                'Status' => $order->order_status,
                'note' => $order->note,
                'delivery_date' => $order->delivery_date,
                'delivery_time' => $order->delivery_time,
                'items' => $order->orderItems->map(function($item) {
                    return [
                        'product_name' => $item->product_name,
                        'Quantity' => $item->quantity,
                        'Price' => $item->product_price,
                        'subtotal' => $item->subtotal,
                    ];
                })
            ];

            return response()->json([
                'success' => true,
                'order' => $orderData
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching order details: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Không thể tải chi tiết đơn hàng'
            ], 500);
        }
    }

    /**
     * API: Cập nhật trạng thái đơn hàng
     */
    public function apiUpdateStatus(Request $request, $orderCode)
    {
        $request->validate([
            'status' => 'required|in:pending,order_received,preparing,delivering,delivery_successful,delivery_failed'
        ]);

        DB::beginTransaction();
        try {
            $order = Order::where('order_code', $orderCode)->firstOrFail();
            
            $oldStatus = $order->order_status;
            $newStatus = $request->status;

            // Lưu lịch sử thay đổi trạng thái
            DB::table('order_status_history')->insert([
                'order_id' => $order->OrderID,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'changed_by' => Auth::id(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Cập nhật trạng thái
            $order->update([
                'order_status' => $newStatus,
                'staff_id' => Auth::id()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật trạng thái thành công!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating order status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Không thể cập nhật trạng thái'
            ], 500);
        }
    }

    /**
     * API: Cập nhật ghi chú đơn hàng
     */
    public function apiUpdateNote(Request $request, $orderCode)
    {
        $request->validate([
            'note' => 'nullable|string|max:1000'
        ]);

        try {
            $order = Order::where('order_code', $orderCode)->firstOrFail();
            
            $order->update([
                'note' => $request->note
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật ghi chú thành công!'
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating order note: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Không thể cập nhật ghi chú'
            ], 500);
        }
    }

    /**
     * Helper: Chuyển đổi payment_method
     */
    private function getPaymentMethodLabel($method)
    {
        $labels = [
            'cod' => 'Thanh toán khi nhận hàng (COD)',
            'vnpay' => 'Ví điện tử VNPay',
        ];
        return $labels[$method] ?? $method;
    }
}