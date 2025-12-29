@extends('layouts.app')

@section('title', 'Sản phẩm - La Cuisine Ngọt')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">Sản phẩm của chúng tôi</h1>

            <!-- Filters -->
            <div class="filters mb-4">
                <div class="filter-group">
                    <label for="categoryFilter">Danh mục:</label>
                    <select id="categoryFilter" class="form-control">
                        <option value="">Tất cả danh mục</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="priceFilter">Giá:</label>
                    <select id="priceFilter" class="form-control">
                        <option value="">Tất cả giá</option>
                        <option value="duoi500">Dưới 500.000đ</option>
                        <option value="500-700">500.000đ - 700.000đ</option>
                        <option value="tren700">Trên 700.000đ</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="searchInput">Tìm kiếm:</label>
                    <input type="text" id="searchInput" class="form-control" placeholder="Nhập tên sản phẩm...">
                </div>

                <button id="applyFilters" class="btn btn-primary">Lọc</button>
                <button id="resetFilters" class="btn btn-secondary">Đặt lại</button>
            </div>

            <!-- Products Grid -->
            <div id="productsGrid" class="products-grid">
                <!-- Products will be loaded via AJAX -->
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Đang tải...</span>
                    </div>
                    <p>Đang tải sản phẩm...</p>
                </div>
            </div>

            <!-- Pagination -->
            <div id="pagination" class="pagination-container">
                <!-- Pagination will be loaded via AJAX -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem 20px;
}

.filters {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    align-items: end;
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 10px;
    margin-bottom: 2rem;
}

.filter-group {
    display: flex;
    flex-direction: column;
    min-width: 200px;
}

.filter-group label {
    font-weight: bold;
    margin-bottom: 0.5rem;
    color: #333;
}

.form-control {
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 1rem;
}

.form-control:focus {
    outline: none;
    border-color: #8B4513;
}

.btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 5px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-primary {
    background: #8B4513;
    color: white;
}

.btn-primary:hover {
    background: #654321;
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #545b62;
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 2rem;
    margin-bottom: 2rem;
}

.product-card {
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s;
    text-decoration: none;
    color: inherit;
    display: block;
}

.product-card:hover {
    transform: translateY(-5px);
    text-decoration: none;
    color: inherit;
}

.product-image {
    height: 200px;
    overflow: hidden;
    position: relative;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    padding: 0.25rem 0.5rem;
    border-radius: 3px;
    font-size: 0.75rem;
    font-weight: bold;
    color: white;
}

.badge-featured {
    background: #28a745;
}

.badge-new {
    background: #007bff;
}

.badge-sale {
    background: #dc3545;
}

.product-info {
    padding: 1rem;
}

.product-name {
    font-size: 1.1rem;
    font-weight: bold;
    margin-bottom: 0.5rem;
    color: #333;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.product-description {
    color: #666;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.product-price {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.original-price {
    text-decoration: line-through;
    color: #999;
    font-size: 0.9rem;
}

.current-price {
    font-size: 1.2rem;
    font-weight: bold;
    color: #8B4513;
}

.product-actions {
    display: flex;
    gap: 0.5rem;
}

.btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.9rem;
    border-radius: 3px;
}

.btn-outline {
    background: transparent;
    border: 1px solid #8B4513;
    color: #8B4513;
}

.btn-outline:hover {
    background: #8B4513;
    color: white;
}

.pagination-container {
    display: flex;
    justify-content: center;
    margin-top: 2rem;
}

.pagination {
    display: flex;
    list-style: none;
    gap: 0.5rem;
}

.pagination li {
    margin: 0;
}

.pagination a,
.pagination span {
    padding: 0.5rem 0.75rem;
    border: 1px solid #ddd;
    border-radius: 3px;
    text-decoration: none;
    color: #333;
    display: block;
}

.pagination .active span {
    background: #8B4513;
    color: white;
    border-color: #8B4513;
}

.pagination a:hover {
    background: #f8f9fa;
}

.text-center {
    text-align: center;
    padding: 2rem;
}

.spinner-border {
    width: 3rem;
    height: 3rem;
    border: 0.25em solid #f3f3f3;
    border-top: 0.25em solid #8B4513;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto 1rem;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

@media (max-width: 768px) {
    .filters {
        flex-direction: column;
    }

    .filter-group {
        min-width: auto;
        width: 100%;
    }

    .products-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
let currentPage = 1;
let currentFilters = {};

document.addEventListener('DOMContentLoaded', function() {
    loadCategories();
    loadProducts();

    // Bind filter events
    document.getElementById('applyFilters').addEventListener('click', applyFilters);
    document.getElementById('resetFilters').addEventListener('click', resetFilters);
    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            applyFilters();
        }
    });
});

async function loadCategories() {
    try {
        const response = await fetch('/api/categories');
        const data = await response.json();

        if (data.success) {
            const categorySelect = document.getElementById('categoryFilter');
            data.data.categories.forEach(category => {
                const option = document.createElement('option');
                option.value = category.category_name;
                option.textContent = category.category_name;
                categorySelect.appendChild(option);
            });
        }
    } catch (error) {
        console.error('Error loading categories:', error);
    }
}

