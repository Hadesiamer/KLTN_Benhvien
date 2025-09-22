<?php
      if($_SESSION["role"] != 2){
          echo "<script>alert('Bạn không có quyền truy cập')</script>";
          header("refresh: 0; url='/KLTN_Benhvien'");
      }
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lập Phiếu Khám</title>
    <link rel="stylesheet" href="/KLTN_Benhvien/public/css/main.css">
    <style>
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .row {
            display: flex;
            flex-wrap: wrap;
            margin: -10px;
        }
        .col-md-4, .col-md-8 {
            padding: 10px;
            box-sizing: border-box;
        }
        .col-md-4 {
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
        }
        .col-md-8 {
            flex: 0 0 66.666667%;
            max-width: 66.666667%;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-control {
            display: block;
            width: 100%;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        .btn {
            display: inline-block;
            font-weight: 400;
            text-align: center;
            vertical-align: middle;
            user-select: none;
            border: 1px solid transparent;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: 0.25rem;
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        .btn-primary {
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-success {
            color: #fff;
            background-color: #28a745;
            border-color: #28a745;
        }
    </style>
</head>
<body>
<?php include "blocks/header.php" ?>

    <div class="container">
        <h1>Lập Phiếu Khám</h1>
        <?php require_once "./mvc/views/pages/".$data["Page"].".php" ?>
    </div>

    <?php require_once "./mvc/views/blocks/footer.php" ?>

    <script>
        function addMedicine() {
            const medicineList = document.getElementById('medicineList');
            const medicineCount = medicineList.children.length + 1;
            const medicineHtml = `
                <div class="medicine-item">
                    <h4>Thuốc ${medicineCount}</h4>
                    <select name="thuoc[${medicineCount}][MaThuoc]" required class="form-control">
                        <option value="">Chọn thuốc</option>
                        <?php foreach ($data['ThuocList'] as $thuoc): ?>
                            <option value="<?php echo $thuoc['MaThuoc']; ?>"><?php echo $thuoc['TenThuoc']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="number" name="thuoc[${medicineCount}][SoLuong]" placeholder="Số lượng" required class="form-control">
                    <input type="text" name="thuoc[${medicineCount}][LieuDung]" placeholder="Liều dùng" required class="form-control">
                    <input type="text" name="thuoc[${medicineCount}][CachDung]" placeholder="Cách dùng" required class="form-control">
                </div>
            `;
            medicineList.insertAdjacentHTML('beforeend', medicineHtml);
        }
    </script>
</body>
</html>