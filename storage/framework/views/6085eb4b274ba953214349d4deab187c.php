<?php $__env->startSection('page-title', 'Quản lý đơn hàng'); ?>

<?php $__env->startSection('content'); ?>
<div class="admin-content">
    <div class="content-header">
    </div>

    <!-- Filters -->
    <div class="filters">
        <div class="filter-group">
            <label for="statusFilter">Trạng thái:</label>
            <select id="statusFilter" class="form-control">
                <option value="">Tất cả</option>
                <option value="pending">Chờ xác nhận</option>
                <option value="order_received">Đã nhận đơn</option>
                <option value="preparing">Đang chuẩn bị</option>
                <option value="delivering">Đang giao hàng</option>
                <option value="delivery_successful">Giao thành công</option>
                <option value="delivery_failed">Giao thất bại</option>
                <option value="cancelled">Đã hủy</option>
            </select>
        </div>

        <div class="filter-group">
            <label for="searchInput">Tìm kiếm:</label>
            <input type="text" id="searchInput" class="form-control" placeholder="Mã đơn hàng, tên khách...">
        </div>

        <button id="applyFilters" class="btn btn-secondary">Lọc</button>
    </div>

    <!-- Orders Table -->
    <div class="table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Mã đơn</th>
                    <th>Khách hàng</th>
                    <th>Số điện thoại</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Ngày đặt</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody id="ordersTableBody">
                <tr>
                    <td colspan="7" class="text-center">Đang tải...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
    <!-- Order Detail Modal -->
    <div id="orderDetailModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="orderDetailTitle">Chi tiết đơn hàng</h3>
                <button class="close-btn" onclick="closeOrderModal()">&times;</button>
            </div>
            <div class="modal-body" id="orderDetailBody">
                <!-- populated by JS -->
            </div>
            <div class="modal-footer" style="padding:1rem;display:flex;gap:0.5rem;justify-content:flex-end;border-top:1px solid #eee;">
                <button class="btn btn-secondary" onclick="closeOrderModal()">Đóng</button>
                <select id="orderStatusSelect" style="padding:6px;border-radius:4px;border:1px solid #ddd;">
                    <option value="pending">Chờ xác nhận</option>
                    <option value="order_received">Đã nhận đơn</option>
                    <option value="preparing">Đang chuẩn bị</option>
                    <option value="delivering">Đang giao hàng</option>
                    <option value="delivery_successful">Giao thành công</option>
                    <option value="delivery_failed">Giao thất bại</option>
                    <option value="cancelled">Đã hủy</option>
                </select>
                <button id="saveOrderStatusBtn" class="btn btn-primary">Cập nhật trạng thái</button>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.content-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.filters {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    align-items: end;
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 8px;
    margin-bottom: 2rem;
}

.filter-group {
    display: flex;
    flex-direction: column;
    min-width: 200px;
}

.filter-group label {
    font-weight: bold;
    margin-bottom: 0.5rem;
    color: #333;
}

.form-control {
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1rem;
}

.table-container {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    overflow: hidden;
}

.admin-table {
    width: 100%;
    border-collapse: collapse;
}

.admin-table th,
.admin-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.admin-table th {
    background: #f8f9fa;
    font-weight: 600;
    color: #333;
}

.status-badge {
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    font-size: 0.875rem;
    font-weight: bold;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
}

.status-order_received {
    background: #d1ecf1;
    color: #0c5460;
}

.status-preparing {
    background: #d4edda;
    color: #155724;
}

.status-delivering {
    background: #cce5ff;
    color: #004085;
}

.status-delivery_successful {
    background: #d4edda;
    color: #155724;
}

.status-delivery_failed,
.status-cancelled {
    background: #f8d7da;
    color: #721c24;
}

.btn {
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.9rem;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s;
}

.btn-primary {
    background: #007bff;
    color: white;
}

.btn-primary:hover {
    background: #0056b3;
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #545b62;
}

