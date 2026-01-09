@extends('layouts.app')

@section('title', 'Đăng nhập - La Cuisine Ngọt')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endpush

@section('content')
<div class="form-container">
    <h2 class="form-title">Đăng nhập</h2>

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

        // Logic 1: Hiển thị/Ẩn mật khẩu bằng icon
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });

        // Logic 2: Xử lý đăng nhập AJAX
        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            submitBtn.classList.add('loading');
            submitBtn.textContent = 'Đang đăng nhập...';

            // Lấy CSRF Token an toàn từ thẻ meta hoặc input
            const tokenMeta = document.querySelector('meta[name="csrf-token"]');
            const csrfToken = tokenMeta ? tokenMeta.getAttribute('content') : document.querySelector('input[name="_token"]').value;

            const urlParams = new URLSearchParams(window.location.search);
            const redirectUrl = urlParams.get('redirect');

            const formData = {
                username: document.getElementById('username').value.trim(),
                password: passwordInput.value,
                redirect_url: redirectUrl // Pass redirect URL to backend
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
                    if (data.data.token) {
                        localStorage.setItem('api_token', data.data.token);
                    }
                    window.location.href = data.data.redirect || '/';
                } else {
                    alert(data.message || 'Đăng nhập thất bại');
                }
            } catch (error) {
                console.error('Lỗi:', error);
                alert('Có lỗi xảy ra. Vui lòng thử lại.');
            } finally {
                submitBtn.classList.remove('loading');
                submitBtn.textContent = 'Đăng nhập';
            }
        });
    });
</script>
@endpush