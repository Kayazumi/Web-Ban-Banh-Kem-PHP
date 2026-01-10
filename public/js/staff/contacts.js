// ========================================================================
// QUẢN LÝ LIÊN HỆ - NHÂN VIÊN (TƯƠNG TỰ ORDERS)
// ========================================================================

let contactsData = [];
let filteredContacts = [];
let currentPage = 1;
const itemsPerPage = 10;
let currentContactId = null;

// ========================================================================
// 1. KHỞI TẠO
// ========================================================================
document.addEventListener('DOMContentLoaded', function () {
    loadContacts();
    initEventListeners();
});

// ========================================================================
// 2. TẢI DANH SÁCH LIÊN HỆ
// ========================================================================
async function loadContacts(resetPage = true) {
    if (resetPage) currentPage = 1;

    try {
        const response = await fetch('/staff/api/contacts', {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });

        if (!response.ok) throw new Error('Không thể tải danh sách liên hệ');

        const data = await response.json();
        contactsData = data.contacts || [];
        applyFiltersAndPaginate();
    } catch (error) {
        console.error('Lỗi:', error);
        showError('Không thể tải danh sách liên hệ. Vui lòng thử lại!');
    }
}

// ========================================================================
// ÁP DỤNG FILTER + PHÂN TRANG
// ========================================================================
function applyFiltersAndPaginate() {
    const searchTerm = document.getElementById('search-input')?.value.toLowerCase() || '';
    const selectedStatuses = Array.from(document.querySelectorAll('.filter-checkbox input:checked'))
        .map(cb => cb.value);

    let temp = contactsData.filter(contact => {
        const matchesSearch = !searchTerm ||
            contact.customer_name.toLowerCase().includes(searchTerm) ||
            contact.Subject.toLowerCase().includes(searchTerm);

        const matchesStatus = selectedStatuses.length === 0 ||
            selectedStatuses.includes(contact.Status);

        return matchesSearch && matchesStatus;
    });

    const totalItems = temp.length;
    const totalPages = Math.ceil(totalItems / itemsPerPage);

    const start = (currentPage - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    filteredContacts = temp.slice(start, end);

    renderContacts();
    renderPagination(totalPages, totalItems);
}

// ========================================================================
// 3. RENDER BẢNG LIÊN HỆ
// ========================================================================
function renderContacts() {
    const tbody = document.getElementById('contacts-table-body');

    if (filteredContacts.length === 0) {
        tbody.innerHTML = `
            <tr class="no-contacts">
                <td colspan="6">
                    <i class="fas fa-inbox"></i>
                    <p>Không tìm thấy liên hệ nào</p>
                </td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = filteredContacts.map(contact => `
        <tr data-contact-id="${contact.ContactID}">
            <td><span class="contact-code">LH${String(contact.ContactID).padStart(4, '0')}</span></td>
            <td>${contact.customer_name}</td>
            <td><span class="contact-subject">${contact.Subject}</span></td>
            <td>${formatDate(contact.CreatedAt)}</td>
            <td>
                <span class="status-badge ${contact.Status}">
                    ${getStatusLabel(contact.Status)}
                </span>
            </td>
            <td>
                <button class="view-detail-btn" onclick="showContactDetail(${contact.ContactID})">
                    <i class="fas fa-eye"></i>
                </button>
            </td>
        </tr>
    `).join('');
}

// ========================================================================
// 4. PHÂN TRANG
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
                của ${totalItems} liên hệ
            </div>
            <div class="pagination-buttons">
    `;

    if (currentPage > 1) {
        html += `<button class="page-btn" onclick="changePage(${currentPage - 1})">&laquo; Trước</button>`;
    }

    if (totalPages <= 10) {
        for (let i = 1; i <= totalPages; i++) {
            if (i === currentPage) {
                html += `<strong class="page-btn active">${i}</strong>`;
            } else {
                html += `<button class="page-btn" onclick="changePage(${i})">${i}</button>`;
            }
        }
    } else {
        for (let i = 1; i <= 3; i++) {
            if (i === currentPage) {
                html += `<strong class="page-btn active">${i}</strong>`;
            } else {
                html += `<button class="page-btn" onclick="changePage(${i})">${i}</button>`;
            }
        }

        html += `<span class="page-dots">...</span>`;

        if (currentPage === totalPages) {
            html += `<strong class="page-btn active">${totalPages}</strong>`;
        } else {
            html += `<button class="page-btn" onclick="changePage(${totalPages})">${totalPages}</button>`;
        }

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

    html += `
            </div>
        </div>
    `;

    document.querySelector('.contacts-table-wrapper').insertAdjacentHTML('beforeend', html);
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
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// ========================================================================
// 5. XEM CHI TIẾT LIÊN HỆ
// ========================================================================
async function showContactDetail(contactId) {
    currentContactId = contactId;
    const modal = document.getElementById('contactDetailModal');
    const modalBody = document.getElementById('modalBody');
    modal.classList.add('show');

    try {
        const response = await fetch(`/staff/api/contacts/${contactId}`);
        if (!response.ok) throw new Error('Không tải được chi tiết');

        const data = await response.json();
        const contact = data.contact;

        modalBody.innerHTML = `
            <div class="detail-section">
                <h3>Thông tin khách hàng</h3>
                <div class="detail-row"><span class="detail-label">Họ tên:</span><span class="detail-value">${contact.customer_name}</span></div>
                <div class="detail-row"><span class="detail-label">Số điện thoại:</span><span class="detail-value">${contact.customer_phone}</span></div>
                <div class="detail-row"><span class="detail-label">Email:</span><span class="detail-value">${contact.customer_email}</span></div>
            </div>

            <div class="detail-section">
                <h3>Thông tin liên hệ</h3>
                <div class="detail-row"><span class="detail-label">Mã liên hệ:</span><span class="detail-value highlight">LH${String(contact.ContactID).padStart(4, '0')}</span></div>
                <div class="detail-row"><span class="detail-label">Tiêu đề:</span><span class="detail-value highlight">${contact.Subject}</span></div>
                <div class="detail-row"><span class="detail-label">Ngày gửi:</span><span class="detail-value">${formatDateTime(contact.CreatedAt)}</span></div>
                <div class="detail-row">
                    <span class="detail-label">Trạng thái:</span>
                    <span class="detail-value">
                        <span class="status-badge ${contact.Status}">${getStatusLabel(contact.Status)}</span>
                    </span>
                </div>
                ${contact.RespondedAt ? `
                    <div class="detail-row"><span class="detail-label">Đã phản hồi lúc:</span><span class="detail-value">${formatDateTime(contact.RespondedAt)}</span></div>
                    <div class="detail-row"><span class="detail-label">Người phản hồi:</span><span class="detail-value">${contact.staff_name}</span></div>
                ` : ''}
            </div>

            <div class="detail-section">
                <h3>Nội dung tin nhắn</h3>
                <div class="message-content">${contact.Message}</div>
            </div>
        `;

        // Cập nhật nút đánh dấu
        const btnMarkResponded = document.getElementById('btnMarkResponded');
        if (contact.Status === 'responded') {
            btnMarkResponded.textContent = 'Đã phản hồi';
            btnMarkResponded.disabled = true;
            btnMarkResponded.innerHTML = '<i class="fas fa-check-circle"></i> Đã phản hồi';
        } else {
            btnMarkResponded.disabled = false;
            btnMarkResponded.innerHTML = '<i class="fas fa-check"></i> Đánh dấu đã phản hồi';
        }

    } catch (error) {
        modalBody.innerHTML = `<p style="color:red; text-align:center;">Không thể tải chi tiết liên hệ!</p>`;
    }
}

// ========================================================================
// 6. ĐÁNH DẤU ĐÃ PHẢN HỒI
// ========================================================================
async function markAsResponded() {
    if (!currentContactId) return;

    showConfirm('Xác nhận bạn đã phản hồi khách hàng này qua điện thoại/email?', async function () {
        try {
            const response = await fetch(`/staff/api/contacts/${currentContactId}/status`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ status: 'responded' })
            });

            if (!response.ok) throw new Error('Không thể cập nhật trạng thái');

            const data = await response.json();

            // Cập nhật dữ liệu local
            const contactIndex = contactsData.findIndex(c => c.ContactID === currentContactId);
            if (contactIndex !== -1) {
                contactsData[contactIndex].Status = 'responded';
                contactsData[contactIndex].RespondedAt = data.contact.RespondedAt;
                contactsData[contactIndex].staff_name = data.contact.staff_name;
            }

            showSuccess('Đã đánh dấu phản hồi thành công!');
            closeContactModal();
            applyFiltersAndPaginate();

        } catch (error) {
            console.error('Lỗi:', error);
            showError('Không thể cập nhật trạng thái. Vui lòng thử lại!');
        }
    });
}

function closeContactModal() {
    document.getElementById('contactDetailModal').classList.remove('show');
    currentContactId = null;
}

document.addEventListener('click', function (e) {
    const modal = document.getElementById('contactDetailModal');
    if (e.target === modal) closeContactModal();
});

// ========================================================================
// 7. TÌM KIẾM VÀ LỌC
// ========================================================================
function initEventListeners() {
    const searchInput = document.getElementById('search-input');
    if (searchInput) {
        searchInput.addEventListener('input', debounce(() => loadContacts(true), 300));
    }

    document.querySelectorAll('.filter-checkbox input').forEach(checkbox => {
        checkbox.addEventListener('change', () => loadContacts(true));
    });
}

// ========================================================================
// 8. HELPER FUNCTIONS
// ========================================================================
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('vi-VN');
}

