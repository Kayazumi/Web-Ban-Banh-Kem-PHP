@extends('layouts.app')

@section('title', 'Đơn hàng của tôi - La Cuisine Ngọt')

@section('content')
<div class="orders-page-wrapper">
    <div class="container py-5">
        <!-- Page Header -->
        <div class="page-header">
            <div class="header-icon">
                <i class="fas fa-receipt"></i>
            </div>
            <h1 class="page-title">Đơn hàng của tôi</h1>
            <p class="page-subtitle">Quản lý và theo dõi đơn hàng của bạn</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-11">
                @if($orders->count() > 0)
                    <!-- Orders Grid -->
                    <div class="orders-grid">
                        @foreach($orders as $order)
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
                            
                            <div class="order-card">
                                <!-- Order Header -->
                                <div class="order-card-header">
                                    <div class="order-code">
                                        <i class="fas fa-hashtag"></i>
                                        <span>{{ $order->order_code }}</span>
                                    </div>
                                    <div class="order-date">
                                        <i class="far fa-calendar-alt"></i>
                                        <span>{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}</span>
                                    </div>
                                </div>

                                <!-- Order Body -->
                                <div class="order-card-body">
                                    <!-- Status Badge -->
                                    <div class="order-status-badge" style="background: {{ $config['bg'] }}; border-left: 4px solid {{ $config['color'] }}">
                                        <i class="fas {{ $config['icon'] }}" style="color: {{ $config['color'] }}"></i>
                                        <span style="color: {{ $config['color'] }}">{{ $config['text'] }}</span>
                                    </div>

                                    <!-- Order Amount -->
                                    <div class="order-amount">
                                        <span class="amount-label">Tổng tiền:</span>
                                        <span class="amount-value">{{ number_format($order->final_amount, 0, ',', '.') }} ₫</span>
                                    </div>
                                </div>

                                <!-- Order Footer -->
                                <div class="order-card-footer">
                                    <a href="{{ route('order.details', $order->OrderID) }}" class="btn-view-details">
                                        <i class="fas fa-eye"></i>
                                        <span>Xem chi tiết</span>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($orders->hasPages())
                        <div class="pagination-wrapper">
                            <nav class="custom-pagination" role="navigation">
                                {{-- Previous Page Link --}}
                                @if ($orders->onFirstPage())
                                    <span class="page-btn disabled">
                                        <i class="fas fa-chevron-left"></i>
                                        <span>Trước</span>
                                    </span>
                                @else
                                    <a href="{{ $orders->previousPageUrl() }}" class="page-btn prev-btn">
                                        <i class="fas fa-chevron-left"></i>
                                        <span>Trước</span>
                                    </a>
                                @endif

                                {{-- Page Numbers --}}
                                <div class="page-numbers">
                                    @foreach ($orders->getUrlRange(1, $orders->lastPage()) as $page => $url)
                                        @if ($page == $orders->currentPage())
                                            <span class="page-num active">{{ $page }}</span>
                                        @else
                                            <a href="{{ $url }}" class="page-num">{{ $page }}</a>
                                        @endif
                                    @endforeach
                                </div>

                                {{-- Next Page Link --}}
                                @if ($orders->hasMorePages())
                                    <a href="{{ $orders->nextPageUrl() }}" class="page-btn next-btn">
                                        <span>Tiếp</span>
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                @else
                                    <span class="page-btn disabled">
                                        <span>Tiếp</span>
                                        <i class="fas fa-chevron-right"></i>
                                    </span>
                                @endif
                            </nav>

                            {{-- Page Info --}}
                            <div class="pagination-info">
                                <i class="fas fa-info-circle"></i>
                                Trang {{ $orders->currentPage() }} / {{ $orders->lastPage() }}
                                <span class="divider">•</span>
                                Tổng {{ $orders->total() }} đơn hàng
                            </div>
                        </div>
                    @endif
                @else
                    <!-- Empty State -->
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                        <h3 class="empty-state-title">Chưa có đơn hàng nào</h3>
                        <p class="empty-state-text">Hãy khám phá cửa hàng và chọn cho mình những chiếc bánh ngon nhé!</p>
                        <a href="{{ route('products') }}" class="btn-start-shopping">
                            <i class="fas fa-shopping-cart"></i>
                            Khám phá ngay
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .orders-page-wrapper {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: calc(100vh - 80px);
    }

    /* Page Header */
    .page-header {
        text-align: center;
        margin-bottom: 3rem;
        position: relative;
    }

    .header-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #324F29 0%, #4a7338 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        box-shadow: 0 10px 30px rgba(50, 79, 41, 0.3);
    }
    
    .header-icon i {
        font-size: 2.5rem;
        color: white;
    }

    .page-title {
        color: #1a3020;
        font-weight: 700;
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .page-subtitle {
        color: #5a6c57;
        font-size: 1.1rem;
        margin: 0;
    }

    /* Orders Grid */
    .orders-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    /* Order Card */
    .order-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border: 1px solid rgba(50, 79, 41, 0.1);
    }

    .order-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(50, 79, 41, 0.15);
    }

    /* Order Card Header */
    .order-card-header {
        background: linear-gradient(135deg, #324F29 0%, #4a7338 100%);
        padding: 1.25rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .order-code {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: white;
        font-weight: 700;
        font-size: 1.1rem;
        font-family: 'Courier New', monospace;
    }

    .order-code i {
        color: #a8d4a0;
    }

    .order-date {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.9rem;
    }

    .order-date i {
        color: #a8d4a0;
    }

    /* Order Card Body */
    .order-card-body {
        padding: 1.5rem;
    }

    /* Status Badge */
    .order-status-badge {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        border-radius: 10px;
        margin-bottom: 1.25rem;
        font-weight: 600;
        font-size: 1rem;
    }

    .order-status-badge i {
        font-size: 1.2rem;
    }

    /* Order Amount */
    .order-amount {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 10px;
        border-left: 4px solid #324F29;
    }

    .amount-label {
        color: #6c757d;
        font-weight: 500;
        font-size: 0.95rem;
    }

    .amount-value {
        color: #324F29;
        font-weight: 700;
        font-size: 1.3rem;
    }

    /* Order Card Footer */
    .order-card-footer {
        padding: 1rem 1.5rem;
        background: #f8f9fa;
        border-top: 1px solid #e9ecef;
    }

    .btn-view-details {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        width: 100%;
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, #324F29 0%, #4a7338 100%);
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(50, 79, 41, 0.2);
    }

    .btn-view-details:hover {
        background: linear-gradient(135deg, #263e20 0%, #324F29 100%);
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 6px 16px rgba(50, 79, 41, 0.3);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .empty-state-icon {
        width: 120px;
        height: 120px;
        background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 2rem;
    }

    .empty-state-icon i {
        font-size: 3.5rem;
        color: #adb5bd;
    }

    .empty-state-title {
        color: #495057;
        font-weight: 700;
        font-size: 1.8rem;
        margin-bottom: 1rem;
    }

    .empty-state-text {
        color: #6c757d;
        font-size: 1.1rem;
        margin-bottom: 2rem;
        max-width: 500px;
        margin-left: auto;
        margin-right: auto;
    }

    .btn-start-shopping {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem 2.5rem;
        background: linear-gradient(135deg, #324F29 0%, #4a7338 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1.1rem;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 6px 20px rgba(50, 79, 41, 0.3);
    }

    .btn-start-shopping:hover {
        background: linear-gradient(135deg, #263e20 0%, #324F29 100%);
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(50, 79, 41, 0.4);
    }

    /* Pagination */
    .pagination-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1.5rem;
        margin-top: 3rem;
    }

    .custom-pagination {
        display: flex;
        align-items: center;
        gap: 1rem;
        background: white;
        padding: 1rem 1.5rem;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    /* Page Buttons (Previous/Next) */
    .page-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, #324F29 0%, #4a7338 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.95rem;
        text-decoration: none;
        transition: all 0.3s ease;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(50, 79, 41, 0.2);
    }

    .page-btn:hover {
        background: linear-gradient(135deg, #263e20 0%, #324F29 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(50, 79, 41, 0.3);
        color: white;
    }

    .page-btn.disabled {
        background: #e9ecef;
        color: #adb5bd;
        cursor: not-allowed;
        box-shadow: none;
    }

    .page-btn.disabled:hover {
        transform: none;
        box-shadow: none;
    }

    .page-btn i {
        font-size: 0.85rem;
    }

    /* Page Numbers Container */
    .page-numbers {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0 1rem;
    }

    /* Individual Page Numbers */
    .page-num {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 42px;
        height: 42px;
        padding: 0.5rem;
        background: #f8f9fa;
        color: #324F29;
        border: 2px solid transparent;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.95rem;
        text-decoration: none;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .page-num:hover {
        background: linear-gradient(135deg, #f0f7ee 0%, #e8f5e9 100%);
        border-color: #324F29;
        color: #324F29;
        transform: scale(1.1);
    }

    .page-num.active {
        background: linear-gradient(135deg, #324F29 0%, #4a7338 100%);
        color: white;
        border-color: #324F29;
        box-shadow: 0 4px 12px rgba(50, 79, 41, 0.25);
        transform: scale(1.05);
    }

    /* Pagination Info */
    .pagination-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1.5rem;
        background: white;
        color: #6c757d;
        border-radius: 12px;
        font-size: 0.95rem;
        font-weight: 500;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
    }

    .pagination-info i {
        color: #324F29;
        font-size: 1rem;
    }

    .pagination-info .divider {
        color: #dee2e6;
        margin: 0 0.25rem;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .orders-grid {
            grid-template-columns: 1fr;
        }

        .page-title {
            font-size: 2rem;
        }

        .header-icon {
            width: 60px;
            height: 60px;
        }

        .header-icon i {
            font-size: 2rem;
        }

        .order-card-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .amount-value {
            font-size: 1.1rem;
        }

        /* Pagination responsive */
        .custom-pagination {
            flex-wrap: wrap;
            padding: 0.75rem 1rem;
            gap: 0.75rem;
        }

        .page-btn {
            padding: 0.6rem 1.2rem;
            font-size: 0.9rem;
        }

        .page-numbers {
            padding: 0;
            gap: 0.3rem;
        }

        .page-num {
            min-width: 38px;
            height: 38px;
            font-size: 0.9rem;
        }

        .pagination-info {
            font-size: 0.85rem;
            padding: 0.6rem 1.2rem;
            flex-wrap: wrap;
            justify-content: center;
        }
    }

    @media (max-width: 480px) {
        .order-amount {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .btn-view-details {
            font-size: 0.95rem;
            padding: 0.65rem 1.25rem;
        }

        /* Extra small screens pagination */
        .page-btn span {
            display: none;
        }

        .page-btn {
            padding: 0.6rem;
            min-width: 38px;
        }

        .page-numbers {
            order: -1;
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush
