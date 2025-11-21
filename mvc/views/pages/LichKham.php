<?php
$lichKhamData = json_decode($data["LK"], true);
// Xác định Mã LK đầu tiên
$firstMaLK = isset($lichKhamData[0]['MaLK']) ? $lichKhamData[0]['MaLK'] : null;

// Kiểm tra xem đây có phải là lần tải trang đầu tiên (không có lịch nào được POST) không
$isInitialLoad = !isset($_POST['MaLK']) || empty($_POST['MaLK']);

// --- BƯỚC 1: LỌC DỮ LIỆU: CHỈ GIỮ LẠI TRẠNG THÁI 'Đã thanh toán' (và 'Da thanh toan') ---
$displayLichKhamData = [];
if (isset($lichKhamData) && is_array($lichKhamData)) {
    foreach ($lichKhamData as $lk) {
        $trangThaiCSDL = $lk['TrangThaiThanhToan'] ?? '';
        
        // Chỉ chấp nhận lịch có trạng thái Đã thanh toán (Chấp nhận cả có dấu và không dấu dựa trên lỗi cũ)
        if ($trangThaiCSDL === 'Đã thanh toán' || $trangThaiCSDL === 'Da thanh toan') {
            $displayLichKhamData[] = $lk;
        }
    }
}
// ----------------------------------------------------------------------
?>
<link rel="stylesheet" href="./public/css/lichkham.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
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
<h2 class="mt-3">Lịch khám</h2>

