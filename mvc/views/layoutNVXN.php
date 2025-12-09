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
    <title>NVX Dashboard nè --- Bệnh viện</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 font-sans">
     <!-- Header -->
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
                        Nhân viên xét nghiệm
                    </span>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <div class="flex pt-16">
        <!-- Sidebar -->
        <aside class="w-64 bg-white h-screen fixed left-0 shadow-lg border-r border-gray-200">
            <nav class="p-4 flex flex-col h-full">
                <!-- Navigation Menu -->
                <ul class="space-y-2 flex-1">
                    <li>
                        <a href="/KLTN_Benhvien/NVXN" class="flex items-center px-4 py-3 bg-blue-50 text-blue-600 rounded-lg font-medium transition-colors hover:bg-blue-100">
                            <i class="fas fa-chart-line w-5 mr-3"></i>
                            Quản lý xét nghiệm
                        </a>
                    </li>
                    <li>
                        <a href="/KLTN_Benhvien/NVXN/LichLamViec" class="flex items-center px-4 py-3 text-gray-700 rounded-lg font-medium transition-colors hover:bg-gray-100">
                            <i class="fas fa-user-plus w-5 mr-3"></i>
                            Lịch làm việc
                        </a>
                    </li>
                    <li>
                        <a href="/KLTN_Benhvien/NVXN/ThongTinCaNhan" class="flex items-center px-4 py-3 text-gray-700 rounded-lg font-medium transition-colors hover:bg-gray-100">
                            <i class="fas fa-stethoscope w-5 mr-3"></i>
                            Thông tin cá nhân
                        </a>
                    </li>
                    <li>
                        <a href="/KLTN_Benhvien/NVXN/DoiMK" class="flex items-center px-4 py-3 text-gray-700 rounded-lg font-medium transition-colors hover:bg-gray-100">
                            <i class="fas fa-key w-5 mr-3"></i>
                            Đổi mật khẩu
                        </a>
                    </li>
                    <li>
                        <a href="/KLTN_Benhvien/Logout" class="flex items-center px-4 py-3 text-gray-700 rounded-lg font-medium transition-colors hover:bg-gray-100">
                            <i class="fas fa-stethoscope w-5 mr-3"></i>
                            Đăng xuất
                        </a>
                    </li>
                </ul>

                <!-- Logout Button -->
                
            </nav>
        </aside>

        <!-- Main Content -->
        <?php if (isset($data["Page"])): ?>

            <?php
            // Lấy flash message (nếu có)
            $flash = $_SESSION['flash'] ?? '';
            if ($flash !== ''):
                unset($_SESSION['flash']);
            ?>
                <!-- 🆕 Hộp thoại thông báo tự ẩn sau 3 giây -->
                <div id="flashToast" class="fixed top-20 right-6 z-50 animate-fade-in">
                    <div class="bg-blue-600 text-white px-4 py-3 rounded-lg shadow-lg flex items-center gap-2">
                        <i class="fa-solid fa-circle-info"></i>
                        <span><?= htmlspecialchars($flash) ?></span>
                    </div>
                </div>

                <script>
                // Tự động ẩn sau 3 giây
                setTimeout(() => {
                    const toast = document.getElementById('flashToast');
                    if (toast) {
                        toast.classList.add('opacity-0', 'transition', 'duration-700');
                        setTimeout(() => toast.remove(), 700);
                    }
                }, 3000);
                </script>
            <?php endif; ?>

            <?php
                require_once "./mvc/views/pages/" . $data["Page"] . ".php";
            ?>
        <?php endif; ?>
    </div>

    <style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fade-in 0.5s ease-out;
    }
    </style>
</body>
</html>