.text-center {
    text-align: center;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('styles'); ?>
<style>
/* Modal Styles (shared) */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
}
.modal-content {
    background-color: white;
    margin: 3% auto;
    padding: 0;
    border-radius: 8px;
    width: 90%;
    max-width: 900px;
    max-height: 90vh;
    overflow-y: auto;
}
.modal-header {
    padding: 1rem;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.modal-body {
    padding: 1rem;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    window.currentOrdersFilters = {};
    loadOrders();

    // Filters
    document.getElementById('applyFilters').addEventListener('click', applyFilters);
    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            applyFilters();
        }
    });

    // setup save status button in modal
    document.getElementById('saveOrderStatusBtn').addEventListener('click', async function(){
        const orderId = this.dataset.orderId;
        const newStatus = document.getElementById('orderStatusSelect').value;
        if (!orderId) return;
        try {
            const token = localStorage.getItem('api_token');
            const csrf = window.Laravel && window.Laravel.csrfToken ? window.Laravel.csrfToken : (document.querySelector('meta[name=\"csrf-token\"]') ? document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content') : '');
            const headers = {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            };
            if (token) headers['Authorization'] = `Bearer ${token}`;
            if (csrf) headers['X-CSRF-TOKEN'] = csrf;
            const response = await fetch(`/api/admin/orders/${orderId}/status`, {
                method: 'PUT',
                headers,
                body: JSON.stringify({ status: newStatus, note: 'Cập nhật bởi admin' })
            });
            const data = await response.json();
            if (data.success) {
                alert('Cập nhật trạng thái thành công');
                closeOrderModal();
                loadOrders();
            } else {
                alert(data.message || 'Có lỗi xảy ra');
            }
        } catch (err) {
            console.error(err);
            alert('Có lỗi xảy ra');
        }
    });
});

async function loadOrders() {
    try {
        const params = new URLSearchParams(window.currentOrdersFilters || {});
        const token = localStorage.getItem('api_token');
        const response = await fetch(`/api/admin/orders?${params.toString()}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`,
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        const data = await response.json();

        const tbody = document.getElementById('ordersTableBody');

        if (data.success && data.data.orders.length > 0) {
            tbody.innerHTML = data.data.orders.map(order => `
                <tr>
                    <td>${order.order_code}</td>
                    <td>${order.customer_name}</td>
                    <td>${order.customer_phone}</td>
                    <td>${formatPrice(order.final_amount)}</td>
                    <td>
                        <span class="status-badge status-${order.order_status}">
                            ${getStatusText(order.order_status)}
                        </span>
                    </td>
                    <td>${formatDate(order.created_at)}</td>
                    <td>
                        <button onclick="showOrderDetail(${order.OrderID})" class="btn btn-primary btn-sm">Xem</button>
                    </td>
                </tr>
            `).join('');
        } else {
            tbody.innerHTML = '<tr><td colspan="7" class="text-center">Không có đơn hàng nào</td></tr>';
        }
    } catch (error) {
        console.error('Error loading orders:', error);
        document.getElementById('ordersTableBody').innerHTML =
            '<tr><td colspan="7" class="text-center text-danger">Lỗi tải dữ liệu</td></tr>';
    }
}

function applyFilters() {
    const status = document.getElementById('statusFilter').value;
    const search = document.getElementById('searchInput').value.trim();
    window.currentOrdersFilters = {};
    if (status) window.currentOrdersFilters.status = status;
    if (search) window.currentOrdersFilters.search = search;
    loadOrders();
}

async function updateStatus(orderId, currentStatus) {
    const newStatus = prompt('Nhập trạng thái mới:', currentStatus);
    if (!newStatus || newStatus === currentStatus) return;

    try {
        const token = localStorage.getItem('api_token');
        const csrf = window.Laravel && window.Laravel.csrfToken ? window.Laravel.csrfToken : (document.querySelector('meta[name=\"csrf-token\"]') ? document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content') : '');
        const headers = { 'Content-Type': 'application/json' };
        if (token) headers['Authorization'] = `Bearer ${token}`;
        if (csrf) headers['X-CSRF-TOKEN'] = csrf;
        const response = await fetch(`/api/admin/orders/${orderId}/status`, {
            method: 'PUT',
            headers,
            body: JSON.stringify({
                status: newStatus,
                note: 'Cập nhật bởi admin'
            })
        });

        const data = await response.json();

        if (data.success) {
            alert('Cập nhật trạng thái thành công!');
            loadOrders();
        } else {
            alert(data.message || 'Có lỗi xảy ra');
        }
    } catch (error) {
        console.error('Error updating status:', error);
        alert('Có lỗi xảy ra');
    }
}

function getStatusText(status) {
    const statusMap = {
        'pending': 'Chờ xác nhận',
        'order_received': 'Đã nhận đơn',
        'preparing': 'Đang chuẩn bị',
        'delivering': 'Đang giao hàng',
        'delivery_successful': 'Giao thành công',
        'delivery_failed': 'Giao thất bại',
        'cancelled': 'Đã hủy'
    };
    return statusMap[status] || status;
}

function canUpdateStatus(status) {
    return !['delivery_successful', 'cancelled', 'delivery_failed'].includes(status);
}

function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(price);
}

function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString('vi-VN');
}

