<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Promotion;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        // Gọi thẳng method featured() đã có sẵn trong ProductController
        $productController = new ProductController();
        $response = $productController->featured();
        $data = $response->getData(true); // true để lấy array thay vì object

        $featuredProducts = $data['data']['products'] ?? [];

        // Nếu bạn muốn lấy thêm danh mục để hiển thị menu
        $categoriesResponse = $productController->categories();
        $categoriesData = $categoriesResponse->getData(true);
        $categories = $categoriesData['data']['categories'] ?? [];

        return view('home', compact('featuredProducts', 'categories'));
    }

    /**
     * Render homepage as a guest (no user data exposed to JS/layout)
     */
    public function guest()
    {
        // reuse index logic to fetch featured products and categories
        $productController = new ProductController();
        $response = $productController->featured();
        $data = $response->getData(true);

        $featuredProducts = $data['data']['products'] ?? [];

        $categoriesResponse = $productController->categories();
        $categoriesData = $categoriesResponse->getData(true);
        $categories = $categoriesData['data']['categories'] ?? [];

        // pass a flag to the view to render as guest (used by layout to avoid exposing Auth user)
        return view('home', compact('featuredProducts', 'categories'))->with('asGuest', true);
    }

    public function products()
    {
        return view('products');
    }

    public function productDetail($id)
    {
        return view('product-detail', ['productId' => $id]);
    }

    public function checkout(Request $request)
    {
        $items = [];
        $subtotal = 0;

        // Case 1: Buy Now (Direct purchase of one item)
        if ($request->has('product_id') && $request->has('quantity')) {
            $product = Product::find($request->product_id);
            if ($product) {
                $quantity = (int) $request->quantity;
                $items[] = [
                    'id' => $product->ProductID,
                    'name' => $product->product_name,
                    'price' => $product->price,
                    'quantity' => $quantity,
                    'image' => $product->image_url // Optional for view
                ];
                $subtotal += $product->price * $quantity;
            }
        }
        // Case 2: Checkout from Cart
        else {
            $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
            foreach ($cartItems as $cartItem) {
                if ($cartItem->product) {
                    $items[] = [
                        'id' => $cartItem->product->ProductID,
                        'name' => $cartItem->product->product_name,
                        'price' => $cartItem->product->price,
                        'quantity' => $cartItem->quantity,
                        'image' => $cartItem->product->image_url
                    ];
                    $subtotal += $cartItem->product->price * $cartItem->quantity;
                }
            }
        }

        $vat = $subtotal * 0.08;
        $total = $subtotal + $vat;

        // Fetch active promotions
        $promotions = Promotion::where('status', 'active')
            ->where(function($query) {
                $query->whereNull('start_date')
                      ->orWhere('start_date', '<=', now());
            })
            ->where(function($query) {
                $query->whereNull('end_date')
                      ->orWhere('end_date', '>=', now());
            })
            ->where(function($query) {
                $query->where('quantity', -1)
                      ->orWhereRaw('quantity > used_count');
            })
            ->get();

        return view('orders', compact('items', 'subtotal', 'vat', 'total', 'promotions'));
    }

    public function history()
    {
        $orders = \App\Models\Order::where('customer_id', Auth::id())
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
        return view('order-history', compact('orders'));
    }

    public function orderDetail($id)
    {
        $order = \App\Models\Order::with(['orderItems.product', 'staff', 'statusHistory'])
             ->where('customer_id', Auth::id())
             ->findOrFail($id);
        return view('order-detail', ['order' => $order, 'orderId' => $id]);
    }
}