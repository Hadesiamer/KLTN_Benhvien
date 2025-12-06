
<?php
//Đây là file C:\xampp\htdocs\KLTN_Benhvien\mvc\views\pages\bn_danhsach_chat.php
// $data["DanhSachBacSi"] là mảng các bác sĩ BN có thể chat
$dsBacSi = isset($data["DanhSachBacSi"]) ? $data["DanhSachBacSi"] : [];
?>
<div class="container mt-3">
    <h3>Nhắn tin với bác sĩ</h3>
    <?php if (empty($dsBacSi)): ?>
        <div class="alert alert-info mt-3">
            Hiện tại bạn chưa có lịch khám online nào đã thanh toán với bác sĩ, nên chưa thể sử dụng chức năng chat.
        </div>
    <?php else: ?>
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Mã bác sĩ</th>
                    <th>Họ và tên</th>
                    <th>Chuyên khoa</th>
                    <th>Vị trí / Mô tả</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($dsBacSi as $bs): ?>
                <tr>
                    <td><?php echo htmlspecialchars($bs["MaBS"]); ?></td>
                    <td><?php echo htmlspecialchars($bs["TenBacSi"]); ?></td>
                    <td><?php echo htmlspecialchars($bs["TenKhoa"]); ?></td>
                    <td><?php echo htmlspecialchars($bs["MoTaKhoa"]); ?></td>
                    <td>
                        <a class="btn btn-primary btn-sm"
                           href="/KLTN_Benhvien/BN/ChatVoiBacSi/<?php echo urlencode($bs["MaBS"]); ?>">
                            Chat
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
