<?php $__env->startSection('title', 'Đăng ký - La Cuisine Ngọt'); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startPush('styles'); ?>
<style>
/* Tăng kích thước form đăng ký, căn giữa, thêm shadow và padding giống hình mẫu */
.register-page {
    display: flex;
    justify-content: center;
    align-items: flex-start;
    padding: 4rem 1rem;
}
.register-card {
    width: 720px; /* to hơn so với form cũ */
    max-width: 95%;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    padding: 36px;
    box-sizing: border-box;
}
.register-card h2 {
    text-align: center;
    color: #1f4d3a;
    margin-bottom: 18px;
    font-size: 1.5rem;
}
.register-columns {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}
.form-group { margin-bottom: 12px; }
.form-group.full { grid-column: 1 / -1; }
.btn-full { width: 100%; padding: 12px 18px; font-weight: 600; }
.auth-links { text-align: center; margin-top: 12px; }

/* Style register button to match site and remove white-looking background */
.btn-full {
    background: linear-gradient(135deg, #395542, #4a7c59);
    color: #fff;
    border: none;
    box-shadow: 0 8px 20px rgba(57,85,66,0.25);
    transition: transform .18s ease, box-shadow .18s ease;
}
.btn-full:hover { transform: translateY(-3px); box-shadow: 0 14px 30px rgba(57,85,66,0.32); }
.btn-full:active { transform: translateY(0); }

/* Modal styles */
.app-modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.45);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}
.app-modal {
    background: #0f1b1a;
    color: #e8fff9;
    border-radius: 10px;
    max-width: 520px;
    width: 90%;
    padding: 20px 22px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.6);
    font-family: Inter, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;
}
.app-modal .modal-title {
    font-weight: 700;
    margin-bottom: 8px;
    font-size: 1.05rem;
}
.app-modal .modal-body {
    margin: 8px 0 14px;
    line-height: 1.4;
}
.app-modal .modal-actions {
    text-align: right;
}
.app-modal .modal-btn {
    background: #7fe3d0;
    color: #05332d;
    border: none;
    padding: 8px 14px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
}
.app-modal .modal-btn.secondary {
    background: transparent;
    color: #cdebe5;
    margin-right: 8px;
    border: 1px solid rgba(255,255,255,0.06);
}
</style>
<?php $__env->stopPush(); ?>

<div class="register-page">
    <div class="register-card">
        <h2>Đăng ký tài khoản</h2>

        <form id="registerForm">
            <?php echo csrf_field(); ?>
            <div class="register-columns">
                <div class="form-group">
                    <label for="first_name">Họ</label>
                    <input type="text" id="first_name" name="first_name" required>
                </div>
            <div class="form-group">
                    <label for="last_name">Tên</label>
                    <input type="text" id="last_name" name="last_name" required>
            </div>

                <div class="form-group full">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="phone">Số điện thoại</label>
                <input type="tel" id="phone" name="phone">
            </div>
            <div class="form-group">
                    <label for="username">Tên đăng nhập</label>
                    <input type="text" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="password_confirmation">Xác nhận mật khẩu</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>

                <div class="form-group full">
                    <label for="address">Địa chỉ</label>
                    <textarea id="address" name="address" rows="3"></textarea>
                </div>
            </div>

            <div style="margin-top:12px;">
            <button type="submit" class="btn btn-primary btn-full">Đăng ký</button>
            </div>
        </form>

        <!-- Centered modal for messages -->
        <div id="appModalOverlay" class="app-modal-overlay" role="dialog" aria-modal="true" aria-hidden="true" style="display:none;">
            <div class="app-modal" id="appModal">
                <div class="modal-title">Thông báo</div>
                <div class="modal-body" id="appModalBody">Nội dung</div>
                <div class="modal-actions">
                    <button id="appModalOk" class="modal-btn">OK</button>
                </div>
            </div>
        </div>

        <div class="auth-links">
            <p>Đã có tài khoản? <a href="<?php echo e(route('login')); ?>">Đăng nhập</a></p>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
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
                // Show modal success and redirect to login
                showModal(data.message || 'Tài khoản của bạn đã được tạo!', function() {
                    if (data.data && data.data.redirect) {
                    window.location.href = data.data.redirect;
                } else {
                        window.location.href = '/login';
                }
                });
            } else {
                // Show validation errors in modal
                if (data.errors) {
                    let html = '<ul style=\"margin:0;padding-left:1.2rem;\">';
                    for (let field in data.errors) {
                        html += `<li>${escapeHtml(data.errors[field][0])}</li>`;
                    }
                    html += '</ul>';
                    showModal('<strong>Vui lòng kiểm tra lại:</strong>' + html);
                } else {
                    showModal(escapeHtml(data.message || 'Đăng ký thất bại'));
                }
            }
        } catch (error) {
            console.error('Register error:', error);
            showModal('Có lỗi xảy ra. Vui lòng thử lại.');
        }
    });

    // Modal OK button handler (delegated)
    document.getElementById('appModalOk').addEventListener('click', function() {
        const overlay = document.getElementById('appModalOverlay');
        overlay.style.display = 'none';
        overlay.setAttribute('aria-hidden', 'true');
    });
});

function escapeHtml(unsafe) {
    return String(unsafe)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#039;');
}

function showModal(message, onOk) {
    const overlay = document.getElementById('appModalOverlay');
    const body = document.getElementById('appModalBody');
    const okBtn = document.getElementById('appModalOk');

    body.innerHTML = message;
    overlay.style.display = 'flex';
    overlay.setAttribute('aria-hidden', 'false');

    // If onOk provided, override OK button to call it
    const handler = function() {
        overlay.style.display = 'none';
        overlay.setAttribute('aria-hidden', 'true');
        okBtn.removeEventListener('click', handler);
        if (typeof onOk === 'function') onOk();
    };

    // Remove previous handler(s) then attach new one
    okBtn.removeEventListener('click', handler);
    okBtn.addEventListener('click', handler);

    // Auto-close and call onOk after 2.5s if provided
    if (typeof onOk === 'function') {
        setTimeout(() => {
            if (overlay.style.display !== 'none') handler();
        }, 2500);
    }
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp01\htdocs\Web-Ban-Banh-Kem-PHP\resources\views/auth/register.blade.php ENDPATH**/ ?>