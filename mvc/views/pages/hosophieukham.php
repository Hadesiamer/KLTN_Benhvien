<?php
// Giải mã JSON từ controller
$thongTinBN   = isset($data["ThongTinBN"])  ? json_decode($data["ThongTinBN"], true)  : [];
$phieuKhamBN  = isset($data["PhieuKhamBN"]) ? json_decode($data["PhieuKhamBN"], true) : [];

$bn = null;
if (!empty($thongTinBN)) {
    $bn = $thongTinBN[0];
}

function safe($arr, $key) {
    return isset($arr[$key]) ? htmlspecialchars($arr[$key]) : "";
}
?>

<div id="medical-records-container">
    <!-- Header -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-icon">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                </svg>
            </div>
            <div>
                <h1 class="page-title">Hồ Sơ Phiếu Khám</h1>
                <p class="page-subtitle">Quản lý và tra cứu lịch sử khám bệnh</p>
            </div>
        </div>
    </div>

    <?php if ($bn): ?>
        <!-- Thông tin bệnh nhân - Card nổi bật -->
        <div class="patient-info-card">
            <div class="card-header-custom">
                <div class="header-left">
                    <div class="avatar-circle">
                        <?= strtoupper(mb_substr(safe($bn, 'HovaTen'), 0, 1, 'UTF-8')); ?>
                    </div>
                    <div>
                        <h2 class="patient-name"><?= safe($bn, 'HovaTen'); ?></h2>
                        <div class="patient-meta">
                            <span class="meta-item">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                                <?= safe($bn, 'MaBN'); ?>
                            </span>
                            <span class="meta-item">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                                <?php
                                    if (!empty($bn['NgaySinh'])) {
                                        echo date('d/m/Y', strtotime($bn['NgaySinh']));
                                    }
                                ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="patient-details-grid">
                <div class="detail-item">
                    <span class="detail-label">Giới tính</span>
                    <span class="detail-value"><?= safe($bn, 'GioiTinh'); ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Số điện thoại</span>
                    <span class="detail-value"><?= safe($bn, 'SoDT') ?: '---'; ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">BHYT</span>
                    <span class="detail-value"><?= safe($bn, 'BHYT') ?: '---'; ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Email</span>
                    <span class="detail-value"><?= safe($bn, 'Email') ?: '---'; ?></span>
                </div>
                <div class="detail-item full-width">
                    <span class="detail-label">Địa chỉ</span>
                    <span class="detail-value"><?= safe($bn, 'DiaChi') ?: '---'; ?></span>
                </div>
            </div>
        </div>

        <!-- Danh sách phiếu khám -->
        <div class="medical-records-section">
            <div class="section-header">
                <h3 class="section-title">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 11l3 3L22 4"></path>
                        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                    </svg>
                    Lịch Sử Khám Bệnh
                </h3>
                <?php if (!empty($phieuKhamBN)): ?>
                    <span class="record-count"><?= count($phieuKhamBN); ?> phiếu khám</span>
                <?php endif; ?>
            </div>

            <?php if (!empty($phieuKhamBN)): ?>
                <!-- Timeline selector -->
                <div class="timeline-selector">
                    <label for="MaPK" class="selector-label">Chọn ngày khám để xem chi tiết:</label>
                    <select id="MaPK" name="MaPK" class="medical-select" onchange="showMedicalDetails(this.value)">
                        <option value="">-- Chọn phiếu khám --</option>
                        <?php
                        $seen = [];
                        foreach ($phieuKhamBN as $pk) {
                            $value = $pk['MaPK'];
                            $label = !empty($pk['NgayTao']) 
                                ? date('d/m/Y H:i', strtotime($pk['NgayTao'])) 
                                : 'Không rõ thời gian';

                            $keySeen = $value;
                            if (!in_array($keySeen, $seen)) {
                                $seen[] = $keySeen;
                                echo '<option value="'.htmlspecialchars($value).'">'.htmlspecialchars($label).'</option>';
                            }
                        }
                        ?>
                    </select>
                </div>

                <!-- Chi tiết phiếu khám -->
                <div id="MedicalDetails" class="medical-details-container">
                    <div class="empty-state">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M9 11l3 3L22 4"></path>
                            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                        </svg>
                        <p>Vui lòng chọn một phiếu khám để xem thông tin chi tiết</p>
                    </div>
                </div>
            <?php else: ?>
                <div class="empty-state-large">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                    </svg>
                    <h3>Chưa có phiếu khám</h3>
                    <p>Hiện tại bạn chưa có phiếu khám nào trong hệ thống</p>
                </div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="error-state">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            <h3>Không lấy được thông tin</h3>
            <p>Vui lòng đăng nhập lại để tiếp tục</p>
        </div>
    <?php endif; ?>
