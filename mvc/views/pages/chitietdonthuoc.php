<?php
    $dt = json_decode($data["CTDT"],true);
    foreach ($dt as $r):
    echo '
    <form action="" method="post">
    <h1>Đơn thuốc #'.$r["MaDT"].'</h1>
    <input type="hidden" name="MaDT" value="'.$r["MaDT"].'">
    <span class="status status-'.$r["TrangThai"].'">'.$r["TrangThai"].'</span>
    <div class="customer-info">
        <h2>Thông tin bệnh nhân</h2>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Họ và tên:</div>
                <div>'.$r["HovaTen"].'</div>
            </div>
            <div class="info-item">
                <div class="info-label">Email:</div>
                <div>'.$r["Email"].'</div>
            </div>
            <div class="info-item">
                <div class="info-label">Điện thoại:</div>
                <div>'.$r["SoDT"].'</div>
            </div>
            <div class="info-item">
                <div class="info-label">BHYT:</div>
                <div>'.$r["BHYT"].'</div>
            </div>
            <div class="info-item">
                <div class="info-label">Bác sĩ:</div>
                <div>'.$r["TenBS"].'</div>
            </div>
            
            <div class="info-item">
                <div class="info-label">Thời gian</div>
                <div>'.$r["NgayTao"].'</div>
            </div>
        </div>
    </div>';
    endforeach;
    echo '<h2>Thuốc: </h2>
    <table>
        <thead>
            <tr>
                <th style="text-align: center;">STT</th>
                <th style="text-align: center;">Mã thuốc</th>
                <th style="text-align: center;">Tên thuốc</th>
                <th style="text-align: center;">Số lượng</th>
                <th style="text-align: center;">Giá</th>
                <th style="text-align: center;">Cách dùng</th>
                <th style="text-align: center;">Thành tiền</th>
                
            </tr>
        </thead>
        <tbody>';
    $thuoc = json_decode($data["Thuoc"],true);
    $dem=1;
    $t1=0;
    foreach ($thuoc as $f):
   
    $tt = $f["SoLuong"]*$f["GiaTien"];
        echo '<tr>
                <td>'.$dem.'</td>
                <td>'.$f["MaThuoc"].'</td>
                <td>'.$f["TenThuoc"].'</td>
                <td>'.$f["SoLuong"].'</td>
                <td>'.$f["GiaTien"].'</td>
                <td>'.$f["CachDung"].'</td>
                <td>'.$tt.'</td>
    </tr>';
    $dem++;
    $t1+=$tt;
    endforeach;
    echo '<tr>
        <th colspan="6" style="text-align: center;">Tổng cộng</th>
        <td>'.$t1.'</td>
        </tr>
        </tbody>
        </table>
        <!-- Nút xác nhận và hủy -->
        <input type="submit" class="nut1" name="nutHuy" id="nut" value="Hủy đơn thuốc" onclick="return confirm(\'Bạn có thật sự muốn hủy hóa đơn này không?\')">
        <input type="submit" class="nut2" name="nutXN" id="nut" value="Xác nhận đơn thuốc">';
            
    echo '</form>';
    if (isset ($data['Result']))
{
    if($data["Result"]== 'true')
    {
        echo'<script language="javascript">
							alert("Cập nhật đơn thuốc thành công");	
							</script>';
    }
    else if($data["Result"]== 3)
    {
        echo'<script language="javascript">
							alert("Hủy đơn thuốc thành công");	
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