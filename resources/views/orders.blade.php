@extends('layouts.app')

@section('title', 'Thanh toán và giao hàng')

@section('content')
<div class="container py-5">
    <h1 class="page-title">Thanh toán và giao hàng</h1>
    
    <div class="checkout-wrapper">
        <form action="{{ route('api.orders.store') }}" method="POST" id="checkoutForm">
            @csrf
            <input type="hidden" name="is_buy_now" value="{{ $isBuyNow ? '1' : '0' }}">
            <div class="row">
                <!-- Cột 1: Thông tin người dùng -->
                <div class="col-md-6 info-column pe-md-5">
                    <h3 class="column-title">Thông tin người dùng</h3>
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Họ tên *</label>
                        <input type="text" name="customer_name" class="form-control" value="{{ Auth::user()->full_name }}" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Số điện thoại *</label>
                        <input type="tel" name="customer_phone" class="form-control" value="{{ Auth::user()->phone ?? '' }}" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Địa chỉ mail*</label>
                        <input type="email" name="customer_email" class="form-control" value="{{ Auth::user()->email }}" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Ghi chú đơn hàng</label>
                        <textarea name="note" class="form-control" rows="4" placeholder="Ví dụ: Giao giờ hành chính, ..."></textarea>
                        <small class="text-muted fst-italic mt-1 d-block">Ghi chú này sẽ được chuyển đến nhân viên xử lý đơn hàng</small>
                    </div>
                </div>
                
                <!-- Cột 2: Phương thức nhận hàng và Đơn hàng -->
                <div class="col-md-6 delivery-column ps-md-4">
                    <h3 class="column-title">Phương thức nhận hàng</h3>
                    
                    <div class="delivery-methods mb-4">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="delivery_method" id="pickup" value="pickup" checked>
                            <label class="form-check-label fw-bold" for="pickup">
                                Nhận trực tiếp tại cửa hàng
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="delivery_method" id="delivery" value="delivery">
                            <label class="form-check-label" for="delivery">
                                Giao hàng tận nơi
                            </label>
                        </div>
                    </div>
                    
                    <!-- Shipping Address -->
                    <div id="shipping-address-group" class="mb-3">
                        <div class="form-group mb-3">
                            <label class="form-label">Địa chỉ nhận hàng *</label>
                            <input type="text" name="shipping_address" class="form-control" required placeholder="Số nhà, tên đường...">
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">Phường/Xã</label>
                                <input type="text" name="ward" class="form-control" placeholder="Nhập phường/xã...">
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Quận/Huyện</label>
                                <input type="text" name="district" class="form-control" placeholder="Nhập quận/huyện...">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Thành phố</label>
                        <input type="text" name="city" class="form-control" value="TP. Hồ Chí Minh">
                    </div>
                    
                    <div class="form-group mb-4">
                        <label class="form-label">Thời gian nhận bánh (sau ít nhất 2 giờ)*</label>
                        <div class="position-relative">
                            <input type="datetime-local" name="delivery_time" class="form-control" required>
                        </div>
                    </div>
                    
                    <!-- Payment Method -->
                    <h3 class="column-title mt-4">Phương thức thanh toán</h3>
                    
                    <div class="payment-methods mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" 
                                   id="payment_bank" value="bank_transfer" checked>
                            <label class="form-check-label fw-bold" for="payment_bank">
                                🏦 Chuyển khoản ngân hàng (Quét mã QR)
                            </label>
                        </div>
                    </div>
                    
                    <!-- Đơn hàng của bạn -->
                </div>
            </div>
            
            <!-- Hàng mới: Đơn hàng của bạn (Full width) -->
            <div class="row">
                <div class="col-12">
                    <div class="order-summary-section mt-5">
                        <h3 class="summary-title text-center mb-3">Đơn hàng của bạn</h3>
                        
                        <div class="order-table mb-3">
                            <div class="d-flex order-header p-2">
                                <span class="flex-grow-1 fw-bold ps-2">Sản phẩm</span>
                                <span class="fw-bold pe-2">Tạm tính</span>
                            </div>
                            @foreach($items as $item)
                            <div class="d-flex order-row p-3 border-bottom align-items-center">
                                @if(isset($item['image']) && $item['image'])
                                <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}" class="order-item-img me-2">
                                @endif
                                <div class="flex-grow-1">
                                    <span class="item-name text-muted">{{ $item['name'] }}</span> 
                                    <span class="item-qty fw-bold">× {{ $item['quantity'] }}</span>
                                </div>
                                <span class="item-price fw-bold">{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }} ₫</span>
                                <input type="hidden" name="items[{{ $loop->index }}][id]" value="{{ $item['id'] }}">
                                <input type="hidden" name="items[{{ $loop->index }}][quantity]" value="{{ $item['quantity'] }}">
                                <input type="hidden" name="items[{{ $loop->index }}][price]" value="{{ $item['price'] }}">
                            </div>
                            @endforeach
                            
                            @if(isset($giftItem) && $giftItem)
                            <div class="d-flex order-row p-3 border-bottom bg-light align-items-center">
                                @if(isset($giftItem['image']) && $giftItem['image'])
                                <img src="{{ asset($giftItem['image']) }}" alt="{{ $giftItem['name'] }}" class="order-item-img me-2">
                                @endif
                                <div class="flex-grow-1">
                                    <span class="item-name text-success">🎁 {{ $giftItem['name'] }}</span> 
                                    <span class="item-qty fw-bold">× {{ $giftItem['quantity'] }}</span>
                                    <small class="d-block text-muted fst-italic mt-1">Quà tặng khi mua Entremet</small>
                                </div>
                                <span class="item-price fw-bold text-success">Miễn phí</span>
                                <input type="hidden" name="gift_items[0][id]" value="{{ $giftItem['id'] }}">
                                <input type="hidden" name="gift_items[0][quantity]" value="{{ $giftItem['quantity'] }}">
                            </div>
                            @endif
                        </div>
                        
                        <div class="form-group mb-4">
                            <label class="form-label fw-bold">Mã khuyến mãi</label>
                            <select name="promotion_code" id="promotionSelect" class="form-select">
                                <option value="">--Chọn mã khuyến mãi--</option>
                                @foreach($promotions as $promo)
                                    <option value="{{ $promo->promotion_code }}" 
                                            data-type="{{ $promo->promotion_type }}"
                                            data-value="{{ $promo->discount_value }}"
                                            data-min="{{ $promo->min_order_value }}"
                                            data-max="{{ $promo->max_discount ?? 0 }}">
                                        {{ $promo->promotion_name }}
                                        @if($promo->promotion_type === 'percent')
                                            (-{{ $promo->discount_value }}%)
                                        @elseif($promo->promotion_type === 'fixed_amount')
                                            (-{{ number_format($promo->discount_value, 0, ',', '.') }}₫)
                                        @elseif($promo->promotion_type === 'free_shipping')
                                            (Miễn phí ship)
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="total-section mt-4 border-top pt-3">
                            <div class="price-breakdown">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-secondary">Tạm tính</span>
                                    <span class="fw-bold">{{ number_format($subtotal, 0, ',', '.') }} ₫</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-secondary">VAT (8%)</span>
                                    <span class="fw-bold">{{ number_format($vat, 0, ',', '.') }} ₫</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-secondary">Phí vận chuyển</span>
                                    <span class="fw-bold">Miễn phí</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-secondary">Giảm giá</span>
                                    <span class="fw-bold text-danger discount-amount">0 ₫</span>
                                </div>
                                <div class="d-flex justify-content-between mt-3">
                                    <span class="fw-bold fs-5 text-dark">Tổng tiền</span>
                                    <span class="fw-bold fs-5 text-success total-amount">{{ number_format($total, 0, ',', '.') }} ₫</span>
                                </div>
                            </div>
                            
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-checkout">Thanh toán</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
.page-title {
    color: #324F29;
    font-weight: 700;
    margin-bottom: 30px;
    font-size: 1.8rem;
}

