@extends('layouts.app')

@section('title', 'Tài khoản - La Cuisine Ngọt')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endpush

@section('content')
<div class="account-page-wrapper">
    <div class="account-card">
        <h1 class="account-title">TÀI KHOẢN</h1>
        <p class="user-display-name" id="staffNameDisplay">{{ Auth::user()->name }}</p>

        <!-- Tab Navigation -->
        <div class="account-nav">
            <button class="nav-item tab-link active" data-tab="info-tab">Thông tin</button>
            <button class="nav-item tab-link" data-tab="password-tab">Đổi mật khẩu</button>
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="nav-item logout-btn">Đăng xuất</button>
            </form>
        </div>

        <!-- Tab Content: Profile Info -->
        <div id="info-tab" class="tab-content active">
            <form id="infoForm">
                <div class="form-section">
                    <h2 class="section-subtitle">Thông tin cá nhân</h2>
                    <div class="form-group">
                        <label>Họ Tên</label>
                        <input type="text" name="full_name" value="{{ Auth::user()->name }}" placeholder="Nguyễn Văn A">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" value="{{ Auth::user()->email }}" readonly class="readonly-field">
                    </div>
                    <div class="form-group">
                        <label>Số điện thoại</label>
                        <input type="text" name="phone" value="{{ Auth::user()->phone }}" placeholder="0903456789">
                    </div>
                    <div class="form-group">
                        <label>Địa chỉ</label>
                        <input type="text" name="address" value="{{ Auth::user()->address }}">
                    </div>
                </div>
                <button type="submit" class="save-btn">Lưu thay đổi</button>
            </form>
        </div>

        <!-- Tab Content: Change Password -->
        <div id="password-tab" class="tab-content">
            <form id="passwordForm">
                <div class="form-section">
                    <h2 class="section-subtitle">Thay đổi mật khẩu</h2>
                    <div class="form-group">
                        <label>Mật khẩu hiện tại</label>
                        <input type="password" name="current_password" required>
                    </div>
                    <div class="form-group">
                        <label>Mật khẩu mới</label>
                        <input type="password" name="new_password" id="newPassword" required>
                    </div>
                    <div class="form-group">
                        <label>Xác nhận mật khẩu mới</label>
                        <input type="password" name="new_password_confirmation" id="confirmPassword" required>
                    </div>
                </div>
                <button type="submit" class="save-btn">Đổi mật khẩu</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/profile.js') }}"></script>
@endpush