<div class="row">
    <div class="col-4">
        <div class="list-group">
         <?php if (!empty($displayLichKhamData)): // KIỂM TRA MẢNG ĐÃ LỌC ?>
            <?php $isFirstItem = true; // Biến cờ kiểm tra lịch đầu tiên ?>
            <?php foreach ($displayLichKhamData as $lichKham): // LẶP QUA MẢNG ĐÃ LỌC ?>
                <?php
                    // Đánh dấu lịch đầu tiên nếu là lần tải trang ban đầu
                    $isSelectedDefault = $isInitialLoad && $isFirstItem;
                    $isFirstItem = false; // Tắt cờ sau vòng lặp đầu tiên
                ?>
                <form method="POST" action="/KLTN_Benhvien/LichKham">
                    <input type="hidden" name="MaLK" value="<?= $lichKham['MaLK']; ?>">
                    
                    <div class="patient-item list-group-item <?= $isSelectedDefault ? 'selected-default' : ''; ?>" 
                         onclick="this.closest('form').submit()">
                        <p style="font-size: 18px;">
                            BS. <?= $lichKham['HovaTenNV']; ?>
                        </p>
                        <p style="font-size: 14px; text-align: left;">
                            <?= $lichKham['NgayKham']; ?> - <?= $lichKham['GioKham']; ?>
                        </p>
                        <p style="font-size: 14px; text-align: left;">
                            <?= $lichKham['HovaTen']; ?>
                        </p>
                        <p style="font-size: 14px; text-align: left;">
                            Mã số - <?= $lichKham['MaLK']; ?>
                        </p>
                    </div>
                </form>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center text-muted p-3">Bạn chưa có lịch khám nào!</p>
        <?php endif; ?>
        </div>
    </div>

    <div class="col-8">
        <?php
        // Đảm bảo dữ liệu chi tiết được giải mã đúng cách
        if (is_array($data["CTLK"])) {
            $data["CTLK"] = json_encode($data["CTLK"]);  
        }
            $chiTietData = json_decode($data["CTLK"], true); 
        ?>
        <?php if (isset($chiTietData) && !empty($chiTietData)): ?>
            <div class="chi-tiet-lich-kham" id="schedule-content-to-print">
                <?php foreach ($chiTietData as $ct): ?>
                    <p><strong>STT - <span id="print-stt-value"><?= $ct['STT']; ?></span></strong> </p> <hr>
                    <h5>Thông tin đặt khám</h5> <hr>
                    <p><strong>Mã lịch khám:</strong> <span id="print-ma-lk"><?= $ct['MaLK']; ?></span></p>
                    <p><strong>Ngày khám:</strong> <span id="print-ngay-kham"><?= $ct['NgayKham']; ?></span></p>
                    <p><strong>Giờ khám:</strong> <span id="print-gio-kham"><?= $ct['GioKham']; ?></span></p>
                    <p><strong>Chuyên khoa:</strong> <span id="print-chuyen-khoa"><?= $ct['TenKhoa']; ?></span></p>
                    <p><strong>Bác sĩ phụ trách:</strong> <span id="print-bac-si"><?= $ct['HovaTenNV']; ?></span></p>
                    <p><strong>Phòng khám:</strong> <span id="print-phong-kham"><?= $ct['TenPhongKham']; ?></span></p>


                    <h5>Thông tin bệnh nhân</h5> <hr>
                    <p><strong>Mã bệnh nhân:</strong> <span id="print-ma-bn"><?= $ct['MaBN']; ?></span></p>
                    <p><strong>Họ tên:</strong> <span id="print-ho-ten"><?= $ct['HovaTen']; ?></span></p>
                    <p><strong>Năm sinh:</strong> <span id="print-ngay-sinh"><?= $ct['NgaySinh']; ?></span></p>
                    <p><strong>Số điện thoại:</strong> <span id="print-sdt"><?= $ct['SoDT']; ?></span></p>
                    <p><strong>Giới tính:</strong> <span id="print-gioi-tinh"><?= $ct['GioiTinh']; ?></span></p>
                    <p><strong>Mã BHYT:</strong> <span id="print-bhyt"><?= $ct['BHYT']; ?></span></p>
                    <p><strong>Triệu chứng:</strong> <span id="print-trieu-chung"><?= $ct['TrieuChung']; ?></span></p>
                    <p><strong>Trạng thái:</strong> 
                        <span class="badge 
                            <?php 
                                // Dùng chuỗi CSDL (có dấu) để hiển thị màu sắc
                                if (isset($ct['TrangThaiThanhToan']) && $ct['TrangThaiThanhToan'] == 'Chua thanh toan') {
                                    echo 'bg-warning text-dark';
                                } elseif (isset($ct['TrangThaiThanhToan']) && $ct['TrangThaiThanhToan'] == 'Da thanh toan') {
                                    echo 'bg-success';
                                } elseif (isset($ct['TrangThaiThanhToan']) && $ct['TrangThaiThanhToan'] == 'Đã hủy') {
                                    echo 'bg-danger';
                                } else {
                                    echo 'bg-secondary';
                                }
                            ?>
                        "><?= $ct['TrangThaiThanhToan'] ?? 'Đang cập nhật'; ?></span>
                    </p>
                    
                    
                    <?php 
                    // ********** LOGIC HIỂN THỊ NOTE CHƯA THANH TOÁN **********
                    if (isset($ct['TrangThaiThanhToan']) && $ct['TrangThaiThanhToan'] == 'Chưa thanh toán'): ?>
                        <div class="alert alert-info mt-3" role="alert">
                            ⚠️ Lịch khám sẽ tự động hủy nếu bạn không thanh toán trong vòng 30 phut kể từ khi đăng ký.
                        </div>
                    <?php endif; 
                    // *************************************************************
                    ?>
                <?php endforeach; ?>
            </div>

            <div class="button">
                
                <button type="button" onclick="printSchedule()" class="btn btn-primary" id="btnPrintSchedule">
                    <i class="fas fa-print"></i> In Lịch khám
                </button>
                </div>

            <div class="modal fade" id="changeScheduleModal" tabindex="-1" aria-labelledby="changeScheduleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="changeScheduleModalLabel">Thay đổi lịch khám</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="/KLTN_Benhvien/LichKham">
                                <input type="hidden" name="ThayDoiLK" value="<?= $ct['MaLK']; ?>">
                                <div class="form-group">
                                    <label for="NgayKham">Chọn ngày khám:</label>
                                    <input type="date" name="NgayKham" id="NgayKham" class="form-control" required>
                                </div>
                                <div class="form-group mt-3">
                                    <label for="GioKham">Chọn giờ khám:</label>
                                    <input type="time" name="GioKham" id="GioKham" class="form-control" required>
                                </div>
                                <div class="modal-footer mt-4">
                                    <button type="submit" class="btn btn-change">Lưu thay đổi</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        <?php else: ?>
            <p>Vui lòng chọn lịch khám để xem chi tiết!</p>
        <?php endif; ?>
    </div>