function formatDateTime(dateString) {
    const date = new Date(dateString);
    return date.toLocaleString('vi-VN', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function getStatusLabel(status) {
    const labels = {
        'pending': 'Chờ xử lý',
        'responded': 'Đã phản hồi'
    };
    return labels[status] || status;
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// ========================================================================
// 9. THÔNG BÁO
// ========================================================================
function showSuccess(message) {
    showNotification(message, 'success');
}

function showError(message) {
    showNotification(message, 'error');
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
        <span>${message}</span>
    `;

    if (!document.getElementById('notification-styles')) {
        const style = document.createElement('style');
        style.id = 'notification-styles';
        style.textContent = `
            .notification {
                position: fixed;
                top: 100px;
                right: 20px;
                background: white;
                padding: 15px 20px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                display: flex;
                align-items: center;
                gap: 12px;
                z-index: 10000;
                animation: slideIn 0.3s ease;
            }
            .notification.success { border-left: 4px solid #27ae60; }
            .notification.success i { color: #27ae60; font-size: 1.2rem; }
            .notification.error { border-left: 4px solid #e74c3c; }
            .notification.error i { color: #e74c3c; font-size: 1.2rem; }
            @keyframes slideIn {
                from { transform: translateX(400px); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
        `;
        document.head.appendChild(style);
    }

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.animation = 'slideIn 0.3s ease reverse';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}