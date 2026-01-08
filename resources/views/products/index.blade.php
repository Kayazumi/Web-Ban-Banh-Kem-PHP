@extends('layouts.app')

@section('title', 'Danh sách sản phẩm - La Cuisine Ngọt')

@section('content')
<div class="container py-5">
    <h1 class="text-center mb-5" style="color: #324F29; font-family: 'Inspiration', cursive; font-size: 3rem;">Sản phẩm</h1>
    
    <div id="productsContainer">
        <div class="text-center">
            <div class="spinner-border text-success" role="status">
                <span class="sr-only">Đang tải...</span>
            </div>
            <p>Đang tải sản phẩm...</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    loadProducts();
});

async function loadProducts() {
    try {
        const response = await fetch('/api/products');
        const data = await response.json();
        
        const container = document.getElementById('productsContainer');
        
        if (data.success && data.data.products.length > 0) {
            const products = data.data.products;
            
            let html = '<div class="row">';
            products.forEach(product => {
                html += `
                    <div class="col-md-4 col-sm-6 mb-4">
                        <div class="product-card">
                            <a href="/products/${product.ProductID}">
                                <div class="product-image">
                                    <img src="${product.image_url}" alt="${product.product_name}">
                                </div>
                                <div class="product-info">
                                    <h3 class="product-name">${product.product_name}</h3>
                                    <p class="product-category">${product.category_name || ''}</p>
                                    <div class="product-price">
                                        ${product.original_price && product.original_price > product.price ? 
                                            `<span class="original-price">${formatPrice(product.original_price)}</span>` : ''}
                                        <span class="current-price">${formatPrice(product.price)}</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                `;
            });
            html += '</div>';
            
            container.innerHTML = html;
        } else {
            container.innerHTML = '<p class="text-center">Không có sản phẩm nào.</p>';
        }
    } catch (error) {
        console.error('Error loading products:', error);
        document.getElementById('productsContainer').innerHTML = 
            '<p class="text-center text-danger">Có lỗi xảy ra khi tải sản phẩm.</p>';
    }
}

function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(price);
}
</script>
@endpush

@push('styles')
<style>
.product-card {
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s, box-shadow 0.3s;
    height: 100%;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}

.product-card a {
    text-decoration: none;
    color: inherit;
    display: block;
}

.product-image {
    width: 100%;
    height: 250px;
    overflow: hidden;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-info {
    padding: 1.5rem;
}

.product-name {
    font-size: 1.25rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 0.5rem;
    min-height: 60px;
}

.product-category {
    color: #324F29;
    font-size: 0.9rem;
    margin-bottom: 1rem;
}

.product-price {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.original-price {
    text-decoration: line-through;
    color: #999;
    font-size: 1rem;
}

.current-price {
    font-size: 1.5rem;
    font-weight: bold;
    color: #324F29;
}
</style>
@endpush
