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
    <div class="col-4">
        <div class="list-group">
        <?php if (isset($lichKhamData) && !empty($lichKhamData)): ?>
            <?php foreach ($lichKhamData as $lichKham): ?>
                <form method="POST" action="/KLTN_Benhvien/ThanhToan">
                    <input type="hidden" name="MaLK" value="<?= $lichKham['MaLK']; ?>">
                    <div class="patient-item list-group-item" onclick="this.closest('form').submit()">
                        <p style="font-size: 18px;">
                            BS. <?= $lichKham['HovaTenNV']; ?>
                        </p>
                        <p style="font-size: 14px; text-align: left;">
                            <?= $lichKham['NgayKham']; ?> - <?= $lichKham['GioKham']; ?>
                        </p>
                        <p style="font-size: 14px; text-align: left;">
                            <?= $lichKham['HovaTen']; ?>
                        </p>
                        <p style="font-size: 14px; text-align: left;">
                            Mã số - <?= $lichKham['MaLK']; ?>
                        </p>
                    </div>
                </form>
            <?php endforeach; ?>
            <?php else: ?>
            <p>Không có lịch khám nào được đặt!</p>
        <?php endif; ?>
        </div>
    </div>

    <div class="col-8">
    <?php
    if (is_array($data["CTLK"])) {
        $data["CTLK"] = json_encode($data["CTLK"]);
    }
    $chiTietData = json_decode($data["CTLK"], true);
    ?>
    <?php if (isset($chiTietData) && !empty($chiTietData)): ?>
        <div class="chi-tiet-lich-kham">
            <?php foreach ($chiTietData as $ct): ?>
                <p><strong>STT - <?= $ct['STT']; ?></strong> </p> <hr>
                <h5>Thông tin đặt khám</h5> <hr>
                <p><strong>Mã lịch khám:</strong> <?= $ct['MaLK']; ?></p>
                <p><strong>Ngày khám:</strong> <?= $ct['NgayKham']; ?></p>
                <p><strong>Giờ khám:</strong> <?= $ct['GioKham']; ?></p>
                <p><strong>Chuyên khoa:</strong> <?= $ct['TenKhoa']; ?></p>

                <h5>Thông tin bệnh nhân</h5> <hr>
                <p><strong>Mã bệnh nhân:</strong> <?= $ct['MaBN']; ?></p>
                <p><strong>Họ tên:</strong> <?= $ct['HovaTen']; ?></p>
                <p><strong>Năm sinh:</strong> <?= $ct['NgaySinh']; ?></p>
                <p><strong>Số điện thoại:</strong> <?= $ct['SoDT']; ?></p>
                <p><strong>Giới tính:</strong> <?= $ct['GioiTinh']; ?></p>
                <p><strong>Mã BHYT:</strong> <?= $ct['BHYT']; ?></p>
                <p><strong>Trạng thái:</strong> <?= $ct['TrangThaiThanhToan']; ?></p>
                <h5>Phương thức thanh toán</h5> 
                <div class="form-group">
                    <select id="paymentMethod" class="form-control">
                        <option value="" selected>--Chọn phương thức thanh toán tại đây--</option>
                        <option value="cash">Tiền mặt</option>
                        <option value="bank">Ngân hàng</option> 
                    </select>
                </div>
            <?php endforeach; ?>
        </div>
        <br>
      
        <div class="button">
        
            <button type="button" class="btn btn-change"  id="btnPay" disabled data-bs-toggle="modal" data-bs-target="#paymentModal">
                Thanh toán lịch khám
            </button>
            
        </div>

    <?php else: ?>
        <p>Vui lòng chọn lịch khám bạn muốn thanh toán!</p>
    <?php endif; ?>
</div>

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
                    <?php foreach ($chiTietData as $ct): ?>
                        <form action="/KLTN_Benhvien/ThanhToan" method="POST">
                        <input type="hidden" name="MaBN1" value="<?= $ct['MaBN'];?>">
                        <button type="submit" class="btn btn-secondary" name="thanhtoan" data-bs-dismiss="modal">Đóng</button>
                        </form>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>


        <script>
            document.addEventListener("DOMContentLoaded", function () {
            const paymentMethod = document.getElementById("paymentMethod");
            const btnPay = document.getElementById("btnPay");
            const paymentInstructions = document.getElementById("paymentInstructions");

           
            paymentMethod.addEventListener("change", function () {
                if (this.value) {
                    btnPay.disabled = false; 
                } else {
                    btnPay.disabled = true; 
                }
            });

            btnPay.addEventListener("click", function () {
                const selectedMethod = paymentMethod.value;
                if (selectedMethod === "cash") {
                    paymentInstructions.innerHTML = `
                        <p>Vui lòng đến quầy để được hướng dẫn thực hiện thanh toán.</p>
                    `;
                } else if (selectedMethod === "bank") {
                    paymentInstructions.innerHTML = `
                        <p>Vui lòng quét mã QR sau để thực hiện thanh toán:</p>
                        <img src="./public/img/DOMDOM_qrcode.png" alt="QR Code" style="width: 100%; max-width: 300px; display: block; margin: 0 auto;">
                    `;
                }
            });
        });

        </script>
<?php
if (isset ($data['rs']))
{
    if($data["rs"]== 'true')
    {
        echo'<script language="javascript">
							alert("Hoàn tất");	
							</script>';
    }
    else
    {
        echo'<script language="javascript">
							alert("Thất bại");	
							</script>';
    }
}

?>