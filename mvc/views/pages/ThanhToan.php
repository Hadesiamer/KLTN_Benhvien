<?php
$lichKhamData = json_decode($data["LK"], true);
?>

<link rel="stylesheet" href="./public/css/thanhtoan.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<div class="col-12">
    <?php if (isset($data["Message"]) && $data["Message"] != ""): ?>
        <div id="alert-message" 
             class="alert <?= $data["MessageType"] === 'error' ? 'alert-danger' : 'alert-success'; ?>" 
             role="alert">
            <?= $data["Message"]; ?>
        </div>
    <?php endif; ?>
</div>

<h2 class="mt-3">Thanh toán</h2>

<div class="row mt-3">
    <!-- DANH SÁCH LỊCH KHÁM BÊN TRÁI -->
    <div class="col-4">
        <div class="list-group">
        <?php if (isset($lichKhamData) && !empty($lichKhamData)): ?>
            <?php foreach ($lichKhamData as $lichKham): ?>
                <?php 
                    // Định dạng ngày khám dạng dd-mm-yyyy
                    $ngayKhamFormatted = '';
                    if (!empty($lichKham['NgayKham'])) {
                        $ngayKhamFormatted = date('d-m-Y', strtotime($lichKham['NgayKham']));
                    }
                ?>
                <form method="POST" action="/KLTN_Benhvien/ThanhToan">
                    <input type="hidden" name="MaLK" value="<?= htmlspecialchars($lichKham['MaLK']); ?>">
                    <div class="patient-item list-group-item" onclick="this.closest('form').submit()">
                        <p class="mb-1" style="font-size: 16px; font-weight: 600;">
                            BS. <?= htmlspecialchars($lichKham['HovaTenNV']); ?>
                        </p>
                        <p class="mb-1" style="font-size: 13px; text-align: left;">
                            <?= htmlspecialchars($ngayKhamFormatted); ?> - <?= htmlspecialchars($lichKham['GioKham']); ?>
                        </p>
                        <p class="mb-1" style="font-size: 13px; text-align: left;">
                            <?= htmlspecialchars($lichKham['HovaTen']); ?>
                        </p>
                        <p class="mb-0" style="font-size: 13px; text-align: left; color:#555;">
                            Mã LK: <?= htmlspecialchars($lichKham['MaLK']); ?>
                        </p>
                    </div>
                </form>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Không có lịch khám nào được đặt!</p>
        <?php endif; ?>
        </div>
    </div>

    <!-- CHI TIẾT LỊCH KHÁM BÊN PHẢI -->
    <div class="col-8">
        <?php
        // CTLK có thể là JSON hoặc array, chuẩn hóa về JSON rồi decode
        if (is_array($data["CTLK"])) {
            $data["CTLK"] = json_encode($data["CTLK"]);
        }
        $chiTietData = json_decode($data["CTLK"], true);
        ?>

        <?php if (isset($chiTietData) && !empty($chiTietData)): ?>
            <?php 
                // Lấy 1 dòng đầu tiên để hiển thị gọn (các dòng khác thường trùng thông tin)
                $ct = $chiTietData[0]; 

                // Định dạng ngày khám dạng dd-mm-yyyy cho phần "Ngày - giờ"
                $ngayKhamChiTietFormatted = '';
                if (!empty($ct['NgayKham'])) {
                    $ngayKhamChiTietFormatted = date('d-m-Y', strtotime($ct['NgayKham']));
                }
            ?>

            <div class="card mb-3 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><strong>Lịch khám cần thanh toán</strong></span>
                    <span class="badge bg-primary">Mã LK: <?= htmlspecialchars($ct['MaLK']); ?></span>
                </div>
                <div class="card-body p-3">
                    <!-- THÔNG TIN ĐẶT KHÁM NGẮN GỌN -->
                    <div class="mb-2">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div style="font-size:14px;">
                                    <strong>Ngày - giờ khám:</strong> 
                                    <?= htmlspecialchars($ngayKhamChiTietFormatted); ?>, 
                                    <?= htmlspecialchars($ct['GioKham']); ?>
                                </div>
                                <div style="font-size:14px;">
                                    <strong>Chuyên khoa:</strong> <?= htmlspecialchars($ct['TenKhoa']); ?>
                                </div>
                            </div>
                            <div class="text-end">
                                <div style="font-size:14px;">
                                    <!-- <strong>STT:</strong> <?= htmlspecialchars($ct['STT']); ?> -->
                                </div>
                                <div style="font-size:14px;">
                                    <strong>Trạng thái:</strong> 
                                    <span class="badge bg-warning text-dark">
                                        <?= htmlspecialchars($ct['TrangThaiThanhToan']); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="my-2">

                    <!-- THÔNG TIN BỆNH NHÂN GỌN -->
                    <div class="row mb-2" style="font-size:14px;">
                        <div class="col-6">
                            <p class="mb-1"><strong>Bệnh nhân:</strong> <?= htmlspecialchars($ct['HovaTen']); ?></p>
                            <p class="mb-1"><strong>Mã Bệnh Nhân:</strong> <?= htmlspecialchars($ct['MaBN']); ?></p>
                            <p class="mb-1"><strong>SĐT:</strong> <?= htmlspecialchars($ct['SoDT']); ?></p>
                        </div>
                        <div class="col-6">
                            <p class="mb-1"><strong>Năm sinh:</strong> <?= htmlspecialchars($ct['NgaySinh']); ?></p>
                            <p class="mb-1"><strong>Giới tính:</strong> <?= htmlspecialchars($ct['GioiTinh']); ?></p>
                            <p class="mb-1"><strong>BHYT:</strong> <?= htmlspecialchars($ct['BHYT']); ?></p>
                        </div>
                    </div>

                    <hr class="my-2">

                    <!-- PHƯƠNG THỨC THANH TOÁN -->
                    <div class="mb-2">
                        <label for="paymentMethod" class="form-label mb-1"><strong>Phương thức thanh toán</strong></label>
                        <select id="paymentMethod" class="form-control form-control-sm">
                            <option value="" selected>--Chọn phương thức thanh toán tại đây--</option>
                            <option value="cash">Tiền mặt</option>
                            <option value="bank">Ngân hàng</option> 
                        </select>
                    </div>
                </div>
            </div>

            <!-- GHI CHÚ + NÚT HÀNH ĐỘNG -->
            <div class="button-area">
                <p style="color: #007bff; font-size: 0.9em; margin-top: 5px;">
                    * Nếu bạn muốn thay đổi lịch khám, vui lòng hủy lịch này và đăng ký lại lịch mới.<br>
                    * Lệ phí thanh toán là 10,000 VND cho mỗi lần khám. (Demo) <br>
                    * Sau 5 phút, nếu không thanh toán, lịch khám sẽ tự động bị hủy.
                </p>
                <div class="d-flex gap-2 mt-2">
                    <button type="button" 
                            class="btn btn-primary btn-sm"  
                            id="btnPay" 
                            disabled 
                            data-bs-toggle="modal" 
                            data-bs-target="#paymentModal">
                        Thanh toán lịch khám
                    </button>
                    
                    <form method="POST" action="" style="display: inline-block;" id="cancelForm"> 
                        <input type="hidden" name="MaLK_Huy" value="<?= htmlspecialchars($ct['MaLK']); ?>" id="MaLK_Huy_Input">
                        <button type="button" 
                                class="btn btn-danger btn-sm" 
                                onclick="confirmCancel()">
                            Hủy lịch khám
                        </button>
                    </form>
                </div>
            </div>

        <?php else: ?>
            <p>Vui lòng chọn lịch khám bạn muốn thanh toán!</p>
        <?php endif; ?>
    </div>
