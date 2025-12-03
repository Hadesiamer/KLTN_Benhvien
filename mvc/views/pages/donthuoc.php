<?php
$dt         = json_decode($data["DT"], true);
$pagination = $data["Pagination"];
$dem        = $pagination->getOffset() + 1;
$loc        = $data["loc"];
$keyword    = isset($data["keyword"]) ? $data["keyword"] : "";
?>

<style>
/* Medical theme colors */
:root {
    --primary-blue: #0c6e6d; /* đổi màu tiêu đề & nút chính sang xanh ngọc */
    --secondary-blue: #4d94ff;
    --success-green: #28a745;
    --danger-red: #dc3545;
    --warning-orange: #fd7e14;
    --neutral-gray: #6c757d;
    --light-bg: #f8f9fa;
    --white: #ffffff;
    --border-color: #dee2e6;
}

.medical-container {
    background: var(--white);
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.page-title {
    color: var(--primary-blue);
    font-size: 26px;
    font-weight: 700;
    margin: 0 0 25px 0;
    padding-bottom: 15px;
    border-bottom: 3px solid var(--primary-blue);
    display: flex;
    align-items: center;
    gap: 10px;
}

.page-title::before {
    content: "📋";
    font-size: 28px;
}

.filter-bar {
    background: var(--light-bg);
    padding: 15px 20px;
    border-radius: 6px;
    margin-bottom: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    border: 1px solid var(--border-color);
}

.filter-left {
    display: flex;
    align-items: center;
    gap: 10px;
    flex: 1;
}

.filter-right {
    display: flex;
    align-items: center;
    gap: 12px;
}

.filter-label {
    font-weight: 600;
    color: var(--neutral-gray);
    font-size: 14px;
}

.search-input {
    flex: 1;
    min-width: 220px;
    padding: 8px 12px;
    border: 1px solid var(--border-color);
    border-radius: 4px;
    font-size: 14px;
}

.search-input:focus {
    outline: none;
    border-color: var(--primary-blue);
    box-shadow: 0 0 0 2px rgba(12, 110, 109, 0.1);
}

.filter-select {
    min-width: 200px;
    padding: 8px 12px;
    border: 1px solid var(--border-color);
    border-radius: 4px;
    font-size: 14px;
    background: var(--white);
    cursor: pointer;
    transition: all 0.2s;
}

.filter-select:hover,
.filter-select:focus {
    border-color: var(--primary-blue);
    outline: none;
    box-shadow: 0 0 0 2px rgba(12, 110, 109, 0.1);
}

.btn-filter {
    padding: 8px 24px;
    background: var(--primary-blue);
    color: var(--white);
    border: none;
    border-radius: 4px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-filter:hover {
    background: #0a5756;
}

.table-wrapper {
    background: var(--white);
    border: 1px solid var(--border-color);
    border-radius: 6px;
    overflow: hidden;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}

.data-table thead {
    background: linear-gradient(180deg, var(--primary-blue) 0%, #0a5756 100%);
}

.data-table thead th {
    padding: 14px 12px;
    text-align: center;
    font-weight: 600;
    font-size: 13px;
    color: var(--white);
    text-transform: uppercase;
    letter-spacing: 0.3px;
    border-bottom: 2px solid rgba(255,255,255,0.2);
}

.data-table tbody tr {
    border-bottom: 1px solid var(--border-color);
    transition: background-color 0.2s;
}

.data-table tbody tr:hover {
    background-color: #f1f7ff;
}

.data-table tbody tr:last-child {
    border-bottom: none;
}

.data-table tbody td {
    padding: 12px;
    color: #2c3e50;
    vertical-align: middle;
}

.table-stt {
    text-align: center;
    font-weight: 600;
    color: var(--neutral-gray);
    width: 60px;
}

.table-code {
    text-align: center;
    font-weight: 700;
    color: var(--primary-blue);
    font-family: 'Courier New', monospace;
}

.table-name {
    text-align: left;
    font-weight: 500;
}

.table-date {
    text-align: center;
    white-space: nowrap;
    color: var(--neutral-gray);
}

.table-status {
    text-align: center;
}

.status-badge {
    display: inline-block;
    padding: 5px 14px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 700;
    text-align: center;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    white-space: nowrap;
}

.status-pending {
    background-color: #fff3cd;
    color: #856404;
    border: 1px solid #ffc107;
}

.status-paid {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #28a745;
}

.status-cancelled {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #dc3545;
}

.table-note {
    text-align: left;
    color: var(--neutral-gray);
    font-size: 13px;
    max-width: 200px;
}

.note-empty {
    color: #adb5bd;
    font-style: italic;
}

.table-action {
    text-align: center;
    width: 120px;
}

.btn-view {
    padding: 6px 18px;
    background: var(--white);
    color: var(--primary-blue);
    border: 2px solid var(--primary-blue);
    border-radius: 4px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-view:hover {
    background: var(--primary-blue);
    color: var(--white);
}

.pagination-area {
    margin-top: 25px;
    display: flex;
    justify-content: center;
}

.pagination-box {
    display: flex;
    gap: 5px;
    align-items: center;
    background: var(--light-bg);
    padding: 10px 15px;
    border-radius: 6px;
    border: 1px solid var(--border-color);
}

.pagination-box form {
    display: inline-block;
    margin: 0;
}

.btn-page {
    padding: 6px 12px;
    min-width: 36px;
    border: 1px solid var(--border-color);
    background: var(--white);
    border-radius: 4px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    color: var(--neutral-gray);
}

.btn-page:hover {
    background: var(--primary-blue);
    color: var(--white);
    border-color: var(--primary-blue);
}

.page-active {
    padding: 6px 12px;
    min-width: 36px;
    background: var(--primary-blue);
    color: var(--white);
    border-radius: 4px;
    font-weight: 700;
    text-align: center;
}

.page-dots {
    padding: 0 6px;
    color: var(--neutral-gray);
}

.no-data {
    text-align: center;
    padding: 50px 20px;
    color: var(--neutral-gray);
}

.no-data-icon {
    font-size: 48px;
    margin-bottom: 15px;
    opacity: 0.5;
}

.no-data-text {
    font-size: 16px;
    font-weight: 500;
}

@media (max-width: 992px) {
    .filter-bar {
        flex-direction: column;
        align-items: stretch;
    }
    
    .filter-left,
    .filter-right {
        width: 100%;
    }
    
    .search-input,
    .filter-select,
    .btn-filter {
        width: 100%;
    }
}

@media (max-width: 768px) {
    .medical-container {
        padding: 15px;
    }
    
    .page-title {
        font-size: 22px;
    }
    
    .table-wrapper {
        overflow-x: auto;
    }
    
    .data-table {
        min-width: 900px;
    }
    
    .pagination-box {
        flex-wrap: wrap;
    }
}
</style>

<div class="medical-container">
    <h1 class="page-title">Danh Sách Đơn Thuốc Bác Sĩ Kê</h1>
    
    <div class="filter-bar">
        <div class="filter-left">
            <span class="filter-label">Tìm kiếm bệnh nhân:</span>
            <form action="/KLTN_Benhvien/NVNT" method="POST" style="display:flex; gap:10px; width:100%; margin:0;">
                <input
                    type="text"
                    name="keyword"
                    class="search-input"
                    placeholder="Nhập mã bệnh nhân hoặc số điện thoại..."
                    value="<?php echo htmlspecialchars($keyword); ?>"
                >
        </div>
        <div class="filter-right">
            <span class="filter-label">Trạng thái:</span>
                <select name="loc" class="filter-select">
                    <option value="all" <?php echo $loc === "all" ? "selected" : ""; ?>>Tất cả đơn thuốc (bác sĩ kê)</option>
                    <option value="Chua thanh toan" <?php echo $loc === "Chua thanh toan" ? "selected" : ""; ?>>Chưa thanh toán</option>
                    <option value="Da thanh toan" <?php echo $loc === "Da thanh toan" ? "selected" : ""; ?>>Đã thanh toán</option>
                    <option value="Huy" <?php echo $loc === "Huy" ? "selected" : ""; ?>>Đã hủy</option>
                </select>
                <button type="submit" name="btnLoc" class="btn-filter">Lọc / Tìm</button>
            </form>
        </div>
    </div>

    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Mã đơn thuốc</th>
                    <th>Tên bệnh nhân</th>
                    <th>Ngày kê</th>
                    <th>Trạng thái</th>
                    <th>Ghi chú</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
<?php     
if (!empty($dt)) :
    foreach ($dt as $r):
        $trangThaiText = $r["TrangThai"];
        $statusClass = "status-pending";
        $statusLabel = "Chưa thanh toán";
        
        if ($trangThaiText === "Da thanh toan") {
            $statusClass = "status-paid";
            $statusLabel = "Đã thanh toán";
        } elseif ($trangThaiText === "Huy") {
            $statusClass = "status-cancelled";
            $statusLabel = "Đã hủy";
        }
        
        $ngayKe = date('d-m-Y', strtotime($r["NgayTao"]));
        
        $ghiChu = ($r["MoTa"] === null || trim($r["MoTa"]) === '') 
            ? '<span class="note-empty">Không có ghi chú</span>' 
            : htmlspecialchars($r["MoTa"]);
        
        echo '<tr>
            <td class="table-stt">'.$dem.'</td>
            <td class="table-code">'.$r["MaDT"].'</td>
            <td class="table-name">'.$r["HovaTen"].'</td>
            <td class="table-date">'.$ngayKe.'</td>
            <td class="table-status"><span class="status-badge '.$statusClass.'">'.$statusLabel.'</span></td>
            <td class="table-note">'.$ghiChu.'</td>
            <td class="table-action">
                <form action="NVNT/CTDT" method="POST" style="margin: 0;">
                    <input type="hidden" name="ctdt" value="'.$r["MaDT"].'">
                    <button type="submit" name="btnCTDT" class="btn-view">Chi tiết</button>
                </form>
            </td>
        </tr>';
        $dem++;
    endforeach;
else:
    echo '<tr><td colspan="7">
            <div class="no-data">
                <div class="no-data-icon">📭</div>
                <div class="no-data-text">Không có đơn thuốc nào phù hợp</div>
            </div>
          </td></tr>';
endif;
?>
            </tbody>
        </table>
    </div>

<?php
if ($pagination instanceof Pagination && !empty($dt)) {
    echo '<div class="pagination-area">
            <div class="pagination-box">';
    
    if ($pagination->hasPreviousPage()) {
        echo '<form action="" method="POST">
                <input type="hidden" name="page" value="'.($pagination->getCurrentPage() - 1).'">
                <input type="hidden" name="loc" value="'.$loc.'">
                <input type="hidden" name="keyword" value="'.htmlspecialchars($keyword).'">
                <button type="submit" name="btnPage" class="btn-page">◀</button>
            </form>';
    }

    $totalPages   = $pagination->getTotalPages();
    $currentPage  = $pagination->getCurrentPage();
    $range        = 2;

    for ($i = 1; $i <= $totalPages; $i++) {
        if ($i == 1 || $i == $totalPages || ($i >= $currentPage - $range && $i <= $currentPage + $range)) {
            if ($i == $currentPage) {
                echo '<span class="page-active">'.$i.'</span>';
            } else {
                echo '<form action="" method="POST">
                        <input type="hidden" name="page" value="'.$i.'">
                        <input type="hidden" name="loc" value="'.$loc.'">
                        <input type="hidden" name="keyword" value="'.htmlspecialchars($keyword).'">
                        <button type="submit" name="btnPage" class="btn-page">'.$i.'</button>
                    </form>';
            }
        } elseif ($i == $currentPage - $range - 1 || $i == $currentPage + $range + 1) {
            echo '<span class="page-dots">...</span>';
        }
    }

    if ($pagination->hasNextPage()) {
        echo '<form action="" method="POST">
                <input type="hidden" name="page" value="'.($pagination->getCurrentPage() + 1).'">
                <input type="hidden" name="loc" value="'.$loc.'">
                <input type="hidden" name="keyword" value="'.htmlspecialchars($keyword).'">
                <button type="submit" name="btnPage" class="btn-page">▶</button>
            </form>';
    }

    echo '</div></div>';
}
?>
</div>
