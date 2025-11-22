<h2 class="mb-4">Tra thông tin bệnh nhân</h2>

<div id="container">
    <!-- Form tìm kiếm bệnh nhân -->
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
            foreach ($thongTinBenhNhan as $tt) {
                ?>
                <!-- Thông tin bệnh nhân -->
                <div class="card-section">
                    <h3 class="section-title">Thông tin bệnh nhân</h3>
                    <div class="info-grid">
                        <div class="info-item"><span class="info-label">Mã bệnh nhân:</span> <?= $tt['MaBN']; ?></div>
                        <div class="info-item"><span class="info-label">BHYT:</span> <?= $tt['BHYT']; ?></div>
                        <div class="info-item"><span class="info-label">Họ và Tên:</span> <?= $tt['HovaTen']; ?></div>
                        <div class="info-item"><span class="info-label">Địa chỉ:</span> <?= $tt['DiaChi']; ?></div>
                        <div class="info-item"><span class="info-label">Ngày sinh:</span> <?= date('d-m-Y', strtotime($tt['NgaySinh'])); ?></div>
                        <div class="info-item"><span class="info-label">Số điện thoại:</span> <?= $tt['SoDT']; ?></div>
                        <div class="info-item"><span class="info-label">Giới tính:</span> <?= $tt['GioiTinh']; ?></div>
                        <div class="info-item"><span class="info-label">Email:</span> <?= $tt['Email']; ?></div>
                    </div>
                </div>
                <?php
            }
            ?>
            <!-- Khu vực bệnh án -->
            <div class="card-section">
                <h3 class="section-title">Thông tin bệnh án</h3>

                <div class="dropdown">
                    <label for="NgayTao" class="info-label">Chọn ngày khám:</label>
                    <select id="NgayTao" name="NgayTao" onchange="showMedicalDetails(this.value)">
                        <option value="">-- Chọn ngày khám --</option>
                        <?php
                        $seen = [];
                        foreach ($phieuKhamBenhNhan as $phieu) {
                            if (!in_array($phieu['NgayTao'], $seen)) {
                                $seen[] = $phieu['NgayTao'];
                                echo '<option value="'.$phieu['NgayTao'].'">'.date('d/m/Y', strtotime($phieu['NgayTao'])).'</option>';
                            }
                        }
                        ?>
                    </select>
                </div>

                <!-- Thông tin chi tiết bệnh án -->
                <div id="MedicalDetails" class="detail-box">
                    <p>Vui lòng chọn một ngày khám để xem thông tin chi tiết.</p>
                </div>

            </div>
            <?php
        } else {
            echo '<p class="no-data">Không tìm thấy bệnh nhân với thông tin đã nhập.</p>';
        }
    } else {
        echo '<p class="no-data">Vui lòng nhập thông tin tìm kiếm để xem thông tin bệnh nhân.</p>';
    }
    ?>
</div>

<style>
/* Tiêu đề trang */
.title-page {
    font-size: 26px;
    font-weight: bold;
    color: #1a73e8;
    margin-bottom: 25px;
}

/* FORM TÌM KIẾM */
.search-bar {
    margin-bottom: 20px;
    position: relative;
}
.search-bar input {
    width: 100%;
    padding: 12px;
    border: 2px solid #cfe2ff;
    border-radius: 8px;
    outline: none;
    transition: 0.2s;
}
.search-bar input:focus {
    border-color: #1a73e8;
    box-shadow: 0 0 4px rgba(26,115,232,0.4);
}
.search-bar button {
    position: absolute;
    right: 6px;
    top: 50%;
    transform: translateY(-50%);
    background: #1a73e8;
    color: white;
    border: none;
    padding: 7px 14px;
    border-radius: 6px;
    cursor: pointer;
    transition: 0.2s;
}
.search-bar button:hover {
    background: #0c56c3;
}

/* THẺ THÔNG TIN */
.card-section {
    background: white;
    border-radius: 10px;
    padding: 20px 25px;
    margin-bottom: 25px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.08);
    border-left: 5px solid #1a73e8;
}

/* TIÊU ĐỀ PHẦN */
.section-title {
    margin-bottom: 15px;
    font-size: 20px;
    font-weight: bold;
    color: #1a73e8;
}

/* GRID THÔNG TIN */
.info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
    padding: 10px 5px;
}
.info-item {
    padding: 8px 12px;
    background: #f8fbff;
    border: 1px solid #e3ecfa;
    border-radius: 6px;
}
.info-item:hover {
    background: #eef4ff;
}
.info-label {
    font-weight: bold;
    color: #0c4ca3;
}

/* DROPDOWN */
.dropdown select {
    width: 100%;
    padding: 10px;
    border: 2px solid #d0e1ff;
    border-radius: 6px;
    margin-top: 5px;
}
.dropdown select:focus {
    border-color: #1a73e8;
}

/* KHUNG CHI TIẾT BỆNH ÁN */
.detail-box {
    background: #f8fbff;
    border: 1px solid #dce6f7;
    border-radius: 10px;
    padding: 15px;
    margin-top: 20px;
}

/* KHÔNG CÓ DỮ LIỆU */
.no-data {
    color: red;
    font-style: italic;
}
</style>

<script>
    const phieuKhamBenhNhan = <?php echo isset($data["PhieuKhamBenhNhan"]) ? $data["PhieuKhamBenhNhan"] : '[]'; ?>;

    function showMedicalDetails(ngayTao) {
        const box = document.getElementById("MedicalDetails");
        if (!ngayTao) {
            box.innerHTML = "<p>Vui lòng chọn một ngày khám để xem thông tin chi tiết.</p>";
            return;
        }

        const phieu = phieuKhamBenhNhan.find(x => x.NgayTao === ngayTao);
        if (!phieu) {
            box.innerHTML = "<p>Không tìm thấy thông tin bệnh án.</p>";
            return;
        }

        box.innerHTML = `
            <div class="info-grid">
                <div class="info-item"><span class="info-label">Ngày khám:</span> ${new Date(phieu.NgayTao).toLocaleDateString()}</div>
                <div class="info-item"><span class="info-label">Triệu chứng:</span> ${phieu.TrieuChung}</div>
                <div class="info-item"><span class="info-label">Kết quả:</span> ${phieu.KetQua}</div>
                <div class="info-item"><span class="info-label">Chuẩn đoán:</span> ${phieu.ChuanDoan}</div>
                <div class="info-item"><span class="info-label">Lời dặn:</span> ${phieu.LoiDan}</div>
                <div class="info-item"><span class="info-label">Ngày tái khám:</span> ${phieu.NgayTaiKham ? new Date(phieu.NgayTaiKham).toLocaleDateString() : 'Không có'}</div>
            </div>

            <div class="info-grid">
                <div class="info-item"><span class="info-label">Xét nghiệm:</span> ${phieu.LoaiXN || 'Không có'}</div>
                <div class="info-item"><span class="info-label">Kết quả xét nghiệm:</span> ${phieu.KetQuaXN || 'Không có'}</div>
            </div>

            <div class="info-grid">
                <div class="info-item"><span class="info-label">Tên thuốc:</span> ${phieu.TenThuoc}</div>
                <div class="info-item"><span class="info-label">Số lượng:</span> ${phieu.SoLuong}</div>
                <div class="info-item"><span class="info-label">Liều dùng:</span> ${phieu.LieuDung}</div>
                <div class="info-item"><span class="info-label">Cách dùng:</span> ${phieu.CachDung}</div>
            </div>
        `;
    }
</script>
