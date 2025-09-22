<div class="container">
    <h2 class="mb-4">Danh sách Nhân viên y tế</h2>
    <?php if (isset($data["Message"])): ?>
        <div class="alert alert-success"><?= $data["Message"] ?></div>
    <?php endif; ?>
    <?php if (isset($data["Error"])): ?>
        <div class="alert alert-danger"><?= $data["Error"] ?></div>
    <?php endif; ?>
    <table class="table table-striped table-bordered " style="box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);">
        <thead>
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
                            <form action='./CTNVNT' method='POST'>
                                <input type='hidden' name='ctnv' value='".$row['MaNV']."'>
                                <button class='btn btn-sm btn-primary' type='submit' name='btnCTNVNT'>Xem chi tiết</button>
                            </form>
                        </td>
                    </tr>";
                    $stt++;
                }
            } else {
                echo "<tr><td colspan='5'>Không có dữ liệu nhân viên nhà thuốc.</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <div class="text-center">
        <a href="./ThemNVNT" class="btn btn-success">Thêm Nhân viên nhà thuốc mới</a>
    </div>
</div>
<?php
    if (isset ($data['rs']))
    {
        if($data["rs"]== 'true')
        {
            echo'<script language="javascript">
                                alert("Thêm nhân viên thành công");	
                                </script>';
        }
    }