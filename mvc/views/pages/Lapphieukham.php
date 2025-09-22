<?php
    $dt = json_decode($data["BenhNhanInfo"],true);
    $hientai=date('d-m-Y');
    $bs = json_decode($data["BacSiInfo"],true);
?>
<div class="row">
    <div class="col-md-4">
        <h2>Thông tin bệnh nhân</h2>
        <?php foreach($dt as $r):?>
        <p><strong>Họ và tên:</strong> <?php echo $r['HovaTen']; ?></p>
        <p><strong>Ngày sinh:</strong> <?php echo $r['NgaySinh']; ?></p>
        <p><strong>Giới tính:</strong> <?php echo $r['GioiTinh']; ?></p>
        <p><strong>BHYT:</strong> <?php echo $r['BHYT']; ?></p>
        <p><strong>Địa chỉ:</strong> <?php echo $r['DiaChi']; ?></p>
        <p><strong>Số điện thoại:</strong> <?php echo $r['SoDT']; ?></p>
        <?php endforeach;?>
    </div>
    <div class="col-md-8">
        <h2>Lập phiếu khám</h2>
        <form action="" method="POST">
        <?php foreach($dt as $r):?>
            <input type="hidden" name="maLK" value="<?php echo $r['MaLK']; ?>">
            <input type="hidden" name="maBN" value="<?php echo $r['MaBN']; ?>">
         <?php endforeach;?>
            <div class="form-group">
                <label for="ngayTao">Ngày tạo:</label>
                <input type="date" id="ngayTao" name="ngayTao" value="<?php echo date('Y-m-d'); ?>" readonly class="form-control">
            </div>

            <div class="form-group">
                <label for="bacSi">Bác sĩ:</label>
                <?php foreach($bs as $r):?>
                <input type="text" id="bacSi" name="bacSi" value="<?php echo $r['HovaTen']; ?>" readonly class="form-control">
                <?php endforeach;?>
            </div>

            <div class="form-group">
                <label for="trieuChung">Triệu chứng nhập viện:</label>
                <textarea id="trieuChung" name="trieuChung" required class="form-control"></textarea>
            </div>

            <div class="form-group">
                <label for="ketQua">Kết quả:</label>
                <textarea id="ketQua" name="ketQua" required class="form-control"></textarea>
            </div>

            <div class="form-group">
                <label for="chuanDoan">Chuẩn đoán ban đầu:</label>
                <input type="text" id="chuanDoan" name="chuanDoan" required class="form-control">
            </div>

            <div class="form-group">
                <label>Đơn thuốc:</label>
                <button type="button" onclick="addMedicine()" class="btn btn-primary">Thêm thuốc</button>
                <div id="medicineList"></div>
            </div>

            <div class="form-group">
                <label for="loiDan">Lời dặn:</label>
                <textarea id="loiDan" name="loiDan" required class="form-control"></textarea>
            </div>

            <div class="form-group">
                <label for="ngayTaiKham">Ngày tái khám:</label>
                <input type="date" id="ngayTaiKham" name="ngayTaiKham" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" class="form-control">
            </div>

            <button type="submit" name="lap" class="btn btn-success">Lập phiếu khám</button>
        </form>
    </div>
</div>

<script>
    function addMedicine() {
        const medicineList = document.getElementById('medicineList');
        const medicineCount = medicineList.children.length + 1;
        const medicineHtml = `
            <div class="medicine-item">
                <h4>Thuốc ${medicineCount}</h4>
                <select name="thuoc[${medicineCount}][MaThuoc]" required class="form-control">
                    <option value="">Chọn thuốc</option>
                    <?php foreach ($data['ThuocList'] as $thuoc): ?>
                        <option value="<?php echo $thuoc['MaThuoc']; ?>"><?php echo $thuoc['TenThuoc']; ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="number" name="thuoc[soluong][]" placeholder="Số lượng" required class="form-control">
                <input type="text" name="thuoc[LieuDung][]" placeholder="Liều dùng" required class="form-control">
                <input type="text" name="thuoc[CachDung][]" placeholder="Cách dùng" required class="form-control">
            </div>
        `;
        medicineList.insertAdjacentHTML('beforeend', medicineHtml);
    }
</script>
<?php
