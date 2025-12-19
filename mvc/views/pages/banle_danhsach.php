<?php
$ds = json_decode($data["DS"], true);
$from_date = $data["from_date"];
$to_date   = $data["to_date"];
?>

<style>
    .bl-container {
        background: #ffffff;
        padding: 20px 24px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        font-family: Arial, sans-serif;
    }

    .bl-title {
        font-size: 22px;
        font-weight: 700;
        color: #0c857d;
        margin: 0 0 16px 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* Nhóm nút bên phải tiêu đề (Thêm mới + Quét QR) */
    .bl-title-actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .bl-filter-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-bottom: 18px;
        flex-wrap: wrap;
    }

    .bl-filter-form {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .bl-filter-label {
        font-size: 14px;
        color: #555;
        font-weight: 600;
    }

    .bl-filter-input {
        padding: 6px 10px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 14px;
    }

    .bl-btn {
        padding: 8px 18px;
        border-radius: 999px;
        border: none;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        transition: background 0.2s, box-shadow 0.2s, transform 0.1s;
    }

    .bl-btn-filter {
        background: #0c857d;
        color: #fff;
    }

    .bl-btn-filter:hover {
        background: #0a6d67;
        box-shadow: 0 2px 6px rgba(12, 133, 125, 0.4);
        transform: translateY(-1px);
    }

    .bl-btn-new {
        background: #f39c12;
        color: #fff;
    }

    .bl-btn-new:hover {
        background: #e67e22;
        box-shadow: 0 2px 6px rgba(230, 126, 34, 0.4);
        transform: translateY(-1px);
    }

    /* Nút mở camera quét QR */
    .bl-btn-qr {
        background: #2c3e50;
        color: #fff;
    }

    .bl-btn-qr:hover {
        background: #1a252f;
        box-shadow: 0 2px 6px rgba(44, 62, 80, 0.4);
        transform: translateY(-1px);
    }

    .bl-table-wrapper {
        border-radius: 8px;
        border: 1px solid #e1e1e1;
        overflow: hidden;
    }

    .bl-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .bl-table thead {
        background: #0c857d;
        color: #fff;
    }

    .bl-table th,
    .bl-table td {
        padding: 10px 8px;
        border-bottom: 1px solid #e1e1e1;
        text-align: left;
    }

    .bl-table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 13px;
    }

    .bl-table tbody tr:hover {
        background: #f6fbfa;
    }

    .bl-col-stt {
        width: 60px;
        text-align: center;
    }

    .bl-col-ma {
        width: 120px;
        text-align: center;
        font-weight: 700;
        color: #0c857d;
    }

    .bl-col-date {
        width: 140px;
        text-align: center;
        color: #555;
    }

    .bl-col-money {
        width: 140px;
        text-align: right;
        font-weight: 600;
        color: #333;
    }

    .bl-col-note {
        font-size: 13px;
        color: #555;
    }

    .bl-note-empty {
        font-style: italic;
        color: #999;
    }

    .bl-col-action {
        width: 120px;
        text-align: center;
    }

    .bl-btn-view {
        padding: 6px 14px;
        border-radius: 999px;
        border: 1px solid #0c857d;
        background: #fff;
        color: #0c857d;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s, color 0.2s;
        text-decoration: none; /* để trông như nút, không gạch chân */
        display: inline-block;
    }

    .bl-btn-view:hover {
        background: #0c857d;
        color: #fff;
    }

    .bl-empty {
        padding: 40px 10px;
        text-align: center;
        color: #777;
    }

    .bl-empty-icon {
        font-size: 40px;
        margin-bottom: 8px;
        opacity: 0.7;
    }

    /* ============================
       MODAL QUÉT QR ĐƠN BÁN LẺ
       ============================ */
    .bl-qr-modal {
        display: none; /* ẩn mặc định */
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
    }

    .bl-qr-dialog {
        background: #ffffff;
        border-radius: 10px;
        padding: 16px 18px;
        width: 100%;
        max-width: 520px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.3);
    }

    .bl-qr-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }

    .bl-qr-title {
        font-size: 18px;
        font-weight: 700;
        color: #0c857d;
    }

    .bl-qr-close {
        border: none;
        background: transparent;
        font-size: 22px;
        cursor: pointer;
        line-height: 1;
        padding: 0 4px;
    }

    .bl-qr-body {
        margin-top: 4px;
    }

    /* Vùng hiển thị camera của thư viện html5-qrcode */
    #bl-qr-reader {
        width: 100%;
        min-height: 260px;
    }

    .bl-qr-hint {
        font-size: 13px;
        color: #555;
        margin-top: 8px;
    }

    .bl-qr-status {
        font-size: 13px;
        margin-top: 4px;
        color: #888;
    }

    .bl-qr-status-success {
        color: #1e8449;
    }

    .bl-qr-status-error {
        color: #c0392b;
    }

    @media (max-width: 768px) {
        .bl-filter-row {
            flex-direction: column;
            align-items: flex-start;
        }

        .bl-filter-form {
            width: 100%;
        }

        .bl-table-wrapper {
            overflow-x: auto;
        }

        .bl-table {
            min-width: 700px;
        }

        .bl-qr-dialog {
            max-width: 95%;
        }
    }
