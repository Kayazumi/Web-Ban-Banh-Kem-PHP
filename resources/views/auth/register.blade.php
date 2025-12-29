@extends('layouts.app')

@section('title', 'Đăng ký - La Cuisine Ngọt')

@section('content')
<div class="auth-container">
    <div class="auth-form">
        <h2>Đăng ký tài khoản</h2>

        <form id="registerForm">
            @csrf
            <div class="form-group">
                <label for="username">Tên đăng nhập</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="full_name">Họ và tên</label>
                <input type="text" id="full_name" name="full_name" required>
            </div>

            <div class="form-group">
                <label for="phone">Số điện thoại</label>
                <input type="tel" id="phone" name="phone">
            </div>

            <div class="form-group">
                <label for="address">Địa chỉ</label>
                <textarea id="address" name="address" rows="3"></textarea>
            </div>

            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Xác nhận mật khẩu</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>

            <button type="submit" class="btn btn-primary btn-full">Đăng ký</button>
        </form>

        <div class="auth-links">
            <p>Đã có tài khoản? <a href="{{ route('login') }}">Đăng nhập</a></p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const registerForm = document.getElementById('registerForm');

    registerForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(registerForm);

        try {
            const response = await fetch('/api/register', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': window.Laravel.csrfToken
                },
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                alert('Đăng ký thành công! Chào mừng bạn đến với La Cuisine Ngọt.');
                // Redirect based on response
                if (data.data.redirect) {
                    window.location.href = data.data.redirect;
                } else {
                    window.location.href = '/';
                }
            } else {
                // Show validation errors
                if (data.errors) {
                    let errorMessage = 'Vui lòng kiểm tra lại:\n';
                    for (let field in data.errors) {
                        errorMessage += `- ${data.errors[field][0]}\n`;
                    }
                    alert(errorMessage);
                } else {
                    alert(data.message || 'Đăng ký thất bại');
                }
            }
        } catch (error) {
            console.error('Register error:', error);
            alert('Có lỗi xảy ra. Vui lòng thử lại.');
        }
    });
});
</script>
@endpush
