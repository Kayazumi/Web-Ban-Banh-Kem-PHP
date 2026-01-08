<?php $__env->startSection('title', 'Danh sách Khiếu nại'); ?>

<?php $__env->startPush('styles'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('css/staff/complaints.css')); ?>?v=<?php echo e(time()); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="staff-complaints-container">
    <h1 class="page-title">DANH SÁCH KHIẾU NẠI</h1>

    <div class="complaints-header">
        <div class="search-box">
            <input type="text" id="complaint-search" placeholder="Tìm Mã KN, Mã ĐH, hoặc Tên KH...">
            <i class="fas fa-search search-icon"></i>
        </div>

        <div class="filter-checkboxes">
            <div class="filter-checkbox">
                <input type="checkbox" id="filter-all" value="all" checked>
                <label for="filter-all">Tất cả</label>
            </div>
            <div class="filter-checkbox">
                <input type="checkbox" id="filter-pending" value="pending">
                <label for="filter-pending">Chờ xử lý</label>
            </div>
            <div class="filter-checkbox">
                <input type="checkbox" id="filter-processing" value="processing">
                <label for="filter-processing">Đang xử lý</label>
            </div>
            <div class="filter-checkbox">
                <input type="checkbox" id="filter-resolved" value="resolved">
                <label for="filter-resolved">Đã giải quyết</label>
            </div>
        </div>
    </div>

    <div class="complaints-table-wrapper">
        <table id="complaints-table">
            <thead>
                <tr>
                    <th>MÃ KN</th>
                    <th>MÃ ĐH</th>
                    <th>KHÁCH HÀNG</th>
                    <th>NỘI DUNG</th>
                    <th>NGÀY PHẢN ÁNH</th>
                    <th>TRẠNG THÁI</th>
                    <th>XỬ LÝ</th>
                </tr>
            </thead>
            <tbody id="complaints-table-body">
                </tbody>
        </table>
    </div>
</div>

<div class="complaint-detail-modal" id="complaintModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>CHI TIẾT YÊU CẦU</h2>
            <button class="close-modal" onclick="closeComplaintModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="complaint-detail-form">
                <div class="detail-grid">
                    <div class="detail-left">
                        <div class="form-group">
                            <label>Họ và tên khách hàng</label>
                            <input type="text" id="detail-customer-name" readonly placeholder="Chọn một khiếu nại từ danh sách">
                        </div>
                        <div class="form-group">
                            <label>Số điện thoại</label>
                            <input type="text" id="detail-customer-phone" readonly>
                        </div>
                        <div class="form-group">
                            <label>Nội dung chi tiết (từ khách hàng)</label>
                            <textarea id="detail-content" readonly placeholder="Nội dung chi tiết khiếu nại / yêu cầu"></textarea>
                        </div>
                    </div>

                    <div class="detail-right">
                        <div class="form-group">
                            <label>Trạng thái</label>
                            <select id="detail-status">
                                <option value="pending">Chờ giải quyết</option>
                                <option value="processing">Đang giải quyết</option>
                                <option value="resolved">Đã giải quyết</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Nhân viên phụ trách (ID)</label>
                            <input type="text" id="detail-staff-id" readonly class="input-readonly" placeholder="ID nhân viên">
                        </div>
                        <div class="form-group">
                            <input type="text" id="detail-staff-name" readonly class="input-readonly" placeholder="Hiển thị tên nhân viên">
                        </div>
                    </div>
                </div>
                <div class="response-container">
            <div class="form-group">
                <label>Nội dung phản hồi</label>
                <textarea id="detail-response" placeholder="Phản hồi gửi lại khách..."></textarea>
            </div>
        </div>
                <div class="modal-footer">
    <button type="button" class="btn-outline-modal" onclick="saveDraft()">
        TRẢ LỜI KHÁCH HÀNG
    </button>
    <button type="button" class="btn-save-modal" onclick="sendToCustomer()">
        LƯU VÀ ĐÓNG
    </button>
</div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('js/staff/complaints.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.staff', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Web-Ban-Banh-Kem-PHP\resources\views/staff/complaints/index.blade.php ENDPATH**/ ?>