.checkout-wrapper {
    border: 1px solid #7FA086; /* Green border */
    border-radius: 15px;
    padding: 40px;
    background: white;
}

.column-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #324F29;
    margin-bottom: 25px;
    text-align: center;
}

.form-label {
    font-weight: 600;
    color: #222;
    margin-bottom: 8px;
    font-size: 0.95rem;
}

.form-control, .form-select {
    border-radius: 6px;
    border: 1px solid #ced4da;
    padding: 10px 12px;
}

.form-control:focus {
    border-color: #324F29;
    box-shadow: 0 0 0 0.2rem rgba(50, 79, 41, 0.25);
}

.order-summary-section .summary-title {
    font-size: 1rem;
    font-weight: 700;
    color: #324F29;
}

.order-header {
    background-color: #9CAFA0; /* Muted green for header */
    color: white; /* Or dark text depending on contrast */
    border-radius: 4px 4px 0 0;
}
.order-header span {
    color: #333; /* Dark text on gray-green background */
}
.order-table {
    border: 1px solid #eee;
    border-radius: 4px;
}

.btn-checkout {
    background-color: #324F29;
    color: white;
    border: none;
    border-radius: 50px; /* Pill shape */
    padding: 10px 30px;
    font-weight: 600;
    white-space: nowrap;
    transition: all 0.3s;
}

