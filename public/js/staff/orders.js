// ========================================================================
// QUẢN LÝ ĐƠN HÀNG - NHÂN VIÊN
// ========================================================================

let ordersData = [];
let filteredOrders = [];

// ========================================================================
// 1. KHỞI TẠO
// ========================================================================
document.addEventListener('DOMContentLoaded', function() {
    loadOrders();
    initEventListeners();
});

// ========================================================================
// 2. TẢI DANH SÁCH ĐƠN HÀNG
// ========================================================================
async function loadOrders() {
    try {
        const response = await fetch('/staff/api/orders', {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });

        if (!response.ok) throw new Error('Không thể tải danh sách đơn hàng');

        const data = await response.json();
        ordersData = data.orders || [];
        filteredOrders = [...ordersData];
        renderOrders();
    } catch (error) {
        console.error('Lỗi:', error);
        showError('Không thể tải danh sách đơn hàng. Vui lòng thử lại!');
    }
}

// ========================================================================
// 3. RENDER BẢNG ĐƠN HÀNG
// ========================================================================
function renderOrders() {
    const tbody = document.getElementById('orders-table-body');

    if (filteredOrders.length === 0) {
        tbody.innerHTML = `
            <tr class="no-orders">
                <td colspan="7">
                    <i class="fas fa-inbox"></i>
                    <p>Không tìm thấy đơn hàng nào</p>
                </td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = filteredOrders.map(order => `
        <tr data-order-id="${order.OrderID}">
            <td><span class="order-code">${order.OrderID}</span></td>
            <td>${order.customer_name || 'N/A'}</td>
            <td>${formatDate(order.OrderDate)}</td>
            <td>${formatCurrency(order.TotalAmount)}</td>
            <td>
                <div class="status-select-wrapper">
                    <select class="status-select ${order.Status}" 
                            data-order-id="${order.OrderID}"
                            onchange="updateOrderStatus(this)">
                        ${getStatusOptions(order.Status)}
                    </select>
                </div>
            </td>
            <td>
                <div class="note-wrapper">
                    <input type="text" 
                           class="note-input" 
                           data-order-id="${order.OrderID}"
                           value="${order.note || ''}"
                           placeholder="Thêm ghi chú...">
                    <button class="save-note-btn" onclick="saveNote('${order.OrderID}')">
                        <i class="fas fa-check"></i>
                    </button>
                </div>
            </td>
            <td>
                <button class="view-detail-btn" onclick="showOrderDetail('${order.OrderID}')">
                    <i class="fas fa-eye"></i>
                </button>
            </td>
        </tr>
    `).join('');
}

// ========================================================================
// 4. TRẠNG THÁI OPTIONS
// ========================================================================
function getStatusOptions(currentStatus) {
    const statuses = {
        'pending': 'Chờ xử lý',
        'order_received': 'Đã nhận đơn',
        'preparing': 'Đang chuẩn bị',
        'delivering': 'Đang giao',
        'delivery_successful': 'Giao hàng thành công',
        'delivery_failed': 'Giao hàng thất bại',
        'cancelled': 'Đã hủy'
    };

    return Object.entries(statuses).map(([value, label]) => 
        `<option value="${value}" ${value === currentStatus ? 'selected' : ''}>${label}</option>`
    ).join('');
}

// ========================================================================
// 5. CẬP NHẬT TRẠNG THÁI
// ========================================================================
async function updateOrderStatus(selectElement) {
    const orderId = selectElement.dataset.orderId;
    const newStatus = selectElement.value;

    if (!confirm('Bạn có chắc muốn cập nhật trạng thái đơn hàng này?')) {
        // Revert về trạng thái cũ
        const order = ordersData.find(o => o.OrderID === orderId);
        if (order) selectElement.value = order.Status;
        return;
    }

    try {
        const response = await fetch(`/staff/api/orders/${orderId}/status`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status: newStatus })
        });

        if (!response.ok) throw new Error('Không thể cập nhật trạng thái');

        const data = await response.json();
        
        // Cập nhật data
        const orderIndex = ordersData.findIndex(o => o.OrderID === orderId);
        if (orderIndex !== -1) {
            ordersData[orderIndex].Status = newStatus;
        }

        // Cập nhật class của select
        selectElement.className = `status-select ${newStatus}`;

        showSuccess('Cập nhật trạng thái thành công!');
    } catch (error) {
        console.error('Lỗi:', error);
        showError('Không thể cập nhật trạng thái. Vui lòng thử lại!');
        
        // Revert về trạng thái cũ
        const order = ordersData.find(o => o.OrderID === orderId);
        if (order) {
            selectElement.value = order.Status;
            selectElement.className = `status-select ${order.Status}`;
        }
    }
}

// ========================================================================
// 6. LƯU GHI CHÚ
// ========================================================================
async function saveNote(orderId) {
    const noteInput = document.querySelector(`.note-input[data-order-id="${orderId}"]`);
    const note = noteInput.value.trim();

    try {
        const response = await fetch(`/staff/api/orders/${orderId}/note`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ note: note })
        });

        if (!response.ok) throw new Error('Không thể lưu ghi chú');

        // Cập nhật data
        const orderIndex = ordersData.findIndex(o => o.OrderID === orderId);
        if (orderIndex !== -1) {
            ordersData[orderIndex].note = note;
        }

        showSuccess('Ghi chú đã được lưu!');
    } catch (error) {
        console.error('Lỗi:', error);
        showError('Không thể lưu ghi chú. Vui lòng thử lại!');
    }
}

// ========================================================================
// 7. HIỂN THỊ CHI TIẾT ĐƠN HÀNG
// ========================================================================
async function showOrderDetail(orderId) {
    const modal = document.getElementById('orderDetailModal');
    const modalBody = document.getElementById('modalBody');

    // Hiển thị loading
    modalBody.innerHTML = `
        <div style="text-align: center; padding: 40px;">
            <div class="loading-spinner"></div>
            <p style="margin-top: 15px; color: #666;">Đang tải chi tiết...</p>
        </div>
    `;
    modal.classList.add('show');

    try {
        const response = await fetch(`/staff/api/orders/${orderId}`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });

        if (!response.ok) throw new Error('Không thể tải chi tiết đơn hàng');

        const data = await response.json();
        const order = data.order;

        modalBody.innerHTML = `
            <div class="detail-section">
                <h3>Thông tin đơn hàng</h3>
                <div class="detail-row">
                    <span class="detail-label">Mã đơn hàng</span>
                    <span class="detail-value"><strong>${order.OrderID}</strong></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Tên khách hàng</span>
                    <span class="detail-value">${order.customer_name || 'N/A'}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Số điện thoại</span>
                    <span class="detail-value">${order.Phone || 'N/A'}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Địa chỉ</span>
                    <span class="detail-value">${order.Address || 'N/A'}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Ngày đặt</span>
                    <span class="detail-value">${formatDateTime(order.OrderDate)}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Tổng tiền</span>
                    <span class="detail-value highlight">${formatCurrency(order.TotalAmount)}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Hình thức thanh toán</span>
                    <span class="detail-value">${order.payment_method || 'N/A'}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Trạng thái hiện tại</span>
                    <span class="detail-value">
                        <span class="status-badge ${order.Status}">${getStatusLabel(order.Status)}</span>
                    </span>
                </div>
            </div>

            ${order.items && order.items.length > 0 ? `
            <div class="detail-section">
                <h3>Sản phẩm</h3>
                <ul class="products-list">
                    ${order.items.map(item => `
                        <li class="product-item">
                            <div>
                                <span class="product-name">${item.product_name}</span>
                                <span class="product-quantity">x${item.Quantity}</span>
                            </div>
                            <span class="product-price">${formatCurrency(item.Price)}</span>
                        </li>
                    `).join('')}
                </ul>
            </div>
            ` : ''}

            ${order.note ? `
            <div class="detail-section">
                <h3>Ghi chú</h3>
                <p style="color: #666; line-height: 1.6;">${order.note}</p>
            </div>
            ` : ''}
        `;
    } catch (error) {
        console.error('Lỗi:', error);
        modalBody.innerHTML = `
            <div style="text-align: center; padding: 40px; color: #e74c3c;">
                <i class="fas fa-exclamation-circle" style="font-size: 3rem; margin-bottom: 15px;"></i>
                <p>Không thể tải chi tiết đơn hàng. Vui lòng thử lại!</p>
            </div>
        `;
    }
}

function closeOrderModal() {
    document.getElementById('orderDetailModal').classList.remove('show');
}

// Đóng modal khi click bên ngoài
document.addEventListener('click', function(e) {
    const modal = document.getElementById('orderDetailModal');
    if (e.target === modal) {
        closeOrderModal();
    }
});

// ========================================================================
// 8. TÌM KIẾM VÀ LỌC
// ========================================================================
function initEventListeners() {
    // Tìm kiếm
    const searchInput = document.getElementById('search-input');
    searchInput.addEventListener('input', debounce(applyFilters, 300));

    // Checkboxes lọc
    document.querySelectorAll('.filter-checkbox input').forEach(checkbox => {
        checkbox.addEventListener('change', applyFilters);
    });
}

function applyFilters() {
    const searchTerm = document.getElementById('search-input').value.toLowerCase();
    const selectedStatuses = Array.from(document.querySelectorAll('.filter-checkbox input:checked'))
        .map(cb => cb.value);

    filteredOrders = ordersData.filter(order => {
        // Lọc theo search
        const matchesSearch = !searchTerm || 
            order.OrderID.toLowerCase().includes(searchTerm) ||
            (order.customer_name && order.customer_name.toLowerCase().includes(searchTerm));

        // Lọc theo trạng thái
        const matchesStatus = selectedStatuses.length === 0 || 
            selectedStatuses.includes(order.Status);

        return matchesSearch && matchesStatus;
    });

    renderOrders();
}

// ========================================================================
// 9. HELPER FUNCTIONS
// ========================================================================
function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(amount);
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('vi-VN');
}

function formatDateTime(dateString) {
    const date = new Date(dateString);
    return date.toLocaleString('vi-VN', {
        hour: '2-digit',
        minute: '2-digit',
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
}

function getStatusLabel(status) {
    const labels = {
        'pending': 'Chờ xử lý',
        'order_received': 'Đã nhận đơn',
        'preparing': 'Đang chuẩn bị',
        'delivering': 'Đang giao',
        'delivery_successful': 'Giao hàng thành công',
        'delivery_failed': 'Giao hàng thất bại',
        'cancelled': 'Đã hủy'
    };
    return labels[status] || status;
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// ========================================================================
// 10. THÔNG BÁO
// ========================================================================
function showSuccess(message) {
    showNotification(message, 'success');
}

function showError(message) {
    showNotification(message, 'error');
}

function showNotification(message, type) {
    // Tạo notification element
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
        <span>${message}</span>
    `;

    // Thêm styles nếu chưa có
    if (!document.getElementById('notification-styles')) {
        const style = document.createElement('style');
        style.id = 'notification-styles';
        style.textContent = `
            .notification {
                position: fixed;
                top: 100px;
                right: 20px;
                background: white;
                padding: 15px 20px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                display: flex;
                align-items: center;
                gap: 12px;
                z-index: 10000;
                animation: slideIn 0.3s ease;
            }
            .notification.success {
                border-left: 4px solid #27ae60;
            }
            .notification.success i {
                color: #27ae60;
                font-size: 1.2rem;
            }
            .notification.error {
                border-left: 4px solid #e74c3c;
            }
            .notification.error i {
                color: #e74c3c;
                font-size: 1.2rem;
            }
            @keyframes slideIn {
                from {
                    transform: translateX(400px);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
        `;
        document.head.appendChild(style);
    }

    document.body.appendChild(notification);

    // Tự động xóa sau 3 giây
    setTimeout(() => {
        notification.style.animation = 'slideIn 0.3s ease reverse';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}