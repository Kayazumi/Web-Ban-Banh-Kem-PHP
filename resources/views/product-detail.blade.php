@extends('layouts.app')

@section('title', 'Chi tiết sản phẩm - La Cuisine Ngọt')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
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
    background: #324F29;
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
    color: #324F29;
}

.discount-badge {
    background: #dc3545;
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 3px;
    font-size: 0.875rem;
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
    background: #324F29;
    color: white;
}

.btn-primary:hover {
    background: #1e3015;
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
    border: 2px solid #324F29;
    color: #324F29;
}

.btn-outline:hover {
    background: #324F29;
    color: white;
}

/* --- BẮT ĐẦU PHẦN PRODUCT ACTIONS MỚI --- */

.product-actions {
    display: flex;
    flex-direction: column; /* Xếp theo chiều dọc */
    gap: 12px; /* KHOẢNG CÁCH DỌC: Cách đều nút Mua ngay 12px */
    margin-top: 2rem;
}

.action-row-1 {
    display: flex;
    gap: 12px; /* KHOẢNG CÁCH NGANG: Cách đều ô +- và Giỏ hàng 12px */
    height: 50px; /* CHIỀU CAO CHUẨN: Quy định chiều cao cho cả hàng này */
}

/* 1. Ô chọn số lượng */
.quantity-selector {
    display: flex;
    align-items: center;
    background: white;
    border: 2px solid #324F29;
    border-radius: 8px;
    overflow: hidden;
    width: 130px; /* Độ rộng cố định */
    height: 100%; /* Cao 100% theo cha (50px) -> Không thể lệch */
    box-sizing: border-box; /* Tính cả viền vào chiều cao */
}

.quantity-selector .qty-btn {
    width: 40px;
    height: 100%; /* Full chiều cao ô */
    background: #324F29;
    color: white;
    border: none;
    font-size: 1.2rem;
    font-weight: bold;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0;
    margin: 0;
    transition: background 0.3s;
}

.quantity-selector .qty-btn:hover {
    background: #1e3015;
}

.quantity-selector input {
    flex: 1;
    width: 100%;
    height: 100%; /* Full chiều cao ô */
    border: none;
    text-align: center;
    font-size: 1.1rem;
    font-weight: 600;
    outline: none;
    padding: 0;
    margin: 0;
    -webkit-appearance: textfield;
    -moz-appearance: textfield;
}

.quantity-selector input::-webkit-outer-spin-button,
.quantity-selector input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

/* 2. Nút Thêm vào giỏ hàng */
.btn-add-cart {
    flex: 1; /* Tự động giãn hết phần còn lại */
    height: 100%; /* Cao 100% theo cha (50px) -> Bằng tuyệt đối với ô bên cạnh */
    background: #324F29;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0; /* Reset padding để flex lo căn giữa */
    transition: all 0.3s;
}

.btn-add-cart:hover {
    background: #1e3015;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(50, 79, 41, 0.2);
}

/* 3. Nút Mua ngay */
.action-row-2 {
    width: 100%;
    height: 50px; /* Chiều cao bằng hàng trên */
}

.btn-buy-now {
    width: 100%;
    height: 100%; /* Full chiều cao */
    background: #324F29;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 1.1rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s;
}

.btn-buy-now:hover {
    background: #1e3015;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(50, 79, 41, 0.2);
}

.btn-add-cart:active,
.btn-buy-now:active {
    transform: translateY(0);
}

.product-info-sections {
    margin-top: 3rem;
}

