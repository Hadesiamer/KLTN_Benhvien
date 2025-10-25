<?php
// mvc/views/pages/qlydoimk.php
// Form đổi mật khẩu cho tài khoản Quản lý
// Lưu ý: phần xử lý POST sẽ do controller đảm nhiệm.
// File này chỉ lo phần giao diện + hook sẵn các id để JS trong LayoutQLdoimatkhau.php hoạt động.
?>

<div class="max-w-2xl mx-auto px-4 py-8">
  <!-- Tiêu đề -->
  <h1 class="text-2xl font-semibold text-gray-800 flex items-center gap-2 mb-6">
    <i class="fa-solid fa-key text-gray-600"></i>
    Đổi mật khẩu
  </h1>

  <!-- Thông báo popup nhỏ khi form lỗi client-side -->
  <!-- Hiển thị/ẩn bằng JS showToast() trong layout -->
  <div
    id="toast"
    class="hidden mb-4 rounded bg-red-100 border border-red-300 text-red-700 px-4 py-2 text-sm"
  >
    Vui lòng kiểm tra lại các trường bị báo đỏ.
  </div>

  <!-- Form đổi mật khẩu -->
  <form id="formDoiMatKhau" method="post" action="/KLTN_Benhvien/QuanLy/DoiMK" class="bg-white rounded-lg shadow p-6 border border-gray-200">
    <!-- Mật khẩu hiện tại -->
    <div class="mb-5">
      <label for="old_password" class="block text-sm font-medium text-gray-700 mb-1">
        Mật khẩu hiện tại
      </label>

      <div class="relative">
        <input
          type="password"
          id="old_password"
          name="old_password"
          required
          class="border border-gray-300 rounded w-full px-3 py-2 pr-10 text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500"
          placeholder="Nhập mật khẩu hiện tại"
        />

        
      
      </div>

      <p id="err_old" class="text-red-500 text-xs mt-1 hidden">
        Vui lòng nhập mật khẩu hiện tại.
      </p>
    </div>

    <!-- Mật khẩu mới -->
    <div class="mb-5">
      <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">
        Mật khẩu mới
        <span class="text-gray-400 font-normal">(8-16 ký tự)</span>
      </label>

      <div class="relative">
        <input
          type="password"
          id="new_password"
          name="new_password"
          required
          minlength="8"
          maxlength="16"
          class="border border-gray-300 rounded w-full px-3 py-2 pr-10 text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500"
          placeholder="Nhập mật khẩu mới (8-16 ký tự)"
        />

      </div>

      <p id="err_new" class="text-red-500 text-xs mt-1 hidden">
        Mật khẩu mới phải từ 8 đến 16 ký tự.
      </p>
    </div>

    <!-- Xác nhận mật khẩu mới -->
    <div class="mb-6">
      <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">
        Nhập lại mật khẩu mới
      </label>

      <div class="relative">
        <input
          type="password"
          id="confirm_password"
          name="confirm_password"
          required
          class="border border-gray-300 rounded w-full px-3 py-2 pr-10 text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500"
          placeholder="Nhập lại mật khẩu mới"
        />

      </div>

      <p id="err_confirm" class="text-red-500 text-xs mt-1 hidden">
        Mật khẩu xác nhận không khớp.
      </p>
    </div>

    <!-- Nút submit -->
    <button
      type="submit"
      class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors text-sm font-medium"
    >
      <i class="fa-solid fa-rotate"></i>
      <span>Đổi mật khẩu</span>
    </button>
  </form>
</div>
