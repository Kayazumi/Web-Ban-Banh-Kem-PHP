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

        <div class="form-group">
            <label for="password">Mật khẩu</label>
            <input type="password" id="password" name="password" required autocomplete="current-password">
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
        const submitBtn = loginForm.querySelector('.btn-submit');

        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            // Hiệu ứng loading
            submitBtn.classList.add('loading');
            submitBtn.textContent = 'Đang đăng nhập...';

            const csrfToken = document.querySelector('input[name="_token"]').value;

            const formData = {
                username: document.getElementById('username').value.trim(),
                password: document.getElementById('password').value
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
                    // Lưu token vào localStorage nếu có
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