<?php
    if($_SESSION["role"] != 5){
        echo "<script>alert('Bạn không có quyền truy cập')</script>";
        header("refresh: 0; url='/KLTN_Benhvien'");
    } else if(!isset($_SESSION["idbn"])){
        echo "<script>alert('Mời bạn tạo hồ sơ để tiếp tục!')</script>";
        header("refresh: 0; url='/KLTN_Benhvien/Register/BNHS'");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khu vực bệnh nhân</title>
    <link rel="stylesheet" href="./public/css/main.css">
    <link rel="stylesheet" href="../public/css/main.css">
    <link rel="stylesheet" href="../public/css/bn.css">
    <link rel="stylesheet" href="./public/css/bn.css"> 

    <!-- KHÔNG dùng file CSS ngoài, viết CSS thuần ngay tại đây -->
    <style>
        /* ====== NỀN CHUNG KHU VỰC BỆNH NHÂN ====== */
        body {
            background-color: #e9ecef;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        /* Mở rộng container cho khu bệnh nhân (rộng hơn Bootstrap mặc định) */
        .bn-layout-container.container {
            max-width: 1400px; /* tăng độ rộng */
        }

        /* Khoảng cách dưới cho khu nội dung chính */
        .bn-layout-container {
            margin-bottom: 50px;
        }

        /* ====== SIDEBAR BỆNH NHÂN (tham khảo bn.css) ====== */
        .sidebar {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            height: 100%;
        }

        .sidebar ul {
            list-style: none;
            padding-left: 0;
            margin: 0;
        }

        .sidebar li {
            margin-bottom: 10px;
        }

        .sidebar a {
            display: block;
            padding: 10px 15px;
            text-decoration: none;
            color: #495057;
            font-weight: 500;
            transition: all 0.3s ease;
            border-radius: 4px;
        }

        .sidebar a:hover {
            background-color: #0d6efd;
            color: #fff;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .sidebar a.active {
            background-color: #0d6efd;
            color: #fff;
            font-weight: bold;
        }

        /* ====== VÙNG NỘI DUNG BÊN PHẢI ====== */
        .bn-layout-content {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.08);
            min-height: 500px;
            /* Quan trọng: cho phép nội dung rộng, không bị bó */
            overflow-x: auto;
        }

        /* Tùy chọn: tiêu đề chung nội dung (nếu page con dùng) */
        .bn-main-title {
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 20px;
        }

        /* Responsive cho màn nhỏ: sidebar lên trên, content bên dưới */
        @media (max-width: 992px) {
            .sidebar {
                margin-bottom: 20px;
            }
        }
    </style>
</head>
<body>
    <?php require "./mvc/views/blocks/header.php";?>

    <div class="container mt-5 bn-layout-container">
        <div class="row">
            <!-- SIDEBAR BỆNH NHÂN -->
            <div class="col-md-2 sidebar">
                <ul>
                    <li><a href="/KLTN_Benhvien/BN/LichKham">Lịch khám đã đặt</a></li>
                    <li><a href="/KLTN_Benhvien/ThanhToan">Thanh toán</a></li>
                    <li><a href="/KLTN_Benhvien/BN/DanhSachBacSiChat">Hộp thư</a></li>
                    <li><a href="/KLTN_Benhvien/BN/TTBN">Hồ sơ cá nhân</a></li>
                    <li><a href="/KLTN_Benhvien/BN/Hosophieukham">Hồ sơ phiếu khám</a></li>
                    <li><a href="/KLTN_Benhvien/BN/changePass">Đổi mật khẩu</a></li>
                </ul>
            </div>

            <!-- NỘI DUNG PAGE CON (CHAT, LỊCH KHÁM, HỒ SƠ, …) -->
            <div class="col-md-10 bn-layout-content">
                <?php
                    if (isset($data["Page"])) {
                        require_once "./mvc/views/pages/" . $data["Page"] . ".php";
                    }
                ?>
            </div>
        </div>
    </div>

    <?php require "./mvc/views/blocks/footer.php";?>
</body>
</html>
