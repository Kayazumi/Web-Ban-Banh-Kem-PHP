document.addEventListener("DOMContentLoaded", function() {
    // 1. Khai báo các phần tử giao diện
    const tabLinks = document.querySelectorAll(".tab-link");
    const tabContents = document.querySelectorAll(".tab-content");
    const infoForm = document.getElementById("infoForm");
    const passwordForm = document.getElementById("passwordForm");
    const staffNameDisplay = document.getElementById("staffNameDisplay");

    // 2. Lay CSRF Token tu the meta de bao mat (Bat buoc trong Laravel)
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");

    // 3. Xu ly chuyen doi Tab (Thong tin / Mat khau)
    tabLinks.forEach(link => {
        link.addEventListener("click", function() {
            // Xoa trang thai active cu
            tabLinks.forEach(item => item.classList.remove("active"));
            tabContents.forEach(item => item.classList.remove("active"));

            // Them trang thai active cho tab duoc chon
            this.classList.add("active");
            const tabId = this.getAttribute("data-tab");
            document.getElementById(tabId).classList.add("active");
        });
    });

    // 4. Xu ly cap nhat thong tin ca nhan
    if (infoForm) {
        infoForm.addEventListener("submit", async function(e) {
            e.preventDefault();

            const formData = {
                full_name: document.getElementById("nameInput").value,
                phone: document.getElementById("phoneInput").value,
                address: document.getElementById("addressInput").value
            };

            try {
                const response = await fetch("/api/staff/profile", {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken,
                        "Accept": "application/json"
                    },
                    body: JSON.stringify(formData)
                });

                const result = await response.json();

                if (response.ok) {
                    alert("Thanh cong: " + result.message);
                    // Cap nhat ten hien thi tren tieu de trang
                    if (staffNameDisplay) {
                        staffNameDisplay.innerText = formData.full_name;
                    }
                } else {
                    // Xu ly loi Validation tu Laravel (UpdateProfileRequest)
                    if (result.errors) {
                        const errorMessages = Object.values(result.errors).flat().join("\n");
                        alert("Loi du lieu:\n" + errorMessages);
                    } else {
                        alert("Loi: " + result.message);
                    }
                }
            } catch (error) {
                console.error("Loi ket noi:", error);
                alert("Khong the ket noi den may chu.");
            }
        });
    }

    // 5. Xu ly doi mat khau
    if (passwordForm) {
        passwordForm.addEventListener("submit", async function(e) {
            e.preventDefault();

            const data = {
                oldPassword: document.getElementById("oldPassword").value,
                newPassword: document.getElementById("newPassword").value,
                newPassword_confirmation: document.getElementById("confirmPassword").value
            };

            try {
                const response = await fetch("/api/staff/password", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken,
                        "Accept": "application/json"
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (response.ok) {
                    alert("Thanh cong: " + result.message);
                    passwordForm.reset(); // Xoa trang form sau khi thanh cong
                } else {
                    // Xu ly loi mat khau cu khong khop hoac mat khau moi khong hop le
                    if (result.errors) {
                        const errorMessages = Object.values(result.errors).flat().join("\n");
                        alert("Loi mat khau:\n" + errorMessages);
                    } else {
                        alert("Loi: " + result.message);
                    }
                }
            } catch (error) {
                console.error("Loi ket noi:", error);
                alert("Khong the thuc hien doi mat khau.");
            }
        });
    }
});