</div>

<style>
/* ===== RESET & BASE ===== */
#medical-records-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 15px;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
}

/* ===== PAGE HEADER ===== */
.page-header {
    margin-bottom: 20px;
}

.header-content {
    display: flex;
    align-items: center;
    gap: 12px;
}

.header-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    flex-shrink: 0;
}

.page-title {
    font-size: 24px;
    font-weight: 700;
    color: #1a202c;
    margin: 0 0 3px 0;
}

.page-subtitle {
    color: #718096;
    font-size: 13px;
    margin: 0;
}

/* ===== PATIENT INFO CARD ===== */
.patient-info-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
    margin-bottom: 18px;
    overflow: hidden;
    border: 1px solid #e2e8f0;
}

.card-header-custom {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 18px 20px;
    color: white;
}

.header-left {
    display: flex;
    align-items: center;
    gap: 14px;
}

.avatar-circle {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    font-weight: 700;
    border: 2px solid rgba(255, 255, 255, 0.3);
    flex-shrink: 0;
}

.patient-name {
    font-size: 20px;
    font-weight: 700;
    margin: 0 0 6px 0;
}

.patient-meta {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 13px;
    opacity: 0.95;
}

.meta-item svg {
    flex-shrink: 0;
}

/* ===== PATIENT DETAILS GRID ===== */
.patient-details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 14px;
    padding: 18px 20px;
}

.detail-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.detail-item.full-width {
    grid-column: 1 / -1;
}

.detail-label {
    font-size: 11px;
    font-weight: 600;
    color: #718096;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.detail-value {
    font-size: 14px;
    color: #2d3748;
    font-weight: 500;
}

/* ===== MEDICAL RECORDS SECTION ===== */
.medical-records-section {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
    padding: 18px 20px;
    border: 1px solid #e2e8f0;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
    padding-bottom: 12px;
    border-bottom: 2px solid #e2e8f0;
}

.section-title {
    font-size: 18px;
    font-weight: 700;
    color: #1a202c;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.section-title svg {
    color: #667eea;
}

.record-count {
    background: #edf2f7;
    color: #4a5568;
    padding: 4px 12px;
    border-radius: 16px;
    font-size: 12px;
    font-weight: 600;
}

/* ===== TIMELINE SELECTOR ===== */
.timeline-selector {
    margin-bottom: 18px;
}

.selector-label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: #4a5568;
    margin-bottom: 8px;
}

.medical-select {
    width: 100%;
    padding: 10px 14px;
    font-size: 14px;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    background: white;
    color: #2d3748;
    cursor: pointer;
    transition: all 0.2s;
}

.medical-select:hover {
    border-color: #cbd5e0;
}

.medical-select:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

/* ===== MEDICAL DETAILS ===== */
.medical-details-container {
    background: #f7fafc;
    border-radius: 10px;
    padding: 18px;
    min-height: 150px;
}

.details-section {
    margin-bottom: 20px;
}

.details-section:last-child {
    margin-bottom: 0;
}

.details-section-title {
    font-size: 15px;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 12px;
    padding-bottom: 8px;
    border-bottom: 2px solid #e2e8f0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.details-section-title svg {
    color: #667eea;
    flex-shrink: 0;
}

/* ===== COMPACT TABLE LAYOUT ===== */
.compact-table {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid #e2e8f0;
}

.table-row {
    display: grid;
    grid-template-columns: 140px 1fr;
    border-bottom: 1px solid #f1f5f9;
}

.table-row:last-child {
    border-bottom: none;
}

.table-label {
    padding: 10px 14px;
    background: #f8fafc;
    font-size: 12px;
    font-weight: 600;
    color: #475569;
    border-right: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
}

.table-value {
    padding: 10px 14px;
    font-size: 13px;
    color: #1e293b;
    display: flex;
    align-items: center;
}

/* ===== MEDICINE GRID ===== */
.medicine-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
}

