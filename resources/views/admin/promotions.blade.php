@extends('layouts.admin')

@section('page-title','Quản lý khuyến mãi')

@section('content')
<div class="admin-content">
    <!-- Form tạo khuyến mãi mới -->
    <div class="promo-form-card">
        <h3 class="form-title">Tạo khuyến mãi mới</h3>
        <form id="promoForm">
            <div class="form-row">
                <div class="form-group">
                    <label>Mã khuyến mãi</label>
                    <input name="code" placeholder="Nhập mã khuyến mãi..." required>
                </div>
                <div class="form-group" style="flex:2">
                    <label>Tên khuyến mãi</label>
                    <input name="title" placeholder="Nhập tên khuyến mãi..." required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Loại khuyến mãi</label>
                    <select name="type" required>
                        <option value="">Chọn loại khuyến mãi</option>
                        <option value="percent">Giảm giá %</option>
                        <option value="fixed">Giảm tiền</option>
                        <option value="free_shipping">Miễn phí vận chuyển</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Ngày bắt đầu</label>
                    <input name="start_date" type="date" placeholder="mm/dd/yyyy" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Ngày kết thúc</label>
                    <input name="end_date" type="date" placeholder="mm/dd/yyyy" required>
                </div>
                <div class="form-group">
                    <label>Giá trị giảm</label>
                    <input name="value" placeholder="Nhập giá trị..." type="number" step="0.01" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Số lượng mã</label>
                    <input name="quantity" placeholder="Nhập số lượng..." type="number" required>
                </div>
                <div class="form-group">
                    <label>Điều kiện áp dụng</label>
                    <input name="min_order" placeholder="VD: Đơn hàng tối thiểu 500,000đ" type="number" step="0.01">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group full-width">
                    <label>URL hình ảnh</label>
                    <input id="promoImageUrl" name="image_url" placeholder="VD: assets/images/promo.jpg">
                    <small style="color: #666; display: block; margin-top: 0.25rem;">Hoặc tải ảnh lên từ máy:</small>
                    <input type="file" id="promoImageFile" name="imageFile" accept="image/*" style="margin-top: 0.5rem;">
                </div>
            </div>
            
            <!-- Image Preview -->
            <div id="promoImagePreview" class="form-group" style="display: none;">
                <label>Xem trước hình ảnh</label>
                <img id="promoPreviewImg" src="" alt="Preview" style="max-width: 200px; max-height: 200px; border-radius: 8px; border: 1px solid #ddd;">
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn-create">Tạo khuyến mãi mới</button>
            </div>
        </form>
    </div>
    
    <!-- Danh sách khuyến mãi -->
    <div class="promo-list-section">
        <h3 class="section-title">Danh sách khuyến mãi</h3>
        
        <!-- Tabs -->
        <div class="promo-tabs">
            <button class="tab-btn active" onclick="filterPromos('all')">Tất cả</button>
            <button class="tab-btn" onclick="filterPromos('pending')">Chưa áp dụng</button>
            <button class="tab-btn" onclick="filterPromos('active')">Đang áp dụng</button>
            <button class="tab-btn" onclick="filterPromos('expired')">Đã phát hết</button>
        </div>
        
        <!-- Promo Cards -->
        <div id="promotionsList" class="promo-grid"></div>
    </div>
    
    <!-- Modal chi tiết -->
    <div id="promoDetailModal" class="modal">
        <div class="modal-content promo-detail-modal">
            <div class="modal-header">
                <h3>Chi tiết khuyến mãi</h3>
                <button class="close-btn" onclick="closePromoModal()">&times;</button>
            </div>
            <div class="modal-body" id="promoDetailBody">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button class="btn btn-delete" onclick="deletePromo()">Xóa</button>
                <button class="btn btn-secondary" onclick="closePromoModal()">Đóng</button>
                <button class="btn btn-primary" id="editPromoBtn">Chỉnh sửa</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.page-heading {
    font-size: 20px;
    font-weight: 600;
    color: #333;
    margin-bottom: 24px;
    letter-spacing: 0.5px;
}

