@extends('layouts.app')

@section('title', 'La Cuisine Ngọt - Bánh Kem Cao Cấp')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/customer.css') }}">
<style>
/* Hero: full-width background image using banh.jpg, overlay, and centered content */
.hero {
    position: relative;
    background-image: url("{{ asset('images/banh.jpg') }}");
    background-size: cover;
    background-position: center;
    color: #fff;
    padding: 120px 0 80px; /* leave space below nav */
}
.hero::before {
    content: "";
    position: absolute;
    inset: 0;
    background: linear-gradient(rgba(18,44,30,0.45), rgba(18,44,30,0.25));
    z-index: 0;
}
.hero .container { position: relative; z-index: 1; display:flex; align-items:center; gap:40px; }
.hero-content { max-width: 720px; }
.hero h1 { font-size: 48px; margin:0 0 12px; color: #fff; text-shadow: 0 6px 22px rgba(0,0,0,0.35); }
.hero .hero-subtitle { color: rgba(255,255,255,0.95); font-weight:600; margin-bottom:18px; }
.hero .hero-description { color: rgba(255,255,255,0.9); line-height:1.5; margin-bottom:20px; }
.hero .btn-hero { background: rgba(255,255,255,0.9); color: #23492f; padding: 10px 18px; border-radius: 8px; text-decoration: none; font-weight:600; }
@media (max-width: 768px) {
    .hero { padding: 90px 0 48px; }
    .hero h1 { font-size: 28px; }
    .hero .container { flex-direction: column; text-align: center; }
}

/* Out of stock badge & disabled button */
.badge.out-of-stock { background: #c0392b; color: white; padding: 6px 10px; border-radius: 12px; font-size: 12px; position: absolute; left: 12px; top: 12px; z-index: 5; }
.product-card .btn[disabled], .product-card .btn[aria-disabled="true"] { opacity: 0.6; cursor: not-allowed; pointer-events: none; background: #999; color: #fff; }
.swiper-wrapper.centered { justify-content: center; }
</style>
@endpush

@section('content')
<!-- Hero Section (background uses images/banh.jpg) -->
<section id="home" class="hero" role="banner" aria-label="Hero">
    <div class="container">
        <div class="hero-content">
            <h1>Bánh Kem Cao Cấp</h1>
            <p class="hero-subtitle">Thưởng thức hương vị tuyệt vời từ những chiếc bánh được làm thủ công với tinh yêu.</p>
            <p class="hero-description">Mỗi chiếc bánh là một tác phẩm nghệ thuật — nguyên liệu cao cấp, quy trình cẩn trọng và tình yêu trong từng khâu. Khám phá bộ sưu tập bánh của chúng tôi và đặt ngay để tận hưởng khoảnh khắc ngọt ngào.</p>
            <a href="#products" class="btn-hero">Khám phá ngay</a>
        </div>
    </div>
</section>

<!-- Featured Products Carousel -->
<!-- Featured Products Carousel -->
<section id="products" class="products-section py-5 bg-light">
    <div class="container position-relative">
        <h2 class="section-title text-center mb-5">Sản phẩm nổi bật</h2>

        <!-- Wrapper cho Swiper và Navigation -->
        <div class="swiper-container-wrapper">
            <!-- Swiper -->
            <div class="swiper featuredProductsSwiper">
                <div class="swiper-wrapper" id="featuredProducts">
                    <!-- AJAX sẽ chèn sản phẩm vào đây -->
                    <div class="swiper-slide text-center">
                        <p class="text-muted">Đang tải sản phẩm...</p>
                    </div>
                </div>
            </div>

            <!-- Navigation Buttons - ĐI RA NGOÀI Swiper -->
            <div class="swiper-button-prev custom-swiper-button">
                <i class="fas fa-chevron-left"></i>
            </div>
            <div class="swiper-button-next custom-swiper-button">
                <i class="fas fa-chevron-right"></i>
            </div>
        </div>

        <div class="text-center mt-5">
            <a href="#" class="btn btn-outline">Xem tất cả sản phẩm</a>
        </div>
    </div>
</section>

<!-- Category Sections -->
<!-- Entremet Section -->
<section class="category-section bg-white py-5">
    <div class="container">
        <h2 class="category-title text-center mb-3">Entremet</h2>
        <p class="category-description text-center mb-5">Đây là bánh kem siêu Pháp cao cấp, kết hợp hài hòa giữa mousse, thạch trái cây và lớp bánh mềm. Mỗi chiếc bánh là một tác phẩm tinh tế, mang đến trải nghiệm vị giác đa tầng độc đáo — sốc, chưa hết, thoáng nhẹ và quyến rũ.</p>
        
        <div class="swiper-container-wrapper">
            <div class="swiper categorySwiper entremetSwiper">
                <div class="swiper-wrapper" data-category="entremet">
                    <div class="swiper-slide text-center">
                        <p class="text-muted">Đang tải sản phẩm...</p>
                    </div>
                </div>
            </div>
            <div class="swiper-button-prev custom-swiper-button">
                <i class="fas fa-chevron-left"></i>
            </div>
            <div class="swiper-button-next custom-swiper-button">
                <i class="fas fa-chevron-right"></i>
            </div>
        </div>
    </div>
</section>

<!-- Mousse Section -->
<section class="category-section bg-light py-5">
    <div class="container">
        <h2 class="category-title text-center mb-3">Mousse</h2>
        <p class="category-description text-center mb-5">Bánh mang đến trải nghiệm mềm mịn, mát lạnh với hương vị từ trái cây tươi, socola hay matcha. Không cần nướng, mỗi chiếc bánh là sự kết hợp tinh tế giữa kem tươi và hương liệu, vừa nhẹ, hấp dẫn ngay từ miếng đầu tiên.</p>
        
        <div class="swiper-container-wrapper">
            <div class="swiper categorySwiper mousseSwiper">
                <div class="swiper-wrapper" data-category="mousse">
                    <div class="swiper-slide text-center">
                        <p class="text-muted">Đang tải sản phẩm...</p>
                    </div>
                </div>
            </div>
            <div class="swiper-button-prev custom-swiper-button">
                <i class="fas fa-chevron-left"></i>
            </div>
            <div class="swiper-button-next custom-swiper-button">
                <i class="fas fa-chevron-right"></i>
            </div>
        </div>
    </div>
</section>

<!-- Traditional Cake Section -->
<section class="category-section bg-white py-5">
    <div class="container">
        <h2 class="category-title text-center mb-3">Bánh Kem Truyền Thống</h2>
        <p class="category-description text-center mb-5">Bánh kem quen thuộc với lớp bánh bông lan mềm mịn, kết hợp kem tươi béo ngậy và trang trí đa dạng. Phù hợp cho mọi dịp lễ, sinh nhật hay kỷ niệm, mang đến hương vị ấm áp và thân quen.</p>
        
        <div class="swiper-container-wrapper">
            <div class="swiper categorySwiper traditionalSwiper">
                <div class="swiper-wrapper" data-category="traditional">
                    <div class="swiper-slide text-center">
                        <p class="text-muted">Đang tải sản phẩm...</p>
                    </div>
                </div>
            </div>
            <div class="swiper-button-prev custom-swiper-button">
                <i class="fas fa-chevron-left"></i>
            </div>
            <div class="swiper-button-next custom-swiper-button">
                <i class="fas fa-chevron-right"></i>
            </div>
        </div>
    </div>
</section>

<!-- Accessories Section -->
<section class="category-section bg-light py-5">
    <div class="container">
        <h2 class="category-title text-center mb-3">Phụ Kiện</h2>
        <p class="category-description text-center mb-5">Các sản phẩm bổ sung hoàn hảo cho bánh kem của bạn, từ nến sinh nhật, hộp đựng, đến các vật trang trí độc đáo. Giúp chiếc bánh thêm phần ấn tượng và đáng nhớ.</p>
        
        <div class="swiper-container-wrapper">
            <div class="swiper categorySwiper accessoriesSwiper">
                <div class="swiper-wrapper" data-category="accessories">
                    <div class="swiper-slide text-center">
                        <p class="text-muted">Đang tải sản phẩm...</p>
                    </div>
                </div>
            </div>
            <div class="swiper-button-prev custom-swiper-button">
                <i class="fas fa-chevron-left"></i>
            </div>
            <div class="swiper-button-next custom-swiper-button">
                <i class="fas fa-chevron-right"></i>
            </div>
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
// Load sản phẩm nổi bật khi trang sẵn sàng
document.addEventListener('DOMContentLoaded', function() {
    loadFeaturedProducts();
    loadCategoryProducts();

    // Smooth scroll cho các link #
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });

    // Contact Form Handler
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const btn = this.querySelector('button[type="submit"]');
            const originalText = btn.innerText;
            btn.innerText = 'Đang gửi...';
            btn.disabled = true;

            try {
                const formData = new FormData(this);
                const response = await fetch('/api/contact', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(Object.fromEntries(formData))
                });
                
                const data = await response.json();
                
                if (response.ok && data.success) {
                    alert(data.message || 'Gửi tin nhắn thành công!');
                    this.reset();
                } else {
                    const errorMessage = data.message || 'Có lỗi xảy ra. Vui lòng thử lại.';
                    const detailedError = data.error ? `\nChi tiết: ${data.error}` : '';
                    alert(`${errorMessage}${detailedError}`);
                    console.error('Errors:', data);
                }
            } catch (error) {
                console.error('Error submitting contact form:', error);
                alert(`Có lỗi xảy ra kết nối: ${error.message}`);
            } finally {
                btn.innerText = originalText;
                btn.disabled = false;
            }
        });
    }
});

