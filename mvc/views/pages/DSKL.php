<?php
$dt = json_decode($data["DanhSachKham"],true);
$pagination = $data["Pagination"];
$dem = $pagination->getOffset() + 1;
if($data["loc"] == "lk.NgayKham")
{
    $date = date('Y-m-d');
}
else
{
    $date = $data["loc"];

}
?>

<h1 style="margin-top:20px;">Danh Sách Lịch Khám</h1>
<div class="loc" style=" display: flex; justify-content: right; text-align: center; padding: 10px 0;">
                <form action="/KLTN_Benhvien/NVYT/LichKham" method="POST">
                <input type="date" name="loc" style="width: 200px; text-align: center; height:30px; border-radius:30px; font-sizing:15px" value="<?php echo $date; ?>">
                <input type="submit" name="btnLoc" value="Lọc" style="margin: 0 20px; height: 30px; width: 60px; text-align: center;  border:none; margin-right:50px; border-radius:15px">
                </select>
                </form> 
            </div>
<table>
    <thead>
        <tr>
            <th style="text-align: center;">STT</th>
            <th style="text-align: center;">Tên Bệnh Nhân</th>
            <th style="text-align: center;">Ngày khám</th>
            <th style="text_align: center"></th>
        </tr>
    </thead>
    <tbody>
<?php
foreach ($dt as $r):  
    echo '<tr>
                <td data-label="STT">'.$dem.'</td>
                <td data-label="Customer" style="text-align: center;">'.$r["HovaTen"].'</td>
                <td data-label="Date">'.$r["NgayKham"].'</td>
                <form action="/KLTN_Benhvien/NVYT/CTLK" method="POST">
                <input type="hidden" name="ctlk" value="'.$r["MaLK"].'">
                </td>
                <td><input type="submit" name="btnCTLK" value="Thay đổi" style=" height: 30px; width: 100px; text-align: center;  border:none; border-radius:15px;">
                </form>
                </td>
            </tr>';
            $dem++;
endforeach;
echo '</tbody>
</table>
';

// Pagination links
if ($pagination instanceof Pagination) {
    echo '<div class="pagination" style=" display: flex; justify-content: center; text-align: center; padding: 10px 0; ">
            <div style="display: inline-block; background-color: #f0f0f0; padding: 10px; border-radius: 5px; ">';
    
    $baseUrl = '';

    // Previous page link
    if ($pagination->hasPreviousPage()) {
        echo '<form action="" method="POST" style="display: inline;">
                <input type="hidden" name="page" value="'.($pagination->getCurrentPage() - 1).'">
                <input type="hidden" name="loc" value="'.$data["loc"].'">
                <input type="submit" name="btnPage" value="<" style="margin: 0   5px; width: 60px; text-align: center;  border:none;">
            </form>';
    }

    // Page number links
    $totalPages = $pagination->getTotalPages();
    $currentPage = $pagination->getCurrentPage();
    $range = 2; // Number of pages to show before and after the current page

    for ($i = 1; $i <= $totalPages; $i++) {
        if ($i == 1 || $i == $totalPages || ($i >= $currentPage - $range && $i <= $currentPage + $range)) {
            if ($i == $currentPage) {
                echo '<span style="margin: 0 5px; font-weight: bold;">'.$i.'</span>';
            } else {
                echo '<form action="" method="POST" style="display: inline;">
                        <input type="hidden" name="page" value="'.$i.'" style="margin: 0 5px; width: 30px; text-align: center;">
                        <input type="hidden" name="loc" value="'.$data["loc"].'">
                        <input type="submit" name="btnPage" value="'.$i.'" style="margin: 0 5px; width: 30px; text-align: center; border:none;">
                    </form>';
            }
        } elseif ($i == $currentPage - $range - 1 || $i == $currentPage + $range + 1) {
            echo '<span style="margin: 0 5px;">...</span>';
        }
    }

    // Next page link
    if ($pagination->hasNextPage()) {
        echo '      <form action="" method="POST" style="display: inline;">
                        <input type="hidden" name="page" value="'.($pagination->getCurrentPage() + 1).'">
                        <input type="hidden" name="loc" value="'.$data["loc"].'">
                        <input type="submit" name="btnPage" value=">" style="margin: 0 5px; width: 60px; text-align: center;  border:none;">
                    </form>';
    }

    echo '</div></div>';
} else {
    echo '<p></p>';
}
?>