/* Form Card */
.promo-form-card {
    background: #fff;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 32px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.form-title {
    font-size: 16px;
    font-weight: 600;
    color: #333;
    margin-bottom: 20px;
}

.form-row {
    display: flex;
    gap: 16px;
    margin-bottom: 16px;
}

.form-group {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.form-group.full-width {
    flex: 1 1 100%;
}

.form-group label {
    font-size: 14px;
    font-weight: 500;
    color: #555;
    margin-bottom: 6px;
}

.form-group input,
.form-group select {
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    transition: border-color 0.2s;
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: #4a6741;
}

.form-group input::placeholder {
    color: #999;
}

.form-actions {
    display: flex;
    justify-content: flex-start;
    margin-top: 20px;
}

.btn-create {
    background: #4a6741;
    color: white;
    border: none;
    padding: 11px 24px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.2s;
}

.btn-create:hover {
    background: #3d5536;
}

/* Promo List Section */
.promo-list-section {
    margin-top: 32px;
}

.section-title {
    font-size: 16px;
    font-weight: 600;
    color: #333;
    margin-bottom: 16px;
}

/* Tabs */
.promo-tabs {
    display: flex;
    gap: 12px;
    margin-bottom: 24px;
}

.tab-btn {
    padding: 10px 20px;
    border: 1px solid #ddd;
    background: #fff;
    border-radius: 20px;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s;
    color: #555;
}

.tab-btn.active {
    background: #4a6741;
    color: white;
    border-color: #4a6741;
}

.tab-btn:hover:not(.active) {
    background: #f5f5f5;
}

/* Promo Grid */
.promo-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
}

.promo-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transition: transform 0.2s, box-shadow 0.2s;
    position: relative;
}

.promo-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.12);
}

.promo-card-image {
    width: 100%;
    height: 160px;
    object-fit: cover;
    background: #f0f0f0;
}

.promo-card-body {
    padding: 16px;
}

.promo-status-badge {
    position: absolute;
    top: 12px;
    right: 12px;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
    background: #4a6741;
    color: white;
}

.promo-status-badge.pending {
    background: #666;
}

.promo-status-badge.expired {
    background: #999;
}

.promo-card-title {
    font-size: 15px;
    font-weight: 600;
    color: #333;
    margin-bottom: 12px;
}

.promo-card-info {
    font-size: 13px;
    color: #666;
    margin-bottom: 6px;
    display: flex;
    justify-content: space-between;
}

.promo-card-info strong {
    color: #333;
    font-weight: 500;
}

.promo-card-actions {
    margin-top: 14px;
    padding-top: 14px;
    border-top: 1px solid #f0f0f0;
}

.btn-detail {
    width: 100%;
    padding: 8px;
    background: #4a6741;
    color: white;
    border: none;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.2s;
}

.btn-detail:hover {
    background: #3d5536;
}

/* Modal */
.modal {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.6);
    z-index: 1000;
    align-items: center;
    justify-content: center;
}

.modal-content {
    background: #fff;
    width: 90%;
    max-width: 500px;
    max-height: 90vh;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 8px 32px rgba(0,0,0,0.2);
}

.modal-header {
    padding: 20px 24px;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h3 {
    font-size: 18px;
    font-weight: 600;
    color: #333;
    margin: 0;
}

.close-btn {
    background: none;
    border: none;
    font-size: 28px;
    color: #999;
    cursor: pointer;
    line-height: 1;
    padding: 0;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.close-btn:hover {
    color: #666;
}

.modal-body {
    padding: 24px;
    max-height: calc(90vh - 180px);
    overflow-y: auto;
}

.promo-detail-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 8px;
    margin-bottom: 20px;
    background: #f0f0f0;
}

.detail-info-row {
    display: flex;
    padding: 10px 0;
    border-bottom: 1px solid #f0f0f0;
    font-size: 14px;
}

.detail-info-row:last-child {
    border-bottom: none;
}

.detail-label {
    flex: 0 0 180px;
    color: #666;
    font-weight: 500;
}

.detail-value {
    flex: 1;
    color: #333;
}

.status-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 13px;
    font-weight: 500;
}

.status-badge.active {
    background: #d4edda;
    color: #155724;
}

