@extends('layouts.staff')
@section('title', 'Hồ sơ Nhân viên')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/staff/profile.css') }}">
@endpush

@section('content')
<section class="profile-section">
    <div class="profile-container">
        <div class="profile-header">
            <div class="profile-avatar"><i class="fas fa-user-shield"></i></div>
            <h2>{{ Auth::user()->full_name }}</h2>
            <p>Quản lý thông tin cá nhân và bảo mật của bạn.</p>
        </div>

        <div class="tab-nav">
            <button class="tab-link active" data-tab="info-tab"><i class="fas fa-user"></i> Thông tin cá nhân</button>
            <button class="tab-link" data-tab="password-tab"><i class="fas fa-key"></i> Thay đổi mật khẩu</button>
        </div>

        <div id="info-tab" class="tab-content active">
            <form id="infoForm" class="profile-form">
                <div class="form-row">
                    <div class="form-group"><label>Họ và Tên</label><input type="text" id="nameInput" value="{{ Auth::user()->full_name }}"></div>
                    <div class="form-group"><label>Số điện thoại</label><input type="tel" id="phoneInput" value="{{ Auth::user()->phone }}" placeholder="Nhập số điện thoại"></div>
                </div>
                <div class="form-group"><label>Email (Không thể sửa)</label><input type="email" value="{{ Auth::user()->email }}" readonly></div>
                <div class="form-group"><label>Địa chỉ</label><input type="text" id="addressInput" value="{{ Auth::user()->address }}"></div>
                <button type="submit" class="save-btn">Lưu thay đổi</button>
            </form>
        </div>
        </div>
</section>
@endsection