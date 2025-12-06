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
// Nếu URL có dạng: /KLTN_Benhvien/BN/LichKham?payment_success=1&MaLK=193
$paymentToast = false;
$paymentMaLK  = null;

if (isset($_GET['payment_success']) && $_GET['payment_success'] === '1') {
    $paymentToast = true;
    $paymentMaLK  = isset($_GET['MaLK']) ? $_GET['MaLK'] : null;
}

// [NEW] Cờ kiểm tra có lịch khám đã thanh toán hay không
$hasLichKham = !empty($lichKhamData);

// [NEW] Hàm format datetime an toàn (dùng lại cho nhiều chỗ)
if (!function_exists('bn_format_datetime_lk')) {
    function bn_format_datetime_lk($str, $format = 'd/m/Y') {
        if (empty($str)) return '';
        $ts = strtotime($str);
        if ($ts === false) return htmlspecialchars($str);
        return date($format, $ts);
    }
}

// [NEW] Hàm map LoaiDichVu -> text hiển thị
if (!function_exists('bn_loaidichvu_label_lk')) {
    function bn_loaidichvu_label_lk($code) {
        $code = (string)$code;
        switch ($code) {
            case '1':
                return 'Khám trong giờ';
            case '2':
                return 'Khám ngoài giờ';
            case '3':
                return 'Khám online';
            default:
                return ''; // nếu dữ liệu khác 1/2/3 thì trả rỗng, UI tự fallback
        }
    }
}
?>

