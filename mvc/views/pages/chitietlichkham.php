<?php
            $chiTietData = json_decode($data["CTLK"], true); 
?>
            <form action="" method="POST">
            <div class="chi-tiet-lich-kham">
                <?php foreach ($chiTietData as $ct): ?>
                    <input type="hidden" name="MaLK" value="<?= $ct['MaLK']; ?>">
                    <h5>Thông tin đặt khám</h5> <hr>
                    <p><strong>Mã lịch khám:</strong> <?= $ct['MaLK']; ?></p>
                    <p><strong>Ngày khám:</strong> <input type="date" name="NgayKham" id="NgayKham" class="form-control" value="<?= $ct['NgayKham']; ?>" style="text-align:center; width:20%; margin-left: 420px;" ></p>
                    <p><strong>Giờ khám:</strong> <input type="time" name="GioKham" id="GioKham" class="form-control" value="<?= $ct['GioKham']; ?>" style="text-align:center; width:20%; margin-left: 420px;" required></p>
                    <p><strong>Chuyên khoa:</strong> <?= $ct['TenKhoa']; ?></p>

                    <h5>Thông tin bệnh nhân</h5> <hr>
                    <p><strong>Mã bệnh nhân:</strong> <?= $ct['MaBN']; ?></p>
                    <p><strong>Họ tên:</strong> <?= $ct['HovaTen']; ?></p>
                    <p><strong>Năm sinh:</strong> <?= $ct['NgaySinh']; ?></p>
                    <p><strong>Số điện thoại:</strong> <?= $ct['SoDT']; ?></p>
                    <p><strong>Giới tính:</strong> <?= $ct['GioiTinh']; ?></p>
                    <p><strong>Mã BHYT:</strong> <?= $ct['BHYT']; ?></p>
                <?php endforeach; ?>
            </div>
            <input type="submit" class="nut2" name="btnTDLK" id="nut" value="Thay đổi lịch khám" onclick="return confirm('Lịch khám sẽ được thay đổi?')">
            </form>     


<?php
if (isset ($data['Result']))
{
    if($data["Result"]== 'true')
    {
        echo'<script language="javascript">
							alert("Thay đổi lịch khám thành công");	
							</script>';
    }

    else
    {
        echo'<script language="javascript">
							alert("Thay đổi lịch khám thất bại");	
							</script>';
    }
}
?>