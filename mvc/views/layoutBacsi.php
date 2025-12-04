<?php
date_default_timezone_set('Asia/Ho_Chi_Minh'); // Đặt múi giờ VN
?>
<?php
if ($_SESSION["role"] != 2) {
    echo "<script>alert('Bạn không có quyền truy cập')</script>";
    header("refresh: 0; url='/KLTN_Benhvien'");
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
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }

        .layout {
            display: flex;
            width: 100%;
            max-width: 1500px; /* tăng từ 1400 lên 1500 cho rộng thêm */
            margin: 80px auto 0;
            gap: 20px;
        }

        .sidebar {
            width: 250px;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .main-content {
            flex-grow: 1;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1,
        h2,
        h3,
        h4 {
            color: #333;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 24px;
        }

        h2 {
            font-size: 20px;
        }

        h3 {
            font-size: 18px;
        }

        h4 {
            font-size: 16px;
        }

        .function-list {
            list-style-type: none;
            padding: 0;
        }

        .function-list li {
            margin-bottom: 10px;
        }

        .function-list a {
            text-decoration: none;
            color: #333;
            display: block;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .function-list a:hover,
        .function-list a.active {
            background-color: #f0f0f0;
        }

        .function-list a.active {
            background-color: #f0f0f0;
            color: #333;
        }

        .search-bar {
            margin-bottom: 20px;
            position: relative;
        }

        .search-bar input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .search-bar button {
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
            background-color: #007bff;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }

        .patient-info,
        .medical-history {
            margin-bottom: 20px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .info-item {
            margin-bottom: 5px;
        }

        .info-label {
            font-weight: bold;
        }

        .btn-close {
            display: inline-block;
            padding: 10px 20px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            text-align: center;
            cursor: pointer;
        }

        .btn-close:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <?php require_once "./mvc/views/blocks/header.php" ?>

    <div class="layout">
        <div class="sidebar">
            <h2>DANH SÁCH CHỨC NĂNG</h2>
            <ul class="function-list">
                <li><a href="/KLTN_Benhvien/Bacsi/thongkebs">Thống kê</a></li>
                <li><a href="/KLTN_Benhvien/Bacsi/XemLichLamViec">Xem lịch làm việc</a></li>
                <li><a href="/KLTN_Benhvien/Bacsi/XemDanhSachKham">Xem danh sách khám</a></li>
                <li><a href="/KLTN_Benhvien/Bacsi/DanhSachCuocTroChuyen">Hộp thư bệnh nhân</a></li>
                <li><a href="/KLTN_Benhvien/Bacsi/XemThongTinBenhNhan">Xem thông tin bệnh nhân</a></li>
                <li><a href="/KLTN_Benhvien/Bacsi/Thongtinbacsi">Thông tin bác sĩ</a></li>
                <li><a href="/KLTN_Benhvien/Bacsi/Doimatkhau">Đổi mật khẩu</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-body" id="mainContent">
                    <?php
                    if (isset($data["Page"])) {
                        require_once "./mvc/views/pages/" . $data["Page"] . ".php";
                    } else {
                        echo "<h5 class='text-center text-muted'>Vui lòng chọn một chức năng</h5>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    </div>

    <?php require_once "./mvc/views/blocks/footer.php" ?>
</body>

</html>
