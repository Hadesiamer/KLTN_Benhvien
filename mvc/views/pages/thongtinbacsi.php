<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./../public/css/main.css">
</head>

<body>
    <?php
    $tt = json_decode($data["thongtinbs"], true);
    foreach ($tt as $r):
        echo '<div class="profile-container">
                <div class="profile-header">
                    <img src="../public/img/' . $r["HinhAnh"] . '" alt="Profile Picture">
                    <div>
                        <h5>' . $r["HovaTen"] . '</h5>
                        <small>Mã nhân viên: ' . $r["MaNV"] . '</small>
                    </div>
                </div>
                
                <div class="profile-section mb-3">
                    <p>Thông tin bác sĩ</p>
                    <div>
                        <span>Họ và tên</span>
                        <span>' . $r["HovaTen"] . '</span>
                    </div>
                    <div>
                        <span>Điện thoại</span>
                        <span>' . $r["SoDT"] . '</span>
                    </div>
                    <div>
                        <span>Email</span>
                        <span>' . $r["EmailNV"] . '</span>
                    </div>
                    <div>
                        <span>Giới tính</span>
                        <span>' . $r["GioiTinh"] . '</span>
                    </div>
                    <div>
                        <span>Ngày sinh</span>
                        <span>' . $r["NgaySinh"] . '</span>
                    </div>
                    <div>
                        <span>Chức vụ</span>
                        <span>' . $r["ChucVu"] . '</span>
                    </div>
                    <div>
                        <span>Chuyên khoa</span>
                        <span>' . $r["TenKhoa"] . '</span>
                    </div>
                </div>
            </div>';
    endforeach;
    ?>
</body>

</html>