.btn-checkout:hover {
    background-color: #263e20;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

/* Specific styling for the total section layout */
.total-section {
    position: relative;
}

/* Order item images */
.order-item-img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #dee2e6;
    flex-shrink: 0; /* Don't shrink image */
}

.order-row {
    display: flex;
    align-items: center;
    flex-wrap: nowrap; /* Prevent wrapping */
}

.order-row .flex-grow-1 {
    white-space: nowrap; /* Keep text on one line */
    overflow: hidden;
    text-overflow: ellipsis;
    min-width: 0; /* Allow flexbox to shrink */
}

.order-row .item-price {
    flex-shrink: 0; /* Don't shrink price */
    white-space: nowrap;
    margin-left: 15px;
}

.action-area {
    display: flex;
    align-items: center; /* Align with top rows */
    padding-left: 20px;
}
</style>
@endpush

@push('scripts')
<script>
    // Set min datetime to 2 hours from now
    const now = new Date();
    now.setHours(now.getHours() + 2);
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    
    // Format: YYYY-MM-DDTHH:mm
    const minDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;
    document.querySelector('input[name="delivery_time"]').min = minDateTime;

    // Store original values
    /* eslint-disable */
    const originalSubtotal = <?php echo json_encode($subtotal); ?>;
    const originalVat = <?php echo json_encode($vat); ?>;
    /* eslint-enable */

    // Listen for promotion selection
    document.getElementById('promotionSelect')?.addEventListener('change', calculateTotal);

    function calculateTotal() {
        const select = document.getElementById('promotionSelect');
        const selectedOption = select.options[select.selectedIndex];
        
        let discount = 0;
        let discountText = '0 ₫';
        
        if (selectedOption.value) {
            const type = selectedOption.dataset.type;
            const value = parseFloat(selectedOption.dataset.value);
            const minOrder = parseFloat(selectedOption.dataset.min || 0);
            const maxDiscount = parseFloat(selectedOption.dataset.max || 0);
            
            // Check minimum order value
            if (originalSubtotal < minOrder) {
                alert(`Đơn hàng tối thiểu ${minOrder.toLocaleString('vi-VN')}₫ để sử dụng mã này`);
                select.value = '';
                return;
            }
            
            // Calculate discount based on type
            if (type === 'percent') {
                discount = originalSubtotal * (value / 100);
                if (maxDiscount > 0) {
                    discount = Math.min(discount, maxDiscount);
                }
            } else if (type === 'fixed_amount') {
                discount = value;
            } else if (type === 'free_shipping') {
                // Free shipping doesn't affect total in this implementation
                discount = 0;
            }
            
            discountText = discount.toLocaleString('vi-VN') + ' ₫';
        }
        
        const total = originalSubtotal + originalVat - discount;
        
        // Update display
        document.querySelector('.discount-amount').textContent = discountText;
        document.querySelector('.total-amount').textContent = total.toLocaleString('vi-VN') + ' ₫';
    }

    // Handle Picking/Delivery toggle
    const deliveryMethodRadios = document.querySelectorAll('input[name="delivery_method"]');
    const shippingGroup = document.getElementById('shipping-address-group');
    const addrInput = document.querySelector('input[name="shipping_address"]');
    
    function toggleShipping() {
        const isDelivery = document.getElementById('delivery').checked;
        if (isDelivery) {
            shippingGroup.style.display = 'block';
            if (addrInput.value === 'Nhận tại cửa hàng') addrInput.value = '';
        } else {
            shippingGroup.style.display = 'none';
            addrInput.value = 'Nhận tại cửa hàng';
        }
    }
    
    // Init and listen
    deliveryMethodRadios.forEach(r => r.addEventListener('change', toggleShipping));
    toggleShipping(); // Run on load

    document.getElementById('checkoutForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang xử lý...';
        
        try {
            const formData = new FormData(this);
            
            const response = await fetch('{{ route("orders.store") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                },
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                window.location.href = result.data.redirect;
            } else {
                let msg = result.message || 'Có lỗi xảy ra khi đặt hàng';
                if (result.errors) {
                    msg += '\n' + Object.values(result.errors).flat().join('\n');
                }
                alert(msg);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Có lỗi hệ thống. Vui lòng thử lại.');
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        }
    });
</script>
@endpush
