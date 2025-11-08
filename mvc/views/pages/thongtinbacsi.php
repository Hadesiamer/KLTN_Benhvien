<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./../public/css/main.css">
</head>
<?php
    $conn = new mysqli("localhost", "root", "", "domdom");
    $conn->set_charset("utf8");

    //Lay thong tin bac si
    $idnv = $_SESSION['idnv'];
    $bacsi = $conn->query("SELECT * FROM bacsi inner join nhanvien on bacsi.MaNV = nhanvien.MaNV inner join chuyenkhoa on bacsi.MaKhoa=chuyenkhoa.MaKhoa   WHERE bacsi.MaNV = '$idnv'");
    $result_bacsi = $bacsi->fetch_assoc();
?>
<body>
    <div class="container">
        <h2 class="mb-4">Thông tin bác sĩ</h2>
        
        <table class="table table-bordered">
            <tr>
                <th>Ảnh đại diện</th>
                <td><img src="/KLTN_Benhvien/public/img/<?php echo htmlspecialchars($result_bacsi['HinhAnh']); ?>" alt="Ảnh đại diện" style="max-width: 150px; height: auto;"></td>
            </tr>
            <tr>
                <th>Mã bác sĩ</th>
                <td><?php echo htmlspecialchars($result_bacsi['MaNV']); ?></td>
            </tr>
            <tr>
                <th>Họ tên</th>
                <td><?php echo htmlspecialchars($result_bacsi['HovaTen']); ?></td>
            </tr>
            <tr>
                <th>Giới tính</th>
                <td><?php echo htmlspecialchars($result_bacsi['GioiTinh']); ?></td>
            </tr>
            <tr>
                <th>Ngày sinh</th>
                <td><?php echo htmlspecialchars($result_bacsi['NgaySinh']); ?></td>
            </tr>
            <tr>
                <th>Số điện thoại</th>
                <td><?php echo htmlspecialchars($result_bacsi['SoDT']); ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?php echo htmlspecialchars($result_bacsi['EmailNV']); ?></td>
            </tr>
            <tr>
                <th>Chuyên khoa</th>
                <td><?php echo htmlspecialchars($result_bacsi['TenKhoa']); ?></td>
            </tr>
            <tr>
                <th>Trang thai lam viec</th>
                <td><?php echo htmlspecialchars($result_bacsi['TrangThaiLamViec']); ?></td>
            </tr>
        </table>
    </div>
</body>

<style>
    body {
        font-family: "Segoe UI", Arial, sans-serif;
        background-color: #f5f8fc;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 700px;
        margin: 50px auto;
        background: #ffffff;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        padding: 25px 30px;
    }

    h2 {
        text-align: center;
        color: #0073e6;
        font-size: 26px;
        margin-bottom: 25px;
        font-weight: 600;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        text-align: left;
        width: 35%;
        background-color: #f0f6ff;
        color: #004a99;
        padding: 12px;
        font-weight: 600;
        border-bottom: 1px solid #dbe4f0;
    }

    td {
        padding: 12px;
        color: #333;
        border-bottom: 1px solid #eaeaea;
    }

    tr:hover td {
        background-color: #f9fcff;
    }

    img {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #cce0ff;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
        transition: transform 0.3s ease;
    }

    img:hover {
        transform: scale(1.05);
    }

    @media (max-width: 600px) {
        .container {
            padding: 15px;
        }

        h2 {
            font-size: 22px;
        }

        th, td {
            padding: 8px;
            font-size: 14px;
        }
    }
</style>