.medicine-item {
    background: white;
    padding: 12px 14px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
}

.medicine-label {
    font-size: 11px;
    font-weight: 600;
    color: #64748b;
    margin-bottom: 4px;
    display: block;
}

.medicine-value {
    font-size: 13px;
    color: #1e293b;
    font-weight: 500;
}

/* ===== PRINT BUTTON ===== */
.print-section {
    margin-top: 18px;
    text-align: right;
}

.print-button {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 14px;
    border-radius: 999px;
    border: none;
    background: #3182ce;
    color: #fff;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    box-shadow: 0 2px 4px rgba(49, 130, 206, 0.4);
    transition: background 0.15s, transform 0.1s, box-shadow 0.1s;
}

.print-button:hover {
    background: #2b6cb0;
    transform: translateY(-1px);
    box-shadow: 0 3px 6px rgba(49, 130, 206, 0.5);
}

.print-button svg {
    flex-shrink: 0;
}

/* ===== EMPTY STATES ===== */
.empty-state {
    text-align: center;
    padding: 36px 20px;
    color: #a0aec0;
}

.empty-state svg {
    margin-bottom: 12px;
    opacity: 0.5;
}

.empty-state p {
    margin: 0;
    font-size: 14px;
}

.empty-state-large {
    text-align: center;
    padding: 48px 20px;
}

.empty-state-large svg {
    color: #cbd5e0;
    margin-bottom: 16px;
}

.empty-state-large h3 {
    font-size: 18px;
    color: #4a5568;
    margin: 0 0 6px 0;
}

.empty-state-large p {
    color: #a0aec0;
    font-size: 14px;
    margin: 0;
}

.error-state {
    text-align: center;
    padding: 48px 20px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
}

.error-state svg {
    color: #f56565;
    margin-bottom: 16px;
}

.error-state h3 {
    font-size: 18px;
    color: #2d3748;
    margin: 0 0 6px 0;
}

.error-state p {
    color: #718096;
    font-size: 14px;
    margin: 0;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    #medical-records-container {
        padding: 12px;
    }

    .page-title {
        font-size: 20px;
    }

    .header-icon {
        width: 42px;
        height: 42px;
    }

    .avatar-circle {
        width: 48px;
        height: 48px;
        font-size: 20px;
    }

    .patient-name {
        font-size: 18px;
    }

    .patient-details-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }

    .section-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }

    .table-row {
        grid-template-columns: 110px 1fr;
    }

    .medicine-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
const phieuKhamBenhNhan = <?php echo isset($data["PhieuKhamBN"]) ? $data["PhieuKhamBN"] : '[]'; ?>;

