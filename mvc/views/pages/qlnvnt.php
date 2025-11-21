<div class="container">
    <h2 class="mb-4">Danh sách Nhân viên nhà thuốc</h2>

    <?php if (isset($data["Message"])): ?>
        <div class="alert alert-success"><?= $data["Message"] ?></div>
    <?php endif; ?>

    <?php if (isset($data["Error"])): ?>
        <div class="alert alert-danger"><?= $data["Error"] ?></div>
    <?php endif; ?>

    <!-- Bọc bảng trong khung cuộn cho gọn layout -->
    <div class="table-container" style="max-height: 300px; overflow-y: auto;">
        <table class="table table-striped table-bordered" style="width: 100%; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);">
            <thead style="position: sticky; top: 0; background-color: #fff; z-index: 1;">
                <tr>
                    <th>STT</th>
                    <th>Mã NV</th>
                    <th>Họ và Tên</th>
                    <th>Ngày sinh</th>
                    <th>Giới tính</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($data['NhanVien']) && !empty($data['NhanVien'])) {
                    $stt = 1;
                    // Giải mã JSON danh sách nhân viên
                    $nhanVien = json_decode($data['NhanVien'], true);

                    if (!empty($nhanVien) && is_array($nhanVien)) {
                        foreach ($nhanVien as $row) {
                            echo "<tr>
                                <td>{$stt}</td>
                                <td>{$row['MaNV']}</td>
                                <td>{$row['HovaTen']}</td>
                                <td>{$row['NgaySinh']}</td>
                                <td>{$row['GioiTinh']}</td>
                                <td>
                                    <form action='./CTNVNT' method='POST' class='d-inline'>
                                        <input type='hidden' name='ctnv' value='".$row['MaNV']."'>
                                        <button class='btn btn-sm btn-primary' type='submit' name='btnCTNVNT'>
                                            Xem chi tiết
                                        </button>
                                    </form>
                                </td>
                            </tr>";
                            $stt++;
                        }
                    } else {
                        echo "<tr><td colspan='6'>Không có dữ liệu nhân viên nhà thuốc.</td></tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>Không có dữ liệu nhân viên nhà thuốc.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="text-center mt-3">
        <a href="./ThemNVNT" class="btn btn-success">Thêm Nhân viên nhà thuốc mới</a>
    </div>
</div>

<?php
// Thông báo khi thêm nhân viên nhà thuốc thành công
if (isset($data['rs'])) {
    if ($data["rs"] == 'true') {
        echo '<script language="javascript">
            alert("Thêm nhân viên nhà thuốc thành công");
        </script>';
    }
}
?>
