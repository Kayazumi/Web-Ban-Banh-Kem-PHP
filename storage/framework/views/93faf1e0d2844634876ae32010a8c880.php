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

        <div class="form-group">
            <label for="password">Mật khẩu</label>
            <input type="password" id="password" name="password" required autocomplete="current-password">
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
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\ProgramFilesD\DevApps\XAM_PP\htdocs\Web-Ban-Banh-Kem-PHP\resources\views/auth/login.blade.php ENDPATH**/ ?>