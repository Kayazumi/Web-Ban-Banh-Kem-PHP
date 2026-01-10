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
     * Show customer's order history page
     */
    public function myOrders()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $orders = Order::where('customer_id', Auth::id())
                      ->with(['orderItems.product'])
                      ->orderBy('created_at', 'desc')
                      ->paginate(5);

        return view('order-history', compact('orders'));
    }

    /**
     * Get user's orders (API)
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
            'payment_method' => 'required|in:cod,bank_transfer',
            'note' => 'nullable|string|max:500',
            'delivery_time' => 'required|date|after:+2 hours',
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

        $validItems = [];
        $totalAmount = 0;
        $shouldClearCart = false;

        // 1. Try DB Cart
        if ($cartItems->isNotEmpty()) {
            $shouldClearCart = true;
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
        } 
        // 2. Try Request Items (Direct Buy)
        elseif ($request->has('items') && is_array($request->items)) {
            foreach ($request->items as $itemData) {
                if (!isset($itemData['id']) || !isset($itemData['quantity'])) continue;

                $product = \App\Models\Product::find($itemData['id']);
                $qty = (int)$itemData['quantity'];

                if (!$product || !$product->is_active || $product->quantity < $qty) {
                    $pName = $product ? $product->product_name : 'Sản phẩm';
                    return response()->json([
                        'success' => false,
                        'message' => "Sản phẩm '$pName' không đủ số lượng hoặc không khả dụng"
                    ], 400);
                }
                
                $sub = $product->price * $qty;
                $totalAmount += $sub;
                
                $validItems[] = [
                    'product' => $product,
                    'quantity' => $qty,
                    'subtotal' => $sub,
                    'note' => null
                ];
            }
        }

        // 3. Process Gift Items
        if ($request->has('gift_items') && is_array($request->gift_items)) {
            foreach ($request->gift_items as $giftData) {
                if (!isset($giftData['id'])) continue;
                $product = \App\Models\Product::find($giftData['id']);
                if ($product) {
                    $validItems[] = [
                        'product' => $product,
                        'quantity' => (int)($giftData['quantity'] ?? 1),
                        'subtotal' => 0, // Free
                        'note' => 'Quà tặng'
                    ];
                }
            }
        }

        if (empty($validItems)) {
            return response()->json([
                'success' => false,
                'message' => 'Giỏ hàng trống hoặc không hợp lệ'
            ], 400);
        }

        // Calculate VAT and Shipping to match logic in HomeController::checkout
        // Frontend: VAT = 8%, Shipping = Free
        $vatRate = 0.08;
        $vatAmount = round($totalAmount * $vatRate);
        $shippingFee = 0; // Free shipping
        
        // Apply 10% Discount
        $discountRate = 0.10;
        $discountAmount = round($totalAmount * $discountRate);
        
        $finalAmount = $totalAmount + $vatAmount + $shippingFee - $discountAmount;

        DB::beginTransaction();

        try {
            // Reserve stock atomically for each product using a conditional UPDATE.
            // If any update affects 0 rows => not enough stock, rollback and return conflict.
            foreach ($validItems as $item) {
                $pid = $item['product']->ProductID;
                $qty = (int)$item['quantity'];

                $affected = DB::update(
                    'UPDATE products SET quantity = quantity - ? WHERE ProductID = ? AND quantity >= ?',
                    [$qty, $pid, $qty]
                );

                if ($affected === 0) {
                    // Not enough stock for this product — rollback and notify client
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Sản phẩm '{$item['product']->product_name}' không đủ số lượng"
                    ], 409);
                }
            }

            // All stock reserved successfully (within the transaction) — create order and items
            // Generate order code
            $orderCode = 'ORD' . date('Ymd') . strtoupper(Str::random(6));

            // Parse delivery_time to extract date and time
            $deliveryDateTime = \Carbon\Carbon::parse($request->delivery_time);

            // Set payment_status based on payment_method
            // Bank transfer: auto-mark as paid (customer sees QR and pays immediately)
            // COD: pending until delivery
            $paymentStatus = ($request->payment_method === 'bank_transfer') ? 'paid' : 'pending';

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
                'discount_amount' => $discountAmount, // Save discount amount if column exists, otherwise optional
                'payment_method' => $request->payment_method,
                'payment_status' => $paymentStatus,
                'note' => $request->note,
                'delivery_date' => $deliveryDateTime->toDateString(),
                'delivery_time' => $deliveryDateTime->format('H:i'),
            ]);

            // Create order items and increment sold_count
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

                // Increment sold_count atomically
                DB::update(
                    'UPDATE products SET sold_count = sold_count + ? WHERE ProductID = ?',
                    [$item['quantity'], $item['product']->ProductID]
                );
            }

            // Clear cart only if items came from DB cart
            if ($shouldClearCart) {
                Cart::where('user_id', Auth::id())->delete();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Đặt hàng thành công',
                'data' => [
                    'order' => $order->load('orderItems.product'),
                    'redirect' => route('orders.success', $order->OrderID)
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

    /**
     * Order success page
     */
    public function success($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $order = Order::with(['orderItems.product'])->find($id);

        if (!$order) {
            abort(404, 'Không tìm thấy đơn hàng');
        }

        // Only show to order owner
        if ($order->customer_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền xem đơn hàng này');
        }

        // Generate VietQR URL if bank transfer
        $qrUrl = null;
        if ($order->payment_method === 'bank_transfer') {
            $qrUrl = $this->generateVietQR($order);
        }

        return view('order-success', compact('order', 'qrUrl'));
    }

    /**
     * Generate VietQR dynamic URL
     */
    private function generateVietQR($order)
    {
        $accountNo = '0817966088';
        $accountName = urlencode('LACUISINE NGOT');
        $amount = (int) $order->final_amount;
        $description = urlencode($order->order_code);
        $template = 'compact2'; // or 'compact', 'print', 'qr_only'
        
        // VietQR format: https://img.vietqr.io/image/{bin}-{account}-{template}.png?amount={amount}&addInfo={info}&accountName={name}
        // Using default bank BIN for VietQR (you can specify exact bank if needed)
        return "https://img.vietqr.io/image/970422-{$accountNo}-{$template}.png?amount={$amount}&addInfo={$description}&accountName={$accountName}";
    }
}
