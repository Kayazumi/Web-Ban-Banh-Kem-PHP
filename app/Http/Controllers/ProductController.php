<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Get all products with filters
     */
    public function index(Request $request)
    {
        $query = Product::with('category')->active();

        // Apply filters
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('product_name', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%")
                    ->orWhereHas('category', function ($q) use ($search) {
                        $q->where('category_name', 'LIKE', "%{$search}%");
                    });
            });
        }

        if ($request->has('category') && !empty($request->category)) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('category_name', $request->category);
            });
        }

        if ($request->has('featured') && $request->featured == '1') {
            $query->featured();
        }

        // Price filter
        if ($request->has('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }

        if ($request->has('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(12);

        // Map products to API-friendly structure (include asset image URL)
        $mappedProducts = collect($products->items())->map(function ($product) {
            return [
                'product_id' => $product->ProductID,
                'product_name' => $product->product_name,
                'image_url' => asset($product->image_url ?: 'images/placeholder.jpg'),
                'price' => (float) $product->price,
                'original_price' => $product->original_price ? (float) $product->original_price : null,
                'is_featured' => (bool) $product->is_featured,
                'is_new' => (bool) $product->is_new,
                'short_intro' => $product->short_intro ?? null,
                'description' => $product->description ?? null,
            ];
        })->toArray();

        return response()->json([
            'success' => true,
            'data' => [
                'products' => $mappedProducts,
                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total(),
                ]
            ]
        ]);
    }

    /**
     * Get product by ID
     */
    public function show($id)
    {
        $product = Product::with(['category', 'productImages', 'reviews' => function ($query) {
            $query->approved()->with('user');
        }])->active()->find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy sản phẩm'
            ], 404);
        }

        // Increment view count
        $product->increment('views');

        // Map product and images for API consumer
        $mappedProduct = [
            'ProductID' => $product->ProductID,
            'product_id' => $product->ProductID,
            'product_name' => $product->product_name,
            'description' => $product->description,
            'short_intro' => $product->short_intro,
            'short_paragraph' => $product->short_paragraph,
            'structure' => $product->structure,
            'usage' => $product->usage,
            'bonus' => $product->bonus,
            'price' => (float) $product->price,
            'original_price' => $product->original_price ? (float) $product->original_price : null,
            'image_url' => asset($product->image_url ?: 'images/placeholder.jpg'),
            'is_featured' => (bool) $product->is_featured,
            'is_new' => (bool) $product->is_new,
            'quantity' => $product->quantity,
            'unit' => $product->unit,
            'status' => $product->status,
            'shelf_life' => $product->shelf_life,
            'category_name' => $product->category ? $product->category->category_name : null,
            'product_images' => collect($product->productImages)->map(function ($img) {
                return [
                    'image_id' => $img->ImageID ?? null,
                    'image_url' => asset($img->image_url),
                    'alt_text' => $img->alt_text ?? null,
                    'is_primary' => (bool) ($img->is_primary ?? false),
                ];
            })->toArray(),
            'reviews' => collect($product->reviews)->map(function ($r) {
                return [
                    'review_id' => $r->ReviewID ?? null,
                    'user_id' => $r->user_id ?? null,
                    'rating' => $r->rating ?? null,
                    'title' => $r->title ?? null,
                    'content' => $r->content ?? null,
                    'created_at' => $r->created_at ?? null,
                    'user' => $r->user ? [
                        'full_name' => $r->user->full_name ?? 'Người dùng ẩn danh'
                    ] : null,
                ];
            })->toArray(),
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'product' => $mappedProduct
            ]
        ]);
    }

    /**
     * Get categories
     */
    public function categories()
    {
        $categories = Category::active()->orderBy('display_order')->get();

        return response()->json([
            'success' => true,
            'data' => [
                'categories' => $categories
            ]
        ]);
    }

    /**
     * Get featured products
     */
       /**
     * Get featured products for homepage
     */
    public function featured()
    {
        try {
            $products = Product::where('is_active', true)
                ->where(function ($query) {
                    $query->where('is_featured', true)
                          ->orWhere('is_new', true); // Có thể thêm sản phẩm mới vào nổi bật
                })
                ->orderBy('is_featured', 'desc')
                ->orderBy('created_at', 'desc')
                ->limit(12) // Hiển thị tối đa 12 sản phẩm trên trang chủ
                ->get()
                ->map(function ($product) {
                    return [
                        'product_id' => $product->ProductID,
                        'product_name' => $product->product_name,
                        'image_url' => asset($product->image_url),
                        'price' => (float) $product->price,
                        'original_price' => $product->original_price ? (float) $product->original_price : null,
                        'is_featured' => $product->is_featured,
                        'is_new' => $product->is_new,
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => [
                    'products' => $products
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('API featured products error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Không thể tải sản phẩm nổi bật'
            ], 500);
        }
    }

    /**
     * Search products
     */
    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'q' => 'required|string|min:1|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Từ khóa tìm kiếm không hợp lệ'
            ], 400);
        }

        $query = Product::with('category')->active();

        $searchTerm = $request->q;
        $query->where(function ($q) use ($searchTerm) {
            $q->where('product_name', 'LIKE', "%{$searchTerm}%")
                ->orWhere('description', 'LIKE', "%{$searchTerm}%")
                ->orWhereHas('category', function ($q) use ($searchTerm) {
                    $q->where('category_name', 'LIKE', "%{$searchTerm}%");
                });
        });

        $products = $query->orderBy('created_at', 'desc')->limit(20)->get();

        return response()->json([
            'success' => true,
            'data' => [
                'products' => $products,
                'total' => $products->count()
            ]
        ]);
    }

    /**
     * Get active promotions for homepage
     */
    public function activePromotions()
    {
        try {
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
                    $query->where('quantity', -1) // Unlimited
                          ->orWhereRaw('quantity > used_count');
                })
                ->select('PromotionID', 'promotion_name', 'description', 'image_url', 'promotion_type', 'discount_value')
                ->limit(6)
                ->get()
                ->map(function ($promo) {
                    return [
                        'promotion_id' => $promo->PromotionID,
                        'promotion_name' => $promo->promotion_name,
                        'description' => $promo->description,
                        'image_url' => $promo->image_url ? asset($promo->image_url) : asset('images/placeholder.jpg'),
                        'promotion_type' => $promo->promotion_type,
                        'discount_value' => $promo->discount_value,
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => ['promotions' => $promotions]
            ]);
        } catch (\Exception $e) {
            Log::error('API active promotions error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Không thể tải khuyến mãi'
            ], 500);
        }
    }
}
