<?php
    error_reporting(0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./public/css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <!-- header -->
    <?php include_once "./mvc/views/blocks/header.php" ?>
    <!-- content -->
    <?php include_once "./mvc/views/blocks/content.php" ?>
    <!-- footer -->
    <?php include_once "./mvc/views/blocks/footer.php" ?>
</body>
</html>
<script>
// Hàm thiết lập cuộn cho một danh sách dựa trên id của danh sách và các nút cuộn
function setupScroll(listId, scrollLeftButtonId, scrollRightButtonId) {
    const list = document.getElementById(listId);
    const scrollLeftButton = document.getElementById(scrollLeftButtonId);
    const scrollRightButton = document.getElementById(scrollRightButtonId);

    scrollLeftButton.addEventListener('click', () => {
        if (list) {
            list.scrollBy({
                left: -200,
                behavior: 'smooth'
            });
        }
    });

    scrollRightButton.addEventListener('click', () => {
        if (list) {
            list.scrollBy({
                left: 200,
                behavior: 'smooth'
            });
        }
    });
}

// Áp dụng hàm cuộn riêng cho từng danh sách
setupScroll('doctorList', 'scrollLeftDoctor', 'scrollRightDoctor');
setupScroll('hopitalList', 'scrollLeftHopital', 'scrollRightHopital');
setupScroll('pkList','scrollLeftPK','scrollRightPK');

</script>
