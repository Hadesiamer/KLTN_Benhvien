<?php 
$nv = isset($data["ThongTin"]) ? $data["ThongTin"] : null;
?>

<div class="nt-profile-container">
    <!-- CSS riêng cho giao diện nhân viên nhà thuốc -->
    <style>
        .nt-profile-container {
            padding: 16px 20px;
        }

        .nt-profile-card {
            border-radius: 20px;
            border: none;
            box-shadow: 0 6px 18px rgba(0, 132, 116, 0.18);
            overflow: hidden;
        }

        .nt-profile-header {
            background: linear-gradient(135deg, #0c857d, #12b3a5);
            color: #ffffff;
            padding: 18px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .nt-profile-user {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .nt-avatar {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: #ffffff;
            color: #0c857d;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 22px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        .nt-avatar span {
            transform: translateY(1px);
        }

        .nt-header-text h3 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
        }

        .nt-header-text p {
            margin: 2px 0 0 0;
            font-size: 13px;
            opacity: 0.9;
        }

        .nt-badge-role {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 10px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.12);
            font-size: 12px;
            font-weight: 500;
        }

        .nt-badge-role span:first-child {
            font-size: 16px;
        }

        .nt-status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 999px;
            padding: 4px 10px;
            font-size: 11px;
            font-weight: 600;
            background: rgba(255, 255, 255, 0.14);
        }

        .nt-status-dot {
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: #47f3c2;
            box-shadow: 0 0 0 3px rgba(71, 243, 194, 0.4);
        }

        .nt-profile-actions a {
            border-radius: 999px;
            font-size: 14px;
            font-weight: 600;
            padding-inline: 18px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: none;
            background: #ffffff;
            color: #0c857d;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.18);
        }

        .nt-profile-actions a:hover {
            background: #f4fffd;
            color: #055a54;
        }

        .nt-profile-body {
            padding: 20px 24px 22px 24px;
            background: #f5fbfa;
        }

        .nt-section-title {
            font-size: 15px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
            color: #055a54;
        }

        .nt-section-title span.icon {
            font-size: 18px;
        }

        .nt-field-label {
            font-size: 12px;
            font-weight: 600;
            color: #555;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .nt-field-value {
            font-size: 14px;
            padding: 9px 11px;
            border-radius: 10px;
            background: #ffffff;
            border: 1px solid #e1f3f1;
        }

        .nt-field-value.readonly {
            background: #ffffff;
        }

        .nt-meta-row {
            margin-top: 6px;
            font-size: 12px;
            color: #777;
        }

        /* Toast sửa lại nhẹ theo tone nhà thuốc */
        #toastProfile.nt-toast {
            border-radius: 999px;
            background: #e1fff6;
            border: 1px solid #0c857d;
            color: #055a54;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        #toastProfile.nt-toast::before {
            content: "✅";
        }

        /* Responsive */
        @media (max-width: 576px) {
            .nt-profile-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .nt-profile-actions {
                width: 100%;
            }

            .nt-profile-actions a {
                width: 100%;
                justify-content: center;
            }
        }
    </style>

    <?php if (!empty($data["error"])): ?>
        <div class="alert alert-danger mb-3">
            <?php echo htmlspecialchars($data["error"]); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($data["toast"])): ?>
        <div id="toastProfile" class="alert alert-success nt-toast position-fixed top-0 end-0 m-3" role="alert"
             style="z-index: 1050; min-width: 260px;">
            <?php echo htmlspecialchars($data["toast"]); ?>
        </div>
        <script>
            setTimeout(function () {
                var t = document.getElementById('toastProfile');
                if (t) t.style.display = 'none';
            }, 4000);
        </script>
    <?php endif; ?>

    <?php if ($nv): ?>
        <div class="card nt-profile-card">
            <!-- HEADER -->
            <div class="nt-profile-header">
                <div class="nt-profile-user">
                    <div class="nt-avatar">
                        <span>
                            <?php
                                // Lấy ký tự đầu của họ tên để hiển thị avatar
                                $name = isset($nv["HovaTen"]) ? trim($nv["HovaTen"]) : "";
                                $initials = "";
                                if ($name !== "") {
                                    $parts = explode(" ", $name);
                                    $last = strtoupper(mb_substr(end($parts), 0, 1, 'UTF-8'));
                                    $first = strtoupper(mb_substr($parts[0], 0, 1, 'UTF-8'));
                                    $initials = $first . $last;
                                }
                                echo htmlspecialchars($initials);
                            ?>
                        </span>
                    </div>
                    <div class="nt-header-text">
                        <h3><?php echo htmlspecialchars($nv["HovaTen"]); ?></h3>
                        <p>
                            <span class="nt-badge-role">
                                <span>💊</span>
                                <span>Nhân viên nhà thuốc</span>
                            </span>
                        </p>
                    </div>
                </div>

                <div class="text-end">
                    <div class="nt-status-badge mb-2">
                        <span class="nt-status-dot"></span>
                        <span><?php echo htmlspecialchars($nv["TrangThaiLamViec"]); ?></span>
                    </div>
                    <div class="nt-profile-actions">
                        <a href="/KLTN_Benhvien/NVNT/ThongTinCaNhanSua" class="btn btn-light">
                            ✏️ Cập nhật hồ sơ
                        </a>
                    </div>
                </div>
            </div>

            <!-- BODY -->
            <div class="nt-profile-body">
                <div class="nt-section-title">
                    <span class="icon">📋</span>
                    <span>Thông tin nhân sự</span>
                </div>

                <div class="row g-3 mb-2">
                    <div class="col-md-6">
                        <div class="nt-field-label">Họ và tên</div>
                        <div class="nt-field-value readonly">
                            <?php echo htmlspecialchars($nv["HovaTen"]); ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="nt-field-label">Chức vụ</div>
                        <div class="nt-field-value readonly">
                            <?php echo htmlspecialchars($nv["ChucVu"]); ?>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="nt-field-label">Số điện thoại</div>
                        <div class="nt-field-value readonly">
                            <?php echo htmlspecialchars($nv["SoDT"]); ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="nt-field-label">Email</div>
                        <div class="nt-field-value readonly">
                            <?php echo htmlspecialchars($nv["EmailNV"]); ?>
                        </div>
                    </div>
                </div>

                <div class="nt-section-title">
                    <span class="icon">👤</span>
                    <span>Thông tin cá nhân</span>
                </div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="nt-field-label">Ngày sinh</div>
                        <div class="nt-field-value readonly">
                            <?php echo htmlspecialchars($nv["NgaySinh"]); ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="nt-field-label">Giới tính</div>
                        <div class="nt-field-value readonly">
                            <?php echo htmlspecialchars($nv["GioiTinh"]); ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="nt-field-label">Trạng thái làm việc</div>
                        <div class="nt-field-value readonly">
                            <?php echo htmlspecialchars($nv["TrangThaiLamViec"]); ?>
                        </div>
                    </div>
                </div>

                <div class="nt-meta-row">
                    <span>💡 Mẹo: Cập nhật email & thông tin cá nhân giúp bộ phận nhân sự liên lạc khi có ca trực gấp.</span>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-warning mt-2">
            Không có dữ liệu nhân viên để hiển thị.
        </div>
    <?php endif; ?>
</div>