.info-sections-title {
    font-size: 1.5rem;
    font-weight: bold;
    color: #324F29;
    margin-bottom: 2rem;
    text-align: center;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.info-section {
    margin-bottom: 2.5rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid #eee;
}

.info-section:last-child {
    border-bottom: none;
}

.section-heading {
    font-size: 1.1rem;
    font-weight: bold;
    color: #324F29;
    margin-bottom: 1rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.section-content {
    line-height: 1.8;
    color: #333;
    text-align: justify;
}

.section-content p {
    margin-bottom: 1rem;
    line-height: 1.8;
    text-align: justify;
}

.section-content .short-intro {
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.section-content ul {
    list-style: none;
    padding-left: 0;
    margin: 0;
}

.section-content ul.no-dot {
    list-style: none;
}

.section-content ul:not(.no-dot) {
    list-style: disc;
    padding-left: 1.5rem;
}

.section-content li {
    margin-bottom: 0.75rem;
    line-height: 1.6;
    text-align: justify;
}

.section-content li b,
.section-content li strong {
    color: #324F29;
    font-weight: 600;
}

.section-content h3 {
    font-size: 1rem;
    font-weight: 600;
    color: #324F29;
    margin: 1.5rem 0 0.75rem 0;
}

.section-content h4 {
    font-size: 0.95rem;
    font-weight: 600;
    color: #324F29;
    margin: 1rem 0 0.5rem 0;
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
    color: #324F29;
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
                        <div class="product-actions">
                            <div class="action-row-1">
                                <div class="quantity-selector">
                                    <button class="qty-btn" onclick="decreaseQuantity()">-</button>
                                    <input type="number" id="quantity" value="1" min="1" max="99">
                                    <button class="qty-btn" onclick="increaseQuantity()">+</button>
                                </div>
                                <button onclick="addToCart(${product.ProductID})" class="btn btn-add-cart">
                                    <i class="fas fa-cart-plus"></i> Thêm vào giỏ hàng
                                </button>
                            </div>
                            <div class="action-row-2">
                                <button onclick="buyNow(${product.ProductID})" class="btn btn-buy-now">
                                    Mua ngay
                                </button>
                            </div>
                        </div>
                        ` : `
                        <div class="alert alert-warning">
                            Sản phẩm này hiện không khả dụng hoặc đã hết hàng.
                        </div>
                        `}
                    </div>
                </div>

                <!-- Product Information Sections - Full Width -->
                <div class="container">
                    <div class="product-info-sections">
                        <h2 class="info-sections-title">THÔNG TIN SẢN PHẨM</h2>
                    
                    <!-- MÔ TẢ Section -->
                    ${(product.short_intro || product.short_paragraph) ? `
                    <div class="info-section">
                        <h3 class="section-heading">MÔ TẢ:</h3>
                        <div class="section-content">
                            ${product.short_intro ? `<div class="short-intro">${product.short_intro}</div>` : ''}
                            ${product.short_paragraph ? `<p>${product.short_paragraph}</p>` : ''}
                        </div>
                    </div>
                    ` : ''}

                    <!-- CẤU TRÚC BÁNH Section -->
                    ${product.structure ? `
                    <div class="info-section">
                        <h3 class="section-heading">CẤU TRÚC BÁNH:</h3>
                        <div class="section-content">
                            ${product.structure}
                        </div>
                    </div>
                    ` : ''}

                    <!-- HƯỚNG DẪN SỬ DỤNG Section -->
                    ${product.usage ? `
                    <div class="info-section">
                        <h3 class="section-heading">HƯỚNG DẪN SỬ DỤNG:</h3>
                        <div class="section-content">
                            ${product.usage}
                        </div>
                    </div>
                    ` : ''}

                    <!-- PHỤ KIỆN TẶNG KÈM Section -->
                    ${product.bonus ? `
                    <div class="info-section">
                        <h3 class="section-heading">PHỤ KIỆN TẶNG KÈM:</h3>
                        <div class="section-content">
                            ${product.bonus}
                        </div>
                    </div>
                    ` : ''}


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

async function addToCart(productId, qty = null, isBuyNow = false) {
    if (!window.Laravel.user) {
        showAlert('Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng');
        window.location.href = '/login';
        return;
    }

    const quantityInput = document.getElementById('quantity');
    const quantity = qty || (quantityInput ? parseInt(quantityInput.value) : 1);

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
            if (!isBuyNow) {
                showAlert('Đã thêm sản phẩm vào giỏ hàng!');
            }
            // Update cart count if available
            if (window.updateCartCount) {
                window.updateCartCount();
            }
            return true;
        } else {
            showAlert(data.message || 'Có lỗi xảy ra');
            return false;
        }
    } catch (error) {
        console.error('Error adding to cart:', error);
        showAlert('Có lỗi xảy ra');
        return false;
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

function increaseQuantity() {
    const qtyInput = document.getElementById('quantity');
    if (qtyInput) {
        const currentValue = parseInt(qtyInput.value) || 1;
        if (currentValue < 99) {
            qtyInput.value = currentValue + 1;
        }
    }
}

function decreaseQuantity() {
    const qtyInput = document.getElementById('quantity');
    if (qtyInput) {
        const currentValue = parseInt(qtyInput.value) || 1;
        if (currentValue > 1) {
            qtyInput.value = currentValue - 1;
        }
    }
}

async function buyNow(productId) {
    if (!window.Laravel.user) {
        showAlert('Vui lòng đăng nhập để tiếp tục thanh toán');
        // Store current page URL as redirect parameter
        const currentUrl = window.location.pathname;
        window.location.href = `/login?redirect=${encodeURIComponent(currentUrl)}`;
        return;
    }

    const qtyInput = document.getElementById('quantity');
    const quantity = qtyInput ? parseInt(qtyInput.value) || 1 : 1;
    
    // Redirect directly to checkout ("orders" page)
    window.location.href = `/orders?product_id=${productId}&quantity=${quantity}`;
}
</script>
@endpush
