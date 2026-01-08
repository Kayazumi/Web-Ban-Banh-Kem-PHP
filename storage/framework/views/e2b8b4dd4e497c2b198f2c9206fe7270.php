<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'La Cuisine Ngọt - Nhân viên'); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Inspiration&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- CSS chung toàn app -->
    <link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>">

    <!-- CSS dành riêng cho khu vực tài khoản / nhân viên -->
    <link rel="stylesheet" href="<?php echo e(asset('css/account.css')); ?>">

    <!-- Stack cho các page con thêm CSS riêng (ví dụ orders.css, complaints.css...) -->
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>

<body class="account-page">
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <a href="<?php echo e(route('staff.profile')); ?>">La Cuisine Ngọt</a>
            </div>

            <ul class="nav-menu">
    <li>
        <a href="<?php echo e(route('staff.orders.index')); ?>" 
           class="nav-menu-link <?php echo e(request()->routeIs('staff.orders.*') ? 'active' : ''); ?>">
            ĐƠN HÀNG
        </a>
    </li>

    <li>
        <a href="<?php echo e(route('staff.complaints.index')); ?>" class="nav-menu-link">
            KHIẾU NẠI
        </a>
    </li>

    <li>
        <a href="<?php echo e(route('staff.contacts.index')); ?>" class="nav-menu-link">
            LIÊN HỆ
        </a>
    </li>
</ul>

            <div class="nav-right">
                <!-- User Dropdown -->
                <div class="nav-user-dropdown">
                    <button class="user-dropdown-toggle" id="userDropdownToggle">
                        <i class="fas fa-user-circle"></i>
                        <span class="user-name"><?php echo e(Auth::user()->full_name); ?></span>
                        <i class="fas fa-chevron-down chevron-icon"></i>
                    </button>

                    <div class="user-dropdown-menu" id="userDropdownMenu">
                        <div class="dropdown-header">
                            <div class="dropdown-user-info">
                                <i class="fas fa-user-circle user-avatar"></i>
                                <div class="user-details">
                                    <strong><?php echo e(Auth::user()->full_name); ?></strong>
                                    <span class="user-role"><?php echo e(Auth::user()->role === 'admin' ? 'Quản trị viên' : 'Nhân viên'); ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="dropdown-divider"></div>

                        <ul class="dropdown-items">
                            <li>
                                <a href="<?php echo e(route('staff.profile')); ?>" class="dropdown-item">
                                    <i class="fas fa-id-card"></i>
                                    <span>Hồ sơ của tôi</span>
                                </a>
                            </li>
                            <?php if(Auth::user()->role === 'admin'): ?>
                            <li>
                                <a href="<?php echo e(route('admin.dashboard')); ?>" class="dropdown-item">
                                    <i class="fas fa-tachometer-alt"></i>
                                    <span>Quản trị</span>
                                </a>
                            </li>
                            <?php endif; ?>
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
        <?php echo $__env->yieldContent('content'); ?>
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
    if (!confirm('Bạn có chắc muốn đăng xuất?')) return;

    // Cách lấy token chắc chắn nhất
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    fetch('<?php echo e(route("logout")); ?>', {
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
        alert('Lỗi đăng xuất, thử tải lại trang (F5) rồi đăng xuất lại nhé!');
    });
}
    </script>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>

</html><?php /**PATH D:\ProgramFilesD\DevApps\XAM_PP\htdocs\Web-Ban-Banh-Kem-PHP\resources\views/layouts/staff.blade.php ENDPATH**/ ?>