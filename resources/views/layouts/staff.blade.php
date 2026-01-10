<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'La Cuisine Ngọt - Nhân viên')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Inspiration&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- CSS chung toàn app -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- CSS dành riêng cho khu vực tài khoản / nhân viên -->
    <link rel="stylesheet" href="{{ asset('css/account.css') }}">

    <!-- Stack cho các page con thêm CSS riêng (ví dụ orders.css, complaints.css...) -->
    @stack('styles')
</head>

<body class="account-page">
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <a href="{{ route('staff.profile') }}">La Cuisine Ngọt</a>
            </div>

            <ul class="nav-menu">
    <li>
        <a href="{{ route('staff.orders.index') }}" 
           class="nav-menu-link {{ request()->routeIs('staff.orders.*') ? 'active' : '' }}">
            ĐƠN HÀNG
        </a>
    </li>

    <li>
        <a href="{{ route('staff.complaints.index') }}" class="nav-menu-link">
            KHIẾU NẠI
        </a>
    </li>

    <li>
        <a href="{{ route('staff.contacts.index') }}" class="nav-menu-link">
            LIÊN HỆ
        </a>
    </li>
</ul>

            <div class="nav-right">
                <!-- User Dropdown -->
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
                                    <span class="user-role">{{ Auth::user()->role === 'admin' ? 'Quản trị viên' : 'Nhân viên' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="dropdown-divider"></div>

                        <ul class="dropdown-items">
                            <li>
                                <a href="{{ route('staff.profile') }}" class="dropdown-item">
                                    <i class="fas fa-id-card"></i>
                                    <span>Hồ sơ của tôi</span>
                                </a>
                            </li>
                            @if(Auth::user()->role === 'admin')
                            <li>
                                <a href="{{ route('admin.dashboard') }}" class="dropdown-item">
                                    <i class="fas fa-tachometer-alt"></i>
                                    <span>Quản trị</span>
                                </a>
                            </li>
                            @endif
                        </ul>

                        <div class="dropdown-divider"></div>

                        <ul class="dropdown-items">
                            <li>
                                <button type="button" onclick="logout()" class="dropdown-item logout-item">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <span>Đăng xuất</span>
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Scripts -->
    <script>
        // Dropdown Toggle
        const dropdownToggle = document.getElementById('userDropdownToggle');
        const dropdownMenu = document.getElementById('userDropdownMenu');
        const chevron = dropdownToggle.querySelector('.chevron-icon');

        // Toggle dropdown khi click vào button
        dropdownToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdownMenu.classList.toggle('show');
            chevron.classList.toggle('rotate');
        });

        // Đóng dropdown khi click ra ngoài
        document.addEventListener('click', function(e) {
            if (!dropdownToggle.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.classList.remove('show');
                chevron.classList.remove('rotate');
            }
        });

        // Đóng dropdown khi nhấn ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                dropdownMenu.classList.remove('show');
                chevron.classList.remove('rotate');
            }
        });

        // Logout function
        function logout() {
    showConfirm('Bạn có chắc muốn đăng xuất?', function() {
        // Cách lấy token chắc chắn nhất
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        fetch('{{ route("logout") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            window.location.href = '/login';
        })
        .catch(err => {
            console.error(err);
            showAlert('Lỗi đăng xuất, thử tải lại trang (F5) rồi đăng xuất lại nhé!');
        });
    });
}
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

    @stack('scripts')
</body>

</html>