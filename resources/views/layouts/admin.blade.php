<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - La Cuisine Ngọt')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inspiration&display=swap" rel="stylesheet">

    <!-- THAY ĐỔI TẠI ĐÂY: Load CSS thủ công -->
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    @stack('styles')
</head>

<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <h3>La Cuisine Ngọt</h3>
                <span>Admin Panel</span>
            </div>

            <nav class="sidebar-nav">
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('admin.products') }}" class="{{ request()->routeIs('admin.products*') ? 'active' : '' }}">
                    <i class="fas fa-box"></i>
                    <span>Sản phẩm</span>
                </a>

                <a href="{{ route('admin.orders') }}" class="{{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Đơn hàng</span>
                </a>

                <a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>Người dùng</span>
                </a>

                <a href="{{ route('admin.complaints') }}" class="{{ request()->routeIs('admin.complaints*') ? 'active' : '' }}">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>Khiếu nại</span>
                </a>

                <a href="{{ route('admin.promotions') }}" class="{{ request()->routeIs('admin.promotions*') ? 'active' : '' }}">
                    <i class="fas fa-tags"></i>
                    <span>Khuyến mãi</span>
                </a>

                <a href="{{ route('guest.home') }}" target="_blank">
                    <i class="fas fa-external-link-alt"></i>
                    <span>Xem website</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <!-- user info removed per design request -->
            </div>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <header class="admin-header">
                <h1>@yield('page-title', 'Dashboard')</h1>
                <div class="header-actions header-user-stack">
                    <div class="header-user-line">{{ Auth::user()->role === 'admin' ? 'Quản trị viên' : (Auth::user()->role === 'staff' ? 'Nhân viên' : 'Khách hàng') }} {{ Auth::user()->email }}</div>
                    <div style="display:flex;align-items:center;">
                        <span class="current-time" id="currentTime"></span>
                        <form action="{{ route('logout') }}" method="POST" style="display:inline;margin-left:16px;">
                        @csrf
                        <button type="submit" class="logout-btn header-logout-btn" style="display:flex;align-items:center;gap:8px;">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Đăng xuất</span>
                        </button>
                    </form>
                    </div>
                </div>
            </header>

            <div class="admin-content">
            @yield('content')
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <!-- THAY ĐỔI TẠI ĐÂY: Load JS thủ công -->
    <script src="{{ asset('js/admin.js') }}"></script>

    @stack('scripts')

    <script>
        // Update current time
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleString('vi-VN', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            document.getElementById('currentTime').textContent = timeString;
        }

        updateTime();
        setInterval(updateTime, 1000);

        // Global variables
        <?php $___laravel_user = $laravelUser ?? null; ?>
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}',
            user: <?php echo json_encode($___laravel_user, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP); ?>,
            routes: {
                api: {
                    products: '{{ url("api/admin/products") }}',
                    orders: '{{ url("api/admin/orders") }}'
                }
            }
        };
    </script>

    <!-- Global Modal CSS -->
    <style>
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
    </style>

    <!-- Global Modal HTML -->
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

    <!-- Global Modal JavaScript -->
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
            }, 250);
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