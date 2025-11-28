<?php
if (isset($data["XL"])) {
    $result = $data["XL"]; 
    if ($result['success']) {
        echo '<script>alert("' . $result['message'] . '")</script>'; 
    } else {
        echo '<script>alert("' . $result['message'] . '")</script>'; 
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin</title>
    <style>
        .profile-container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: auto;
        }

        .profile-container h4 {
            font-weight: bold;
            margin-bottom: 25px;
            color: #333;
            text-align: center;
        }
        .updateform{
            width: 800px;
            margin: auto;
        }
        .form-label {
            font-weight: bold;
            color: #495057;
            margin-bottom: 5px;
        }

        .form-control {
            border-radius: 5px;
            padding: 10px;
            border: 1px solid #ced4da;
            box-shadow: none;
            margin-bottom: 15px;
        }

        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }

        .radio-group {
            display: flex;
            gap: 20px; /* Khoảng cách giữa các radio button */
            margin-bottom: 15px;
            align-items: center; /* Căn giữa các phần tử trong dòng */
        }

        .il-gr {
            display: flex;
            margin-bottom: 20px;
        }
        .gr-rdo {
            margin-right: 20px;
        }

        .radio-group label {
            font-weight: normal;
            margin-left: 5px;
        }

        input[type="radio"] {
            margin-right: 5px;
        }

        .d-flex {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

        .btn-secondary {
            background-color: #6c757d;
            border: none;
            padding: 8px 20px;
            font-weight: bold;
        }

        .btn-primary {
            background-color: #0d6efd;
            border: none;
            padding: 8px 20px;
            font-weight: bold;
        }

        .btn-secondary:hover, .btn-primary:hover {
            opacity: 0.9;
        }

        .mb-3 {
            display: flex;
            flex-direction: column;
        }

        .form-group {
            display: grid;
            grid-template-columns: 1fr 2fr;
            align-items: center;
            gap: 15px;
        }

        .form-group label {
            margin: 0;
        }

        .form-group input[type="text"], 
        .form-group input[type="email"], 
        .form-group input[type="date"] {
            width: 100%;
        }

        a{
            text-decoration: none;
            color: #fff;
        }
    </style>
</head>
<body>
    <?php 
        // if (isset($data["UD"])) {
        $ud = json_decode($data["UD"], true);
        foreach($ud as $r):
            echo '<div class="profile-container">
            <h4>Điều chỉnh thông tin</h4>
            <form class="updateform" action="/KLTN_Benhvien/BN/UDTT" method="POST">
                <div class="mb-3">
                    <label for="fullName" class="form-label">Họ và tên *</label>
                    <input type="text" name="hovaten" class="form-control" id="fullName" value="'.$r["HovaTen"].'" required>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Số điện thoại *</label>
                    <span>'.$r["SoDT"].'</span>
                </div>
                <div class="mb-3">
                    <label for="dob" class="form-label">Ngày sinh *</label>
                    <input type="date" name="ngaysinh" class="form-control" id="dob" value="'.$r['NgaySinh'].'" required>
                </div>
                <div class="mb-3">
                    <label class="gt form-label">Giới tính *</label>';
                    echo '<div class="il-gr">';
                        $genders = ['Nam', 'Nữ'];
                        foreach ($genders as $gender) {
                            $checked = ($gender === $r['GioiTinh']) ? 'checked' : '';
                            echo '<div class="gr-rdo">';
                            echo '<input type="radio" name="gt" id="gender_' . $gender . '" value="' . $gender . '" ' . $checked . ' required>';
                            echo '<label class="rdo" for="gender_' . $gender . '">' . $gender . '</label>';
                            echo '</div>';
                        }
                    '</div>';
                echo '</div>
                <div class="mb-3">
                    <label for="insurance" class="form-label">Mã thẻ BHYT</label>
                    <input type="text" name="bhyt" class="form-control" id="insurance" value="'.$r['BHYT'].'">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" id="email" value="'.$r['Email'].'" required>
                </div>
                <div class="mb-3">
                    <label for="diachi" class="form-label">Đia chỉ </label>
                    <input type="text" name="diachi" class="form-control" id="diachi" value="'.$r['DiaChi'].'">
                </div>
                <div class="d-flex justify-content-end">
                    <button type="reset" class="btn btn-secondary me-2">Hủy</button>
                    <button type="submit" name="btn-updatebn" class="btn btn-primary">Cập nhật</button>
                </div>
                
            </form>
        </div>';
        endforeach;
        // }
    ?>
</body>
</html>