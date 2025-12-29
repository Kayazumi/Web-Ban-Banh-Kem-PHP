@extends('layouts.admin')

@section('page-title', 'Quản lý sản phẩm')

@section('content')
<div class="admin-content">
    <div class="content-header">
        <h2>Quản lý sản phẩm</h2>
        <button class="btn btn-primary" onclick="showAddProductModal()">
            <i class="fas fa-plus"></i> Thêm sản phẩm mới
        </button>
    </div>

    <!-- Filters -->
    <div class="filters">
        <div class="filter-group">
            <label for="categoryFilter">Danh mục:</label>
            <select id="categoryFilter" class="form-control">
                <option value="">Tất cả danh mục</option>
            </select>
        </div>

        <div class="filter-group">
            <label for="statusFilter">Trạng thái:</label>
            <select id="statusFilter" class="form-control">
                <option value="">Tất cả</option>
                <option value="available">Còn hàng</option>
                <option value="out_of_stock">Hết hàng</option>
                <option value="discontinued">Ngừng bán</option>
            </select>
        </div>

        <div class="filter-group">
            <label for="searchInput">Tìm kiếm:</label>
            <input type="text" id="searchInput" class="form-control" placeholder="Tên sản phẩm...">
        </div>

        <button id="applyFilters" class="btn btn-secondary">Lọc</button>
    </div>

    <!-- Products Table -->
    <div class="table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Hình ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Danh mục</th>
                    <th>Giá</th>
                    <th>Tồn kho</th>
                    <th>Trạng thái</th>
                    <th>Nổi bật</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody id="productsTableBody">
                <tr>
                    <td colspan="9" class="text-center">Đang tải...</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div id="pagination" class="pagination-container"></div>
</div>

<!-- Add/Edit Product Modal -->
<div id="productModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">Thêm sản phẩm mới</h3>
            <button class="close-btn" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="productForm">
                <input type="hidden" id="productId" name="productId">

                <div class="form-row">
                    <div class="form-group">
                        <label for="productName">Tên sản phẩm *</label>
                        <input type="text" id="productName" name="productName" required>
                    </div>

                    <div class="form-group">
                        <label for="categoryId">Danh mục *</label>
                        <select id="categoryId" name="categoryId" required>
                            <option value="">Chọn danh mục</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="price">Giá (VNĐ) *</label>
                        <input type="number" id="price" name="price" min="0" required>
                    </div>

                    <div class="form-group">
                        <label for="originalPrice">Giá gốc (VNĐ)</label>
                        <input type="number" id="originalPrice" name="originalPrice" min="0">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="quantity">Số lượng *</label>
                        <input type="number" id="quantity" name="quantity" min="0" required>
                    </div>

                    <div class="form-group">
                        <label for="unit">Đơn vị</label>
                        <input type="text" id="unit" name="unit" value="cái">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="status">Trạng thái</label>
                        <select id="status" name="status">
                            <option value="available">Còn hàng</option>
                            <option value="out_of_stock">Hết hàng</option>
                            <option value="discontinued">Ngừng bán</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="imageUrl">URL hình ảnh</label>
                        <input type="url" id="imageUrl" name="imageUrl" placeholder="https://...">
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Mô tả</label>
                    <textarea id="description" name="description" rows="4"></textarea>
                </div>

                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" id="isFeatured" name="isFeatured">
                        Sản phẩm nổi bật
                    </label>
                    <label class="checkbox-label">
                        <input type="checkbox" id="isNew" name="isNew">
                        Sản phẩm mới
                    </label>
                    <label class="checkbox-label">
                        <input type="checkbox" id="isActive" name="isActive" checked>
                        Hiển thị sản phẩm
                    </label>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Lưu</button>
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Hủy</button>
                </div>
            </form>
        </div>
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

.admin-table img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 4px;
}

.status-badge {
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    font-size: 0.875rem;
    font-weight: bold;
}

