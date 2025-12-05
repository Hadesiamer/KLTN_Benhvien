<?php
//Đây là file C:\xampp\htdocs\KLTN_Benhvien\mvc\views\pages\bs_danhsach_chat.php
$dsCuoc = isset($data["DanhSachCuocTroChuyen"]) ? $data["DanhSachCuocTroChuyen"] : [];
?>
<div class="container mt-3">
    <h3>Hộp thư bệnh nhân</h3>
    <p>Hiển thị các cuộc trò chuyện với bệnh nhân (khám online đã thanh toán).</p>

    <?php if (empty($dsCuoc)): ?>
        <div class="alert alert-info mt-3">
            Hiện tại bạn chưa có cuộc trò chuyện nào.
        </div>
    <?php else: ?>
        <table class="table table-bordered mt-3">
            <thead>
            <tr>
                <th>Mã cuộc trò chuyện</th>
                <th>Mã BN</th>
                <th>Họ tên BN</th>
                <th>Số điện thoại</th>
                <th>BHYT</th>
                <th>Lần cập nhật cuối</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($dsCuoc as $row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row["MaCuocTrove"]); ?></td>
                    <td><?php echo htmlspecialchars($row["MaBN"]); ?></td>
                    <td><?php echo htmlspecialchars($row["TenBenhNhan"]); ?></td>
                    <td><?php echo htmlspecialchars($row["SoDT"]); ?></td>
                    <td><?php echo htmlspecialchars($row["BHYT"]); ?></td>
                    <td><?php echo htmlspecialchars($row["ThoiGianCapNhat"]); ?></td>
                    <td>
                        <?php if (!empty($row["TrangThaiChoBS"])): ?>
                            <span class="badge bg-danger">Có tin mới</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Đã đọc</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a class="btn btn-primary btn-sm"
                           href="/KLTN_Benhvien/Bacsi/ChatVoiBenhNhan/<?php echo urlencode($row["MaCuocTrove"]); ?>">
                            Mở chat
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