async function loadProducts(page = 1) {
    try {
        const params = new URLSearchParams({
            page: page,
            ...currentFilters
        });

        const response = await fetch(`/api/products?${params}`);
        const data = await response.json();

        const productsGrid = document.getElementById('productsGrid');
        const pagination = document.getElementById('pagination');

        if (data.success && data.data.products.length > 0) {
            // Render products
            productsGrid.innerHTML = data.data.products.map(product => `
                <a href="/products/${product.product_id}" class="product-card">
                    <div class="product-image">
                        <img src="${product.image_url || '/images/placeholder.jpg'}"
                             alt="${product.product_name}">
                        ${product.is_featured ? '<span class="product-badge badge-featured">Nổi bật</span>' : ''}
                        ${product.is_new ? '<span class="product-badge badge-new">Mới</span>' : ''}
                        ${product.original_price && product.original_price > product.price ?
                            '<span class="product-badge badge-sale">Giảm giá</span>' : ''}
                    </div>
                    <div class="product-info">
                        <div class="product-name">${product.product_name}</div>
                        <div class="product-description">${product.description || ''}</div>
                        <div class="product-price">
                            ${product.original_price && product.original_price > product.price ?
                                `<span class="original-price">${formatPrice(product.original_price)}</span>` :
                                ''}
                            <span class="current-price">${formatPrice(product.price)}</span>
                        </div>
                        <div class="product-actions">
                            <button onclick="event.preventDefault(); addToCart(${product.product_id})"
                                    class="btn btn-primary btn-sm">
                                Thêm vào giỏ
                            </button>
                            <a href="/products/${product.product_id}" class="btn btn-outline btn-sm">
                                Xem chi tiết
                            </a>
                        </div>
                    </div>
                </a>
            `).join('');

            // Render pagination
            pagination.innerHTML = renderPagination(data.data.pagination);
        } else {
            productsGrid.innerHTML = `
                <div class="text-center">
                    <h3>Không tìm thấy sản phẩm nào</h3>
                    <p>Vui lòng thử lại với bộ lọc khác.</p>
                </div>
            `;
            pagination.innerHTML = '';
        }
    } catch (error) {
        console.error('Error loading products:', error);
        document.getElementById('productsGrid').innerHTML = `
            <div class="text-center">
                <p>Có lỗi xảy ra khi tải sản phẩm. Vui lòng thử lại.</p>
                <button onclick="loadProducts()" class="btn btn-primary">Thử lại</button>
            </div>
        `;
    }
}

function renderPagination(pagination) {
    if (!pagination || pagination.last_page <= 1) return '';

    let html = '<ul class="pagination">';

    // Previous button
    if (pagination.current_page > 1) {
        html += `<li><a href="#" onclick="changePage(${pagination.current_page - 1})">&laquo;</a></li>`;
    }

    // Page numbers
    for (let i = Math.max(1, pagination.current_page - 2);
         i <= Math.min(pagination.last_page, pagination.current_page + 2);
         i++) {
        if (i === pagination.current_page) {
            html += `<li class="active"><span>${i}</span></li>`;
        } else {
            html += `<li><a href="#" onclick="changePage(${i})">${i}</a></li>`;
        }
    }

    // Next button
    if (pagination.current_page < pagination.last_page) {
        html += `<li><a href="#" onclick="changePage(${pagination.current_page + 1})">&raquo;</a></li>`;
    }

    html += '</ul>';
    return html;
}

function changePage(page) {
    currentPage = page;
    loadProducts(page);
}

function applyFilters() {
    const category = document.getElementById('categoryFilter').value;
    const priceRange = document.getElementById('priceFilter').value;
    const search = document.getElementById('searchInput').value.trim();

    currentFilters = {};

    if (search) currentFilters.search = search;
    if (category) currentFilters.category = category;

    // Parse price range
    if (priceRange) {
        switch (priceRange) {
            case 'duoi500':
                currentFilters.price_max = 500000;
                break;
            case '500-700':
                currentFilters.price_min = 500000;
                currentFilters.price_max = 700000;
                break;
            case 'tren700':
                currentFilters.price_min = 700000;
                break;
        }
    }

    currentPage = 1;
    loadProducts(1);
}

function resetFilters() {
    document.getElementById('categoryFilter').value = '';
    document.getElementById('priceFilter').value = '';
    document.getElementById('searchInput').value = '';
    currentFilters = {};
    currentPage = 1;
    loadProducts(1);
}

async function addToCart(productId) {
    if (!window.Laravel.user) {
        alert('Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng');
        return;
    }

    try {
        const response = await fetch('/api/cart', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.Laravel.csrfToken
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: 1
            })
        });

        const data = await response.json();

        if (data.success) {
            alert('Đã thêm sản phẩm vào giỏ hàng!');
            // Update cart count if available
            if (window.updateCartCount) {
                window.updateCartCount();
            }
        } else {
            alert(data.message || 'Có lỗi xảy ra');
        }
    } catch (error) {
        console.error('Error adding to cart:', error);
        alert('Có lỗi xảy ra');
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
