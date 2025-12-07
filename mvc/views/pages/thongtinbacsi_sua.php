<?php
$bs = null;
if (isset($data["ThongTin"])) {
    $decoded = json_decode($data["ThongTin"], true);
    if (is_array($decoded) && count($decoded) > 0) {
        $bs = $decoded[0];
    }
}
?>

<h2 class="mb-3 bs-title-page">Chỉnh sửa thông tin bác sĩ</h2>

<div class="bs-edit-wrapper">

    <?php if (!empty($data["error"])): ?>
        <div class="alert alert-danger mb-3">
            <?= htmlspecialchars($data["error"]); ?>
        </div>
    <?php endif; ?>

    <?php if ($bs): ?>
        <div class="bs-edit-card">
            <!-- Cột tóm tắt nhanh -->
            <div class="bs-edit-summary">
                <div class="bs-edit-avatar">
                    <span>🩺</span>
                </div>
                <div class="bs-edit-summary-text">
                    <div class="name">
                        <?= htmlspecialchars($bs["HovaTen"], ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                    <div class="sub">
                        <?= htmlspecialchars($bs["TenKhoa"], ENT_QUOTES, 'UTF-8'); ?> ·
                        <?= htmlspecialchars($bs["ChucVu"], ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                    <div class="meta">
                        Số điện thoại: <?= htmlspecialchars($bs["SoDT"], ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                </div>
                <div class="bs-edit-note">
                    Thông tin cơ bản phía trái chỉ mang tính hiển thị và không thay đổi được tại đây.
                </div>
            </div>

            <!-- Cột form chỉnh sửa -->
            <div class="bs-edit-form-col">
                <form method="POST" class="bs-edit-form">

                    <!-- THÔNG TIN KHÔNG CHO SỬA -->
                    <div class="bs-edit-section readonly">
                        <div class="section-title">Thông tin cố định</div>

                        <div class="mb-2">
                            <label class="form-label">Họ và tên</label>
                            <input class="form-control" readonly
                                   style="background:#e5e7eb"
                                   value="<?= htmlspecialchars($bs["HovaTen"], ENT_QUOTES, 'UTF-8'); ?>">
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Số điện thoại</label>
                            <input class="form-control" readonly
                                   style="background:#e5e7eb"
                                   value="<?= htmlspecialchars($bs["SoDT"], ENT_QUOTES, 'UTF-8'); ?>">
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Chuyên khoa</label>
                            <input class="form-control" readonly
                                   style="background:#e5e7eb"
                                   value="<?= htmlspecialchars($bs["TenKhoa"], ENT_QUOTES, 'UTF-8'); ?>">
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Chức vụ</label>
                            <input class="form-control" readonly
                                   style="background:#e5e7eb"
                                   value="<?= htmlspecialchars($bs["ChucVu"], ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>

                    <!-- TRƯỜNG ĐƯỢC SỬA -->
                    <div class="bs-edit-section editable">
                        <div class="section-title">Thông tin có thể chỉnh sửa</div>

                        <div class="row g-2">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label class="form-label">Ngày sinh</label>
                                    <input type="date" class="form-control"
                                           name="NgaySinh"
                                           value="<?= htmlspecialchars($bs["NgaySinh"], ENT_QUOTES, 'UTF-8'); ?>">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label class="form-label">Giới tính</label>
                                    <?php $gt = $bs["GioiTinh"] ?? ""; ?>
                                    <select name="GioiTinh" class="form-select">
                                        <option value="">-- Chọn giới tính --</option>
                                        <option value="Nam"  <?= ($gt == "Nam" ? "selected" : "") ?>>Nam</option>
                                        <option value="Nữ"   <?= ($gt == "Nữ" ? "selected" : "") ?>>Nữ</option>
                                        <option value="Khác" <?= ($gt == "Khác" ? "selected" : "") ?>>Khác</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Email <span style="color:red">*</span></label>
                            <input type="email" class="form-control"
                                   name="EmailNV"
                                   required
                                   id="EmailNV"
                                   oninput="checkEmail()"
                                   value="<?= htmlspecialchars($bs["EmailNV"], ENT_QUOTES, 'UTF-8'); ?>">

                            <small id="emailError" style="color:red; display:none;">
                                Email không được bỏ trống
                            </small>
                        </div>
                    </div>

                    <div class="bs-edit-actions">
                        <button class="btn btn-primary">Lưu thay đổi</button>
                        <a href="/KLTN_Benhvien/Bacsi/ThongTinBacSi"
                           class="btn btn-secondary">
                            Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    <?php else: ?>
        <p class="text-danger">Không tìm thấy dữ liệu bác sĩ.</p>
    <?php endif; ?>
</div>

<script>
function checkEmail() {
    const email = document.getElementById("EmailNV");
    const er = document.getElementById("emailError");
    if (!email) return;
    if (email.value.trim() === "") er.style.display = "block";
    else er.style.display = "none";
}
</script>

<style>
    .bs-title-page {
        font-size: 22px;
        font-weight: 700;
        color: #0f172a;
    }

    .bs-edit-wrapper {
        max-width: 980px;
        margin: 0 auto 32px;
        padding: 0 8px;
    }

    .bs-edit-card {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        background: #ffffff;
        border-radius: 18px;
        box-shadow: 0 10px 26px rgba(15, 23, 42, 0.16);
        border: 1px solid rgba(148, 163, 184, 0.32);
        padding: 14px 16px 18px;
    }

    .bs-edit-summary {
        flex: 0 0 260px;
        max-width: 260px;
        border-right: 1px solid #e5e7eb;
        padding-right: 14px;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .bs-edit-avatar {
        width: 56px;
        height: 56px;
        border-radius: 999px;
        background: linear-gradient(135deg, #2563eb, #0ea5e9);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-size: 26px;
        box-shadow: 0 8px 20px rgba(37, 99, 235, 0.55);
        margin-bottom: 4px;
    }

    .bs-edit-summary-text .name {
        font-size: 16px;
        font-weight: 700;
        color: #0f172a;
    }

    .bs-edit-summary-text .sub {
        font-size: 13px;
        color: #1d4ed8;
        margin-top: 2px;
    }

    .bs-edit-summary-text .meta {
        font-size: 12px;
        color: #6b7280;
        margin-top: 4px;
    }

    .bs-edit-note {
        font-size: 12px;
        color: #4b5563;
        margin-top: 6px;
        padding: 6px 8px;
        border-radius: 8px;
        background: #eff6ff;
        border: 1px dashed #93c5fd;
    }

    .bs-edit-form-col {
        flex: 1;
        min-width: 0;
    }

    .bs-edit-form .form-label {
        font-weight: 600;
        color: #004a99;
        font-size: 13px;
    }

    .bs-edit-section {
        border-radius: 12px;
        padding: 10px 12px;
        margin-bottom: 10px;
    }

    .bs-edit-section.readonly {
        background: #f3f4f6;
    }

    .bs-edit-section.editable {
        background: #f9fafb;
    }

    .bs-edit-section .section-title {
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #6b7280;
        font-weight: 600;
        margin-bottom: 6px;
    }

    .bs-edit-actions {
        display: flex;
        gap: 8px;
        justify-content: flex-end;
        margin-top: 4px;
    }

    @media (max-width: 768px) {
        .bs-edit-card {
            flex-direction: column;
            padding: 12px 12px 14px;
            border-radius: 14px;
        }

        .bs-edit-summary {
            max-width: 100%;
            border-right: none;
            border-bottom: 1px solid #e5e7eb;
            padding-right: 0;
            padding-bottom: 10px;
        }

        .bs-edit-actions {
            justify-content: flex-start;
        }
    }
</style>
