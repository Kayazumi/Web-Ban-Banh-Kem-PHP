@extends('layouts.admin')

@section('page-title', 'Dashboard')

@section('content')
<div class="dashboard">
    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="stat-content">
                <h3 id="totalOrders">0</h3>
                <p>Tổng đơn hàng</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-box"></i>
            </div>
            <div class="stat-content">
                <h3 id="totalProducts">0</h3>
                <p>Tổng sản phẩm</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <h3 id="totalUsers">0</h3>
                <p>Tổng người dùng</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="stat-content">
                <h3 id="totalRevenue">0đ</h3>
                <p>Doanh thu</p>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="dashboard-section">
        <div class="section-header">
            <h2>Đơn hàng gần đây</h2>
            <a href="{{ route('admin.orders') }}" class="btn btn-sm">Xem tất cả</a>
        </div>

        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Mã đơn</th>
                        <th>Khách hàng</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Ngày đặt</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody id="recentOrders">
                    <tr>
                        <td colspan="6" class="text-center">Đang tải...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Products -->
    <div class="dashboard-section">
        <div class="section-header">
            <h2>Sản phẩm gần đây</h2>
            <a href="{{ route('admin.products') }}" class="btn btn-sm">Xem tất cả</a>
        </div>

        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Hình ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Giá</th>
                        <th>Tồn kho</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody id="recentProducts">
                    <tr>
                        <td colspan="6" class="text-center">Đang tải...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    loadDashboardStats();
    loadRecentOrders();
    loadRecentProducts();
});

async function loadDashboardStats() {
    try {
        // Load orders count
        const ordersResponse = await fetch('/api/admin/orders?limit=1');
        const ordersData = await ordersResponse.json();
        document.getElementById('totalOrders').textContent = ordersData.total || 0;

        // Load products count
        const productsResponse = await fetch('/api/admin/products?limit=1');
        const productsData = await productsResponse.json();
        document.getElementById('totalProducts').textContent = productsData.total || 0;

        // For demo purposes, set static values
        document.getElementById('totalUsers').textContent = '150';
        document.getElementById('totalRevenue').textContent = '250.000.000đ';

    } catch (error) {
        console.error('Error loading dashboard stats:', error);
    }
}

async function loadRecentOrders() {
    try {
        const response = await fetch('/api/admin/orders?limit=5');
        const data = await response.json();

        const tbody = document.getElementById('recentOrders');

        if (data.success && data.data.orders.length > 0) {
            tbody.innerHTML = data.data.orders.map(order => `
                <tr>
                    <td>${order.order_code}</td>
                    <td>${order.customer_name}</td>
                    <td>${formatPrice(order.final_amount)}</td>
                    <td><span class="status status-${order.order_status}">${getOrderStatusText(order.order_status)}</span></td>
                    <td>${formatDate(order.created_at)}</td>
                    <td>
                        <a href="/admin/orders/${order.OrderID}" class="btn btn-xs">Xem</a>
                    </td>
                </tr>
            `).join('');
        } else {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center">Không có đơn hàng nào</td></tr>';
        }

    } catch (error) {
        console.error('Error loading recent orders:', error);
        document.getElementById('recentOrders').innerHTML =
            '<tr><td colspan="6" class="text-center text-danger">Lỗi tải dữ liệu</td></tr>';
    }
}

async function loadRecentProducts() {
    try {
        const response = await fetch('/api/admin/products?limit=5');
        const data = await response.json();

        const tbody = document.getElementById('recentProducts');

        if (data.success && data.data.products.length > 0) {
            tbody.innerHTML = data.data.products.map(product => `
                <tr>
                    <td>
                        <img src="${product.image_url || '/images/placeholder.jpg'}"
                             alt="${product.product_name}" class="table-image">
                    </td>
                    <td>${product.product_name}</td>
                    <td>${formatPrice(product.price)}</td>
                    <td>${product.quantity}</td>
                    <td><span class="status status-${product.status}">${product.status}</span></td>
                    <td>
                        <a href="/admin/products/${product.ProductID}/edit" class="btn btn-xs">Sửa</a>
                    </td>
                </tr>
            `).join('');
        } else {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center">Không có sản phẩm nào</td></tr>';
        }

    } catch (error) {
        console.error('Error loading recent products:', error);
        document.getElementById('recentProducts').innerHTML =
            '<tr><td colspan="6" class="text-center text-danger">Lỗi tải dữ liệu</td></tr>';
    }
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

function getOrderStatusText(status) {
    const statusMap = {
        'pending': 'Chờ xác nhận',
        'order_received': 'Đã nhận đơn',
        'preparing': 'Đang chuẩn bị',
        'delivering': 'Đang giao',
        'delivery_successful': 'Giao thành công',
        'delivery_failed': 'Giao thất bại'
    };
    return statusMap[status] || status;
}
</script>
@endpush
