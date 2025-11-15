<!-- mvc/views/layout1.php -->
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trang bệnh viện</title>
    <link rel="stylesheet" href="/KLTN_Benhvien/public/css/main.css">
</head>
<body>

<?php include_once "./mvc/views/blocks/header.php" ?>

<main>
    <?php
        if (isset($data["Page"])) {
            require_once "./mvc/views/pages/".$data["Page"].".php";
        }
    ?>
</main>

<?php include_once "./mvc/views/blocks/footer.php" ?>
</body>
</html>
