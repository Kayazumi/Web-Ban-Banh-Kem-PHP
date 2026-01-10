@extends('layouts.admin')

@section('page-title', 'Quản lý khiếu nại')

@section('content')
<div class="complaints-page">
    <!-- Search Bar -->
    <div class="search-container">
        <input type="text" id="searchInput" class="search-input" placeholder="Tìm kiếm khiếu nại">
    </div>

    <!-- Filter Buttons -->
    <div class="filter-buttons">
        <button class="filter-btn active" onclick="filterComplaints('all')">Tất cả</button>
        <button class="filter-btn" onclick="filterComplaints('pending')">Chưa xử lí</button>
        <button class="filter-btn" onclick="filterComplaints('resolved')">Đã xử lí</button>
    </div>

    <!-- Complaints Table -->
    <div class="table-container">
        <table class="complaints-table">
            <thead>
                <tr>
                    <th>Mã KN</th>
                    <th>Mã ĐH</th>
                    <th>Khách hàng</th>
                    <th>Nội dung</th>
                    <th>Ngày tạo</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody id="complaintsTableBody">
                <tr>
                    <td colspan="7" class="text-center">Đang tải...</td>
                </tr>
            </tbody>
        </table>

        <!-- Pagination -->
        <div id="complaintsPagination" class="pagination-container" style="display: none;">
            <div class="pagination-info">
                <span id="complaintsShowingText"></span>
            </div>
            <div class="pagination-controls">
                <button id="complaintsPrevPage" class="pagination-btn">‹</button>
                <div id="complaintsPageNumbers" class="page-numbers"></div>
                <button id="complaintsNextPage" class="pagination-btn">›</button>
            </div>
        </div>
    </div>
</div>

<!-- Complaint Detail Modal -->
<div id="complaintModal" class="modal">
    <div class="modal-content complaint-modal">
        <div class="modal-header">
            <h3>Chi tiết khiếu nại</h3>
            <button class="close-btn" onclick="closeComplaintModal()">×</button>
        </div>
        <div class="modal-body" id="complaintDetailBody">
            <!-- Content will be loaded here -->
        </div>
        <div class="complaint-modal-footer">
            <button class="btn-cancel" onclick="closeComplaintModal()">Đóng</button>
            <select id="complaintStatusSelect" class="status-select">
                <option value="pending">Chưa xử lý</option>
                <option value="processing">Đang xử lý</option>
                <option value="resolved">Đã xử lý</option>
                <option value="closed">Đã đóng</option>
            </select>
            <button id="saveComplaintBtn" class="btn-save">Cập nhật</button>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.complaints-page {
    background-color: #e8dfd0;
    padding: 2rem;
    min-height: calc(100vh - 80px);
}

/* Search Container */
.search-container {
    margin-bottom: 1.5rem;
}

.search-input {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 0.95rem;
    background: white;
}

.search-input::placeholder {
    color: #999;
}

/* Filter Buttons */
.filter-buttons {
    display: flex;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
}

.filter-btn {
    padding: 0.5rem 1.5rem;
    border: 1px solid #ddd;
    background: white;
    color: #333;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.9rem;
    transition: all 0.2s;
}

.filter-btn.active {
    background: #3d5a3d;
    color: white;
    border-color: #3d5a3d;
}

.filter-btn:hover:not(.active) {
    background: #f5f5f5;
}

/* Table */
.table-container {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.complaints-table {
    width: 100%;
    border-collapse: collapse;
}

.complaints-table thead {
    background: #3d5a3d;
    color: white;
}

.complaints-table th {
    padding: 0.75rem;
    text-align: left;
    font-weight: 500;
    font-size: 0.9rem;
}

.complaints-table td {
    padding: 0.75rem;
    border-bottom: 1px solid #eee;
    font-size: 0.875rem;
}

.complaints-table tbody tr:hover {
    background: #f9f9f9;
}

.status-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.8rem;
}

.status-badge.status-resolved {
    background: #add8e6;
    color: #333;
}

.status-badge.status-processing {
    background: #fff3cd;
    color: #856404;
}

.status-badge.status-closed {
    background: #d4edda;
    color: #155724;
}

