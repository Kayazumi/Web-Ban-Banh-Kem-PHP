@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng - La Cuisine Ngọt')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="mb-4">
                <a href="{{ route('orders.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại danh sách đơn hàng
                </a>
            </div>

            <div id="orderDetailContent">
                <!-- Order detail will be loaded via AJAX -->
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Đang tải...</span>
                    </div>
                    <p>Đang tải chi tiết đơn hàng...</p>
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

.order-detail-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
    overflow: hidden;
    margin-bottom: 2rem;
}

.order-header {
    background: #8B4513;
    color: white;
    padding: 2rem;
}

.order-title {
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
}

.order-meta {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
}

.order-meta-item {
    background: rgba(255,255,255,0.1);
    padding: 1rem;
    border-radius: 5px;
}

.order-meta-item strong {
    display: block;
    font-size: 0.875rem;
    opacity: 0.8;
}

.order-status {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: bold;
    display: inline-block;
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
    padding: 2rem;
}

.section-title {
    font-size: 1.25rem;
    font-weight: bold;
    margin-bottom: 1rem;
    color: #333;
}

.order-items {
    margin-bottom: 2rem;
}

.order-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    border: 1px solid #eee;
    border-radius: 8px;
    margin-bottom: 1rem;
    background: #fafafa;
}

.order-item-image {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 5px;
    margin-right: 1rem;
}

.order-item-details {
    flex: 1;
}

.order-item-name {
    font-weight: bold;
    margin-bottom: 0.5rem;
}

.order-item-price {
    color: #8B4513;
    font-weight: bold;
}

.order-summary {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 8px;
    margin-bottom: 2rem;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.5rem;
}

.summary-row.total {
    border-top: 2px solid #8B4513;
    padding-top: 1rem;
    font-size: 1.1rem;
    font-weight: bold;
    color: #8B4513;
}

.delivery-info,
.customer-info {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 8px;
    margin-bottom: 2rem;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
}

.btn {
    display: inline-block;
    padding: 0.75rem 1.5rem;
    text-decoration: none;
    border-radius: 5px;
    font-weight: 500;
    cursor: pointer;
    border: none;
    transition: all 0.3s;
    margin-right: 1rem;
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

.text-center {
    text-align: center;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const orderId = {{ $orderId }};
    loadOrderDetail(orderId);
});

