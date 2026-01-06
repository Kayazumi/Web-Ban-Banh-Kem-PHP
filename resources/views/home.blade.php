@extends('layouts.app')

@section('title', 'La Cuisine Ngọt - Bánh Kem Cao Cấp')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/customer.css') }}">
@endpush

@section('content')
<!-- Hero Section -->
<section id="home" class="hero">
    <div class="container">
        <div class="hero-content">
            <h1>LA CUISINE NGỌT</h1>
            <p class="hero-subtitle">Thương hiệu bánh kem cao cấp hàng đầu Việt Nam</p>
            <p class="hero-description">
                Mỗi chiếc bánh là một tác phẩm nghệ thuật, mang đến trải nghiệm vị giác tinh tế và cảm xúc ấm áp.
                Chúng tôi cam kết sử dụng nguyên liệu cao cấp, quy trình sản xuất nghiêm ngặt để mang đến cho bạn
                những sản phẩm tốt nhất.
            </p>
            <div class="hero-buttons">
                <a href="#products" class="btn btn-primary">Xem sản phẩm</a>
                <a href="#contact" class="btn btn-secondary">Liên hệ ngay</a>
            </div>
        </div>
        <div class="hero-image">
            <img src="{{ asset('images/chaomung1.jpg') }}" alt="Bánh kem cao cấp">
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<section id="products" class="products-section">
    <div class="container">
        <h2 class="section-title">Sản phẩm nổi bật</h2>
        <div class="products-grid" id="featuredProducts">
            <!-- Products will be loaded via AJAX -->
            <div class="text-center" style="grid-column: 1/-1; padding: 3rem 0;">
                <p style="color: #666;">Đang tải sản phẩm...</p>
            </div>
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('products.index') }}" class="btn btn-outline">Xem tất cả sản phẩm</a>
        </div>
    </div>
</section>

<!-- Promotions Section -->
<section id="khuyenmai" class="promotions-section">
    <div class="container">
        <h2 class="section-title">Khuyến mãi hấp dẫn</h2>
        <div class="promotions-grid">
            <div class="promotion-card">
                <img src="{{ asset('images/buy-1-get-1.jpg') }}" alt="Mua 1 tặng 1">
                <div class="promotion-content">
                    <h3>Mua 1 tặng 1</h3>
                    <p>Áp dụng cho các loại bánh entremet</p>
                </div>
            </div>
            <div class="promotion-card">
                <img src="{{ asset('images/free-ship.jpg') }}" alt="Miễn phí giao hàng">
                <div class="promotion-content">
                    <h3>Miễn phí giao hàng</h3>
                    <p>Đơn hàng từ 500.000đ</p>
                </div>
            </div>
            <div class="promotion-card">
                <img src="{{ asset('images/gg.jpg') }}" alt="Giảm giá">
                <div class="promotion-content">
                    <h3>Giảm 10%</h3>
                    <p>Cho khách hàng thân thiết</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="about-section">
    <div class="container">
        <div class="about-content">
            <div class="about-text">
                <h2>Về La Cuisine Ngọt</h2>
                <p>
                    La Cuisine Ngọt được thành lập với sứ mệnh mang đến những trải nghiệm vị giác tinh tế
                    và cảm xúc ấm áp thông qua những chiếc bánh kem cao cấp. Chúng tôi tin rằng mỗi chiếc bánh
                    không chỉ là một món tráng miệng mà còn là một tác phẩm nghệ thuật.
                </p>
                <p>
                    Với đội ngũ đầu bếp chuyên nghiệp, nguyên liệu cao cấp nhập khẩu và quy trình sản xuất
                    nghiêm ngặt, chúng tôi cam kết mang đến cho khách hàng những sản phẩm tốt nhất.
                </p>
            </div>
            <div class="about-image">
                <img src="{{ asset('images/tamnhin.jpg') }}" alt="Tầm nhìn La Cuisine Ngọt">
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section id="contact" class="contact-section">
    <div class="container">
        <h2 class="section-title">Liên hệ với chúng tôi</h2>
        <div class="contact-content">
            <div class="contact-info">
                <div class="contact-item">
                    <i class="fas fa-phone"></i>
                    <div>
                        <h4>Hotline</h4>
                        <p>0901 234 567</p>
                    </div>
                </div>
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <h4>Email</h4>
                        <p>info@lacuisine.vn</p>
                    </div>
                </div>
                <div class="contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <h4>Địa chỉ</h4>
                        <p>TP. Hồ Chí Minh</p>
                    </div>
                </div>
            </div>

            <div class="contact-form">
                <h3>Gửi tin nhắn</h3>
                <form id="contactForm">
                    @csrf
                    <div class="form-group">
                        <input type="text" name="name" placeholder="Họ tên" required>
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" placeholder="Email" required>
                    </div>
                    <div class="form-group">
                        <input type="text" name="subject" placeholder="Tiêu đề" required>
                    </div>
                    <div class="form-group">
                        <textarea name="message" placeholder="Nội dung" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Gửi tin nhắn</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
