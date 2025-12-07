<?php
// Page: Thông tin bác sĩ đang đăng nhập
// Dữ liệu được truyền từ controller Bacsi::ThongTinBacSi()
// $data["thongtinbs"] là JSON do MBacsi::get1BS($maNV) trả về

$bacsi = null;

if (isset($data["thongtinbs"])) {
    $decoded = json_decode($data["thongtinbs"], true);
    if (is_array($decoded) && count($decoded) > 0) {
        $bacsi = $decoded[0];
    }
}
?>

<h2 class="mb-3 bs-title-page">Thông tin bác sĩ</h2>

<?php if ($bacsi): ?>
    <div class="bs-profile-wrapper">
        <div class="bs-profile-card">
            <!-- Cột avatar + tên bác sĩ -->
            <div class="bs-profile-left">
                <div class="bs-avatar-wrapper">
                    <img src="/KLTN_Benhvien/public/img/<?= htmlspecialchars($bacsi['HinhAnh'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                         alt="Ảnh bác sĩ">
                </div>
                <div class="bs-name-block">
                    <div class="bs-name">
                        <?= htmlspecialchars($bacsi['HovaTen'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                    </div>
                    <div class="bs-specialty">
                        <?= htmlspecialchars($bacsi['TenKhoa'] ?? 'Chuyên khoa: đang cập nhật', ENT_QUOTES, 'UTF-8') ?>
                    </div>

                    <div class="bs-status-chip">
                        <span class="dot"></span>
                        <?= htmlspecialchars($bacsi['TrangThaiLamViec'] ?? 'Đang cập nhật', ENT_QUOTES, 'UTF-8') ?>
                    </div>
                </div>
            </div>

            <!-- Cột thông tin chi tiết -->
            <div class="bs-profile-right">
                <div class="bs-info-section">
                    <div class="bs-section-title">
                        Thông tin cá nhân
                    </div>
                    <div class="bs-info-grid">
                        <div class="bs-info-item">
                            <span class="label">Mã bác sĩ</span>
                            <span class="value"><?= htmlspecialchars($bacsi['MaNV'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
                        </div>
                        <div class="bs-info-item">
                            <span class="label">Họ tên</span>
                            <span class="value"><?= htmlspecialchars($bacsi['HovaTen'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
                        </div>
                        <div class="bs-info-item">
                            <span class="label">Giới tính</span>
                            <span class="value"><?= htmlspecialchars($bacsi['GioiTinh'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
                        </div>
                        <div class="bs-info-item">
                            <span class="label">Ngày sinh</span>
                            <span class="value">
                                <?= !empty($bacsi['NgaySinh'])
                                    ? date("d-m-Y", strtotime($bacsi['NgaySinh']))
                                    : "Đang cập nhật" ?>
                            </span>
                        </div>
                        <div class="bs-info-item">
                            <span class="label">Số điện thoại</span>
                            <span class="value"><?= htmlspecialchars($bacsi['SoDT'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
                        </div>
                        <div class="bs-info-item">
                            <span class="label">Email</span>
                            <span class="value"><?= htmlspecialchars($bacsi['EmailNV'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
                        </div>
                    </div>
                </div>

                <div class="bs-info-section">
                    <div class="bs-section-title">
                        Thông tin chuyên môn
                    </div>
                    <div class="bs-info-grid">
                        <div class="bs-info-item">
                            <span class="label">Chức vụ</span>
                            <span class="value"><?= htmlspecialchars($bacsi['ChucVu'] ?? 'Bác sĩ', ENT_QUOTES, 'UTF-8') ?></span>
                        </div>
                        <div class="bs-info-item">
                            <span class="label">Chuyên khoa</span>
                            <span class="value"><?= htmlspecialchars($bacsi['TenKhoa'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
                        </div>
                        <div class="bs-info-item">
                            <span class="label">Trạng thái làm việc</span>
                            <span class="value"><?= htmlspecialchars($bacsi['TrangThaiLamViec'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
                        </div>
                    </div>
                </div>

                <div class="bs-actions">
                    <a href="/KLTN_Benhvien/Bacsi/SuaThongTinBacSi" class="btn btn-primary">
                        Chỉnh sửa thông tin
                    </a>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <p class="bs-no-data">Không tìm thấy thông tin bác sĩ. Vui lòng kiểm tra lại tài khoản đăng nhập.</p>
<?php endif; ?>

<style>
    .bs-title-page {
        font-size: 22px;
        font-weight: 700;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .bs-profile-wrapper {
        max-width: 980px;
        margin: 0 auto 32px;
        padding: 0 8px;
    }

    .bs-profile-card {
        display: flex;
        flex-wrap: wrap;
        gap: 18px;
        background: linear-gradient(135deg, #e0f2fe 0%, #eff6ff 35%, #ffffff 100%);
        border-radius: 18px;
        padding: 18px 18px 16px;
        box-shadow: 0 10px 26px rgba(15, 23, 42, 0.16);
        border: 1px solid rgba(148, 163, 184, 0.35);
    }

    .bs-profile-left {
        flex: 0 0 260px;
        max-width: 260px;
        border-right: 1px solid #dbeafe;
        padding-right: 16px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
    }

    .bs-avatar-wrapper img {
        width: 128px;
        height: 128px;
        object-fit: cover;
        border-radius: 999px;
        border: 3px solid #dbeafe;
        box-shadow: 0 6px 16px rgba(37, 99, 235, 0.45);
        transition: transform 0.25s ease;
        background: #ffffff;
    }

    .bs-avatar-wrapper img:hover {
        transform: scale(1.04);
    }

    .bs-name-block {
        text-align: center;
    }

    .bs-name {
        font-size: 18px;
        font-weight: 700;
        color: #0f172a;
    }

    .bs-specialty {
        font-size: 13px;
        color: #1d4ed8;
        margin-top: 2px;
    }

    .bs-status-chip {
        margin-top: 6px;
        padding: 3px 10px;
        border-radius: 999px;
        background: #ecfdf5;
        color: #166534;
        font-size: 12px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .bs-status-chip .dot {
        width: 8px;
        height: 8px;
        border-radius: 999px;
        background: #22c55e;
    }

    .bs-profile-right {
        flex: 1;
        min-width: 0;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .bs-info-section {
        background: #ffffff;
        border-radius: 12px;
        padding: 12px 12px 10px;
        box-shadow: 0 6px 16px rgba(15, 23, 42, 0.08);
        border: 1px solid rgba(209, 213, 219, 0.7);
    }

    .bs-section-title {
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #6b7280;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .bs-info-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 6px 14px;
    }

    .bs-info-item {
        display: flex;
        flex-direction: column;
        font-size: 13px;
    }

    .bs-info-item .label {
        color: #6b7280;
        margin-bottom: 2px;
    }

    .bs-info-item .value {
        color: #111827;
        font-weight: 500;
        word-break: break-word;
    }

    .bs-actions {
        margin-top: 6px;
        text-align: right;
    }

    .bs-no-data {
        color: #b91c1c;
        font-style: italic;
        font-size: 14px;
    }

    @media (max-width: 768px) {
        .bs-profile-card {
            flex-direction: column;
            padding: 14px 12px;
            border-radius: 14px;
        }

        .bs-profile-left {
            flex: 1 1 auto;
            max-width: 100%;
            border-right: none;
            border-bottom: 1px solid #dbeafe;
            padding-right: 0;
            padding-bottom: 10px;
        }

        .bs-info-grid {
            grid-template-columns: 1fr;
        }

        .bs-actions {
            text-align: left;
        }
    }
</style>
