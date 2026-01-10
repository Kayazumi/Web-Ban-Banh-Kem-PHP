console.log('complaints.js loaded successfully!');

// ========================================================================
// QUẢN LÝ KHIẾU NẠI - NHÂN VIÊN (PHÂN TRANG CLIENT-SIDE)
// ========================================================================

let complaintsData = [];
let filteredComplaints = [];
let currentPage = 1;
const itemsPerPage = 10;

// ========================================================================
// 1. KHỞI TẠO
// ========================================================================
document.addEventListener('DOMContentLoaded', function () {
    console.log('DOM fully loaded - starting complaints page');
    loadComplaints();
    initEventListeners();
});

function initEventListeners() {
    const searchInput = document.getElementById('complaint-search');
    if (searchInput) {
        searchInput.addEventListener('input', debounce(() => applyFiltersAndPaginate(), 300));
    }

    document.querySelectorAll('.filter-checkbox input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', () => applyFiltersAndPaginate());
    });
}

// ========================================================================
// 2. TẢI DANH SÁCH KHIẾU NẠI
// ========================================================================
async function loadComplaints(resetPage = true) {
    if (resetPage) currentPage = 1;
    console.log('loadComplaints() called');

    try {
        // Tải toàn bộ dữ liệu (không truyền params search/status để lấy hết về client xử lý)
        const response = await fetch('/staff/api/complaints', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }

        const result = await response.json();
        complaintsData = result.data || [];
        applyFiltersAndPaginate();

    } catch (error) {
        console.error('Lỗi khi tải danh sách khiếu nại:', error);
        const tbody = document.getElementById('complaints-table-body');
        if(tbody) tbody.innerHTML = `<tr><td colspan="7" style="text-align:center; padding:40px; color:red;">Lỗi kết nối: ${error.message}</td></tr>`;
    }
}

