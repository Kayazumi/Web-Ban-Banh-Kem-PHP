// ========================================================================
// QUẢN LÝ ĐƠN HÀNG - NHÂN VIÊN (CÓ PHÂN TRANG THEO YÊU CẦU MỚI)
// ========================================================================

let ordersData = [];
let filteredOrders = [];
let currentPage = 1;
const itemsPerPage = 10;  // 10 đơn mỗi trang

// ========================================================================
// 1. KHỞI TẠO
// ========================================================================
document.addEventListener('DOMContentLoaded', function () {
    loadOrders();
    initEventListeners();
});

// ========================================================================
// 2. TẢI DANH SÁCH ĐƠN HÀNG
// ========================================================================
async function loadOrders(resetPage = true) {
    if (resetPage) currentPage = 1;

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
        applyFiltersAndPaginate();
    } catch (error) {
        console.error('Lỗi:', error);
        showError('Không thể tải danh sách đơn hàng. Vui lòng thử lại!');
    }
}

// ========================================================================
// ÁP DỤNG FILTER + PHÂN TRANG
// ========================================================================
function applyFiltersAndPaginate() {
    const searchTerm = document.getElementById('search-input')?.value.toLowerCase() || '';
    const selectedStatuses = Array.from(document.querySelectorAll('.filter-checkbox input:checked'))
        .map(cb => cb.value);

    let temp = ordersData.filter(order => {
        const matchesSearch = !searchTerm ||
            order.OrderID.toLowerCase().includes(searchTerm) ||
            (order.customer_name && order.customer_name.toLowerCase().includes(searchTerm));

        const matchesStatus = selectedStatuses.length === 0 ||
            selectedStatuses.includes(order.Status);

        return matchesSearch && matchesStatus;
    });

    const totalItems = temp.length;
    const totalPages = Math.ceil(totalItems / itemsPerPage);

    const start = (currentPage - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    filteredOrders = temp.slice(start, end);

    renderOrders();
    renderPagination(totalPages, totalItems);
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
                    <select class="status-select-clean" 
                            data-order-id="${order.OrderID}"
                            onchange="updateOrderStatus(this)">
                        ${getStatusOptions(order.Status)}
                    </select>
                    <div class="status-display ${order.Status}">
                        <span class="status-text">${getStatusLabel(order.Status)}</span>
                        <i class="fas fa-chevron-down dropdown-arrow"></i>
                    </div>
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
// 4. PHÂN TRANG - RENDER NÚT TRANG (THEO YÊU CẦU MỚI)
// ========================================================================
function renderPagination(totalPages, totalItems) {
    const oldPagination = document.querySelector('.pagination-controls');
    if (oldPagination) oldPagination.remove();

    if (totalPages <= 1) return;

    let html = `
        <div class="pagination-controls">
            <div class="pagination-info">
                Hiển thị ${(currentPage - 1) * itemsPerPage + 1} - 
                ${Math.min(currentPage * itemsPerPage, totalItems)} 
                của ${totalItems} đơn hàng
            </div>
            <div class="pagination-buttons">
    `;

    // Nút Trước
    if (currentPage > 1) {
        html += `<button class="page-btn" onclick="changePage(${currentPage - 1})">&laquo; Trước</button>`;
    }

    // Nếu ≤ 10 trang → hiện hết
    if (totalPages <= 10) {
        for (let i = 1; i <= totalPages; i++) {
            if (i === currentPage) {
                html += `<strong class="page-btn active">${i}</strong>`;
            } else {
                html += `<button class="page-btn" onclick="changePage(${i})">${i}</button>`;
            }
        }
    } else {
        // > 10 trang → hiện 1 2 3 ... trang cuối + ô nhảy trang
        for (let i = 1; i <= 3; i++) {
            if (i === currentPage) {
                html += `<strong class="page-btn active">${i}</strong>`;
            } else {
                html += `<button class="page-btn" onclick="changePage(${i})">${i}</button>`;
            }
        }

        html += `<span class="page-dots">...</span>`;

        if (currentPage === totalPages) {
            html += `<strong class="page-btn active">${totalPages}</strong>`;
        } else {
            html += `<button class="page-btn" onclick="changePage(${totalPages})">${totalPages}</button>`;
        }

        // Ô nhập số trang + nút Go
        html += `
            <div class="page-jump">
                <input type="number" id="jumpPageInput" min="1" max="${totalPages}" value="${currentPage}" placeholder="Trang">
                <button class="page-btn" onclick="jumpToPage(${totalPages})">Go</button>
            </div>
        `;
    }

    // Nút Sau
    if (currentPage < totalPages) {
        html += `<button class="page-btn" onclick="changePage(${currentPage + 1})">Sau &raquo;</button>`;
    }

    html += `
            </div>
        </div>
    `;

    document.querySelector('.orders-table-wrapper').insertAdjacentHTML('beforeend', html);
}

// ========================================================================
// HÀM NHẢY TỚI TRANG KHI BẤM GO
// ========================================================================
function jumpToPage(totalPages) {
    const input = document.getElementById('jumpPageInput');
    let page = parseInt(input.value);

    if (isNaN(page) || page < 1) page = 1;
    if (page > totalPages) page = totalPages;

    input.value = page;

    if (page !== currentPage) {
        currentPage = page;
        applyFiltersAndPaginate();
    }
}

// ========================================================================
// HÀM CHUYỂN TRANG THÔNG THƯỜNG
// ========================================================================
function changePage(page) {
    currentPage = page;
    applyFiltersAndPaginate();
    window.scrollTo({ top: 0, behavior: 'smooth' });
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

    showConfirm('Bạn có chắc muốn cập nhật trạng thái đơn hàng này?', async function () {
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

            const orderIndex = ordersData.findIndex(o => o.OrderID === orderId);
            if (orderIndex !== -1) {
                ordersData[orderIndex].Status = newStatus;
            }

            // Cập nhật hiển thị màu và text
            const wrapper = selectElement.nextElementSibling;
            wrapper.className = `status-display ${newStatus}`;

            const statusText = wrapper.querySelector('.status-text');
            statusText.textContent = getStatusLabel(newStatus);

            showSuccess('Cập nhật trạng thái thành công!');

            // Cập nhật lại phân trang nếu cần
            applyFiltersAndPaginate();
        } catch (error) {
            console.error('Lỗi:', error);
            showError('Không thể cập nhật trạng thái. Vui lòng thử lại!');
            const order = ordersData.find(o => o.OrderID === orderId);
            if (order) selectElement.value = order.Status;
        }
    }, function () {
        // Cancel callback - reset to original status
        const order = ordersData.find(o => o.OrderID === orderId);
        if (order) selectElement.value = order.Status;
    });
}

// ========================================================================
// 6. LƯU GHI CHÚ
// ========================================================================
async function saveNote(orderId) {
    const input = document.querySelector(`.note-input[data-order-id="${orderId}"]`);
    const note = input.value.trim();

    try {
        const response = await fetch(`/staff/api/orders/${orderId}/note`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ note: note })
        });

        if (!response.ok) throw new Error('Không thể lưu ghi chú');

        const orderIndex = ordersData.findIndex(o => o.OrderID === orderId);
        if (orderIndex !== -1) {
            ordersData[orderIndex].note = note;
        }

        showSuccess('Lưu ghi chú thành công!');
    } catch (error) {
        showError('Không thể lưu ghi chú!');
    }
}

