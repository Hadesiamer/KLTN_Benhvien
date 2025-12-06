<?php
// ĐÂY LÀ FILE: mvc/views/pages/LichKham.php

// Danh sách lịch khám (JSON) -> array
$lichKhamData = json_decode($data["LK"], true);

// Chuẩn hóa chi tiết lịch khám (JSON hoặc array)
if (is_array($data["CTLK"])) {
    $data["CTLK"] = json_encode($data["CTLK"]);
}
$chiTietData = json_decode($data["CTLK"], true);

// Lấy mã lịch khám đang được xem (nếu có)
$currentMaLK = null;
if (!empty($chiTietData) && isset($chiTietData[0]['MaLK'])) {
    $currentMaLK = $chiTietData[0]['MaLK'];
}

// ================== THÔNG TIN TOAST SAU KHI THANH TOÁN ==================
$paymentToast = false;
$paymentMaLK  = null;

if (isset($_GET['payment_success']) && $_GET['payment_success'] === '1') {
    $paymentToast = true;
    $paymentMaLK  = isset($_GET['MaLK']) ? $_GET['MaLK'] : null;
}
?>

<style>
    /* Khung cuộn riêng cho danh sách lịch khám bên trái */
    .lichkham-scroll-container {
        max-height: 472px;
        overflow-y: auto;
        padding-right: 4px;
    }

    .lichkham-scroll-container .list-group-item {
        margin-bottom: 6px;
    }

    /* Lịch khám đang được chọn */
    .lichkham-active {
        background-color: #e7f1ff !important;
        border-left: 4px solid #0d6efd;
    }
</style>

<h2 class="mt-3">Lịch khám đã đặt</h2>

<?php if ($paymentToast): ?>
    <!-- TOAST THÔNG BÁO THANH TOÁN THÀNH CÔNG -->
    <div id="bn-payment-toast"
         class="alert alert-success shadow position-fixed top-0 end-0 m-3"
         role="alert"
         style="z-index: 2000; min-width: 260px;">
        <strong>Thanh toán thành công!</strong><br>
        <?php if ($paymentMaLK): ?>
            Lịch khám mã <strong>LK<?= htmlspecialchars($paymentMaLK); ?></strong> đã được xác nhận.
        <?php else: ?>
            Lịch khám của bạn đã được xác nhận thanh toán.
        <?php endif; ?>
    </div>

    <script>
        // Log nhẹ để anh thấy có chạy
        console.log("BN/LichKham: paymentToast = true, MaLK = <?= json_encode($paymentMaLK); ?>");

        document.addEventListener("DOMContentLoaded", function () {
            setTimeout(function () {
                var toast = document.getElementById("bn-payment-toast");
                if (toast) {
                    toast.style.transition = "opacity 0.5s ease";
                    toast.style.opacity = "0";
                    setTimeout(function () {
                        toast.remove();
                    }, 500);
                }
            }, 3000); // Toast hiển thị 3s
        });
    </script>
<?php else: ?>
    <script>
        console.log("BN/LichKham: paymentToast = false");
    </script>
<?php endif; ?>

