<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chưa tìm thấy</title>
</head>
<body>
<?php
    $tt = json_decode($data["TT"], true);
    foreach($tt as $r):

        // --- Ép kiểu ngày sinh dd-mm-yyyy ---
        $ngaySinhFormatted = "";
        if (!empty($r["NgaySinh"])) {
            $ngaySinhFormatted = date("d-m-Y", strtotime($r["NgaySinh"]));
        }

        echo '<div class="profile-container">
                <div class="profile-header">
                    <div class="avatar">👤</div>
                    <div>
                        <h5>'.$r["HovaTen"].'</h5>
                        <small>Mã BN: '.$r["MaBN"].'</small>
                    </div>
                </div>
                
                <div class="profile-section mb-3">
                    <p>Thông tin cơ bản</p>
                    <div>
                        <span>Họ và tên</span>
                        <span>'.$r["HovaTen"].'</span>
                    </div>
                    <div>
                        <span>Điện thoại</span>
                        <span>'.$r["SoDT"].'</span>
                    </div>
                    <div>
                        <span>Giới tính</span>
                        <span>'.$r["GioiTinh"].'</span>
                    </div>
                    <div>
                        <span>Ngày sinh</span>
                        <span>'.$ngaySinhFormatted.'</span>
                    </div>
                    <div>
                        <span>Địa chỉ</span>
                        <span>'.$r["DiaChi"].'</span>
                    </div>
                </div>

                <div class="profile-section">
                    <p>Thông tin bổ sung</p>
                    <div>
                        <span>Mã BHYT</span>
                        <span>'.$r["BHYT"].'</span>
                    </div>
                    <div>
                        <span>Email</span>
                        <span>'.$r["Email"].'</span>
                    </div>
                </div>

                <div class="edit-button">
                    <form action="/KLTN_Benhvien/BN/UDTT" method="post">
                        <button type="submit" class="btn btn-primary" name="btnUDTT">Thay đổi thông tin</button>
                    </form>
                </div>
            </div>';
    endforeach;

?>
</body>
</html>