.status-badge.pending {
    background: #fff3cd;
    color: #856404;
}

.status-badge.expired {
    background: #f8d7da;
    color: #721c24;
}

.modal-footer {
    padding: 16px 24px;
    border-top: 1px solid #eee;
    display: flex;
    gap: 10px;
    justify-content: flex-end;
}

.btn {
    padding: 9px 20px;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-delete {
    background: #f0f0f0;
    color: #666;
}

.btn-delete:hover {
    background: #e0e0e0;
}

.btn-secondary {
    background: #f0f0f0;
    color: #666;
}

.btn-secondary:hover {
    background: #e0e0e0;
}

.btn-primary {
    background: #4a6741;
    color: white;
}

.btn-primary:hover {
    background: #3d5536;
}

/* Responsive */
@media (max-width: 768px) {
    .form-row {
        flex-direction: column;
    }
    
    .promo-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@push('scripts')
<script>
let currentFilter = 'all';
let allPromotions = [];

async function loadPromotions() {
    try {
        const token = localStorage.getItem('api_token');
        const headers = {'Accept':'application/json'};
        if (token) headers['Authorization'] = `Bearer ${token}`;
        const res = await fetch('/api/admin/promotions',{ headers });
        const payload = await res.json();
        
        if (payload.success) {
            allPromotions = payload.data.promotions;
            renderPromotions();
        } else {
            document.getElementById('promotionsList').innerHTML = '<div style="padding:20px;text-align:center;color:#999">Không có khuyến mãi</div>';
        }
    } catch(e){ 
        console.error(e);
        document.getElementById('promotionsList').innerHTML = '<div style="padding:20px;text-align:center;color:#999">Lỗi tải dữ liệu</div>';
    }
}

function getPromoStatus(promo) {
    const now = new Date();
    const start = new Date(promo.start_date);
    const end = new Date(promo.end_date);
    
    if (now < start) return 'pending';
    if (now > end) return 'expired';
    if (promo.usage_count >= promo.quantity) return 'expired';
    return 'active';
}

function getStatusText(status) {
    switch(status) {
        case 'pending': return 'Chưa áp dụng';
        case 'active': return 'Đang áp dụng';
        case 'expired': return 'Đã phát hết';
        default: return '';
    }
}

function renderPromotions() {
    const container = document.getElementById('promotionsList');
    let filteredPromos = allPromotions;
    
    if (currentFilter !== 'all') {
        filteredPromos = allPromotions.filter(p => getPromoStatus(p) === currentFilter);
    }
    
    if (filteredPromos.length === 0) {
        container.innerHTML = '<div style="padding:40px;text-align:center;color:#999;grid-column:1/-1">Không có khuyến mãi</div>';
        return;
    }
    
    container.innerHTML = filteredPromos.map(p => {
        const status = getPromoStatus(p);
        const statusText = getStatusText(status);
        const typeText = p.promotion_type === 'percentage' ? 'Giảm giá %' : (p.promotion_type === 'fixed_amount' ? 'Giảm giá tiền' : 'Miễn phí vận chuyển');
        const valueText = p.promotion_type === 'percentage' ? `${p.discount_value}%` : `${parseFloat(p.discount_value || 0).toLocaleString()}đ`;
        
        return `
            <div class="promo-card">
                <span class="promo-status-badge ${status}">${statusText}</span>
                <img src="${p.image_url || '/images/placeholder.jpg'}" alt="${p.promotion_name || p.promotion_code}" class="promo-card-image" onerror="this.src='/images/placeholder.jpg'">
                <div class="promo-card-body">
                    <div class="promo-card-title">Mã: ${p.promotion_code}</div>
                    <div class="promo-card-info">
                        <span><strong>Tên:</strong> ${p.promotion_name || p.promotion_code}</span>
                    </div>
                    <div class="promo-card-info">
                        <span><strong>Loại:</strong> ${typeText}</span>
                    </div>
                    <div class="promo-card-info">
                        <span><strong>Giá trị:</strong> ${valueText}</span>
                    </div>
                    <div class="promo-card-info">
                        <span><strong>Số lượng:</strong> ${p.used_count || 0}/${p.quantity || 0}</span>
                    </div>
                    <div class="promo-card-info">
                        <span><strong>Thời gian:</strong> ${formatDate(p.start_date)} - ${formatDate(p.end_date)}</span>
                    </div>
                    <div class="promo-card-actions">
                        <button class="btn-detail" onclick="showPromo(${p.PromotionID})">Chi tiết</button>
                    </div>
                </div>
            </div>
        `;
    }).join('');
}

function formatDate(dateStr) {
    if (!dateStr) return '';
    const d = new Date(dateStr);
    const day = String(d.getDate()).padStart(2, '0');
    const month = String(d.getMonth() + 1).padStart(2, '0');
    const year = d.getFullYear();
    return `${day}/${month}/${year}`;
}

function filterPromos(filter) {
    currentFilter = filter;
    
    // Update active tab
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
    
    renderPromotions();
}

document.addEventListener('DOMContentLoaded', function(){
    loadPromotions();
    
    // Image preview handlers
    document.getElementById('promoImageFile').addEventListener('change', handlePromoImageFileChange);
    document.getElementById('promoImageUrl').addEventListener('input', handlePromoImageUrlChange);
    
    document.getElementById('promoForm').addEventListener('submit', async function(e){
        e.preventDefault();
        const form = new FormData(e.target);
        const imageFile = form.get('imageFile');
        const body = {};
        
        form.forEach((v,k) => {
            if (k !== 'imageFile') {
                body[k] = v;
            }
        });
        
        // Handle image upload
        if (imageFile && imageFile.size > 0) {
            try {
                const base64 = await fileToBase64(imageFile);
                body.image_url = base64;
            } catch (err) {
                console.error('Error converting image:', err);
                alert('Lỗi khi xử lý hình ảnh');
                return;
            }
        }
        
        // Set default description from title
        if (!body.description) {
            body.description = body.title;
        }
        
        try {
            const token = localStorage.getItem('api_token');
            const csrf = window.Laravel && window.Laravel.csrfToken ? window.Laravel.csrfToken : (document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '');
            const headers = {'Content-Type':'application/json','Accept':'application/json'};
            if (token) headers['Authorization'] = `Bearer ${token}`;
            if (csrf) headers['X-CSRF-TOKEN'] = csrf;
            
            const res = await fetch('/api/admin/promotions',{ method:'POST', headers, body: JSON.stringify(body) });
            const data = await res.json();
            
            if (data.success) {
                alert('Tạo khuyến mãi thành công!');
                loadPromotions();
                e.target.reset();
                document.getElementById('promoImagePreview').style.display = 'none';
            } else {
                alert('Lỗi: ' + (data.message || 'Không thể tạo khuyến mãi'));
            }
        } catch(err){
            console.error(err);
            alert('Lỗi kết nối');
        }
    });
});

function handlePromoImageFileChange(e) {
    const file = e.target.files[0];
    if (file) {
        document.getElementById('promoImageUrl').value = '';
        
        const reader = new FileReader();
        reader.onload = function(event) {
            document.getElementById('promoPreviewImg').src = event.target.result;
            document.getElementById('promoImagePreview').style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        document.getElementById('promoImagePreview').style.display = 'none';
    }
}

function handlePromoImageUrlChange(e) {
    const url = e.target.value.trim();
    if (url) {
        document.getElementById('promoImageFile').value = '';
        
        document.getElementById('promoPreviewImg').src = url;
        document.getElementById('promoImagePreview').style.display = 'block';
    } else {
        document.getElementById('promoImagePreview').style.display = 'none';
    }
}

function fileToBase64(file) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onload = () => resolve(reader.result);
        reader.onerror = error => reject(error);
        reader.readAsDataURL(file);
    });
}

