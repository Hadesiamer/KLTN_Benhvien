<?php
    // Kiểm tra quyền truy cập: chỉ cho phép role = 1 (quản lý)
    if($_SESSION["role"] != 1){
        echo "<script>alert('Bạn không có quyền truy cập')</script>";
        header("refresh: 0; url='/KLTN_Benhvien'");
    }
?>

<?php
// File: layoutQL.php

// Sau khi đã start session ở index chính:
require_once "./mvc/controllers/QuanLy.php";
$quanLy = new QuanLy();
// Lấy các số liệu tổng quan cho dashboard
$counts = $quanLy->GetDashboardCounts();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Quản Lý Bệnh Viện</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- CSS chung (nếu có) -->
    <link rel="stylesheet" href="./public/css/main.css">
    <link rel="stylesheet" href="../public/css/main.css">

    <style>
        /* ================== UI DASHBOARD SÁNG – HIỆN ĐẠI ================== */
        body {
            background-color: #edf2fb;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: #0f172a;
        }

        .main {
            padding-top: 10px;
            padding-bottom: 40px;
        }

        .dashboard-wrapper {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Header khu vực trên cùng */
        .dash-header {
            background: linear-gradient(135deg, #e0f2fe, #dbeafe);
            border-radius: 18px;
            padding: 20px 22px;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
            border: 1px solid #bfdbfe;
        }

        .dash-title {
            font-size: 1.7rem;
            font-weight: 700;
            color: #1d4ed8;
            margin-bottom: 5px;
        }

        .dash-subtitle {
            margin: 0;
            color: #475569;
            font-size: 0.95rem;
        }

        .badge-role {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.35rem 0.85rem;
            border-radius: 999px;
            background-color: #1d4ed8;
            color: #eff6ff;
            font-size: 0.8rem;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .badge-role i {
            font-size: 0.95rem;
        }

        .dash-meta {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 0.35rem;
            font-size: 0.85rem;
            color: #334155;
        }

        .dash-meta span {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.25rem 0.7rem;
            border-radius: 999px;
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
        }

        /* Card thống kê nhỏ */
        .stats-row {
            margin-top: 22px;
            margin-bottom: 26px;
        }

        .stat-card {
            border-radius: 14px;
            background-color: #ffffff;
            padding: 16px 18px;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.08);
            border: 1px solid #e2e8f0;
            transition: transform 0.15s ease-out, box-shadow 0.15s ease-out, border-color 0.15s ease-out;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.12);
            border-color: #60a5fa;
        }

        .stat-label {
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #64748b;
            margin-bottom: 4px;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0f172a;
        }

        .stat-desc {
            font-size: 0.8rem;
            color: #94a3b8;
        }

        .stat-icon {
            font-size: 2rem;
            color: #2563eb;
            opacity: 0.9;
        }

        /* Tiêu đề khu chức năng */
        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 10px;
        }

        .section-subtitle {
            font-size: 0.85rem;
            color: #6b7280;
            margin-bottom: 18px;
        }

        /* Card module (các block điều hướng) */
        .module-card {
            display: block;
            text-decoration: none;
            background-color: #ffffff;
            border-radius: 16px;
            padding: 18px 18px 16px;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
            border: 1px solid #e5e7eb;
            color: inherit;
            transition: transform 0.16s ease-out, box-shadow 0.16s ease-out, border-color 0.16s ease-out, background-color 0.16s ease-out;
            height: 100%;
        }

        .module-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 14px 32px rgba(15, 23, 42, 0.14);
            border-color: #3b82f6;
            background-color: #f9fbff;
        }

        .module-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 0.8rem;
            margin-bottom: 10px;
        }

        .module-icon-wrap {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #eff6ff;
            color: #1d4ed8;
        }

        .module-icon-wrap i {
            font-size: 1.5rem;
        }

        .module-title {
            font-size: 1.02rem;
            font-weight: 600;
            color: #111827;
            margin: 0;
        }

        .module-tag {
            font-size: 0.75rem;
            color: #2563eb;
            background-color: #e0f2fe;
            padding: 2px 8px;
            border-radius: 999px;
            display: inline-block;
            margin-top: 3px;
        }

        .module-desc {
            margin: 6px 0 0;
            font-size: 0.86rem;
            color: #4b5563;
        }

        .module-footer {
            margin-top: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.8rem;
            color: #6b7280;
        }

        .module-link {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            color: #1d4ed8;
            font-weight: 500;
        }

        .module-link i {
            font-size: 1rem;
        }

        .module-meta {
            font-size: 0.78rem;
            color: #9ca3af;
        }

        @media (max-width: 767.98px) {
            .dash-meta {
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <!-- header -->
    <?php include "blocks/header.php" ?>

    <div class="main">
        <div class="dashboard-wrapper">

            <!-- ============= HEADER DASHBOARD ============= -->
            <div class="dash-header mb-4">
                <div>
                    <div class="badge-role mb-2">
                        <i class="bi bi-shield-lock-fill"></i>
                        Quản lý hệ thống
                    </div>
                    <div class="dash-title">Bảng điều khiển quản lý bệnh viện</div>
                    <p class="dash-subtitle">
                        Theo dõi tổng quan nhân sự, bệnh nhân, hoạt động điểm danh và thống kê trong một màn hình.
                    </p>
                </div>
                <div class="dash-meta">
                    <span>
                        <i class="bi bi-calendar3"></i>
                        Ngày hệ thống: <?php echo date('d/m/Y'); ?>
                    </span>
                    <span>
                        <i class="bi bi-activity"></i>
                        Trạng thái: Hệ thống hoạt động ổn định
                    </span>
                </div>
            </div>

            <!-- ============= HÀNG THỐNG KÊ NHANH ============= -->
            <div class="row stats-row g-3">
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <div>
                                <div class="stat-label">Bác sĩ</div>
                                <div class="stat-value">
                                    <?php echo isset($counts['doctorCount']) ? $counts['doctorCount'] : '--'; ?>
                                </div>
                            </div>
                            <i class="bi bi-person-badge stat-icon"></i>
                        </div>
                        <div class="stat-desc">Số bác sĩ đang được quản lý</div>
                    </div>
                </div>

                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <div>
                                <div class="stat-label">Nhân viên y tế</div>
                                <div class="stat-value">
                                    <?php echo isset($counts['staffCount']) ? $counts['staffCount'] : '--'; ?>
                                </div>
                            </div>
                            <i class="bi bi-hospital stat-icon"></i>
                        </div>
                        <div class="stat-desc">Điều dưỡng, kỹ thuật viên, hộ lý…</div>
                    </div>
                </div>

                <!-- KHỐI 3: NHÂN VIÊN NHÀ THUỐC (ĐÃ ĐỔI) -->
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <div>
                                <div class="stat-label">Nhân viên nhà thuốc</div>
                                <div class="stat-value">
                                    <?php echo isset($counts['pharmacyCount']) ? $counts['pharmacyCount'] : 0; ?>
                                </div>
                            </div>
                            <i class="bi bi-capsule-pill stat-icon"></i>
                        </div>
                        <div class="stat-desc">Tổng số nhân viên nhà thuốc</div>
                    </div>
                </div>

                <!-- KHỐI 4: NHÂN VIÊN XÉT NGHIỆM (ĐÃ ĐỔI) -->
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <div>
                                <div class="stat-label">Nhân viên xét nghiệm</div>
                                <div class="stat-value">
                                    <?php echo isset($counts['labCount']) ? $counts['labCount'] : 0; ?>
                                </div>
                            </div>
                            <i class="bi bi-eyedropper stat-icon"></i>
                        </div>
                        <div class="stat-desc">Tổng số nhân viên xét nghiệm</div>
                    </div>
                </div>
            </div>

            <!-- ============= CÁC CHỨC NĂNG QUẢN LÝ CHÍNH ============= -->
            <div class="mb-2">
                <div class="section-title">Chức năng quản lý chính</div>
                <div class="section-subtitle">
                    Truy cập nhanh các module quan trọng: điểm danh, nhân sự, bệnh nhân, nhà thuốc và thống kê.
                </div>
            </div>

            <div class="row g-3">

                <!-- Quản lý điểm danh (GIỮ NGUYÊN) -->
                <div class="col-lg-4 col-md-6">
                    <a href="/KLTN_Benhvien/Qlydd" class="module-card">
                        <div class="module-top">
                            <div class="d-flex align-items-start gap-2">
                                <div class="module-icon-wrap">
                                    <i class="bi bi-clipboard-check"></i>
                                </div>
                                <div>
                                    <h3 class="module-title">Quản lý điểm danh</h3>
                                    <span class="module-tag">Chấm công & ca trực</span>
                                    <p class="module-desc">
                                        Ghi nhận và theo dõi giờ làm việc, ca trực của nhân sự theo ngày / ca.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="module-footer">
                            <span class="module-link">
                                Vào màn hình điểm danh
                                <i class="bi bi-arrow-right-short"></i>
                            </span>
                            <span class="module-meta">Ưu tiên cao</span>
                        </div>
                    </a>
                </div>

                <!-- Quản lý nhân viên (THAY CHO "Quản lý bác sĩ") -->
                <div class="col-lg-4 col-md-6">
                    <a href="/KLTN_Benhvien/QuanLy/DSBS" class="module-card">
                        <div class="module-top">
                            <div class="d-flex align-items-start gap-2">
                                <div class="module-icon-wrap">
                                    <i class="bi bi-people-fill"></i>
                                </div>
                                <div>
                                    <h3 class="module-title">Quản lý nhân viên</h3>
                                    <span class="module-tag">Bác sĩ & NV y tế</span>
                                    <p class="module-desc">
                                        Quản lý thông tin bác sĩ và nhân viên y tế trong hệ thống, tập trung vào hồ sơ nhân sự.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="module-footer">
                            <span class="module-link">
                                Vào quản lý nhân viên
                                <i class="bi bi-arrow-right-short"></i>
                            </span>
                            <span class="module-meta">
                                Nhân sự chính: <?php echo isset($counts['doctorCount']) ? $counts['doctorCount'] : '--'; ?> bác sĩ
                            </span>
                        </div>
                    </a>
                </div>

                <!-- Lịch làm việc bác sĩ (THAY CHO "Nhân viên y tế") -->
                <div class="col-lg-4 col-md-6">
                    <a href="/KLTN_Benhvien/QuanLy/LLV" class="module-card">
                        <div class="module-top">
                            <div class="d-flex align-items-start gap-2">
                                <div class="module-icon-wrap">
                                    <i class="bi bi-calendar2-week"></i>
                                </div>
                                <div>
                                    <h3 class="module-title">Lịch làm việc bác sĩ</h3>
                                    <span class="module-tag">Phân ca & trực</span>
                                    <p class="module-desc">
                                        Quản lý lịch làm việc, phân ca khám và trực cho bác sĩ theo chuyên khoa và ngày.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="module-footer">
                            <span class="module-link">
                                Vào lịch làm việc
                                <i class="bi bi-arrow-right-short"></i>
                            </span>
                            <span class="module-meta">Quản lý ca khám & trực</span>
                        </div>
                    </a>
                </div>

                <!-- 3 khối Nhân viên nhà thuốc, Quản lý bệnh nhân, Thống kê & báo cáo đã được BỎ -->

            </div>
        </div>
    </div>

    <!-- JS Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
