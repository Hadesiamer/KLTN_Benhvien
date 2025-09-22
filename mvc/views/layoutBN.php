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
    <title>Document</title>
    <link rel="stylesheet" href="./public/css/main.css">
    <link rel="stylesheet" href="../public/css/main.css">
    <link rel="stylesheet" href="../public/css/bn.css">
    <link rel="stylesheet" href="./public/css/bn.css">
    <style>
        
    </style>
</head>
<body>
    <?php require "./mvc/views/blocks/header.php";?>
    <div class="container mt-5">
        <div class="row">

            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-2 sidebar">
                        <ul>
                            <li><a href="/KLTN_Benhvien/LichKham">Lịch khám</a></li>
                            <li><a href="/KLTN_Benhvien/LichXetNghiem">Lịch xét nghiệm</a></li>
                            <li><a href="/KLTN_Benhvien/ThanhToan">Thanh toán</a></li>
                            <li><a href="/KLTN_Benhvien/BN/TTBN">Hồ sơ cá nhân</a></li>
                            <li ><a href="/KLTN_Benhvien/XemPhieuKham">Hồ sơ phiếu khám</a></li>
                        </ul>
                    </div>
                    <div class="col-md-10">
                        <div class="row">
                        <?php
                            if(isset($data["Page"])){
                                require_once "./mvc/views/pages/".$data["Page"].".php";
                            }
                        ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php require "./mvc/views/blocks/footer.php";?>
</body>
</html>