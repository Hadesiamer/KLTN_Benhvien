<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông Tin Bệnh Nhân</title>
    <style>
        .search-bar {
            margin-bottom: 20px;
            position: relative;
        }

        .search-bar input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .search-bar button {
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
            background-color: #007bff;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 20px;
            background-color: transparent;
            /* Không màu */
        }

        .info-item {
            padding: 5px;
            margin-bottom: 10px;
            background-color: transparent;
            /* Không màu */
        }

        .info-label {
            font-weight: bold;
            color: inherit;
            /* Giữ màu sắc kế thừa */
        }

        .info-item {
            margin-bottom: 10px;
        }

        .info-label {
            font-weight: bold;
        }

        .dropdown {
            margin-top: 20px;
        }

        .dropdown select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <div id="container">
        <h2 class="mb-4">Xem thông tin bệnh nhân</h2>
        <form method="POST" class="search-bar">
            <input type="text" name="maBN" placeholder="Vui lòng nhập mã bệnh nhân hoặc mã BHYT" required>
            <button type="submit" name="search">Tìm kiếm</button>
        </form>
    </div>
    <div id="container">
        <?php
        if (isset($data["ThongTinBenhNhan"])) {
            $thongTinBenhNhan = json_decode($data["ThongTinBenhNhan"], true);
            $phieuKhamBenhNhan = json_decode($data["PhieuKhamBenhNhan"], true);

            if ($thongTinBenhNhan) {
                foreach ($thongTinBenhNhan as $index => $tt) {
        ?>
                    <!-- Thông tin bệnh nhân -->
                    <div class="patient-info">
                        <h3>Thông tin bệnh nhân</h3>
                        <div class="info-grid">
                            <div class="info-item"><span class="info-label">Mã bệnh nhân:</span> <?php echo $tt['MaBN']; ?></div>
                            <div class="info-item"><span class="info-label">BHYT:</span> <?php echo $tt['BHYT']; ?></div>
                            <div class="info-item"><span class="info-label">Họ và Tên:</span> <?php echo $tt['HovaTen']; ?></div>
                            <div class="info-item"><span class="info-label">Địa chỉ:</span> <?php echo $tt['DiaChi']; ?></div>
                            <div class="info-item"><span class="info-label">Ngày sinh:</span> <?php echo date('d-m-Y', strtotime($tt['NgaySinh'])); ?></div>
                            <div class="info-item"><span class="info-label">Số điện thoại:</span> <?php echo $tt['SoDT']; ?></div>
                            <div class="info-item"><span class="info-label">Giới tính:</span> <?php echo $tt['GioiTinh']; ?></div>
                            <div class="info-item"><span class="info-label">Email:</span> <?php echo $tt['Email']; ?></div>
                        </div>
                    </div>
                <?php } ?>
                <!-- Dropdown để chọn ngày tạo -->
                <div class="medical-history">
                    <h3>Thông tin bệnh án</h3>
                    <div class="dropdown">
                        <label for="NgayTao">Chọn ngày khám:</label>
                        <select id="NgayTao" name="NgayTao" onchange="showMedicalDetails(this.value)">
                            <option value="">-- Chọn ngày khám --</option>
                            <?php
                            $ngayTaoDaXuatHien = []; // Mảng lưu trữ các giá trị NgayTao đã xuất hiện
                            if ($phieuKhamBenhNhan) {
                                foreach ($phieuKhamBenhNhan as $phieu) {
                                    if (!in_array($phieu['NgayTao'], $ngayTaoDaXuatHien)) {
                                        $ngayTaoDaXuatHien[] = $phieu['NgayTao']; // Thêm vào mảng nếu chưa tồn tại
                                        echo '<option value="' . $phieu['NgayTao'] . '">' . date('d/m/Y', strtotime($phieu['NgayTao'])) . '</option>';
                                    }
                                }
                            }
                            ?>
                        </select>
                    </div>


                    <!-- Thông tin chi tiết bệnh án -->
                    <div id="MedicalDetails" style="margin-top: 20px;">
                        <p>Vui lòng chọn một ngày khám để xem thông tin chi tiết.</p>
                    </div>
                </div>
        <?php
            } else {
                echo "<p>Không tìm thấy bệnh nhân với thông tin đã nhập.</p>";
            }
        } else {
            echo "<p>Vui lòng nhập thông tin tìm kiếm để xem thông tin bệnh nhân.</p>";
        }
        ?>
    </div>

    <script>
        // JavaScript để xử lý hiển thị thông tin bệnh án khi chọn ngày
        const phieuKhamBenhNhan = <?php echo isset($data["PhieuKhamBenhNhan"]) ? $data["PhieuKhamBenhNhan"] : '[]'; ?>;

        function showMedicalDetails(ngayTao) {
            const detailsContainer = document.getElementById("MedicalDetails");
            if (!ngayTao) {
                detailsContainer.innerHTML = "<p>Vui lòng chọn một ngày khám để xem thông tin chi tiết.</p>";
                return;
            }

            const phieu = phieuKhamBenhNhan.find(item => item.NgayTao === ngayTao);
            if (phieu) {
                detailsContainer.innerHTML = `
                    <div class="info-grid">
                        <div class="info-item"><span class="info-label">Ngày khám:</span> ${new Date(phieu.NgayTao).toLocaleDateString()}</div>
                        <div class="info-item"><span class="info-label">Triệu chứng:</span> ${phieu.TrieuChung}</div>
                        <div class="info-item"><span class="info-label">Kết quả:</span> ${phieu.KetQua}</div>
                        <div class="info-item"><span class="info-label">Chuẩn đoán:</span> ${phieu.ChuanDoan}</div>
                        <div class="info-item"><span class="info-label">Lời dặn:</span> ${phieu.LoiDan}</div>
                        <div class="info-item"><span class="info-label">Ngày tái khám:</span> ${phieu.NgayTaiKham ? new Date(phieu.NgayTaiKham).toLocaleDateString() : 'Không có'}</div>
                    </div>
                    <div class="info-grid">
                        <div class="info-item"><span class="info-label">Xét nghiệm</span> ${phieu.LoaiXN ? phieu.LoaiXN : 'Không có'}</div>
                        <div class="info-item"><span class="info-label">Kết quả:</span> ${phieu.KetQuaXN ? phieu.LoaiXN : 'Không có'}</div>
                    </div>
                    <div class="info-grid">
                        <div class="info-item"><span class="info-label">Tên thuốc:</span> ${phieu.TenThuoc}</div>
                        <div class="info-item"><span class="info-label">Số lượng:</span> ${phieu.SoLuong}</div>
                        <div class="info-item"><span class="info-label">Liều dùng:</span> ${phieu.LieuDung}</div>
                        <div class="info-item"><span class="info-label">Cách dùng:</span> ${phieu.CachDung}</div>
                    </div>
                `;
            } else {
                detailsContainer.innerHTML = "<p>Không tìm thấy thông tin chi tiết cho ngày khám đã chọn.</p>";
            }
        }
    </script>
</body>

</html>