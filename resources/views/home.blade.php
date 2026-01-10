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

/* Welcome Section */
.welcome-section { background: #fff; overflow: hidden; }
.welcome-content { display: flex; align-items: center; gap: 60px; }
.welcome-images { 
    flex: 0 0 45%; 
    display: grid; 
    grid-template-columns: 1fr 1fr; 
    gap: 15px;
    position: relative;
}
.welcome-img-1, .welcome-img-2 { 
    width: 100%; 
    height: auto; 
    border-radius: 12px; 
    object-fit: cover;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}
.welcome-img-1 { 
    grid-column: 1 / 2; 
    align-self: start; 
    max-height: 380px;
    margin-top: 0;
}
.welcome-img-2 { 
    grid-column: 2 / 3; 
    align-self: end; 
    max-height: 300px; 
    margin-top: 60px;
}
.welcome-text { flex: 1; }
.welcome-title { 
    font-family: 'Crimson Text';
    font-size: 28px; 
    margin: 0 0 20px;
    color: #333;
    line-height: 1.4;
    font-weight: normal;
}
.brand-name { 
    font-family: 'Inspiration', cursive; 
    font-size: 48px;
    color: #23492f;
    font-style: italic;
    font-weight: normal;
    display: block;
    margin-top: 5px;
}
.welcome-description { 
    color: #555; 
    line-height: 1.8; 
    margin-bottom: 18px;
    text-align: justify;
}
.welcome-closing { 
    color: #555; 
    line-height: 1.8; 
    margin-bottom: 0;
    text-align: justify;
}
@media (max-width: 768px) {
    .welcome-content { flex-direction: column; gap: 30px; }
    .welcome-images { flex: 1; width: 100%; }
    .welcome-img-1 { margin-top: 0; }
    .welcome-img-2 { margin-top: 40px; max-height: 250px; }
    .welcome-title { font-size: 22px; }
    .brand-name { font-size: 36px; }
}

/* Vision & Mission Section */
.vision-mission-section { background: #f8f8f8; }
.vision-mission-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
    align-items: center;
}
.vm-item {
    width: 100%;
}
.vm-image {
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}
.vm-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    max-height: 350px;
}
.vm-content {
    padding: 20px 0;
}
.vm-title {
    font-family:'Crimson Text';
    font-size: 36px;
    color: #333;
    margin: 0 0 20px;
    font-weight: 600;
}
.vm-text-box {
    border-left: 4px solid #c9a961;
    padding-left: 24px;
    background: transparent;
}
.vm-text-box p {
    color: #555;
    line-height: 1.8;
    margin: 0;
    font-size: 15px;
    text-align: justify;
}
@media (max-width: 992px) {
    .vision-mission-grid {
        grid-template-columns: 1fr;
        gap: 30px;
    }
    .vm-title {
        font-size: 28px;
    }
    .vm-image img {
        max-height: 300px;
    }
}

/* Core Values Section */
.core-values-section {
    background: #e8dcc8;
    padding: 60px 0;
}
.core-values-title {
    font-family:'Crimson Text';
    font-size: 36px;
    color: #333;
    font-weight: 600;
    letter-spacing: 2px;
    margin-bottom: 40px;
}
.core-values-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 30px;
}
.value-card {
    background: #fff;
    padding: 30px 20px;
    border-radius: 16px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    min-height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.value-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.12);
}
.value-text {
    color: #333;
    line-height: 1.7;
    margin: 0;
    font-size: 14px;
    text-align: center;
}
.value-text strong {
    display: block;
    margin-bottom: 8px;
    font-size: 16px;
    color: #23492f;
}
@media (max-width: 1200px) {
    .core-values-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 25px;
    }
}
@media (max-width: 768px) {
    .core-values-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    .core-values-title {
        font-size: 28px;
    }
    .value-card {
        min-height: 160px;
        padding: 25px 18px;
    }
}