// Show order detail modal
async function showOrderDetail(orderId) {
    try {
        const token = localStorage.getItem('api_token');
        const res = await fetch(`/api/admin/orders/${orderId}`, {
            headers: { 'Accept': 'application/json', 'Authorization': `Bearer ${token}`, 'X-Requested-With': 'XMLHttpRequest' }
        });
        const payload = await res.json();
        if (!payload.success) {
            alert('Không thể tải chi tiết đơn hàng');
            return;
        }
        const order = payload.data.order;
        const body = document.getElementById('orderDetailBody');
        // build simple detail layout similar to provided mock
        let itemsHtml = '';
        (order.orderItems || []).forEach(i => {
            const prod = i.product || {};
            itemsHtml += `<tr><td>${prod.product_name || ''}</td><td style="text-align:center">${i.quantity}</td><td style="text-align:right">${formatPrice(i.subtotal)}</td></tr>`;
        });
        const total = formatPrice(order.final_amount);
        body.innerHTML = `
            <div style="padding:0 1rem 1rem;">
                <h4>Chi tiết đơn hàng - ${order.order_code}</h4>
                <h5>Thông tin khách hàng</h5>
                <p>Tên khách: ${order.customer_name}<br>Điện thoại: ${order.customer_phone}<br>Địa chỉ: ${order.customer_address || order.customer_address_line || ''}</p>
                <h5>Danh sách sản phẩm</h5>
                <table style="width:100%;border-collapse:collapse;margin-bottom:1rem">
                    <thead><tr style="background:#f5f5f5"><th style="text-align:left">Tên sản phẩm</th><th style="width:100px;text-align:center">Số lượng</th><th style="width:120px;text-align:right">Tổng tiền</th></tr></thead>
                    <tbody>${itemsHtml}</tbody>
                    <tfoot><tr><td></td><td style="text-align:right;font-weight:700">Tổng cộng</td><td style="text-align:right;font-weight:700">${total}</td></tr></tfoot>
                </table>
                <h5>Trạng thái đơn hàng hiện tại</h5>
                <p>Lần cập nhật cuối: ${formatDate(order.updated_at || order.created_at)}</p>
            </div>
        `;
        // set modal status select and attach order id to save button
        document.getElementById('orderStatusSelect').value = order.order_status || 'pending';
        document.getElementById('saveOrderStatusBtn').dataset.orderId = order.OrderID;
        document.getElementById('orderDetailModal').style.display = 'block';
    } catch (e) {
        console.error(e);
        alert('Lỗi tải chi tiết đơn hàng');
    }
}

function closeOrderModal() {
    document.getElementById('orderDetailModal').style.display = 'none';
    document.getElementById('saveOrderStatusBtn').dataset.orderId = '';
}
// close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('orderDetailModal');
    if (modal && event.target === modal) {
        closeOrderModal();
    }
};
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\ProgramFilesD\DevApps\XAM_PP\htdocs\Web-Ban-Banh-Kem-PHP\resources\views/admin/orders.blade.php ENDPATH**/ ?>