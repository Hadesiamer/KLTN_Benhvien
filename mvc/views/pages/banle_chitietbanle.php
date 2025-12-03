<?php 
$headerArr = json_decode($data["Header"], true);
$thuocArr  = json_decode($data["Thuoc"], true);
$editable  = !empty($data["editable"]);
$error     = isset($data["error"]) ? $data["error"] : "";
$success   = isset($data["success"]) ? $data["success"] : "";
$DanhSachThuoc = isset($data["DanhSachThuoc"]) ? json_decode($data["DanhSachThuoc"], true) : [];

$MaDon       = 0;
$NgayKeRaw   = "";
$NgayKe      = "";
$GhiChuChung = "";
$TongTien    = 0;
$TenNV       = "";

if (!empty($headerArr)) {
    $h           = $headerArr[0];
    $MaDon       = $h["MaDon"];
    $NgayKeRaw   = $h["NgayKe"];
    $NgayKe      = date('d-m-Y', strtotime($h["NgayKe"]));
    $GhiChuChung = $h["GhiChuChung"];
    $TongTien    = (float)$h["TongTien"];
    $TenNV       = $h["TenNV"];
}

/* ============================================
   TẠO NỘI DUNG QR: LINK ĐẦY ĐỦ ĐƠN BÁN LẺ
   ============================================ */
$qrContent = "";
if ($MaDon > 0) {
    // Link đầy đủ theo yêu cầu: http://localhost/KLTN_Benhvien/NVNT/BanLeChiTiet/{MaDon}
    $qrContent = "http://localhost/KLTN_Benhvien/NVNT/BanLeChiTiet/" . $MaDon;
}
?>