</div>

 <script>
    // Hàm in Lịch khám (Chức năng in)
    function printSchedule() {
        // Lấy dữ liệu trực tiếp từ các span/element đã được gán ID
        const data = {
            maPhieu: document.getElementById('print-ma-lk')?.textContent || 'N/A',
            phongKham: document.getElementById('print-phong-kham')?.textContent || 'N/A',
            chuyenKhoa: document.getElementById('print-chuyen-khoa')?.textContent || 'N/A',
            stt: document.getElementById('print-stt-value')?.textContent || 'N/A',
            ngayKham: document.getElementById('print-ngay-kham')?.textContent || 'N/A',
            gioKham: document.getElementById('print-gio-kham')?.textContent || 'N/A',
            hoTen: document.getElementById('print-ho-ten')?.textContent || 'N/A',
            gioiTinh: document.getElementById('print-gioi-tinh')?.textContent || 'N/A',
            ngaySinh: document.getElementById('print-ngay-sinh')?.textContent || 'N/A',
            bhyt: document.getElementById('print-bhyt')?.textContent || 'Không có',
            trieuChung: document.getElementById('print-trieu-chung')?.textContent || 'N/A',
        };
        
        // Chuyển BHYT thành "Có" hoặc "Không"
        const bhytDisplay = data.bhyt && data.bhyt !== 'Không có' ? 'Có' : 'Không';
        
        // Xây dựng cấu trúc HTML cho Phiếu Khám
        const printHTML = `
            <div style="width: 100%; margin: 0 auto; padding: 20px; font-family: Arial, sans-serif; line-height: 1.5; font-size: 14px;">
                
                <div style="text-align: center; padding-bottom: 10px;">
                    <p style="font-size: 14px; margin: 0; font-weight: bold;">BỆNH VIỆN QUẬN THỦ ĐỨC</p>
                    <p style="font-size: 12px; margin: 0; color: #007bff; font-style: italic;">Chất lượng - Cảm Thông - Vững Tiến</p>
                </div>

                <h3 style="text-align: center; margin-top: 25px; font-weight: bold; font-size: 18px;">PHIẾU KHÁM BỆNH</h3>
                <p style="text-align: center; margin: 5px 0;">(Mã phiếu: ${data.maPhieu})</p>
                <p style="text-align: center; margin: 2px 0;">Phòng khám: ${data.phongKham}</p>
                <p style="text-align: center; margin: 2px 0;">Chuyên khoa: ${data.chuyenKhoa}</p>

                <div style="text-align: center; margin: 20px 0;">
                    <p style="font-size: 70px; font-weight: 900; color: #0056b3; margin: 0;">${data.stt}</p>
                </div>

                <div style="font-size: 15px; margin-top: 20px;">
                    <p style="margin: 3px 0;"><b>Ngày khám:</b> ${data.ngayKham}</p>
                    <p style="margin: 3px 0;"><b>Giờ khám dự kiến:</b> ${data.gioKham}</p>
                    <p style="margin: 3px 0;"><b>Họ tên:</b> ${data.hoTen.toUpperCase()}</p>
                    <p style="margin: 3px 0;"><b>Giới tính:</b> ${data.gioiTinh}</p>
                    <p style="margin: 3px 0;"><b>Ngày sinh:</b> ${data.ngaySinh}</p>
                    <p style="margin: 3px 0;"><b>Triệu chứng:</b> ${data.trieuChung}</p>
                    <p style="margin: 3px 0;"><b>BHYT:</b> ${bhytDisplay}</p>
                </div>
                
                <div style="margin-top: 40px; font-size: 12px; text-align: center; color: #777;">
                    Vui lòng mang theo Phiếu này và có mặt tại phòng khám trước 15 phút.
                </div>
            </div>
        `;

        // 4. Mở cửa sổ in và in
        const printWindow = window.open('', '', 'height=700,width=500');
        
        if (printWindow) {
            printWindow.document.write('<html><head><title>Phiếu Khám Bệnh</title>');
            printWindow.document.write('</head><body>');
            printWindow.document.write(printHTML);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            
            printWindow.onload = function() {
                printWindow.print();
            };
        } else {
            alert("Vui lòng cho phép cửa sổ bật lên (pop-up) để in phiếu khám.");
        }
    }


    document.addEventListener("DOMContentLoaded", function () {
        // --- Logic Ẩn Alert Message ---
        const alertMessage = document.getElementById("alert-message");
        if (alertMessage) {
            setTimeout(() => {
                alertMessage.style.transition = "opacity 0.5s ease";
                alertMessage.style.opacity = "0";
                setTimeout(() => alertMessage.remove(), 100); 
            }, 1000); 
        }
        
        // --- Logic Tự Động Chọn Lịch Khám Đầu Tiên ---
        const isScheduleSubmitted = <?= (isset($_POST['MaLK']) && !empty($_POST['MaLK'])) ? 'true' : 'false'; ?>;
        
        if (!isScheduleSubmitted) {
            const defaultItem = document.querySelector(".patient-item.selected-default");
            
            if (defaultItem) {
                defaultItem.click(); 
            }
        }
    });
</script>
<script>
    // Giữ nguyên script cho nút thay đổi lịch (nếu cần)
    document.getElementById("btnChangeSchedule").addEventListener("click", function(event) {
        event.preventDefault(); 
        document.getElementById("changeScheduleForm").style.display = "block";
    });
</script>