<style>
    /* [NEW] Tùy chỉnh UI lịch khám giống style lịch sử thanh toán */

    .lk-main-title {
        font-size: 1.6rem;
        font-weight: 700;
        margin-top: 10px;
        margin-bottom: 15px;
    }

    /* Khung cuộn riêng cho danh sách lịch khám bên trái */
    .lichkham-scroll-container {
        max-height: 480px;
        overflow-y: auto;
        padding-right: 4px;
    }

    .lichkham-scroll-container .list-group-item {
        margin-bottom: 6px;
        border-radius: 6px;
        border: 1px solid #e0e0e0;
        transition: all 0.2s ease-in-out;
    }

    .lichkham-scroll-container .list-group-item:hover {
        background-color: #f8f9ff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    /* Lịch khám đang được chọn */
    .lichkham-active {
        background-color: #e7f1ff !important;
        border-left: 4px solid #0d6efd;
        box-shadow: 0 2px 6px rgba(13,110,253,0.25);
    }

    /* [NEW] Badge nhỏ trong list */
    .lk-small-badge {
        font-size: 11px;
        padding: 2px 6px;
        border-radius: 10px;
    }
</style>

<h2 class="lk-main-title">Lịch khám đã đặt</h2>

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
        // Log nhẹ để kiểm tra
        console.log("BN/LichKham: paymentToast = true, MaLK = <?= json_encode($paymentMaLK); ?>");

        document.addEventListener("DOMContentLoaded", function () {
            // Sau 3 giây thì fade out rồi remove
            setTimeout(function () {
                var toast = document.getElementById("bn-payment-toast");
                if (toast) {
                    toast.style.transition = "opacity 0.5s ease";
                    toast.style.opacity = "0";
                    setTimeout(function () {
                        toast.remove();
                    }, 500);
                }
            }, 3000);
        });
    </script>
<?php else: ?>
    <script>
        console.log("BN/LichKham: paymentToast = false");
    </script>
<?php endif; ?>

<?php if (!$hasLichKham): ?>
    <!-- =========================
         UI KHI CHƯA CÓ LỊCH KHÁM
    ========================== -->
    <div class="row mt-3">
        <div class="col-12">
            <div class="card border-0 shadow-sm text-center p-4 mt-2"
                 style="max-width: 640px; margin: 40px auto;">
                <div class="mb-2" style="font-size: 48px;">🩺</div>
                <h5 class="mb-2">Hiện tại bạn chưa có lịch khám nào đã thanh toán</h5>
                <p class="text-muted mb-0" style="font-size: 14px;">
                    Khi bạn hoàn tất thanh toán lịch khám, thông tin chi tiết sẽ hiển thị tại đây.
                </p>
                <a href="/KLTN_Benhvien" class="btn btn-primary mt-3 px-4">
                    Quay lại trang chủ để đặt lịch khám
                </a>
            </div>
        </div>
    </div>
<?php else: ?>

<div class="row mt-3">
    <!-- DANH SÁCH LỊCH KHÁM BÊN TRÁI -->
    <div class="col-md-4 mb-3">
        <div class="card h-100 shadow-sm"><!-- Card bao danh sách -->
            <div class="card-header">
                <strong>Danh sách lịch khám đã thanh toán</strong>
            </div>
            <div class="card-body p-2">
                <div class="lichkham-scroll-container">
                    <div class="list-group">
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

                            $maLK      = htmlspecialchars($lichKham['MaLK'] ?? '');
                            $tenBS     = htmlspecialchars($lichKham['HovaTenNV'] ?? '');
                            $tenBN     = htmlspecialchars($lichKham['HovaTen'] ?? '');
                            $gioKham   = htmlspecialchars($lichKham['GioKham'] ?? '');
                            $tenKhoa   = htmlspecialchars($lichKham['TenKhoa'] ?? '');
                            $loaiDVRaw = $lichKham['LoaiDichVu'] ?? '';
                            $loaiDV    = bn_loaidichvu_label_lk($loaiDVRaw); // [NEW] map số -> text
                            ?>
                            <form method="POST" action="/KLTN_Benhvien/BN/LichKham">
                                <input type="hidden" name="MaLK" value="<?= $maLK; ?>">
                                <div class="patient-item list-group-item <?= $isActiveClass ?>"
                                     style="cursor:pointer;"
                                     onclick="this.closest('form').submit()">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <p class="mb-1" style="font-size: 15px; font-weight: 600;">
                                                BS. <?= $tenBS; ?>
                                            </p>
                                            <p class="mb-1" style="font-size: 13px;">
                                                <?= htmlspecialchars($ngayKhamFormatted); ?> - <?= $gioKham; ?>
                                            </p>
                                            <p class="mb-1" style="font-size: 13px;">
                                                <?= $tenBN; ?>
                                            </p>
                                            <p class="mb-0" style="font-size: 12px; color:#555;">
                                                Mã LK: <strong>LK<?= $maLK; ?></strong>
                                            </p>
                                        </div>
                                        <div class="text-end" style="font-size: 11px;">
                                            <?php if ($tenKhoa !== ''): ?>
                                                <span class="lk-small-badge bg-light text-muted d-block mb-1">
                                                    <?= $tenKhoa; ?>
                                                </span>
                                            <?php endif; ?>
                                            <?php if ($loaiDV !== ''): ?>
                                                <!-- [NEW] Hiển thị LoaiDichVu đã map -->
                                                <span class="lk-small-badge bg-primary text-white d-block">
                                                    <?= htmlspecialchars($loaiDV); ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="lk-small-badge bg-success text-white d-block">
                                                    Đã thanh toán
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CHI TIẾT LỊCH KHÁM BÊN PHẢI -->
    <div class="col-md-8">
        <?php if (!empty($chiTietData)): ?>
            <?php
            // Lấy 1 bản ghi đầu tiên để hiển thị gọn
            $ct = $chiTietData[0];

            // Định dạng ngày khám & năm sinh
            $ngayKhamFormatted = '';
            if (!empty($ct['NgayKham'])) {
                $ngayKhamFormatted = bn_format_datetime_lk($ct['NgayKham'], 'd/m/Y');
            }

            $namSinhFormatted = '';
            if (!empty($ct['NgaySinh'])) {
                $namSinhFormatted = bn_format_datetime_lk($ct['NgaySinh'], 'd/m/Y');
            }

            $maLK          = htmlspecialchars($ct['MaLK'] ?? '');
            $moTaKhoa      = htmlspecialchars($ct['MoTa']      ?? '');
            $bacSi         = htmlspecialchars($ct['HovaTenNV'] ?? '');
            $tenKhoa       = htmlspecialchars($ct['TenKhoa']   ?? '');
            $trangThaiText = "Đã thanh toán"; // luôn là đã thanh toán

            // [NEW] Lấy LoaiDichVu từ chi tiết để hiển thị
            $loaiDVDetailRaw = $ct['LoaiDichVu'] ?? '';
            $loaiDVDetail    = bn_loaidichvu_label_lk($loaiDVDetailRaw);
            ?>
            <div class="card mb-3 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <span><strong>Chi tiết lịch khám đã đặt</strong></span><br>
                        <small>Mã lịch khám: <strong>LK<?= $maLK; ?></strong></small>
                    </div>
                    <div class="d-flex gap-2">
                        <!-- Nút In lịch khám -->
                        <form method="GET"
                              action="/KLTN_Benhvien/BN/InLichKham/<?= $maLK; ?>"
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
                        <table class="table table-sm mb-2">
                            <tr>
                                <th style="width: 30%;">Ngày - giờ khám</th>
                                <td><?= $ngayKhamFormatted . ' ' . htmlspecialchars($ct['GioKham'] ?? ''); ?></td>
                            </tr>
                            <tr>
                                <th>Chuyên khoa</th>
                                <td><?= $tenKhoa; ?></td>
                            </tr>
                            <tr>
                                <th>Vị trí khám bệnh</th>
                                <td><?= $moTaKhoa; ?></td>
                            </tr>
                            <?php if ($loaiDVDetail !== ''): ?>
                            <!-- [NEW] Hàng hiển thị loại dịch vụ -->
                            <tr>
                                <th>Loại dịch vụ</th>
                                <td><?= htmlspecialchars($loaiDVDetail); ?></td>
                            </tr>
                            <?php endif; ?>
                            <tr>
                                <th>Bác sĩ phụ trách</th>
                                <td>BS. <?= $bacSi; ?></td>
                            </tr>
                            <tr>
                                <th>Trạng thái</th>
                                <td>
                                    <span class="badge bg-success">
                                        <?= htmlspecialchars($trangThaiText); ?>
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <hr class="my-2">

                    <!-- THÔNG TIN BỆNH NHÂN -->
                    <div class="mb-2">
                        <div class="section-title"
                             style="font-weight:bold; text-transform:uppercase; font-size:13px;">
                            Thông tin bệnh nhân
                        </div>
                        <table class="table table-sm mb-2">
                            <tr>
                                <th style="width: 30%;">Tên bệnh nhân</th>
                                <td><?= htmlspecialchars($ct['HovaTen'] ?? ''); ?></td>
                            </tr>
                            <tr>
                                <th>Mã bệnh nhân</th>
                                <td><?= htmlspecialchars($ct['MaBN'] ?? ''); ?></td>
                            </tr>
                            <tr>
                                <th>Số điện thoại</th>
                                <td><?= htmlspecialchars($ct['SoDT'] ?? ''); ?></td>
                            </tr>
                            <tr>
                                <th>Ngày sinh</th>
                                <td><?= $namSinhFormatted; ?></td>
                            </tr>
                            <tr>
                                <th>Giới tính</th>
                                <td><?= htmlspecialchars($ct['GioiTinh'] ?? ''); ?></td>
                            </tr>
                            <tr>
                                <th>Địa chỉ</th>
                                <td><?= htmlspecialchars($ct['DiaChi']   ?? ''); ?></td>
                            </tr>
                            <tr>
                                <th>BHYT</th>
                                <td><?= htmlspecialchars($ct['BHYT']     ?? ''); ?></td>
                            </tr>
                            <tr>
                                <th>Triệu chứng</th>
                                <td><?= htmlspecialchars($ct['TrieuChung'] ?? ''); ?></td>
                            </tr>
                        </table>
                    </div>

                    <hr class="my-2">

                    <p style="font-size: 0.9em; color:#0d6efd; margin-top: 10px;">
                        Vui lòng đến đúng thời gian và vị trí khám bệnh, chúng tôi sẽ không hoàn tiền nếu bạn vắng mặt.
                    </p>
                </div>
            </div>
        <?php else: ?>
            <div class="card shadow-sm">
                <div class="card-body">
                    <p class="mb-0">Vui lòng chọn một lịch khám đã thanh toán ở danh sách bên trái để xem chi tiết.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php endif; // end if !$hasLichKham ?>
            