// ========================================================================
// ÁP DỤNG FILTER + PHÂN TRANG
// ========================================================================
function applyFiltersAndPaginate() {
    const searchTerm = document.getElementById('complaint-search')?.value.toLowerCase().trim() || '';
    
    // Lấy các checkbox được chọn
    const selectedStatuses = Array.from(document.querySelectorAll('.filter-checkbox input:checked'))
        .map(cb => cb.value)
        .filter(val => val !== 'all'); // Loại bỏ 'all' nếu có

    let temp = complaintsData.filter(item => {
        // Logic tìm kiếm
        const matchesSearch = !searchTerm ||
            (item.complaint_code && item.complaint_code.toLowerCase().includes(searchTerm)) ||
            (item.title && item.title.toLowerCase().includes(searchTerm)) ||
            (item.customer && item.customer.full_name && item.customer.full_name.toLowerCase().includes(searchTerm));

        // Logic lọc trạng thái
        // Nếu không chọn gì hoặc chọn 'all' (đã lọc ở trên) thì lấy hết, ngược lại check includes
        const matchesStatus = selectedStatuses.length === 0 || selectedStatuses.includes(item.status);

        return matchesSearch && matchesStatus;
    });

    const totalItems = temp.length;
    const totalPages = Math.ceil(totalItems / itemsPerPage);

    // Reset về trang 1 nếu trang hiện tại vượt quá tổng số trang
    if (currentPage > totalPages && totalPages > 0) currentPage = 1;

    const start = (currentPage - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    filteredComplaints = temp.slice(start, end);

    renderComplaints();
    renderPagination(totalPages, totalItems);
}

// ========================================================================
// 3. RENDER BẢNG KHIẾU NẠI
// ========================================================================

function renderComplaints() {
    const tbody = document.getElementById('complaints-table-body');
    if (!tbody) return;

    if (filteredComplaints.length === 0) {
        tbody.innerHTML = `
            <tr class="no-data">
                <td colspan="7" style="text-align:center; padding:40px; color:#888;">
                    <i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: 10px; display:block;"></i>
                    Không tìm thấy khiếu nại nào
                </td>
            </tr>
        `;
        return;
    }

    const statusText = {
        pending: 'Chờ xử lý',
        processing: 'Đang xử lý',
        resolved: 'Đã giải quyết'
    };

    tbody.innerHTML = filteredComplaints.map(item => {
        const complaintId = item.id || item.complaint_id || item.ComplaintID || item.complaintId;
        
        // ✅ THÊM Ở ĐÂY: Disable nút nếu status là 'resolved'
        const isResolved = item.status === 'resolved';
        const buttonDisabled = isResolved ? 'disabled' : '';
        const buttonOnClick = isResolved 
            ? ''  // Không gọi hàm khi disabled
            : `onclick="openComplaintModal(${complaintId})"`;
        const buttonTitle = isResolved ? 'Đã giải quyết - Không thể chỉnh sửa' : 'Xử lý';

        return `
            <tr>
                <td><span class="code-badge">${item.complaint_code || 'N/A'}</span></td>
                <td>${item.order?.order_code || 'N/A'}</td>
                <td>${item.customer?.full_name || 'Khách lạ'}</td>
                <td style="line-height:1.5; max-width:300px;">
                    <div class="truncate-text" title="${item.title || item.content}">${item.title || item.content || ''}</div>
                </td>
                <td>${formatDate(item.created_at)}</td>
                <td>
                    <span class="badge-status status-${item.status || 'unknown'}">
                        ${statusText[item.status] || item.status || 'Không rõ'}
                    </span>
                </td>
                <td style="text-align:center;">
                    <!-- ✅ Button được disable và không onclick nếu resolved -->
                    <button type="button" 
                            class="btn-process ${buttonDisabled ? 'btn-disabled' : ''}" 
                            ${buttonOnClick}
                            title="${buttonTitle}">
                        <i class="fas fa-edit"></i>
                    </button>
                </td>
            </tr>
        `;
    }).join('');
}

// ========================================================================
// 4. PHÂN TRANG (GIỐNG ORDERS.JS)
// ========================================================================
function renderPagination(totalPages, totalItems) {
    const oldPagination = document.querySelector('.pagination-controls');
    if (oldPagination) oldPagination.remove();

    if (totalPages <= 1) return;

    let html = `
        <div class="pagination-controls">
            <div class="pagination-info">
                Hiển thị ${(currentPage - 1) * itemsPerPage + 1} -
                ${Math.min(currentPage * itemsPerPage, totalItems)}
                của ${totalItems} khiếu nại
            </div>
            <div class="pagination-buttons">
    `;

    if (currentPage > 1) {
        html += `<button class="page-btn" onclick="changePage(${currentPage - 1})">&laquo; Trước</button>`;
    }

    if (totalPages <= 10) {
        for (let i = 1; i <= totalPages; i++) {
            html += i === currentPage 
                ? `<strong class="page-btn active">${i}</strong>`
                : `<button class="page-btn" onclick="changePage(${i})">${i}</button>`;
        }
    } else {
        for (let i = 1; i <= 3; i++) {
            html += i === currentPage
                ? `<strong class="page-btn active">${i}</strong>`
                : `<button class="page-btn" onclick="changePage(${i})">${i}</button>`;
        }
        html += `<span class="page-dots">...</span>`;
        
        html += currentPage === totalPages
            ? `<strong class="page-btn active">${totalPages}</strong>`
            : `<button class="page-btn" onclick="changePage(${totalPages})">${totalPages}</button>`;

        html += `
            <div class="page-jump">
                <input type="number" id="jumpPageInput" min="1" max="${totalPages}" value="${currentPage}" placeholder="Trang">
                <button class="page-btn" onclick="jumpToPage(${totalPages})">Go</button>
            </div>
        `;
    }

    if (currentPage < totalPages) {
        html += `<button class="page-btn" onclick="changePage(${currentPage + 1})">Sau &raquo;</button>`;
    }

    html += `</div></div>`;

    // Chèn vào sau bảng (tìm wrapper của bảng)
    const tableWrapper = document.querySelector('.complaints-table-wrapper') || document.querySelector('.table-responsive') || document.getElementById('complaints-table-body').parentNode.parentNode;
    if(tableWrapper) {
        tableWrapper.insertAdjacentHTML('beforeend', html);
    }
}

function jumpToPage(totalPages) {
    const input = document.getElementById('jumpPageInput');
    let page = parseInt(input.value);

    if (isNaN(page) || page < 1) page = 1;
    if (page > totalPages) page = totalPages;

    input.value = page;

    if (page !== currentPage) {
        currentPage = page;
        applyFiltersAndPaginate();
    }
}

function changePage(page) {
    currentPage = page;
    applyFiltersAndPaginate();
    // Scroll lên đầu bảng
    const table = document.getElementById('complaints-table-body');
    if(table) table.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

// ========================================================================
// 5. CÁC HÀM XỬ LÝ MODAL & API (GIỮ NGUYÊN LOGIC CŨ)
// ========================================================================
async function openComplaintModal(id) {
    console.log('openComplaintModal called with id:', id);

    if (!id) {
        alert('Lỗi: Không có ID khiếu nại');
        return;
    }

    // Gán ID để dùng cho save/send sau này
    currentComplaintId = id;

    try {
        const response = await fetch(`/staff/api/complaints/${id}`);
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }

        const result = await response.json();
        console.log('Full API response:', result); // <-- THÊM DÒNG NÀY để debug

        const data = result.data;
        if (!data) {
            alert('Không tìm thấy dữ liệu khiếu nại');
            return;
        }

        // Điền thông tin khách hàng & khiếu nại (giữ nguyên)
        document.getElementById('detail-customer-name').value = data.customer?.full_name || 'Khách lạ';
        document.getElementById('detail-customer-phone').value = data.customer?.phone || 'N/A';
        document.getElementById('detail-content').value = data.content || data.title || '';

        // ✅ SỬA: Đọc current_staff từ result (không phải result.data)
        const currentStaff = result.current_staff || {}; // Lấy trực tiếp từ root response
        document.getElementById('detail-staff-id').value = currentStaff.id || 'N/A';
        document.getElementById('detail-staff-name').value = currentStaff.full_name || 'Chưa có nhân viên phụ trách';

        // Trạng thái
        document.getElementById('detail-status').value = data.status || 'pending';

        // Phản hồi cũ
        document.getElementById('detail-response').value = data.response || '';

        // Disable nếu resolved (giữ nguyên nếu đã có)

        // Hiển thị modal
        document.getElementById('complaintModal').classList.add('show');
        document.body.style.overflow = 'hidden';

    } catch (error) {
        console.error('Lỗi mở modal:', error);
        alert('Không thể tải chi tiết. Kiểm tra console (F12).');
    }
}

// Đóng modal
function closeComplaintModal() {
    document.getElementById('complaintModal').classList.remove('show');
    document.getElementById('complaint-detail-form').reset();
    document.body.style.overflow = '';
    const saveBtn = document.querySelector('.btn-save');
    if (saveBtn) delete saveBtn.dataset.id;
}

// Lưu tạm (chưa gửi khách hàng)
async function saveDraft() {
    const responseContent = document.getElementById('detail-response').value.trim();
    if (!responseContent) {
        alert('Vui lòng nhập nội dung phản hồi!');
        return;
    }

    const btn = event.target;  // Hoặc document.querySelector('.btn-outline-modal')
    const originalText = btn.textContent;
    btn.disabled = true;
    btn.textContent = 'Đang lưu...';

    try {
        const response = await fetch(`/staff/api/complaints/${currentComplaintId}/respond`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                response_content: responseContent,
                send_to_customer: false  // ✅ Lưu tạm
            })
        });

        const result = await response.json();
        if (result.success) {
            alert(result.message);
            closeComplaintModal();
            loadComplaints();  // Reload để update status
        } else {
            alert(result.message || 'Lỗi lưu tạm!');
        }
    } catch (error) {
        console.error('Lỗi:', error);
        alert('Lỗi kết nối!');
    } finally {
        btn.disabled = false;
        btn.textContent = originalText;
    }
}

