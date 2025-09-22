<?php
$lichKhamData = json_decode($data["LXN"], true);
?>
<link rel="stylesheet" href="./public/css/lichkham.css">
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
<h2 class="mt-3">Lịch xét nghiệm</h2>

    <div class="col-4">
        <div class="list-group">
         <?php if (isset($lichKhamData) && !empty($lichKhamData)): ?>
            <?php foreach ($lichKhamData as $lichKham): ?>
                <form method="POST" action="/KLTN_Benhvien/LichXetNghiem">
                    <input type="hidden" name="MaLXN" value="<?= $lichKham['MaXN']; ?>">
                    <div class="patient-item list-group-item" onclick="this.closest('form').submit()">
                        <p style="font-size: 18px;">
                            <?= $lichKham['HovaTen']; ?>
                        </p>
                        <p style="font-size: 14px; text-align: left;">
                            <?= $lichKham['NgayXetNghiem']; ?>
                        </p>
                        <p style="font-size: 14px; text-align: left;">
                            <?= $lichKham['HovaTen']; ?>
                        </p>
                        <p style="font-size: 14px; text-align: left;">
                            Mã số - <?= $lichKham['MaXN']; ?>
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
        if (is_array($data["CTLXN"])) {
            $data["CTLK"] = json_encode($data["CTLXN"]);  
        }
            $chiTietData = json_decode($data["CTLXN"], true); 
        ?>
        <?php if (isset($chiTietData) && !empty($chiTietData)): ?>
            <div class="chi-tiet-lich-kham">
                <?php foreach ($chiTietData as $ct): ?>
                    <h5>Thông tin lịch xét nghiệm</h5> <hr>
                    <p><strong>Mã lịch xét nghiệm:</strong> <?= $ct['MaXN']; ?></p>
                    <p><strong>Ngày xét nghiêm:</strong> <?= $ct['NgayXetNghiem']; ?></p>
                    <p><strong>Loại xét nghiệm:</strong> <?= $ct['LoaiXN']; ?></p>
                    <p><strong>Kết quả:</strong> <?= $ct['KetQua']; ?></p>
                    <p><strong>Trạng thái:</strong> <?= $ct['TrangThai']; ?></p>

                    <h5>Thông tin bệnh nhân</h5> <hr>
                    <p><strong>Mã bệnh nhân:</strong> <?= $ct['MaBN']; ?></p>
                    <p><strong>Họ tên:</strong> <?= $ct['HovaTen']; ?></p>
                    <p><strong>Năm sinh:</strong> <?= $ct['NgaySinh']; ?></p>
                    <p><strong>Số điện thoại:</strong> <?= $ct['SoDT']; ?></p>
                    <p><strong>Giới tính:</strong> <?= $ct['GioiTinh']; ?></p>
                    <p><strong>Mã BHYT:</strong> <?= $ct['BHYT']; ?></p>
                    
                <?php endforeach; ?>
            </div>
            <div class="button">
                <!-- <button type="button" class="btn btn-change" data-bs-toggle="modal" data-bs-target="#changeScheduleModal">
                    Thay đổi lịch xét nghiệm
                </button> -->
                <form method="POST" action="/KLTN_Benhvien/LichXetNghiem">
                    <button type="submit" name="HuyLXN" value="<?= $ct['MaXN']; ?>" class="btn btn-cancel" onclick="return confirm('Bạn có thật sự muốn hủy lịch xét nghiệm này không?')">Hủy lịch xét nghiệm</button>
                </form>
            </div>

            
            <div class="modal fade" id="changeScheduleModal" tabindex="-1" aria-labelledby="changeScheduleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="changeScheduleModalLabel">Thay đổi lịch khám</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="/KLTN_Benhvien/LichKham">
                                <input type="hidden" name="ThayDoiLK" value="<?= $ct['MaLK']; ?>">
                                <div class="form-group">
                                    <label for="NgayKham">Chọn ngày khám:</label>
                                    <input type="date" name="NgayKham" id="NgayKham" class="form-control" required>
                                </div>
                                <div class="form-group mt-3">
                                    <label for="GioKham">Chọn giờ khám:</label>
                                    <input type="time" name="GioKham" id="GioKham" class="form-control" required>
                                </div>
                                <div class="modal-footer mt-4">
                                    <button type="submit" class="btn btn-change">Lưu thay đổi</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        <?php else: ?>
            <p>Vui lòng chọn lịch khám để xem chi tiết!</p>
        <?php endif; ?>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const alertMessage = document.getElementById("alert-message");
        if (alertMessage) {
            setTimeout(() => {
                alertMessage.style.transition = "opacity 0.5s ease";
                alertMessage.style.opacity = "0";
                setTimeout(() => alertMessage.remove(), 100); 
            }, 1000); 
        }
    });
    </script>
    <script>
        document.getElementById("btnChangeSchedule").addEventListener("click", function(event) {
            event.preventDefault(); 
            document.getElementById("changeScheduleForm").style.display = "block";
        });
    </script>
    
<?php
    if (isset ($data['rs']))
    {
        if($data["rs"]== 'true')
        {
            echo'<script language="javascript">
                                alert("Hủy lịch xét nghiệm thành công");	
                                </script>';
        }

    }

?>