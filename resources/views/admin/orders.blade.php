@extends('layouts.admin')

@section('page-title', 'Quản lý đơn hàng')

@section('content')
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
        
        <!-- Pagination -->
        <div id="ordersPagination" class="pagination-container" style="display: none;">
            <div class="pagination-info">
                <span id="ordersShowingText"></span>
            </div>
            <div class="pagination-controls">
                <button id="ordersPrevPage" class="pagination-btn">‹</button>
                <div id="ordersPageNumbers" class="page-numbers"></div>
                <button id="ordersNextPage" class="pagination-btn">›</button>
            </div>
        </div>
    </div>
</div>
    <!-- Order Detail Modal -->
    <div id="orderDetailModal" class="modal">
        <div class="modal-content order-detail-modal">
            <div class="order-modal-header">
                <h3 id="orderDetailTitle">Chi tiết đơn hàng</h3>
                <button class="close-btn"  onclick="closeOrderModal()">×</button>
            </div>
            <div class="order-code-bar" id="orderCodeBar">
                <!-- Order code will be inserted here -->
            </div>
            <div class="modal-body" id="orderDetailBody">
                <!-- populated by JS -->
            </div>
            <div class="order-modal-footer">
                <button class="btn-cancel" onclick="closeOrderModal()">Đóng</button>
                <select id="orderStatusSelect" class="status-select">
                    <option value="pending">Chờ xác nhận</option>
                    <option value="order_received">Đã nhận đơn</option>
                    <option value="preparing">Đang chuẩn bị</option>
                    <option value="delivering">Đang giao hàng</option>
                    <option value="delivery_successful">Giao thành công</option>
                    <option value="delivery_failed">Giao thất bại</option>
                    <option value="cancelled">Đã hủy</option>
                </select>
                <button id="saveOrderStatusBtn" class="btn-save">Cập nhật trạng thái</button>
            </div>
        </div>
    </div>
@endsection

@push('styles')
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

/* Pagination Styles */
.pagination-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.5rem;
    border-top: 1px solid #eee;
    background: #fafafa;
}

.pagination-info {
    color: #666;
    font-size: 0.9rem;
}

.pagination-controls {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.page-numbers {
    display: flex;
    gap: 0.25rem;
}

.pagination-btn,
.page-btn {
    padding: 0.5rem 0.75rem;
    border: 1px solid #ddd;
    background: white;
    color: #333;
    cursor: pointer;
    border-radius: 4px;
    font-size: 0.9rem;
    transition: all 0.2s;
}

.pagination-btn:hover:not(:disabled),
.page-btn:hover:not(.active) {
    background: #f0f0f0;
    border-color: #999;
}

.pagination-btn:disabled {
    opacity: 0.4;
    cursor: not-allowed;
}

.page-btn.active {
    background: #007bff;
    color: white;
    border-color: #007bff;
    font-weight: bold;
}

.page-btn.ellipsis {
    border: none;
    background: none;
    cursor: default;
    pointer-events: none;
}
</style>
@endpush

@push('styles')
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

/* Order Detail Modal Specific Styles */
.order-detail-modal {
    max-width: 600px;
}

.order-modal-header {
    background-color: #3d5a3d;
    color: white;
    padding: 0.75rem 1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-radius: 8px 8px 0 0;
}

.order-modal-header h3 {
    margin: 0;
    font-size: 1rem;
    font-weight: 500;
}

.order-modal-header .close-btn {
    background: none;
    border: none;
    color: white;
    font-size: 1.5rem;
    cursor: pointer;
    padding: 0;
    line-height: 1;
}

.order-code-bar {
    background-color: #4a6d4a;
    color: white;
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
}

.order-detail-modal .modal-body {
    padding: 1.5rem;
}

.order-section {
    margin-bottom: 1.5rem;
}

.order-section-title {
    background-color: #f5f0e8;
    color: #333;
    padding: 0.5rem;
    font-weight: 500;
    font-size: 0.875rem;
    margin-bottom: 0.75rem;
}

.order-info-row {
    display: flex;
    padding: 0.25rem 0;
    font-size: 0.875rem;
}

.order-info-label {
    min-width: 140px;
    color: #666;
}

.order-info-value {
    color: #333;
}

.order-products-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 0.5rem;
}

.order-products-table thead {
    background-color: #f5f0e8;
}

.order-products-table th,
.order-products-table td {
    padding: 0.5rem;
    text-align: left;
    font-size: 0.875rem;
    border-bottom: 1px solid #eee;
}

.order-products-table th {
    font-weight: 500;
}

.order-products-table .text-center {
    text-align: center;
}

.order-products-table .text-right {
    text-align: right;
}

.order-total-row {
    background-color: #f5f0e8;
    font-weight: 600;
}

.order-modal-footer {
    padding: 1rem;
    display: flex;
    gap: 0.5rem;
    justify-content: space-between;
    align-items: center;
    border-top: 1px solid #eee;
}

.btn-cancel {
    background-color: #f0f0f0;
    color: #666;
    border: 1px solid #ddd;
    padding: 0.5rem 1rem;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.875rem;
}

.btn-cancel:hover {
    background-color: #e0e0e0;
}

.status-select {
    padding: 0.5rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 0.875rem;
    max-width: 150px;
}

.btn-save {
    background-color: #3d5a3d;
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.875rem;
}

.btn-save:hover {
    background-color: #2d4a2d;
}
</style>
@endpush

@push('scripts')
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

