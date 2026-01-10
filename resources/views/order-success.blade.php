@extends('layouts.app')

@section('title', 'Đặt hàng thành công - La Cuisine Ngọt')

@section('content')
<!-- Check header height, usually need padding-top if header is fixed -->
<div class="success-page-wrapper d-flex align-items-center justify-content-center" style="min-height: 80vh; padding-top: 120px;">
    <div class="container pb-5">
        <!-- Header -->
        <div class="text-center mb-4">
            <h1 class="main-title text-uppercase mb-2">ĐẶT HÀNG THÀNH CÔNG!</h1>
            <p class="order-id mb-0">Mã đơn hàng: <strong>{{ $order->order_code }}</strong></p>
        </div>

        <!-- CENTERED QR SECTION -->
        <div class="row justify-content-center mb-4">
            <div class="col-12 text-center">
                @if($order->payment_method === 'cod')
                    <!-- COD UI -->
                    <div class="payment-box p-4 bg-white shadow-sm rounded-3 d-inline-block">
                        <div class="icon-circle mb-3 mx-auto">
                            <i class="fas fa-money-bill-wave fa-2x"></i>
                        </div>
                        <h4 class="mb-2" style="color: #324F29; font-weight: 700;">Thanh toán khi nhận hàng</h4>
                        <p class="text-muted mb-3">Vui lòng chuẩn bị số tiền tương ứng</p>
                        <div class="price-tag">
                            {{ number_format($order->final_amount, 0, ',', '.') }}₫
                        </div>
                    </div>
                @else
                    <!-- Bank Transfer UI -->
                    <p class="mb-2 text-muted small"><i class="fas fa-qrcode me-2"></i>Quét mã QR để thanh toán</p>
                    
                    <!-- QR Frame Centered -->
                    <div class="qr-frame-wrapper bg-white shadow-sm d-inline-block p-2 mb-2">
                        @if(isset($qrUrl))
                            <img src="{{ $qrUrl }}" alt="VietQR" class="img-fluid qr-image mb-2">
                        @endif
                        
                        <!-- Bank Details Minimal -->
                        <div class="bank-details-mini mt-1 pt-2 border-top">
                           <div class="d-flex justify-content-between align-items-center px-2">
                                <span class="fw-bold" style="color: #004a9e;">VietQR</span>
                                <div class="text-end line-height-sm">
                                    <div class="fw-bold small text-dark">LACUISINE NGOT</div>
                                    <div class="small text-muted font-monospace">0817966088</div>
                                </div>
                           </div>
                        </div>
                    </div>

                    <!-- Status Animation DIRECTLY BELOW QR (CENTERED) -->
                    <div class="d-flex justify-content-center mt-2 w-100">
                        <div id="paymentStatusBox" class="text-center" style="min-width: 250px;">
                            <!-- State 1: Loading -->
                            <div id="loadingState">
                                <div class="d-inline-flex align-items-center justify-content-center gap-2 py-2 px-3 bg-white rounded-pill shadow-sm border">
                                    <div class="spinner-border text-warning spinner-border-sm" role="status"></div>
                                    <div class="small text-muted timer mb-0">Đang chờ thanh toán (00:15)</div>
                                </div>
                            </div>
                            
                            <!-- State 2: Success -->
                            <div id="successState" style="display: none;">
                                <div class="d-inline-flex align-items-center justify-content-center gap-2 py-2 px-3 bg-white rounded-pill shadow-sm border border-success">
                                    <i class="fas fa-check-circle text-success"></i>
                                    <div class="fw-bold text-success mb-0">Thanh toán thành công!</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- HORIZONTAL INFO SECTION (One Line) -->
        <div class="row justify-content-center text-center mb-5">
            <div class="col-md-10">
                <div class="info-line bg-white py-3 px-4 rounded-3 shadow-sm d-inline-block">
                    <div class="d-flex flex-wrap justify-content-center align-items-center gap-3 gap-md-4 text-muted small">
                        
                        <!-- Receiver -->
                        <div class="d-flex align-items-center">
                            <i class="fas fa-user-circle me-2 fs-5 text-success"></i>
                            <span>
                                <span class="fw-bold text-dark">Khách hàng:</span> {{ $order->customer_name }}
                            </span>
                        </div>

                        <div class="d-none d-md-block border-end mx-2" style="height: 20px;"></div>

                        <!-- Phone -->
                        <div class="d-flex align-items-center">
                            <i class="fas fa-phone me-2 fs-5 text-success"></i>
                            <span>
                                <span class="fw-bold text-dark">SĐT:</span> {{ $order->customer_phone }}
                            </span>
                        </div>

                        <div class="d-none d-md-block border-end mx-2" style="height: 20px;"></div>

                         <!-- Address -->
                         <div class="d-flex align-items-center">
                            <i class="fas fa-map-marker-alt me-2 fs-5 text-success"></i>
                            <span class="text-truncate" style="max-width: 250px;">
                                <span class="fw-bold text-dark">Địa chỉ:</span> {{ $order->shipping_address }}
                            </span>
                        </div>
                        
                        <div class="d-none d-md-block border-end mx-2" style="height: 20px;"></div>

                        <!-- Time -->
                        <div class="d-flex align-items-center">
                            <i class="fas fa-clock me-2 fs-5 text-success"></i>
                             <span>
                                <span class="fw-bold text-dark">Nhận:</span> 
                                {{ $order->delivery_time }} - {{ $order->delivery_date ? \Carbon\Carbon::parse($order->delivery_date)->format('d/m/Y') : '' }}
                            </span>
                        </div>

                    </div>
                    
                    @if($order->customer_email)
                    <div class="text-center mt-2 pt-2 border-top small text-muted">
                        Email: {{ $order->customer_email }}
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="text-center pb-4">
            <a href="{{ route('home') }}" class="btn btn-outline-custom rounded-pill px-4 py-2 me-3">
                <i class="fas fa-home me-2"></i>Về trang chủ
            </a>
            <a href="{{ route('order.history') }}" class="btn btn-custom rounded-pill px-4 py-2">
                <i class="fas fa-receipt me-2"></i>Lịch sử đơn hàng
            </a>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Roboto:wght@300;400;500;700&display=swap');

    /* Force background color */
    body {
        background-color: #FAF8F2 !important;
        font-family: 'Roboto', sans-serif;
    }

    .main-title {
        color: #324F29;
        font-family: 'Playfair Display', serif;
        font-size: 2.2rem;
        margin-top: 10px;
    }

    .order-id {
        color: #888;
        font-size: 0.95rem;
    }

    /* QR Style */
    .qr-frame-wrapper {
        border: 1px solid #ddd;
        border-radius: 8px;
        max-width: 260px; /* Even smaller for better centering */
        margin: 0 auto;
    }
    .qr-image {
        border-radius: 4px;
        width: 100%;
        display: block;
    }
    
    /* Buttons */
    .btn-custom {
        background-color: #324F29 !important;
        border: 1px solid #324F29 !important;
        color: #ffffff !important;
        font-weight: 500;
    }
    .btn-custom:hover {
        background-color: #263e20 !important;
        transform: translateY(-1px);
    }

    .btn-outline-custom {
        background-color: transparent !important;
        border: 1px solid #324F29 !important;
        color: #324F29 !important;
        font-weight: 500;
        background: #fff !important; /* White bg for contrast */
    }
    .btn-outline-custom:hover {
        background-color: #324F29 !important;
        color: #ffffff !important;
    }

    /* Helpers */
    .line-height-sm { line-height: 1.2; }
    .text-success { color: #324F29 !important; }

    /* Confetti */
    .confetti {
        position: fixed;
        width: 8px; height: 8px;
        z-index: 9999;
        animation: fall 3s linear forwards;
    }
    @keyframes fall {
        to { transform: translateY(100vh) rotate(720deg); }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const loadingState = document.getElementById('loadingState');
        const successState = document.getElementById('successState');
        
        if (loadingState && successState) {
            const timerEl = loadingState.querySelector('.timer');
            let timeLeft = 15;
            
            const interval = setInterval(() => {
                timeLeft--;
                let timeStr = timeLeft < 10 ? '0' + timeLeft : timeLeft;
                timerEl.textContent = 'Đang chờ thanh toán (00:' + timeStr + ')';
                if (timeLeft <= 0) clearInterval(interval);
            }, 1000);

            setTimeout(() => {
                clearInterval(interval);
                loadingState.style.display = 'none';
                successState.style.display = 'block';
                fireConfetti();
            }, 3000);
        }
    });

    function fireConfetti() {
        const colors = ['#C4A574', '#324F29', '#888'];
        for (let i = 0; i < 40; i++) {
            const conf = document.createElement('div');
            conf.classList.add('confetti');
            conf.style.left = Math.random() * 100 + 'vw';
            conf.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
            conf.style.top = '-10px';
            conf.style.animationDuration = (Math.random() * 2 + 1.5) + 's';
            document.body.appendChild(conf);
            setTimeout(() => conf.remove(), 4000);
        }
    }
</script>
@endpush
