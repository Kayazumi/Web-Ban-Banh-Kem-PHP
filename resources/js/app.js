// La Cuisine Ngọt - Main JavaScript

// Global functions
window.logout = function() {
    if (confirm('Bạn có chắc chắn muốn đăng xuất?')) {
        fetch('/api/logout', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': window.Laravel.csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '/';
            }
        })
        .catch(error => {
            console.error('Logout error:', error);
            // Force logout on client side
            window.location.href = '/';
        });
    }
};

// Cart count update
function updateCartCount() {
    if (!window.Laravel.user) return;

    fetch('/api/cart', {
        headers: {
            'Accept': 'application/json'
        }
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
window.formatPrice = function(price) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(price);
};

// DOM ready
document.addEventListener('DOMContentLoaded', function() {
    // Update cart count on page load
    updateCartCount();

    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });

    // Handle search functionality
    const searchInput = document.getElementById('searchInput');
    const searchSubmitBtn = document.getElementById('searchSubmitBtn');

    if (searchInput && searchSubmitBtn) {
        searchSubmitBtn.addEventListener('click', function() {
            const query = searchInput.value.trim();
            if (query) {
                window.location.href = `/products?search=${encodeURIComponent(query)}`;
            }
        });

        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchSubmitBtn.click();
            }
        });
    }

    // Handle filter functionality
    const filterBtn = document.querySelector('.filter-btn');
    const filterPopup = document.querySelector('.filter-popup');

    if (filterBtn && filterPopup) {
        filterBtn.addEventListener('click', function() {
            filterPopup.classList.toggle('hidden');
        });

        // Close filter popup when clicking outside
        document.addEventListener('click', function(e) {
            if (!filterBtn.contains(e.target) && !filterPopup.contains(e.target)) {
                filterPopup.classList.add('hidden');
            }
        });
    }
});

// AJAX helper functions
window.ajaxRequest = function(url, options = {}) {
    const defaultOptions = {
        headers: {
            'X-CSRF-TOKEN': window.Laravel.csrfToken,
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
window.showNotification = function(message, type = 'success') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `alert alert-${type}`;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        border-radius: 5px;
        color: white;
        background: ${type === 'success' ? '#28a745' : '#dc3545'};
        z-index: 9999;
        transition: opacity 0.3s;
    `;
    notification.textContent = message;

    document.body.appendChild(notification);

    // Auto remove after 3 seconds
    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
};