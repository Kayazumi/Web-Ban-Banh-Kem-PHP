@extends('layouts.admin')

@section('page-title','Quản lý khuyến mãi')

@section('content')
<div class="admin-content">
    <div class="card" style="padding:16px;margin-bottom:18px;">
        <form id="promoForm">
            <div style="display:flex;gap:12px;flex-wrap:wrap">
                <input name="code" placeholder="Mã khuyến mãi" style="flex:1;padding:8px">
                <input name="title" placeholder="Tên khuyến mãi" style="flex:2;padding:8px">
                <select name="type" style="padding:8px">
                    <option value="percent">Giảm %</option>
                    <option value="fixed">Giảm tiền</option>
                    <option value="free_shipping">Miễn phí vận chuyển</option>
                </select>
                <input name="value" placeholder="Giá trị" style="width:140px;padding:8px">
                <input name="start_date" placeholder="Ngày bắt đầu" type="date" style="padding:8px">
                <input name="end_date" placeholder="Ngày kết thúc" type="date" style="padding:8px">
            </div>
            <div style="margin-top:12px">
                <input name="min_order" placeholder="Đơn hàng tối thiểu" style="padding:8px;width:220px">
                <input name="quantity" placeholder="Số lượng" style="padding:8px;width:220px;margin-left:8px">
                <input name="image_url" placeholder="URL hình ảnh" style="padding:8px;width:420px;margin-left:8px">
                <button class="btn btn-primary" type="submit" style="margin-left:8px">Tạo khuyến mãi mới</button>
            </div>
        </form>
    </div>

    <div id="promotionsList" style="display:flex;gap:16px;flex-wrap:wrap"></div>

    <!-- detail modal -->
    <div id="promoDetailModal" class="modal">
        <div class="modal-content">
            <div class="modal-header"><h3>Chi tiết khuyến mãi</h3><button class="close-btn" onclick="closePromoModal()">&times;</button></div>
            <div class="modal-body" id="promoDetailBody"></div>
            <div style="padding:12px;border-top:1px solid #eee;display:flex;gap:8px;justify-content:flex-end">
                <button class="btn btn-danger" onclick="deletePromo()">Xóa</button>
                <button class="btn btn-secondary" onclick="closePromoModal()">Đóng</button>
                <button class="btn btn-primary" id="editPromoBtn">Chỉnh sửa</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.promo-card { width: 260px; background:#fff;border-radius:8px;padding:12px;box-shadow:0 2px 8px rgba(0,0,0,.06); }
.promo-card h4{margin:0 0 8px}
.modal { display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:1000; }
.modal-content{ background:#fff;width:90%;max-width:800px;margin:4% auto;border-radius:8px;overflow:auto;}
</style>
@endpush

@push('scripts')
<script>
async function loadPromotions() {
    try {
        const token = localStorage.getItem('api_token');
        const headers = {'Accept':'application/json'};
        if (token) headers['Authorization'] = `Bearer ${token}`;
        const res = await fetch('/api/admin/promotions',{ headers });
        const payload = await res.json();
        const container = document.getElementById('promotionsList');
        container.innerHTML = '';
        if (payload.success) {
            payload.data.promotions.forEach(p=>{
                const el = document.createElement('div'); el.className='promo-card';
                el.innerHTML = `<h4>${p.title}</h4><div>Mã: <strong>${p.code}</strong></div><div>Giá trị: ${p.value}</div><div>Thời gian: ${p.start_date || ''} - ${p.end_date || ''}</div><div style="margin-top:8px"><button class="btn btn-secondary" onclick="showPromo(${p.id})">Chi tiết</button></div>`;
                container.appendChild(el);
            });
        } else {
            container.innerHTML = '<div>Không có khuyến mãi</div>';
        }
    } catch(e){ console.error(e); }
}

document.addEventListener('DOMContentLoaded', function(){
    loadPromotions();
    document.getElementById('promoForm').addEventListener('submit', async function(e){
        e.preventDefault();
        const form = new FormData(e.target);
        const body = {};
        form.forEach((v,k)=> body[k]=v);
        try {
            const token = localStorage.getItem('api_token');
            const csrf = window.Laravel && window.Laravel.csrfToken ? window.Laravel.csrfToken : (document.querySelector('meta[name=\"csrf-token\"]') ? document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content') : '');
            const headers = {'Content-Type':'application/json','Accept':'application/json'};
            if (token) headers['Authorization'] = `Bearer ${token}`;
            if (csrf) headers['X-CSRF-TOKEN'] = csrf;
            const res = await fetch('/api/admin/promotions',{ method:'POST', headers, body: JSON.stringify(body) });
            const data = await res.json();
            if (data.success) { alert('Tạo thành công'); loadPromotions(); form.reset(); }
            else alert('Lỗi tạo');
        } catch(err){ console.error(err); alert('Lỗi'); }
    });
});

let currentPromoId = null;
async function showPromo(id) {
    try {
        const token = localStorage.getItem('api_token');
        const headers = {'Accept':'application/json'}; if (token) headers['Authorization']=`Bearer ${token}`;
        const res = await fetch('/api/admin/promotions/'+id,{ headers });
        const payload = await res.json();
        if (!payload.success) return alert('Không tải được');
        const p = payload.data.promotion;
        currentPromoId = p.id;
        document.getElementById('promoDetailBody').innerHTML = `<div style="display:flex;gap:12px"><div style="flex:1"><img src="${p.image_url||'/images/placeholder.jpg'}" style="max-width:220px"></div><div style="flex:2"><p><strong>${p.title}</strong></p><p>Mã: ${p.code}</p><p>Giá trị: ${p.value}</p><p>Loại: ${p.type}</p><p>Thời gian: ${p.start_date||''} - ${p.end_date||''}</p><p>Số lượng: ${p.quantity||0}</p></div></div>`;
        document.getElementById('promoDetailModal').style.display='block';
    } catch(e){ console.error(e); alert('Lỗi'); }
}
function closePromoModal(){ document.getElementById('promoDetailModal').style.display='none'; currentPromoId=null; }
async function deletePromo(){
    if (!confirm('Xóa khuyến mãi?')) return;
    try {
        const token = localStorage.getItem('api_token');
        const csrf = window.Laravel && window.Laravel.csrfToken ? window.Laravel.csrfToken : (document.querySelector('meta[name=\"csrf-token\"]') ? document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content') : '');
        const headers = {'Accept':'application/json'}; if (token) headers['Authorization']=`Bearer ${token}`; if (csrf) headers['X-CSRF-TOKEN']=csrf;
        const res = await fetch('/api/admin/promotions/'+currentPromoId,{ method:'DELETE', headers });
        const data = await res.json();
        if (data.success) { alert('Đã xóa'); closePromoModal(); loadPromotions(); }
        else alert('Lỗi xóa');
    } catch(e){ console.error(e); alert('Lỗi'); }
}
</script>
@endpush


