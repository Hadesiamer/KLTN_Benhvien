<?php
    if($_SESSION["role"] != 4){
        echo "<script>alert('Bạn không có quyền truy cập')</script>";
        header("refresh: 0; url='/KLTN_Benhvien'");
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./public/css/nvyt.css">
    <link rel="stylesheet" href="./public/css/main.css">
    <link rel="stylesheet" href="../public/css/nvyt.css">
    <link rel="stylesheet" href="../public/css/main.css">
    <title>Document</title>
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
                            <h5 class="card-title mb-3" >Chức năng</h5>
                            <div class="list-group">
                                <a href="/KLTN_Benhvien/NVNT"><button class="tab_btn active" id="a">Xử lý đơn thuốc</button></a>
                                <!-- <button class="tab_btn" id="a">Chi tiết đơn thuốc</button> -->
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
    </div>




    <script>
    function initializeTabs(tabClass, contentClass) {
        const tabs = document.querySelectorAll(tabClass);
        const allContent = document.querySelectorAll(contentClass);

        tabs.forEach((tab, index) => {
            tab.addEventListener('click', () => {
                tabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');

                allContent.forEach(content => content.classList.remove('active'));
                allContent[index].classList.add('active');
            });
        });
    }

    // Initialize tabs for each section
    initializeTabs('#a', '#a1');
    </script>
</body>

</html>