<?php
// Lấy dữ liệu thống kê do controller truyền sang
$thongKe = $data['ThongKe'] ?? [];
$filter  = $data['Filter'] ?? 'today';

$soCaLamViec = $thongKe['so_ca_lam_viec'] ?? 0;
$soLichKham  = $thongKe['so_lich_kham'] ?? 0;
$soPhieuKham = $thongKe['so_phieu_kham'] ?? 0;

$label       = $thongKe['label'] ?? '';
$startDate   = $thongKe['start_date'] ?? null;
$endDate     = $thongKe['end_date'] ?? null;

// Hàm format dd/mm/YYYY
function tkbs_format_date_vn($d) {
    if (!$d) return '';
    $ts = strtotime($d);
    return date('d/m/Y', $ts);
}
?>

<div class="tkbs-wrapper">
    <div class="tkbs-header">
        <div class="tkbs-header-left">
            <div class="tkbs-header-icon">
                🩺
            </div>
            <div>
                <h2 class="tkbs-title">Thống kê hoạt động khám</h2>
                <p class="tkbs-subtitle">
                    Tổng quan số ca làm việc, lịch khám và phiếu khám trong khoảng thời gian bạn chọn.
                </p>
            </div>
        </div>

        <div class="tkbs-filter">
            <form method="post" action="/KLTN_Benhvien/Bacsi/thongkebs">
                <div class="tkbs-filter-group">
                    <button type="submit" name="filter" value="today"
                        class="tkbs-filter-btn <?php echo $filter === 'today' ? 'active' : ''; ?>">
                        Hôm nay
                    </button>
                    <button type="submit" name="filter" value="7days"
                        class="tkbs-filter-btn <?php echo $filter === '7days' ? 'active' : ''; ?>">
                        7 ngày qua
                    </button>
                    <button type="submit" name="filter" value="month"
                        class="tkbs-filter-btn <?php echo $filter === 'month' ? 'active' : ''; ?>">
                        Tháng này
                    </button>
                    <button type="submit" name="filter" value="all"
                        class="tkbs-filter-btn <?php echo $filter === 'all' ? 'active' : ''; ?>">
                        Tất cả
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="tkbs-range">
        <?php if ($startDate && $endDate): ?>
            <span class="tkbs-range-label">
                Khoảng thời gian:
            </span>
            <span class="tkbs-range-value">
                <?php echo tkbs_format_date_vn($startDate) . ' - ' . tkbs_format_date_vn($endDate); ?>
            </span>
            <span class="tkbs-range-chip">
                <?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?>
            </span>
        <?php else: ?>
            <span class="tkbs-range-label">
                Khoảng thời gian:
            </span>
            <span class="tkbs-range-value">
                Tất cả dữ liệu từ trước đến nay
            </span>
            <span class="tkbs-range-chip">
                Tất cả
            </span>
        <?php endif; ?>
    </div>

    <div class="tkbs-cards row g-3">
        <!-- Số ca làm việc -->
        <div class="col-md-4 col-sm-6">
            <div class="tkbs-card tkbs-card-shift">
                <div class="tkbs-card-header">
                    <div class="tkbs-card-icon">
                        <i class="bi bi-calendar2-week"></i>
                    </div>
                    <div class="tkbs-card-title">Ca làm việc</div>
                </div>
                <div class="tkbs-card-body">
                    <div class="tkbs-card-value"><?php echo (int)$soCaLamViec; ?></div>
                    <div class="tkbs-card-desc">
                        Tổng số ca trực mà bạn đã đăng ký trong khoảng thời gian đã chọn.
                    </div>
                </div>
            </div>
        </div>

        <!-- Số lịch khám -->
        <div class="col-md-4 col-sm-6">
            <div class="tkbs-card tkbs-card-appointment">
                <div class="tkbs-card-header">
                    <div class="tkbs-card-icon">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="tkbs-card-title">Lịch khám</div>
                </div>
                <div class="tkbs-card-body">
                    <div class="tkbs-card-value"><?php echo (int)$soLichKham; ?></div>
                    <div class="tkbs-card-desc">
                        Số lịch hẹn bệnh nhân được phân công cho bạn.
                    </div>
                </div>
            </div>
        </div>

        <!-- Số phiếu khám -->
        <div class="col-md-4 col-sm-6">
            <div class="tkbs-card tkbs-card-pk">
                <div class="tkbs-card-header">
                    <div class="tkbs-card-icon">
                        <i class="bi bi-file-earmark-medical"></i>
                    </div>
                    <div class="tkbs-card-title">Phiếu khám</div>
                </div>
                <div class="tkbs-card-body">
                    <div class="tkbs-card-value"><?php echo (int)$soPhieuKham; ?></div>
                    <div class="tkbs-card-desc">
                        Số phiếu khám bệnh mà bạn đã lập cho bệnh nhân.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gợi ý nhỏ phía dưới -->
    <div class="tkbs-footer-note">
        <i class="bi bi-info-circle"></i>
        <span>
            Bạn có thể thay đổi bộ lọc thời gian ở phía trên để theo dõi xu hướng khối lượng công việc.
        </span>
    </div>
