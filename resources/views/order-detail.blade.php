@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng - La Cuisine Ngọt')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="mb-4">
                <a href="{{ route('order.history') }}" class="btn btn-back">
                    <i class="fas fa-arrow-left"></i> Quay lại danh sách đơn hàng
                </a>
            </div>

            @if(isset($order))
                <div class="order-detail-card">
                    <div class="order-header">
                        <h1 class="order-title">Đơn hàng #{{ $order->order_code }}</h1>
                        <div class="order-meta">
                            <div class="order-meta-item">
                                <strong>Ngày đặt</strong>
                                {{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y') }}
                            </div>
                            <div class="order-meta-item">
                                <strong>Trạng thái</strong>
                                @php
                                    $statusConfig = [
                                        'pending' => ['color' => '#FFA500', 'icon' => 'fa-clock', 'text' => 'Chờ xác nhận', 'bg' => '#FFF4E6'],
                                        'order_received' => ['color' => '#00BCD4', 'icon' => 'fa-check-circle', 'text' => 'Đã nhận đơn', 'bg' => '#E0F7FA'],
                                        'preparing' => ['color' => '#9C27B0', 'icon' => 'fa-utensils', 'text' => 'Đang chuẩn bị', 'bg' => '#F3E5F5'],
                                        'delivering' => ['color' => '#2196F3', 'icon' => 'fa-shipping-fast', 'text' => 'Đang giao hàng', 'bg' => '#E3F2FD'],
                                        'delivery_successful' => ['color' => '#4CAF50', 'icon' => 'fa-check-double', 'text' => 'Giao thành công', 'bg' => '#E8F5E9'],
                                        'delivery_failed' => ['color' => '#F44336', 'icon' => 'fa-times-circle', 'text' => 'Giao thất bại', 'bg' => '#FFEBEE'],
                                        'cancelled' => ['color' => '#757575', 'icon' => 'fa-ban', 'text' => 'Đã hủy', 'bg' => '#F5F5F5']
                                    ];
                                    $status = $order->order_status;
                                    $config = $statusConfig[$status] ?? ['color' => '#757575', 'icon' => 'fa-question', 'text' => $status, 'bg' => '#F5F5F5'];
                                @endphp
                                <div class="status-badge-detail" style="background: {{ $config['bg'] }}; border-left: 4px solid {{ $config['color'] }}">
                                    <i class="fas {{ $config['icon'] }}" style="color: {{ $config['color'] }}"></i>
                                    <span style="color: {{ $config['color'] }}">{{ $config['text'] }}</span>
                                </div>
                            </div>
                            <div class="order-meta-item">
                                <strong>Phương thức thanh toán</strong>
                                {{ $order->payment_method === 'cod' ? 'Thanh toán khi nhận hàng' : 'VNPay' }}
                            </div>
                            <div class="order-meta-item">
                                <strong>Trạng thái thanh toán</strong>
                                @php
                                    $paymentStatusMap = [
                                        'pending' => 'Chờ thanh toán',
                                        'paid' => 'Đã thanh toán',
                                        'failed' => 'Thanh toán thất bại',
                                        'refunded' => 'Đã hoàn tiền'
                                    ];
                                @endphp
                                {{ $paymentStatusMap[$order->payment_status] ?? $order->payment_status }}
                            </div>
                        </div>
                    </div>

                    <div class="order-body">
                        <div class="order-items">
                            <h3 class="section-title">Chi tiết sản phẩm</h3>
                            @foreach($order->orderItems as $item)
                                <div class="order-item">
                                    <img src="{{ $item->product->image_url ?? '/images/placeholder.jpg' }}"
                                         alt="{{ $item->product_name }}"
                                         class="order-item-image">
                                    <div class="order-item-details">
                                        <div class="order-item-name">{{ $item->product_name }}</div>
                                        <div>Số lượng: {{ $item->quantity }}</div>
                                        <div>Đơn giá: {{ number_format($item->product_price, 0, ',', '.') }} ₫</div>
                                        @if($item->note)
                                            <div><small>Ghi chú: {{ $item->note }}</small></div>
                                        @endif
                                    </div>
                                    <div class="order-item-price">{{ number_format($item->subtotal, 0, ',', '.') }} ₫</div>
                                </div>
                            @endforeach
                        </div>

                        <div class="order-summary">
                            <h3 class="section-title">Tóm tắt đơn hàng</h3>
                            <div class="summary-row">
                                <span>Tạm tính:</span>
                                <span>{{ number_format($order->total_amount, 0, ',', '.') }} ₫</span>
                            </div>
                            <div class="summary-row">
                                <span>Phí giao hàng:</span>
                                <span>{{ number_format($order->shipping_fee, 0, ',', '.') }} ₫</span>
                            </div>
                            <div class="summary-row">
                                <span>Giảm giá:</span>
                                <span>-{{ number_format($order->discount_amount, 0, ',', '.') }} ₫</span>
                            </div>
                            <div class="summary-row total">
                                <span>Tổng cộng:</span>
                                <span>{{ number_format($order->final_amount, 0, ',', '.') }} ₫</span>
                            </div>
                        </div>

                        <div class="delivery-info">
                            <h3 class="section-title">Thông tin giao hàng</h3>
                            <div class="info-grid">
                                <div>
                                    <strong>Người nhận:</strong> {{ $order->customer_name }}<br>
                                    <strong>Số điện thoại:</strong> {{ $order->customer_phone }}<br>
                                    <strong>Email:</strong> {{ $order->customer_email }}
                                </div>
                                <div>
                                    <strong>Địa chỉ giao hàng:</strong><br>
                                    {{ $order->shipping_address }}<br>
                                    {{ $order->ward ? $order->ward . ', ' : '' }}
                                    {{ $order->district ? $order->district . ', ' : '' }}
                                    {{ $order->city ?? '' }}
                                </div>
                                @if($order->delivery_date)
                                <div>
                                    <strong>Ngày giao hàng:</strong> {{ \Carbon\Carbon::parse($order->delivery_date)->format('d/m/Y') }}<br>
                                    <strong>Giờ giao hàng:</strong> {{ $order->delivery_time ?? 'Chưa xác định' }}
                                </div>
                                @endif
                            </div>
                            @if($order->note)
                                <p class="mt-2"><strong>Ghi chú:</strong> {{ $order->note }}</p>
                            @endif
                        </div>

                        @if(in_array($order->order_status, ['delivery_successful', 'delivery_failed']))
                        <div class="complaint-section">
                            <h3 class="section-title">Gửi Khiếu Nại</h3>
                            <form id="complaintForm" class="complaint-form">
                                <div class="form-group">
                                    <label for="complaint_type">Loại khiếu nại <span class="required">*</span></label>
                                    <select id="complaint_type" name="complaint_type" class="form-control" required>
                                        <option value="">-- Chọn loại khiếu nại --</option>
                                        <option value="product_quality">Chất lượng sản phẩm</option>
                                        <option value="delivery">Vấn đề giao hàng</option>
                                        <option value="service">Dịch vụ</option>
                                        <option value="other">Khác</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="complaint_title">Tiêu đề <span class="required">*</span></label>
                                    <input type="text" id="complaint_title" name="title" class="form-control" 
                                           placeholder="Nhập tiêu đề khiếu nại" maxlength="200" required>
                                    <small class="form-text">Tối đa 200 ký tự</small>
                                </div>
                                <div class="form-group">
                                    <label for="complaint_content">Nội dung <span class="required">*</span></label>
                                    <textarea id="complaint_content" name="content" class="form-control" 
                                              placeholder="Mô tả chi tiết vấn đề của bạn" 
                                              rows="5" maxlength="1000" required></textarea>
                                    <small class="form-text">Tối đa 1000 ký tự</small>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary" id="submitComplaintBtn">
                                        <i class="fas fa-paper-plane"></i> Gửi Khiếu Nại
                                    </button>
                                </div>
                            </form>
                        </div>
                        @endif

                        @if(in_array($order->order_status, ['pending', 'order_received']))
                        <div class="text-center">
                            <button onclick="cancelOrder('{{ $order->OrderID }}')" class="btn btn-secondary">
                                Hủy đơn hàng
                            </button>
                        </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="text-center">
                    <h3>Không tìm thấy đơn hàng</h3>
                    <p>Đơn hàng không tồn tại hoặc bạn không có quyền xem.</p>
                    <a href="{{ route('order.history') }}" class="btn btn-primary">Quay lại</a>
                </div>
            @endif
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
    background: #c4a574;
    color: white;
    padding: 2rem;
}

.order-title {
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
    font-weight: 700;
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
    color: #c4a574;
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
    border-top: 2px solid #c4a574;
    padding-top: 1rem;
    font-size: 1.1rem;
    font-weight: bold;
    color: #c4a574;
}

.delivery-info {
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
    background: #c4a574;
    color: white;
}

.btn-primary:hover {
    background: #c4a574;
}

.btn-back {
    background: linear-gradient(135deg, #324F29 0%, #4a7338 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(50, 79, 41, 0.2);
}

.btn-back:hover {
    background: linear-gradient(135deg, #263e20 0%, #324F29 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(50, 79, 41, 0.3);
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #545b62;
}

.status-badge-detail {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-weight: 600;
    margin-top: 0.5rem;
}

.status-badge-detail i {
    font-size: 1.1rem;
}

}

.text-center {
    text-align: center;
}

/* Complaint Form Styles */
.complaint-section {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 8px;
    margin-bottom: 2rem;
}

.complaint-form {
    max-width: 600px;
    margin: 0 auto;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #333;
}

.required {
    color: #dc3545;
}

.form-control {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 1rem;
    transition: border-color 0.3s;
}

.form-control:focus {
    outline: none;
    border-color: #c4a574;
    box-shadow: 0 0 0 0.2rem rgba(196, 165, 116, 0.25);
}

.form-text {
    display: block;
    margin-top: 0.25rem;
    font-size: 0.875rem;
    color: #6c757d;
}

.btn-primary i {
    margin-right: 0.5rem;
}

.btn-primary:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}
</style>
@endpush

@push('scripts')
<script>
// Complaint Form Handler
document.addEventListener('DOMContentLoaded', function() {
    const complaintForm = document.getElementById('complaintForm');
    
    if (complaintForm) {
        complaintForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const orderId = '{{ $order->OrderID ?? '' }}';
            const submitBtn = document.getElementById('submitComplaintBtn');
            const originalBtnText = submitBtn.innerHTML;
            
            // Validate form
            const complaintType = document.getElementById('complaint_type').value;
            const title = document.getElementById('complaint_title').value.trim();
            const content = document.getElementById('complaint_content').value.trim();
            
            if (!complaintType || !title || !content) {
                alert('Vui lòng điền đầy đủ thông tin');
                return;
            }
            
            // Disable button and show loading
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang gửi...';
            
            try {
                const response = await fetch(`/orders/${orderId}/submit-complaint`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        complaint_type: complaintType,
                        title: title,
                        content: content
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alert(data.message);
                    // Reset form
                    complaintForm.reset();
                } else {
                    alert(data.message || 'Có lỗi xảy ra khi gửi khiếu nại');
                }
            } catch (error) {
                console.error('Error submitting complaint:', error);
                alert('Có lỗi xảy ra. Vui lòng thử lại sau.');
            } finally {
                // Re-enable button
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            }
        });
    }
});

// Cancel Order Function
async function cancelOrder(orderId) {
    const reason = prompt('Vui lòng nhập lý do hủy đơn hàng:');
    if (!reason || !reason.trim()) return;

    try {
        const response = await fetch(`/api/orders/${orderId}/cancel`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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
</script>
@endpush
