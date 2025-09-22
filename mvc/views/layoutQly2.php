<?php
    if($_SESSION["role"] != 1){
        echo "<script>alert('Bạn không có quyền truy cập')</script>";
        header("refresh: 0; url='/KLTN_Benhvien'");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Lịch làm việc</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="./public/css/main.css">
    <link rel="stylesheet" href="../public/css/main.css">
    <style>
        .schedule-grid {
            display: flex;
            border: 1px solid #dee2e6;
            border-radius: 15px;
            overflow: hidden;
            background: white;
            height: 600px;
            box-shadow: 2px 4px 6px rgba(0, 0, 0, 0.1);
        }

        .add-doctor-btn:hover {
            background-color: #f8f9fa;
        }

        body {
            background-color: #f0f0f0;
        }

        .main-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        .delete-btn {
            color: #dc3545;
            background: none;
            border: none;
            padding: 4px 8px;
            border-radius: 4px;
        }

        .delete-btn:hover {
            background-color: #fee2e2;
        }

        tr th {
            text-align: center;
            width: 100px;
        }
        td{
            font-size: 13px;
            padding: 5px;
            /* font-weight: bold; */
        }
        .ca{
            font-weight: bold;
            font-size: 15px;
        }
        #a{
            background: #f0f0f0;
        }
        .shift{
            width: 50px;
        }
    </style>
</head>
<body>
    <?php include "blocks/header.php" ?>
    <div class="main-container">
        <?php require_once "./mvc/views/pages/".$data["Page"].".php" ?>
    </div>
<?php require_once "./mvc/views/blocks/footer.php" ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

