<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

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

    public function products()
    {
        return view('products');
    }
}