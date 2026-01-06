<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Lấy tất cả sản phẩm đang active, sắp xếp theo display order hoặc mới nhất
        $products = Product::where('is_active', true)->get();

        // Hoặc chỉ lấy sản phẩm nổi bật trước
        // $products = Product::where('is_featured', true)->where('is_active', true)->get();

        return view('home', compact('products'));
    }
}