function escapeHtml(str) {
    if (str === null || str === undefined) return '';
    return String(str)
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

function cleanValue(value) {
    if (value === null || value === undefined) return "";
    const v = String(value).trim();
    if (v === "" || v === "0000-00-00" || v === "0000-00-00 00:00:00") {
        return "";
    }
    return v;
}

function cleanDate(value) {
    const v = cleanValue(value);
    if (v === "") return "";
    const d = new Date(v);
    if (Number.isNaN(d.getTime())) {
        return "";
    }
    return d.toLocaleDateString("vi-VN");
}

function showMedicalDetails(maPK) {
    const box = document.getElementById("MedicalDetails");
    if (!maPK) {
        box.innerHTML = `
            <div class="empty-state">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M9 11l3 3L22 4"></path>
                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                </svg>
                <p>Vui lòng chọn một phiếu khám để xem thông tin chi tiết</p>
            </div>
        `;
        return;
    }

    const id = parseInt(maPK, 10);
    const phieu = phieuKhamBenhNhan.find(x => parseInt(x.MaPK, 10) === id);

    if (!phieu) {
        box.innerHTML = `
            <div class="empty-state">
                <p>Không tìm thấy thông tin phiếu khám</p>
            </div>
        `;
        return;
    }

    const ngayTao = escapeHtml(cleanDate(phieu.NgayTao)) || '---';
    const trieuChung = escapeHtml(cleanValue(phieu.TrieuChung)) || '---';
    const ketQua = escapeHtml(cleanValue(phieu.KetQua)) || '---';
    const chuanDoan = escapeHtml(cleanValue(phieu.ChuanDoan)) || '---';
    const loiDan = escapeHtml(cleanValue(phieu.LoiDan)) || '---';
    const ngayTaiKham = escapeHtml(cleanDate(phieu.NgayTaikham)) || '---';
    const ngayKham = escapeHtml(cleanDate(phieu.NgayKham)) || '---';
    const gioKham = escapeHtml(cleanValue(phieu.GioKham)) || '';
    const tenKhoa = escapeHtml(cleanValue(phieu.TenKhoa)) || '---';
    const viTriKhoa = escapeHtml(cleanValue(phieu.ViTriKhoa)) || '---';
    const tenBacSi = escapeHtml(cleanValue(phieu.TenBacSi)) || '---';
    const tenThuoc = escapeHtml(cleanValue(phieu.TenThuoc)) || '---';
    const soLuong = escapeHtml(cleanValue(phieu.SoLuong)) || '---';
    const lieuDung = escapeHtml(cleanValue(phieu.LieuDung)) || '---';
    const cachDung = escapeHtml(cleanValue(phieu.CachDung)) || '---';

    const printUrl = "/KLTN_Benhvien/BN/InPhieuKham/" + id;

    box.innerHTML = `
        <div class="details-section">
            <h4 class="details-section-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                Thông Tin Khám
            </h4>
            <div class="compact-table">
                <div class="table-row">
                    <div class="table-label">Ngày lập phiếu</div>
                    <div class="table-value">${ngayTao}</div>
                </div>
                <div class="table-row">
                    <div class="table-label">Ngày & Giờ khám</div>
                    <div class="table-value">${ngayKham} ${gioKham}</div>
                </div>
                <div class="table-row">
                    <div class="table-label">Chuyên khoa</div>
                    <div class="table-value">${tenKhoa}</div>
                </div>
                <div class="table-row">
                    <div class="table-label">Vị trí khám</div>
                    <div class="table-value">${viTriKhoa}</div>
                </div>
                <div class="table-row">
                    <div class="table-label">Bác sĩ phụ trách</div>
                    <div class="table-value">${tenBacSi}</div>
                </div>
            </div>
        </div>

        <div class="details-section">
            <h4 class="details-section-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 12h-4l-3 9L9 3l-3 9H2"></path>
                </svg>
                Kết Quả Khám
            </h4>
            <div class="compact-table">
                <div class="table-row">
                    <div class="table-label">Triệu chứng</div>
                    <div class="table-value">${trieuChung}</div>
                </div>
                <div class="table-row">
                    <div class="table-label">Kết quả khám</div>
                    <div class="table-value">${ketQua}</div>
                </div>
                <div class="table-row">
                    <div class="table-label">Chuẩn đoán</div>
                    <div class="table-value">${chuanDoan}</div>
                </div>
                <div class="table-row">
                    <div class="table-label">Lời dặn</div>
                    <div class="table-value">${loiDan}</div>
                </div>
                <div class="table-row">
                    <div class="table-label">Ngày tái khám</div>
                    <div class="table-value">${ngayTaiKham}</div>
                </div>
            </div>
        </div>

        <div class="details-section">
            <h4 class="details-section-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                    <line x1="8" y1="21" x2="16" y2="21"></line>
                    <line x1="12" y1="17" x2="12" y2="21"></line>
                </svg>
                Đơn Thuốc
            </h4>
            <div class="medicine-grid">
                <div class="medicine-item">
                    <span class="medicine-label">Tên thuốc</span>
                    <span class="medicine-value">${tenThuoc}</span>
                </div>
                <div class="medicine-item">
                    <span class="medicine-label">Số lượng</span>
                    <span class="medicine-value">${soLuong}</span>
                </div>
                <div class="medicine-item">
                    <span class="medicine-label">Liều dùng</span>
                    <span class="medicine-value">${lieuDung}</span>
                </div>
                <div class="medicine-item">
                    <span class="medicine-label">Cách dùng</span>
                    <span class="medicine-value">${cachDung}</span>
                </div>
            </div>
        </div>

        <div class="print-section">
            <a href="${printUrl}" target="_blank" class="print-button">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="6 9 12 15 18 9"></polyline>
                    <line x1="12" y1="3" x2="12" y2="15"></line>
                    <path d="M5 19h14"></path>
                </svg>
                In phiếu khám
            </a>
        </div>
    `;
}
</script>
