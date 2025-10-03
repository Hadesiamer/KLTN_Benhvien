<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../public/css/main.css">
    <title>Document</title>
    <style>
        /* Styling for the login name */
        .menu {
            list-style-type: none;
            margin: 0;
            padding: 0;
            position: relative;
        }

        .menu > li {
            position: relative;
            display: inline-block;
        }

        .menu > li > a {
            display: block;
            padding: 10px 15px;
            color: #007bff;
            font-weight: bold;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .menu > li > a:hover {
            background-color: #e9ecef;
            color: #0056b3;
        }

        /* Submenu styles */
        .submenu {
            display: none;
            list-style-type: none;
            position: absolute;
            top: 100%; /* Positioned right below the login name */
            left: 0;
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            padding: 5px 0;
            z-index: 1000;
        }

        .submenu li {
            width: 200px;
        }

        .submenu li a {
            display: block;
            padding: 10px 15px;
            color: #333;
            text-decoration: none;
            font-weight: 500;
            white-space: nowrap;
            transition: background-color 0.3s;
        }

        .submenu li a:hover {
            background-color: #f8f9fa;
            color: #007bff;
        }

        /* Show the submenu when hovering over the parent menu item */
        .menu > li:hover .submenu {
            display: block;
        }

        #nutdn .nav-link {
            font-weight: bold;
            color: #007bff;
            background-color: #e9ecef;
            padding: 8px 12px;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
        }

        #nutdn .nav-link:hover {
            background-color: #d1e7ff;
            color: #0056b3;
        }
    </style>