<style>
    /* ==============================
       GIAO DIỆN CHI TIẾT TRÊN MÀN HÌNH
       ============================== */
    .bl-detail-container {
        background: #ffffff;
        padding: 22px 24px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        font-family: Arial, sans-serif;
    }

    .bl-detail-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 16px;
    }

    .bl-detail-title {
        font-size: 22px;
        font-weight: 700;
        color: #0c857d;
        margin: 0;
    }

    .bl-detail-sub {
        font-size: 13px;
        color: #777;
    }

    .bl-tag {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 12px;
        margin-left: 6px;
    }

    .bl-tag-editable {
        background: #eaf9f1;
        color: #1e8449;
    }

    .bl-tag-locked {
        background: #fbeaea;
        color: #c0392b;
    }

    .bl-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 12px;
        margin-bottom: 16px;
    }

    .bl-info-item-label {
        font-size: 12px;
        text-transform: uppercase;
        color: #888;
        margin-bottom: 2px;
    }

    .bl-info-item-value {
        font-size: 14px;
        font-weight: 600;
        color: #333;
    }

    .bl-detail-label {
        font-size: 14px;
        font-weight: 600;
        color: #444;
        margin-bottom: 4px;
        display: block;
    }

    .bl-detail-textarea {
        width: 100%;
        padding: 8px 10px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 14px;
        min-height: 70px;
        resize: vertical;
        box-sizing: border-box;
    }

    .bl-detail-textarea[readonly] {
        background: #f7f7f7;
        color: #555;
    }

    .bl-med-table-wrapper {
        border-radius: 8px;
        border: 1px solid #e1e1e1;
        overflow: hidden;
        margin: 14px 0;
    }

    .bl-med-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .bl-med-table thead {
        background: #0c857d;
        color: #fff;
    }

    .bl-med-table th,
    .bl-med-table td {
        padding: 8px 6px;
        border-bottom: 1px solid #e1e1e1;
        text-align: left;
    }

    .bl-med-table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 12px;
    }

    .bl-med-table tbody tr:hover {
        background: #f6fbfa;
    }

    .bl-input-small {
        width: 70px;
        padding: 6px 8px;
        border-radius: 4px;
        border: 1px solid #ccc;
        font-size: 13px;
    }

    .bl-input-text {
        width: 100%;
        padding: 6px 8px;
        border-radius: 4px;
        border: 1px solid #ccc;
        font-size: 13px;
        box-sizing: border-box;
    }

    .bl-input-readonly {
        background: #f7f7f7;
        color: #555;
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

    .bl-btn-add {
        background: #3498db;
        color: #fff;
        margin-bottom: 10px;
    }

    .bl-btn-add:hover {
        background: #2980b9;
        box-shadow: 0 2px 6px rgba(41, 128, 185, 0.4);
        transform: translateY(-1px);
    }

    .bl-btn-remove-row {
        padding: 4px 8px;
        border-radius: 999px;
        border: none;
        background: #e74c3c;
        color: #fff;
        cursor: pointer;
        font-size: 12px;
        font-weight: 600;
    }

    .bl-btn-remove-row:hover {
        background: #c0392b;
    }

    .bl-total-row {
        background: #f7fdfb;
        font-weight: 700;
    }

    /* Ô tổng cộng (base) */
    .bl-total-cell {
        padding: 8px 10px;
        font-size: 14px;
        vertical-align: middle;
    }

    /* Label bên trái: cho phép xuống dòng thoải mái */
    .bl-total-label {
        text-align: left;
        white-space: normal;
    }

    /* Số tiền bên phải: luôn 1 dòng, ép cột co lại */
    .bl-total-amount {
        text-align: right;
        white-space: nowrap;
        width: 1%;
    }

    .bl-actions {
        margin-top: 16px;
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
    }

    .bl-actions-right {
        display: flex;
        gap: 10px;
    }

    .bl-btn-back {
        background: #bdc3c7;
        color: #2c3e50;
    }

    .bl-btn-back:hover {
        background: #95a5a6;
        box-shadow: 0 2px 6px rgba(149,165,166,0.4);
        transform: translateY(-1px);
    }

    .bl-btn-print {
        background: #f39c12;
        color: #fff;
    }

    .bl-btn-print:hover {
        background: #e67e22;
        box-shadow: 0 2px 6px rgba(230,126,34,0.4);
        transform: translateY(-1px);
    }

    .bl-btn-save {
        background: #27ae60;
        color: #fff;
    }

    .bl-btn-save:hover {
        background: #1e8449;
        box-shadow: 0 2px 6px rgba(39,174,96,0.4);
        transform: translateY(-1px);
    }

    .bl-alert {
        padding: 10px 12px;
        border-radius: 6px;
        font-size: 13px;
        margin-bottom: 10px;
    }

    .bl-alert-error {
        background: #fbeaea;
        color: #c0392b;
        border: 1px solid #e74c3c;
    }

    .bl-alert-success {
        background: #eaf9f1;
        color: #1e8449;
        border: 1px solid #27ae60;
    }

    @media (max-width: 768px) {
        .bl-med-table-wrapper {
            overflow-x: auto;
        }

        .bl-med-table {
            min-width: 800px;
        }
    }

    /* ==============================
       PHẦN PHIẾU IN ĐƠN GIẢN (BILL)
       ============================== */

    .print-bill {
        display: none; /* chỉ dùng khi in */
    }

    /* --------- QR CODE CHO PHIẾU IN ---------- */
    .print-bill-info-layout {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 8mm;
    }

    .print-bill-info-left {
        flex: 1;
    }

    .print-bill-qr-wrapper {
        text-align: center;
        min-width: 45mm;
    }

    .print-bill-qr-text {
        font-size: 10pt;
        margin-bottom: 2mm;
    }

    /* container chứa QR do JS vẽ vào */
    .print-bill-qr-img {
        width: 40mm;
        height: 40mm;
        margin: 0 auto;
    }

    /* đảm bảo canvas/img bên trong fit khung */
    .print-bill-qr-img canvas,
    .print-bill-qr-img img {
        width: 100% !important;
        height: 100% !important;
        display: block;
    }

    .print-bill-qr-link {
        margin-top: 2mm;
        font-size: 8pt;
        word-break: break-all;
    }
    /* ----------------------------------------- */

    @media print {
        /* Ẩn tất cả mọi thứ, CHỈ hiển thị khối .print-bill */
        body {
            margin: 0;
            font-family: "Times New Roman", serif;
            font-size: 12pt;
            color: #000;
            background: #fff;
        }

        /* Ẩn toàn bộ nội dung trang */
        body * {
            visibility: hidden;
        }

        /* Chỉ cho phép khối phiếu in hiển thị và chiếm trang */
        .print-bill,
        .print-bill * {
            visibility: visible;
        }

        /* Đưa phiếu in lên đầu trang in */
        .print-bill {
            display: block;
            position: absolute;
            left: 0;
            top: 0;
            padding: 10mm 12mm;
            width: 100%;
        }

        /* Ẩn giao diện chi tiết web (cho chắc) */
        .bl-detail-container {
            display: none !important;
        }

        .print-bill h1,
        .print-bill h2,
        .print-bill h3,
        .print-bill p,
        .print-bill span,
        .print-bill td,
        .print-bill th {
            color: #000 !important;
        }

        .print-bill-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4mm;
        }

        .print-bill-table th,
        .print-bill-table td {
            border: 1px solid #000;
            padding: 2mm 2mm;
            font-size: 11pt;
        }

        .print-bill-header {
            text-align: center;
            margin-bottom: 4mm;
        }

        .print-bill-header h2 {
            margin: 0;
            font-size: 16pt;
            font-weight: bold;
        }

        .print-bill-header p {
            margin: 1mm 0 0 0;
            font-size: 11pt;
        }

        .print-bill-info {
            margin-top: 4mm;
            font-size: 11pt;
        }

        .print-bill-total {
            margin-top: 4mm;
            font-size: 12pt;
            font-weight: bold;
            text-align: right;
        }

        @page {
            margin: 10mm;
        }
    }
