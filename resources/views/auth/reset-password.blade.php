@extends('layouts.app')

@section('title', 'Đặt lại mật khẩu - La Cuisine Ngọt')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endpush

@section('content')
<div class="form-container">
    <h2 class="form-title">Đặt lại mật khẩu</h2>

    @if($errors->any())
        <div class="alert alert-danger" style="background: #fee; border: 1px solid #fcc; padding: 15px; border-radius: 8px; margin-bottom: 20px; color: #c33;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-group">
            <label for="email">Địa chỉ Email</label>
            <input id="email" type="email" class="@error('email') error @enderror" 
                   name="email" value="{{ $email ?? old('email') }}" 
                   required autocomplete="email" readonly>
            @error('email')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Mật khẩu mới</label>
            <div class="password-wrapper" style="position: relative;">
                <input type="password" id="password" 
                       class="@error('password') error @enderror" 
                       name="password" required autocomplete="new-password"
                       style="padding-right: 45px;">
                <i class="fas fa-eye" id="togglePassword" 
                   style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #666;"></i>
            </div>
            @error('password')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password-confirm">Xác nhận mật khẩu</label>
            <div class="password-wrapper" style="position: relative;">
                <input type="password" id="password-confirm" 
                       name="password_confirmation" required autocomplete="new-password"
                       style="padding-right: 45px;">
                <i class="fas fa-eye" id="togglePasswordConfirm" 
                   style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #666;"></i>
            </div>
        </div>

        <button type="submit" class="btn-submit">Đặt lại mật khẩu</button>
    </form>

    <div class="form-links">
        <p>Quay lại <a href="{{ route('login') }}">Đăng nhập</a></p>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('password');
        const passwordConfirmInput = document.getElementById('password-confirm');
        const togglePassword = document.getElementById('togglePassword');
        const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');

        // Toggle password visibility
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });

        togglePasswordConfirm.addEventListener('click', function() {
            const type = passwordConfirmInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordConfirmInput.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    });
</script>
@endpush