async function loadFeaturedProducts() {
    try {
        const response = await fetch('/api/products/featured');
        const data = await response.json();

        if (data.success && data.data.products.length > 0) {
            // Chỉ lấy tối đa 6 sản phẩm để carousel đẹp (Swiper sẽ tự loop)
            const products = data.data.products.slice(0, 6);
            displayProducts(products);
        } else {
            showEmptyMessage('Chưa có sản phẩm nổi bật nào.');
        }
    } catch (error) {
        console.error('Lỗi tải sản phẩm:', error);
        showEmptyMessage('Không thể tải sản phẩm. Vui lòng thử lại sau.');
    }
}

async function loadCategoryProducts() {
    const categories = [
        { name: 'Entremet', selector: '.entremetSwiper .swiper-wrapper', swiperName: 'entremet' },
        { name: 'Mousse', selector: '.mousseSwiper .swiper-wrapper', swiperName: 'mousse' },
        { name: 'Truyền thống', selector: '.traditionalSwiper .swiper-wrapper', swiperName: 'traditional' },
        { name: 'Phụ kiện', selector: '.accessoriesSwiper .swiper-wrapper', swiperName: 'accessories' }
    ];

    for (const category of categories) {
        try {
            const response = await fetch(`/api/products?category=${category.name}`);
            const data = await response.json();

            if (data.success && data.data.products.length > 0) {
                const products = data.data.products.slice(0, 6);
                displayCategoryProducts(products, category.selector, category.swiperName);
            } else {
                showCategoryEmptyMessage(category.selector, 'Chưa có sản phẩm trong danh mục này.');
            }
        } catch (error) {
            console.error(`Lỗi tải sản phẩm ${category.name}:`, error);
            showCategoryEmptyMessage(category.selector, 'Không thể tải sản phẩm. Vui lòng thử lại sau.');
        }
    }
}

