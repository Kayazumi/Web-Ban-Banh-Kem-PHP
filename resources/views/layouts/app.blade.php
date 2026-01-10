<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'La Cuisine Ngọt - Bánh Kem Cao Cấp')</title>
    <meta name="description" content="@yield('description', 'La Cuisine Ngọt - Thương hiệu bánh kem cao cấp hàng đầu Việt Nam')">
    
    <link rel="icon" type="image/png" href="https://cdn-icons-png.flaticon.com/512/2921/2921822.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

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
    <link rel="stylesheet" href="{{ asset('css/account.css') }}">
    <link rel="stylesheet" href="{{ asset('css/chatbot.css') }}">

    @stack('styles')

    <style>
        /* Global centered modal used for confirmations/alerts */
        .global-modal-overlay {
            position: fixed;
            inset: 0;
            display: none;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            z-index: 99999;
            opacity: 0;
            transition: opacity 0.25s ease;
        }

        .global-modal-overlay.show {
            opacity: 1;
        }

        .global-modal {
            background: #0f1b1a;
            color: #e8fff9;
            border-radius: 12px;
            max-width: 440px;
            width: 92%;
            padding: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.8);
            transform: scale(0.9);
            transition: transform 0.25s ease;
        }

        .global-modal-overlay.show .global-modal {
            transform: scale(1);
        }

        .global-modal .modal-title {
            font-weight: 700;
            font-size: 18px;
            margin-bottom: 12px;
        }

        .global-modal .modal-body {
            margin-bottom: 20px;
            line-height: 1.6;
            font-size: 15px;
        }

        .global-modal .modal-actions {
            text-align: right;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .global-modal .btn-primary {
            background: #7fe3d0;
            color: #05332d;
            border: 0;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .global-modal .btn-primary:hover {
            background: #6dd4bf;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(127, 227, 208, 0.4);
        }

        .global-modal .btn-secondary {
            background: transparent;
            color: #cdebe5;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .global-modal .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.3);
        }

        /* Auth links in navbar - make login/register visually prominent */
        .nav-login-1,
        .nav-login-2 {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 6px;
            text-decoration: none;
            color: #fff;
            font-weight: 600;
            transition: all .15s ease;
            margin-left: 6px;
        }

        .nav-login-1:hover,
        .nav-login-2:hover {
            transform: translateY(-1px);
            opacity: 0.95;
        }

        .nav-login-2 {
            background: #fff;
            color: #1f4d3a;
            padding: 8px 14px;
        }

        .nav-separator {
            color: rgba(255, 255, 255, 0.35);
            margin: 0 6px;
        }

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
            border: 1px solid rgba(255, 255, 255, 0.08);
            padding: 8px 12px;
            color: #fff;
        }

        /* adjust register button to match register page style */
        .nav-login-2 {
            background: #fff;
            color: #1f4d3a;
            padding: 8px 14px;
            box-shadow: 0 6px 18px rgba(31, 77, 58, 0.12);
        }
    </style>


    <!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="{{ asset('js/customer.js') }}"></script>

</head>

<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                @php
                    $logoLink = route('home');
                    if(auth()->check()) {
                        $role = auth()->user()->role;
                        if($role === 'staff' && Route::has('staff.profile')) $logoLink = route('staff.profile');
                        elseif($role === 'admin') $logoLink = route('admin.dashboard');
                    }
                @endphp
                <a href="{{ $logoLink }}">La Cuisine Ngọt</a>
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
                {{-- Chỉ hiển thị Giỏ hàng cho Khách hàng --}}
                @if(Auth::user()->role === 'customer')
                <li>
                    <a href="{{ route('cart') }}" class="nav-cart" style="padding: 0 10px;">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-count" id="cartCount">0</span>
                    </a>
                </li>
                @endif
                @endauth

                <li class="nav-user">
                    @auth
                        <div class="nav-user-dropdown">
                            <button class="user-dropdown-toggle" id="userDropdownToggle">
                                <i class="fas fa-user-circle"></i>
                                <span class="user-name">{{ Auth::user()->full_name }}</span>
                                <i class="fas fa-chevron-down chevron-icon"></i>
                            </button>

                            <div class="user-dropdown-menu" id="userDropdownMenu">
                                <div class="dropdown-header">
                                    <div class="dropdown-user-info">
                                        <i class="fas fa-user-circle user-avatar"></i>
                                        <div class="user-details">
                                            <strong>{{ Auth::user()->full_name }}</strong>
                                            <span class="user-role">{{ Auth::user()->role === 'admin' ? 'Quản trị viên' : (Auth::user()->role === 'staff' ? 'Nhân viên' : 'Khách hàng') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="dropdown-divider"></div>

                                <ul class="dropdown-items">
                                    <li>
                                        <a href="{{ route('profile') }}" class="dropdown-item">
                                            <i class="fas fa-user"></i>
                                            <span>Thông tin tài khoản</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('order.history') }}" class="dropdown-item">
                                            <i class="fas fa-receipt"></i>
                                            <span>Đơn hàng của tôi</span>
                                        </a>
                                    </li>
                                    @if(Auth::user()->isAdmin())
                                    <li>
                                        <a href="{{ route('admin.dashboard') }}" class="dropdown-item">
                                            <i class="fas fa-tools"></i>
                                            <span>Quản trị</span>
                                        </a>
                                    </li>
                                    @endif
                                    <li>
                                        <button type="button" class="dropdown-item logout-item" onclick="logout()">
                                            <i class="fas fa-sign-out-alt"></i>
                                            <span>Đăng xuất</span>
                                        </button>
                                    </li>
                                </ul>
                            </div>
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
    <input type="text" id="searchInput" placeholder="Tìm kiếm hương vị ngọt ngào...">

    <button class="search-submit-btn" type="button" id="searchSubmitBtn">
        <i class="fas fa-search"></i>
    </button>

    <div class="filter-wrapper">
        <button class="filter-btn" title="Lọc sản phẩm">
            <i class="fas fa-filter"></i>
        </button>

        <div class="filter-popup hidden">
            <div class="filter-content">
                <label for="category">Danh mục</label>
                <select id="category">
                    <option value="">Tất cả danh mục</option>
                    <option value="banh-sinh-nhat">Bánh sinh nhật</option>
                    <option value="banh-kem-tuoi">Bánh kem tươi</option>
                    <option value="banh-cupcake">Cupcake</option>
                    <option value="banh-man">Bánh mặn</option>
                </select>

                <label for="price-range">Khoảng giá</label>
                <select id="price-range">
                    <option value="">Tất cả giá</option>
                    <option value="0-200000">Dưới 200.000đ</option>
                    <option value="200000-400000">200.000đ - 400.000đ</option>
                    <option value="400000-600000">400.000đ - 600.000đ</option>
                    <option value="600000-1000000">600.000đ - 1.000.000đ</option>
                    <option value="1000000+">Trên 1.000.000đ</option>
                </select>

                <label for="sort">Sắp xếp theo</label>
                <select id="sort">
                    <option value="newest">Mới nhất</option>
                    <option value="price-asc">Giá tăng dần</option>
                    <option value="price-desc">Giá giảm dần</option>
                    <option value="bestseller">Bán chạy nhất</option>
                </select>

                <button type="button" id="applyFilterBtn">Áp dụng lọc</button>
                <button type="button" id="clearFilterBtn" style="background: #999; margin-top: 8px;">Xóa lọc</button>
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

    @php
        $laravelData = [
            'csrfToken' => csrf_token(),
            'isLoggedIn' => auth()->check(),
            'user' => auth()->check() ? auth()->user()->only(['UserID', 'full_name', 'role']) : null,
            'routes' => [
                'logout' => route("logout"),
                'cart' => url("api/cart"),
                'products' => url("api/products"),
                'orders' => route("api.orders.index"),
            ]
        ];
    @endphp
    <script>
        // Expose server data safely to JS
        <?php echo 'window.Laravel = ' . json_encode($laravelData, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP) . ';'; ?>
        // Backwards-compat alias used by legacy scripts
        try {
            window.isLoggedIn = !!window.Laravel && !!window.Laravel.isLoggedIn;
            window.Laravel = window.Laravel || {};
        } catch (e) {
            window.isLoggedIn = false;
        }

        // Toggle search bar
        document.querySelector('.nav-search')?.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelector('.search-bar')?.classList.toggle('hidden');
        });

        // Handle search submission
        function handleSearch() {
            const query = document.getElementById('searchInput').value;
            if(query.trim()) {
                window.location.href = '{{ route("products") }}?search=' + encodeURIComponent(query);
            }
        }

        document.getElementById('searchSubmitBtn')?.addEventListener('click', handleSearch);

        document.getElementById('searchInput')?.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                handleSearch();
            }
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

        document.addEventListener('click', function (e) {
            const filterWrapper = document.querySelector('.filter-wrapper');
            const filterPopup = document.querySelector('.filter-popup');
            const filterBtn = document.querySelector('.filter-btn');

        if (!filterWrapper.contains(e.target)) {
            filterPopup.classList.add('hidden');
        }
    });

    // Ngăn đóng khi click vào bên trong popup
    document.querySelector('.filter-popup')?.addEventListener('click', function(e) {
        e.stopPropagation();
    });
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
            // Trigger animation
            setTimeout(() => overlay.classList.add('show'), 10);

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
            bodyEl.innerHTML = escapeHtml(message);
            cancelBtn.style.display = 'none';
            okBtn.textContent = opts.okText || 'OK';
            overlay.style.display = 'flex';
            overlay.setAttribute('aria-hidden', 'false');
            // Trigger animation
            setTimeout(() => overlay.classList.add('show'), 10);

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
            overlay.classList.remove('show');
            setTimeout(() => {
                overlay.style.display = 'none';
                overlay.setAttribute('aria-hidden', 'true');
            }, 250); // Match transition duration
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
    <script>
    // User dropdown toggle (shared behavior with staff layout)
    (function() {
        const dropdownToggle = document.getElementById('userDropdownToggle');
        const dropdownMenu = document.getElementById('userDropdownMenu');
        const chevron = dropdownToggle ? dropdownToggle.querySelector('.chevron-icon') : null;

        if (!dropdownToggle || !dropdownMenu) return;

        dropdownToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdownMenu.classList.toggle('show');
            if (chevron) chevron.classList.toggle('rotate');
        });

        // Close when clicking outside
        document.addEventListener('click', function(e) {
            if (!dropdownToggle.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.classList.remove('show');
                if (chevron) chevron.classList.remove('rotate');
            }
        });

        // Close on ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                dropdownMenu.classList.remove('show');
                if (chevron) chevron.classList.remove('rotate');
            }
        });
    })();
    </script>
    
    <!-- Chatbot Script -->
    <script src="{{ asset('js/chatbot.js') }}"></script>
</body>
</html>