<?php $__env->startSection('title', 'Sản phẩm - La Cuisine Ngọt'); ?>

<?php $__env->startSection('content'); ?>
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
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
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
    border-radius: 15px; /* Rounder corners */
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08); /* Softer shadow */
    transition: transform 0.3s;
    text-decoration: none;
    color: inherit;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.product-card:hover {
    transform: translateY(-5px);
}

.product-image {
    height: 250px; /* Taller image */
    overflow: hidden;
    position: relative;
    width: 100%;
    display: block;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.product-card:hover .product-image img {
    transform: scale(1.05); /* Slight zoom on hover */
}

.product-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    padding: 0.4rem 0.8rem;
    border-radius: 20px; /* Rounded badge */
    font-size: 0.75rem;
    font-weight: bold;
    color: white;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    z-index: 2;
}

.badge-featured {
    background: #b08d55; /* Gold/brown color from image */
}

.badge-new {
    background: #007bff;
}

.badge-sale {
    background: #dc3545;
}

.product-info {
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    align-items: center; /* Center horizontally */
    flex-grow: 1;
}

.product-name {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #333;
    text-align: center;
    text-decoration: none;
    line-height: 1.4;
    display: block;
}

.product-name:hover {
    color: #b08d55;
}

/* Removed description styles as we removed the element */

.product-price {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.8rem;
    margin-bottom: 1.2rem;
    margin-top: auto; /* Push to bottom if content varies */
    width: 100%;
}

.original-price {
    text-decoration: line-through;
    color: #999;
    font-size: 1rem;
}

.current-price {
    font-size: 1.4rem;
    font-weight: 700;
    color: #b08d55; /* Gold/brown color */
}

.product-actions {
    width: 100%;
    display: flex;
    justify-content: center;
}

.btn-detail {
    background: #c1a175; /* The beige/gold color */
    color: white;
    width: 100%;
    padding: 10px 0;
    font-weight: 600;
    border-radius: 5px;
    text-align: center;
    text-transform: none;
    font-size: 1rem;
    border: none;
    transition: background 0.3s;
    display: block;
    text-decoration: none;
}

.btn-detail:hover {
    background: #a88b5e;
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
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
let currentPage = 1;
let currentFilters = {};

document.addEventListener('DOMContentLoaded', function() {
    loadCategories();

    // Check for search param in URL
    const urlParams = new URLSearchParams(window.location.search);
    const searchParam = urlParams.get('search');
    
    if (searchParam) {
        document.getElementById('searchInput').value = searchParam;
        currentFilters.search = searchParam;
    }

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
                <div class="product-card">
                    <a href="/products/${product.product_id}" class="product-image">
                        <img src="${product.image_url || '/images/placeholder.jpg'}"
                             alt="${product.product_name}">
                        ${product.is_featured ? '<span class="product-badge badge-featured">NỔI BẬT</span>' : ''}
                        ${product.is_new ? '<span class="product-badge badge-new">MỚI</span>' : ''}
                        ${product.original_price && product.original_price > product.price ?
                            '<span class="product-badge badge-sale">GIẢM GIÁ</span>' : ''}
                    </a>
                    <div class="product-info text-center">
                        <a href="/products/${product.product_id}" class="product-name">${product.product_name}</a>
                        
                        <div class="product-price justify-content-center">
                            ${product.original_price && product.original_price > product.price ?
                                `<span class="original-price">${formatPrice(product.original_price)}</span>` :
                                ''}
                            <span class="current-price">${formatPrice(product.price)}</span>
                        </div>
                        
                        <div class="product-actions justify-content-center">
                            <a href="/products/${product.product_id}" class="btn btn-detail">
                                Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>
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
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp01\htdocs\Web-Ban-Banh-Kem-PHP\resources\views/products.blade.php ENDPATH**/ ?>