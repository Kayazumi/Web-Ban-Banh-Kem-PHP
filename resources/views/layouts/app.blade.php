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
    <style>
    /* Global centered modal used for confirmations/alerts */
    .global-modal-overlay {
        position: fixed;
        inset: 0;
        display: none;
        align-items: center;
        justify-content: center;
        background: rgba(0,0,0,0.5);
        z-index: 99999;
    }
    .global-modal {
        background: #0f1b1a;
        color: #e8fff9;
        border-radius: 10px;
        max-width: 540px;
        width: 92%;
        padding: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.6);
    }
    .global-modal .modal-title { font-weight:700; margin-bottom:8px; }
    .global-modal .modal-body { margin-bottom:14px; line-height:1.45; }
    .global-modal .modal-actions { text-align:right; }
    .global-modal .btn-primary {
        background:#7fe3d0;
        color:#05332d;
        border:0;
        padding:8px 14px;
        border-radius:8px;
        cursor:pointer;
        font-weight:600;
    }
    .global-modal .btn-secondary {
        background:transparent;
        color:#cdebe5;
        border:1px solid rgba(255,255,255,0.06);
        padding:8px 12px;
        border-radius:8px;
        cursor:pointer;
        margin-right:8px;
    }
    /* Auth links in navbar - make login/register visually prominent */
    .nav-login-1, .nav-login-2 {
        display: inline-block;
        padding: 6px 10px;
        border-radius: 6px;
        text-decoration: none;
        color: #fff;
        font-weight: 600;
        transition: all .15s ease;
        margin-left: 6px;
    }
    .nav-login-1:hover, .nav-login-2:hover { transform: translateY(-1px); opacity: 0.95; }
    .nav-login-2 {
        background: #fff;
        color: #1f4d3a;
        padding: 8px 14px;
    }
    .nav-separator { color: rgba(255,255,255,0.35); margin: 0 6px; }
    /* auth-actions container style */
    .auth-actions {
        display: inline-flex;
        gap: 8px;
        align-items: center;
        margin-left: 12px;
    }
    /* make login link look like subtle outline button */
    .nav-login-1 {
        background: transparent;
        border: 1px solid rgba(255,255,255,0.08);
        padding: 8px 12px;
        color: #fff;
    }
    /* adjust register button to match register page style */
    .nav-login-2 {
        background: #fff;
        color: #1f4d3a;
        padding: 8px 14px;
        box-shadow: 0 6px 18px rgba(31,77,58,0.12);
    }
    </style>
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
                        <div class="auth-actions">
                            <a href="{{ route('login') }}" class="nav-login-1">ĐĂNG NHẬP</a>
                            <a href="{{ route('register') }}" class="nav-login-2">ĐĂNG KÝ</a>
                        </div>
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

        // Logout function (uses centered confirm modal)
        function logout() {
            showConfirm('Bạn có chắc muốn đăng xuất?', function() {
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
            }, function() {
                // canceled
            });
        }
    </script>

    @stack('scripts')
    <!-- Global modal HTML -->
    <div id="globalModalOverlay" class="global-modal-overlay" aria-hidden="true">
        <div class="global-modal" role="dialog" aria-modal="true" aria-labelledby="globalModalTitle">
            <div id="globalModalTitle" class="modal-title">Thông báo</div>
            <div id="globalModalBody" class="modal-body"></div>
            <div class="modal-actions">
                <button id="globalModalCancel" class="btn-secondary" style="display:none;">Huỷ</button>
                <button id="globalModalOk" class="btn-primary">OK</button>
            </div>
        </div>
    </div>

    <script>
    (function() {
        const overlay = document.getElementById('globalModalOverlay');
        const titleEl = document.getElementById('globalModalTitle');
        const bodyEl = document.getElementById('globalModalBody');
        const okBtn = document.getElementById('globalModalOk');
        const cancelBtn = document.getElementById('globalModalCancel');

        function showConfirm(message, onOk, onCancel, opts = {}) {
            titleEl.textContent = opts.title || 'Xác nhận';
            bodyEl.innerHTML = escapeHtml(message);
            cancelBtn.style.display = 'inline-block';
            okBtn.textContent = opts.okText || 'OK';
            cancelBtn.textContent = opts.cancelText || 'Huỷ';
            overlay.style.display = 'flex';
            overlay.setAttribute('aria-hidden', 'false');

            function handleOk() {
                hide();
                okBtn.removeEventListener('click', handleOk);
                cancelBtn.removeEventListener('click', handleCancel);
                if (onOk) onOk();
            }
            function handleCancel() {
                hide();
                okBtn.removeEventListener('click', handleOk);
                cancelBtn.removeEventListener('click', handleCancel);
                if (onCancel) onCancel();
            }

            okBtn.addEventListener('click', handleOk);
            cancelBtn.addEventListener('click', handleCancel);
        }

        function showAlert(message, onOk, opts = {}) {
            titleEl.textContent = opts.title || 'Thông báo';
            bodyEl.innerHTML = message;
            cancelBtn.style.display = 'none';
            okBtn.textContent = opts.okText || 'OK';
            overlay.style.display = 'flex';
            overlay.setAttribute('aria-hidden', 'false');

            function handleOk() {
                hide();
                okBtn.removeEventListener('click', handleOk);
                if (onOk) onOk();
            }
            okBtn.addEventListener('click', handleOk);
            if (onOk && opts.autoClose) {
                setTimeout(handleOk, opts.timeout || 2000);
            }
        }

        function hide() {
            overlay.style.display = 'none';
            overlay.setAttribute('aria-hidden', 'true');
        }

        function escapeHtml(unsafe) {
            return String(unsafe)
              .replace(/&/g, '&amp;')
              .replace(/</g, '&lt;')
              .replace(/>/g, '&gt;')
              .replace(/"/g, '&quot;')
              .replace(/'/g, '&#039;');
        }

        window.showConfirm = showConfirm;
        window.showAlert = showAlert;
    })();
    </script>
</body>
</html>