<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - La Cuisine Ngọt</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Inspiration&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
    @stack('styles')
</head>

<body class="staff-profile-page">
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <a href="{{ route('home') }}">La Cuisine Ngọt</a>
            </div>

            <ul class="nav-menu">
                <li><a href="#" class="nav-menu-link">SẢN PHẨM</a></li>
                <li><a href="#" class="nav-menu-link">KHUYẾN MÃI</a></li>
                <li><a href="#" class="nav-menu-link">LIÊN HỆ</a></li>
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
            if (!confirm('Bạn có chắc muốn đăng xuất?')) return;

            fetch('{{ route("logout") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = '/login';
                } else {
                    alert('Đăng xuất thất bại. Vui lòng thử lại.');
                }
            })
            .catch(error => {
                console.error('Lỗi đăng xuất:', error);
                alert('Không thể đăng xuất. Vui lòng thử lại!');
            });
        }
    </script>

    @stack('scripts')
</body>

</html>