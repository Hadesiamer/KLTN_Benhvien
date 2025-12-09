<div class="mb-3">
    <!-- Header section -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h2 class="h5 mb-1">Danh sách Nhân viên y tế</h2>
            <p class="text-muted small mb-0">
                Quản lý thông tin điều dưỡng, kỹ thuật viên, hộ lý và các nhân viên hỗ trợ lâm sàng.
            </p>
        </div>
        <a href="./ThemNVYT" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i> Thêm Nhân viên y tế mới
        </a>
    </div>

    <!-- Thông báo session riêng -->
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success shadow-sm">
            <?php 
                echo $_SESSION['success_message'];
                unset($_SESSION['success_message']);
            ?>
        </div>
    <?php endif; ?>

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
                        if (isset($data['NhanVien']) && !empty($data['NhanVien'])) {
                            $stt = 1;
                            $nhanVien = json_decode($data['NhanVien'], true);
                            foreach ($nhanVien as $row) {
                                echo "<tr>
                                    <td>{$stt}</td>
                                    <td class='text-nowrap'>{$row['MaNV']}</td>
                                    <td>{$row['HovaTen']}</td>
                                    <td class='text-nowrap'>{$row['NgaySinh']}</td>
                                    <td>{$row['GioiTinh']}</td>
                                    <td class='text-center'>
                                        <form action='./CTNVYT' method='POST' class='d-inline'>
                                            <input type='hidden' name='ctnv' value='{$row['MaNV']}'>
                                            <button class='btn btn-sm btn-outline-primary' type='submit' name='btnCTNVYT'>
                                                Xem chi tiết
                                            </button>
                                        </form>
                                    </td>
                                </tr>";
                                $stt++;
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center text-muted'>Không có dữ liệu nhân viên y tế.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
