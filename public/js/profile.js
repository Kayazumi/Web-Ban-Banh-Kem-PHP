// Profile Page JavaScript - La Cuisine Ngọt

document.addEventListener('DOMContentLoaded', function () {
    // Phát hiện role từ meta tag hoặc URL
    const userRole = document.querySelector('meta[name="user-role"]')?.content || 
                     (window.location.pathname.includes('/staff/') ? 'staff' : 
                      window.location.pathname.includes('/admin/') ? 'admin' : 'customer');

    console.log('Detected user role:', userRole); // Debug

    // ===== TAB SWITCHING =====
    const tabLinks = document.querySelectorAll('.tab-link');
    const tabContents = document.querySelectorAll('.tab-content');

    tabLinks.forEach(link => {
        link.addEventListener('click', function () {
            const targetTab = this.getAttribute('data-tab');

            // Remove active class from all tabs and contents
            tabLinks.forEach(l => l.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));

            // Add active class to clicked tab and corresponding content
            this.classList.add('active');
            document.getElementById(targetTab).classList.add('active');
        });
    });

    // ===== UPDATE PROFILE INFO FORM =====
    const infoForm = document.getElementById('infoForm');
    if (infoForm) {
        infoForm.addEventListener('submit', async function (e) {
            e.preventDefault();

            const submitBtn = this.querySelector('.save-btn');
            const originalText = submitBtn.textContent;

            // Disable button and show loading
            submitBtn.disabled = true;
            submitBtn.textContent = 'Đang lưu...';

            const formData = new FormData(this);

            // Xác định API endpoint dựa trên role
            const apiUrl = userRole === 'staff' 
                ? '/staff/api/profile' 
                : '/api/user/profile';

            console.log('Calling API:', apiUrl); // Debug

            try {
                const response = await fetch(apiUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                console.log('Response status:', response.status); // Debug

                // Kiểm tra Content-Type
                const contentType = response.headers.get("content-type");
                if (contentType && contentType.indexOf("application/json") === -1) {
                    if (response.ok) {
                        showAlert('success', 'Cập nhật thông tin thành công!');
                        setTimeout(() => window.location.reload(), 1000);
                        return;
                    }
                    throw new Error("Phản hồi từ máy chủ không hợp lệ (HTML).");
                }

                const data = await response.json();
                console.log('Response data:', data); // Debug

                if (data.success) {
                    showAlert('success', 'Cập nhật thông tin thành công!');

                    // Update displayed name if changed
                    const newName = formData.get('full_name');
                    if (newName) {
                        // Cập nhật tất cả các element hiển thị tên
                        const nameDisplays = ['userNameDisplay', 'staffNameDisplay'];
                        nameDisplays.forEach(id => {
                            const el = document.getElementById(id);
                            if (el) el.textContent = newName;
                        });
                        
                        const userNameElements = document.querySelectorAll('.user-name');
                        userNameElements.forEach(el => el.textContent = newName);
                    }
                } else {
                    showAlert('error', data.message || 'Có lỗi xảy ra. Vui lòng thử lại.');
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('error', 'Không thể kết nối đến máy chủ. Vui lòng thử lại.');
            } finally {
                // Re-enable button
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        });
    }

    // ===== CHANGE PASSWORD FORM =====
    const passwordForm = document.getElementById('passwordForm');
    if (passwordForm) {
        passwordForm.addEventListener('submit', async function (e) {
            e.preventDefault();

            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;

            // Validate password match
            if (newPassword !== confirmPassword) {
                showAlert('error', 'Mật khẩu xác nhận không khớp!');
                return;
            }

            // Validate password length
            if (newPassword.length < 6) {
                showAlert('error', 'Mật khẩu phải có ít nhất 6 ký tự!');
                return;
            }

            const submitBtn = this.querySelector('.save-btn');
            const originalText = submitBtn.textContent;

            // Disable button and show loading
            submitBtn.disabled = true;
            submitBtn.textContent = 'Đang đổi...';

            const formData = new FormData(this);

            // Xác định API endpoint dựa trên role
            const apiUrl = userRole === 'staff' 
                ? '/staff/api/password' 
                : '/api/user/password';

            console.log('Calling password API:', apiUrl); // Debug

            try {
                const response = await fetch(apiUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                const contentType = response.headers.get("content-type");
                if (contentType && contentType.indexOf("application/json") === -1) {
                    if (response.ok) {
                        showAlert('success', 'Đổi mật khẩu thành công!');
                        this.reset();
                        return;
                    }
                    throw new Error("Phản hồi từ máy chủ không hợp lệ (HTML).");
                }

                const data = await response.json();

                if (data.success) {
                    showAlert('success', 'Đổi mật khẩu thành công!');
                    this.reset();
                } else {
                    showAlert('error', data.message || 'Mật khẩu hiện tại không đúng.');
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('error', 'Không thể kết nối đến máy chủ. Vui lòng thử lại.');
            } finally {
                // Re-enable button
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        });
    }

    // ===== SHOW ALERT FUNCTION =====
    function showAlert(type, message) {
        // Remove existing alerts
        const existingAlerts = document.querySelectorAll('.custom-alert');
        existingAlerts.forEach(alert => alert.remove());

        // Create new alert
        const alert = document.createElement('div');
        alert.className = `custom-alert alert-${type}`;
        alert.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            <span>${message}</span>
            <button class="alert-close">&times;</button>
        `;

        // Add to body
        document.body.appendChild(alert);

        // Show alert
        setTimeout(() => alert.classList.add('show'), 10);

        // Auto hide after 5 seconds
        const hideTimeout = setTimeout(() => {
            alert.classList.remove('show');
            setTimeout(() => alert.remove(), 300);
        }, 5000);

        // Close button
        alert.querySelector('.alert-close').addEventListener('click', function () {
            clearTimeout(hideTimeout);
            alert.classList.remove('show');
            setTimeout(() => alert.remove(), 300);
        });
    }
});