.action-btn {
    background: none;
    border: none;
    color: #333;
    cursor: pointer;
    padding: 0.25rem 0.5rem;
    font-size: 1.1rem;
    transition: color 0.2s;
}

.action-btn:hover {
    color: #007bff;
}

.action-btn.delete:hover {
    color: #dc3545;
}

.text-center {
    text-align: center;
}

/* Pagination */
.pagination-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.5rem;
    border-top: 1px solid #eee;
    background: #fafafa;
}

.pagination-info {
    color: #666;
    font-size: 0.9rem;
}

.pagination-controls {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.page-numbers {
    display: flex;
    gap: 0.25rem;
}

.pagination-btn,
.page-btn {
    padding: 0.5rem 0.75rem;
    border: 1px solid #ddd;
    background: white;
    color: #333;
    cursor: pointer;
    border-radius: 4px;
    font-size: 0.9rem;
    transition: all 0.2s;
}

.pagination-btn:hover:not(:disabled),
.page-btn:hover:not(.active) {
    background: #f0f0f0;
    border-color: #999;
}

.pagination-btn:disabled {
    opacity: 0.4;
    cursor: not-allowed;
}

.page-btn.active {
    background: #007bff;
    color: white;
    border-color: #007bff;
    font-weight: bold;
}

/* Modal */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
    align-items: center;
    justify-content: center;
}

.complaint-modal {
    background: white;
    border-radius: 8px;
    width: 90%;
    max-width: 550px;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-header {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h3 {
    margin: 0;
    font-size: 1.1rem;
}

.close-btn {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: #999;
}

.modal-body {
    padding: 1.5rem;
}

.detail-section {
    margin-bottom: 1.5rem;
}

.detail-section-title {
    background: #f5f0e8;
    padding: 0.5rem;
    font-weight: 500;
    font-size: 0.9rem;
    margin-bottom: 0.75rem;
}

.detail-row {
    display: flex;
    padding: 0.25rem 0;
    font-size: 0.875rem;
}

.detail-label {
    min-width: 140px;
    color: #666;
}

.detail-value {
    color: #333;
}

.complaint-modal-footer {
    padding: 1rem 1.5rem;
    border-top: 1px solid #eee;
    display: flex;
    gap: 0.5rem;
    justify-content: space-between;
    align-items: center;
}

.btn-cancel {
    background: #f0f0f0;
    color: #666;
    border: 1px solid #ddd;
    padding: 0.5rem 1rem;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.875rem;
}

.btn-cancel:hover {
    background: #e0e0e0;
}

.status-select {
    padding: 0.5rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 0.875rem;
}

.btn-save {
    background: #3d5a3d;
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.875rem;
}

.btn-save:hover {
    background: #2d4a2d;
}
</style>
@endpush

@push('scripts')
<script>
let currentPage = 1;
let currentFilter = 'all';

document.addEventListener('DOMContentLoaded', function() {
    loadComplaints();

    // Search handler
    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            loadComplaints();
        }
    });

    // Save complaint button
    document.getElementById('saveComplaintBtn').addEventListener('click', saveComplaintStatus);
});

