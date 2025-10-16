<?php
// PAGE: Admin đổi mật khẩu (giao diện/JS phía client)
// Yêu cầu: Tailwind + Font Awesome đã được layoutadmin.php nạp sẵn

// Gợi ý (tuỳ chọn): nếu bạn đã có CSRF token ở session, có thể nhúng vào form hidden input
// $csrf = $_SESSION['csrf_token'] ?? '';
?>

<div class="max-w-2xl mx-auto px-4 py-6">
  <!-- Header -->
  <div class="mb-6">
    <h1 class="text-2xl font-semibold text-gray-800 flex items-center gap-2">
      <i class="fa-solid fa-key text-gray-600"></i>
      Đổi mật khẩu
    </h1>
    <p class="text-sm text-gray-500 mt-1">
      Vui lòng nhập mật khẩu hiện tại và đặt mật khẩu mới (8–16 ký tự).
    </p>
  </div>

  <!-- Card -->
  <div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="p-6">
      <!-- SỬA ĐIỂM NÀY: thêm method="post" và action đúng base-path -->
      <form id="formDoiMatKhau" method="post" action="/KLTN_Benhvien/Admin/DoiMK" novalidate>
        <!-- Mật khẩu cũ -->
        <div class="mb-5">
          <label for="old_password" class="block text-sm font-medium text-gray-700 mb-1">
            Mật khẩu hiện tại <span class="text-red-500">*</span>
          </label>
          <div class="relative">
            <input
              type="password"
              id="old_password"
              name="old_password"
              required
              class="peer w-full rounded-lg border border-gray-300 px-3 py-2 pr-10 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
              placeholder="Nhập mật khẩu hiện tại"
            />
            <button type="button" class="toggle-visibility absolute inset-y-0 right-2 flex items-center text-gray-400 hover:text-gray-600"
              data-target="old_password" aria-label="Hiện/ẩn mật khẩu hiện tại">
              <i class="fa-regular fa-eye"></i>
            </button>
          </div>
          <p id="err_old" class="mt-1 text-sm text-red-600 hidden">Vui lòng nhập mật khẩu hiện tại.</p>
        </div>

        <!-- Mật khẩu mới -->
        <div class="mb-5">
          <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">
            Mật khẩu mới (8–16 ký tự) <span class="text-red-500">*</span>
          </label>
          <div class="relative">
            <input
              type="password"
              id="new_password"
              name="new_password"
              required
              minlength="8"
              maxlength="16"
              class="peer w-full rounded-lg border border-gray-300 px-3 py-2 pr-10 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
              placeholder="Tạo mật khẩu mới (8–16 ký tự)"
            />
            <button type="button" class="toggle-visibility absolute inset-y-0 right-2 flex items-center text-gray-400 hover:text-gray-600"
              data-target="new_password" aria-label="Hiện/ẩn mật khẩu mới">
              <i class="fa-regular fa-eye"></i>
            </button>
          </div>
          <div class="mt-1 text-xs text-gray-500">
            Gợi ý: Dùng chữ hoa/thường, số và ký tự đặc biệt để tăng độ mạnh.
          </div>
          <p id="err_new" class="mt-1 text-sm text-red-600 hidden">Mật khẩu mới phải từ 8 đến 16 ký tự.</p>
        </div>

        <!-- Nhập lại mật khẩu mới -->
        <div class="mb-6">
          <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">
            Nhập lại mật khẩu mới <span class="text-red-500">*</span>
          </label>
          <div class="relative">
            <input
              type="password"
              id="confirm_password"
              name="confirm_password"
              required
              class="peer w-full rounded-lg border border-gray-300 px-3 py-2 pr-10 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
              placeholder="Nhập lại mật khẩu mới"
            />
            <button type="button" class="toggle-visibility absolute inset-y-0 right-2 flex items-center text-gray-400 hover:text-gray-600"
              data-target="confirm_password" aria-label="Hiện/ẩn xác nhận mật khẩu">
              <i class="fa-regular fa-eye"></i>
            </button>
          </div>
          <p id="err_confirm" class="mt-1 text-sm text-red-600 hidden">Mật khẩu nhập lại phải trùng mật khẩu mới.</p>
        </div>

        <!-- CSRF (tuỳ chọn, nếu có) -->
        <!-- <input type="hidden" name="csrf_token" value="<?php // echo htmlspecialchars($csrf); ?>"> -->

        <!-- Actions -->
        <div class="flex items-center gap-3">
          <button
            id="btnSubmit"
            type="submit"
            class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-white font-medium shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
            <i class="fa-solid fa-rotate"></i>
            Đổi mật khẩu
          </button>
          
        </div>
      </form>
    </div>
  </div>

  <!-- Thông báo (toast đơn giản) -->
  <div id="toast" class="fixed bottom-4 right-4 hidden">
    <div class="bg-red-600 text-white px-4 py-2 rounded-lg shadow-md text-sm">
      <i class="fa-solid fa-circle-exclamation mr-2"></i>
      Vui lòng điền đầy đủ và đúng yêu cầu trước khi đổi mật khẩu.
    </div>
  </div>
</div>

<script>
// ===== Helpers =====
const $$ = (s) => document.querySelector(s);
const addErr = (el, msgEl, show) => {
  if (show) {
    el.classList.remove("border-gray-300");
    el.classList.add("border-red-500", "focus:ring-red-500");
    msgEl.classList.remove("hidden");
  } else {
    el.classList.remove("border-red-500", "focus:ring-red-500");
    el.classList.add("border-gray-300");
    msgEl.classList.add("hidden");
  }
};
const showToast = () => {
  const t = $$("#toast");
  t.classList.remove("hidden");
  setTimeout(() => t.classList.add("hidden"), 2000);
};

// ===== Elements =====
const f = $$("#formDoiMatKhau");
const oldPw = $$("#old_password");
const newPw = $$("#new_password");
const cfmPw = $$("#confirm_password");
const errOld = $$("#err_old");
const errNew = $$("#err_new");
const errCfm = $$("#err_confirm");

// ===== Validation =====
function validateOld() {
  const ok = oldPw.value.trim().length > 0;
  addErr(oldPw, errOld, !ok);
  return ok;
}
function validateNew() {
  const len = newPw.value.length;
  const ok = len >= 8 && len <= 16;
  addErr(newPw, errNew, !ok);
  if (cfmPw.value.length > 0) validateConfirm();
  return ok;
}
function validateConfirm() {
  const ok = cfmPw.value === newPw.value && cfmPw.value.length > 0;
  addErr(cfmPw, errCfm, !ok);
  return ok;
}

// Live validation
[oldPw, newPw, cfmPw].forEach((el) => {
  el.addEventListener("input", () => { validateOld(); validateNew(); validateConfirm(); });
  el.addEventListener("blur", () => { validateOld(); validateNew(); validateConfirm(); });
});

// Submit handler (frontend only gate)
f.addEventListener("submit", (e) => {
  const allOk = validateOld() && validateNew() && validateConfirm();
  if (!allOk) {
    e.preventDefault();
    showToast();
    return false;
  }
  // Backend sẽ nhận POST theo action đã chỉ định
});

// Toggle show/hide password
document.querySelectorAll(".toggle-visibility").forEach(btn => {
  btn.addEventListener("click", () => {
    const id = btn.getAttribute("data-target");
    const inp = document.getElementById(id);
    if (!inp) return;
    inp.type = inp.type === "password" ? "text" : "password";
    const icon = btn.querySelector("i");
    if (icon) {
      icon.classList.toggle("fa-eye");
      icon.classList.toggle("fa-eye-slash");
    }
    inp.focus();
  });
});
</script>