/* Sourcing Strategy Section */
.sourcing-section {
    position: relative;
    background-image: url('/images/nho.jpg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    min-height: 600px;
    display: flex;
    align-items: center;
    padding: 80px 0;
}
.sourcing-text-content {
    position: relative;
    max-width: 460px;
    margin-left: 80px;
    background: rgba(255, 255, 255, 0.75);
    padding: 45px 40px;
    border-radius: 0;
}
.sourcing-title {
    font-family: 'Crimson Text';
    font-size: 24px;
    color: #4a5d4a;
    font-weight: 600;
    margin: 0 0 18px;
    letter-spacing: 0.5px;
} 
.sourcing-description {
    color: #444;
    line-height: 1.7;
    margin-bottom: 15px;
    text-align: justify;
    font-size: 13.5px;
}
.sourcing-description strong {
    color: #2d4a2d;
    font-weight: 600;
}
@media (max-width: 992px) {
    .sourcing-section {
        min-height: 500px;
        padding: 60px 0;
    }
    .sourcing-text-content {
        margin-left: 40px;
        margin-right: 40px;
        max-width: calc(100% - 80px);
    }
}
@media (max-width: 768px) {
    .sourcing-section {
        min-height: 450px;
        padding: 50px 0;
    }
    .sourcing-text-content {
        margin-left: 20px;
        margin-right: 20px;
        max-width: calc(100% - 40px);
        padding: 35px 30px;
    }
    .sourcing-title {
        font-size: 20px;
    }
    .sourcing-description {
        font-size: 13px;
    }
}







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

<!-- Welcome Section -->
<section class="welcome-section py-5 bg-white">
    <div class="container">
        <div class="welcome-content">
            <div class="welcome-images">
                <img src="{{ asset('images/chaomung1.jpg') }}" alt="La Cuisine Ngọt - Artisan Baking" class="welcome-img-1">
                <img src="{{ asset('images/chaomung2.jpg') }}" alt="La Cuisine Ngọt - Quality Products" class="welcome-img-2">
            </div>
            <div class="welcome-text">
                <h2 class="welcome-title">Chào mừng bạn đến với<br><span class="brand-name">La Cuisine Ngọt!</span></h2>
                <p class="welcome-description">
                    Đây là "ngôi nhà nhỏ" của chúng mình, chất chứa từ niềm yêu dành cho bánh trái, sự dạn mặt với hương vị và lòng chân thành được lan toả mỗi khi mỗi món ngọt ngào mắt đến bạn. Ở đây, bạn có thể tìm thấy những "đứa con tinh thần" của chúng mình hiện diện rực rỡ và sống động qua từng sản phẩm chỉn chu và tâm huyết.
                </p>
                <p class="welcome-description">
                    Ở đây, dù chỉ là một cái dừng chân tình cờ, hay một sự tìm kiếm có chủ đích, chúng mình mong bạn sẽ luôn có thể dễ chịu hơn toàn và chọn được những hương vị, thật tâm ý để hướng chiều bạn thầu hoặc sẻ chia người thương quý.
                </p>
                <p class="welcome-closing">
                    Chúc bạn có những phút thảnh thơi trong "ngôi nhà hương vị" này và tận hưởng hành trình khám phá thú vị của riêng mình, bạn nhé!
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Vision & Mission Section -->
<section class="vision-mission-section py-5 bg-light">
    <div class="container">
        <div class="vision-mission-grid">
            <!-- Row 1: Mission Image (left) & Vision Content (right) -->
            <div class="vm-item vm-image">
                <img src="{{ asset('images/sumenh.jpg') }}" alt="Sứ mệnh La Cuisine Ngọt">
            </div>
            <div class="vm-item vm-content">
                <h3 class="vm-title">Tầm nhìn</h3>
                <div class="vm-text-box">
                    <p>Đặt trọn đam mê và tâm huyết trong từng sản phẩm, chúng tôi mong muốn trở thành lựa chọn uy tín hàng đầu của khách hàng, cung cấp những món tráng miệng đặc sắc nhất cho bạn tiệc thêm phần đáng nhớ!</p>
                </div>
            </div>
            
            <!-- Row 2: Mission Content (left) & Vision Image (right) -->
            <div class="vm-item vm-content">
                <h3 class="vm-title">Sứ mệnh</h3>
                <div class="vm-text-box">
                    <p>Với quyết tâm "tái định vị" vị thế của chiếc bánh sinh nhật trên bàn tiệc, chúng tôi hỗ lực sáng tạo nên những hương vị khác biệt, độc đáo và ngon miệng để  mang đến những trải nghiệm tuyệt vời nhất cho khách hàng; đồng thời xây dựng chuẩn mực mới của hương vị, đó là: không chỉ ngon mà còn chạm đến cảm xúc.</p>
                </div>
            </div>
            <div class="vm-item vm-image">
                <img src="{{ asset('images/tamnhin.jpg') }}" alt="Tầm nhìn La Cuisine Ngọt">
            </div>
        </div>
    </div>
</section>

<!-- Core Values Section -->
<section class="core-values-section py-5">
    <div class="container">
        <h2 class="core-values-title text-center mb-5">GIÁ TRỊ CỐT LÕI</h2>
        <div class="core-values-grid">
            <div class="value-card">
                <p class="value-text">
                    <strong>Tận tâm</strong> Nỗ lực hết mình trên hành trình sáng tạo sản phẩm, chu đáo và tận tụy trong từng dịch vụ gửi đến khách hàng.
                </p>
            </div>
            <div class="value-card">
                <p class="value-text">
                    <strong>Trách nhiệm</strong> Độc lộng mang đến những giá trị tốt nhất cho khách hàng thông qua tinh thần trách nhiệm cao độ.
                </p>
            </div>
            <div class="value-card">
                <p class="value-text">
                    <strong>Khiêm tốn</strong> Khiêm tốn học hỏi, trau dồi kỹ năng, kinh nghiệm và chân thành tiếp thu những phản hồi của khách hàng.
                </p>
            </div>
            <div class="value-card">
                <p class="value-text">
                    <strong>Đáng tin cậy</strong> Chú trọng nâng cao chất lượng sản phẩm, dịch vụ để tạo dựng uy tín thương hiệu và vun đắp sự yêu mến của khách hàng.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Sourcing Strategy Section -->
<section class="sourcing-section">
    <div class="sourcing-text-content">
        <h2 class="sourcing-title">CHIẾN LƯỢC THU MUA ĐỘC ĐÁO</h2>
        <p class="sourcing-description">
            Với tinh yêu mãnh liệt dành cho trái cây Việt Nam, ngay từ những ngày đầu thành lập đến nay, <strong>LA CUSINE NGỌT</strong> luôn trung thành với nguồn nguyên liệu bản địa đặc sắc.
        </p>
        <p class="sourcing-description">
            Hiểu rằng mỗi vùng sẽ cho loại trái cây ngon tùy theo từng mùa riêng biệt, do đó, chúng tôi dành nhiều thời gian khảo sát các trang trại, nhà vườn tại nhiều địa phương trên khắp cả nước. Điều này tạo cơ hội để chúng tôi hiểu hơn về đặc tính của các loại trái cây, cách thức chăm bảo đốc đáo của người nông dân, tâm huyết của họ đặt trọn trong khu vườn và trên tất cả, chúng tôi may mắn tìm được những nhà vườn đạt chuẩn để cung ứng nguồn nguyên liệu lâu dài, chất lượng cho <strong>LA CUSINE NGỌT</strong>
        </p>
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

<!-- Contact Section -->
<section id="contact" class="contact-section">
    <div class="container">
        <h2 class="section-title">Liên hệ với chúng tôi</h2>
        <div class="contact-content">
            <div class="contact-form">
                <h3>Điền thông tin để La Cuisine Ngọt có thể liên hệ với bạn trong thời gian sớm nhất nhé.</h3>
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

// ============================================
// CHATBOT FUNCTIONALITY
// ============================================
const chatbotToggle = document.getElementById('chatbot-toggle');
const chatbotWidget = document.getElementById('chatbot-widget');
const chatbotClose = document.getElementById('chatbot-close');
const chatbotInput = document.getElementById('chatbot-input-field');
const chatbotSend = document.getElementById('chatbot-send');
const chatbotMessages = document.getElementById('chatbot-messages');

// Toggle chatbot widget
chatbotToggle.addEventListener('click', function() {
    chatbotWidget.style.display = 'block';
    chatbotToggle.style.display = 'none';
    chatbotInput.focus();
});

// Close chatbot widget
chatbotClose.addEventListener('click', function() {
    chatbotWidget.style.display = 'none';
    chatbotToggle.style.display = 'flex';
});

// Send message on button click
chatbotSend.addEventListener('click', sendMessage);

// Send message on Enter key
chatbotInput.addEventListener('keypress', function(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendMessage();
    }
});

