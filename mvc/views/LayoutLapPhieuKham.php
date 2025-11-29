<?php
    // Chỉ cho BÁC SĨ (role = 2) vào layout này
    if (!isset($_SESSION["role"]) || $_SESSION["role"] != 2) {
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
    <title>Lập Phiếu Khám</title>

    <!-- CSS chung của hệ thống -->
    <link rel="stylesheet" href="/KLTN_Benhvien/public/css/main.css">

    <!-- CSS riêng cho màn hình Lập phiếu khám -->
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-hover: #1d4ed8;
            --success-color: #16a34a;
            --success-hover: #15803d;
            --danger-color: #dc2626;
            --danger-hover: #b91c1c;
            --bg-gray: #f8fafc;
            --border-color: #e2e8f0;
            --text-primary: #0f172a;
            --text-secondary: #64748b;
            --card-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
        }

        body {
            background-color: var(--bg-gray);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        /* Container chính */
        .lpk-wrapper {
            max-width: 1400px;
            margin: 0 auto;
            padding: 24px 20px;
        }

        /* Header với breadcrumb và title */
        .lpk-header {
            margin-bottom: 24px;
        }

        .lpk-breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: var(--text-secondary);
            margin-bottom: 12px;
        }

        .lpk-breadcrumb a {
            color: var(--primary-color);
            text-decoration: none;
            transition: color 0.2s;
        }

        .lpk-breadcrumb a:hover {
            color: var(--primary-hover);
            text-decoration: underline;
        }

        .lpk-breadcrumb-separator {
            color: var(--text-secondary);
        }

        .lpk-title-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }

        .lpk-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
        }

        .lpk-subtitle {
            font-size: 14px;
            color: var(--text-secondary);
            margin-top: 4px;
        }

        /* Nút trở về */
        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background-color: white;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-primary);
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
            box-shadow: var(--card-shadow);
        }

        .btn-back:hover {
            background-color: var(--bg-gray);
            border-color: var(--primary-color);
            color: var(--primary-color);
            transform: translateX(-2px);
        }

        .btn-back svg {
            width: 18px;
            height: 18px;
        }

        /* Main content card */
        .lpk-card {
            background-color: white;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            box-shadow: var(--card-shadow);
            padding: 28px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .lpk-wrapper {
                padding: 16px 12px;
            }

            .lpk-title {
                font-size: 24px;
            }

            .lpk-title-row {
                flex-direction: column;
                align-items: flex-start;
            }

            .lpk-card {
                padding: 20px 16px;
            }
        }
    </style>
</head>
<body>
    <?php include "blocks/header.php"; ?>

    <div class="lpk-wrapper">
        <!-- Header Section -->
        <div class="lpk-header">
            <!-- Breadcrumb -->
            <div class="lpk-breadcrumb">
                <a href="/KLTN_Benhvien/Bacsi">Trang chủ</a>
                <span class="lpk-breadcrumb-separator">/</span>
                <a href="/KLTN_Benhvien/Bacsi/XemDanhSachKham">Danh sách khám</a>
                <span class="lpk-breadcrumb-separator">/</span>
                <span>Lập phiếu khám</span>
            </div>

            <!-- Title và nút trở về -->
            <div class="lpk-title-row">
                <div>
                    <h1 class="lpk-title">Lập Phiếu Khám Bệnh</h1>
                    <div class="lpk-subtitle">
                        Tạo phiếu khám và kê đơn thuốc cho bệnh nhân từ lịch khám đã thanh toán
                    </div>
                </div>
                <a href="/KLTN_Benhvien/Bacsi/XemDanhSachKham" class="btn-back">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Trở về danh sách
                </a>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="lpk-card">
            <!-- Nội dung chính được render từ pages/Lapphieukham.php -->
            <?php require_once "./mvc/views/pages/" . $data["Page"] . ".php"; ?>
        </div>
    </div>

    <?php require_once "./mvc/views/blocks/footer.php"; ?>
</body>
</html>