.status-available {
    background: #d4edda;
    color: #155724;
}

.status-out_of_stock {
    background: #f8d7da;
    color: #721c24;
}

.status-discontinued {
    background: #e2e3e5;
    color: #383d41;
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

.pagination-container {
    display: flex;
    justify-content: center;
    margin-top: 2rem;
}

.pagination {
    display: flex;
    list-style: none;
    gap: 0.5rem;
}

.pagination a,
.pagination span {
    padding: 0.5rem 0.75rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    text-decoration: none;
    color: #333;
}

.pagination .active span {
    background: #007bff;
    color: white;
    border-color: #007bff;
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
}

.modal-content {
    background-color: white;
    margin: 5% auto;
    padding: 0;
    border-radius: 8px;
    width: 90%;
    max-width: 800px;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-header {
    padding: 1.5rem;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h3 {
    margin: 0;
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

.form-row {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
}

.form-row .form-group {
    flex: 1;
}

.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1rem;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-right: 1rem;
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
}

.text-center {
    text-align: center;
}

@media (max-width: 768px) {
    .form-row {
        flex-direction: column;
    }

    .content-header {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }
}
</style>
@endpush

@push('scripts')
<script>
let currentPage = 1;
let currentFilters = {};

document.addEventListener('DOMContentLoaded', function() {
    loadCategories();
    loadProducts();

    // Form submission
    document.getElementById('productForm').addEventListener('submit', handleProductSubmit);

    // Filters
    document.getElementById('applyFilters').addEventListener('click', applyFilters);
    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            applyFilters();
        }
    });
});

async function loadCategories() {
    try {
        const response = await fetch('/api/categories');
        const data = await response.json();

        if (data.success) {
            const categorySelect = document.getElementById('categoryId');
            const categoryFilter = document.getElementById('categoryFilter');

            data.data.categories.forEach(category => {
                // For modal
                const option1 = document.createElement('option');
                option1.value = category.CategoryID;
                option1.textContent = category.category_name;
                categorySelect.appendChild(option1);

                // For filter
                const option2 = document.createElement('option');
                option2.value = category.category_name;
                option2.textContent = category.category_name;
                categoryFilter.appendChild(option2);
            });
        }
    } catch (error) {
        console.error('Error loading categories:', error);
    }
}

async function loadProducts(page = 1) {
    try {
        const params = new URLSearchParams({
            page: page,
            ...currentFilters
        });

        const response = await fetch(`/api/admin/products?${params}`);
        const data = await response.json();

        const tbody = document.getElementById('productsTableBody');
        const pagination = document.getElementById('pagination');

        if (data.success && data.data.length > 0) {
            tbody.innerHTML = data.data.map(product => `
                <tr>
                    <td>${product.ProductID}</td>
                    <td>
                        <img src="${product.image_url || '/images/placeholder.jpg'}"
                             alt="${product.product_name}">
                    </td>
                    <td>${product.product_name}</td>
                    <td>${product.category_name || 'Chưa phân loại'}</td>
                    <td>${formatPrice(product.price)}</td>
                    <td>${product.quantity}</td>
                    <td>
                        <span class="status-badge status-${product.status}">
                            ${getStatusText(product.status)}
                        </span>
                    </td>
                    <td>
                        ${product.is_featured ? '<i class="fas fa-star text-warning"></i>' : ''}
                        ${product.is_new ? '<i class="fas fa-sparkles text-info"></i>' : ''}
                    </td>
                    <td>
                        <button onclick="editProduct(${product.ProductID})" class="btn btn-secondary btn-sm">
                            <i class="fas fa-edit"></i> Sửa
                        </button>
                        <button onclick="deleteProduct(${product.ProductID})" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i> Xóa
                        </button>
                    </td>
                </tr>
            `).join('');

            // Pagination would be implemented here
            pagination.innerHTML = '';
        } else {
            tbody.innerHTML = '<tr><td colspan="9" class="text-center">Không có sản phẩm nào</td></tr>';
            pagination.innerHTML = '';
        }
    } catch (error) {
        console.error('Error loading products:', error);
        document.getElementById('productsTableBody').innerHTML =
            '<tr><td colspan="9" class="text-center text-danger">Lỗi tải dữ liệu</td></tr>';
    }
}

