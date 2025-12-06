<?php
    if($_SESSION["role"] != 4){
        echo "<script>alert('Bạn không có quyền truy cập')</script>";
        header("refresh: 0; url='/KLTN_Benhvien'");
    }

    // Xác định trang hiện tại để set active cho menu
    $currentPage = isset($data["Page"]) ? $data["Page"] : "";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nhân viên nhà thuốc</title>

    <!-- CSS thuần cho menu chức năng -->
    <style>
        .list-group a {
            text-decoration: none;
            display: block;
            margin-bottom: 8px;
        }

        .tab_btn {
            width: 100%;
            padding: 10px 16px;
            border-radius: 999px;
            border: none;
            outline: none;
            cursor: pointer;

            background: linear-gradient(135deg, #0c857d, #12b3a5);
            color: #ffffff;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 0.3px;

            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;

            box-shadow: 0 3px 8px rgba(12, 133, 125, 0.35);
            transition: transform 0.15s ease, box-shadow 0.15s ease, background 0.15s ease;
        }

        .tab_btn::before {
            content: "💊";
            font-size: 16px;
        }

        .tab_btn:hover {
            background: linear-gradient(135deg, #0a6d67, #0fa293);
            box-shadow: 0 4px 10px rgba(12, 133, 125, 0.45);
            transform: translateY(-1px);
        }

        .tab_btn.active {
            background: linear-gradient(135deg, #055a54, #0a8b7f);
        }
    </style>
</head>

<body>
    <!-- header -->
    <?php include "blocks/header.php" ?>

    <div class="main">
        <div class="container mt-3 mb-3">
            <div class="row">
                <div class="col-md-3 p-3 border-end">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Chức năng</h5>
                            <div class="list-group">
                                <!-- Xử lý đơn thuốc KE_DON -->
                                <a href="/KLTN_Benhvien/NVNT">
                                    <button class="tab_btn <?php echo ($currentPage === 'donthuoc' || $currentPage === 'chitietdonthuoc') ? 'active' : ''; ?>">
                                        Xử lý đơn thuốc
                                    </button>
                                </a>

                                <!-- Bán lẻ thuốc BAN_LE -->
                                <a href="/KLTN_Benhvien/NVNT/BanLe">
                                    <button class="tab_btn <?php echo (strpos($currentPage, 'banle') !== false) ? 'active' : ''; ?>">
                                        Bán lẻ thuốc
                                    </button>
                                </a>

                                <!-- Thông tin cá nhân -->
                                <a href="/KLTN_Benhvien/NVNT/ThongTinCaNhan">
                                    <button class="tab_btn <?php echo (strpos($currentPage, 'thongtincanhan') !== false) ? 'active' : ''; ?>">
                                        Thông tin cá nhân
                                    </button>
                                </a>

                                <!-- Đổi mật khẩu -->
                                <a href="/KLTN_Benhvien/NVNT/DoiMatKhau">
                                    <button class="tab_btn <?php echo (strpos($currentPage, 'doimatkhau') !== false) ? 'active' : ''; ?>">
                                        Đổi mật khẩu
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-9 p-3">
                    <div class="card mb-4">
                        <div class="table-panel">
                            <div class="content active" id="a1">
                                <?php include "./mvc/views/pages/".$data["Page"].".php" ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