</style>

<?php
// TÍNH LẠI TỔNG TIỀN DỰA TRÊN DANH SÁCH THUỐC (để dùng cho cả bảng & bill)
$tongTienTinhLai = 0;
if (!empty($thuocArr)) {
    foreach ($thuocArr as $item) {
        $soLuong = (int)$item["SoLuong"];
        $donGia  = (float)$item["DonGiaBan"];
        $tongTienTinhLai += $soLuong * $donGia;
    }
}
if ($TongTien <= 0 && $tongTienTinhLai > 0) {
    $TongTien = $tongTienTinhLai;
}
?>

<div class="bl-detail-container">
    <div class="bl-detail-header">
        <div>
            <h2 class="bl-detail-title">Đơn bán lẻ #<?php echo htmlspecialchars($MaDon); ?></h2>
            <div class="bl-detail-sub">
                Ngày kê: <strong><?php echo $NgayKe; ?></strong>
                <?php if ($editable): ?>
                    <span class="bl-tag bl-tag-editable">Có thể chỉnh sửa trong ngày hôm nay</span>
                <?php else: ?>
                    <span class="bl-tag bl-tag-locked">Chỉ xem - không thể chỉnh sửa vì đã qua ngày</span>
                <?php endif; ?>
            </div>
        </div>
        <div class="bl-detail-sub">
            Nhân viên bán thuốc: <strong><?php echo htmlspecialchars($TenNV); ?></strong>
        </div>
    </div>

    <?php if ($error !== ""): ?>
        <div class="bl-alert bl-alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if ($success !== ""): ?>
        <div class="bl-alert bl-alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <div class="bl-info-grid">
        <div>
            <div class="bl-info-item-label">Mã đơn</div>
            <div class="bl-info-item-value"><?php echo htmlspecialchars($MaDon); ?></div>
        </div>
        <div>
            <div class="bl-info-item-label">Trạng thái thanh toán</div>
            <div class="bl-info-item-value">Đã thanh toán</div>
        </div>
        <div>
            <div class="bl-info-item-label">Tổng tiền</div>
            <div class="bl-info-item-value"><?php echo number_format($TongTien, 0, ',', '.'); ?> đ</div>
        </div>
    </div>

    <form action="/KLTN_Benhvien/NVNT/BanLeChiTiet" method="POST" id="bl-form-detail">
        <input type="hidden" name="MaDon" value="<?php echo htmlspecialchars($MaDon); ?>">

        <div style="margin-bottom: 14px;">
            <label class="bl-detail-label">Ghi chú chung đơn bán lẻ:</label>
            <textarea name="GhiChuChung" class="bl-detail-textarea" <?php echo $editable ? '' : 'readonly'; ?>><?php echo htmlspecialchars($GhiChuChung); ?></textarea>
        </div>

        <div class="bl-detail-label" style="margin-bottom: 6px;">Danh sách thuốc</div>

        <?php if ($editable): ?>
            <button type="button" class="bl-btn bl-btn-add" id="bl-btn-add-row">+ Thêm thuốc</button>
        <?php endif; ?>

        <div class="bl-med-table-wrapper">
            <table class="bl-med-table" id="bl-med-table">
                <thead>
                    <tr>
                        <th style="width: 40px;">#</th>
                        <th style="min-width: 200px;">Tên thuốc</th>
                        <th style="min-width: 120px;">Dạng</th>
                        <th style="min-width: 100px;">Hàm lượng</th>
                        <th style="min-width: 80px;">Đơn vị</th>
                        <th style="width: 80px;">Số lượng</th>
                        <th style="min-width: 150px;">Liều dùng</th>
                        <?php if ($editable): ?>
                            <th style="width: 60px;">Xóa</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody id="bl-med-tbody">
                    <?php
                    $stt = 1;
                    if (!empty($thuocArr)) {
                        foreach ($thuocArr as $item) {
                            $maThuoc   = $item["MaThuoc"];
                            $tenThuoc  = $item["TenThuoc"];
                            $soLuong   = (int)$item["SoLuong"];
                            $donGia    = (float)$item["DonGiaBan"];
                            $lieuDung  = $item["LieuDung"];

                            echo '<tr>
                                <td>'.$stt.'</td>
                                <td>
                                    <select name="ma_thuoc[]" class="bl-input-text bl-select-thuoc" data-selected="'.htmlspecialchars($maThuoc).'"></select>
                                </td>
                                <td><input type="text" class="bl-input-text bl-input-readonly bl-dang" readonly /></td>
                                <td><input type="text" class="bl-input-text bl-input-readonly bl-hamluong" readonly /></td>
                                <td><input type="text" class="bl-input-text bl-input-readonly bl-donvitinh" readonly /></td>
                                <td><input type="number" name="so_luong[]" class="bl-input-small" min="1" value="'.$soLuong.'" '.($editable ? '' : 'readonly').'/></td>
                                <td><input type="text" name="lieu_dung[]" class="bl-input-text" value="'.htmlspecialchars($lieuDung).'" '.($editable ? '' : 'readonly').'/></td>';
                            if ($editable) {
                                echo '<td style="text-align:center;"><button type="button" class="bl-btn-remove-row">X</button></td>';
                            }
                            echo '</tr>';
                            $stt++;
                        }
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr class="bl-total-row">
                        <td colspan="<?php echo $editable ? 7 : 7; ?>" class="bl-total-cell bl-total-label">
                            Tổng cộng (tính lại theo Danh mục thuốc):
                        </td>
                        <td class="bl-total-cell bl-total-amount" colspan="<?php echo $editable ? 1 : 1; ?>" id="bl-total-display">
                            <?php echo number_format($tongTienTinhLai, 0, ',', '.'); ?> đ
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="bl-actions">
            <a href="/KLTN_Benhvien/NVNT/BanLe">
                <button type="button" class="bl-btn bl-btn-back">Quay lại danh sách</button>
            </a>
            <div class="bl-actions-right">
                <button type="button" class="bl-btn bl-btn-print" onclick="window.print()">In đơn thuốc</button>
                <?php if ($editable): ?>
                    <button type="submit" name="luuThayDoi" class="bl-btn bl-btn-save">Lưu thay đổi</button>
                <?php endif; ?>
            </div>
        </div>
    </form>
</div>

<!-- ============================
     KHỐI PHIẾU IN ĐƠN GIẢN (BILL)
     ============================ -->
<div class="print-bill">
    <div class="print-bill-header">
        <h2>BỆNH VIỆN ĐỨC TÂM</h2>
        <p>PHIẾU BÁN THUỐC</p>
    </div>

    <div class="print-bill-info">
        <div class="print-bill-info-layout">
            <div class="print-bill-info-left">
                <div class="print-bill-info-row">
                    <span><strong>Mã đơn:</strong> <?php echo htmlspecialchars($MaDon); ?></span>
                    <span><strong>Ngày kê:</strong> <?php echo $NgayKe; ?></span>
                </div>
                <div class="print-bill-info-row" style="margin-top:2mm;">
                    <span><strong>Nhân viên bán thuốc:</strong> <?php echo htmlspecialchars($TenNV); ?></span>
                    <span><strong>Trạng thái:</strong> Đã thanh toán</span>
                </div>
                <div style="margin-top:2mm;">
                    <strong>Ghi chú:</strong>
                    <?php echo $GhiChuChung !== "" ? nl2br(htmlspecialchars($GhiChuChung)) : "Không có"; ?>
                </div>
            </div>

            <!-- QR CODE: hiển thị to, rõ bên phải phiếu in -->
            <div class="print-bill-qr-wrapper">
                <div class="print-bill-qr-text">
                    QR đơn #<?php echo htmlspecialchars($MaDon); ?>
                </div>
                <?php if ($qrContent !== ""): ?>
                    <!-- JS sẽ vẽ QR vào đây -->
                    <div id="print-bill-qr-js" class="print-bill-qr-img"></div>
                    <div class="print-bill-qr-link">
                        <?php echo htmlspecialchars($qrContent); ?>
                    </div>
                <?php else: ?>
                    <div style="font-size:9pt; font-style:italic;">Không tạo được QR</div>
                <?php endif; ?>
            </div>
            <!-- END QR CODE -->
        </div>
    </div>

    <table class="print-bill-table">
        <thead>
            <tr>
                <th style="width:5%;">STT</th>
                <th style="width:35%;">Tên thuốc</th>
                <th style="width:15%;">Hàm lượng</th>
                <th style="width:15%;">Dạng</th>
                <th style="width:10%;">Đơn vị</th>
                <th style="width:10%;">SL</th>
                <th style="width:10%;">Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sttIn = 1;
            if (!empty($thuocArr)) {
                foreach ($thuocArr as $item) {
                    $tenThuoc  = $item["TenThuoc"];
                    $hamLuong  = isset($item["HamLuong"]) ? $item["HamLuong"] : "";
                    $dangBC    = isset($item["DangBaoChe"]) ? $item["DangBaoChe"] : "";
                    $donViTinh = isset($item["DonViTinh"]) ? $item["DonViTinh"] : "";
                    $soLuong   = (int)$item["SoLuong"];
                    $donGia    = (float)$item["DonGiaBan"];
                    $thanhTien = $soLuong * $donGia;

                    echo "<tr>
                        <td style=\"text-align:center;\">".$sttIn."</td>
                        <td>".htmlspecialchars($tenThuoc)."</td>
                        <td>".htmlspecialchars($hamLuong)."</td>
                        <td>".htmlspecialchars($dangBC)."</td>
                        <td>".htmlspecialchars($donViTinh)."</td>
                        <td style=\"text-align:center;\">".$soLuong."</td>
                        <td style=\"text-align:right;\">".number_format($thanhTien, 0, ',', '.')."</td>
                    </tr>";
                    $sttIn++;
                }
            } else {
                echo '<tr><td colspan="7" style="text-align:center;">Không có thuốc trong đơn.</td></tr>';
            }
            ?>
        </tbody>
    </table>

    <div class="print-bill-total">
        Tổng tiền: <?php echo number_format($TongTien, 0, ',', '.'); ?> đ
    </div>

    <div style="margin-top:10mm; display:flex; justify-content:space-between;">
        <div style="text-align:center;">
            <strong>Người bệnh / Khách hàng</strong><br>
            <span>(Ký và ghi rõ họ tên)</span>
        </div>
        <div style="text-align:center;">
            <strong>Nhân viên bán thuốc</strong><br>
            <span>(Ký và ghi rõ họ tên)</span>
        </div>
    </div>
</div>

<script>
// Dữ liệu danh sách thuốc server gửi sang cho dropdown
window.BL_THUOC_LIST = <?php echo json_encode($DanhSachThuoc ?: []); ?>;
</script>

<!-- THƯ VIỆN TẠO QR CODE BẰNG JS (qrcode.js) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
// Sinh QR code cho phiếu in, không phụ thuộc server bên ngoài
(function() {
    var qrContent = <?php echo json_encode($qrContent); ?>;
    if (!qrContent) return;
    var el = document.getElementById('print-bill-qr-js');
    if (!el) return;

    var size = 220; // px, sẽ được CSS ép fit 40mm khi in
    try {
        new QRCode(el, {
            text: qrContent,
            width: size,
            height: size
        });
    } catch (e) {
        console.error('Lỗi tạo QR:', e);
    }
})();
</script>

<script>
// JS thuần cho dropdown thuốc + thêm/xóa dòng (nếu được phép chỉnh sửa)
// Giữ nguyên logic như cũ, chỉ thao tác trên bảng chỉnh sửa, không ảnh hưởng phiếu in
(function() {
    const tbody = document.getElementById('bl-med-tbody');
    const btnAdd = document.getElementById('bl-btn-add-row');
    const totalDisplay = document.getElementById('bl-total-display');
    const thuocList = Array.isArray(window.BL_THUOC_LIST) ? window.BL_THUOC_LIST : [];
    const editable = <?php echo $editable ? 'true' : 'false'; ?>;

    function buildThuocSelect(selectElement, selectedMaThuoc) {
        while (selectElement.firstChild) {
            selectElement.removeChild(selectElement.firstChild);
        }

        const defaultOpt = document.createElement('option');
        defaultOpt.value = '';
        defaultOpt.textContent = '-- Chọn thuốc --';
        selectElement.appendChild(defaultOpt);

        thuocList.forEach(function(item) {
            const opt = document.createElement('option');
            opt.value = item.MaThuoc || '';
            const ten = item.TenThuoc || '';
            const hamluong = item.HamLuong || '';
            const dang = item.DangBaoChe || '';
            const dvt = item.DonViTinh || '';

            let label = ten;
            if (hamluong !== '') {
                label += ' - ' + hamluong;
            }
            if (dang !== '') {
                label += ' (' + dang + ')';
            }
            if (dvt !== '') {
                label += ' - ' + dvt;
            }

            opt.textContent = label;
            opt.setAttribute('data-dang', dang);
            opt.setAttribute('data-hamluong', hamluong);
            opt.setAttribute('data-donvitinh', dvt);
            opt.setAttribute('data-dongia', item.DonGiaBan || '');

            if (selectedMaThuoc && String(selectedMaThuoc) === String(item.MaThuoc)) {
                opt.selected = true;
            }

            selectElement.appendChild(opt);
        });
    }

    function refreshIndex() {
        const rows = tbody.querySelectorAll('tr');
        rows.forEach((tr, idx) => {
            const sttCell = tr.querySelector('td');
            if (sttCell) {
                sttCell.textContent = idx + 1;
            }
        });
    }

    function attachEventsForRow(tr) {
        const btnRemove   = tr.querySelector('.bl-btn-remove-row');
        const selectThuoc = tr.querySelector('.bl-select-thuoc');
        const dangInput   = tr.querySelector('.bl-dang');
        const hamInput    = tr.querySelector('.bl-hamluong');
        const dvtInput    = tr.querySelector('.bl-donvitinh');
        const soLuongInput= tr.querySelector('input[name="so_luong[]"]');

        if (selectThuoc) {
            const selectedMa = selectThuoc.getAttribute('data-selected') || '';
            buildThuocSelect(selectThuoc, selectedMa);

            const opt = selectThuoc.options[selectThuoc.selectedIndex];
            if (opt && opt.value) {
                dangInput.value = opt.getAttribute('data-dang') || '';
                hamInput.value  = opt.getAttribute('data-hamluong') || '';
                dvtInput.value  = opt.getAttribute('data-donvitinh') || '';
            }

            if (editable) {
                selectThuoc.addEventListener('change', function() {
                    const o = selectThuoc.options[selectThuoc.selectedIndex];
                    if (!o || !o.value) {
                        dangInput.value = '';
                        hamInput.value  = '';
                        dvtInput.value  = '';
                        return;
                    }
                    dangInput.value = o.getAttribute('data-dang') || '';
                    hamInput.value  = o.getAttribute('data-hamluong') || '';
                    dvtInput.value  = o.getAttribute('data-donvitinh') || '';
                });
            }
        }

        if (btnRemove && editable) {
            btnRemove.addEventListener('click', function() {
                tbody.removeChild(tr);
                refreshIndex();
            });
        }

        if (soLuongInput && editable) {
            soLuongInput.addEventListener('input', function() {
                if (parseInt(soLuongInput.value || '0', 10) < 1) {
                    soLuongInput.value = '1';
                }
            });
        }
    }

    (function initExistingRows() {
        const rows = tbody.querySelectorAll('tr');
        rows.forEach(tr => {
            attachEventsForRow(tr);
        });
        refreshIndex();
    })();

    if (btnAdd && editable) {
        btnAdd.addEventListener('click', function() {
            const index = tbody.querySelectorAll('tr').length;
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${index + 1}</td>
                <td>
                    <select name="ma_thuoc[]" class="bl-input-text bl-select-thuoc" data-selected=""></select>
                </td>
                <td><input type="text" class="bl-input-text bl-input-readonly bl-dang" readonly /></td>
                <td><input type="text" class="bl-input-text bl-input-readonly bl-hamluong" readonly /></td>
                <td><input type="text" class="bl-input-text bl-input-readonly bl-donvitinh" readonly /></td>
                <td><input type="number" name="so_luong[]" class="bl-input-small" min="1" value="1" /></td>
                <td><input type="text" name="lieu_dung[]" class="bl-input-text" placeholder="Ví dụ: 1 ngày 2 lần..." /></td>
                <td style="text-align:center;"><button type="button" class="bl-btn-remove-row">X</button></td>
            `;
            tbody.appendChild(tr);
            attachEventsForRow(tr);
            refreshIndex();
        });
    }
})();
</script>
