<?php
date_default_timezone_set('Asia/Ho_Chi_Minh'); // Đặt múi giờ VN

if (!isset($_SESSION["role"]) || $_SESSION["role"] != 2) {
    echo "<script>alert('Bạn không có quyền truy cập')</script>";
    header("refresh: 0; url='/KLTN_Benhvien'");
    exit;
}

// Lấy URI hiện tại để set active cho menu
$currentUri = $_SERVER['REQUEST_URI'] ?? '';

function bs_is_active($path, $currentUri)
{
    return (strpos($currentUri, $path) !== false) ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chức năng bác sĩ</title>
    <link rel="stylesheet" href="/KLTN_Benhvien/public/css/main.css">
    <link rel="stylesheet" href="/KLTN_Benhvien/public/css/ttbs.css">
    <!-- Giả định Bootstrap đã được load trong header.php, nếu chưa thì bạn đã tự cấu hình riêng -->

    <style>
        /* ===== RESET NHẸ & NỀN CHUNG ===== */
        body {
            margin: 0;
            padding: 0;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: linear-gradient(180deg, #e5f0ff 0%, #f5f7fb 40%, #ffffff 100%);
        }

        /* ===== LAYOUT BÁC SĨ ===== */
        .bs-layout-shell {
            max-width: 1500px;
            margin: 80px auto 24px;
            padding: 0 12px;
        }

        .bs-layout {
            display: flex;
            gap: 18px;
            align-items: flex-start;
        }

        /* ===== SIDEBAR ===== */
        .bs-sidebar {
            width: 270px;
            background: #ffffff;
            border-radius: 18px;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.12);
            padding: 18px 16px 16px;
            position: relative;
            overflow: hidden;
        }

        .bs-sidebar::before {
            /* Thanh màu phía trên tạo cảm giác brand bệnh viện */
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 0 0, rgba(37, 99, 235, 0.18), transparent 55%),
                        radial-gradient(circle at 100% 0, rgba(16, 185, 129, 0.15), transparent 60%);
            opacity: 0.35;
            pointer-events: none;
        }

        .bs-sidebar-inner {
            position: relative;
            z-index: 1;
        }

        .bs-sidebar-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 14px;
        }

        .bs-sidebar-avatar {
            width: 46px;
            height: 46px;
            border-radius: 14px;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 22px;
            box-shadow: 0 6px 14px rgba(37, 99, 235, 0.45);
        }

        .bs-sidebar-header-text h2 {
            margin: 0;
            font-size: 17px;
            font-weight: 700;
            color: #0f172a;
        }

        .bs-sidebar-header-text p {
            margin: 2px 0 0 0;
            font-size: 12px;
            color: #6b7280;
        }

        .bs-sidebar-section-title {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #9ca3af;
            margin: 8px 2px 6px;
        }

        .bs-nav {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .bs-nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 10px;
            border-radius: 999px;
            font-size: 14px;
            color: #1f2937;
            text-decoration: none;
            position: relative;
            transition: background 0.15s ease, transform 0.12s ease, box-shadow 0.12s ease, color 0.12s ease;
        }

        .bs-nav-link-icon {
            width: 26px;
            height: 26px;
            border-radius: 999px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            background: #eff3ff;
            color: #2563eb;
            flex-shrink: 0;
        }

        .bs-nav-link-text {
            flex: 1;
            min-width: 0;
        }

        .bs-nav-link span.label {
            white-space: nowrap;
        }

        .bs-nav-link span.sub {
            display: block;
            font-size: 11px;
            color: #9ca3af;
        }

        .bs-nav-link:hover {
            background: rgba(219, 234, 254, 0.8);
            transform: translateY(-1px);
            box-shadow: 0 3px 8px rgba(59, 130, 246, 0.25);
        }

        .bs-nav-link.active {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #ffffff;
            box-shadow: 0 5px 14px rgba(37, 99, 235, 0.55);
        }

        .bs-nav-link.active .bs-nav-link-icon {
            background: rgba(255, 255, 255, 0.18);
            color: #ffffff;
        }

        .bs-nav-link.active span.sub {
            color: #e5e7eb;
        }

        /* ===== MAIN CONTENT ===== */
        .bs-main {
            flex: 1;
            min-width: 0;
        }

        .bs-main-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 14px 36px rgba(15, 23, 42, 0.15);
            padding: 18px 18px 20px;
        }

        .bs-main-header-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            margin-bottom: 14px;
            flex-wrap: wrap;
        }

        .bs-main-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 15px;
            font-weight: 600;
            color: #4b5563;
        }

        .bs-main-title span.icon {
            font-size: 18px;
        }

        .bs-main-meta {
            font-size: 12px;
            color: #6b7280;
        }

        /* ===== TOGGLE SIDEBAR MOBILE ===== */
        .bs-sidebar-toggle {
            display: none;
            align-items: center;
            gap: 6px;
            margin-bottom: 8px;
        }

        .bs-sidebar-toggle button {
            border-radius: 999px;
            border: none;
            padding: 6px 12px;
            font-size: 14px;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #ffffff;
            display: flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.45);
            cursor: pointer;
        }

        .bs-sidebar-toggle button span.icon {
            font-size: 16px;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 991.98px) {
            .bs-layout-shell {
                margin-top: 76px;
            }

            .bs-layout {
                flex-direction: column;
            }

            .bs-sidebar-toggle {
                display: flex;
            }

            .bs-sidebar {
                width: 100%;
                max-height: 0;
                opacity: 0;
                transform: translateY(-6px);
                transition: max-height 0.25s ease, opacity 0.25s ease, transform 0.2s ease;
                overflow: hidden;
            }

            .bs-sidebar.open {
                max-height: 420px;
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 576px) {
            .bs-main-card {
                padding: 14px 12px 16px;
            }

            .bs-layout-shell {
                padding: 0 8px;
            }
        }
    </style>
</head>

<?php if (isset($_SESSION["toast"])): ?>
<script>
    document.addEventListener("DOMContentLoaded", function(){
        let t = document.createElement("div");
        t.innerHTML = "<?= $_SESSION['toast'] ?>";
        t.style.position = "fixed";
        t.style.right = "20px";
        t.style.top = "20px";
        t.style.padding = "12px 20px";
        t.style.background = "#4CAF50";
        t.style.color = "#fff";
        t.style.borderRadius = "8px";
        t.style.boxShadow = "0 2px 6px rgba(0,0,0,0.2)";
        document.body.appendChild(t);
        setTimeout(()=> t.remove(), 4000);
    });
</script>
<?php unset($_SESSION["toast"]); endif; ?>

<body>
    <?php require_once "./mvc/views/blocks/header.php"; ?>

    <div class="bs-layout-shell">
        <!-- Nút toggle sidebar trên mobile -->
        <div class="bs-sidebar-toggle">
            <button type="button" id="bsSidebarToggleBtn">
                <span class="icon">☰</span>
                <span>Menu chức năng bác sĩ</span>
            </button>
        </div>

        <div class="bs-layout">
            <!-- SIDEBAR -->
            <aside class="bs-sidebar" id="bsSidebar">
                <div class="bs-sidebar-inner">
                    <div class="bs-sidebar-header">
                        <div class="bs-sidebar-avatar">🩺</div>
                        <div class="bs-sidebar-header-text">
                            <h2>Bác sĩ</h2>
                            <p>Bảng điều khiển chức năng</p>
                        </div>
                    </div>

                    <div class="bs-sidebar-section-title">Chức năng</div>
                    <nav class="bs-nav">
                        <a href="/KLTN_Benhvien/Bacsi/thongkebs"
                           class="bs-nav-link <?php echo bs_is_active('/Bacsi/thongkebs', $currentUri); ?>">
                            <div class="bs-nav-link-icon">📊</div>
                            <div class="bs-nav-link-text">
                                <span class="label">Thống kê</span>
                                <span class="sub">Tổng quan hoạt động khám</span>
                            </div>
                        </a>

                        <a href="/KLTN_Benhvien/Bacsi/XemLichLamViec"
                           class="bs-nav-link <?php echo bs_is_active('/Bacsi/XemLichLamViec', $currentUri); ?>">
                            <div class="bs-nav-link-icon">📅</div>
                            <div class="bs-nav-link-text">
                                <span class="label">Xem lịch làm việc</span>
                                <span class="sub">Ca trực theo tuần</span>
                            </div>
                        </a>

                        <a href="/KLTN_Benhvien/Bacsi/XemDanhSachKham"
                           class="bs-nav-link <?php echo bs_is_active('/Bacsi/XemDanhSachKham', $currentUri); ?>">
                            <div class="bs-nav-link-icon">👨‍⚕️</div>
                            <div class="bs-nav-link-text">
                                <span class="label">Xem danh sách khám</span>
                                <span class="sub">Bệnh nhân trong ngày</span>
                            </div>
                        </a>

                        <a href="/KLTN_Benhvien/Bacsi/DanhSachCuocTroChuyen"
                           class="bs-nav-link <?php echo bs_is_active('/Bacsi/DanhSachCuocTroChuyen', $currentUri); ?>">
                            <div class="bs-nav-link-icon">💬</div>
                            <div class="bs-nav-link-text">
                                <span class="label">Hộp thư bệnh nhân</span>
                                <span class="sub">Trao đổi & tư vấn</span>
                            </div>
                        </a>

                        <a href="/KLTN_Benhvien/Bacsi/XemThongTinBenhNhan"
                           class="bs-nav-link <?php echo bs_is_active('/Bacsi/XemThongTinBenhNhan', $currentUri); ?>">
                            <div class="bs-nav-link-icon">📁</div>
                            <div class="bs-nav-link-text">
                                <span class="label">Xem thông tin bệnh nhân</span>
                                <span class="sub">Hồ sơ điều trị</span>
                            </div>
                        </a>

                        <a href="/KLTN_Benhvien/Bacsi/Thongtinbacsi"
                           class="bs-nav-link <?php echo bs_is_active('/Bacsi/Thongtinbacsi', $currentUri); ?>">
                            <div class="bs-nav-link-icon">👤</div>
                            <div class="bs-nav-link-text">
                                <span class="label">Thông tin bác sĩ</span>
                                <span class="sub">Hồ sơ cá nhân</span>
                            </div>
                        </a>

                        <a href="/KLTN_Benhvien/Bacsi/Doimatkhau"
                           class="bs-nav-link <?php echo bs_is_active('/Bacsi/Doimatkhau', $currentUri); ?>">
                            <div class="bs-nav-link-icon">🔐</div>
                            <div class="bs-nav-link-text">
                                <span class="label">Đổi mật khẩu</span>
                                <span class="sub">Bảo mật tài khoản</span>
                            </div>
                        </a>
                    </nav>
                </div>
            </aside>

            <!-- MAIN CONTENT -->
            <main class="bs-main">
                <div class="bs-main-card">
                    <div class="bs-main-header-bar">
                        <div class="bs-main-title">
                            <span class="icon">🏥</span>
                            <span>Bảng điều khiển bác sĩ</span>
                        </div>
                        <div class="bs-main-meta">
                            <?php
                            // hiển thị ngày hiện tại
                            echo 'Hôm nay: ' . date('d/m/Y');
                            ?>
                        </div>
                    </div>

                    <div id="mainContent">
                        <?php
                        if (isset($data["Page"])) {
                            require_once "./mvc/views/pages/" . $data["Page"] . ".php";
                        } else {
                            echo "<h5 class='text-center text-muted'>Vui lòng chọn một chức năng ở thanh bên trái.</h5>";
                        }
                        ?>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <?php require_once "./mvc/views/blocks/footer.php"; ?>

    <script>
        // JS toggle sidebar trên mobile
        document.addEventListener('DOMContentLoaded', function () {
            var toggleBtn = document.getElementById('bsSidebarToggleBtn');
            var sidebar = document.getElementById('bsSidebar');

            if (toggleBtn && sidebar) {
                toggleBtn.addEventListener('click', function () {
                    sidebar.classList.toggle('open');
                });
            }
        });
    </script>
</body>

</html>
