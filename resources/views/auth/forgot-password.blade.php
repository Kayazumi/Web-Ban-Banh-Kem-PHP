@extends('layouts.app')

@section('title', 'Quên mật khẩu - La Cuisine Ngọt')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endpush

@section('content')
<div class="form-container">
    <h2 class="form-title">Quên mật khẩu</h2>
    <p style="text-align: center; color: #666; margin-bottom: 20px;">
        Nhập địa chỉ email của bạn, chúng tôi sẽ gửi link đặt lại mật khẩu
    </p>

    <form id="forgotPasswordForm">
        @csrf
        <div class="form-group">
            <label for="email">Địa chỉ Email</label>
            <input type="email" id="email" name="email" required autocomplete="email" 
                   placeholder="example@email.com">
        </div>

        <button type="submit" class="btn-submit">Gửi link đặt lại mật khẩu</button>
    </form>

    <div class="form-links">
        <p>Đã nhớ mật khẩu? <a href="{{ route('login') }}">Đăng nhập</a></p>
        <p>Chưa có tài khoản? <a href="{{ route('register') }}">Đăng ký ngay</a></p>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('forgotPasswordForm');
        const submitBtn = form.querySelector('.btn-submit');
        const emailInput = document.getElementById('email');

        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            // Disable button
            submitBtn.classList.add('loading');
            submitBtn.textContent = 'Đang gửi...';
            submitBtn.disabled = true;

            const tokenMeta = document.querySelector('meta[name="csrf-token"]');
            const csrfToken = tokenMeta ? tokenMeta.getAttribute('content') : 
                             document.querySelector('input[name="_token"]').value;

            const formData = {
                email: emailInput.value.trim()
            };

            try {
                const response = await fetch('/forgot-password', {
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
                    showNotification('success', data.message);
                    // Clear form
                    emailInput.value = '';
                    
                    // Redirect to login after 3 seconds
                    setTimeout(() => {
                        window.location.href = "{{ route('login') }}";
                    }, 3000);
                } else {
                    showNotification('error', data.message || 'Có lỗi xảy ra. Vui lòng thử lại.');
                    submitBtn.classList.remove('loading');
                    submitBtn.textContent = 'Gửi link đặt lại mật khẩu';
                    submitBtn.disabled = false;
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('error', 'Có lỗi xảy ra. Vui lòng thử lại sau.');
                submitBtn.classList.remove('loading');
                submitBtn.textContent = 'Gửi link đặt lại mật khẩu';
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

            form.insertBefore(notif, form.firstChild);

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