<?php
// View chi tiết Nhân viên xét nghiệm
if (isset($data["CTNV"]) && !empty($data["CTNV"])):
    // CTNV là chuỗi JSON, decode thành mảng
    $dt = json_decode($data["CTNV"], true);
?>
    <div class="card">
        <div class="card-header">
            <h3>Chi tiết Nhân viên xét nghiệm</h3>
        </div>
        <div class="card-body">
            <table class="table">
                <?php foreach ($dt as $nv): ?>
                <tr>
                    <th>Mã NV</th>
                    <td><?= $nv['MaNV'] ?></td>
                </tr>
                <tr>
                    <th>Họ và Tên</th>
                    <td><?= $nv['HovaTen'] ?></td>
                </tr>
                <tr>
                    <th>Ngày sinh</th>
                    <td><?= $nv['NgaySinh'] ?></td>
                </tr>
                <tr>
                    <th>Giới tính</th>
                    <td><?= $nv['GioiTinh'] ?></td>
                </tr>
                <tr>
                    <th>Số điện thoại</th>
                    <td><?= $nv['SoDT'] ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?= $nv['EmailNV'] ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
            <div class="d-flex justify-content-center mt-3">
                <!-- Nút mở form sửa -->
                <button class="btn btn-primary me-2" onclick="showEditForm()">Sửa</button>
                <!-- Form thôi việc / xóa NVXN -->
                <form action="" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn thôi việc nhân viên này?');">
                    <input type="hidden" name="MaNV" value="<?= $nv['MaNV'] ?>">
                    <button type="submit" class="btn btn-danger" name="btnXoaNVXN">Thôi việc</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Form sửa thông tin NVXN -->
    <div id="editForm" style="display: none;">
        <div class="card mt-3">
            <div class="card-header">
                <h3>Sửa thông tin Nhân viên xét nghiệm</h3>
            </div>
            <div class="card-body">
                <form action="/KLTN_Benhvien/QuanLy/CTNVXN" method="POST">
                    <input type="hidden" name="MaNV" value="<?= $nv['MaNV'] ?>">
                    <div class="mb-3">
                        <label for="HovaTen" class="form-label">Họ và Tên</label>
                        <input type="text" class="form-control" id="HovaTen" name="HovaTen" value="<?= $nv['HovaTen'] ?>" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="NgaySinh" class="form-label">Ngày sinh</label>
                        <input type="date" class="form-control" id="NgaySinh" name="NgaySinh" value="<?= $nv['NgaySinh'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="GioiTinh" class="form-label">Giới tính</label>
                        <select class="form-select" id="GioiTinh" name="GioiTinh" required>
                            <option value="Nam" <?= $nv['GioiTinh'] == 'Nam' ? 'selected' : '' ?>>Nam</option>
                            <option value="Nữ" <?= $nv['GioiTinh'] == 'Nữ' ? 'selected' : '' ?>>Nữ</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="SoDT" class="form-label">Số điện thoại</label>
                        <input type="tel" class="form-control" id="SoDT" name="SoDT" value="<?= $nv['SoDT'] ?>" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="EmailNV" class="form-label">Email</label>
                        <input type="email" class="form-control" id="EmailNV" name="EmailNV" value="<?= $nv['EmailNV'] ?>" required>
                    </div>
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary me-2" name="btnSuaNVXN">Cập nhật</button>
                        <button type="button" class="btn btn-secondary" onclick="hideEditForm()">Hủy</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Hiện form sửa
        function showEditForm() {
            document.getElementById('editForm').style.display = 'block';
        }

        // Ẩn form sửa
        function hideEditForm() {
            document.getElementById('editForm').style.display = 'none';
        }
    </script>
<?php endif; ?>

<?php
// Hiển thị thông báo sau khi cập nhật / thôi việc
if (isset($data['rs'])) {
    if ($data["rs"] === 'true') {
        echo '<script language="javascript">
                alert("Cập nhật nhân viên xét nghiệm thành công");
              </script>';
    } elseif ($data["rs"] == 3) {
        echo '<script language="javascript">
                alert("Thôi việc nhân viên xét nghiệm thành công");
              </script>';
    } else {
        echo '<script language="javascript">
                alert("Cập nhật nhân viên xét nghiệm thất bại");
              </script>';
    }
}
?>
