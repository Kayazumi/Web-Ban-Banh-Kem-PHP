<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    /**
     * Get user's cart
     */
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để xem giỏ hàng'
            ], 401);
        }

        $cartItems = Cart::with(['product.category'])
                        ->where('user_id', Auth::id())
                        ->orderBy('created_at', 'desc')
                        ->get();

        $total = 0;
        $totalItems = 0;

        foreach ($cartItems as $item) {
            if ($item->product) {
                $item->subtotal = $item->product->price * $item->quantity;
                $total += $item->subtotal;
                $totalItems += $item->quantity;
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'cart_items' => $cartItems,
                'total' => $total,
                'total_items' => $totalItems
            ]
        ]);
    }

    /**
     * Add item to cart
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để thêm vào giỏ hàng'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,ProductID',
            'quantity' => 'required|integer|min:1',
            'note' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 400);
        }

        $product = Product::active()->available()->find($request->product_id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không tồn tại hoặc đã hết hàng'
            ], 404);
        }

        if ($request->quantity > $product->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Số lượng sản phẩm trong kho không đủ'
            ], 400);
        }

        // Check if item already exists in cart
        $cartItem = Cart::where('user_id', Auth::id())
                       ->where('product_id', $request->product_id)
                       ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $request->quantity;

            if ($newQuantity > $product->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tổng số lượng sản phẩm trong giỏ hàng vượt quá số lượng có sẵn'
                ], 400);
            }

            $cartItem->update([
                'quantity' => $newQuantity,
                'note' => $request->note ?: $cartItem->note
            ]);
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'note' => $request->note
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Đã thêm sản phẩm vào giỏ hàng'
        ]);
    }

    /**
     * Update cart item
     */
    public function update(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1',
            'note' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 400);
        }

        $cartItem = Cart::where('user_id', Auth::id())
                       ->where('CartID', $id)
                       ->first();

        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy sản phẩm trong giỏ hàng'
            ], 404);
        }

        $product = Product::find($cartItem->product_id);

        if (!$product || $request->quantity > $product->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Số lượng sản phẩm không đủ'
            ], 400);
        }

        $cartItem->update([
            'quantity' => $request->quantity,
            'note' => $request->note
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đã cập nhật giỏ hàng'
        ]);
    }

    /**
     * Remove item from cart
     */
    public function destroy($id)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập'
            ], 401);
        }

        $cartItem = Cart::where('user_id', Auth::id())
                       ->where('CartID', $id)
                       ->first();

        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy sản phẩm trong giỏ hàng'
            ], 404);
        }

        $cartItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa sản phẩm khỏi giỏ hàng'
        ]);
    }

    /**
     * Clear cart
     */
    public function clear()
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập'
            ], 401);
        }

        Cart::where('user_id', Auth::id())->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa tất cả sản phẩm trong giỏ hàng'
        ]);
    }
}
