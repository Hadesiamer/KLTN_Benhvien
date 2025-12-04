<?php
    error_reporting(0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./public/css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <!-- Thông báo đồ án: chỉ là sản phẩm khóa luận -->
    <div id="alert-doan" class="alert alert-warning alert-dismissible fade show text-center"
         role="alert"
         style="font-size: 16px; font-weight: 500; margin-bottom: 0;">
        ⚠️ Đây chỉ là sản phẩm đồ án. Hệ thống không chịu trách nhiệm cho bất kỳ mục đích sử dụng nào khác.
        <button type="button" class="btn-close" id="btnCloseAlert" aria-label="Close"></button>
    </div>

    <!-- header -->
    <?php include_once "./mvc/views/blocks/header.php" ?>
    <!-- content -->
    <?php include_once "./mvc/views/blocks/content.php" ?>
    <!-- footer -->
    <?php include_once "./mvc/views/blocks/footer.php" ?>

    <!-- Script xử lý đóng/thả thông báo đồ án (dùng localStorage) -->
    <!-- <script>
        // Khi DOM sẵn sàng
        window.addEventListener("DOMContentLoaded", function () {
            var alertBox = document.getElementById("alert-doan");
            var closeBtn = document.getElementById("btnCloseAlert");

            if (!alertBox || !closeBtn) {
                return; // Phòng trường hợp sau này layout thay đổi
            }

            // Nếu trước đó người dùng đã tắt, thì không hiển thị nữa
            if (localStorage.getItem("hide_doan_alert") === "1") {
                alertBox.style.display = "none";
            }

            // Bấm (x) để tắt và ghi nhớ
            closeBtn.addEventListener("click", function () {
                alertBox.style.display = "none";
                localStorage.setItem("hide_doan_alert", "1");
            });
        });
    </script> -->

    <!-- Script cuộn danh sách bác sĩ / bệnh viện / phòng khám -->
    <script>
    // Hàm thiết lập cuộn cho một danh sách dựa trên id của danh sách và các nút cuộn
    function setupScroll(listId, scrollLeftButtonId, scrollRightButtonId) {
        const list = document.getElementById(listId);
        const scrollLeftButton = document.getElementById(scrollLeftButtonId);
        const scrollRightButton = document.getElementById(scrollRightButtonId);

        // Nếu một trong các phần tử không tồn tại (ở một số trang không dùng), thì bỏ qua để tránh lỗi
        if (!list || !scrollLeftButton || !scrollRightButton) {
            return;
        }

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
    // hh
    </script>
    <?php
        // Gọi widget chatbot AI bệnh viện
        require_once "./mvc/views/blocks/chatbot_widget.php";
    ?>
</body>
</html>