let currentPromoId = null;

async function showPromo(id) {
    try {
        const token = localStorage.getItem('api_token');
        const headers = {'Accept':'application/json'};
        if (token) headers['Authorization']=`Bearer ${token}`;
        
        const res = await fetch('/api/admin/promotions/'+id,{ headers });
        const payload = await res.json();
        
        if (!payload.success) return alert('Không tải được thông tin');
        
        const p = payload.data.promotion;
        currentPromoId = p.PromotionID;
        
        const status = getPromoStatus(p);
        const statusText = getStatusText(status);
        const typeText = p.promotion_type === 'percentage' ? 'Giảm giá %' : (p.promotion_type === 'fixed_amount' ? 'Giảm giá tiền' : 'Miễn phí vận chuyển');
        const valueText = p.promotion_type === 'percentage' ? `${p.discount_value}%` : `${parseFloat(p.discount_value || 0).toLocaleString()}đ`;
        const minOrderText = p.min_order_value ? `${parseFloat(p.min_order_value).toLocaleString()} đ` : 'Không có';
        
        document.getElementById('promoDetailBody').innerHTML = `
            <div>
                <h4 style="margin-bottom:12px;font-weight:500">Hình ảnh:</h4>
                <img src="${p.image_url || '/images/placeholder.jpg'}" alt="${p.promotion_name || p.promotion_code}" class="promo-detail-image" onerror="this.src='/images/placeholder.jpg'">
            </div>
            
            <div class="detail-info-row">
                <div class="detail-label">Mã khuyến mãi:</div>
                <div class="detail-value"><strong>${p.promotion_code}</strong></div>
            </div>
            
            <div class="detail-info-row">
                <div class="detail-label">Tên khuyến mãi:</div>
                <div class="detail-value">${p.promotion_name || p.promotion_code}</div>
            </div>
            
            <div class="detail-info-row">
                <div class="detail-label">Mô tả:</div>
                <div class="detail-value">${p.description || p.promotion_name || p.promotion_code}</div>
            </div>
            
            <div class="detail-info-row">
                <div class="detail-label">Loại:</div>
                <div class="detail-value">${typeText}</div>
            </div>
            
            <div class="detail-info-row">
                <div class="detail-label">Giá trị giảm:</div>
                <div class="detail-value"><strong>${valueText}</strong></div>
            </div>
            
            <div class="detail-info-row">
                <div class="detail-label">Đơn hàng tối thiểu:</div>
                <div class="detail-value">${minOrderText}</div>
            </div>
            
            <div class="detail-info-row">
                <div class="detail-label">Số lượng:</div>
                <div class="detail-value">${p.quantity || 0}</div>
            </div>
            
            <div class="detail-info-row">
                <div class="detail-label">Đã sử dụng:</div>
                <div class="detail-value">${p.used_count || 0}</div>
            </div>
            
            <div class="detail-info-row">
                <div class="detail-label">Thời gian:</div>
                <div class="detail-value">${formatDate(p.start_date)} - ${formatDate(p.end_date)}</div>
            </div>
            
            <div class="detail-info-row">
                <div class="detail-label">Trạng thái:</div>
                <div class="detail-value"><span class="status-badge ${status}">${statusText}</span></div>
            </div>
        `;
        
        document.getElementById('promoDetailModal').style.display='flex';
    } catch(e){
        console.error(e);
        alert('Lỗi tải thông tin');
    }
}

function closePromoModal(){
    document.getElementById('promoDetailModal').style.display='none';
    currentPromoId=null;
}

async function deletePromo(){
    if (!confirm('Bạn có chắc chắn muốn xóa khuyến mãi này?')) return;
    
    try {
        const token = localStorage.getItem('api_token');
        const csrf = window.Laravel && window.Laravel.csrfToken ? window.Laravel.csrfToken : (document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '');
        const headers = {'Accept':'application/json'};
        if (token) headers['Authorization']=`Bearer ${token}`;
        if (csrf) headers['X-CSRF-TOKEN']=csrf;
        
        const res = await fetch('/api/admin/promotions/'+currentPromoId,{ method:'DELETE', headers });
        const data = await res.json();
        
        if (data.success) {
            alert('Đã xóa khuyến mãi thành công!');
            closePromoModal();
            loadPromotions();
        } else {
            alert('Lỗi: ' + (data.message || 'Không thể xóa'));
        }
    } catch(e){
        console.error(e);
        alert('Lỗi kết nối');
    }
}

// Close modal when clicking outside
document.getElementById('promoDetailModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePromoModal();
    }
});
</script>
@endpush