async function sendMessage() {
    const message = chatbotInput.value.trim();
    
    if (!message) return;
    
    // Add user message to chat
    addMessageToChat('user', message);
    
    // Clear input
    chatbotInput.value = '';
    
    // Disable input while waiting
    chatbotInput.disabled = true;
    chatbotSend.disabled = true;
    
    // Show typing indicator
    const typingId = addTypingIndicator();
    
    try {
        const response = await fetch('/api/chatbot', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({ message: message })
        });
        
        const data = await response.json();
        
        // Remove typing indicator
        removeTypingIndicator(typingId);
        
        if (data.success) {
            addMessageToChat('bot', data.message);
        } else {
            addMessageToChat('bot', data.message || 'Xin lỗi, có lỗi xảy ra. Vui lòng thử lại.');
        }
    } catch (error) {
        console.error('Chatbot error:', error);
        removeTypingIndicator(typingId);
        addMessageToChat('bot', 'Không thể kết nối đến server. Vui lòng kiểm tra kết nối và thử lại.');
    } finally {
        // Re-enable input
        chatbotInput.disabled = false;
        chatbotSend.disabled = false;
        chatbotInput.focus();
    }
}

function addMessageToChat(type, content) {
    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${type}-message`;
    
    if (type === 'bot') {
        messageDiv.innerHTML = `
            <div class="message-avatar">
                <i class="fas fa-robot"></i>
            </div>
            <div class="message-content">${escapeHtml(content)}</div>
        `;
    } else {
        messageDiv.innerHTML = `
            <div class="message-content">${escapeHtml(content)}</div>
        `;
    }
    
    chatbotMessages.appendChild(messageDiv);
    chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
}

function addTypingIndicator() {
    const typingDiv = document.createElement('div');
    typingDiv.className = 'message bot-message typing-indicator';
    typingDiv.id = 'typing-' + Date.now();
    typingDiv.innerHTML = `
        <div class="message-avatar">
            <i class="fas fa-robot"></i>
        </div>
        <div class="message-content">
            <span></span><span></span><span></span>
        </div>
    `;
    chatbotMessages.appendChild(typingDiv);
    chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
    return typingDiv.id;
}

function removeTypingIndicator(id) {
    const typingDiv = document.getElementById(id);
    if (typingDiv) {
        typingDiv.remove();
    }
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
</script>

<style>
/* ============================================
   CHATBOT STYLES
   ============================================ */
#chatbot-container {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 9999;
}

.chatbot-toggle {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #ff6b6b, #ff8e53);
    border: none;
    color: white;
    font-size: 24px;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(255, 107, 107, 0.4);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.chatbot-toggle:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(255, 107, 107, 0.6);
}

.chatbot-widget {
    width: 380px;
    height: 550px;
    background: white;
    border-radius: 16px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    animation: slideUp 0.3s ease;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.chatbot-header {
    background: linear-gradient(135deg, #ff6b6b, #ff8e53);
    color: white;
    padding: 16px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.chatbot-header-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.chatbot-header-info i {
    font-size: 28px;
}

.chatbot-header-info h4 {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
}

.chatbot-status {
    font-size: 12px;
    opacity: 0.9;
}

.chatbot-close-btn {
    background: transparent;
    border: none;
    color: white;
    font-size: 20px;
    cursor: pointer;
    padding: 4px;
    transition: opacity 0.2s;
}

.chatbot-close-btn:hover {
    opacity: 0.8;
}

.chatbot-messages {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
    background: #f8f9fa;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.message {
    display: flex;
    gap: 10px;
    align-items: flex-start;
}

.bot-message {
    flex-direction: row;
}

.user-message {
    flex-direction: row-reverse;
    justify-content: flex-start;
}

.message-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, #ff6b6b, #ff8e53);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.message-avatar i {
    font-size: 18px;
}

.message-content {
    max-width: 70%;
    padding: 12px 16px;
    border-radius: 12px;
    line-height: 1.5;
    font-size: 14px;
}

.bot-message .message-content {
    background: white;
    color: #333;
    border-bottom-left-radius: 4px;
}

.user-message .message-content {
    background: linear-gradient(135deg, #ff6b6b, #ff8e53);
    color: white;
    border-bottom-right-radius: 4px;
}

.typing-indicator .message-content {
    display: flex;
    gap: 4px;
    padding: 16px;
}

.typing-indicator .message-content span {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #999;
    animation: typing 1.4s infinite;
}

.typing-indicator .message-content span:nth-child(2) {
    animation-delay: 0.2s;
}

.typing-indicator .message-content span:nth-child(3) {
    animation-delay: 0.4s;
}

@keyframes typing {
    0%, 60%, 100% {
        transform: translateY(0);
        opacity: 0.7;
    }
    30% {
        transform: translateY(-10px);
        opacity: 1;
    }
}

.chatbot-input {
    padding: 16px;
    background: white;
    border-top: 1px solid #e9ecef;
    display: flex;
    gap: 10px;
}

.chatbot-input input {
    flex: 1;
    padding: 12px 16px;
    border: 1px solid #dee2e6;
    border-radius: 24px;
    outline: none;
    font-size: 14px;
    transition: border-color 0.2s;
}

.chatbot-input input:focus {
    border-color: #ff6b6b;
}

.chatbot-send-btn {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: linear-gradient(135deg, #ff6b6b, #ff8e53);
    border: none;
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.2s;
}

.chatbot-send-btn:hover {
    transform: scale(1.05);
}

.chatbot-send-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Mobile responsive */
@media (max-width: 768px) {
    .chatbot-widget {
        width: calc(100vw - 40px);
        height: calc(100vh - 100px);
        max-width: 380px;
    }
}
</style>

@endpush
