<?php $__env->startSection('title', 'Quản lý Đơn hàng'); ?>

<?php $__env->startPush('styles'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('css/staff/orders.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="staff-orders-container">
    <h1>Danh sách đơn hàng</h1>

    <!-- SEARCH & FILTER -->
    <div class="orders-header">
        <div class="search-box">
            <input type="text" id="search-input" placeholder="Tìm mã đơn hàng hoặc tên khách hàng...">
            <i class="fas fa-search search-icon"></i>
        </div>

        <div class="filter-checkboxes">
            <div class="filter-checkbox">
                <input type="checkbox" id="filter-pending" value="pending">
                <label for="filter-pending">Chờ xử lý</label>
            </div>
            <div class="filter-checkbox">
                <input type="checkbox" id="filter-received" value="order_received">
                <label for="filter-received">Đã nhận đơn</label>
            </div>
            <div class="filter-checkbox">
                <input type="checkbox" id="filter-preparing" value="preparing">
                <label for="filter-preparing">Đang chuẩn bị</label>
            </div>
            <div class="filter-checkbox">
                <input type="checkbox" id="filter-delivering" value="delivering">
                <label for="filter-delivering">Đang giao</label>
            </div>
            <div class="filter-checkbox">
                <input type="checkbox" id="filter-success" value="delivery_successful">
                <label for="filter-success">Giao hàng thành công</label>
            </div>
            <div class="filter-checkbox">
                <input type="checkbox" id="filter-failed" value="delivery_failed">
                <label for="filter-failed">Giao hàng thất bại</label>
            </div>
        </div>
    </div>

    <!-- BẢNG ĐƠN HÀNG -->
    <div class="orders-table-wrapper">
        <div class="orders-table-container">
            <table id="orders-table">
                <thead>
                    <tr>
                        <th>Mã đơn hàng</th>
                        <th>Khách hàng</th>
                        <th>Ngày đặt</th>
                        <th>Tổng tiền</th>
                        <th>Cập nhật trạng thái</th>
                        <th>Ghi chú</th>
                        <th>Xem chi tiết</th>
                    </tr>
                </thead>
                <tbody id="orders-table-body">
                    <tr class="loading-row">
                        <td colspan="7">
                            <div class="loading-spinner"></div>
                            <p style="margin-top: 15px; color: #666;">Đang tải dữ liệu...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL CHI TIẾT ĐƠN HÀNG -->
<div class="order-detail-modal" id="orderDetailModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Chi tiết đơn hàng</h2>
            <button class="close-modal" onclick="closeOrderModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body" id="modalBody">
            <!-- Nội dung chi tiết sẽ được load bằng JS -->
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('js/staff/orders.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.staff', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Web-Ban-Banh-Kem-PHP\resources\views/staff/orders/index.blade.php ENDPATH**/ ?>