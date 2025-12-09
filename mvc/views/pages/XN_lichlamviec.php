<?php
// Lấy dữ liệu lịch làm việc được truyền từ controller
$lich = isset($data["LichLamViec"]) ? $data["LichLamViec"] : [];
?>
<div class="ml-64 w-full p-6">
    <h2 class="text-2xl font-bold text-blue-600 mb-4">
        <i class="fas fa-calendar-alt mr-2"></i>
        Lịch làm việc
    </h2>

    <?php if (empty($lich)): ?>
        <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded-lg">
            Hiện chưa có lịch làm việc nào được ghi nhận cho tài khoản này.
        </div>
    <?php else: ?>
        <div class="bg-white shadow rounded-xl overflow-hidden">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">
                            Ngày làm việc
                        </th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">
                            Ca làm việc
                        </th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">
                            Trạng thái
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lich as $row): ?>
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-4 py-2 text-gray-700">
                                <?= htmlspecialchars($row["NgayLamViec"]) ?>
                            </td>
                            <td class="px-4 py-2 text-gray-700">
                                <?= htmlspecialchars($row["CaLamViec"]) ?>
                            </td>
                            <td class="px-4 py-2">
                                <?php
                                    $status = $row["TrangThai"];
                                    $badgeClass = "bg-gray-100 text-gray-700";
                                    if ($status === "Đang làm") {
                                        $badgeClass = "bg-green-100 text-green-700";
                                    } elseif ($status === "Nghỉ") {
                                        $badgeClass = "bg-red-100 text-red-700";
                                    }
                                ?>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold <?= $badgeClass ?>">
                                    <?= htmlspecialchars($status) ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
