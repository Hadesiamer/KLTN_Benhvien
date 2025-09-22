<div class="filters">
    
    <div class="date-picker">
        <span>Ngày hiện tại: </span>
        <input type="date" value="<?php echo date('Y-m-d'); ?>" readonly>
    </div>
</div>

<table class="patient-list">
    <thead>
        <tr>
            <th>STT</th>
            <th>Tên Bệnh nhân</th>
            <th>Ngày sinh</th>
            <th>Số điện thoại</th>
            <th>Giờ khám</th>
            <th>Chức năng</th>
        </tr>
    </thead>
    <tbody>
    
        <?php
        $danhSach = json_decode($data["DanhSachKham"], true);
        if (!empty($danhSach)) {
            $STT=1;
            foreach ($danhSach as $benhnhan) {
                echo '<td>' .$STT. '</td>';
                echo '<td>'.$benhnhan["HovaTen"].'</td>';
                echo '<td>'.$benhnhan['NgaySinh'].'</td>';
                echo '<td>'.$benhnhan['SoDT'].'</td>';
                echo '<td>'.$benhnhan['GioKham'].'</td>';
                echo '<td>
                <form action="/KLTN_Benhvien/Bacsi/Lapphieukham" method="POST">
                <input type="hidden" name="MaBN" value="'.$benhnhan['MaBN'].'">
                <input type="hidden" name="MaLK" value="'.$benhnhan['MaLK'].'">
                <button type="submit" name ="btnLPK" class="btn-submit">Lập phiếu khám</button></td>';
                echo '</tr>';
                echo '</form>';
                $STT++;
            }
        } else {
            echo "<tr><td colspan='5'>Không có dữ liệu</td></tr>";
        }
        ?>
    </tbody>
</table>
<?php

if (isset ($data['result']))
{
    if($data["result"]== 'true')
    {
        echo'<script language="javascript">
							alert("Lập phiếu khám thành công");	
							</script>';
    }
    else
    {
        echo'<script language="javascript">
							alert("Lập phiếu khám thất bại");	
							</script>';
    }
}