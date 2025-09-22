<?php
if(isset($data["ThongTinBenhNhan"])) {
    $thongTinBenhNhan = json_decode($data["ThongTinBenhNhan"], true);
    $phieuKhamBenhNhan = json_decode($data["PhieuKhamBenhNhan"], true);
    $soLanKhamBenh = $data["SoLanKhamBenh"];
?>
<?php
    if($thongTinBenhNhan) {
        foreach($thongTinBenhNhan as $index => $tt) {
?>
        <div class="patient-info">
            <h3>Bệnh nhân</h3>
            <div class="info-grid">
                <div class="info-item"><span class="info-label">Mã bệnh nhân:</span> <?php echo $tt['MaBN']; ?></div>
                <div class="info-item"><span class="info-label">BHYT:</span> <?php echo $tt['BHYT']; ?></div>
                <div class="info-item"><span class="info-label">Họ và Tên:</span> <?php echo $tt['HovaTen']; ?></div>
                <div class="info-item"><span class="info-label">Địa chỉ:</span> <?php echo $tt['DiaChi']; ?></div>
                <div class="info-item"><span class="info-label">Ngày sinh:</span> <?php echo date('d-m-Y', strtotime($tt['NgaySinh'])); ?></div>
                <div class="info-item"><span class="info-label">Số điện thoại:</span> <?php echo $tt['SoDT']; ?></div>
                <div class="info-item"><span class="info-label">Giới tính:</span> <?php echo $tt['GioiTinh']; ?></div>
            </div>
        </div>
<?php } ?>
        <div class="medical-history">
            <h3>Các lần khám bệnh</h3>
            <p><span class="info-label">Số lần khám bệnh:</span> <?php echo $soLanKhamBenh; ?></p>
            <?php
            if($phieuKhamBenhNhan) {
                foreach($phieuKhamBenhNhan as $index => $phieu) {
            ?>
                    <div class="visit-details">
                        <h4>Kết quả khám bệnh lần <?php echo $index + 1; ?>:</h4>
                        <div class="info-grid">
                            <div class="info-item"><span class="info-label">Ngày khám:</span> <?php echo date('d/m/Y', strtotime($phieu['NgayTao'])); ?></div>
                            <div class="info-item"><span class="info-label">Bác sĩ:</span> <?php echo $phieu['BacSi']; ?></div>
                            <div class="info-item"><span class="info-label">Triệu chứng:</span> <?php echo $phieu['TrieuChung']; ?></div>
                            <div class="info-item"><span class="info-label">Kết quả:</span> <?php echo $phieu['KetQua']; ?></div>
                            <div class="info-item"><span class="info-label">Chuẩn đoán ban đầu:</span> <?php echo $phieu['ChuanDoan']; ?></div>
                            <div class="info-item"><span class="info-label">Lời dặn:</span> <?php echo $phieu['LoiDan']; ?></div>
                            <div class="info-item"><span class="info-label">Ngày tái khám:</span> <?php echo $phieu['NgayTaiKham'] ? date('d/m/Y', strtotime($phieu['NgayTaiKham'])) : 'Không'; ?></div>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo "<p>Bệnh nhân chưa có lần khám bệnh nào.</p>";
            }
            ?>
        </div>
<?php
    } else {
        echo "<p>Không tìm thấy thông tin bệnh nhân với mã bệnh nhân đã nhập.</p>";
    }
} else {
    echo "<p>Vui lòng nhập mã bệnh nhân để xem lịch sử khám bệnh.</p>";
}
?>

