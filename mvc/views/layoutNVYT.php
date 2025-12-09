<?php
    // Kiểm tra quyền truy cập: giả sử role = 3 là Nhân viên y tế
    if (!isset($_SESSION["role"]) || $_SESSION["role"] != 3) {
        echo "<script>alert('Bạn không có quyền truy cập');</script>";
        header("refresh: 0; url='/KLTN_Benhvien'");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khu vực Nhân viên y tế</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- CSS chung -->
    <link rel="stylesheet" href="./public/css/main.css">
    <link rel="stylesheet" href="../public/css/main.css">

    <style>
        body {
            background-color: #f3f4f6;
        }

        .nvyt-wrapper {
            min-height: calc(100vh - 60px);
        }

        .sidebar-card {
            background: linear-gradient(135deg, #eff6ff, #e0f2fe);
            border-radius: 16px;
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .sidebar-avatar {
            width: 42px;
            height: 42px;
            border-radius: 999px;
            background-color: #1d4ed8;
            color: #eff6ff;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .sidebar-avatar i {
            font-size: 1.4rem;
        }

        .sidebar-nav .btn {
            border-radius: 999px;
            justify-content: flex-start;
            font-size: 0.9rem;
        }

        .sidebar-nav .btn.active {
            background-color: #1d4ed8;
            color: #ffffff;
            border-color: #1d4ed8;
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.4);
        }

        .sidebar-nav .btn:not(.active) {
            background-color: #ffffff;
            border-color: #d1d5db;
            color: #111827;
        }

        .sidebar-nav .btn:not(.active):hover {
            background-color: #eff6ff;
            border-color: #93c5fd;
        }

        .page-header-card {
            border-radius: 16px;
        }
    </style>
</head>
<body>
    <?php include "blocks/header.php"; ?>

    <div class="container-fluid py-3 nvyt-wrapper">
        <div class="row g-3">

            <!-- SIDEBAR -->
            <aside class="col-12 col-md-4 col-lg-3 col-xl-2">
                <div class="card sidebar-card border-0 shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="sidebar-header">
                            <div class="sidebar-avatar">
                                <i class="bi bi-heart-pulse"></i>
                            </div>
                            <div>
                                <div class="small text-uppercase text-muted fw-semibold">
                                    Nhân viên y tế
                                </div>
                                <div class="fw-bold">Khu vực làm việc</div>
                            </div>
                        </div>

                        <hr class="my-2">

                        <!-- Menu điều hướng -->
                        <div class="sidebar-nav d-grid gap-2 mt-2">
                            <button
                                class="btn btn-sm d-flex align-items-center <?php echo ($data['Page'] == 'YT_QuanLyLichKham') ? 'active' : ''; ?>"
                                onclick="window.location.href='/KLTN_Benhvien/NVYT/QuanLyLichKham'">
                                <i class="bi bi-calendar-check me-2"></i>
                                Quản lý lịch khám
                            </button>

                            <button
                                class="btn btn-sm d-flex align-items-center <?php echo ($data['Page'] == 'YT_TaoBenhNhan') ? 'active' : ''; ?>"
                                onclick="window.location.href='/KLTN_Benhvien/NVYT/TaoBenhNhanMoi'">
                                <i class="bi bi-person-plus me-2"></i>
                                Tạo bệnh nhân mới
                            </button>

                            <button
                                class="btn btn-sm d-flex align-items-center <?php echo ($data['Page'] == 'YT_ThongTinCaNhan') ? 'active' : ''; ?>"
                                onclick="window.location.href='/KLTN_Benhvien/NVYT/ThongTinCaNhan'">
                                <i class="bi bi-person-lines-fill me-2"></i>
                                Thông tin cá nhân
                            </button>

                            <button
                                class="btn btn-sm d-flex align-items-center <?php echo ($data['Page'] == 'YT_DoiMatKhau') ? 'active' : ''; ?>"
                                onclick="window.location.href='/KLTN_Benhvien/NVYT/DoiMatKhau'">
                                <i class="bi bi-key me-2"></i>
                                Đổi mật khẩu
                            </button>
                        </div>

                        <div class="mt-auto pt-3 small text-muted">
                            <div class="d-flex align-items-center mb-1">
                                <i class="bi bi-calendar3 me-2"></i>
                                <span><?php echo date('d/m/Y'); ?></span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-shield-check me-2"></i>
                                <span>Đã đăng nhập</span>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- MAIN CONTENT -->
            <main class="col-12 col-md-8 col-lg-9 col-xl-10">
                <div class="card border-0 shadow-sm mb-3 page-header-card">
                    <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <div>
                            <h1 class="h4 text-start mb-1">Khu vực làm việc - Nhân viên y tế</h1>
                            <p class="text-muted small mb-0">
                                Thực hiện quản lý lịch khám, hỗ trợ bệnh nhân và cập nhật thông tin cá nhân.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Flash từ controller (nếu có) -->
                <?php if (isset($data["Message"])): ?>
                    <div class="alert alert-success shadow-sm"><?php echo $data["Message"]; ?></div>
                <?php endif; ?>

                <?php if (isset($data["Error"])): ?>
                    <div class="alert alert-danger shadow-sm"><?php echo $data["Error"]; ?></div>
                <?php endif; ?>

                <!-- Page con -->
                <?php require_once "./mvc/views/pages/" . $data["Page"] . ".php"; ?>
            </main>
        </div>
    </div>

    <!-- JS Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