function applyFilters() {
    const category = document.getElementById('categoryFilter').value;
    const status = document.getElementById('statusFilter').value;
    const search = document.getElementById('searchInput').value.trim();

    currentFilters = {};

    if (search) currentFilters.search = search;
    if (category) currentFilters.category = category;
    if (status) currentFilters.status = status;

    currentPage = 1;
    loadProducts(1);
}

function showAddProductModal() {
    document.getElementById('modalTitle').textContent = 'Thêm sản phẩm mới';
    document.getElementById('productForm').reset();
    document.getElementById('productId').value = '';
    document.getElementById('productModal').style.display = 'block';
}

function editProduct(productId) {
    // Load product data and show modal
    fetch(`/api/admin/products/${productId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const product = data.data;
                document.getElementById('modalTitle').textContent = 'Chỉnh sửa sản phẩm';
                document.getElementById('productId').value = product.ProductID;
                document.getElementById('productName').value = product.product_name;
                document.getElementById('categoryId').value = product.category_id;
                document.getElementById('price').value = product.price;
                document.getElementById('originalPrice').value = product.original_price || '';
                document.getElementById('quantity').value = product.quantity;
                document.getElementById('unit').value = product.unit;
                document.getElementById('status').value = product.status;
                document.getElementById('imageUrl').value = product.image_url || '';
                document.getElementById('description').value = product.description || '';
                document.getElementById('isFeatured').checked = product.is_featured;
                document.getElementById('isNew').checked = product.is_new;
                document.getElementById('isActive').checked = product.is_active;

                document.getElementById('productModal').style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error loading product:', error);
            alert('Lỗi tải dữ liệu sản phẩm');
        });
}

async function handleProductSubmit(e) {
    e.preventDefault();

    const formData = new FormData(e.target);
    const productId = formData.get('productId');

    // Convert FormData to JSON
    const data = {};
    for (let [key, value] of formData.entries()) {
        if (key === 'isFeatured' || key === 'isNew' || key === 'isActive') {
            data[key] = value === 'on';
        } else if (key !== 'productId') {
            data[key] = value;
        }
    }

    try {
        const url = productId ? `/api/admin/products/${productId}` : '/api/admin/products';
        const method = productId ? 'PUT' : 'POST';

        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.Laravel.csrfToken
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (result.success) {
            alert(productId ? 'Cập nhật sản phẩm thành công!' : 'Thêm sản phẩm thành công!');
            closeModal();
            loadProducts();
        } else {
            alert(result.message || 'Có lỗi xảy ra');
        }
    } catch (error) {
        console.error('Error saving product:', error);
        alert('Có lỗi xảy ra');
    }
}

async function deleteProduct(productId) {
    if (!confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')) return;

    try {
        const response = await fetch(`/api/admin/products/${productId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': window.Laravel.csrfToken
            }
        });

        const data = await response.json();

        if (data.success) {
            alert('Xóa sản phẩm thành công!');
            loadProducts();
        } else {
            alert(data.message || 'Có lỗi xảy ra');
        }
    } catch (error) {
        console.error('Error deleting product:', error);
        alert('Có lỗi xảy ra');
    }
}

function closeModal() {
    document.getElementById('productModal').style.display = 'none';
}

function getStatusText(status) {
    const statusMap = {
        'available': 'Còn hàng',
        'out_of_stock': 'Hết hàng',
        'discontinued': 'Ngừng bán'
    };
    return statusMap[status] || status;
}

function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(price);
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('productModal');
    if (event.target === modal) {
        closeModal();
    }
}
</script>
@endpush
