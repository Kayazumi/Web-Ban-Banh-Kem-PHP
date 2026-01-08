<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - La Cuisine Ngọt')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

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

                <a href="{{ route('admin.promotions') }}" class="{{ request()->routeIs('admin.promotions*') ? 'active' : '' }}">
                    <i class="fas fa-tags"></i>
                    <span>Khuyến mãi</span>
                </a>

                <a href="{{ route('home') }}" target="_blank">
                    <i class="fas fa-external-link-alt"></i>
                    <span>Xem website</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <div class="user-info">
                    <span>{{ Auth::user()->full_name }}</span>
                    <small>{{ Auth::user()->email }}</small>
                </div>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Đăng xuất</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <header class="admin-header">
                <h1>@yield('page-title', 'Dashboard')</h1>
                <div class="header-actions">
                    <span class="current-time" id="currentTime"></span>
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
</body>

</html>