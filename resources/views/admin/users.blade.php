@extends('layouts.admin')

@section('page-title', 'Quản lý người dùng')

@section('content')
<div class="admin-content">
    <div class="content-header">
        <h2>Quản lý người dùng</h2>
    </div>

    <!-- Filters -->
    <div class="filters">
        <div class="filter-group">
            <label for="roleFilter">Vai trò:</label>
            <select id="roleFilter" class="form-control">
                <option value="">Tất cả</option>
                <option value="customer">Khách hàng</option>
                <option value="staff">Nhân viên</option>
                <option value="admin">Quản trị viên</option>
            </select>
        </div>

        <div class="filter-group">
            <label for="statusFilter">Trạng thái:</label>
            <select id="statusFilter" class="form-control">
                <option value="">Tất cả</option>
                <option value="active">Hoạt động</option>
                <option value="inactive">Không hoạt động</option>
                <option value="banned">Đã khóa</option>
            </select>
        </div>

        <div class="filter-group">
            <label for="searchInput">Tìm kiếm:</label>
            <input type="text" id="searchInput" class="form-control" placeholder="Tên, email...">
        </div>

        <button id="applyFilters" class="btn btn-secondary">Lọc</button>
    </div>

    <!-- Users Table -->
    <div class="table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên đăng nhập</th>
                    <th>Họ tên</th>
                    <th>Email</th>
                    <th>Vai trò</th>
                    <th>Trạng thái</th>
                    <th>Ngày đăng ký</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody id="usersTableBody">
                <tr>
                    <td colspan="8" class="text-center">Đang tải...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('styles')
<style>
.content-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.filters {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    align-items: end;
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 8px;
    margin-bottom: 2rem;
}

.filter-group {
    display: flex;
    flex-direction: column;
    min-width: 200px;
}

.filter-group label {
    font-weight: bold;
    margin-bottom: 0.5rem;
    color: #333;
}

.form-control {
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1rem;
}

.table-container {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    overflow: hidden;
}

.admin-table {
    width: 100%;
    border-collapse: collapse;
}

.admin-table th,
.admin-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.admin-table th {
    background: #f8f9fa;
    font-weight: 600;
    color: #333;
}

.role-badge,
.status-badge {
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    font-size: 0.875rem;
    font-weight: bold;
}

.role-customer {
    background: #cce5ff;
    color: #004085;
}

.role-staff {
    background: #d1ecf1;
    color: #0c5460;
}

.role-admin {
    background: #f8d7da;
    color: #721c24;
}

.status-active {
    background: #d4edda;
    color: #155724;
}

.status-inactive {
    background: #fff3cd;
    color: #856404;
}

.status-banned {
    background: #f8d7da;
    color: #721c24;
}

.btn {
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.9rem;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s;
}

.btn-primary {
    background: #007bff;
    color: white;
}

.btn-primary:hover {
    background: #0056b3;
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #545b62;
}

.btn-danger {
    background: #dc3545;
    color: white;
}

.btn-danger:hover {
    background: #c82333;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.8rem;
}

.text-center {
    text-align: center;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    loadUsers();

    // Filters
    document.getElementById('applyFilters').addEventListener('click', applyFilters);
    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            applyFilters();
        }
    });
});

async function loadUsers() {
    try {
        const response = await fetch('/api/admin/users');
        const data = await response.json();

        const tbody = document.getElementById('usersTableBody');

        if (data.success && data.data.users.length > 0) {
            tbody.innerHTML = data.data.users.map(user => `
                <tr>
                    <td>${user.UserID}</td>
                    <td>${user.username}</td>
                    <td>${user.full_name}</td>
                    <td>${user.email}</td>
                    <td>
                        <span class="role-badge role-${user.role}">
                            ${getRoleText(user.role)}
                        </span>
                    </td>
                    <td>
                        <span class="status-badge status-${user.status}">
                            ${getStatusText(user.status)}
                        </span>
                    </td>
                    <td>${formatDate(user.created_at)}</td>
                    <td>
                        <button onclick="editUser(${user.UserID})" class="btn btn-secondary btn-sm">
                            <i class="fas fa-edit"></i> Sửa
                        </button>
                        ${user.role !== 'admin' ?
                            `<button onclick="toggleStatus(${user.UserID}, '${user.status}')" class="btn btn-danger btn-sm">
                                ${user.status === 'active' ? 'Khóa' : 'Mở khóa'}
                            </button>` :
                            ''}
                    </td>
                </tr>
            `).join('');
        } else {
            tbody.innerHTML = '<tr><td colspan="8" class="text-center">Không có người dùng nào</td></tr>';
        }
    } catch (error) {
        console.error('Error loading users:', error);
        document.getElementById('usersTableBody').innerHTML =
            '<tr><td colspan="8" class="text-center text-danger">Lỗi tải dữ liệu</td></tr>';
    }
}

function applyFilters() {
    // Filter functionality would be implemented here
    loadUsers();
}

async function editUser(userId) {
    // Edit user functionality would be implemented here
    alert('Tính năng chỉnh sửa người dùng sẽ được phát triển');
}

async function toggleStatus(userId, currentStatus) {
    const action = currentStatus === 'active' ? 'khóa' : 'mở khóa';
    if (!confirm(`Bạn có chắc chắn muốn ${action} tài khoản này?`)) return;

    const newStatus = currentStatus === 'active' ? 'banned' : 'active';

    try {
        const response = await fetch(`/api/admin/users/${userId}/status`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.Laravel.csrfToken
            },
            body: JSON.stringify({
                status: newStatus
            })
        });

        const data = await response.json();

        if (data.success) {
            alert(`Đã ${action} tài khoản thành công!`);
            loadUsers();
        } else {
            alert(data.message || 'Có lỗi xảy ra');
        }
    } catch (error) {
        console.error('Error updating user status:', error);
        alert('Có lỗi xảy ra');
    }
}

function getRoleText(role) {
    const roleMap = {
        'customer': 'Khách hàng',
        'staff': 'Nhân viên',
        'admin': 'Quản trị viên'
    };
    return roleMap[role] || role;
}

function getStatusText(status) {
    const statusMap = {
        'active': 'Hoạt động',
        'inactive': 'Không hoạt động',
        'banned': 'Đã khóa'
    };
    return statusMap[status] || status;
}

function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString('vi-VN');
}
</script>
@endpush