</div>

<!-- MODAL HƯỚNG DẪN THANH TOÁN -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Hướng dẫn thanh toán</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="paymentInstructions">
            
            </div>
            <div class="modal-footer">
            <?php if (!empty($chiTietData)): ?>
                <?php $ctFooter = $chiTietData[0]; ?>
                <form action="/KLTN_Benhvien/ThanhToan" method="POST">
                    <input type="hidden" name="MaBN1" value="<?= htmlspecialchars($ctFooter['MaBN']); ?>">
                    <input type="hidden" name="ThanhToanLK" value="<?= htmlspecialchars($ctFooter['MaLK']); ?>"> 
                    <button type="submit" class="btn btn-success" name="thanhtoan">Xác nhận thanh toán</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </form>
            <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    // Hàm xác nhận và submit form Hủy
    function confirmCancel() {
        const maLK = document.getElementById('MaLK_Huy_Input').value; 
        if (confirm("Bạn có chắc chắn muốn hủy lịch khám Mã số " + maLK + " không?")) {
            document.getElementById('cancelForm').submit();
        }
    }
    
    document.addEventListener("DOMContentLoaded", function () {
        // Ẩn alert message sau 2s
        <?php if (isset($data["Message"]) && $data["Message"] != ""): ?>
            setTimeout(() => {
                const alertMessage = document.getElementById("alert-message");
                if (alertMessage) {
                    alertMessage.style.transition = "opacity 0.5s ease";
                    alertMessage.style.opacity = "0";
                    setTimeout(() => alertMessage.remove(), 500); 
                }
            }, 2000); 
        <?php endif; ?>

        const paymentMethod = document.getElementById("paymentMethod");
        const btnPay = document.getElementById("btnPay");
        const paymentInstructions = document.getElementById("paymentInstructions");

        if (paymentMethod) {
            paymentMethod.addEventListener("change", function () {
                if (this.value) {
                    btnPay.disabled = false; 
                } else {
                    btnPay.disabled = true; 
                }
            });
        }

        if (btnPay) {
            btnPay.addEventListener("click", function () {
                const selectedMethod = paymentMethod.value;
                if (selectedMethod === "cash") {
                    paymentInstructions.innerHTML = `
                        <p>Vui lòng đến quầy để được hướng dẫn thực hiện thanh toán. Sau khi nhận thanh toán, nhân viên sẽ tiến hành xác nhận lịch hẹn trên hệ thống.</p>
                    `;
                } else if (selectedMethod === "bank") {
                    paymentInstructions.innerHTML = `
                        <p>Vui lòng quét mã QR sau để thực hiện thanh toán:</p>
                        <img src="./public/img/DOMDOM_qrcode.png" alt="QR Code" style="width: 100%; max-width: 300px; display: block; margin: 0 auto;">
                        <p style="text-align: center; margin-top: 10px; font-weight: bold;">Sau khi thanh toán qua Ngân hàng, vui lòng nhấn 'Xác nhận thanh toán' bên dưới.</p>
                    `;
                }
            });
        }
    });
</script>

<?php
if (isset($data['rs'])) {
    if ($data["rs"] == 'true') {
        echo '<script language="javascript">
                alert("Hoàn tất");  
              </script>';
    } else {
        echo '<script language="javascript">
                alert("Thất bại");  
              </script>';
    }
}
?>
