<?php
    if($_SESSION["role"] != 1){
        echo "<script>alert('Bạn không có quyền truy cập')</script>";
        header("refresh: 0; url='/KLTN_Benhvien'");
    }
?>

<?php
// File: layoutQL.php

// At the top of the file, after starting the session:
require_once "./mvc/controllers/QuanLy.php";
$quanLy = new QuanLy();
$counts = $quanLy->GetDashboardCounts();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./public/css/main.css">
    <link rel="stylesheet" href="../public/css/main.css">
    <title>Trang chủ quản lý</title>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar-brand {
            font-weight: bold;
        }
        .card {
            transition: transform 0.2s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body>
<!-- header -->
<?php include "blocks/header.php" ?>
<div class="main">
<div class="container mt-4">
        <h1 class="mb-4">Welcome, Admin...!</h1>
        
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                    <a href="./QuanLy/TTBN" class="text-white text-decoration-none"><h5 class="card-title">Quản Lý Thông Tin Bệnh Nhân</h5></a>
                        <p class="card-text display-4">1,234</p>
                    </div>
                </div>
            </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <a href="./QuanLy/DSBS" class="text-white text-decoration-none"><h5 class="card-title">Quản Lý Thông Tin Bác Sĩ</h5></a>
                    <p class="card-text display-4"><?php echo $counts['doctorCount']; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <a href="./QuanLy/DSNVYT" class="text-white text-decoration-none"><h5 class="card-title">Quản Lý Thông Tin Nhân Viên Y Tế</h5></a>
                    <p class="card-text display-4"><?php echo $counts['staffCount']; ?></p>
                </div>
            </div>
        </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                    <a href="./QuanLy/ThongKe" class="text-white text-decoration-none"><h5 class="card-title">Thống Kê Hóa Đơn</h5></a>
                        <p class="card-text display-4">27</p>
                    </div>
                </div>
            </div>
        </div>

        <h2 class="mb-3">Thao tác nhanh</h2>
        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Nhân sự</h5>
                        <p class="card-text">Cập nhật thay đổi nhân sự bệnh viện.</p>
                        <a href="./QuanLy/DSBS" class="btn btn-primary">Đi đến</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Xử lý lịch làm việc</h5>
                        <p class="card-text">Xử lý yêu cầu thay đổi lịch làm việc.</p>
                        <a href="./QuanLy/LLV" class="btn btn-primary">Đi đến</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Báo cáo thống kê</h5>
                        <p class="card-text">Tổng hợp, phân tích và trình bày các số liệu thống kê hỗ trợ quản lý và đưa ra quyết định hiệu quả.</p>
                        <a href="./QuanLy/ThongKe" class="btn btn-primary">Đi đến</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
 <!-- footer -->
</body>
</html>
