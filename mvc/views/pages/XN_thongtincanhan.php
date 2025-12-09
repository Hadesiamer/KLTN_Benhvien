<?php
// Dữ liệu thông tin cá nhân do controller truyền vào
$info = isset($data["ThongTin"]) ? $data["ThongTin"] : [];
// Do truy vấn theo MaNV, thường chỉ có 1 dòng
$nv = !empty($info) ? $info[0] : null;
?>
<div class="ml-64 w-full p-6">
    <h2 class="text-2xl font-bold text-blue-600 mb-4">
        <i class="fas fa-id-card mr-2"></i>
        Thông tin cá nhân
    </h2>

    <?php if (!$nv): ?>
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            Không tìm thấy thông tin nhân viên xét nghiệm tương ứng với tài khoản đang đăng nhập.
        </div>
    <?php else: ?>
        <div class="bg-white shadow rounded-xl p-6 max-w-xl">
            <div class="space-y-3 text-sm">
                <div class="flex">
                    <span class="w-40 font-semibold text-gray-700">Mã nhân viên:</span>
                    <span class="text-gray-800"><?= htmlspecialchars($nv["MaNV"]) ?></span>
                </div>
                <div class="flex">
                    <span class="w-40 font-semibold text-gray-700">Họ và tên:</span>
                    <span class="text-gray-800"><?= htmlspecialchars($nv["HovaTen"]) ?></span>
                </div>
                <div class="flex">
                    <span class="w-40 font-semibold text-gray-700">Ngày sinh:</span>
                    <span class="text-gray-800"><?= htmlspecialchars($nv["NgaySinh"]) ?></span>
                </div>
                <div class="flex">
                    <span class="w-40 font-semibold text-gray-700">Giới tính:</span>
                    <span class="text-gray-800"><?= htmlspecialchars($nv["GioiTinh"]) ?></span>
                </div>
                <div class="flex">
                    <span class="w-40 font-semibold text-gray-700">Số điện thoại:</span>
                    <span class="text-gray-800"><?= htmlspecialchars($nv["SoDT"]) ?></span>
                </div>
                <div class="flex">
                    <span class="w-40 font-semibold text-gray-700">Email:</span>
                    <span class="text-gray-800"><?= htmlspecialchars($nv["EmailNV"]) ?></span>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
