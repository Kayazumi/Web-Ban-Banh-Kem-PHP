@extends('layouts.app')

@section('title', 'Đăng nhập - La Cuisine Ngọt')

@section('content')
<div class="auth-container">
    <div class="auth-form">
        <h2>Đăng nhập</h2>

        <form id="loginForm">
            @csrf
            <div class="form-group">
                <label for="username">Tên đăng nhập hoặc Email</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="btn btn-primary btn-full">Đăng nhập</button>
        </form>

        <div class="auth-links">
            <p>Chưa có tài khoản? <a href="{{ route('register') }}">Đăng ký ngay</a></p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');

    loginForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(loginForm);

        try {
            const response = await fetch('/api/login', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': window.Laravel.csrfToken
                },
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                // Redirect based on user role
                if (data.data.redirect) {
                    window.location.href = data.data.redirect;
                } else {
                    window.location.href = '/';
                }
            } else {
                alert(data.message || 'Đăng nhập thất bại');
            }
        } catch (error) {
            console.error('Login error:', error);
            alert('Có lỗi xảy ra. Vui lòng thử lại.');
        }
    });
});
</script>
@endpush
