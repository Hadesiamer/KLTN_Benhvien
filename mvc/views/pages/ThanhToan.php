<?php 
$lichKhamData = json_decode($data["LK"], true);
?>

<link rel="stylesheet" href="./public/css/thanhtoan.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<div class="col-12">
    <?php if (isset($data["Message"]) && $data["Message"] != ""): ?>
        <div id="alert-message" 
             class="alert <?= $data["MessageType"] === 'error' ? 'alert-danger' : 'alert-success'; ?>" 
             role="alert">
            <?= $data["Message"]; ?>
        </div>
    <?php endif; ?>
</div>

<h2 class="mt-3">Thanh toán</h2>

<div class="row mt-3">
    <!-- DANH SÁCH LỊCH KHÁM BÊN TRÁI -->
    <div class="col-4">
        <div class="list-group">
        <?php if (isset($lichKhamData) && !empty($lichKhamData)): ?>
            <?php foreach ($lichKhamData as $lichKham): ?>
                <?php 
                    // Định dạng ngày khám dạng dd-mm-yyyy
                    $ngayKhamFormatted = '';
                    if (!empty($lichKham['NgayKham'])) {
                        $ngayKhamFormatted = date('d-m-Y', strtotime($lichKham['NgayKham']));
                    }
                ?>
                <form method="POST" action="/KLTN_Benhvien/ThanhToan">
                    <input type="hidden" name="MaLK" value="<?= htmlspecialchars($lichKham['MaLK']); ?>">
                    <div class="patient-item list-group-item" onclick="this.closest('form').submit()">
                        <p class="mb-1" style="font-size: 16px; font-weight: 600;">
                            BS. <?= htmlspecialchars($lichKham['HovaTenNV']); ?>
                        </p>
                        <p class="mb-1" style="font-size: 13px; text-align: left;">
                            <?= htmlspecialchars($ngayKhamFormatted); ?> - <?= htmlspecialchars($lichKham['GioKham']); ?>
                        </p>
                        <p class="mb-1" style="font-size: 13px; text-align: left;">
                            <?= htmlspecialchars($lichKham['HovaTen']); ?>
                        </p>
                        <p class="mb-0" style="font-size: 13px; text-align: left; color:#555;">
                            Mã LK: <?= htmlspecialchars($lichKham['MaLK']); ?>
                        </p>
                    </div>
                </form>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Không có lịch khám nào được đặt!</p>
        <?php endif; ?>
        </div>
    </div>

    <!-- CHI TIẾT LỊCH KHÁM BÊN PHẢI -->
    <div class="col-8">
        <?php
        // CTLK có thể là JSON hoặc array, chuẩn hóa về JSON rồi decode
        if (is_array($data["CTLK"])) {
            $data["CTLK"] = json_encode($data["CTLK"]);
        }
        $chiTietData = json_decode($data["CTLK"], true);

        // Lấy cấu hình SePay (VA, ngân hàng, số tiền) từ controller truyền sang
        $sepayConfig = isset($data["SePay"]) && is_array($data["SePay"]) ? $data["SePay"] : null;
        ?>

        <?php if (isset($chiTietData) && !empty($chiTietData)): ?>
            <?php 
                // Lấy 1 dòng đầu tiên để hiển thị gọn (các dòng khác thường trùng thông tin)
                $ct = $chiTietData[0]; 

                // Định dạng ngày khám dạng dd-mm-yyyy cho phần "Ngày - giờ"
                $ngayKhamChiTietFormatted = '';
                if (!empty($ct['NgayKham'])) {
                    $ngayKhamChiTietFormatted = date('d-m-Y', strtotime($ct['NgayKham']));
                }

                // Ép kiểu Năm sinh về dd-mm-yyyy
                $namSinhFormatted = '';
                if (!empty($ct['NgaySinh'])) {
                    $namSinhFormatted = date('d-m-Y', strtotime($ct['NgaySinh']));
                }

                // Đổi trạng thái trong DB -> tiếng Việt có dấu để hiển thị
                $trangThaiDisplay = '';
                if (!empty($ct['TrangThaiThanhToan'])) {
                    $trangThaiDb = strtolower(trim($ct['TrangThaiThanhToan']));
                    switch ($trangThaiDb) {
                        case 'chua thanh toan':
                            $trangThaiDisplay = 'Chưa thanh toán';
                            break;
                        case 'da thanh toan':
                            $trangThaiDisplay = 'Đã thanh toán';
                            break;
                        default:
                            $trangThaiDisplay = $ct['TrangThaiThanhToan'];
                            break;
                    }
                }

                // ================== TẠO LINK QR ĐỘNG SEPAY VA ==================
                $qrUrl = '';
                if ($sepayConfig && !empty($ct['MaLK'])) {
                    $acc    = $sepayConfig['va_acc'];       // VQRQAFSGX7208
                    $bank   = $sepayConfig['bank'];         // MBBank
                    $amount = (int)$sepayConfig['amount'];  // 10000
                    $maLK   = $ct['MaLK'];

                    // des = LK{MaLK}, ví dụ LK123
                    $des    = 'LK' . $maLK;

                    $qrUrl  = 'https://qr.sepay.vn/img'
                            . '?acc='   . urlencode($acc)
                            . '&bank='  . urlencode($bank)
                            . '&amount='. $amount
                            . '&des='   . urlencode($des);
                }
            ?>

            <div class="card mb-3 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><strong>Lịch khám cần thanh toán</strong></span>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-secondary">
                            Mã LK: <?= htmlspecialchars($ct['MaLK']); ?>
                        </span>
                        <span class="badge <?= ($trangThaiDisplay === 'Đã thanh toán') ? 'bg-success' : 'bg-warning text-dark'; ?>">
                            <?= htmlspecialchars($trangThaiDisplay); ?>
                        </span>
                    </div>
                </div>
                <div class="card-body p-3">
                    <!-- THÔNG TIN ĐẶT KHÁM NGẮN GỌN -->
                    <div class="mb-2">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div style="font-size:14px;">
                                    <strong>Ngày - giờ khám:</strong> 
                                    <?= htmlspecialchars($ngayKhamChiTietFormatted); ?>, 
                                    <?= htmlspecialchars($ct['GioKham']); ?>
                                </div>
                                <div style="font-size:14px;">
                                    <strong>Chuyên khoa:</strong> <?= htmlspecialchars($ct['TenKhoa']); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="my-2">

                    <!-- THÔNG TIN BỆNH NHÂN GỌN -->
                    <div class="row mb-2" style="font-size:14px;">
                        <div class="col-6">
                            <p class="mb-1"><strong>Bệnh nhân:</strong> <?= htmlspecialchars($ct['HovaTen']); ?></p>
                            <p class="mb-1"><strong>Mã Bệnh Nhân:</strong> <?= htmlspecialchars($ct['MaBN']); ?></p>
                            <p class="mb-1"><strong>SĐT:</strong> <?= htmlspecialchars($ct['SoDT']); ?></p>
                        </div>
                        <div class="col-6">
                            <p class="mb-1"><strong>Năm sinh:</strong> <?= htmlspecialchars($namSinhFormatted); ?></p>
                            <p class="mb-1"><strong>Giới tính:</strong> <?= htmlspecialchars($ct['GioiTinh']); ?></p>
                            <p class="mb-1"><strong>BHYT:</strong> <?= htmlspecialchars($ct['BHYT']); ?></p>
                        </div>
                    </div>

                    <hr class="my-2">

                    <!-- HƯỚNG DẪN THANH TOÁN SEPAY + QR ĐỘNG -->
                    <div class="mb-2">
                        <h5 class="mb-2">Thông tin thanh toán qua SePay (QR động VA)</h5>

                        <!-- Thông tin tài khoản gốc (cho bệnh nhân muốn chuyển tay) -->
                        <p class="mb-1"><strong>Số tài khoản:</strong> 010401304888</p>
                        <p class="mb-1"><strong>Chủ tài khoản:</strong> TRAN NHAT CUONG</p>
                        <p class="mb-1"><strong>Ngân hàng:</strong> MBBank</p>

                        <!-- Thông tin VA / số tiền / nội dung -->
                        <?php if ($sepayConfig): ?>
                            <p class="mb-1"><strong>Tài khoản ảo (VA) SePay:</strong> <?= htmlspecialchars($sepayConfig['va_acc']); ?></p>
                            <p class="mb-1"><strong>Số tiền:</strong> <?= number_format((int)$sepayConfig['amount'], 0, ',', '.'); ?> VND / 1 lịch khám</p>
                        <?php else: ?>
                            <p class="mb-1 text-danger"><strong>Lỗi cấu hình SePay: chưa có thông tin VA.</strong></p>
                        <?php endif; ?>

                        <p class="mb-1">
                            <strong>Nội dung chuyển khoản (Code thanh toán):</strong> 
                            <span style="color:#d63384; font-weight:bold;">
                                LK<?= htmlspecialchars($ct['MaLK']); ?>
                            </span>
                        </p>

                        <!-- QR ĐỘNG -->
                        <?php if ($qrUrl !== ''): ?>
                            <div class="text-center mt-3">
                                <img src="<?= htmlspecialchars($qrUrl); ?>" 
                                     alt="QR thanh toán SePay" 
                                     style="max-width:260px; width:100%; height:auto; border:1px solid #eee; padding:6px; border-radius:8px;">
                                <p style="font-size: 0.9em; color:#555; margin-top:8px;">
                                    Quét mã QR bằng ứng dụng ngân hàng để thanh toán nhanh.<br>
                                    Số tiền và nội dung <strong>LK<?= htmlspecialchars($ct['MaLK']); ?></strong> 
                                    sẽ được điền sẵn.
                                </p>
                            </div>
                        <?php endif; ?>

                        <p class="mt-2" style="font-size: 0.9em; color:#555;">
                        </p>
                        <!-- ===== GHI CHÚ BẮT BUỘC: NỘI DUNG PHẢI CÓ LK{MaLK} ===== -->
                        <p style="font-size: 0.9em; color:#d63384; font-weight:bold;">
                            ➤ Lưu ý: <u>Nội dung chuyển khoản BẮT BUỘC phải chứa chính xác chuỗi 
                            "LK<?= htmlspecialchars($ct['MaLK']); ?>"</u>.<br>
                            Nếu thiếu hoặc gõ sai mã (ví dụ gõ nhầm số), hệ thống sẽ không thể tự động
                            xác nhận thanh toán cho lịch khám này.
                        </p>
                    </div>
                </div>
            </div>

            <!-- GHI CHÚ + NÚT HỦY LỊCH -->
            <div class="button-area">
                <p style="color: #007bff; font-size: 0.9em; margin-top: 5px;">
                    * Nếu bạn muốn thay đổi lịch khám, vui lòng hủy lịch này và đăng ký lại lịch mới.<br>
                    * Lệ phí thanh toán là 10,000 VND cho mỗi lần khám. (Demo)
                </p>
                <div class="d-flex gap-2 mt-2">
                    <form method="POST" action="" style="display: inline-block;" id="cancelForm"> 
                        <input type="hidden" name="MaLK_Huy" value="<?= htmlspecialchars($ct['MaLK']); ?>" id="MaLK_Huy_Input">
                        <button type="button" 
                                class="btn btn-danger btn-sm" 
                                onclick="confirmCancel()">
                            Hủy lịch khám
                        </button>
                    </form>
                </div>
            </div>

        <?php else: ?>
            <p>Vui lòng chọn lịch khám bạn muốn thanh toán!</p>
        <?php endif; ?>
    </div>
</div>

<script>
    // Hàm xác nhận và submit form Hủy
    function confirmCancel() {
        const maLK = document.getElementById('MaLK_Huy_Input').value; 
        if (confirm("Bạn có chắc chắn muốn hủy lịch khám Mã số " + maLK + " không?")) {
            document.getElementById('cancelForm').submit();
        }
    }
    
    document.addEventListener("DOMContentLoaded", function () {
        // Ẩn alert message sau 2s
        <?php if (isset($data["Message"]) && $data["Message"] != ""): ?>
            setTimeout(() => {
                const alertMessage = document.getElementById("alert-message");
                if (alertMessage) {
                    alertMessage.style.transition = "opacity 0.5s ease";
                    alertMessage.style.opacity = "0";
                    setTimeout(() => alertMessage.remove(), 500); 
                }
            }, 2000); 
        <?php endif; ?>
    });
</script>

<?php
// Đoạn alert cũ giữ nguyên để có thể dùng cho các case khác sau này
if (isset($data['rs'])) {
    if ($data["rs"] == 'true') {
        echo '<script language="javascript">
                alert("Hoàn tất");  
              </script>';
    } else {
        echo '<script language="javascript">
                alert("Thất bại");  
              </script>';
    }
}
?>
