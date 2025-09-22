
<?php
$phieuKhamData = json_decode($data["PK"], true);
?>
<link rel="stylesheet" href="./public/css/xemphieukham.css">
    <h2 class="mt-3">Phiếu khám</h2>
    <div class="col-4">
        <div class="list-group">
         <?php if (isset($phieuKhamData) && !empty($phieuKhamData)): ?>
            <?php foreach ($phieuKhamData as $phieuKham): ?>
                <form method="POST" action="/KLTN_Benhvien/XemPhieuKham">
                    <input type="hidden" name="MaPK" value="<?= $phieuKham['phieukham_MaPK'];  ?>">
                    <div class="patient-item list-group-item" onclick="this.closest('form').submit()">
                        <p style="font-size: 18px;">
                            BS. <?= $phieuKham['HovaTenNV']; ?>
                        </p>
                        <p style="font-size: 14px; text-align: left;">
                            <?= $phieuKham['NgayTao']; ?> 
                        </p>
                        <p style="font-size: 14px; text-align: left;">
                            <?= $phieuKham['HovaTen']; ?>
                        </p>
                        <p style="font-size: 14px; text-align: left;">
                            Mã số - <?= $phieuKham['phieukham_MaPK']; ?>
                        </p>
                    </div>
                </form>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Không có phiếu khám nào được lập!</p>
        <?php endif; ?>
        </div>
    </div>

    <div class="col-8">
    <?php
    if (is_array($data["CTPK"])) {
        $data["CTPK"] = json_encode($data["CTPK"]);  
    }
    $chiTietData = json_decode($data["CTPK"], true); 
    ?>
    <?php if (isset($chiTietData) && !empty($chiTietData)): ?>
        <div class="chi-tiet-phieu-kham">
            <?php foreach ($chiTietData as $ct): ?>
                    
                    <div class="header-info">
                    <h6 style="text-align: center;"><strong>BỆNH VIỆN ĐOM ĐÓM</strong></h6>
                        <p>Địa chỉ: F4/9C tổ 14 ấp 6C, xã Vĩnh Lộc A, huyện Bình Chánh, TP.HCM</p>
                    </div>
                    <h3>BỆNH ÁN</h3>
                    <p style="text-align: center;"><strong>Ngày lập bệnh án:</strong> <?= $ct['NgayTao'] ?? 'Không'; ?></p>
                    <hr>
                    <h6><strong>THÔNG TIN BỆNH NHÂN</strong></h6>
                        <div>
                            <p><strong>Họ và tên:</strong> <?= $ct['HovaTen'] ?? 'Không'; ?></p>
                            <p><strong>Ngày sinh:</strong> <?= $ct['NgaySinh'] ?? 'Không'; ?></p>
                            <p><strong>Giới tính:</strong> <?= $ct['GioiTinh'] ?? 'Không'; ?></p>
                            <p><strong>Địa chỉ:</strong> <?= $ct['DiaChi'] ?? 'Không'; ?></p>
                        </div>
                    </div>
                    <hr>
                    <h6><strong>KHÁM VÀ CHUẨN ĐOÁN</strong></h6>
                    <div>
                        <p><strong>Chỉ định xét nghiệm:</strong> <?= $ct['LoaiXN'] ?? 'Không'; ?></p>
                        <p><strong>Kết quả xét nghiệm:</strong> <?= $ct['KetQua'] ?? 'Không'; ?></p>
                        <p><strong>Chuẩn đoán:</strong> <?= $ct['ChuanDoan'] ?? 'Không'; ?></p>
                    </div>
                    
                    <div class="footerFORM">
                        <div class="signature">
                            <p><strong>Bác sĩ khám</strong></p>
                            <p>(Ký và ghi rõ họ tên)</p>
                            <br><br>
                            <p><strong><?= $ct['HovaTenNV'] ?? 'Không'; ?></strong></p>
                        </div>
                        <div class="signature">
                            <p><strong>Bệnh nhân</strong></p>
                            <p>(Ký và ghi rõ họ tên)</p>
                            <br><br>
                            <p><strong><?= $ct['HovaTen'] ?? 'Không'; ?></strong></p>
                        </div>
                    </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
            <p>Vui lòng chọn phiếu khám để xem chi tiết!</p>
        <?php endif; ?>
    </div>


    
