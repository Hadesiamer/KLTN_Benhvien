<div class="container">
    <h2 class="mb-4">Danh sách Nhân viên y tế</h2>
    <?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success">
        <?php 
        echo $_SESSION['success_message'];
        unset($_SESSION['success_message']);
        ?>
    </div>
<?php endif; ?>
    <?php if (isset($data["Message"])): ?>
        <div class="alert alert-success"><?= $data["Message"] ?></div>
    <?php endif; ?>
    <?php if (isset($data["Error"])): ?>
        <div class="alert alert-danger"><?= $data["Error"] ?></div>
    <?php endif; ?>
    <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
        <table class="table table-striped table-bordered" style="box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);">
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
                    $nhanVien = json_decode($data['NhanVien'], true);
                    foreach ($nhanVien as $row) {
                        echo "<tr>
                            <td>{$stt}</td>
                            <td>{$row['MaNV']}</td>
                            <td>{$row['HovaTen']}</td>
                            <td>{$row['NgaySinh']}</td>
                            <td>{$row['GioiTinh']}</td>
                            <td>
                                <form action='./CTNVYT' method='POST'>
                                    <input type='hidden' name='ctnv' value='{$row['MaNV']}'>
                                    <button class='btn btn-sm btn-primary' type='submit' name='btnCTNVYT'>Xem chi tiết</button>
                                </form>
                            </td>
                        </tr>";
                        $stt++;
                    }
                } else {
                    echo "<tr><td colspan='6'>Không có dữ liệu nhân viên y tế.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <div class="text-center mt-3">
        <a href="./ThemNVYT" class="btn btn-success">Thêm Nhân viên y tế mới</a>
    </div>
</div>