function displayProducts(products) {
    const wrapper = document.querySelector('#featuredProducts');
    if (!wrapper) return;

    wrapper.innerHTML = products.map(product => {
        const outOfStock = (product.quantity === 0);
        const stockBadge = outOfStock ? `<span class="badge out-of-stock">Hết hàng</span>` : '';
        const btn = outOfStock ? `<button class="btn btn-secondary" aria-disabled="true">Hết hàng</button>` : `<a href="/products/${product.product_id}" class="btn btn-primary">Xem chi tiết</a>`;

        return `
        <div class="swiper-slide">
            <div class="product-card" style="position:relative;">
                <div class="product-image">
                    ${stockBadge}
                    <img src="${product.image_url || '/images/placeholder.jpg'}"
                         alt="${product.product_name}"
                         onerror="this.src='/images/placeholder.jpg'">
                    ${product.is_featured ? '<span class="badge featured">Nổi bật</span>' : ''}
                    ${product.is_new ? '<span class="badge new">Mới</span>' : ''}
                </div>
                <div class="product-info">
                    <h3 class="product-name">${product.product_name}</h3>
                    <div class="product-price">
                        ${product.original_price && product.original_price > product.price 
                            ? `<span class="original-price">${formatPrice(product.original_price)}</span>` 
                            : ''}
                        <span class="current-price">${formatPrice(product.price)}</span>
                    </div>
                    ${btn}
                </div>
            </div>
        </div>
    `;
    }).join('');

    // Đợi DOM cập nhật xong rồi khởi tạo Swiper
    setTimeout(initFeaturedSwiper, 100);
}

