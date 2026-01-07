<?php $__env->startSection('page-title', 'Quản lý đơn hàng'); ?>

<?php $__env->startSection('content'); ?>
<div class="admin-content">
    <div class="content-header">
        <h2>Quản lý đơn hàng</h2>
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

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    loadOrders();

    // Filters
    document.getElementById('applyFilters').addEventListener('click', applyFilters);
    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            applyFilters();
        }
    });
});

async function loadOrders() {
    try {
        const token = localStorage.getItem('api_token');
        const response = await fetch('/api/admin/orders', {
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
                        <a href="/admin/orders/${order.OrderID}" class="btn btn-primary btn-sm">Xem</a>
                        ${canUpdateStatus(order.order_status) ?
                            `<button onclick="updateStatus(${order.OrderID}, '${order.order_status}')" class="btn btn-secondary btn-sm">Cập nhật</button>` :
                            ''}
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
    // Filter functionality would be implemented here
    loadOrders();
}

async function updateStatus(orderId, currentStatus) {
    const newStatus = prompt('Nhập trạng thái mới:', currentStatus);
    if (!newStatus || newStatus === currentStatus) return;

    try {
        const response = await fetch(`/api/admin/orders/${orderId}/status`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.Laravel.csrfToken
            },
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
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Hoc_tap\Lap_Trinh_PHP\xampp\htdocs\Web-Ban-Banh-Kem-PHP\resources\views/admin/orders.blade.php ENDPATH**/ ?>