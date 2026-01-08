<?php $__env->startSection('title', 'Hồ sơ Nhân viên'); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/account.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<section class="profile-section">
    <div class="profile-container">
        <!-- Header -->
        <div class="profile-header">
            <div class="profile-avatar">
                <i class="fas fa-user-shield"></i>
            </div>
            <h2 id="staffNameDisplay"><?php echo e(Auth::user()->full_name); ?></h2>
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

        <!-- Tab Thông tin cá nhân -->
        <div id="info-tab" class="tab-content active">
            <form id="infoForm" class="profile-form">
                <?php echo csrf_field(); ?>
                <div class="form-row">
                    <div class="form-group">
                        <label for="nameInput">Họ và Tên <span style="color: #e74c3c;">*</span></label>
                        <input 
                            type="text" 
                            id="nameInput" 
                            name="full_name" 
                            value="<?php echo e(Auth::user()->full_name); ?>" 
                            required
                            placeholder="Nhập họ và tên">
                    </div>
                    <div class="form-group">
                        <label for="phoneInput">Số điện thoại</label>
                        <input 
                            type="tel" 
                            id="phoneInput" 
                            name="phone" 
                            value="<?php echo e(Auth::user()->phone ?? ''); ?>" 
                            placeholder="Nhập số điện thoại"
                            pattern="[0-9]{10,11}">
                    </div>
                </div>

                <div class="form-group">
    <label for="email">Email (do cửa hàng cấp)</label>
    <input type="email" id="email" name="email" value="<?php echo e(Auth::user()->email); ?>" 
           readonly disabled style="background: #e9ecef; cursor: not-allowed;">
    <small style="color: #6c757d; font-size: 0.85rem;">
        <i class="fas fa-info-circle"></i> Email không thể thay đổi
    </small>
</div>

                <div class="form-group">
                    <label for="addressInput">Địa chỉ</label>
                    <input 
                        type="text" 
                        id="addressInput" 
                        name="address" 
                        value="<?php echo e(Auth::user()->address ?? ''); ?>" 
                        placeholder="Nhập địa chỉ">
                </div>

                <button type="submit" class="save-btn">
                    <i class="fas fa-save"></i> Lưu thay đổi
                </button>
            </form>
        </div>

        <!-- Tab Thay đổi mật khẩu -->
        <div id="password-tab" class="tab-content">
            <form id="passwordForm" class="profile-form">
                <?php echo csrf_field(); ?>
                <div class="form-group">
                    <label for="oldPassword">Mật khẩu hiện tại <span style="color: #e74c3c;">*</span></label>
                    <input 
                        type="password" 
                        id="oldPassword" 
                        name="oldPassword" 
                        required
                        placeholder="Nhập mật khẩu hiện tại"
                        autocomplete="current-password">
                </div>

                <div class="form-group">
                    <label for="newPassword">Mật khẩu mới <span style="color: #e74c3c;">*</span></label>
                    <input 
                        type="password" 
                        id="newPassword" 
                        name="newPassword" 
                        required
                        placeholder="Nhập mật khẩu mới (tối thiểu 6 ký tự)"
                        autocomplete="new-password"
                        minlength="6">
                </div>

                <div class="form-group">
                    <label for="confirmPassword">Xác nhận mật khẩu mới <span style="color: #e74c3c;">*</span></label>
                    <input 
                        type="password" 
                        id="confirmPassword" 
                        name="newPassword_confirmation" 
                        required
                        placeholder="Nhập lại mật khẩu mới"
                        autocomplete="new-password"
                        minlength="6">
                </div>

                <button type="submit" class="save-btn">
                    <i class="fas fa-key"></i> Đổi mật khẩu
                </button>
            </form>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('js/profile.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.staff', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Web-Ban-Banh-Kem-PHP\resources\views/staff/profile/index.blade.php ENDPATH**/ ?>