async function loadOrderDetail(orderId) {
    try {
        const response = await fetch(`/api/orders/${orderId}`);
        const data = await response.json();

        const content = document.getElementById('orderDetailContent');

        if (data.success && data.data.order) {
            const order = data.data.order;
            const statusClass = `status-${order.order_status}`;
            const statusText = getStatusText(order.order_status);

            let html = `
                <div class="order-detail-card">
                    <div class="order-header">
                        <h1 class="order-title">Đơn hàng #${order.order_code}</h1>
                        <div class="order-meta">
                            <div class="order-meta-item">
                                <strong>Ngày đặt</strong>
                                ${formatDate(order.created_at)}
                            </div>
                            <div class="order-meta-item">
                                <strong>Trạng thái</strong>
                                <span class="order-status ${statusClass}">${statusText}</span>
                            </div>
                            <div class="order-meta-item">
                                <strong>Phương thức thanh toán</strong>
                                ${order.payment_method === 'cod' ? 'Thanh toán khi nhận hàng' : 'VNPay'}
                            </div>
                            <div class="order-meta-item">
                                <strong>Trạng thái thanh toán</strong>
                                ${getPaymentStatusText(order.payment_status)}
                            </div>
                        </div>
                    </div>

                    <div class="order-body">
                        <div class="order-items">
                            <h3 class="section-title">Chi tiết sản phẩm</h3>
                            ${order.order_items ? order.order_items.map(item => `
                                <div class="order-item">
                                    <img src="${item.product?.image_url || '/images/placeholder.jpg'}"
                                         alt="${item.product_name}"
                                         class="order-item-image">
                                    <div class="order-item-details">
                                        <div class="order-item-name">${item.product_name}</div>
                                        <div>Số lượng: ${item.quantity}</div>
                                        <div>Đơn giá: ${formatPrice(item.product_price)}</div>
                                        ${item.note ? `<div><small>Ghi chú: ${item.note}</small></div>` : ''}
                                    </div>
                                    <div class="order-item-price">${formatPrice(item.subtotal)}</div>
                                </div>
                            `).join('') : ''}
                        </div>

                        <div class="order-summary">
                            <h3 class="section-title">Tóm tắt đơn hàng</h3>
                            <div class="summary-row">
                                <span>Tạm tính:</span>
                                <span>${formatPrice(order.total_amount)}</span>
                            </div>
                            <div class="summary-row">
                                <span>Phí giao hàng:</span>
                                <span>${formatPrice(order.shipping_fee)}</span>
                            </div>
                            <div class="summary-row">
                                <span>Giảm giá:</span>
                                <span>-${formatPrice(order.discount_amount)}</span>
                            </div>
                            <div class="summary-row total">
                                <span>Tổng cộng:</span>
                                <span>${formatPrice(order.final_amount)}</span>
                            </div>
                        </div>

                        <div class="delivery-info">
                            <h3 class="section-title">Thông tin giao hàng</h3>
                            <div class="info-grid">
                                <div>
                                    <strong>Người nhận:</strong> ${order.customer_name}<br>
                                    <strong>Số điện thoại:</strong> ${order.customer_phone}<br>
                                    <strong>Email:</strong> ${order.customer_email}
                                </div>
                                <div>
                                    <strong>Địa chỉ giao hàng:</strong><br>
                                    ${order.shipping_address}<br>
                                    ${order.ward ? order.ward + ', ' : ''}
                                    ${order.district ? order.district + ', ' : ''}
                                    ${order.city || ''}
                                </div>
                                ${order.delivery_date ? `
                                <div>
                                    <strong>Ngày giao hàng:</strong> ${formatDate(order.delivery_date)}<br>
                                    <strong>Giờ giao hàng:</strong> ${order.delivery_time || 'Chưa xác định'}
                                </div>
                                ` : ''}
                            </div>
                            ${order.note ? `<p><strong>Ghi chú:</strong> ${order.note}</p>` : ''}
                        </div>

                        ${canCancelOrder(order.order_status) ? `
                        <div class="text-center">
                            <button onclick="cancelOrder(${order.OrderID})" class="btn btn-secondary">
                                Hủy đơn hàng
                            </button>
                        </div>
                        ` : ''}
                    </div>
                </div>
            `;

            content.innerHTML = html;
        } else {
            content.innerHTML = `
                <div class="text-center">
                    <h3>Không tìm thấy đơn hàng</h3>
                    <p>Đơn hàng không tồn tại hoặc bạn không có quyền xem.</p>
                    <a href="{{ route('orders.index') }}" class="btn btn-primary">Quay lại</a>
                </div>
            `;
        }
    } catch (error) {
        console.error('Error loading order detail:', error);
        document.getElementById('orderDetailContent').innerHTML = `
            <div class="text-center">
                <p>Có lỗi xảy ra khi tải chi tiết đơn hàng. Vui lòng thử lại.</p>
                <button onclick="loadOrderDetail({{ $orderId }})" class="btn btn-primary">Thử lại</button>
            </div>
        `;
    }
}

async function cancelOrder(orderId) {
    const reason = prompt('Vui lòng nhập lý do hủy đơn hàng:');
    if (!reason || !reason.trim()) return;

    try {
        const response = await fetch(`/api/orders/${orderId}/cancel`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.Laravel.csrfToken
            },
            body: JSON.stringify({
                reason: reason.trim()
            })
        });

        const data = await response.json();

        if (data.success) {
            alert('Đã hủy đơn hàng thành công');
            window.location.reload();
        } else {
            alert(data.message || 'Có lỗi xảy ra');
        }
    } catch (error) {
        console.error('Error cancelling order:', error);
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

function getPaymentStatusText(status) {
    const statusMap = {
        'pending': 'Chờ thanh toán',
        'paid': 'Đã thanh toán',
        'failed': 'Thanh toán thất bại',
        'refunded': 'Đã hoàn tiền'
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