async function loadOrders(page = 1) {
    try {
        const params = new URLSearchParams(window.currentOrdersFilters || {});
        params.set('page', page);
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
            
            // Render pagination
            if (data.pagination) {
                renderOrdersPagination(data.pagination);
            }
        } else {
            tbody.innerHTML = '<tr><td colspan="7" class="text-center">Không có đơn hàng nào</td></tr>';
            document.getElementById('ordersPagination').style.display = 'none';
        }
    } catch (error) {
        console.error('Error loading orders:', error);
        document.getElementById('ordersTableBody').innerHTML =
            '<tr><td colspan="7" class="text-center text-danger">Lỗi tải dữ liệu</td></tr>';
        document.getElementById('ordersPagination').style.display = 'none';
    }
}

function renderOrdersPagination(pagination) {
    const { current_page, last_page, per_page, total } = pagination;
    
    // Show pagination container
    const paginationContainer = document.getElementById('ordersPagination');
    paginationContainer.style.display = 'flex';
    
    // Update showing text
    const start = (current_page - 1) * per_page + 1;
    const end = Math.min(current_page * per_page, total);
    document.getElementById('ordersShowingText').textContent = 
        `Hiển thị ${start} - ${end} của ${total} đơn hàng`;
    
    // Update prev/next buttons
    const prevBtn = document.getElementById('ordersPrevPage');
    const nextBtn = document.getElementById('ordersNextPage');
    
    prevBtn.disabled = current_page === 1;
    nextBtn.disabled = current_page === last_page;
    
    prevBtn.onclick = () => {
        if (current_page > 1) loadOrders(current_page - 1);
    };
    nextBtn.onclick = () => {
        if (current_page < last_page) loadOrders(current_page + 1);
    };
    
    // Render page numbers
    const pageNumbersContainer = document.getElementById('ordersPageNumbers');
    pageNumbersContainer.innerHTML = '';
    
    const maxVisiblePages = 7;
    let startPage = Math.max(1, current_page - Math.floor(maxVisiblePages / 2));
    let endPage = Math.min(last_page, startPage + maxVisiblePages - 1);
    
    if (endPage - startPage < maxVisiblePages - 1) {
        startPage = Math.max(1, endPage - maxVisiblePages + 1);
    }
    
    // Always show first page
    if (startPage > 1) {
        pageNumbersContainer.appendChild(createPageButton(1, current_page));
        if (startPage > 2) {
            const ellipsis = document.createElement('button');
            ellipsis.className = 'page-btn ellipsis';
            ellipsis.textContent = '...';
            pageNumbersContainer.appendChild(ellipsis);
        }
    }
    
    // Show page range
    for (let i = startPage; i <= endPage; i++) {
        pageNumbersContainer.appendChild(createPageButton(i, current_page));
    }
    
    // Always show last page
    if (endPage < last_page) {
        if (endPage < last_page - 1) {
            const ellipsis = document.createElement('button');
            ellipsis.className = 'page-btn ellipsis';
            ellipsis.textContent = '...';
            pageNumbersContainer.appendChild(ellipsis);
        }
        pageNumbersContainer.appendChild(createPageButton(last_page, current_page));
    }
}

function createPageButton(pageNum, currentPage) {
    const btn = document.createElement('button');
    btn.className = 'page-btn' + (pageNum === currentPage ? ' active' : '');
    btn.textContent = pageNum;
    if (pageNum !== currentPage) {
        btn.onclick = () => loadOrders(pageNum);
    }
    return btn;
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
        
        // Update order code bar
        document.getElementById('orderCodeBar').innerHTML = `Chi tiết đơn hàng-${order.order_code}`;
        
        // Build product items
        let itemsHtml = '';
        (order.orderItems || []).forEach(i => {
            const prod = i.product || {};
            itemsHtml += `
                <tr>
                    <td>${prod.product_name || ''}</td>
                    <td class="text-center">${i.quantity}</td>
                    <td class="text-right">${formatPrice(i.subtotal)}</td>
                </tr>
            `;
        });
        
        const total = formatPrice(order.final_amount);
        
        // Build modal body
        const body = document.getElementById('orderDetailBody');
        body.innerHTML = `
            <div class="order-section">
                <div class="order-section-title">Thông tin khách hàng</div>
                <div class="order-info-row">
                    <span class="order-info-label">Tên khách hàng:</span>
                    <span class="order-info-value">${order.customer_name}</span>
                </div>
                <div class="order-info-row">
                    <span class="order-info-label">Số điện thoại:</span>
                    <span class="order-info-value">${order.customer_phone}</span>
                </div>
                <div class="order-info-row">
                    <span class="order-info-label">Địa chỉ giao hàng:</span>
                    <span class="order-info-value">${order.customer_address || order.customer_address_line || ''}</span>
                </div>
            </div>
            
            <div class="order-section">
                <div class="order-section-title">Danh sách sản phẩm</div>
                <table class="order-products-table">
                    <thead>
                        <tr>
                            <th>Tên sản phẩm</th>
                            <th class="text-center" style="width: 100px;">Số lượng</th>
                            <th class="text-right" style="width: 120px;">Tổng tiền</th>
                        </tr>
                    </thead>
                    <tbody>${itemsHtml}</tbody>
                    <tfoot>
                        <tr class="order-total-row">
                            <td colspan="2">Tổng cộng</td>
                            <td class="text-right">${total}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <div class="order-section">
                <div class="order-section-title">Trạng thái đơn hàng hiện tại</div>
                <div class="order-info-row">
                    <span class="order-info-label">Lần cập nhật cuối:</span>
                    <span class="order-info-value">${formatDate(order.updated_at || order.created_at)}</span>
                </div>
            </div>
        `;
        
        // Set modal status select and attach order id to save button
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
@endpush
