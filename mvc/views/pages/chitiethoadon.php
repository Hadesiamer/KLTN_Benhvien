<?php
$dt = json_decode($data["CTHD"],true);
$TT = json_decode($data["TT"],true);
foreach ($dt as $r): 
     echo '<form action="" method="post">
     <h1>Hóa đơn #'.$r["MaHD"].'</h1>
     <input type="hidden" name="MaHD" value="'.$r["MaHD"].'">
     <span class="status status-'.$r["TrangThai"].'">'.$r["TrangThai"].'</span>
     
     <div class="customer-info">
         <h2>Thông tin bệnh nhân</h2>
         <div class="info-grid">
             <div class="info-item"><div class="info-label">Họ và tên:</div><div>'.$r["HovaTen"].'</div></div>
             <div class="info-item"><div class="info-label">Email:</div><div>'.$r["Email"].'</div></div>
             <div class="info-item"><div class="info-label">Điện thoại:</div><div>'.$r["SoDT"].'</div></div>
             <div class="info-item"><div class="info-label">BHYT:</div><div>'.$r["BHYT"].'</div></div>
             <div class="info-item"><div class="info-label">Dịch vụ:</div><div>'.$r["DichVu"].'</div></div>
             <div class="info-item"><div class="info-label">Thời gian:</div><div>'.$r["NgayLapHoaDon"].'</div></div>
         </div>
     </div>';
endforeach;

 echo '<h2>Phương thức thanh toán</h2>
    <table class="payment-table">
        <tr>';
foreach($TT as $f):
        $name = $f["TenPTTT"];
        switch ($name) {
            case 'MoMo':
                $name1 = "'momo'";
                $name2 = 'momo';
                break;
            case 'ViSa':
                $name1 = "'visa'";
                $name2 = 'visa';
                break;
            case 'Ngân Hàng':
                $name1 = "'bank'";
                $name2 = 'bank';
                break;
            case 'Tiền mặt':
                $name1 = "'cash'";
                $name2 = 'cash';
        }
        echo '<td onclick="selectPayment(' . $name1 . ')">
        <form action="#" method="post">
        <input type="checkbox" name="paymentOption" value="' . $f["MaPTTT"] . '" onclick="onlyOneCheckbox(this)" require>
        <div class="payment-icon '.$name2.'"></div>' . $f["TenPTTT"] . '
      </td>';

    endforeach;
 echo '</table>
        <div id="momoAction" class="action-section"><div class="qr-code"></div></div>
        <div id="bankAction" class="action-section"><div class="qr-code"></div></div>
        <div id="visaAction" class="action-section">
            <table id="card-payment-details" class="action-button">
                <tr><td><label for="card-name">Tên trên thẻ</label></td><td><input type="text" id="card-name" name="card-name"></td></tr>
                <tr><td><label for="card-number">Số thẻ</label></td><td><input type="text" id="card-number" name="card-number"></td></tr>
                <tr><td><label for="expiry-date">Ngày hết hạn</label></td><td><input type="text" id="expiry-date" name="expiry-date" placeholder="MM/YY"></td></tr>
                <tr><td><label for="cvv">CVV</label></td><td><input type="number" id="cvv" name="cvv"></td></tr>
            </table>
        </div>
        <div id="cashAction" class="action-section"><a href="#" class="action-button">Xác nhận thanh toán tiền mặt</a></div>

        <!-- Nút xác nhận và hủy -->
        <input type="submit" class="nut1" name="nutHuy" id="nut" value="Hủy hóa đơn" onclick="return confirm(\'Bạn có thật sự muốn hủy hóa đơn này không?\')">
        <input type="submit" class="nut2" name="nutXN" id="nut" value="Xác nhận hóa đơn">
        </form> 
    ';
if (isset ($data['Result']))
{
    if($data["Result"]== 'true')
    {
        echo'<script language="javascript">
							alert("Cập nhật phương thức thanh toán thành công");	
							</script>';
    }
    else if($data["Result"]== 3)
    {
        echo'<script language="javascript">
							alert("Hủy hóa đơn thành công");	
							</script>';
    }
    else
    {
        echo'<script language="javascript">
							alert("Cập nhật phương thức thất bại");	
							</script>';
    }
}
?>