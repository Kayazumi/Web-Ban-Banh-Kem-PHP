@extends('layouts.app')

@section('title', 'Đơn hàng của tôi - La Cuisine Ngọt')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">Đơn hàng của tôi</h1>

            <div id="ordersContent">
                <!-- Orders will be loaded via AJAX -->
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Đang tải...</span>
                    </div>
                    <p>Đang tải đơn hàng...</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem 20px;
}

.order-card {
    border: 1px solid #ddd;
    border-radius: 10px;
    margin-bottom: 1rem;
    background: white;
    overflow: hidden;
}

.order-header {
    background: #f8f9fa;
    padding: 1rem;
    border-bottom: 1px solid #ddd;
    display: flex;
    justify-content: between;
    align-items: center;
}

.order-header h3 {
    margin: 0;
    font-size: 1.2rem;
}

.order-code {
    color: #8B4513;
    font-weight: bold;
}

.order-status {
    padding: 0.25rem 0.5rem;
    border-radius: 3px;
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

.order-body {
    padding: 1rem;
}

.order-item {
    display: flex;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid #f0f0f0;
}

.order-item:last-child {
    border-bottom: none;
}

.order-item-image {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 5px;
    margin-right: 1rem;
}

.order-item-details {
    flex: 1;
}

.order-item-name {
    font-weight: bold;
    margin-bottom: 0.25rem;
}

.order-item-price {
    color: #8B4513;
    font-weight: bold;
}

.order-footer {
    background: #f8f9fa;
    padding: 1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.order-total {
    font-size: 1.1rem;
    font-weight: bold;
    color: #8B4513;
}

.btn {
    display: inline-block;
    padding: 0.5rem 1rem;
    text-decoration: none;
    border-radius: 5px;
    font-weight: 500;
    cursor: pointer;
    border: none;
    transition: all 0.3s;
}

.btn-primary {
    background: #8B4513;
    color: white;
}

.btn-primary:hover {
    background: #654321;
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #545b62;
}

.btn-outline {
    background: transparent;
    border: 1px solid #8B4513;
    color: #8B4513;
}

.btn-outline:hover {
    background: #8B4513;
    color: white;
}

.empty-orders {
    text-align: center;
    padding: 3rem;
}

.empty-orders h3 {
    color: #6c757d;
    margin-bottom: 1rem;
}

.text-center {
    text-align: center;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    loadOrders();
});

async function loadOrders() {
    try {
        const response = await fetch('/api/orders');
        const data = await response.json();

        const ordersContent = document.getElementById('ordersContent');

        if (data.success && data.data.orders.length > 0) {
            let html = '';

            data.data.orders.forEach(order => {
                const statusClass = getStatusClass(order.order_status);
                const statusText = getStatusText(order.order_status);

                html += `
                    <div class="order-card">
                        <div class="order-header">
                            <div>
                                <h3>Đơn hàng <span class="order-code">#${order.order_code}</span></h3>
                                <small>${formatDate(order.created_at)}</small>
                            </div>
                            <span class="order-status ${statusClass}">${statusText}</span>
                        </div>

                        <div class="order-body">
                            ${order.order_items ? order.order_items.map(item => `
                                <div class="order-item">
                                    <img src="${item.product?.image_url || '/images/placeholder.jpg'}"
                                         alt="${item.product_name}"
                                         class="order-item-image">
                                    <div class="order-item-details">
                                        <div class="order-item-name">${item.product_name}</div>
                                        <div>Số lượng: ${item.quantity} x ${formatPrice(item.product_price)}</div>
                                        ${item.note ? `<small>Ghi chú: ${item.note}</small>` : ''}
                                    </div>
                                    <div class="order-item-price">${formatPrice(item.subtotal)}</div>
                                </div>
                            `).join('') : ''}
                        </div>

                        <div class="order-footer">
                            <div>
                                <p><strong>Giao đến:</strong> ${order.shipping_address}</p>
                                ${order.note ? `<p><strong>Ghi chú:</strong> ${order.note}</p>` : ''}
                            </div>
                            <div class="text-right">
                                <div class="order-total">Tổng: ${formatPrice(order.final_amount)}</div>
                                <a href="/orders/${order.OrderID}" class="btn btn-outline">Xem chi tiết</a>
                                ${canCancelOrder(order.order_status) ?
                                    `<button onclick="cancelOrder(${order.OrderID})" class="btn btn-secondary">Hủy đơn</button>` :
                                    ''}
                            </div>
                        </div>
                    </div>
                `;
            });

            ordersContent.innerHTML = html;
        } else {
            ordersContent.innerHTML = `
                <div class="empty-orders">
                    <h3>Bạn chưa có đơn hàng nào</h3>
                    <p>Hãy bắt đầu mua sắm để tạo đơn hàng đầu tiên.</p>
                    <a href="/" class="btn btn-primary">Mua sắm ngay</a>
                </div>
            `;
        }
    } catch (error) {
        console.error('Error loading orders:', error);
        document.getElementById('ordersContent').innerHTML = `
            <div class="text-center">
                <p>Có lỗi xảy ra khi tải đơn hàng. Vui lòng thử lại.</p>
                <button onclick="loadOrders()" class="btn btn-primary">Thử lại</button>
            </div>
        `;
    }
}

async function cancelOrder(orderId) {
    if (!confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')) return;

    try {
        const response = await fetch(`/api/orders/${orderId}/cancel`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.Laravel.csrfToken
            },
            body: JSON.stringify({
                reason: 'Khách hàng hủy đơn hàng'
            })
        });

        const data = await response.json();

        if (data.success) {
            alert('Đã hủy đơn hàng thành công');
            loadOrders(); // Reload orders
        } else {
            alert(data.message || 'Có lỗi xảy ra');
        }
    } catch (error) {
        console.error('Error cancelling order:', error);
        alert('Có lỗi xảy ra');
    }
}

function getStatusClass(status) {
    return `status-${status}`;
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

function canCancelOrder(status) {
    return ['pending', 'order_received'].includes(status);
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
@endpush
