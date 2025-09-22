<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./public/css/main.css">
    <link rel="stylesheet" href="../public/css/main.css">
    <link rel="stylesheet" href="./public/css/khachhang.css">
    <link rel="stylesheet" href="../public/css/khachhang.css">
    <link rel="stylesheet" href="./public/css/layoutDKLK.css">
    <link rel="stylesheet" href="../public/css/layoutDKLK.css">

</head>
<body>
    <?php require "./mvc/views/blocks/header.php";?>
    <div class="container mt-5">
        <div class="row">
            <div class="container-fluid">
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
    <?php require "./mvc/views/blocks/footer.php";?>
</body>
</html>