<div class="row mt-3">
    <!-- DANH SÁCH LỊCH KHÁM BÊN TRÁI -->
    <div class="col-4">
        <div class="lichkham-scroll-container">
            <div class="list-group">
                <?php if (!empty($lichKhamData)): ?>
                    <?php foreach ($lichKhamData as $lichKham): ?>
                        <?php
                        // Định dạng ngày khám dd-mm-yyyy
                        $ngayKhamFormatted = '';
                        if (!empty($lichKham['NgayKham'])) {
                            $ngayKhamFormatted = date('d-m-Y', strtotime($lichKham['NgayKham']));
                        }

                        // Kiểm tra có phải lịch đang xem không
                        $isActiveClass = '';
                        if ($currentMaLK !== null && isset($lichKham['MaLK']) && $currentMaLK == $lichKham['MaLK']) {
                            $isActiveClass = 'lichkham-active';
                        }
                        ?>
                        <form method="POST" action="/KLTN_Benhvien/BN/LichKham">
                            <input type="hidden" name="MaLK" value="<?= htmlspecialchars($lichKham['MaLK']); ?>">
                            <div class="patient-item list-group-item <?= $isActiveClass ?>"
                                 style="cursor:pointer;"
                                 onclick="this.closest('form').submit()">
                                <p class="mb-1" style="font-size: 16px; font-weight: 600;">
                                    BS. <?= htmlspecialchars($lichKham['HovaTenNV'] ?? ''); ?>
                                </p>
                                <p class="mb-1" style="font-size: 13px; text-align: left;">
                                    <?= htmlspecialchars($ngayKhamFormatted); ?>
                                    -
                                    <?= htmlspecialchars($lichKham['GioKham'] ?? ''); ?>
                                </p>
                                <p class="mb-1" style="font-size: 13px; text-align: left;">
                                    <?= htmlspecialchars($lichKham['HovaTen'] ?? ''); ?>
                                </p>
                                <p class="mb-0" style="font-size: 13px; text-align: left; color:#555;">
                                    Mã LK: <?= htmlspecialchars($lichKham['MaLK'] ?? ''); ?>
                                </p>
                            </div>
                        </form>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Hiện tại bạn chưa có lịch khám nào đã thanh toán.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- CHI TIẾT LỊCH KHÁM BÊN PHẢI -->
    <div class="col-8">
        <?php if (!empty($chiTietData)): ?>
            <?php
            // Lấy 1 bản ghi đầu tiên để hiển thị gọn
            $ct = $chiTietData[0];

            // Định dạng ngày khám
            $ngayKhamFormatted = '';
            if (!empty($ct['NgayKham'])) {
                $ngayKhamFormatted = date('d-m-Y', strtotime($ct['NgayKham']));
            }

            // Năm sinh
            $namSinhFormatted = '';
            if (!empty($ct['NgaySinh'])) {
                $namSinhFormatted = date('d-m-Y', strtotime($ct['NgaySinh']));
            }

            $moTaKhoa   = $ct['MoTa']      ?? '';
            $bacSi      = $ct['HovaTenNV'] ?? '';
            $trangThaiText = "Đã thanh toán"; // luôn là đã thanh toán
            ?>
            <div class="card mb-3 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <span><strong>Chi tiết lịch khám đã đặt</strong></span><br>
                        <small>Mã LK: <?= htmlspecialchars($ct['MaLK'] ?? ''); ?></small>
                    </div>
                    <div class="d-flex gap-2">
                        <!-- Nút In lịch khám -->
                        <form method="GET"
                              action="/KLTN_Benhvien/BN/InLichKham/<?= htmlspecialchars($ct['MaLK'] ?? ''); ?>"
                              target="_blank"
                              class="d-inline">
                            <button type="submit" class="btn btn-outline-secondary btn-sm">
                                In lịch khám
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body p-3" style="font-size:14px;">
                    <!-- THÔNG TIN KHÁM -->
                    <div class="mb-2">
                        <div class="section-title"
                             style="font-weight:bold; text-transform:uppercase; font-size:13px;">
                            Thông tin khám bệnh
                        </div>
                        <div><strong>Ngày - giờ khám:</strong>
                            <?= htmlspecialchars($ngayKhamFormatted); ?>
                            <?= htmlspecialchars($ct['GioKham'] ?? ''); ?>
                        </div>
                        <div><strong>Chuyên khoa:</strong> <?= htmlspecialchars($ct['TenKhoa'] ?? ''); ?></div>
                        <div><strong>Vị trí khám bệnh:</strong> <?= htmlspecialchars($moTaKhoa); ?></div>
                        <div><strong>Bác sĩ phụ trách:</strong> BS. <?= htmlspecialchars($bacSi); ?></div>
                        <div>
                            <strong>Trạng thái:</strong>
                            <span class="badge bg-success">
                                <?= htmlspecialchars($trangThaiText); ?>
                            </span>
                        </div>
                    </div>

                    <hr class="my-2">

                    <!-- THÔNG TIN BỆNH NHÂN -->
                    <div class="mb-2">
                        <div class="section-title"
                             style="font-weight:bold; text-transform:uppercase; font-size:13px;">
                            Thông tin bệnh nhân
                        </div>
                        <div><strong>Tên bệnh nhân:</strong> <?= htmlspecialchars($ct['HovaTen'] ?? ''); ?></div>
                        <div><strong>Mã bệnh nhân:</strong> <?= htmlspecialchars($ct['MaBN'] ?? ''); ?></div>
                        <div><strong>Số điện thoại:</strong> <?= htmlspecialchars($ct['SoDT'] ?? ''); ?></div>
                        <div><strong>Năm sinh:</strong> <?= htmlspecialchars($namSinhFormatted); ?></div>
                        <div><strong>Giới tính:</strong> <?= htmlspecialchars($ct['GioiTinh'] ?? ''); ?></div>
                        <div><strong>Địa chỉ:</strong> <?= htmlspecialchars($ct['DiaChi'] ?? ''); ?></div>
                        <div><strong>BHYT:</strong> <?= htmlspecialchars($ct['BHYT'] ?? ''); ?></div>
                        <div><strong>Triệu chứng:</strong> <?= htmlspecialchars($ct['TrieuChung'] ?? ''); ?></div>
                    </div>

                    <hr class="my-2">

                    <p style="font-size: 0.9em; color:#0d6efd; margin-top: 10px;">
                        Vui lòng đến đúng thời gian và vị trí khám bệnh, chúng tôi sẽ không hoàn tiền nếu bạn vắng mặt.
                    </p>
                </div>
            </div>
        <?php else: ?>
            <p>Vui lòng chọn một lịch khám đã thanh toán ở danh sách bên trái để xem chi tiết.</p>
        <?php endif; ?>
    </div>
</div>
