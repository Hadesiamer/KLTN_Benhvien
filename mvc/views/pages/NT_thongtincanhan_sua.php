<?php
$nv = isset($data["ThongTin"]) ? $data["ThongTin"] : null;
?>

<div class="nt-profile-edit-container">
    <!-- CSS riêng cho màn hình chỉnh sửa -->
    <style>
        .nt-profile-edit-container {
            padding: 16px 20px;
        }

        .nt-edit-card {
            border-radius: 20px;
            border: none;
            box-shadow: 0 6px 18px rgba(0, 132, 116, 0.18);
            overflow: hidden;
        }

        .nt-edit-header {
            background: linear-gradient(135deg, #0c857d, #12b3a5);
            color: #ffffff;
            padding: 18px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .nt-edit-header-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .nt-edit-icon {
            width: 40px;
            height: 40px;
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.16);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .nt-edit-header-text h3 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
        }

        .nt-edit-header-text p {
            margin: 2px 0 0 0;
            font-size: 13px;
            opacity: 0.9;
        }

        .nt-edit-header-right a {
            border-radius: 999px;
            font-size: 13px;
            padding: 6px 14px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.45);
            color: #ffffff;
            text-decoration: none;
        }

        .nt-edit-header-right a:hover {
            background: rgba(255, 255, 255, 0.18);
        }

        .nt-edit-body {
            padding: 20px 24px 22px 24px;
            background: #f5fbfa;
        }

        .nt-section-title {
            font-size: 15px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 10px;
            margin-top: 6px;
            color: #055a54;
        }

        .nt-section-title span.icon {
            font-size: 18px;
        }

        .nt-section-note {
            font-size: 12px;
            color: #777;
            margin-bottom: 10px;
        }

        .nt-label {
            font-size: 12px;
            font-weight: 600;
            color: #555;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .nt-input-readonly {
            background-color: #e9ecef !important;
            border-radius: 10px;
            border: 1px solid #dde5e8;
            font-size: 14px;
        }

        .nt-input-control {
            border-radius: 10px;
            border: 1px solid #d3e9e6;
            font-size: 14px;
        }

        .nt-input-control:focus {
            border-color: #0c857d;
            box-shadow: 0 0 0 0.15rem rgba(12, 133, 125, 0.2);
        }

        select.nt-input-control {
            cursor: pointer;
        }

        .nt-required {
            color: red;
        }

        .nt-email-hint {
            font-size: 12px;
            color: #777;
            margin-top: 4px;
        }

        #emailError {
            font-size: 12px;
        }

        .nt-edit-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 8px;
        }

        .nt-edit-actions .btn-primary {
            border-radius: 999px;
            padding-inline: 18px;
            background: linear-gradient(135deg, #0c857d, #12b3a5);
            border: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .nt-edit-actions .btn-primary:hover {
            background: linear-gradient(135deg, #0a6d67, #0fa293);
        }

        .nt-edit-actions .btn-secondary {
            border-radius: 999px;
            padding-inline: 16px;
            font-weight: 500;
        }

        .nt-divider {
            border-top: 1px dashed #c6dfdb;
            margin: 12px 0 16px 0;
        }

        @media (max-width: 576px) {
            .nt-edit-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .nt-edit-header-right {
                width: 100%;
            }

            .nt-edit-header-right a {
                display: inline-block;
                margin-top: 6px;
            }
        }
    </style>

    <?php if (!empty($data["error"])): ?>
        <div class="alert alert-danger mb-3">
            <?php echo htmlspecialchars($data["error"]); ?>
        </div>
    <?php endif; ?>

    <?php if ($nv): ?>
        <div class="card nt-edit-card">
            <!-- HEADER -->
            <div class="nt-edit-header">
                <div class="nt-edit-header-left">
                    <div class="nt-edit-icon">✏️</div>
                    <div class="nt-edit-header-text">
                        <h3>Thay đổi thông tin cá nhân</h3>
                        <p>Điều chỉnh thông tin để đảm bảo liên hệ & phân ca trực chính xác.</p>
                    </div>
                </div>
                <div class="nt-edit-header-right">
                    <a href="/KLTN_Benhvien/NVNT/ThongTinCaNhan">
                        ⬅ Quay lại hồ sơ
                    </a>
                </div>
            </div>

            <!-- BODY -->
            <div class="nt-edit-body">
                <form method="POST">
                    <!-- THÔNG TIN HỆ THỐNG - CHỈ XEM -->
                    <div class="nt-section-title">
                        <span class="icon">🏥</span>
                        <span>Thông tin hệ thống</span>
                    </div>
                    <div class="nt-section-note">
                        Các thông tin dưới đây được quản lý bởi bộ phận nhân sự, nhân viên nhà thuốc không thể tự chỉnh sửa.
                    </div>

                    <div class="row g-3 mb-2">
                        <div class="col-md-6">
                            <label class="nt-label">Họ và tên</label>
                            <input type="text" class="form-control nt-input-readonly"
                                   value="<?php echo htmlspecialchars($nv["HovaTen"]); ?>"
                                   readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="nt-label">Số điện thoại</label>
                            <input type="text" class="form-control nt-input-readonly"
                                   value="<?php echo htmlspecialchars($nv["SoDT"]); ?>"
                                   readonly>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="nt-label">Chức vụ</label>
                            <input type="text" class="form-control nt-input-readonly"
                                   value="<?php echo htmlspecialchars($nv["ChucVu"]); ?>"
                                   readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="nt-label">Trạng thái làm việc</label>
                            <input type="text" class="form-control nt-input-readonly"
                                   value="<?php echo htmlspecialchars($nv["TrangThaiLamViec"]); ?>"
                                   readonly>
                        </div>
                    </div>

                    <div class="nt-divider"></div>

                    <!-- THÔNG TIN CÁ NHÂN - ĐƯỢC SỬA -->
                    <div class="nt-section-title">
                        <span class="icon">👤</span>
                        <span>Thông tin cá nhân</span>
                    </div>

                    <div class="row g-3 mb-2">
                        <div class="col-md-4">
                            <label for="NgaySinh" class="nt-label">Ngày sinh</label>
                            <input type="date" class="form-control nt-input-control"
                                   id="NgaySinh" name="NgaySinh"
                                   value="<?php echo htmlspecialchars($nv["NgaySinh"]); ?>">
                        </div>

                        <div class="col-md-4">
                            <label for="GioiTinh" class="nt-label">Giới tính</label>
                            <?php $gt = isset($nv["GioiTinh"]) ? $nv["GioiTinh"] : ""; ?>
                            <select class="form-select nt-input-control" id="GioiTinh" name="GioiTinh">
                                <option value="">-- Chọn giới tính --</option>
                                <option value="Nam"  <?php echo ($gt === "Nam")  ? "selected" : ""; ?>>Nam</option>
                                <option value="Nữ"   <?php echo ($gt === "Nữ")   ? "selected" : ""; ?>>Nữ</option>
                                <option value="Khác" <?php echo ($gt === "Khác") ? "selected" : ""; ?>>Khác</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="EmailNV" class="nt-label">
                                Email <span class="nt-required">*</span>
                            </label>
                            <input type="email"
                                   class="form-control nt-input-control"
                                   id="EmailNV"
                                   name="EmailNV"
                                   required
                                   oninput="checkEmail()"
                                   value="<?php echo htmlspecialchars($nv['EmailNV']); ?>">

                            <small id="emailError" style="color:red; display:none;">
                                Email không được để trống.
                            </small>
                            <div class="nt-email-hint">
                                📧 Vui lòng dùng email đang sử dụng thường xuyên để nhận thông báo ca trực & nhắc lịch.
                            </div>
                        </div>
                    </div>

                    <script>
                        // Validate email không để trống (giữ nguyên logic cũ, chỉ bọc lại)
                        function checkEmail() {
                            const emailInput = document.getElementById('EmailNV');
                            const errorText = document.getElementById('emailError');

                            if (emailInput.value.trim() === "") {
                                errorText.style.display = "block";
                            } else {
                                errorText.style.display = "none";
                            }
                        }
                    </script>

                    <div class="nt-edit-actions">
                        <button type="submit" class="btn btn-primary" name="btnLuuThongTin">
                            💾 Lưu thay đổi
                        </button>
                        <a href="/KLTN_Benhvien/NVNT/ThongTinCaNhan" class="btn btn-secondary">
                            Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-warning mt-2">
            Không có dữ liệu nhân viên để chỉnh sửa.
        </div>
    <?php endif; ?>
</div>
