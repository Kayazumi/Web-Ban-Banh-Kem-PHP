<?php $__env->startSection('title', 'La Cuisine Ngọt - Bánh Kem Cao Cấp'); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/customer.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
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
            <img src="<?php echo e(asset('images/chaomung1.jpg')); ?>" alt="Bánh kem cao cấp">
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

<!-- Promotions Section -->
<section id="khuyenmai" class="promotions-section">
    <div class="container">
        <h2 class="section-title">Khuyến mãi hấp dẫn</h2>
        <div class="promotions-grid">
            <div class="promotion-card">
                <img src="<?php echo e(asset('images/buy-1-get-1.jpg')); ?>" alt="Mua 1 tặng 1">
                <div class="promotion-content">
                    <h3>Mua 1 tặng 1</h3>
                    <p>Áp dụng cho các loại bánh entremet</p>
                </div>
            </div>
            <div class="promotion-card">
                <img src="<?php echo e(asset('images/free-ship.jpg')); ?>" alt="Miễn phí giao hàng">
                <div class="promotion-content">
                    <h3>Miễn phí giao hàng</h3>
                    <p>Đơn hàng từ 500.000đ</p>
                </div>
            </div>
            <div class="promotion-card">
                <img src="<?php echo e(asset('images/gg.jpg')); ?>" alt="Giảm giá">
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
                <img src="<?php echo e(asset('images/tamnhin.jpg')); ?>" alt="Tầm nhìn La Cuisine Ngọt">
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
                    <?php echo csrf_field(); ?>
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
<?php $__env->stopSection(); ?>


<?php $__env->startPush('scripts'); ?>
<script>
// Load sản phẩm nổi bật khi trang sẵn sàng
document.addEventListener('DOMContentLoaded', function() {
    loadFeaturedProducts();

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

function displayProducts(products) {
    const wrapper = document.querySelector('#featuredProducts');
    if (!wrapper) return;

    wrapper.innerHTML = products.map(product => `
        <div class="swiper-slide">
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
                    <div class="product-price">
                        ${product.original_price && product.original_price > product.price 
                            ? `<span class="original-price">${formatPrice(product.original_price)}</span>` 
                            : ''}
                        <span class="current-price">${formatPrice(product.price)}</span>
                    </div>
                    <a href="/products/${product.product_id}" class="btn btn-primary">Xem chi tiết</a>
                </div>
            </div>
        </div>
    `).join('');

    // Đợi DOM cập nhật xong rồi khởi tạo Swiper
    setTimeout(initFeaturedSwiper, 100);
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

function showEmptyMessage(msg) {
    const wrapper = document.querySelector('#featuredProducts');
    if (wrapper) {
        wrapper.innerHTML = `<div class="swiper-slide"><div style="text-align:center; padding:4rem 0; color:#666;">${msg}</div></div>`;
    }
}

function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(price);
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\ProgramFilesD\DevApps\XAM_PP\htdocs\Web-Ban-Banh-Kem-PHP\resources\views/home.blade.php ENDPATH**/ ?>