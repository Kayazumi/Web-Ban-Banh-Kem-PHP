<?php $__env->startSection('title', 'Đăng nhập - La Cuisine Ngọt'); ?>

<?php $__env->startPush('styles'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('css/login.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="form-container">
    <h2 class="form-title">Đăng nhập</h2>

    <form id="loginForm">
        <?php echo csrf_field(); ?>
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
        <p>Chưa có tài khoản? <a href="<?php echo e(route('register')); ?>">Đăng ký ngay</a></p>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
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

            const formData = {
                username: document.getElementById('username').value.trim(),
                password: passwordInput.value
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
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Web-Ban-Banh-Kem-PHP\resources\views/auth/login.blade.php ENDPATH**/ ?>