function displayCategoryProducts(products, selector, categoryName) {
    const wrapper = document.querySelector(selector);
    if (!wrapper) return;

    // center if only one product to show it in the middle
    if (products.length === 1) {
        wrapper.classList.add('centered');
    } else {
        wrapper.classList.remove('centered');
    }

    wrapper.innerHTML = products.map(product => {
        const outOfStock = (product.quantity === 0);
        const stockBadge = outOfStock ? `<span class="badge out-of-stock">Hết hàng</span>` : '';
        const btn = outOfStock ? `<button class="btn btn-secondary" aria-disabled="true">Hết hàng</button>` : `<a href="/products/${product.product_id}" class="btn btn-primary">Xem chi tiết</a>`;

        return `
        <div class="swiper-slide">
            <div class="product-card" style="position:relative;">
                <div class="product-image">
                    ${stockBadge}
                    <img src="${product.image_url || '/images/placeholder.jpg'}"
                         alt="${product.product_name}"
                         onerror="this.src='/images/placeholder.jpg'">
                    ${product.is_featured ? '<span class="badge featured">Nổi bật</span>' : ''}
                    ${product.is_new ? '<span class="badge new">Mới</span>' : ''}
                </div>
                <div class="product-info">
                    <h3 class="product-name">${product.product_name}</h3>
                    <div class="product-price">
                        ${product.original_price && product.original_price > product.price 
                            ? `<span class="original-price">${formatPrice(product.original_price)}</span>` 
                            : ''}
                        <span class="current-price">${formatPrice(product.price)}</span>
                    </div>
                    ${btn}
                </div>
            </div>
        </div>
    `;
    }).join('');

    // Khởi tạo Swiper cho category
    setTimeout(() => initCategorySwiper(categoryName), 100);
}

function initFeaturedSwiper() {
    if (window.featuredSwiper) {
        window.featuredSwiper.destroy(true, true);
    }

    window.featuredSwiper = new Swiper('.featuredProductsSwiper', {
        loop: true,
        autoplay: {
            delay: 4000,
            disableOnInteraction: false,
        },
        speed: 800,
        spaceBetween: 30, // Khoảng cách giữa các bánh (giảm xuống một chút cho cân đối)
        slidesPerView: 3, // Hiển thị đúng 3 sản phẩm
        centeredSlides: false, // Giữ false để bánh đầu tiên sát lề trái

        navigation: {
            nextEl: '.swiper-button-next.custom-swiper-button',
            prevEl: '.swiper-button-prev.custom-swiper-button',
        },

        breakpoints: {
            0: {
                slidesPerView: 1,
                spaceBetween: 10
            },
            768: {
                slidesPerView: 2,
                spaceBetween: 20
            },
            992: {
                slidesPerView: 3,
                spaceBetween: 30
            }
        }
    });
}

function initCategorySwiper(categoryName) {
    const swiperClass = `.${categoryName}Swiper`;
    const swiperElement = document.querySelector(swiperClass);
    
    if (!swiperElement) return;

    // Destroy existing swiper if any
    if (window[`${categoryName}Swiper`]) {
        window[`${categoryName}Swiper`].destroy(true, true);
    }

    // Get the navigation buttons for this specific swiper
    const swiperWrapper = swiperElement.closest('.swiper-container-wrapper');
    const prevButton = swiperWrapper.querySelector('.swiper-button-prev');
    const nextButton = swiperWrapper.querySelector('.swiper-button-next');

    window[`${categoryName}Swiper`] = new Swiper(swiperClass, {
        loop: true,
        autoplay: {
            delay: 4500,
            disableOnInteraction: false,
        },
        speed: 800,
        spaceBetween: 30,
        slidesPerView: 3,
        centeredSlides: false,

        navigation: {
            nextEl: nextButton,
            prevEl: prevButton,
        },

        breakpoints: {
            0: {
                slidesPerView: 1,
                spaceBetween: 10
            },
            768: {
                slidesPerView: 2,
                spaceBetween: 20
            },
            992: {
                slidesPerView: 3,
                spaceBetween: 30
            }
        }
    });
}

function showEmptyMessage(msg) {
    const wrapper = document.querySelector('#featuredProducts');
    if (wrapper) {
        wrapper.innerHTML = `<div class="swiper-slide"><div style="text-align:center; padding:4rem 0; color:#666;">${msg}</div></div>`;
    }
}

function showCategoryEmptyMessage(selector, msg) {
    const wrapper = document.querySelector(selector);
    if (wrapper) {
        wrapper.innerHTML = `<div class="swiper-slide"><div style="text-align:center; padding:4rem 0; color:#666;">${msg}</div></div>`;
    }
}

function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(price);
}
</script>
@endpush
