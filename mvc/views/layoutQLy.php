<?php
    if($_SESSION["role"] != 1){
        echo "<script>alert('Bạn không có quyền truy cập')</script>";
        header("refresh: 0; url='/KLTN_Benhvien'");
    }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin bệnh nhân</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="./public/css/main.css">
    <link rel="stylesheet" href="../public/css/main.css">
    <link rel="stylesheet" href="./public/css/QLPK.css">
    <link rel="stylesheet" href="../public/css/QLPK.css">
    <style>
        .btn-back{
            border: none; 
            padding: 5px 20px; 
            text-align: center;
            text-decoration: none; 
            display: inline-block; 
            font-size: 16px; 
            border-radius: 5px; 
            transition: background-color 0.3s ease; 
            cursor: pointer; 
            color: #9C9C9C;
        }

        .btn-back:hover {
        background-color: #CFCFCF; 
        }
    </style>
</head>
<body>
<?php include "blocks/header.php" ?>
<div class="main">
        <div class="container mt-4">
        <?php require_once "./mvc/views/pages/".$data["Page"].".php" ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>