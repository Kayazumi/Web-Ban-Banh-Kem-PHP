// public/js/app.js
// La Cuisine Ngọt - Main JavaScript

// Global functions
// public/js/customer.js

window.logout = function () {
    showConfirm('Bạn có chắc muốn đăng xuất?', function () {
        // Hiệu ứng Loading
        const logoutBtn = document.getElementById('logoutBtn');
        if (logoutBtn) {
            logoutBtn.disabled = true;
            logoutBtn.textContent = 'Đang xử lý...';
        }


        fetch(window.Laravel.routes.logout, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.Laravel.csrfToken,
                'Accept': 'application/json'
            }
        })
            .then(response => {
                if (response.redirected) {
                    window.location.href = response.url;
                    return;
                }
                return response.json();
            })
            .then(data => {
                if (data && data.success) {
                    window.location.href = '/';
                }
            })
            .catch(error => {
                console.error('Lỗi:', error);
                window.location.href = '/login';
            });
    }); // Close showConfirm callback
};

// Cart count update
function updateCartCount() {
    // Check if user is logged in (you can set this in blade template)
    if (!window.isLoggedIn) return;

    fetch('/api/cart', {
        headers: {
            'Accept': 'application/json'
        },
        credentials: 'same-origin'
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const cartCount = document.getElementById('cartCount');
                if (cartCount) {
                    cartCount.textContent = data.data.total_items || 0;
                }
            }
        })
        .catch(error => console.error('Error updating cart count:', error));
}

// Format price utility
window.formatPrice = function (price) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(price);
};

// DOM ready
document.addEventListener('DOMContentLoaded', function () {
    // updateCartCount(); // Removed to prevent flicker - handled by server-side View Composer

    // Auto-hide alerts
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });

    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const searchSubmitBtn = document.getElementById('searchSubmitBtn');

    if (searchInput && searchSubmitBtn) {
        searchSubmitBtn.addEventListener('click', function () {
            const query = searchInput.value.trim();
            if (query) {
                window.location.href = `/products?search=${encodeURIComponent(query)}`;
            }
        });

        searchInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                searchSubmitBtn.click();
            }
        });
    }

    // Filter functionality
    const filterBtn = document.querySelector('.filter-btn');
    const filterPopup = document.querySelector('.filter-popup');

    if (filterBtn && filterPopup) {
        filterBtn.addEventListener('click', function () {
            filterPopup.classList.toggle('hidden');
        });

        document.addEventListener('click', function (e) {
            if (!filterBtn.contains(e.target) && !filterPopup.contains(e.target)) {
                filterPopup.classList.add('hidden');
            }
        });
    }
});

// AJAX helper
window.ajaxRequest = function (url, options = {}) {
    const defaultOptions = {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    };

    return fetch(url, { ...defaultOptions, ...options })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        });
};

// Show notification
window.showNotification = function (message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type}`;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        border-radius: 5px;
        color: white;
        background: ${type === 'success' ? '#324F29' : '#dc3545'};
        z-index: 9999;
        transition: opacity 0.3s;
    `;
    notification.textContent = message;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
};