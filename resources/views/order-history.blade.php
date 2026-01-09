@extends('layouts.app')

@section('title', 'Lịch sử mua hàng - La Cuisine Ngọt')

@section('content')
<div class="container py-5">
    <h1 class="page-title text-center text-uppercase">Lịch sử đơn hàng</h1>

    <div class="row justify-content-center">
        <div class="col-md-10">
            @if($orders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover order-history-table">
                        <thead>
                            <tr>
                                <th>Mã đơn hàng</th>
                                <th>Ngày đặt</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td class="order-code-cell">
                                        <a href="{{ route('order.details', $order->OrderID) }}" class="fw-bold text-dark">
                                            {{ $order->order_code }}
                                        </a>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}</td>
                                    <td class="fw-bold text-success">{{ number_format($order->final_amount, 0, ',', '.') }} ₫</td>
                                    <td>
                                        @php
                                            $statusClasses = [
                                                'pending' => 'bg-warning text-dark',
                                                'order_received' => 'bg-info text-white',
                                                'preparing' => 'bg-secondary text-white',
                                                'delivering' => 'bg-primary text-white',
                                                'delivery_successful' => 'bg-success text-white',
                                                'delivery_failed' => 'bg-danger text-white',
                                                'cancelled' => 'bg-danger text-white'
                                            ];
                                            $statusText = [
                                                'pending' => 'Chờ xác nhận',
                                                'order_received' => 'Đã nhận đơn',
                                                'preparing' => 'Đang chuẩn bị',
                                                'delivering' => 'Đang giao hàng',
                                                'delivery_successful' => 'Giao thành công',
                                                'delivery_failed' => 'Giao thất bại',
                                                'cancelled' => 'Đã hủy'
                                            ];
                                            $status = $order->order_status;
                                        @endphp
                                        <span class="badge rounded-pill {{ $statusClasses[$status] ?? 'bg-secondary' }}">
                                            {{ $statusText[$status] ?? $status }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('order.details', $order->OrderID) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i> Xem chi tiết
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $orders->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-shopping-bag fa-4x text-muted"></i>
                    </div>
                    <h4>Bạn chưa có đơn hàng nào</h4>
                    <p class="text-muted">Hãy tham quan cửa hàng và mua sắm những chiếc bánh ngon tuyệt nhé!</p>
                    <a href="{{ route('products') }}" class="btn btn-primary mt-3">
                        Mua sắm ngay
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .page-title {
        color: #324F29;
        font-weight: 700;
        margin-bottom: 2rem;
    }
    
    .order-history-table {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        overflow: hidden;
    }
    
    .order-history-table thead {
        background-color: #f8f9fa;
        color: #324F29;
    }
    
    .order-history-table th {
        font-weight: 600;
        padding: 1rem;
        border-bottom: 2px solid #e9ecef;
    }
    
    .order-history-table td {
        padding: 1rem;
        vertical-align: middle;
    }
    
    .order-code-cell {
        font-family: monospace;
        font-size: 1.1em;
    }
    
    .btn-outline-primary {
        color: #324F29;
        border-color: #324F29;
    }
    
    .btn-outline-primary:hover {
        background-color: #324F29;
        color: white;
    }
    
    .badge {
        font-weight: 500;
        padding: 0.5em 0.8em;
    }
    
    .btn-primary {
        background-color: #324F29;
        border-color: #324F29;
    }
    
    .btn-primary:hover {
        background-color: #263e20;
        border-color: #263e20;
    }
</style>
@endpush
