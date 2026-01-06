@extends('layouts.staff')

@section('title', 'Hồ sơ Nhân viên - La Cuisine Ngọt')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/profile.js') }}"></script>
@endpush

@section('content')
<section class="profile-section">
    <div class="profile-container">
        <!-- Header -->
        <div class="profile-header">
            <div class="profile-avatar">
                <i class="fas fa-user-shield"></i>
            </div>
            <h2 id="staffNameDisplay">{{ Auth::user()->full_name }}</h2>
            <p>Quản lý thông tin cá nhân và bảo mật tài khoản của bạn.</p>
        </div>

        <!-- Tab Navigation -->
        <div class="tab-nav">
            <button class="tab-link active" data-tab="info-tab">
                <i class="fas fa-user"></i> Thông tin cá nhân
            </button>
            <button class="tab-link" data-tab="password-tab">
                <i class="fas fa-key"></i> Thay đổi mật khẩu
            </button>
        </div>

        <!-- Tab Thông tin cá nhân -->
        <div id="info-tab" class="tab-content active">
            <form id="infoForm" class="profile-form">
                @csrf
                <div class="form-row">
                    <div class="form-group">
                        <label for="nameInput">Họ và Tên</label>
                        <input type="text" id="nameInput" name="full_name" value="{{ Auth::user()->full_name }}" required>
                    </div>
                    <div class="form-group">
                        <label for="phoneInput">Số điện thoại</label>
                        <input type="tel" id="phoneInput" name="phone" value="{{ Auth::user()->phone ?? '' }}" placeholder="Nhập số điện thoại">
                    </div>
                </div>

                <div class="form-group">
                    <label>Email (Không thể sửa)</label>
                    <input type="email" value="{{ Auth::user()->email }}" readonly>
                </div>

                <div class="form-group">
                    <label for="addressInput">Địa chỉ</label>
                    <input type="text" id="addressInput" name="address" value="{{ Auth::user()->address ?? '' }}" placeholder="Nhập địa chỉ">
                </div>

                <button type="submit" class="save-btn">Lưu thay đổi</button>
            </form>
        </div>

        <!-- Tab Thay đổi mật khẩu -->
        <div id="password-tab" class="tab-content">
            <form id="passwordForm" class="profile-form">
                @csrf
                <div class="form-group">
                    <label for="oldPassword">Mật khẩu hiện tại</label>
                    <input type="password" id="oldPassword" name="oldPassword" required>
                </div>

                <div class="form-group">
                    <label for="newPassword">Mật khẩu mới</label>
                    <input type="password" id="newPassword" name="newPassword" required>
                </div>

                <div class="form-group">
                    <label for="confirmPassword">Xác nhận mật khẩu mới</label>
                    <input type="password" id="confirmPassword" name="newPassword_confirmation" required>
                </div>

                <button type="submit" class="save-btn">Đổi mật khẩu</button>
            </form>
        </div>
    </div>
</section>
@endsection