</head>
<body>


    <div class="header">
        <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
            <div class="container-fluid">
                <a class="navbar-brand" href="" >Bệnh Viện Đức Tâm</a>
                
                <?php
                    if (isset($_SESSION['role']) && $_SESSION['role'] == 5) { // Bệnh nhân
                        $tenbn = $_SESSION['ten'];
                            echo '<div class="navbar-nav ms-auto">
                                    <a class="nav-link" href="/KLTN_Benhvien">Trang chủ</a>
                                    <a class="nav-link" href="/KLTN_Benhvien/DangKyLK">Đặt lịch khám</a>
                                    <!-- <a class="nav-link" href="/KLTN_Benhvien/BN/DKXN">Đặt lịch xét nghiệm</a> -->
                                    <a class="nav-link" href="#">Tư vấn trực tiếp</a>

                                </div>';
                                if(isset($_SESSION['idbn'])){
                                    echo '<nav>
                                    <ul class="menu">
                                        <li>
                                            <a class="nav-link" href="BN">'.$tenbn.'</a>
                                            <ul class="submenu">
                                                <li><a href="/KLTN_Benhvien/LichKham">Lịch khám</a></li>
                                                <li><a href="/KLTN_Benhvien/ThanhToan">Thanh toán</a></li>
                                                <li><a href="/KLTN_Benhvien/BN">Hồ sơ cá nhân</a></li>
                                                <li><a href="/KLTN_Benhvien/XemPhieuKham">Hồ sơ phiếu khám</a></li>
                                                <li><a href="/KLTN_Benhvien/Logout" onclick="return confirm(\'Bạn có muốn đăng xuất?\')">Đăng xuất</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                    </nav>';
                                }else{
                                    echo '<div class="navbar-nav">
                                    <a class="nav-link" href="/KLTN_Benhvien/Register/BNHS">Tạo hồ sơ</a>
                                    <a class="nav-link" href="/KLTN_Benhvien/Logout" onclick="return confirm(\'Bạn có muốn đăng xuất?\')">Đăng xuất</a>
                                    </div>';
                                }
                                
                    } else if (isset($_SESSION['role']) && $_SESSION['role'] == 4) { // Nhân viên nhà thuốc
                        $tennvnt = $_SESSION['ten'];
                        echo '<div class="navbar-nav ms-auto">
                                <a class="nav-link" href="/KLTN_Benhvien">Trang chủ</a>
                                <a class="nav-link" href="/KLTN_Benhvien/NVNT">Chức năng</a>
                                <li><a href="/KLTN_Benhvien/Logout" onclick="return confirm(\'Bạn có muốn đăng xuất?\')">Đăng xuất</a></li>

                            </div>
                            <nav>
                                <ul class="menu">
                                    <li>
                                        <a class="nav-link" href="#">' . $tennvnt . '</a>
                                        <ul class="submenu">
                                            <li><a href="/KLTN_Benhvien/Logout" onclick="return confirm(\'Bạn có muốn đăng xuất?\')">Đăng xuất</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </nav>';
                        
                    } else if (isset($_SESSION['role']) && $_SESSION['role'] == 3) { // Nhân viên y tế
                        $tennvyt = $_SESSION['ten'];
                        echo '<div class="navbar-nav ms-auto">
                                <a class="nav-link" href="/KLTN_Benhvien">Trang chủ</a>
                                <a class="nav-link" href="/KLTN_Benhvien/NVYT">Chức năng</a>
                                <li><a href="/KLTN_Benhvien/Logout" onclick="return confirm(\'Bạn có muốn đăng xuất?\')">Đăng xuất</a></li>
                            </div>
                            <nav>
                                <ul class="menu">
                                    <li>
                                        <a class="nav-link" href="#">' . $tennvyt . '</a>
                                        <ul class="submenu">
                                            
                                            <li><a href="/KLTN_Benhvien/Logout" onclick="return confirm(\'Bạn có muốn đăng xuất?\')">Đăng xuất</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </nav>';
                        
                    } else if (isset($_SESSION['role']) && $_SESSION['role'] == 2) { // Bác sĩ
                        $tenbs = $_SESSION['ten'];
                        echo '<div class="navbar-nav ms-auto">
                                <a class="nav-link" href="/KLTN_Benhvien/Bacsi">Chức năng</a>
                            </div>
                            <nav>
                                <ul class="menu">
                                    <li>
                                        <a class="nav-link" href="#">' . $tenbs . '</a>
                                        <ul class="submenu">
                                            <li><a href="/KLTN_Benhvien/Bacsi/ThongTinBacSi">Thông tin bác sĩ</a></li>
                                            <li><a href="/KLTN_Benhvien/Logout" onclick="return confirm(\'Bạn có muốn đăng xuất?\')">Đăng xuất</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </nav>';
                        
                    } else if (isset($_SESSION['role']) && $_SESSION['role'] == 1) { // Quản lý
                        $tenql = $_SESSION['ten'];
                        echo '<div class="navbar-nav ms-auto">
                                <a class="nav-link" href="/KLTN_Benhvien/QuanLy">Trang chủ</a>
                                <a class="nav-link" href="/KLTN_Benhvien/QuanLy/TTBN">Bệnh nhân</a>
                                <a class="nav-link" href="/KLTN_Benhvien/QuanLy/DSBS">Nhân viên</a>
                                <a class="nav-link" href="/KLTN_Benhvien/QuanLy/LLV">Lịch làm việc</a>
                                <a class="nav-link" href="/KLTN_Benhvien/QuanLy/ThongKe">Thống kê</a>
                            </div>
                            <nav>
                                <ul class="menu">
                                    <li>
                                        <a class="nav-link" href="#">' . $tenql . '</a>
                                        <ul class="submenu">
                                            <li><a href="/KLTN_Benhvien/Logout" onclick="return confirm(\'Bạn có muốn đăng xuất?\')">Đăng xuất</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </nav>';
                        
                    } else if (isset($_SESSION['role']) && $_SESSION['role'] == 6) { // Admin nè
                        $tenql = $_SESSION['ten'];
                        echo '<div class="navbar-nav ms-auto">
                                <a class="nav-link" href="/KLTN_Benhvien/Admin">Dashboard Admin</a>
                                <a class="nav-link" href="/KLTN_Benhvien/">Bệnh nhân</a>
                                <a class="nav-link" href="/KLTN_Benhvien/Admin">Nhân viên</a>
                                <a class="nav-link" href="/KLTN_Benhvien/Admin">Lịch làm việc</a>
                                <a class="nav-link" href="/KLTN_Benhvien/Admin">Thống kê</a>
                            </div>
                            <nav>
                                <ul class="menu">
                                    <li>
                                        <a class="nav-link" href="#">Admin</a>
                                        <ul class="submenu">
                                            <li><a href="/KLTN_Benhvien/Logout" onclick="return confirm(\'Bạn có muốn đăng xuất?\')">Đăng xuất</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </nav>';
                        
                    }
                    else {
                        echo '
                            <div class="navbar-nav ms-auto">
                                <a class="nav-link" href="/KLTN_Benhvien">Trang chủ</a>
                                <a class="nav-link" href="#">Tư vấn trực tiếp</a>
                                <a class="nav-link" href="#">Tin y tế</a>
                                <a class="nav-link" href="#">Về chúng tôi</a>
                            </div>
                        <div id="nutdn" class="navbar-nav"><a class="nav-link" href="Login">Đăng nhập</a></div>';
                    }
                ?>
            </div>
        </nav>
    </div>
    <div class="main">
        
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>