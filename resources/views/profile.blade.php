@extends('layouts.app')

@section('title', 'Thông tin tài khoản - La Cuisine Ngọt')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h2>Thông tin tài khoản</h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Thông tin cá nhân</h4>
                            <p><strong>Họ tên:</strong> {{ Auth::user()->full_name }}</p>
                            <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                            <p><strong>Tên đăng nhập:</strong> {{ Auth::user()->username }}</p>
                            <p><strong>Số điện thoại:</strong> {{ Auth::user()->phone ?: 'Chưa cập nhật' }}</p>
                            <p><strong>Địa chỉ:</strong> {{ Auth::user()->address ?: 'Chưa cập nhật' }}</p>
                            <p><strong>Vai trò:</strong> {{ Auth::user()->role }}</p>
                            <p><strong>Trạng thái:</strong> {{ Auth::user()->status }}</p>
                            <p><strong>Ngày đăng ký:</strong> {{ Auth::user()->created_at->format('d/m/Y') }}</p>
                            @if(Auth::user()->last_login)
                            <p><strong>Đăng nhập cuối:</strong> {{ Auth::user()->last_login->format('d/m/Y H:i') }}</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h4>Thống kê</h4>
                            <p><strong>Tổng đơn hàng:</strong> {{ Auth::user()->orders()->count() }}</p>
                            <p><strong>Đơn hàng đã hoàn thành:</strong> {{ Auth::user()->orders()->where('order_status', 'delivery_successful')->count() }}</p>
                            <p><strong>Tổng tiền đã mua:</strong> {{ number_format(Auth::user()->orders()->where('order_status', 'delivery_successful')->sum('final_amount'), 0, ',', '.') }}đ</p>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('orders.index') }}" class="btn btn-primary">Xem đơn hàng</a>
                        <button class="btn btn-secondary" onclick="logout()">Đăng xuất</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
}

.card-header {
    background: #8B4513;
    color: white;
    border-radius: 10px 10px 0 0 !important;
    border: none;
}

.card-header h2 {
    margin: 0;
    font-size: 1.5rem;
}

.card-body {
    padding: 2rem;
}

.btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 5px;
    text-decoration: none;
    font-weight: 500;
    cursor: pointer;
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
</style>
@endpush
