<?php
if ($_SESSION["role"] != 6) {
    echo "<script>alert('Bạn không có quyền truy cập')</script>";
    header("refresh: 0; url='/KLTN_Benhvien'");
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Bệnh viện</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 font-sans">
     Header 
    <header class="bg-white shadow-sm border-b border-gray-200 fixed top-0 left-0 right-0 z-10">
        <div class="px-6 py-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-blue-600">
                    <i class="fas fa-hospital-alt mr-2"></i>
                    Hệ thống quản lý bệnh viện
                </h1>
                <div class="flex items-center gap-4">
                    <span class="text-gray-600">
                        <i class="far fa-user-circle text-xl mr-2"></i>
                        Admin
                    </span>
                </div>
            </div>
        </div>
    </header>

     Main Container 
    <div class="flex pt-16">
         Sidebar 
        <aside class="w-64 bg-white h-screen fixed left-0 shadow-lg border-r border-gray-200">
            <nav class="p-4 flex flex-col h-full">
                 Navigation Menu 
                <ul class="space-y-2 flex-1">
                    <li>
                        <a href="/KLTN_Benhvien/Admin" class="flex items-center px-4 py-3 bg-blue-50 text-blue-600 rounded-lg font-medium transition-colors hover:bg-blue-100">
                            <i class="fas fa-chart-line w-5 mr-3"></i>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="/KLTN_Benhvien/Admin/ThemNV" class="flex items-center px-4 py-3 text-gray-700 rounded-lg font-medium transition-colors hover:bg-gray-100">
                            <i class="fas fa-user-plus w-5 mr-3"></i>
                            Thêm nhân viên
                        </a>
                    </li>
                    <li>
                        <a href="/KLTN_Benhvien/Admin/ChanTruyCap" class="flex items-center px-4 py-3 text-gray-700 rounded-lg font-medium transition-colors hover:bg-gray-100">
                            <i class="fas fa-stethoscope w-5 mr-3"></i>
                            Chặn truy cập
                        </a>
                    </li>
                    <li>
                        <a href="/KLTN_Benhvien/Admin/DoiMK" class="flex items-center px-4 py-3 text-gray-700 rounded-lg font-medium transition-colors hover:bg-gray-100">
                            <i class="fas fa-key w-5 mr-3"></i>
                            Đổi mật khẩu
                        </a>
                    </li>
                </ul>

                 Logout Button 
                <div class="border-t border-gray-200 pt-4">
                    <a href="#" class="flex items-center px-4 py-3 text-red-600 rounded-lg font-medium transition-colors hover:bg-red-50">
                        <i class="fas fa-sign-out-alt w-5 mr-3"></i>
                        Đăng xuất
                    </a>
                </div>
            </nav>
        </aside>

         Main Content
        <?php
                            if(isset($data["Page"])){
                                require_once "./mvc/views/pages/".$data["Page"].".php";
                            }
                        ?>
        
    </div>
</body>
</html>