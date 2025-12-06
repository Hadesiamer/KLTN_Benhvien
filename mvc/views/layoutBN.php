<?php
    if($_SESSION["role"] != 5){
        echo "<script>alert('Bạn không có quyền truy cập')</script>";
        header("refresh: 0; url='/KLTN_Benhvien'");
    } else if(!isset($_SESSION["idbn"])){
        echo "<script>alert('Mời bạn tạo hồ sơ để tiếp tục!')</script>";
        header("refresh: 0; url='/KLTN_Benhvien/Register/BNHS'");
    }

    // Lấy route hiện tại để highlight menu
    $currentUrl = isset($_GET["url"]) ? $_GET["url"] : "";
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

    <style>
        body {
            background-color: #e9ecef;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .bn-layout-container.container {
            max-width: 1400px;
        }

        .bn-layout-container {
            margin-bottom: 50px;
        }

        .sidebar {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
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
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .sidebar a.active {
            background-color: #0d6efd;
            color: #fff;
            font-weight: bold;
        }

        .bn-layout-content {
            background-color: #ffffff;
            padding: 20px;
            min-height: 500px;
            overflow-x: auto;
        }

        .bn-main-title {
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 20px;
        }

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

            <!-- SIDEBAR -->
            <div class="col-md-2 sidebar">
                <ul>
                    <li>
                        <a href="/KLTN_Benhvien/BN/LichKham"
                           class="<?php echo (strpos($currentUrl, 'BN/LichKham') !== false) ? 'active' : ''; ?>">
                           Lịch khám đã đặt
                        </a>
                    </li>

                    <li>
                        <a href="/KLTN_Benhvien/ThanhToan"
                           class="<?php echo (strpos($currentUrl, 'ThanhToan') !== false) ? 'active' : ''; ?>">
                           Thanh toán
                        </a>
                    </li>

                    <li>
                        <a href="/KLTN_Benhvien/BN/DanhSachBacSiChat"
                           class="<?php echo (strpos($currentUrl, 'BN/DanhSachBacSiChat') !== false) ? 'active' : ''; ?>">
                           Hộp thư
                        </a>
                    </li>

                    <li>
                        <a href="/KLTN_Benhvien/BN/TTBN"
                           class="<?php echo (strpos($currentUrl, 'BN/TTBN') !== false) ? 'active' : ''; ?>">
                           Hồ sơ cá nhân
                        </a>
                    </li>

                    <li>
                        <a href="/KLTN_Benhvien/BN/Hosophieukham"
                           class="<?php echo (strpos($currentUrl, 'BN/Hosophieukham') !== false) ? 'active' : ''; ?>">
                           Hồ sơ phiếu khám
                        </a>
                    </li>

                    <li>
                        <a href="/KLTN_Benhvien/BN/changePass"
                           class="<?php echo (strpos($currentUrl, 'BN/changePass') !== false) ? 'active' : ''; ?>">
                           Đổi mật khẩu
                        </a>
                    </li>
                </ul>
            </div>

            <!-- PAGE CONTENT -->
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
