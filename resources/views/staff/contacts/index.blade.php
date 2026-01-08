@extends('layouts.staff')

@section('title', 'Quản lý Liên hệ')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/staff/contacts.css') }}">
@endpush

@section('content')
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
@endsection

@push('scripts')
    <script src="{{ asset('js/staff/contacts.js') }}"></script>
@endpush