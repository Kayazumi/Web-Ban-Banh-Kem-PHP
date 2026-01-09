@extends('layouts.app')

@section('title', 'Tài khoản - La Cuisine Ngọt')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/account.css') }}">
@endpush

@section('content')
<section class="profile-section">
    <div class="profile-container">
        <!-- Header -->
        <div class="profile-header">
            <div class="profile-avatar">
                <i class="fas fa-user-circle"></i>
            </div>
            <h2 id="userNameDisplay">{{ Auth::user()->full_name ?? Auth::user()->name }}</h2>
            <p>Quản lý thông tin cá nhân và bảo mật tài khoản của bạn</p>
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

        <!-- Tab: Info -->
        <div id="info-tab" class="tab-content active">
            <form id="infoForm" class="profile-form">
                @csrf
                <div class="form-row">
                    <div class="form-group">
                        <label for="nameInput">Họ và Tên <span style="color: #e74c3c;">*</span></label>
                        <input type="text" id="nameInput" name="full_name" value="{{ Auth::user()->full_name ?? Auth::user()->name }}" required placeholder="Nhập họ và tên">
                    </div>
                    <div class="form-group">
                        <label for="phoneInput">Số điện thoại</label>
                        <input type="tel" id="phoneInput" name="phone" value="{{ Auth::user()->phone ?? '' }}" placeholder="0901234567" pattern="[0-9]{9,12}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email (do cửa hàng cấp)</label>
                    <input type="email" id="email" name="email" value="{{ Auth::user()->email }}" readonly disabled style="background: #e9ecef; cursor: not-allowed;">
                    <small style="color: #6c757d; font-size: 0.85rem;"><i class="fas fa-info-circle"></i> Email không thể thay đổi</small>
                </div>

                <div class="form-group">
                    <label for="addressInput">Địa chỉ</label>
                    <input type="text" id="addressInput" name="address" value="{{ Auth::user()->address ?? '' }}" placeholder="Nhập địa chỉ">
                </div>

                <button type="submit" class="save-btn"><i class="fas fa-save"></i> Lưu thay đổi</button>
            </form>
        </div>

        <!-- Tab: Password -->
        <div id="password-tab" class="tab-content">
            <form id="passwordForm" class="profile-form">
                @csrf
                <div class="form-group">
                    <label for="oldPassword">Mật khẩu hiện tại <span style="color: #e74c3c;">*</span></label>
                    <input type="password" id="oldPassword" name="oldPassword" required placeholder="Nhập mật khẩu hiện tại" autocomplete="current-password">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="newPassword">Mật khẩu mới <span style="color: #e74c3c;">*</span></label>
                        <input type="password" id="newPassword" name="newPassword" required placeholder="Mật khẩu mới (tối thiểu 6 kí tự)" minlength="6" autocomplete="new-password">
                    </div>
                    <div class="form-group">
                        <label for="confirmPassword">Xác nhận mật khẩu mới <span style="color: #e74c3c;">*</span></label>
                        <input type="password" id="confirmPassword" name="newPassword_confirmation" required placeholder="Nhập lại mật khẩu mới" minlength="6" autocomplete="new-password">
                    </div>
                </div>

                <button type="submit" class="save-btn"><i class="fas fa-key"></i> Đổi mật khẩu</button>
            </form>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="{{ asset('js/profile.js') }}"></script>
@endpush
