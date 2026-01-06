<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'La Cuisine Ngọt - Bánh Kem Cao Cấp')</title>
    <meta name="description" content="@yield('description', 'La Cuisine Ngọt - Thương hiệu bánh kem cao cấp hàng đầu Việt Nam')">
    <link rel="icon" type="image/png" href="https://cdn-icons-png.flaticon.com/512/2921/2921822.png">

    <!-- Preload fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Inspiration&family=Crimson+Text:wght@400;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- các meta, title, css chung -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/customer.css') }}">

    @stack('styles')
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <a href="{{ route('home') }}">La Cuisine Ngọt</a>
            </div>

            <ul class="nav-menu">
                <li><a href="{{ route('home') }}#products" class="nav-menu-link">SẢN PHẨM</a></li>
                <li><a href="{{ route('home') }}#khuyenmai" class="nav-menu-link">KHUYẾN MÃI</a></li>
                <li><a href="{{ route('home') }}#contact" class="nav-menu-link">LIÊN HỆ</a></li>

                <li>
                    <a href="#" class="nav-search">
                        <i class="fas fa-search"></i>
                    </a>
                </li>

                @auth
                <li>
                    <a href="{{ route('cart') }}" class="nav-cart" style="padding: 0 10px;">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-count" id="cartCount">0</span>
                    </a>
                </li>
                @endauth

                <li class="nav-user">
                    @auth
                        <div class="user-menu">
                            <span>Xin chào, {{ Auth::user()->full_name }}</span>
                            <ul>
                                <li><a href="{{ route('profile') }}">Thông tin tài khoản</a></li>
                                <li><a href="{{ route('orders.index') }}">Đơn hàng của tôi</a></li>
                                @if(Auth::user()->isAdmin())
                                <li><a href="{{ route('admin.dashboard') }}">Quản trị</a></li>
                                @endif
                                <li><button id="logoutBtn" onclick="logout()">Đăng xuất</button></li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="nav-login-1">ĐĂNG NHẬP</a>
                        <span class="nav-separator">|</span>
                        <a href="{{ route('register') }}" class="nav-login-2">ĐĂNG KÝ</a>
                    @endauth
                </li>
            </ul>
        </div>
    </nav>

    <!-- Search Bar -->
    <div class="search-bar hidden">
        <input type="text" id="searchInput" placeholder="Nhập từ khóa tìm kiếm...">
        <button class="search-submit-btn" type="button" id="searchSubmitBtn">
            <i class="fas fa-check"></i>
        </button>

        <div class="filter-wrapper">
            <button class="filter-btn">Lọc</button>

            <div class="filter-popup hidden">
                <div class="filter-content">
                    <label for="categorySelect">Danh mục</label>
                    <select id="categorySelect">
                        <option value="">Tất cả danh mục</option>
                    </select>

                    <label for="priceSelect">Giá</label>
                    <select id="priceSelect">
                        <option value="">Tất cả giá</option>
                        <option value="duoi500">Dưới 500.000</option>
                        <option value="500-700">500.000 - 700.000</option>
                        <option value="tren700">Trên 700.000</option>
                    </select>

                    <button id="filterButton">Lọc</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>La Cuisine Ngọt</h3>
                <p>Thương hiệu bánh kem cao cấp hàng đầu Việt Nam</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                </div>
            </div>

            <div class="footer-section">
                <h4>Liên kết</h4>
                <ul>
                    <li><a href="{{ route('home') }}#products">Sản phẩm</a></li>
                    <li><a href="{{ route('home') }}#khuyenmai">Khuyến mãi</a></li>
                    <li><a href="{{ route('home') }}#contact">Liên hệ</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h4>Liên hệ</h4>
                <ul>
                    <li><i class="fas fa-phone"></i> 0901 234 567</li>
                    <li><i class="fas fa-envelope"></i> info@lacuisine.vn</li>
                    <li><i class="fas fa-map-marker-alt"></i> TP. Hồ Chí Minh</li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; 2025 La Cuisine Ngọt. Tất cả quyền được bảo lưu.</p>
        </div>
    </footer>

    <script>
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}',
            isLoggedIn: {{ auth()->check() ? 'true' : 'false' }},
            user: {!! auth()->check() ? json_encode(auth()->user()->only(['UserID', 'full_name', 'role'])) : 'null' !!},
            routes: {
                logout: '{{ route("logout") }}',
                cart: '{{ url("api/cart") }}',
                products: '{{ url("api/products") }}',
                orders: '{{ url("api/orders") }}'
            }
        };

        // Toggle search bar
        document.querySelector('.nav-search')?.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelector('.search-bar')?.classList.toggle('hidden');
        });

        // Toggle filter popup
        document.querySelector('.filter-btn')?.addEventListener('click', function() {
            document.querySelector('.filter-popup')?.classList.toggle('hidden');
        });

        // Logout function
        function logout() {
            if (confirm('Bạn có chắc muốn đăng xuất?')) {
                fetch(window.Laravel.routes.logout, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': window.Laravel.csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = '/';
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }
    </script>

    @stack('scripts')
</body>
</html>