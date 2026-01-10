@extends('layouts.app')

@section('title', 'Đăng nhập - La Cuisine Ngọt')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endpush

@section('content')
<div class="form-container">
    <h2 class="form-title">Đăng nhập</h2>

    @if(session('status'))
        <div class="alert alert-success" style="background: #d4edda; border: 1px solid #c3e6cb; padding: 15px; border-radius: 8px; margin-bottom: 20px; color: #155724;">
            {{ session('status') }}
        </div>
    @endif

    <form id="loginForm">
        @csrf
        <div class="form-group">
            <label for="username">Tên đăng nhập hoặc Email</label>
            <input type="text" id="username" name="username" required autocomplete="username">
        </div>

        <div class="form-group" style="position: relative;">
            <label for="password">Mật khẩu</label>
            <div class="password-wrapper" style="position: relative;">
                <input type="password" id="password" name="password" required autocomplete="current-password" style="padding-right: 45px;">
                <i class="fas fa-eye" id="togglePassword" 
                   style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #666;"></i>
            </div>
        </div>

        <div style="text-align: right; margin-bottom: 20px;">
            <a href="{{ route('password.request') }}" style="color: #ff6b6b; text-decoration: none; font-size: 14px;">
                Quên mật khẩu?
            </a>
        </div>

        <button type="submit" class="btn-submit">Đăng nhập</button>
    </form>

    <div class="form-links">
        <p>Chưa có tài khoản? <a href="{{ route('register') }}">Đăng ký ngay</a></p>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const loginForm = document.getElementById('loginForm');
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');
        const submitBtn = loginForm.querySelector('.btn-submit');

        // Hiển thị/ẩn mật khẩu
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });

        // Xử lý đăng nhập AJAX
        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            submitBtn.classList.add('loading');
            submitBtn.textContent = 'Đang đăng nhập...';
            submitBtn.disabled = true;

            const tokenMeta = document.querySelector('meta[name="csrf-token"]');
            const csrfToken = tokenMeta ? tokenMeta.getAttribute('content') : document.querySelector('input[name="_token"]').value;

            const urlParams = new URLSearchParams(window.location.search);
            const redirectUrl = urlParams.get('redirect');

            const formData = {
                username: document.getElementById('username').value.trim(),
                password: passwordInput.value,
                redirect_url: redirectUrl
            };

            try {
                const response = await fetch('/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();

                if (data.success) {
                    showNotification('success', 'Đăng nhập thành công! Đang chuyển trang...');
                    
                    if (data.data.token) {
                        localStorage.setItem('api_token', data.data.token);
                    }
                    
                    setTimeout(() => {
                        window.location.href = data.data.redirect || '/';
                    }, 1000);
                } else {
                    showNotification('error', data.message || 'Tên đăng nhập hoặc mật khẩu không đúng');
                    submitBtn.classList.remove('loading');
                    submitBtn.textContent = 'Đăng nhập';
                    submitBtn.disabled = false;
                }
            } catch (error) {
                console.error('Lỗi:', error);
                showNotification('error', 'Có lỗi xảy ra. Vui lòng thử lại sau.');
                submitBtn.classList.remove('loading');
                submitBtn.textContent = 'Đăng nhập';
                submitBtn.disabled = false;
            }
        });

        function showNotification(type, message) {
            const oldNotif = document.querySelector('.login-notification');
            if (oldNotif) oldNotif.remove();

            const notif = document.createElement('div');
            notif.className = `login-notification ${type}`;
            notif.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                <span>${message}</span>
                <button class="notif-close" type="button">&times;</button>
            `;

            loginForm.insertBefore(notif, loginForm.firstChild);

            const autoHide = setTimeout(() => {
                notif.classList.add('fade-out');
                setTimeout(() => notif.remove(), 300);
            }, 5000);

            notif.querySelector('.notif-close').addEventListener('click', function() {
                clearTimeout(autoHide);
                notif.classList.add('fade-out');
                setTimeout(() => notif.remove(), 300);
            });
        }
    });
</script>
@endpush