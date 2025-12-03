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
    }
</style>

<div class="bl-container">
    <div class="bl-title">
        <span>Danh sách đơn bán lẻ</span>
        <a href="/KLTN_Benhvien/NVNT/BanLeTao">
            <button type="button" class="bl-btn bl-btn-new">+ Tạo đơn bán lẻ mới</button>
        </a>
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
