<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Get user's orders
     */
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập'
            ], 401);
        }

        $query = Order::with(['orderItems.product', 'staff'])
                     ->where('customer_id', Auth::id());

        // Filter by status
        if ($request->has('status')) {
            $query->where('order_status', $request->status);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(10);

        return response()->json([
            'success' => true,
            'data' => [
                'orders' => $orders->items(),
                'pagination' => [
                    'current_page' => $orders->currentPage(),
                    'last_page' => $orders->lastPage(),
                    'per_page' => $orders->perPage(),
                    'total' => $orders->total(),
                ]
            ]
        ]);
    }

    /**
     * Get order by ID
     */
    public function show($id)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập'
            ], 401);
        }

        $order = Order::with(['orderItems.product', 'staff', 'statusHistory'])
                     ->where('customer_id', Auth::id())
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
     * Create order from cart
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để đặt hàng'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'required|string|max:15',
            'customer_email' => 'required|email|max:100',
            'shipping_address' => 'required|string|max:255',
            'ward' => 'nullable|string|max:50',
            'district' => 'nullable|string|max:50',
            'city' => 'nullable|string|max:50',
            'payment_method' => 'required|in:cod,vnpay',
            'note' => 'nullable|string|max:500',
            'delivery_date' => 'nullable|date|after:today',
            'delivery_time' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 400);
        }

        // Get user's cart
        $cartItems = Cart::with('product')
                        ->where('user_id', Auth::id())
                        ->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Giỏ hàng trống'
            ], 400);
        }

        // Calculate totals and validate stock
        $totalAmount = 0;
        $validItems = [];

        foreach ($cartItems as $cartItem) {
            if (!$cartItem->product || !$cartItem->product->is_active || $cartItem->product->quantity < $cartItem->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => "Sản phẩm '{$cartItem->product->product_name}' không đủ số lượng hoặc không khả dụng"
                ], 400);
            }

            $subtotal = $cartItem->product->price * $cartItem->quantity;
            $totalAmount += $subtotal;

            $validItems[] = [
                'product' => $cartItem->product,
                'quantity' => $cartItem->quantity,
                'subtotal' => $subtotal,
                'note' => $cartItem->note
            ];
        }

        $shippingFee = 30000; // Fixed shipping fee
        $finalAmount = $totalAmount + $shippingFee;

        DB::beginTransaction();

        try {
            // Generate order code
            $orderCode = 'ORD' . date('Ymd') . strtoupper(Str::random(6));

            // Create order
            $order = Order::create([
                'order_code' => $orderCode,
                'customer_id' => Auth::id(),
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'customer_email' => $request->customer_email,
                'shipping_address' => $request->shipping_address,
                'ward' => $request->ward,
                'district' => $request->district,
                'city' => $request->city,
                'total_amount' => $totalAmount,
                'shipping_fee' => $shippingFee,
                'final_amount' => $finalAmount,
                'payment_method' => $request->payment_method,
                'note' => $request->note,
                'delivery_date' => $request->delivery_date,
                'delivery_time' => $request->delivery_time,
            ]);

            // Create order items
            foreach ($validItems as $item) {
                OrderItem::create([
                    'order_id' => $order->OrderID,
                    'product_id' => $item['product']->ProductID,
                    'product_name' => $item['product']->product_name,
                    'product_price' => $item['product']->price,
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['subtotal'],
                    'note' => $item['note'],
                ]);

                // Update product sold count and quantity
                $item['product']->increment('sold_count', $item['quantity']);
                $item['product']->decrement('quantity', $item['quantity']);
            }

            // Clear cart
            Cart::where('user_id', Auth::id())->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Đặt hàng thành công',
                'data' => [
                    'order' => $order->load('orderItems.product'),
                    'redirect' => route('orders.show', $order->OrderID)
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi đặt hàng: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel order
     */
    public function cancel(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'reason' => 'required|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 400);
        }

        $order = Order::where('customer_id', Auth::id())
                     ->where('OrderID', $id)
                     ->whereIn('order_status', ['pending', 'order_received'])
                     ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể hủy đơn hàng này'
            ], 400);
        }

        DB::beginTransaction();

        try {
            // Update order
            $order->update([
                'order_status' => 'cancelled',
                'cancelled_at' => now(),
                'cancellation_reason' => $request->reason
            ]);

            // Restore product quantities
            foreach ($order->orderItems as $item) {
                $item->product->increment('quantity', $item->quantity);
                $item->product->decrement('sold_count', $item->quantity);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Đã hủy đơn hàng thành công'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi hủy đơn hàng'
            ], 500);
        }
    }
}
