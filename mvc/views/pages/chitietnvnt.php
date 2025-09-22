<?php
if (isset($data["CTNV"]) && !empty($data["CTNV"])):
    $dt = json_decode($data["CTNV"],true);
?>
    <div class="card">
        <div class="card-header">
            <h3>Chi tiết Nhân viên nhà thuốc</h3>
        </div>
        <div class="card-body">
            <table class="table">
                <?php foreach($dt as $nv):?>
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
                <?php endforeach;?>
            </table>
            <div class="d-flex justify-content-center mt-3">
                <button class="btn btn-primary me-2" onclick="showEditForm()">Sửa</button>
                <form action="" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa nhân viên này?');">
                    <input type="hidden" name="MaNV" value="<?= $nv['MaNV'] ?>">
                    <button type="submit" class="btn btn-danger" name="btnXoaNVNT">Xóa nhân viên</button>
                </form>
            </div>
        </div>
    </div>

    <div id="editForm" style="display: none;">
        <div class="card mt-3">
            <div class="card-header">
                <h3>Sửa thông tin Nhân viên y tế</h3>
            </div>
            <div class="card-body">
                <form action="/KLTN_Benhvien/QuanLy/CTNVNT" method="POST">
                    <input type="hidden" name="MaNV" value="<?= $nv['MaNV'] ?>">
                    <div class="mb-3">
                        <label for="HovaTen" class="form-label">Họ và Tên</label>
                        <input type="text" class="form-control" id="HovaTen" name="HovaTen" value="<?= $nv['HovaTen'] ?>" disabled >
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
                        <input type="tel" class="form-control" id="SoDT" name="SoDT" value="<?= $nv['SoDT'] ?>" disabled >
                    </div>
                    <div class="mb-3">
                        <label for="EmailNV" class="form-label">Email</label>
                        <input type="email" class="form-control" id="EmailNV" name="EmailNV" value="<?= $nv['EmailNV'] ?>" required>
                    </div>
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary me-2" name="btnSuaNVNT">Cập nhật</button>
                        <button type="button" class="btn btn-secondary" onclick="hideEditForm()">Hủy</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showEditForm() {
            document.getElementById('editForm').style.display = 'block';
        }

        function hideEditForm() {
            document.getElementById('editForm').style.display = 'none';
        }
    </script>
<?php endif; ?>
<?php
    if (isset ($data['rs']))
    {
        if($data["rs"]== 'true')
        {
            echo'<script language="javascript">
                                alert("Cập nhật nhân viên thành công");	
                                </script>';
        }
        else if($data["rs"]== 3)
        {
            echo'<script language="javascript">
                                alert("Thôi việc nhân viên thành công");	
                                </script>';
        }
        else
        {
            echo'<script language="javascript">
                                alert("Cập nhật nhân viên thất bại");	
                                </script>';
        }
    }

?>