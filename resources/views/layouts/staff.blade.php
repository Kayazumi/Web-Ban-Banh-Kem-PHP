<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title') - La Cuisine Ngọt</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  @stack('styles')
</head>

<body class="staff-profile-page">
  <nav class="navbar">
    <div class="nav-container">
      <div class="nav-logo">
        <a href="{{ url('/home') }}">La Cuisine Ngọt</a>
      </div>

      <div class="nav-menu">
        <a class="nav-menu-link" href="#">Sản phẩm</a>
        <a class="nav-menu-link" href="#">Khuyến mãi</a>
        <a class="nav-menu-link" href="#">Liên hệ</a>
      </div>

      <div class="nav-right">
        <i class="fas fa-search nav-icon"></i>
        <i class="fas fa-shopping-cart nav-icon"></i>

        <div class="nav-user-icon">
          <i class="fas fa-user-circle"></i>
          <div class="user-menu hidden" id="userDropdown">
            <ul>
              <li class="user-info-name">
                <strong>{{ Auth::user()->full_name }}</strong>
                <br><small>{{ Auth::user()->role }}</small>
              </li>
              <hr>
              <li><a href="{{ route('staff.profile') }}"><i class="fas fa-id-card"></i> Hồ sơ của tôi</a></li>
              <li>
                <button type="button" onclick="logout()" class="logout-btn">
                  <i class="fas fa-sign-out-alt"></i> Đăng xuất
                </button>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </nav>

  <main>
    @yield('content')
  </main>

  <script>
    // 1. Xử lý ẩn/hiện Menu khi nhấn vào icon
    document.querySelector('.nav-user-icon').addEventListener('click', function(e) {
      e.stopPropagation();
      document.getElementById('userDropdown').classList.toggle('hidden');
    });

    // Đóng menu nếu nhấn ra ngoài vùng menu
    document.addEventListener('click', function() {
      document.getElementById('userDropdown').classList.add('hidden');
    });

    // 2. Hàm đăng xuất sử dụng Fetch API (AJAX)
    function logout() {
      if (!confirm('Bạn có chắc muốn đăng xuất?')) return;

      fetch('{{ route("logout") }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          }
        })
        .then(() => {
          window.location.href = '/login';
        })
        .catch(error => {
          console.error('Lỗi đăng xuất:', error);
          alert('Không thể đăng xuất, vui lòng thử lại!');
        });
    }
  </script>

  @stack('scripts')

</body>

</html>