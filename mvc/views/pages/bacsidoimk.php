<?php
// Page: Đổi mật khẩu cho Bác sĩ
// Controller: Bacsi::Doimatkhau()
?>

<h2 class="mb-4">Đổi mật khẩu</h2>

<?php if (isset($data["msg"]) && $data["msg"] != ""): ?>
    <div class="alert alert-info">
        <?= htmlspecialchars($data["msg"], ENT_QUOTES, 'UTF-8') ?>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">

        <form id="formDoiMatKhau" method="post" action="/KLTN_Benhvien/Bacsi/Doimatkhau">

            <!-- Mật khẩu hiện tại -->
            <div class="mb-3">
                <label class="form-label">Mật khẩu hiện tại</label>
                <input type="password" id="old_password" name="old_password" class="form-control" required>
                <div class="invalid-feedback">Mật khẩu hiện tại không được để trống.</div>
            </div>

            <!-- Mật khẩu mới -->
            <div class="mb-3">
                <label class="form-label">
                    Mật khẩu mới <span class="text-muted">(8–16 ký tự)</span>
                </label>
                <input type="password" id="new_password" minlength="8" maxlength="16"
                       name="new_password" class="form-control" required>
                <div class="invalid-feedback">Mật khẩu mới phải từ 8–16 ký tự.</div>
            </div>

            <!-- Xác nhận -->
            <div class="mb-3">
                <label class="form-label">Nhập lại mật khẩu mới</label>
                <input type="password" id="confirm_password"
                       name="confirm_password" class="form-control" required>
                <div class="invalid-feedback">Mật khẩu xác nhận không khớp.</div>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-key"></i> Đổi mật khẩu
            </button>
        </form>
    </div>
</div>

<script>
// --------- VALIDATE REAL-TIME ---------

const oldPass = document.getElementById("old_password");
const newPass = document.getElementById("new_password");
const confirmPass = document.getElementById("confirm_password");
const form = document.getElementById("formDoiMatKhau");

// Kiểm tra mật khẩu hiện tại
oldPass.addEventListener("input", function () {
    if (oldPass.value.trim() === "") {
        oldPass.classList.add("is-invalid");
    } else {
        oldPass.classList.remove("is-invalid");
        oldPass.classList.add("is-valid");
    }
});

// Check mật khẩu mới
newPass.addEventListener("input", function () {
    if (newPass.value.length < 8 || newPass.value.length > 16) {
        newPass.classList.add("is-invalid");
    } else {
        newPass.classList.remove("is-invalid");
        newPass.classList.add("is-valid");
    }
    checkConfirm();
});

// Check xác nhận mật khẩu
confirmPass.addEventListener("input", function () {
    checkConfirm();
});

function checkConfirm() {
    if (confirmPass.value !== newPass.value || confirmPass.value === "") {
        confirmPass.classList.add("is-invalid");
        confirmPass.classList.remove("is-valid");
    } else {
        confirmPass.classList.remove("is-invalid");
        confirmPass.classList.add("is-valid");
    }
}

// Ngăn submit nếu còn lỗi
form.addEventListener("submit", function (e) {
    if (document.querySelector(".is-invalid")) {
        e.preventDefault();
    }
});
</script>
