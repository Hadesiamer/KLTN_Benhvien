<?php
    // if(isset($_SESSION["role"])){
    //     header("refresh: 0; url='/KLTN_Benhvien'");
    // }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./public/css/main.css">
    <style>
        .login-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            margin: 50px auto;
            max-width: 1200px;
        }
        .login-image {
            width: 80%;
            background-image: url('./public/img/bannerlogin.jpg');
            background-size: cover;
            background-position: center;
            border-radius: 8px;
            min-height: 400px;
        }
        .login-form-container {
            margin-left: 150px;
            width: 40%;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <!-- header -->
    <?php include "blocks/header.php" ?>
    <div class="main">
    <div class="container login-container">
        <div class="login-image">
            <a href="./public/img/bannerlogin.jpg"></a>
        </div>

        <div class="login-form-container">
            <ul class="nav nav-tabs" id="login-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="login-tab" href="Login">Đăng nhập</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " id="register-tab" href="Register">Đăng ký</a>
                </li>
            </ul>
            <div class="tab-content mt-3">
                <div class="tab-pane fade show active" id="login" role="tabpanel">
                    <form method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label">Số điện thoại</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Nhập số điện thoại">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu</label>
                            <input type="password" class="form-control" id="pass" name="pass" placeholder="Nhập mật khẩu">
                        </div>
                        <button type="submit" class="btn btn-primary w-100" name="nut" id="nut">Đăng nhập</button>
                    </form>
                    <p class="mt-3 text-center">Chưa có tài khoản? <a href="Register">Đăng ký ngay</a></p>
                    
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- footer -->
    <?php require_once "./mvc/views/blocks/footer.php" ?>
</body>
</html>