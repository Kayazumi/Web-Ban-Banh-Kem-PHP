@extends('layouts.app')

@section('title', 'Chi tiết sản phẩm - La Cuisine Ngọt')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="mb-4">
                <a href="{{ route('products.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại danh sách sản phẩm
                </a>
            </div>

            <div id="productDetailContent">
                <!-- Product detail will be loaded via AJAX -->
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Đang tải...</span>
                    </div>
                    <p>Đang tải chi tiết sản phẩm...</p>
                </div>
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

.product-detail {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem;
    margin-bottom: 3rem;
}

.product-gallery {
    position: relative;
}

.main-image {
    width: 100%;
    height: 400px;
    object-fit: cover;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.product-badges {
    position: absolute;
    top: 1rem;
    left: 1rem;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.product-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: bold;
    color: white;
    display: inline-block;
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

.product-info h1 {
    font-size: 2rem;
    color: #333;
    margin-bottom: 1rem;
}

.product-price {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.original-price {
    text-decoration: line-through;
    color: #999;
    font-size: 1.2rem;
}

.current-price {
    font-size: 2rem;
    font-weight: bold;
    color: #8B4513;
}

.discount-badge {
    background: #dc3545;
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 3px;
    font-size: 0.875rem;
}

.product-rating {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.stars {
    color: #ffc107;
}

.rating-count {
    color: #666;
    font-size: 0.9rem;
}

.product-description {
    margin-bottom: 2rem;
    line-height: 1.6;
}

.product-meta {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 8px;
}

.meta-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.meta-item strong {
    color: #333;
}

.quantity-selector {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 2rem;
}

.quantity-controls {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.quantity-btn {
    width: 40px;
    height: 40px;
    border: 1px solid #ddd;
    background: white;
    border-radius: 4px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.quantity-input {
    width: 80px;
    text-align: center;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 0.5rem;
    font-size: 1rem;
}

.action-buttons {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
}

.btn {
    padding: 1rem 2rem;
    border: none;
    border-radius: 5px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s;
    font-size: 1rem;
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

.btn-outline {
    background: transparent;
    border: 2px solid #8B4513;
    color: #8B4513;
}

.btn-outline:hover {
    background: #8B4513;
    color: white;
}

.product-tabs {
    margin-top: 3rem;
}

.tab-buttons {
    display: flex;
    border-bottom: 1px solid #ddd;
    margin-bottom: 2rem;
}

.tab-btn {
    padding: 1rem 2rem;
    background: transparent;
    border: none;
    border-bottom: 3px solid transparent;
    cursor: pointer;
    font-size: 1rem;
    font-weight: 500;
    color: #666;
    transition: all 0.3s;
}

.tab-btn.active {
    color: #8B4513;
    border-bottom-color: #8B4513;
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

.tab-pane {
    line-height: 1.6;
}

.tab-pane h3 {
    color: #333;
    margin-bottom: 1rem;
}

.tab-pane ul {
    padding-left: 2rem;
}

.tab-pane li {
    margin-bottom: 0.5rem;
}

.reviews-section {
    margin-top: 3rem;
}

.review-item {
    border: 1px solid #eee;
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    background: white;
}

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.review-author {
    font-weight: bold;
    color: #333;
}

.review-date {
    color: #666;
    font-size: 0.9rem;
}

.review-rating {
    color: #ffc107;
    margin-bottom: 0.5rem;
}

.review-content {
    color: #555;
    line-height: 1.5;
}

.related-products {
    margin-top: 4rem;
}

.related-products h2 {
    text-align: center;
    margin-bottom: 2rem;
    color: #333;
}

.related-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
}

.related-card {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    text-decoration: none;
    color: inherit;
    transition: transform 0.3s;
}

.related-card:hover {
    transform: translateY(-5px);
    text-decoration: none;
    color: inherit;
}

.related-image {
    height: 150px;
    overflow: hidden;
}

.related-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.related-info {
    padding: 1rem;
}

.related-name {
    font-weight: bold;
    margin-bottom: 0.5rem;
    color: #333;
}

.related-price {
    color: #8B4513;
    font-weight: bold;
}

@media (max-width: 768px) {
    .product-detail {
        grid-template-columns: 1fr;
        gap: 2rem;
    }

    .product-gallery {
        order: 1;
    }

    .product-info {
        order: 2;
    }

    .action-buttons {
        flex-direction: column;
    }

    .btn {
        width: 100%;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const productId =  <?php echo $productId; ?>;
    loadProductDetail(productId);

    // Tab functionality
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const tabId = this.getAttribute('data-tab');

            // Remove active class from all tabs
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));

            // Add active class to clicked tab
            this.classList.add('active');
            document.getElementById(tabId).classList.add('active');
        });
    });

    // Activate first tab by default
    if (tabButtons.length > 0) {
        tabButtons[0].click();
    }
});

async function loadProductDetail(productId) {
    try {
        const response = await fetch(`/api/products/${productId}`);
        const data = await response.json();

        const content = document.getElementById('productDetailContent');

        if (data.success && data.data.product) {
            const product = data.data.product;

            // Calculate discount percentage
            let discountPercent = 0;
            if (product.original_price && product.original_price > product.price) {
                discountPercent = Math.round(((product.original_price - product.price) / product.original_price) * 100);
            }

            let html = `
                <div class="product-detail">
                    <div class="product-gallery">
                        <img src="${product.image_url || '/images/placeholder.jpg'}"
                             alt="${product.product_name}"
                             class="main-image">
                        <div class="product-badges">
                            ${product.is_featured ? '<span class="product-badge badge-featured">Nổi bật</span>' : ''}
                            ${product.is_new ? '<span class="product-badge badge-new">Mới</span>' : ''}
                            ${discountPercent > 0 ? `<span class="product-badge badge-sale">-${discountPercent}%</span>` : ''}
                        </div>
                    </div>

                    <div class="product-info">
                        <h1>${product.product_name}</h1>

                        <div class="product-rating">
                            <div class="stars">
                                ${'★'.repeat(Math.floor(product.average_rating || 0))}
                                ${'☆'.repeat(5 - Math.floor(product.average_rating || 0))}
                            </div>
                            <span class="rating-count">(${product.reviews_count || 0} đánh giá)</span>
                        </div>

                        <div class="product-price">
                            ${product.original_price && product.original_price > product.price ?
                                `<span class="original-price">${formatPrice(product.original_price)}</span>` :
                                ''}
                            <span class="current-price">${formatPrice(product.price)}</span>
                            ${discountPercent > 0 ?
                                `<span class="discount-badge">Giảm ${discountPercent}%</span>` :
                                ''}
                        </div>

                        <div class="product-description">
                            ${product.description || 'Chưa có mô tả cho sản phẩm này.'}
                        </div>

                        <div class="product-meta">
                            <div class="meta-item">
                                <strong>Danh mục:</strong>
                                <span>${product.category_name || 'Chưa phân loại'}</span>
                            </div>
                            <div class="meta-item">
                                <strong>Tồn kho:</strong>
                                <span>${product.quantity} ${product.unit}</span>
                            </div>
                            <div class="meta-item">
                                <strong>Trạng thái:</strong>
                                <span>${product.status === 'available' ? 'Còn hàng' : 'Hết hàng'}</span>
                            </div>
                            ${product.shelf_life ?
                                `<div class="meta-item">
                                    <strong>Hạn sử dụng:</strong>
                                    <span>${product.shelf_life} ngày</span>
                                </div>` :
                                ''}
                        </div>

                        ${product.status === 'available' && product.quantity > 0 ? `
                        <div class="quantity-selector">
                            <strong>Số lượng:</strong>
                            <div class="quantity-controls">
                                <button class="quantity-btn" onclick="changeQuantity(-1)">-</button>
                                <input type="number" class="quantity-input" id="quantityInput"
                                       value="1" min="1" max="${product.quantity}">
                                <button class="quantity-btn" onclick="changeQuantity(1)">+</button>
                            </div>
                        </div>

                        <div class="action-buttons">
                            <button onclick="addToCart(${product.ProductID})" class="btn btn-primary">
                                <i class="fas fa-cart-plus"></i> Thêm vào giỏ hàng
                            </button>
                            <button onclick="addToWishlist(${product.ProductID})" class="btn btn-outline">
                                <i class="fas fa-heart"></i> Yêu thích
                            </button>
                        </div>
                        ` : `
                        <div class="alert alert-warning">
                            Sản phẩm này hiện không khả dụng hoặc đã hết hàng.
                        </div>
                        `}
                    </div>
                </div>

                <div class="product-tabs">
                    <div class="tab-buttons">
                        <button class="tab-btn active" data-tab="description">Mô tả</button>
                        <button class="tab-btn" data-tab="details">Chi tiết</button>
                        <button class="tab-btn" data-tab="reviews">Đánh giá (${product.reviews_count || 0})</button>
                    </div>

                    <div id="description" class="tab-content active">
                        <div class="tab-pane">
                            ${product.description || 'Chưa có mô tả chi tiết cho sản phẩm này.'}
                        </div>
                    </div>

                    <div id="details" class="tab-content">
                        <div class="tab-pane">
                            ${product.short_intro ? `<h3>Giới thiệu</h3><p>${product.short_intro}</p>` : ''}
                            ${product.short_paragraph ? `<h3>Mô tả ngắn</h3><p>${product.short_paragraph}</p>` : ''}
                            ${product.structure ? `<h3>Cấu tạo</h3><div>${product.structure}</div>` : ''}
                            ${product.usage ? `<h3>Cách sử dụng</h3><div>${product.usage}</div>` : ''}
                            ${product.bonus ? `<h3>Quà tặng</h3><div>${product.bonus}</div>` : ''}
                            ${product.ingredients ? `<h3>Thành phần</h3><p>${product.ingredients}</p>` : ''}
                            ${product.allergens ? `<h3>Chất gây dị ứng</h3><p>${product.allergens}</p>` : ''}
                        </div>
                    </div>

                    <div id="reviews" class="tab-content">
                        <div class="tab-pane">
                            ${renderReviews(product.reviews || [])}
                        </div>
                    </div>
                </div>
            `;

            content.innerHTML = html;
        } else {
            content.innerHTML = `
                <div class="text-center">
                    <h3>Không tìm thấy sản phẩm</h3>
                    <p>Sản phẩm không tồn tại hoặc đã bị xóa.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary">Quay lại</a>
                </div>
            `;
        }
    } catch (error) {
        console.error('Error loading product detail:', error);
        document.getElementById('productDetailContent').innerHTML = `
            <div class="text-center">
                <p>Có lỗi xảy ra khi tải chi tiết sản phẩm. Vui lòng thử lại.</p>
                <button onclick="loadProductDetail({{ $productId }})" class="btn btn-primary">Thử lại</button>
            </div>
        `;
    }
}

function renderReviews(reviews) {
    if (!reviews || reviews.length === 0) {
        return '<p>Chưa có đánh giá nào cho sản phẩm này.</p>';
    }

    return reviews.map(review => `
        <div class="review-item">
            <div class="review-header">
                <div class="review-author">${review.user?.full_name || 'Người dùng ẩn danh'}</div>
                <div class="review-date">${formatDate(review.created_at)}</div>
            </div>
            <div class="review-rating">
                ${'★'.repeat(review.rating)}${'☆'.repeat(5 - review.rating)}
            </div>
            ${review.title ? `<h4>${review.title}</h4>` : ''}
            <div class="review-content">${review.content || ''}</div>
        </div>
    `).join('');
}

function changeQuantity(delta) {
    const input = document.getElementById('quantityInput');
    const currentValue = parseInt(input.value);
    const newValue = currentValue + delta;

    if (newValue >= 1 && newValue <= parseInt(input.max)) {
        input.value = newValue;
    }
}

async function addToCart(productId) {
    if (!window.Laravel.user) {
        alert('Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng');
        window.location.href = '/login';
        return;
    }

    const quantity = parseInt(document.getElementById('quantityInput').value);

    try {
        const response = await fetch('/api/cart', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.Laravel.csrfToken
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: quantity
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

async function addToWishlist(productId) {
    if (!window.Laravel.user) {
        alert('Vui lòng đăng nhập để thêm vào danh sách yêu thích');
        return;
    }

    try {
        const response = await fetch('/api/wishlist', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.Laravel.csrfToken
            },
            body: JSON.stringify({
                product_id: productId
            })
        });

        const data = await response.json();

        if (data.success) {
            alert('Đã thêm vào danh sách yêu thích!');
        } else {
            alert(data.message || 'Có lỗi xảy ra');
        }
    } catch (error) {
        console.error('Error adding to wishlist:', error);
        alert('Có lỗi xảy ra');
    }
}

function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(price);
}

function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString('vi-VN');
}
</script>
@endpush
