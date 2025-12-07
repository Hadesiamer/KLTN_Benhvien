<?php
// Đây là file C:\xampp\htdocs\KLTN_Benhvien\mvc\views\pages\bs_danhsach_chat.php
$dsCuoc = isset($data["DanhSachCuocTroChuyen"]) ? $data["DanhSachCuocTroChuyen"] : [];
?>

<div class="bs-chatlist-wrapper">
    <style>
        /* ==== UI danh sách cuộc trò chuyện bác sĩ - bệnh nhân ==== */
        .bs-chatlist-wrapper {
            margin-top: 8px;
        }

        .bs-chatlist-card {
            background: #ffffff;
            border-radius: 18px;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.16);
            padding: 14px 14px 16px;
            border: 1px solid rgba(148, 163, 184, 0.25);
        }

        .bs-chatlist-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 8px;
            margin-bottom: 10px;
        }

        .bs-chatlist-header-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .bs-chatlist-icon {
            width: 40px;
            height: 40px;
            border-radius: 999px;
            background: linear-gradient(135deg, #22c55e, #16a34a);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 20px;
            flex-shrink: 0;
            box-shadow: 0 6px 16px rgba(22, 163, 74, 0.55);
        }

        .bs-chatlist-header-text h3 {
            margin: 0;
            font-size: 17px;
            font-weight: 700;
            color: #0f172a;
        }

        .bs-chatlist-header-text p {
            margin: 3px 0 0;
            font-size: 13px;
            color: #6b7280;
        }

        .bs-chatlist-header-right {
            font-size: 12px;
            color: #6b7280;
            text-align: right;
        }

        .bs-chatlist-table-wrapper {
            margin-top: 6px;
        }

        .bs-chatlist-note {
            margin-top: 8px;
            font-size: 12px;
            color: #4b5563;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .bs-row-new {
            background-color: #fef2f2;
        }

        .bs-row-new:hover {
            background-color: #fee2e2;
        }

        @media (max-width: 768px) {
            .bs-chatlist-card {
                padding: 10px 10px 12px;
                border-radius: 14px;
            }

            .bs-chatlist-header-left {
                align-items: flex-start;
            }

            .bs-chatlist-header-right {
                text-align: left;
            }

            .bs-chatlist-wrapper .table {
                font-size: 12px;
            }
        }
    </style>

    <div class="bs-chatlist-card">
        <div class="bs-chatlist-header">
            <div class="bs-chatlist-header-left">
                <div class="bs-chatlist-icon">📥</div>
                <div class="bs-chatlist-header-text">
                    <h3>Hộp thư bệnh nhân</h3>
                    <p>
                        Danh sách các cuộc trò chuyện từ bệnh nhân khám online đã thanh toán.
                    </p>
                </div>
            </div>
            <div class="bs-chatlist-header-right">
                <div>🩺 Bác sĩ xem và trả lời từng cuộc trò chuyện.</div>
            </div>
        </div>

        <?php if (empty($dsCuoc)): ?>
            <div class="alert alert-info mt-2 mb-0">
                Hiện tại bạn chưa có cuộc trò chuyện nào.
            </div>
        <?php else: ?>
            <div class="bs-chatlist-table-wrapper table-responsive">
                <table class="table table-bordered align-middle mb-1">
                    <thead class="table-light">
                    <tr>
                        <th style="min-width: 80px;">Mã cuộc trò chuyện</th>
                        <th style="min-width: 70px;">Mã BN</th>
                        <th style="min-width: 160px;">Họ tên BN</th>
                        <th style="min-width: 120px;">Số điện thoại</th>
                        <th style="min-width: 80px;">BHYT</th>
                        <th style="min-width: 150px;">Lần cập nhật cuối</th>
                        <th style="min-width: 90px;">Trạng thái</th>
                        <th style="min-width: 90px;">Thao tác</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($dsCuoc as $row): ?>
                        <?php
                        $hasNew = !empty($row["TrangThaiChoBS"]);
                        $rowClass = $hasNew ? 'bs-row-new' : '';
                        ?>
                        <tr class="<?php echo $rowClass; ?>">
                            <td><?php echo htmlspecialchars($row["MaCuocTrove"]); ?></td>
                            <td><?php echo htmlspecialchars($row["MaBN"]); ?></td>
                            <td><?php echo htmlspecialchars($row["TenBenhNhan"]); ?></td>
                            <td><?php echo htmlspecialchars($row["SoDT"]); ?></td>
                            <td><?php echo htmlspecialchars($row["BHYT"]); ?></td>
                            <td><?php echo htmlspecialchars($row["ThoiGianCapNhat"]); ?></td>
                            <td>
                                <?php if ($hasNew): ?>
                                    <span class="badge bg-danger">Có tin mới</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Đã đọc</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a class="btn btn-primary btn-sm"
                                   href="/KLTN_Benhvien/Bacsi/ChatVoiBenhNhan/<?php echo urlencode($row["MaCuocTrove"]); ?>">
                                    Mở chat
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="bs-chatlist-note">
                <i class="bi bi-info-circle"></i>
                <span>
                    Hàng được tô hồng là cuộc trò chuyện đang có tin nhắn mới từ bệnh nhân.
                </span>
            </div>
        <?php endif; ?>
    </div>
</div>
