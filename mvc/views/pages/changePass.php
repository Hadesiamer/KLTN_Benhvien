<div>
    <h2>Đổi mật khẩu</h2>
    <form method="POST" action="/KLTN_Benhvien/BN/changePass" id="formChangePass" novalidate>
        <!-- Mật khẩu hiện tại -->
        <div class="form-group mb-3">
            <label for="oldPass">Mật khẩu hiện tại:</label>
            <input type="password" id="oldPass" name="oldPass" required class="form-control">
            <div class="invalid-feedback">Vui lòng nhập mật khẩu hiện tại.</div>
        </div>

        <!-- Mật khẩu mới -->
        <div class="form-group mb-3">
            <label for="newPass">Mật khẩu mới:</label>
            <input type="password" id="newPass" name="newPass" required class="form-control">
            <div class="invalid-feedback">Mật khẩu mới phải từ 8–16 ký tự.</div>
        </div>

        <!-- Xác nhận mật khẩu mới -->
        <div class="form-group mb-3">
            <label for="confirmPass">Nhập lại mật khẩu mới:</label>
            <input type="password" id="confirmPass" name="confirmPass" required class="form-control">
            <div class="invalid-feedback">Mật khẩu xác nhận không khớp.</div>
        </div>

        <div>
            <input type="submit" name="btnChangePass" value="Đổi mật khẩu" class="btn btn-primary mt-2">
        </div>
    </form>
</div>

<!-- ========== Giữ nguyên đoạn hiển thị thông báo server-side cũ ========== -->
<?php if (isset($data['CP'])): ?>
    <div 
        class="alert <?= $data['CP']['success'] ? 'alert-success' : 'alert-danger' ?> mt-3" 
        role="alert" 
        id="serverAlert"
    >
        <?= $data['CP']['message'] ?>
    </div>
<?php endif; ?>


<!-- ================== CODE MỚI THÊM VÀO (Validate realtime + Alert tự ẩn) ================== -->
<script>
const oldP = document.getElementById("oldPass");
const newP = document.getElementById("newPass");
const cfmP = document.getElementById("confirmPass");

// ========== Hàm kiểm tra từng trường ==========
function validateOld() {
    if (oldP.value.trim() === "") {
        oldP.classList.add("is-invalid");
        return false;
    } else {
        oldP.classList.remove("is-invalid");
        return true;
    }
}

function validateNew() {
    const len = newP.value.trim().length;
    if (len < 8 || len > 16) {
        newP.classList.add("is-invalid");
        return false;
    } else {
        newP.classList.remove("is-invalid");
        return true;
    }
}

function validateConfirm() {
    if (cfmP.value.trim() === "" || cfmP.value !== newP.value) {
        cfmP.classList.add("is-invalid");
        return false;
    } else {
        cfmP.classList.remove("is-invalid");
        return true;
    }
}

// ========== Sự kiện realtime: khi người dùng rời khỏi trường (blur) ==========
oldP.addEventListener("blur", validateOld);
newP.addEventListener("blur", validateNew);
cfmP.addEventListener("blur", validateConfirm);

// ========== Khi người dùng đang nhập, xóa lỗi nếu hợp lệ ==========
oldP.addEventListener("input", validateOld);
newP.addEventListener("input", validateNew);
cfmP.addEventListener("input", validateConfirm);

// ========== Kiểm tra toàn form khi submit ==========
document.getElementById("formChangePass").addEventListener("submit", function(e) {
    const ok1 = validateOld();
    const ok2 = validateNew();
    const ok3 = validateConfirm();
    if (!ok1 || !ok2 || !ok3) e.preventDefault();
});

// ========== Tự ẩn alert thông báo server sau 3 giây ==========
setTimeout(() => {
    const alertBox = document.getElementById("serverAlert");
    if (alertBox) {
        alertBox.style.transition = "opacity 0.5s";
        alertBox.style.opacity = "0";
        setTimeout(() => alertBox.remove(), 500);
    }
}, 3000);
</script>