</style>

<div class="bl-container">
    <div class="bl-title">
        <span>Danh sách đơn bán lẻ</span>

        <!-- Nhóm nút: Quét QR + Tạo đơn mới -->
        <div class="bl-title-actions">
            <!-- Nút mở camera quét QR -->
            <button type="button" class="bl-btn bl-btn-qr" id="bl-btn-open-qr">
                📷 Quét QR đơn
            </button>

            <!-- Nút tạo đơn bán lẻ mới -->
            <a href="/KLTN_Benhvien/NVNT/BanLeTao">
                <button type="button" class="bl-btn bl-btn-new">+ Tạo đơn bán lẻ mới</button>
            </a>
        </div>
    </div>

    <div class="bl-filter-row">
        <form action="/KLTN_Benhvien/NVNT/BanLe" method="POST" class="bl-filter-form">
            <span class="bl-filter-label">Từ ngày:</span>
            <input type="date" name="from_date" class="bl-filter-input" value="<?php echo htmlspecialchars($from_date); ?>" />
            <span class="bl-filter-label">Đến ngày:</span>
            <input type="date" name="to_date" class="bl-filter-input" value="<?php echo htmlspecialchars($to_date); ?>" />
            <button type="submit" class="bl-btn bl-btn-filter">Lọc</button>
        </form>
    </div>

    <div class="bl-table-wrapper">
        <table class="bl-table">
            <thead>
                <tr>
                    <th class="bl-col-stt">STT</th>
                    <th class="bl-col-ma">Mã đơn</th>
                    <th class="bl-col-date">Ngày kê</th>
                    <th class="bl-col-money">Tổng tiền</th>
                    <th>Ghi chú</th>
                    <th class="bl-col-action">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($ds)) {
                    $stt = 1;
                    foreach ($ds as $row) {
                        $maDon = $row["MaDon"];
                        $ngayKe = date('d-m-Y', strtotime($row["NgayKe"]));
                        $tongTien = (float)$row["TongTien"];
                        $ghiChu = trim($row["GhiChuChung"]);

                        if ($ghiChu === "") {
                            $ghiChuHtml = '<span class="bl-note-empty">Không có</span>';
                        } else {
                            $ghiChuHtml = htmlspecialchars($ghiChu);
                        }

                        echo '<tr>
                                <td class="bl-col-stt">'.$stt.'</td>
                                <td class="bl-col-ma">'.$maDon.'</td>
                                <td class="bl-col-date">'.$ngayKe.'</td>
                                <td class="bl-col-money">'.number_format($tongTien, 0, ',', '.').' đ</td>
                                <td class="bl-col-note">'.$ghiChuHtml.'</td>
                                <td class="bl-col-action">
                                    <!-- Đổi từ form POST sang link GET để truyền MaDon lên URL -->
                                    <a href="/KLTN_Benhvien/NVNT/BanLeChiTiet/'.$maDon.'" class="bl-btn-view">Xem / Sửa</a>
                                </td>
                              </tr>';
                        $stt++;
                    }
                } else {
                    echo '<tr><td colspan="6">
                            <div class="bl-empty">
                                <div class="bl-empty-icon">🧾</div>
                                <div>Không có đơn bán lẻ nào trong khoảng thời gian được chọn.</div>
                            </div>
                          </td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ============================
     MODAL QUÉT QR ĐƠN BÁN LẺ
     ============================ -->
<div class="bl-qr-modal" id="bl-qr-modal">
    <div class="bl-qr-dialog">
        <div class="bl-qr-header">
            <span class="bl-qr-title">Quét QR đơn bán lẻ</span>
            <button type="button" class="bl-qr-close" id="bl-qr-close">&times;</button>
        </div>
        <div class="bl-qr-body">
            <!-- Vùng camera của thư viện html5-qrcode -->
            <div id="bl-qr-reader"></div>
            <p class="bl-qr-hint">
                Đưa mã QR trên phiếu bán thuốc lại gần camera. Khi đọc được, hệ thống sẽ tự mở chi tiết đơn tương ứng.
            </p>
            <p class="bl-qr-status" id="bl-qr-status"></p>
        </div>
    </div>
</div>

<!-- Thư viện JS html5-qrcode dùng để quét QR bằng camera -->
<script src="https://unpkg.com/html5-qrcode"></script>

<script>
// JS quét QR bằng camera laptop cho danh sách đơn bán lẻ
(function() {
    const btnOpenQr = document.getElementById('bl-btn-open-qr');
    const modalQr   = document.getElementById('bl-qr-modal');
    const btnClose  = document.getElementById('bl-qr-close');
    const statusEl  = document.getElementById('bl-qr-status');

    let qrInstance = null;
    let isScanning = false;

    // Mở modal quét QR
    function openQrModal() {
        if (!modalQr) return;
        modalQr.style.display = 'flex';
        statusEl.textContent = 'Đang khởi động camera...';
        statusEl.className = 'bl-qr-status';

        // Khởi tạo và start camera nếu chưa chạy
        if (typeof Html5Qrcode !== 'undefined' && !isScanning) {
            const qrRegionId = 'bl-qr-reader';
            qrInstance = new Html5Qrcode(qrRegionId);
            const config = {
                fps: 10,
                qrbox: 250
            };

            qrInstance.start(
                { facingMode: "environment" }, // cố gắng dùng camera sau nếu có
                config,
                onScanSuccess,
                onScanFailure
            ).then(() => {
                isScanning = true;
                statusEl.textContent = 'Camera đã bật, vui lòng đưa mã QR vào khung.';
            }).catch(err => {
                statusEl.textContent = 'Không thể bật camera: ' + err;
                statusEl.className = 'bl-qr-status bl-qr-status-error';
            });
        }
    }

    // Đóng modal, dừng camera
    function closeQrModal() {
        if (modalQr) {
            modalQr.style.display = 'none';
        }
        if (qrInstance && isScanning) {
            qrInstance.stop().then(() => {
                qrInstance.clear();
                isScanning = false;
            }).catch(err => {
                console.error('Lỗi dừng camera:', err);
            });
        }
    }

    // Khi quét QR thành công
    function onScanSuccess(decodedText, decodedResult) {
        // decodedText là nội dung QR 
        if (!decodedText) {
            return;
        }

        statusEl.textContent = 'Đã đọc được mã: ' + decodedText;
        statusEl.className = 'bl-qr-status bl-qr-status-success';

        // Dừng scanner để tránh đọc nhiều lần
        if (qrInstance && isScanning) {
            qrInstance.stop().then(() => {
                qrInstance.clear();
                isScanning = false;
            }).catch(err => {
                console.error('Lỗi dừng sau khi scan:', err);
            });
        }

        // Xử lý điều hướng:
        // 1. Nếu nội dung là URL (bắt đầu bằng http), chuyển thẳng tới URL đó
        if (decodedText.indexOf('http://') === 0 || decodedText.indexOf('https://') === 0) {
            window.location.href = decodedText;
            return;
        }

        // 2. Nếu nội dung là chuỗi giống dạng /KLTN_Benhvien/NVNT/BanLeChiTiet/10
        if (decodedText.indexOf('/KLTN_Benhvien/NVNT/BanLeChiTiet/') === 0) {
            window.location.href = decodedText;
            return;
        }

        // 3. Nếu nội dung chỉ là số (MaDon), tự ghép URL chi tiết
        const onlyDigits = decodedText.replace(/\D/g, '');
        if (onlyDigits.length > 0 && /^\d+$/.test(onlyDigits)) {
            var target = '/KLTN_Benhvien/NVNT/BanLeChiTiet/' + onlyDigits;
            window.location.href = target;
            return;
        }

        // Nếu không nhận dạng được định dạng
        statusEl.textContent = 'Đã đọc được mã nhưng không đúng định dạng đường dẫn đơn bán lẻ: ' + decodedText;
        statusEl.className = 'bl-qr-status bl-qr-status-error';
    }

    // Khi fail (không đọc được trong một frame) - chỉ log, không báo lỗi liên tục
    function onScanFailure(error) {
        // Có thể bỏ trống để tránh spam status
        // console.log('Scan failed:', error);
    }

    if (btnOpenQr) {
        btnOpenQr.addEventListener('click', function() {
            openQrModal();
        });
    }

    if (btnClose) {
        btnClose.addEventListener('click', function() {
            closeQrModal();
        });
    }

    // Đóng modal khi bấm ra ngoài (click vùng tối)
    if (modalQr) {
        modalQr.addEventListener('click', function(e) {
            if (e.target === modalQr) {
                closeQrModal();
            }
        });
    }

    // Đảm bảo dừng camera khi rời trang (phòng hờ)
    window.addEventListener('beforeunload', function() {
        if (qrInstance && isScanning) {
            qrInstance.stop().then(() => {
                qrInstance.clear();
                isScanning = false;
            }).catch(() => {});
        }
    });
})();
</script>
