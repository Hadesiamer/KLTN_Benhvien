<?php
    error_reporting(0);
    if($_SESSION["role"] != 5){
        if(!isset($_SESSION['last_id'])){
            echo "<script>alert('Bạn không có quyền truy cập')</script>";
            header("refresh: 0; url='/KLTN_Benhvien'");
        }
    }
?>

<?php
$genders = ['Nam', 'Nữ'];

if (isset($data["result"])) {
    $result = $data["result"];
    if ($result['success']) {
        echo '<script>alert("' . $result['message'] . '")</script>';
        header("refresh:0; url='/KLTN_Benhvien/Login'");
    } else {
        echo '<script>alert("' . $result['message'] . '")</script>';
        header("refresh:0; url='/KLTN_Benhvien/Register/BNHS'");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./public/css/main.css">
    <link rel="stylesheet" href="../public/css/main.css">
    <link rel="stylesheet" href="./public/css/taohs.css">
    <link rel="stylesheet" href="../public/css/taohs.css">
</head>
<body>
<?php include "blocks/header.php" ?>
<div class="model">
        <div class="container">
        <h2>Thông tin Hồ sơ</h2>
        <form action="/KLTN_Benhvien/Register/XNHS" method="POST">
            <input type="hidden" name="last_id" value="<?php echo $_SESSION['last_id']; ?>">
            <div class="mb-3">
                <label for="name">Họ và tên:</label>
                <input type="text" class="form-control" name="ten" required>
            </div>
            <div class="mb-3">
                <label for="name">Số điện thoại:</label>
                <input type="tel" class="form-control" name="sdt" 
                value="<?php 
                $sdt = json_decode($data["SDT"],true);
                foreach($sdt as $r):
                    echo $r["username"];
                endforeach;
                ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="email">Email:</label>
                <input type="email" class="form-control" name="email" required>
            </div>

            <div class="mb-3">
                <label for="dob">Ngày sinh:</label>
                <input type="date" class="form-control" name="ns" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Giới tính *</label>
                <div class="radio-group">
                    <?php
                        
                        foreach ($genders as $gender) {
                            echo '<div class="gr-rdo">';
                            echo '<input type="radio" class="ipgt" name="gt" id="gender_' . $gender . '" value="' . $gender . '"  required>';
                            echo '<label class="rdo" for="gender_' . $gender . '">' . $gender . '</label>';
                            echo '</div>';
                        }
                    ?>
                </div>
            </div>
            <div class="mb-3">
                <label for="diachi">Địa chỉ:</label>
                <input type="text" class="form-control" name="diachi">
            </div>
            <div class="mb-3">
                <label for="bh">Bảo hiểm y tế:</label>
                <input type="text" class="form-control" name="bhyt">
                <input type="hidden" name="maphieukham" value="0">
            </div>
            <button type="submit" class="btn btn-primary w-100" name="btn-xn" id="btn-xn">Xác nhận</button>
        </form>
        </div>
</div>
</body>
</html>