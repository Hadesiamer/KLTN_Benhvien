<?php
    // Kiểm tra quyền truy cập: chỉ role = 1 (quản lý) mới được vào
    if ($_SESSION["role"] != 1) {
        echo "<script>alert('Bạn không có quyền truy cập')</script>";
        header("refresh: 0; url='/KLTN_Benhvien'");
    }
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý nhân sự bệnh viện</title>

    <!-- Bootstrap core -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons (UI icon cho sidebar, button,...) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- CSS dự án (giữ nguyên đường dẫn sẵn có) -->
    <link rel="stylesheet" href="./public/css/main.css">
    <link rel="stylesheet" href="../public/css/doctor.css">
    <link rel="stylesheet" href="../public/css/main.css">

    <style>
        /* UI mới: nền tổng thể dịu, cảm giác hệ thống y tế */
        body {
            background-color: #f5f7fb;
        }

        .layout-wrapper {
            min-height: calc(100vh - 70px); /* trừ chiều cao header (ước lượng) */
        }

        .sidebar-card {
            background: linear-gradient(145deg, #6062deff, #f1f5ff);
        }

        .sidebar-avatar {
            width: 40px;
            height: 40px;
            background: #e3eeff;
            color: #2563eb;
            font-size: 1.5rem;
        }

        .sidebar-nav .btn {
            border-radius: 999px;
            font-weight: 500;
        }

        .sidebar-nav .btn.active {
            background-color: #2563eb;
            color: #fff;
            border-color: #2563eb;
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);
        }

        .sidebar-nav .btn:not(.active) {
            background-color: #ffffff;
            border-color: #e2e8f0;
            color: #1e293b;
        }

        .sidebar-nav .btn:not(.active):hover {
            background-color: #eff6ff;
            border-color: #bfdbfe;
        }

        .page-header-card {
            border-radius: 1rem;
        }
    </style>
</head>
<body>
    <?php include "blocks/header.php" ?>

    <div class="container-fluid py-3 layout-wrapper">
        <div class="row g-3">

            <!-- SIDEBAR: Điều hướng quản lý nhân sự -->
            <aside class="col-12 col-md-4 col-lg-3 col-xl-2">
                <div class="card border-0 shadow-sm sidebar-card h-100">
                    <div class="card-body d-flex flex-column">
                        <!-- Thông tin khối quản lý -->
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center sidebar-avatar me-3">
                                <i class="bi bi-hospital"></i>
                            </div>
                            <div>
                                <div class="text-uppercase small text-muted fw-semibold">Bảng điều khiển</div>
                                <div class="fw-bold">Quản lý nhân sự</div>
                            </div>
                        </div>

                        <hr class="my-2">

                        <!-- Điều hướng các danh sách -->
                        <div class="sidebar-nav mt-2 d-grid gap-2">
                            <button class="btn btn-sm text-start <?php echo ($data['Page'] == 'qlbs') ? 'active' : ''; ?>"
                                    onclick="window.location.href='./DSBS'">
                                <i class="bi bi-person-badge me-2"></i>
                                Bác sĩ
                            </button>

                            <button class="btn btn-sm text-start <?php echo ($data['Page'] == 'qlnvyt') ? 'active' : ''; ?>"
                                    onclick="window.location.href='./DSNVYT'">
                                <i class="bi bi-heart-pulse me-2"></i>
                                Nhân viên y tế
                            </button>

                            <button class="btn btn-sm text-start <?php echo ($data['Page'] == 'qlnvnt') ? 'active' : ''; ?>"
                                    onclick="window.location.href='./DSNVNT'">
                                <i class="bi bi-capsule me-2"></i>
                                Nhân viên nhà thuốc
                            </button>

                            <button class="btn btn-sm text-start <?php echo ($data['Page'] == 'qlnvxn') ? 'active' : ''; ?>"
                                    onclick="window.location.href='./DSNVXN'">
                                <i class="bi bi-droplet-half me-2"></i>
                                Nhân viên xét nghiệm
                            </button>
                        </div>

                        <!-- Thông tin nhỏ ở đáy sidebar -->
                        <div class="mt-auto pt-3 small text-muted">
                            <div class="d-flex align-items-center mb-1">
                                <i class="bi bi-calendar3 me-2"></i>
                                <span><?php echo date('d/m/Y'); ?></span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-shield-check me-2"></i>
                                <span>Quyền: Quản lý</span>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- MAIN CONTENT -->
            <main class="col-12 col-md-8 col-lg-9 col-xl-10">
                <!-- Header chính cho khu vực nội dung -->
                <div class="card border-0 shadow-sm mb-3 page-header-card">
                    <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <div>
                            <h1 class="h4 text-start mb-1">Quản lý nhân viên</h1>
                        </div>

                        <div class="d-flex flex-wrap gap-2">
                            <span class="badge rounded-pill text-bg-primary-subtle border border-primary-subtle text-dark fw-semibold">
                                <i class="bi bi-person-badge me-1"></i> Bác sĩ
                            </span>
                            <span class="badge rounded-pill text-bg-success-subtle border border-success-subtle text-dark fw-semibold">
                                <i class="bi bi-heart-pulse me-1"></i> Nhân viên y tế
                            </span>
                            <span class="badge rounded-pill text-bg-warning-subtle border border-warning-subtle text-dark fw-semibold">
                                <i class="bi bi-capsule me-1"></i> Nhà thuốc
                            </span>
                            <span class="badge rounded-pill text-bg-info-subtle border border-info-subtle text-dark fw-semibold">
                                <i class="bi bi-droplet-half me-1"></i> Xét nghiệm
                            </span>
                        </div>

                    </div>
                </div>

                <!-- Thông báo chung (nếu có) -->
                <?php if (isset($data["Message"])): ?>
                    <div class="alert alert-success shadow-sm"><?php echo $data["Message"]; ?></div>
                <?php endif; ?>

                <?php if (isset($data["Error"])): ?>
                    <div class="alert alert-danger shadow-sm"><?php echo $data["Error"]; ?></div>
                <?php endif; ?>

                <!-- Nội dung page con -->
                <?php require_once "./mvc/views/pages/" . $data["Page"] . ".php" ?>
            </main>
        </div>
    </div>

    <!-- Bootstrap bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
