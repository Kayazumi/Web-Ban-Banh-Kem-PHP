console.log('complaints.js loaded successfully!');

// Hàm debounce để tránh gọi API quá nhiều khi gõ tìm kiếm
function debounce(func, delay) {
    let timeout;
    return function () {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, arguments), delay);
    };
}

document.addEventListener('DOMContentLoaded', function () {
    console.log('DOM fully loaded - starting complaints page');

    // Gọi loadComplaints ngay khi trang tải
    loadComplaints();

    // Tìm kiếm realtime (debounce 300ms)
    const searchInput = document.getElementById('complaint-search');
    if (searchInput) {
        searchInput.addEventListener('input', debounce(loadComplaints, 300));
    } else {
        console.error('Không tìm thấy #complaint-search');
    }

    // Filter checkbox
    document.querySelectorAll('.filter-checkbox input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', loadComplaints);
    });
});

// Tải danh sách khiếu nại
async function loadComplaints() {
    console.log('loadComplaints() called');

    const search = document.getElementById('complaint-search')?.value.trim() || '';
    let status = 'all';

    const checked = document.querySelector('.filter-checkbox input[type="checkbox"]:checked');
    if (checked && checked.value !== 'all') {
        status = checked.value;
    }

    const tbody = document.getElementById('complaints-table-body');
    if (!tbody) {
        console.error('Không tìm thấy #complaints-table-body');
        return;
    }

    // Hiển thị loading
    tbody.innerHTML = '<tr><td colspan="7" style="text-align:center; padding:40px; color:#666;">Đang tải dữ liệu...</td></tr>';

    try {
        const params = new URLSearchParams();
        if (search) params.append('search', search);
        if (status !== 'all') params.append('status', status);

        const url = `/staff/api/complaints?${params.toString()}`;
        console.log('Calling API:', url);

        const response = await fetch(url, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }

        const result = await response.json();
        console.log('API Response:', result);

        // Kiểm tra cấu trúc response
        if (!result || !result.data) {
            tbody.innerHTML = '<tr><td colspan="7" style="text-align:center; padding:40px; color:#888;">Không có dữ liệu trả về từ server</td></tr>';
            return;
        }

        if (result.data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" style="text-align:center; padding:40px; color:#888;">Không có khiếu nại nào phù hợp</td></tr>';
            return;
        }

        // Xóa bảng cũ
        tbody.innerHTML = '';

        // Map trạng thái sang tiếng Việt
        const statusText = {
            pending: 'Chờ xử lý',
            processing: 'Đang xử lý',
            resolved: 'Đã giải quyết'
        };

        result.data.forEach(item => {
            // *** QUAN TRỌNG: Tìm ID đúng của khiếu nại ***
            // Thử các trường phổ biến: id, complaint_id, ComplaintID, complaintId
            const complaintId = item.id || item.complaint_id || item.ComplaintID || item.complaintId || null;

            if (!complaintId) {
                console.warn('Item không có ID:', item);
            }

            const row = document.createElement('tr');

            row.innerHTML = `
                <td>${item.complaint_code || 'N/A'}</td>
                <td>${item.order?.order_code || 'N/A'}</td>
                <td>${item.customer?.full_name || 'Khách lạ'}</td>
                <td style="line-height:1.5; max-width:300px;">${item.title || item.content || ''}</td>
                <td>${item.created_at ? new Date(item.created_at).toLocaleDateString('vi-VN') : 'N/A'}</td>
                <td>
                    <span class="badge-status status-${item.status || 'unknown'}">
                        ${statusText[item.status] || item.status || 'Không rõ'}
                    </span>
                </td>
                <td style="text-align:center;">
                    <button type="button" class="btn-process" ${complaintId ? `onclick="openComplaintModal(${complaintId})"` : 'disabled'} title="Xử lý">
                        <i class="fas fa-edit"></i>
                    </button>
                </td>
            `;

            tbody.appendChild(row);
        });

    } catch (error) {
        console.error('Lỗi khi tải danh sách khiếu nại:', error);
        tbody.innerHTML = `<tr><td colspan="7" style="text-align:center; padding:40px; color:red;">Lỗi kết nối: ${error.message}</td></tr>`;
    }
}

// Mở modal chi tiết
async function openComplaintModal(id) {
    console.log('openComplaintModal called with id:', id);

    if (!id) {
        alert('Lỗi: Không có ID khiếu nại');
        return;
    }

    try {
        const response = await fetch(`/staff/api/complaints/${id}`);
        console.log('Chi tiết API response status:', response.status);

        if (!response.ok) {
            const errorText = await response.text();
            console.error('Error response body:', errorText);
            throw new Error(`HTTP ${response.status}`);
        }

        const result = await response.json();
        console.log('Chi tiết API response:', result);

        const data = result.data;
        if (!data) {
            alert('Không tìm thấy dữ liệu khiếu nại');
            return;
        }

        // Điền thông tin khách hàng
        document.getElementById('detail-customer-name').value = data.customer?.full_name || 'Khách lạ';
        document.getElementById('detail-customer-phone').value = data.customer?.phone || 'N/A';
        document.getElementById('detail-content').value = data.content || data.title || '';

        // Nhân viên phụ trách
        document.getElementById('detail-staff-id').value = result.current_staff?.id || '';
        document.getElementById('detail-staff-name').value = result.current_staff?.full_name || '';

        // Trạng thái
        document.getElementById('detail-status').value = data.status || 'pending';

        // Phản hồi cũ (nếu có)
        document.getElementById('detail-response').value = data.response || '';

        // === PHẦN QUAN TRỌNG: GÁN ID CHO CẢ 2 NÚT MỚI ===
        const buttons = document.querySelectorAll('.btn-outline-modal, .btn-save-modal');
        buttons.forEach(btn => {
            if (btn) btn.dataset.id = id;
        });

        // Hiển thị modal
        document.getElementById('complaintModal').classList.add('show');
        document.body.style.overflow = 'hidden';

    } catch (error) {
        console.error('Lỗi mở modal:', error);
        alert('Không thể tải chi tiết khiếu nại. Vui lòng kiểm tra console (F12) để xem lỗi chi tiết.');
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
    await handleUpdateComplaint({ send_to_customer: false });
}

// Trả lời khách hàng (gửi chính thức)
async function sendToCustomer() {
    // Tự động chuyển trạng thái thành "Đã giải quyết" khi gửi khách
    document.getElementById('detail-status').value = 'resolved';
    await handleUpdateComplaint({ send_to_customer: true });
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