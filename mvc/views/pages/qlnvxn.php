<div class="mb-3">
    <!-- Header section -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h2 class="h5 mb-1">Danh sách Nhân viên xét nghiệm</h2>
            <p class="text-muted small mb-0">
                Quản lý thông tin nhân viên phụ trách thực hiện và hỗ trợ các xét nghiệm cận lâm sàng.
            </p>
        </div>
        <a href="./ThemNVXN" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i> Thêm Nhân viên xét nghiệm mới
        </a>
    </div>

    <!-- Thông báo theo data (nếu có) -->
    <?php if (isset($data["Message"])): ?>
        <div class="alert alert-success shadow-sm"><?= $data["Message"] ?></div>
    <?php endif; ?>

    <?php if (isset($data["Error"])): ?>
        <div class="alert alert-danger shadow-sm"><?= $data["Error"] ?></div>
    <?php endif; ?>

    <!-- Card chứa bảng -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <!-- Bọc bảng trong vùng cuộn -->
            <div class="table-responsive" style="max-height: 360px; overflow-y: auto;">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light" style="position: sticky; top: 0; z-index: 1;">
                        <tr>
                            <th scope="col" style="width: 60px;">STT</th>
                            <th scope="col">Mã NV</th>
                            <th scope="col">Họ và Tên</th>
                            <th scope="col">Ngày sinh</th>
                            <th scope="col">Giới tính</th>
                            <th scope="col" class="text-center" style="width: 140px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // $data['NhanVien'] dự kiến là JSON giống Nhân viên nhà thuốc
                        if (isset($data['NhanVien']) && !empty($data['NhanVien'])) {
                            $stt = 1;
                            // Giải mã JSON danh sách nhân viên xét nghiệm
                            $nhanVien = json_decode($data['NhanVien'], true);

                            if (!empty($nhanVien) && is_array($nhanVien)) {
                                foreach ($nhanVien as $row) {
                                    echo "<tr>
                                        <td>{$stt}</td>
                                        <td class='text-nowrap'>{$row['MaNV']}</td>
                                        <td>{$row['HovaTen']}</td>
                                        <td class='text-nowrap'>{$row['NgaySinh']}</td>
                                        <td>{$row['GioiTinh']}</td>
                                        <td class='text-center'>
                                            <form action='./CTNVXN' method='POST' class='d-inline'>
                                                <input type='hidden' name='ctnv' value='" . $row['MaNV'] . "'>
                                                <button class='btn btn-sm btn-outline-primary' type='submit' name='btnCTNVXN'>
                                                    Xem chi tiết
                                                </button>
                                            </form>
                                        </td>
                                    </tr>";
                                    $stt++;
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-center text-muted'>Không có dữ liệu nhân viên xét nghiệm.</td></tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center text-muted'>Không có dữ liệu nhân viên xét nghiệm.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
// Thông báo khi thêm nhân viên xét nghiệm thành công (theo style nhân viên nhà thuốc)
if (isset($data['rs'])) {
    if ($data["rs"] == 'true') {
        echo '<script language="javascript">
            alert("Thêm nhân viên xét nghiệm thành công");
        </script>';
    }
}
?>
