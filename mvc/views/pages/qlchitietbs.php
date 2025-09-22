<?php
if (isset($data["CTBS"]) && !empty($data["CTBS"])):
    $bs = $data["CTBS"];
?>
    <div class="card">
        <div class="card-header">
            <h3>Chi tiết Bác sĩ</h3>
        </div>
        <div class="card-body">
            <table class="table">
                <tr>
                    <th>Mã NV</th>
                    <td><?= $bs['MaNV'] ?></td>
                </tr>
                <tr>
                    <th>Họ và Tên</th>
                    <td><?= $bs['HovaTen'] ?></td>
                </tr>
                <tr>
                    <th>Ngày sinh</th>
                    <td><?= $bs['NgaySinh'] ?></td>
                </tr>
                <tr>
                    <th>Giới tính</th>
                    <td><?= $bs['GioiTinh'] ?></td>
                </tr>
                <tr>
                    <th>Số điện thoại</th>
                    <td><?= $bs['SoDT'] ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?= $bs['EmailNV'] ?></td>
                </tr>
                <tr>
                    <th>Chuyên khoa</th>
                    <td><?= $bs['TenKhoa'] ?></td>
                </tr>
            </table>
            <div class="d-flex justify-content-center mt-3">
                <button class="btn btn-primary me-2" onclick="showEditForm()">Sửa</button>
                <form action="./XoaBS" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bác sĩ này?');">
                    <input type="hidden" name="MaNV" value="<?= $bs['MaNV'] ?>">
                    <button type="submit" class="btn btn-danger" name="btnXoaBS">Xóa bác sĩ</button>
                </form>
            </div>
        </div>
    </div>


    <div id="editForm" style="display: none;">
        <div class="card mt-3">
            <div class="card-header">
                <h3>Sửa thông tin Bác sĩ</h3>
            </div>
            <div class="card-body">
                <form action="./SuaBS" method="POST">
                    <input type="hidden" name="MaNV" value="<?= $bs['MaNV'] ?>">
                    <div class="mb-3">
                        <label for="HovaTen" class="form-label">Họ và Tên</label>
                        <input type="text" class="form-control" id="HovaTen" name="HovaTen" value="<?= $bs['HovaTen'] ?>" readonly >
                    </div>
                    <div class="mb-3">
                        <label for="NgaySinh" class="form-label">Ngày sinh</label>
                        <input type="date" class="form-control" id="NgaySinh" name="NgaySinh" value="<?= $bs['NgaySinh'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="GioiTinh" class="form-label">Giới tính</label>
                        <select class="form-select" id="GioiTinh" name="GioiTinh" required>
                            <option value="Nam" <?= $bs['GioiTinh'] == 'Nam' ? 'selected' : '' ?>>Nam</option>
                            <option value="Nữ" <?= $bs['GioiTinh'] == 'Nữ' ? 'selected' : '' ?>>Nữ</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="SoDT" class="form-label">Số điện thoại</label>
                        <input type="tel" class="form-control" id="SoDT" name="SoDT" value="<?= $bs['SoDT'] ?>" readonly >
                    </div>
                    <div class="mb-3">
                        <label for="EmailNV" class="form-label">Email</label>
                        <input type="email" class="form-control" id="EmailNV" name="EmailNV" value="<?= $bs['EmailNV'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="MaKhoa" class="form-label">Chuyên khoa</label>
                        <select class="form-select" id="MaKhoa" name="MaKhoa" required>
                        <?php
                            $ql = $this->model("mQLBS");
                            $specialties = $ql->GetAllChuyenKhoa();
                            if ($specialties && $specialties->num_rows > 0) {
                                while ($specialty = $specialties->fetch_assoc()) {
                                    $selected = ($specialty['MaKhoa'] == $bs['MaKhoa']) ? 'selected' : '';
                                    echo "<option value='{$specialty['MaKhoa']}' {$selected}>{$specialty['TenKhoa']}</option>";
                                }
                            } else {
                                echo "<option value=''>Không có chuyên khoa nào</option>";
                            }
                        ?>
                        </select>
                    </div>
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary me-2" name="btnSuaBS">Cập nhật</button>
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
