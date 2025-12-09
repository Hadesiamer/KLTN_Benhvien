<div class="ml-64 w-full p-6">
    <h2 class="text-2xl font-bold text-blue-600 mb-4">
        <i class="fas fa-key mr-2"></i>
        Đổi mật khẩu
    </h2>

    <div class="bg-white shadow rounded-xl p-6 max-w-lg">
        <form action="/KLTN_Benhvien/NVXN/DoiMK" method="POST" class="space-y-4">
            <!-- Mật khẩu cũ -->
            <div>
                <label for="old_password" class="block text-sm font-medium text-gray-700 mb-1">
                    Mật khẩu hiện tại
                </label>
                <input
                    type="password"
                    id="old_password"
                    name="old_password"
                    required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Nhập mật khẩu hiện tại"
                >
            </div>

            <!-- Mật khẩu mới -->
            <div>
                <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">
                    Mật khẩu mới
                </label>
                <input
                    type="password"
                    id="new_password"
                    name="new_password"
                    required
                    minlength="8"
                    maxlength="16"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Từ 8–16 ký tự"
                >
                <p class="text-xs text-gray-500 mt-1">
                    Mật khẩu mới phải từ 8 đến 16 ký tự.
                </p>
            </div>

            <!-- Nhập lại mật khẩu mới -->
            <div>
                <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">
                    Xác nhận mật khẩu mới
                </label>
                <input
                    type="password"
                    id="confirm_password"
                    name="confirm_password"
                    required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Nhập lại mật khẩu mới"
                >
            </div>

            <div class="pt-2">
                <button
                    type="submit"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <i class="fas fa-save mr-2"></i>
                    Cập nhật mật khẩu
                </button>
            </div>
        </form>

        <p class="mt-4 text-xs text-gray-500">
            Sau khi đổi mật khẩu thành công, lần đăng nhập tiếp theo bạn sẽ dùng mật khẩu mới này.
        </p>
    </div>
</div>
