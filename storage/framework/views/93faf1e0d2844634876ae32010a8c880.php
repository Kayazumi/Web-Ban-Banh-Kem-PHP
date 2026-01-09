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
                    // Đăng nhập thành công
                    showNotification('success', 'Đăng nhập thành công! Đang chuyển trang...');
                    
                    if (data.data.token) {
                        localStorage.setItem('api_token', data.data.token);
                    }
                    
                    setTimeout(() => {
                        window.location.href = data.data.redirect || '/';
                    }, 1000);
                } else {
                    // Đăng nhập thất bại
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

        // Hàm hiển thị thông báo đẹp
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

    // Tự động ẩn sau 5 giây
    const autoHide = setTimeout(() => {
        notif.classList.add('fade-out');
        setTimeout(() => notif.remove(), 300);
    }, 5000);

    // Nút đóng thủ công
    notif.querySelector('.notif-close').addEventListener('click', function() {
        clearTimeout(autoHide);
        notif.classList.add('fade-out');
        setTimeout(() => notif.remove(), 300);
    });
}
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\ProgramFilesD\DevApps\XAM_PP\htdocs\Web-Ban-Banh-Kem-PHP\resources\views/auth/login.blade.php ENDPATH**/ ?>