</div>

<style>
/* Prefix tkbs_ để tránh trùng CSS với chỗ khác */
.tkbs-wrapper {
    background: linear-gradient(135deg, #e0f2fe 0%, #eff6ff 45%, #ffffff 100%);
    border-radius: 18px;
    padding: 18px 18px 18px;
    border: 1px solid #e5e7eb;
}

/* HEADER */
.tkbs-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    margin-bottom: 14px;
    flex-wrap: wrap;
}

.tkbs-header-left {
    display: flex;
    align-items: center;
    gap: 12px;
}

.tkbs-header-icon {
    width: 42px;
    height: 42px;
    border-radius: 16px;
    background: linear-gradient(135deg, #2563eb, #0ea5e9);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    font-size: 24px;
    box-shadow: 0 8px 20px rgba(37, 99, 235, 0.45);
}

.tkbs-title {
    margin: 0;
    font-size: 20px;
    font-weight: 700;
    color: #0f172a;
}

.tkbs-subtitle {
    margin: 3px 0 0;
    font-size: 13px;
    color: #4b5563;
}

/* FILTER */
.tkbs-filter-group {
    display: inline-flex;
    padding: 3px;
    border-radius: 999px;
    background-color: rgba(15, 23, 42, 0.06);
    gap: 3px;
}

.tkbs-filter-btn {
    border: none;
    padding: 6px 12px;
    font-size: 13px;
    border-radius: 999px;
    background: transparent;
    cursor: pointer;
    color: #1f2937;
    transition: all 0.15s ease;
}

.tkbs-filter-btn:hover {
    background-color: rgba(255, 255, 255, 0.9);
}

.tkbs-filter-btn.active {
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    color: #ffffff;
    box-shadow: 0 0 0 1px rgba(37, 99, 235, 0.3);
}

/* RANGE TEXT */
.tkbs-range {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 6px;
    margin-bottom: 16px;
    font-size: 13px;
}

.tkbs-range-label {
    color: #6b7280;
}

.tkbs-range-value {
    font-weight: 600;
    color: #111827;
}

.tkbs-range-chip {
    padding: 2px 8px;
    font-size: 11px;
    border-radius: 999px;
    background-color: #e0f2fe;
    color: #0369a1;
    font-weight: 600;
}

/* CARD CHUNG */
.tkbs-card {
    border-radius: 16px;
    padding: 14px 16px;
    background-color: #ffffff;
    box-shadow: 0 10px 24px rgba(15, 23, 42, 0.14);
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    border: 1px solid rgba(148, 163, 184, 0.18);
}

.tkbs-card-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 6px;
}

.tkbs-card-icon {
    width: 36px;
    height: 36px;
    border-radius: 999px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}

/* Màu riêng cho từng loại card */
.tkbs-card-shift .tkbs-card-icon {
    background-color: #eff6ff;
    color: #1d4ed8;
}
.tkbs-card-appointment .tkbs-card-icon {
    background-color: #ecfdf5;
    color: #15803d;
}
.tkbs-card-pk .tkbs-card-icon {
    background-color: #fef3c7;
    color: #b45309;
}

.tkbs-card-title {
    font-size: 13px;
    font-weight: 600;
    color: #374151;
    text-transform: uppercase;
    letter-spacing: 0.03em;
}

.tkbs-card-value {
    font-size: 28px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 2px;
}

.tkbs-card-desc {
    font-size: 12px;
    color: #6b7280;
}

/* Footer note */
.tkbs-footer-note {
    margin-top: 16px;
    font-size: 12px;
    color: #4b5563;
    display: flex;
    align-items: center;
    gap: 6px;
}

.tkbs-footer-note i {
    font-size: 14px;
}

/* Responsive nhỏ */
@media (max-width: 768px) {
    .tkbs-wrapper {
        padding: 14px 12px;
        border-radius: 14px;
    }
    .tkbs-title {
        font-size: 18px;
    }
    .tkbs-card-value {
        font-size: 24px;
    }
}
</style>
