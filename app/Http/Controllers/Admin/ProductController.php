<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Apply filters
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('product_name', 'LIKE', "%{$search}%");
        }

        if ($request->has('category') && !empty($request->category)) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('category_name', $request->category);
            });
        }

        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        $products = $query->orderBy('created_at', 'desc')
                          ->paginate(15);

        $items = collect($products->items())->map(function($p) {
            // ensure consistent JSON shape expected by frontend
            return array_merge(
                is_array($p) ? $p : $p->toArray(),
                [
                    'category_name' => isset($p->category) && $p->category ? ($p->category->category_name ?? null) : null,
                    'category_id' => isset($p->category) && $p->category ? ($p->category->CategoryID ?? null) : ($p->category_id ?? null),
                ]
            );
        })->all();

        return response()->json([
            'success' => true,
            'data' => $items,
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_name' => 'required|string|max:200',
            'category_id' => 'required|exists:categories,CategoryID',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'unit' => 'required|string|max:20',
            'status' => 'required|in:available,out_of_stock,discontinued',
            'image_url' => 'nullable|url',
            'description' => 'nullable|string',
            'is_featured' => 'boolean',
            'is_new' => 'boolean',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 400);
        }

        $product = Product::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Thêm sản phẩm thành công',
            'data' => $product
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = Product::with('category')->find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy sản phẩm'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy sản phẩm'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'product_name' => 'sometimes|required|string|max:200',
            'category_id' => 'sometimes|required|exists:categories,CategoryID',
            'price' => 'sometimes|required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'quantity' => 'sometimes|required|integer|min:0',
            'unit' => 'sometimes|required|string|max:20',
            'status' => 'sometimes|required|in:available,out_of_stock,discontinued',
            'image_url' => 'nullable|url',
            'description' => 'nullable|string',
            'is_featured' => 'boolean',
            'is_new' => 'boolean',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 400);
        }

        $product->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật sản phẩm thành công',
            'data' => $product
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy sản phẩm'
            ], 404);
        }

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa sản phẩm thành công'
        ]);
    }
}
