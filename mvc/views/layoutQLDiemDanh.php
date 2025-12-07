<?php
    // Chặn truy cập nếu không phải quản lý
    if (!isset($_SESSION["role"]) || $_SESSION["role"] != 1) {
        echo "<script>alert('Bạn không có quyền truy cập');</script>";
        header("refresh: 0; url='/KLTN_Benhvien'");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <title>Quản lý điểm danh khuôn mặt</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet" />

    <!-- CSS chung -->
    <link rel="stylesheet" href="/KLTN_Benhvien/public/css/main.css">

    <style>
        body {
            background-color: #f3f4f6;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .dd-layout {
            display: flex;
            min-height: 100vh;
        }

        .dd-sidebar {
            width: 260px;
            background: linear-gradient(180deg, #0d6efd, #0b5ed7);
            color: #fff;
            display: flex;
            flex-direction: column;
            padding: 16px 0;
        }

        .dd-sidebar .dd-brand {
            font-size: 18px;
            font-weight: 600;
            padding: 0 20px 12px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .dd-sidebar .dd-brand i {
            font-size: 22px;
        }

        .dd-nav {
            list-style: none;
            padding: 8px 0;
            margin: 0;
            flex: 1;
        }

        .dd-nav li {
            margin: 4px 0;
        }

        .dd-nav a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 20px;
            color: #e5e7eb;
            text-decoration: none;
            font-size: 14px;
            border-radius: 999px;
            transition: background 0.2s ease, color 0.2s ease, transform 0.1s;
        }

        .dd-nav a i {
            font-size: 18px;
        }

        .dd-nav a:hover {
            background: rgba(255, 255, 255, 0.12);
            color: #ffffff;
            transform: translateX(2px);
        }

        .dd-nav a.active {
            background: #ffffff;
            color: #0d6efd;
            font-weight: 600;
        }

        .dd-sidebar-footer {
            padding: 8px 20px 0 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            font-size: 12px;
            color: #e5e7eb;
            opacity: 0.9;
        }

        .dd-main {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .dd-header {
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            padding: 10px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .dd-header-title {
            font-size: 18px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .dd-header-title span.badge {
            font-size: 11px;
        }

        .dd-header-user {
            font-size: 14px;
            color: #4b5563;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .dd-header-user i {
            font-size: 18px;
        }

        .dd-content {
            padding: 20px 24px;
        }

        /* Toast container */
        .toast-container {
            z-index: 1080;
        }
    </style>
</head>
<body>
<?php
$toastType = isset($_SESSION["toast_type"]) ? $_SESSION["toast_type"] : null;
$toastMessage = isset($_SESSION["toast_message"]) ? $_SESSION["toast_message"] : null;

// Xoá ngay sau khi đọc để F5 lại không hiện nữa
if ($toastType && $toastMessage) {
    unset($_SESSION["toast_type"], $_SESSION["toast_message"]);
?>
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999;">
        <div id="liveToast" class="toast align-items-center text-bg-<?php echo ($toastType === 'success' ? 'success' : 'danger'); ?> border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <?php echo htmlspecialchars($toastMessage); ?>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var toastEl = document.getElementById('liveToast');
            if (toastEl) {
                var t = new bootstrap.Toast(toastEl);
                t.show();
            }
        });
    </script>
<?php } ?>

    <div class="dd-layout">

        <!-- SIDEBAR -->
        <aside class="dd-sidebar">
            <div class="dd-brand">
                <i class="bi bi-person-badge-fill"></i>
                <div>
                    <div>Quản lý điểm danh</div>
                    <small class="text-light-emphasis">Face Attendance</small>
                </div>
            </div>

            <ul class="dd-nav">
                <li>
                    <a href="/KLTN_Benhvien/Qlydd/DD_QuetMat"
                       class="<?php echo (isset($data['Page']) && $data['Page'] == 'dd_quetmat') ? 'active' : ''; ?>">
                        <i class="bi bi-camera-video"></i>
                        <span>Điểm danh khuôn mặt</span>
                    </a>
                </li>
                <li>
                    <a href="/KLTN_Benhvien/Qlydd/DD_QuanLyMau"
                       class="<?php echo (isset($data['Page']) && $data['Page'] == 'dd_quanly_mau') ? 'active' : ''; ?>">
                        <i class="bi bi-people"></i>
                        <span>Quản lý mẫu khuôn mặt</span>
                    </a>
                </li>
                <li>
                    <a href="/KLTN_Benhvien/Qlydd/DD_DanhSachNgay"
                       class="<?php echo (isset($data['Page']) && $data['Page'] == 'dd_danhsach_ngay') ? 'active' : ''; ?>">
                        <i class="bi bi-list-check"></i>
                        <span>Danh sách điểm danh</span>
                    </a>
                </li>
                <li>
                    <a href="/KLTN_Benhvien/Qlydd/DD_DoiChieu"
                       class="<?php echo (isset($data['Page']) && $data['Page'] == 'dd_doichieu_lich') ? 'active' : ''; ?>">
                        <i class="bi bi-arrow-left-right"></i>
                        <span>Đối chiếu lịch & điểm danh</span>
                    </a>
                </li>
                <li>
                    <a href="/KLTN_Benhvien/Qlydd/DD_ThongKe"
                       class="<?php echo (isset($data['Page']) && $data['Page'] == 'dd_thongke') ? 'active' : ''; ?>">
                        <i class="bi bi-graph-up"></i>
                        <span>Thống kê điểm danh</span>
                    </a>
                </li>
                <li>
                    <a href="/KLTN_Benhvien/Qlydd/DD_CauHinhCa"
                       class="<?php echo (isset($data['Page']) && $data['Page'] == 'dd_cauhinh_ca') ? 'active' : ''; ?>">
                        <i class="bi bi-gear"></i>
                        <span>Cấu hình ca làm việc</span>
                    </a>
                </li>
            </ul>

            <div class="dd-sidebar-footer">
                <div><i class="bi bi-hospital"></i> Hệ thống bệnh viện</div>
                <div>&copy; <?php echo date("Y"); ?></div>
            </div>
        </aside>

        <!-- MAIN -->
        <div class="dd-main">
            <!-- Có thể reuse header chung nếu anh muốn -->
            <header class="dd-header">
                <div class="dd-header-title">
                    <i class="bi bi-clock-history text-primary"></i>
                    <span>Dashboard điểm danh</span>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                        Khuôn mặt · Ca làm việc
                    </span>
                </div>
                <div class="dd-header-user">
                    <i class="bi bi-person-circle"></i>
                    <span>Quản lý</span>
                </div>
            </header>

            <main class="dd-content">
                <?php
                    // Load page con theo "Page"
                    if (isset($data["Page"])) {
                        $pagePath = "./mvc/views/pages/" . $data["Page"] . ".php";
                        if (file_exists($pagePath)) {
                            require_once $pagePath;
                        } else {
                            echo '<div class="alert alert-warning">Trang không tồn tại.</div>';
                        }
                    } else {
                        echo '<div class="alert alert-info">Chưa có trang được chỉ định.</div>';
                    }
                ?>
            </main>
        </div>
    </div>

    <!-- Toast chung cho toàn module (sau này controller set $_SESSION['toast']) -->
    <?php if (isset($_SESSION['toast']) && is_array($_SESSION['toast'])): ?>
        <div class="toast-container position-fixed top-0 end-0 p-3">
            <div class="toast align-items-center show text-bg-<?php echo htmlspecialchars($_SESSION['toast']['type'] ?? 'primary'); ?> border-0"
                 role="alert" aria-live="assertive" aria-atomic="true" id="dd-main-toast">
                <div class="d-flex">
                    <div class="toast-body">
                        <?php echo htmlspecialchars($_SESSION['toast']['message'] ?? ''); ?>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>
        <?php unset($_SESSION['toast']); ?>
    <?php endif; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Nếu cần tự động show toast (đề phòng khi dùng data-bs-autohide)
        document.addEventListener("DOMContentLoaded", function () {
            var toastEl = document.getElementById('dd-main-toast');
            if (toastEl) {
                var t = new bootstrap.Toast(toastEl);
                t.show();
            }
        });
    </script>
</body>
</html>