async function loadComplaints(page = 1) {
    try {
        const search = document.getElementById('searchInput').value.trim();
        const params = new URLSearchParams();
        
        if (currentFilter !== 'all') {
            params.set('status', currentFilter);
        }
        if (search) {
            params.set('search', search);
        }
        params.set('page', page);

        const token = localStorage.getItem('api_token');
        const response = await fetch(`/api/admin/complaints?${params.toString()}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`,
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const data = await response.json();
        const tbody = document.getElementById('complaintsTableBody');

        if (data.success && data.data.complaints.length > 0) {
            tbody.innerHTML = data.data.complaints.map(complaint => {
                const orderCode = complaint.order ? complaint.order.order_code : 'N/A';
                const customerName = complaint.customer ? complaint.customer.full_name : 'N/A';
                const content = complaint.title || complaint.content || '';
                const shortContent = content.length > 30 ? content.substring(0, 30) + '...' : content;
                
                // Show status text/badge for all statuses
                let statusDisplay = '';
                switch(complaint.status) {
                    case 'resolved':
                        statusDisplay = '<span class="status-badge status-resolved">Đã xử lý</span>';
                        break;
                    case 'processing':
                        statusDisplay = '<span class="status-badge status-processing">Đang xử lý</span>';
                        break;
                    case 'closed':
                        statusDisplay = '<span class="status-badge status-closed">Đã đóng</span>';
                        break;
                    case 'pending':
                    default:
                        statusDisplay = 'Chưa xử lý';
                        break;
                }
                
                return `
                    <tr>
                        <td>${complaint.complaint_code}</td>
                        <td>${orderCode}</td>
                        <td>${customerName}</td>
                        <td>${shortContent}</td>
                        <td>${formatDate(complaint.created_at)}</td>
                        <td>${statusDisplay}</td>
                        <td>
                            <button onclick="viewComplaint(${complaint.ComplaintID})" class="action-btn" title="Xem chi tiết">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button onclick="deleteComplaint(${complaint.ComplaintID})" class="action-btn delete" title="Xóa">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
            }).join('');

            // Render pagination
            if (data.pagination) {
                renderPagination(data.pagination);
            }
        } else {
            tbody.innerHTML = '<tr><td colspan="7" class="text-center">Không có khiếu nại nào</td></tr>';
            document.getElementById('complaintsPagination').style.display = 'none';
        }
    } catch (error) {
        console.error('Error loading complaints:', error);
        document.getElementById('complaintsTableBody').innerHTML =
            '<tr><td colspan="7" class="text-center">Lỗi tải dữ liệu</td></tr>';
    }
}

function renderPagination(pagination) {
    const { current_page, last_page, per_page, total } = pagination;
    
    const paginationContainer = document.getElementById('complaintsPagination');
    paginationContainer.style.display = 'flex';
    
    const start = (current_page - 1) * per_page + 1;
    const end = Math.min(current_page * per_page, total);
    document.getElementById('complaintsShowingText').textContent = 
        `Hiển thị ${start} - ${end} của ${total} khiếu nại`;
    
    const prevBtn = document.getElementById('complaintsPrevPage');
    const nextBtn = document.getElementById('complaintsNextPage');
    
    prevBtn.disabled = current_page === 1;
    nextBtn.disabled = current_page === last_page;
    
    prevBtn.onclick = () => {
        if (current_page > 1) loadComplaints(current_page - 1);
    };
    nextBtn.onclick = () => {
        if (current_page < last_page) loadComplaints(current_page + 1);
    };
    
    const pageNumbersContainer = document.getElementById('complaintsPageNumbers');
    pageNumbersContainer.innerHTML = '';
    
    const maxVisiblePages = 7;
    let startPage = Math.max(1, current_page - Math.floor(maxVisiblePages / 2));
    let endPage = Math.min(last_page, startPage + maxVisiblePages - 1);
    
    if (endPage - startPage < maxVisiblePages - 1) {
        startPage = Math.max(1, endPage - maxVisiblePages + 1);
    }
    
    if (startPage > 1) {
        pageNumbersContainer.appendChild(createPageButton(1, current_page));
        if (startPage > 2) {
            const ellipsis = document.createElement('button');
            ellipsis.className = 'page-btn';
            ellipsis.textContent = '...';
            ellipsis.disabled = true;
            pageNumbersContainer.appendChild(ellipsis);
        }
    }
    
    for (let i = startPage; i <= endPage; i++) {
        pageNumbersContainer.appendChild(createPageButton(i, current_page));
    }
    
    if (endPage < last_page) {
        if (endPage < last_page - 1) {
            const ellipsis = document.createElement('button');
            ellipsis.className = 'page-btn';
            ellipsis.textContent = '...';
            ellipsis.disabled = true;
            pageNumbersContainer.appendChild(ellipsis);
        }
        pageNumbersContainer.appendChild(createPageButton(last_page, current_page));
    }
}

function createPageButton(pageNum, currentPage) {
    const btn = document.createElement('button');
    btn.className = 'page-btn' + (pageNum === currentPage ? ' active' : '');
    btn.textContent = pageNum;
    if (pageNum !== currentPage) {
        btn.onclick = () => loadComplaints(pageNum);
    }
    return btn;
}

function filterComplaints(status) {
    currentFilter = status;
    currentPage = 1;
    
    // Update button states
    document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
    
    loadComplaints();
}

async function viewComplaint(complaintId) {
    try {
        const token = localStorage.getItem('api_token');
        const response = await fetch(`/api/admin/complaints/${complaintId}`, {
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`,
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const data = await response.json();
        if (!data.success) {
            showAlert('Không thể tải chi tiết khiếu nại');
            return;
        }

        const complaint = data.data.complaint;
        const orderCode = complaint.order ? complaint.order.order_code : 'N/A';
        const customerName = complaint.customer ? complaint.customer.full_name : 'N/A';
        
        const body = document.getElementById('complaintDetailBody');
        body.innerHTML = `
            <div class="detail-section">
                <div class="detail-section-title">Thông tin khiếu nại</div>
                <div class="detail-row">
                    <span class="detail-label">Mã khiếu nại:</span>
                    <span class="detail-value">${complaint.complaint_code}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Mã đơn hàng:</span>
                    <span class="detail-value">${orderCode}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Khách hàng:</span>
                    <span class="detail-value">${customerName}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Loại khiếu nại:</span>
                    <span class="detail-value">${complaint.complaint_type || 'N/A'}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Ngày tạo:</span>
                    <span class="detail-value">${formatDateTime(complaint.created_at)}</span>
                </div>
            </div>

            <div class="detail-section">
                <div class="detail-section-title">Nội dung</div>
                <div class="detail-row">
                    <span class="detail-label">Tiêu đề:</span>
                    <span class="detail-value">${complaint.title || 'N/A'}</span>
                </div>
                <div style="margin-top: 0.5rem; font-size: 0.875rem; line-height: 1.5;">
                    ${complaint.content || 'Không có mô tả'}
                </div>
            </div>

            <div class="detail-section">
                <div class="detail-section-title">Giải quyết</div>
                <div style="margin-top: 0.5rem; font-size: 0.875rem; line-height: 1.5;">
                    ${complaint.resolution || 'Chưa có giải quyết'}
                </div>
            </div>
        `;

        document.getElementById('complaintStatusSelect').value = complaint.status || 'pending';
        document.getElementById('saveComplaintBtn').dataset.complaintId = complaint.ComplaintID;
        document.getElementById('complaintModal').style.display = 'flex';
    } catch (error) {
        console.error('Error viewing complaint:', error);
        showAlert('Lỗi tải chi tiết khiếu nại');
    }
}

async function saveComplaintStatus() {
    const complaintId = document.getElementById('saveComplaintBtn').dataset.complaintId;
    const status = document.getElementById('complaintStatusSelect').value;

    try {
        const token = localStorage.getItem('api_token');
        const csrf = window.Laravel && window.Laravel.csrfToken ? window.Laravel.csrfToken : '';
        
        const response = await fetch(`/api/admin/complaints/${complaintId}/status`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`,
                'X-CSRF-TOKEN': csrf,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ status })
        });

        const data = await response.json();
        if (data.success) {
            showAlert('Cập nhật trạng thái thành công!');
            closeComplaintModal();
            loadComplaints(currentPage);
        } else {
            showAlert(data.message || 'Có lỗi xảy ra');
        }
    } catch (error) {
        console.error('Error updating status:', error);
        showAlert('Có lỗi xảy ra');
    }
}

function closeComplaintModal() {
    document.getElementById('complaintModal').style.display = 'none';
}

async function deleteComplaint(complaintId) {
    showConfirm('Bạn có chắc chắn muốn xóa khiếu nại này?', function() {
    
    // Implement delete functionality if needed
    showAlert('Chức năng xóa chưa được triển khai');
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('vi-VN');
}

function formatDateTime(dateString) {
    const date = new Date(dateString);
    return date.toLocaleString('vi-VN', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit'
    });
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('complaintModal');
    if (event.target === modal) {
        closeComplaintModal();
    }
};
</script>
@endpush