// Load featured products on page load
document.addEventListener('DOMContentLoaded', function() {
    loadFeaturedProducts();

    // Handle contact form
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', handleContactSubmit);
    }

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});

async function loadFeaturedProducts() {
    try {
        const response = await fetch('/api/products/featured');
        const data = await response.json();

        if (data.success && data.data.products.length > 0) {
            displayProducts(data.data.products, 'featuredProducts');
        } else {
            document.getElementById('featuredProducts').innerHTML = `
                <div class="text-center" style="grid-column: 1/-1; padding: 3rem 0;">
                    <p style="color: #666;">Chưa có sản phẩm nào.</p>
                </div>
            `;
        }
    } catch (error) {
        console.error('Error loading featured products:', error);
        document.getElementById('featuredProducts').innerHTML = `
            <div class="text-center" style="grid-column: 1/-1; padding: 3rem 0;">
                <p style="color: #dc3545;">Không thể tải sản phẩm. Vui lòng thử lại sau.</p>
            </div>
        `;
    }
}

function displayProducts(products, containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;

    container.innerHTML = products.map(product => `
        <div class="product-card">
            <div class="product-image">
                <img src="${product.image_url || '/images/placeholder.jpg'}" 
                     alt="${product.product_name}"
                     onerror="this.src='/images/placeholder.jpg'">
                ${product.is_featured ? '<span class="badge featured">Nổi bật</span>' : ''}
                ${product.is_new ? '<span class="badge new">Mới</span>' : ''}
            </div>
            <div class="product-info">
                <h3 class="product-name">${product.product_name}</h3>
                <p class="product-price">
                    ${product.original_price && product.original_price > product.price
                        ? `<span class="original-price">${formatPrice(product.original_price)}</span>`
                        : ''}
                    <span class="current-price">${formatPrice(product.price)}</span>
                </p>
                <a href="/products/${product.product_id}" class="btn btn-sm btn-primary">Xem chi tiết</a>
            </div>
        </div>
    `).join('');
}

function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(price);
}

async function handleContactSubmit(e) {
    e.preventDefault();

    const formData = new FormData(e.target);
    const submitBtn = e.target.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;

    // Disable button and show loading state
    submitBtn.disabled = true;
    submitBtn.textContent = 'Đang gửi...';

    try {
        const response = await fetch('/api/contacts', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': window.Laravel.csrfToken,
                'Accept': 'application/json'
            },
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            alert('Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi sớm nhất có thể.');
            e.target.reset();
        } else {
            alert(data.message || 'Có lỗi xảy ra. Vui lòng thử lại.');
        }
    } catch (error) {
        console.error('Error submitting contact form:', error);
        alert('Có lỗi xảy ra. Vui lòng thử lại sau.');
    } finally {
        // Re-enable button
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
    }
}
</script>
@endpush