// ✅ SỬA: Function gửi khách (send_to_customer: true)
async function sendToCustomer() {
    const responseContent = document.getElementById('detail-response').value.trim();
    if (!responseContent) {
        alert('Vui lòng nhập nội dung phản hồi!');
        return;
    }

    if (!confirm('Bạn chắc chắn muốn gửi phản hồi này cho khách hàng và đóng khiếu nại?')) return;

    const btn = event.target;  // Hoặc document.querySelector('.btn-save-modal')
    const originalText = btn.textContent;
    btn.disabled = true;
    btn.textContent = 'Đang gửi...';

    try {
        const response = await fetch(`/staff/api/complaints/${currentComplaintId}/respond`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                response_content: responseContent,
                send_to_customer: true  // ✅ Gửi khách
            })
        });

        const result = await response.json();
        if (result.success) {
            alert(result.message);
            closeComplaintModal();
            loadComplaints();
        } else {
            alert(result.message || 'Lỗi gửi!');
        }
    } catch (error) {
        console.error('Lỗi:', error);
        alert('Lỗi kết nối!');
    } finally {
        btn.disabled = false;
        btn.textContent = originalText;
    }
}

// Hàm chung xử lý cả 2 trường hợp
async function handleUpdateComplaint(options = { send_to_customer: false }) {
    const id = document.querySelector('.btn-outline-modal')?.dataset?.id ||
        document.querySelector('.btn-save-modal')?.dataset?.id;
    if (!id) {
        alert('Lỗi: Không xác định được ID khiếu nại');
        return;
    }

    const status = document.getElementById('detail-status').value;
    const response_content = document.getElementById('detail-response').value.trim();

    if (!response_content) {
        alert('Vui lòng nhập nội dung phản hồi');
        return;
    }

    const btnOutline = document.querySelector('.btn-outline-modal');
    const btnSave = document.querySelector('.btn-save-modal');
    const originalOutline = btnOutline.textContent;
    const originalSave = btnSave.textContent;

    btnOutline.disabled = true;
    btnSave.disabled = true;
    btnSave.textContent = 'Đang xử lý...';

    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

        const res = await fetch(`/staff/api/complaints/${id}/respond`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken || ''
            },
            body: JSON.stringify({
                status,
                response_content,
                send_to_customer: options.send_to_customer
            })
        });

        const result = await res.json();

        if (result.success) {
            alert(options.send_to_customer
                ? 'Đã gửi phản hồi thành công cho khách hàng!'
                : 'Đã lưu tạm phản hồi thành công!');
            closeComplaintModal();
            loadComplaints();
        } else {
            alert('Lỗi: ' + (result.message || 'Không thể cập nhật'));
        }
    } catch (error) {
        console.error('Lỗi cập nhật:', error);
        alert('Lỗi kết nối server');
    } finally {
        btnOutline.disabled = false;
        btnSave.disabled = false;
        btnOutline.textContent = originalOutline;
        btnSave.textContent = originalSave;
    }
}

// ========================================================================
// 6. HELPER FUNCTIONS
// ========================================================================
function formatDate(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString('vi-VN');
}

function debounce(func, wait) {
    let timeout;
    return function(...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), wait);
    };
}