// ========================================================================
// 7. XEM CHI TIẾT ĐƠN HÀNG
// ========================================================================
async function showOrderDetail(orderId) {
    const modal = document.getElementById('orderDetailModal');
    const modalBody = document.getElementById('modalBody');
    modal.classList.add('show');

    try {
        const response = await fetch(`/staff/api/orders/${orderId}`);
        if (!response.ok) throw new Error('Không tải được chi tiết');

        const data = await response.json();
        const order = data.order;

        modalBody.innerHTML = `
            <div class="detail-section">
                <h3>Thông tin khách hàng</h3>
                <div class="detail-row"><span class="detail-label">Họ tên:</span><span class="detail-value">${order.customer_name}</span></div>
                <div class="detail-row"><span class="detail-label">Số điện thoại:</span><span class="detail-value">${order.Phone}</span></div>
                <div class="detail-row"><span class="detail-label">Email:</span><span class="detail-value">${order.customer_email || 'N/A'}</span></div>
                <div class="detail-row"><span class="detail-label">Địa chỉ giao:</span><span class="detail-value">${order.Address}</span></div>
            </div>

            <div class="detail-section">
                <h3>Thông tin đơn hàng</h3>
                <div class="detail-row"><span class="detail-label">Mã đơn hàng:</span><span class="detail-value highlight">${order.OrderID}</span></div>
                <div class="detail-row"><span class="detail-label">Ngày đặt:</span><span class="detail-value">${formatDateTime(order.OrderDate)}</span></div>
                <div class="detail-row"><span class="detail-label">Phương thức thanh toán:</span><span class="detail-value">${getPaymentMethodLabel(order.payment_method)}</span></div>
                <div class="detail-row"><span class="detail-label">Trạng thái:</span><span class="detail-value"><span class="status-badge ${order.Status}">${getStatusLabel(order.Status)}</span></span></div>
                <div class="detail-row"><span class="detail-label">Tổng tiền:</span><span class="detail-value highlight">${formatCurrency(order.TotalAmount)}</span></div>
            </div>

            ${order.note ? `
            <div class="detail-section">
                <h3>Ghi chú</h3>
                <p style="background:#f8f9fa; padding:15px; border-radius:8px; color:#555; font-style:italic;">${order.note}</p>
            </div>
            ` : ''}

            <div class="detail-section">
                <h3>Sản phẩm</h3>
                <ul class="products-list">
                    ${order.items.map(item => `
                        <li class="product-item">
                            <div>
                                <span class="product-name">${item.product_name}</span>
                                <span class="product-quantity">x ${item.Quantity}</span>
                            </div>
                            <span class="product-price">${formatCurrency(item.subtotal)}</span>
                        </li>
                    `).join('')}
                </ul>
            </div>
        `;
    } catch (error) {
        modalBody.innerHTML = `<p style="color:red; text-align:center;">Không thể tải chi tiết đơn hàng!</p>`;
    }
}

