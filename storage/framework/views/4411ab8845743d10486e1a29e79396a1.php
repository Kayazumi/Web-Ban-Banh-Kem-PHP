<?php $__env->startSection('title', 'Quản lý Liên hệ'); ?>

<?php $__env->startPush('styles'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('css/staff/contacts.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="staff-contacts-container">
    <h1>Danh sách liên hệ từ khách hàng</h1>

    <!-- SEARCH & FILTER -->
    <div class="contacts-header">
        <div class="search-box">
            <input type="text" id="search-input" placeholder="Tìm theo tên khách hàng hoặc tiêu đề...">
            <i class="fas fa-search search-icon"></i>
        </div>

        <div class="filter-checkboxes">
            <div class="filter-checkbox">
                <input type="checkbox" id="filter-pending" value="pending">
                <label for="filter-pending">Chờ xử lý</label>
            </div>
            <div class="filter-checkbox">
                <input type="checkbox" id="filter-responded" value="responded">
                <label for="filter-responded">Đã phản hồi</label>
            </div>
        </div>
    </div>

    <!-- BẢNG LIÊN HỆ -->
    <div class="contacts-table-wrapper">
        <div class="contacts-table-container">
            <table id="contacts-table">
                <thead>
                    <tr>
                        <th>Mã liên hệ</th>
                        <th>Khách hàng</th>
                        <th>Tiêu đề</th>
                        <th>Ngày gửi</th>
                        <th>Trạng thái</th>
                        <th>Xem chi tiết</th>
                    </tr>
                </thead>
                <tbody id="contacts-table-body">
                    <tr class="loading-row">
                        <td colspan="6">
                            <div class="loading-spinner"></div>
                            <p style="margin-top: 15px; color: #666;">Đang tải dữ liệu...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL CHI TIẾT LIÊN HỆ -->
<div class="contact-detail-modal" id="contactDetailModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Chi tiết liên hệ</h2>
            <button class="close-modal" onclick="closeContactModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body" id="modalBody">
            <!-- Nội dung chi tiết sẽ được load bằng JS -->
        </div>
        <div class="modal-footer">
            <button class="btn-mark-responded" id="btnMarkResponded" onclick="markAsResponded()">
                <i class="fas fa-check"></i> Đánh dấu đã phản hồi
            </button>
            <button class="btn-secondary" onclick="closeContactModal()">Đóng</button>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('js/staff/contacts.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.staff', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\ProgramFilesD\DevApps\XAM_PP\htdocs\Web-Ban-Banh-Kem-PHP\resources\views/staff/contacts/index.blade.php ENDPATH**/ ?>