<?php
$dt = json_decode($data["CTDT"], true);
?>

<style>
.prescription-detail-container {
    background: #f8f9fa;
    border-radius: 16px;
    padding: 30px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}

.prescription-detail-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 3px solid #e9ecef;
}

.prescription-detail-header h1 {
    color: #2c3e50;
    font-size: 32px;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
}

.prescription-number {
    color: #667eea;
}

.patient-info-section {
    background: white;
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 30px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}

.patient-info-section h2 {
    color: #2c3e50;
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.info-label {
    font-size: 13px;
    font-weight: 600;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-value {
    font-size: 15px;
    font-weight: 500;
    color: #2c3e50;
    padding: 8px 12px;
    background: #f8f9fa;
    border-radius: 6px;
}

.medicine-section {
    background: white;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    margin-bottom: 30px;
}

.medicine-section h2 {
    color: #2c3e50;
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.medicine-table {
    width: 100%;
    border-collapse: collapse;
    overflow: hidden;
    border-radius: 8px;
}

.medicine-table thead {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.medicine-table thead th {
    padding: 14px 10px;
    text-align: center;
    font-weight: 600;
    font-size: 13px;
    color: white;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.medicine-table tbody tr {
    border-bottom: 1px solid #f1f3f5;
    transition: all 0.2s ease;
}

.medicine-table tbody tr:hover {
    background-color: #f8f9fa;
}

.medicine-table tbody td {
    padding: 14px 10px;
    font-size: 14px;
    color: #495057;
    text-align: center;
}

.medicine-table tbody td:nth-child(3) {
    text-align: left;
    font-weight: 500;
}

.medicine-table tbody td:nth-child(6) {
    text-align: left;
    font-size: 13px;
}

.medicine-table tfoot tr {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    font-weight: 700;
}

.medicine-table tfoot th,
.medicine-table tfoot td {
    padding: 16px 10px;
    font-size: 16px;
    color: #2c3e50;
}

.total-amount {
    color: #667eea;
    font-size: 18px;
}

.action-buttons {
    display: flex;
    gap: 15px;
    justify-content: flex-end;
    margin-top: 30px;
}

.btn-action {
    padding: 12px 32px;
    border: none;
    border-radius: 10px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-cancel {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
}

.btn-cancel:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(245, 87, 108, 0.4);
}

.btn-confirm {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
}

.btn-confirm:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(79, 172, 254, 0.4);
}

.status {
    display: inline-block;
    padding: 8px 20px;
    border-radius: 25px;
    font-size: 14px;
    font-weight: 600;
}

.status-chua {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
}

.status-da {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
}

.status-huy {
    background: linear-gradient(135deg, #a8a8a8 0%, #6c757d 100%);
    color: white;
}

@media (max-width: 768px) {
    .prescription-detail-container {
        padding: 20px 15px;
    }
    
    .prescription-detail-header {
        flex-direction: column;
        gap: 15px;
        align-items: flex-start;
    }
    
    .prescription-detail-header h1 {
        font-size: 24px;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .medicine-table {
        overflow-x: auto;
        display: block;
    }
    
    .medicine-table table {
        min-width: 800px;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .btn-action {
        width: 100%;
        justify-content: center;
    }
}
</style>

<div class="prescription-detail-container">
<?php
if (!empty($dt)) {
    foreach ($dt as $r):
        echo '
        <form action="" method="post">
            <div class="prescription-detail-header">
                <h1>📄 Đơn thuốc <span class="prescription-number">#'.$r["MaDT"].'</span></h1>
                <input type="hidden" name="MaDT" value="'.$r["MaDT"].'">';

        $trangThaiText = $r["TrangThai"];
        $statusClass   = "status-chua";
        if ($trangThaiText === "Da thanh toan") {
            $statusClass = "status-da";
        } elseif ($trangThaiText === "Huy") {
            $statusClass = "status-huy";
        }

        echo '<span class="status '.$statusClass.'">'.$trangThaiText.'</span>
            </div>
            
            <div class="patient-info-section">
                <h2>👤 Thông tin bệnh nhân</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Họ và tên</div>
                        <div class="info-value">'.$r["HovaTen"].'</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Email</div>
                        <div class="info-value">'.$r["Email"].'</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Điện thoại</div>
                        <div class="info-value">'.$r["SoDT"].'</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Bảo hiểm y tế</div>
                        <div class="info-value">'.$r["BHYT"].'</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Bác sĩ kê đơn</div>
                        <div class="info-value">'.$r["TenBS"].'</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Thời gian kê đơn</div>
                        <div class="info-value">'.$r["NgayTao"].'</div>
                    </div>
                </div>
            </div>';
    endforeach;
}

echo '<div class="medicine-section">
        <h2>💊 Danh sách thuốc</h2>
        <table class="medicine-table">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Mã thuốc</th>
                    <th>Tên thuốc</th>
                    <th>Số lượng</th>
                    <th>Đơn giá</th>
                    <th>Liều dùng</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>';

$thuoc = json_decode($data["Thuoc"], true);
$dem   = 1;
$t1    = 0;

if (!empty($thuoc)) {
    foreach ($thuoc as $f):
        $soLuong   = (int)$f["SoLuong"];
        $donGia    = (float)$f["DonGiaBan"];
        $tt        = $soLuong * $donGia;

        echo '<tr>
                <td style="font-weight: 600;">'.$dem.'</td>
                <td style="color: #667eea; font-weight: 600;">'.$f["MaThuoc"].'</td>
                <td>'.$f["TenThuoc"].'</td>
                <td style="font-weight: 600;">'.$soLuong.'</td>
                <td>'.number_format($donGia, 0, ',', '.').' đ</td>
                <td>'.$f["LieuDung"].'</td>
                <td style="font-weight: 600; color: #667eea;">'.number_format($tt, 0, ',', '.').' đ</td>
            </tr>';
        $dem++;
        $t1 += $tt;
    endforeach;
}

echo '<tr>
        <th colspan="6" style="text-align: right; padding-right: 20px; font-size: 16px;">TỔNG CỘNG</th>
        <td class="total-amount" style="font-weight: 700;">'.number_format($t1, 0, ',', '.').' đ</td>
    </tr>
    </tbody>
</table>
</div>';

echo '
    <div class="action-buttons">
        <button type="submit" class="btn-action btn-cancel" name="nutHuy" onclick="return confirm(\'Bạn có chắc chắn muốn hủy đơn thuốc này không?\')">
            ❌ Hủy đơn thuốc
        </button>
        <button type="submit" class="btn-action btn-confirm" name="nutXN">
            ✅ Xác nhận đã thanh toán
        </button>
    </div>
</form>
</div>';

if (isset($data["Result"])) {
    if ($data["Result"] === 1) {
        echo '<script>
                alert("✅ Cập nhật thanh toán đơn thuốc thành công!");
                window.location.href = "/KLTN_Benhvien/NVNT";
              </script>';
    } elseif ($data["Result"] === 3) {
        echo '<script>
                alert("✅ Hủy đơn thuốc thành công!");
                window.location.href = "/KLTN_Benhvien/NVNT";
              </script>';
    } else {
        echo '<script>
                alert("❌ Cập nhật đơn thuốc thất bại. Vui lòng thử lại!");
              </script>';
    }
}
?>