function closeOrderModal() {
    document.getElementById('orderDetailModal').classList.remove('show');
}

document.addEventListener('click', function (e) {
    const modal = document.getElementById('orderDetailModal');
    if (e.target === modal) closeOrderModal();
});

// ========================================================================
// 8. TÌM KIẾM VÀ LỌC
// ========================================================================
function initEventListeners() {
    const searchInput = document.getElementById('search-input');
    if (searchInput) {
        searchInput.addEventListener('input', debounce(() => loadOrders(true), 300));
    }

    document.querySelectorAll('.filter-checkbox input').forEach(checkbox => {
        checkbox.addEventListener('change', () => loadOrders(true));
    });
}

// ========================================================================
// 9. HELPER FUNCTIONS
// ========================================================================
function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('vi-VN');
}

function formatDateTime(dateString) {
    const date = new Date(dateString);
    return date.toLocaleString('vi-VN', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' });
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

function getPaymentMethodLabel(method) {
    const labels = {
        'cod': 'Thanh toán khi nhận hàng (COD)',
        'bank_transfer': 'Chuyển khoản ngân hàng',
        'vnpay': 'Thanh toán VNPay',
        'momo': 'Ví MoMo'
    };
    return labels[method] || method;
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
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
        <span>${message}</span>
    `;

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
            .notification.success { border-left: 4px solid #27ae60; }
            .notification.success i { color: #27ae60; font-size: 1.2rem; }
            .notification.error { border-left: 4px solid #e74c3c; }
            .notification.error i { color: #e74c3c; font-size: 1.2rem; }
            @keyframes slideIn {
                from { transform: translateX(400px); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
        `;
        document.head.appendChild(style);
    }

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.animation = 'slideIn 0.3s ease reverse';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}