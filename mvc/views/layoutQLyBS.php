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
    <title>Thông tin nhân viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./public/css/main.css">
    <link rel="stylesheet" href="../public/css/doctor.css">
    <link rel="stylesheet" href="../public/css/main.css">
</head>
<body>
<?php include "blocks/header.php" ?>

<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">THÔNG TIN QUẢN LÝ</h2>
                    <div class="d-grid gap-3">
                    <a href="./DSBS" class="btn <?php echo ($data['Page'] == 'qlbs') ? 'btn-primary' : 'btn-secondary'; ?>" id="btnDSBS">Thông tin bác sĩ</a>
                    <a href="./DSNVYT" class="btn <?php echo ($data['Page'] == 'qlnvyt') ? 'btn-primary' : 'btn-secondary'; ?>" id="btnDSNVYT">Thông tin nhân viên y tế</a>
                    <a href="./DSNVNT" class="btn <?php echo ($data['Page'] == 'qlnvnt') ? 'btn-primary' : 'btn-secondary'; ?>" id="btnDSNVNT">Thông tin nhân viên nhà thuốc</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
           <h1 class="display-5 text-white fw-bold" 
                style="
                    text-shadow: 2px 2px 4px #000;
                    background-color: #3498db;
                    padding: 20px;
                    border-radius: 10px;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); ">
                    Quản lý Nhân Viên
            </h1>
            <?php if (isset($data["Message"])): ?>
            <div class="alert alert-success"><?= $data["Message"] ?></div>
            <?php endif; ?>
            <?php if (isset($data["Error"])): ?>
            <div class="alert alert-danger"><?= $data["Error"] ?></div>
            <?php endif; ?>
            <?php require_once "./mvc/views/pages/".$data["Page"].".php" ?>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

