
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
        // $data["DanhSachKham"] có thể là JSON hoặc mảng
        if (isset($data["DanhSachKham"])) {
            $danhSach = is_string($data["DanhSachKham"])
                ? json_decode($data["DanhSachKham"], true)
                : $data["DanhSachKham"];
        } else {
            $danhSach = [];
        }

        if (!empty($danhSach) && is_array($danhSach)) {
            $STT = 1;
            foreach ($danhSach as $benhnhan) {
                echo '<tr data-malk="' . htmlspecialchars($benhnhan['MaLK']) . '">';
                echo '<td>' . $STT . '</td>';
                echo '<td>' . htmlspecialchars($benhnhan['HovaTen']) . '</td>';
                echo '<td>' . htmlspecialchars($benhnhan['NgaySinh']) . '</td>';
                echo '<td>' . htmlspecialchars($benhnhan['SoDT']) . '</td>';
                echo '<td>' . htmlspecialchars($benhnhan['GioKham']) . '</td>';
                echo '<td>
                        <form action="/KLTN_Benhvien/Bacsi/Lapphieukham" method="POST">
                            <input type="hidden" name="MaBN" value="' . htmlspecialchars($benhnhan['MaBN']) . '">
                            <input type="hidden" name="MaLK" value="' . htmlspecialchars($benhnhan['MaLK']) . '">
                            <button type="submit" name="btnLPK" class="btn-submit">Lập phiếu khám</button>
                        </form>
                      </td>';
                echo '</tr>';
                $STT++;
            }
        } else {
            echo "<tr><td colspan='6'>Không có dữ liệu</td></tr>";
        }
        ?>
    </tbody>
</table>
