<?php
// Page: Thông tin bác sĩ đang đăng nhập
// Dữ liệu được truyền từ controller Bacsi::ThongTinBacSi()
// $data["thongtinbs"] là JSON do MBacsi::get1BS($maNV) trả về

$bacsi = null;

if (isset($data["thongtinbs"])) {
    $decoded = json_decode($data["thongtinbs"], true);
    if (is_array($decoded) && count($decoded) > 0) {
        $bacsi = $decoded[0];
    }
}
?>

<!-- TIÊU ĐỀ ĐƯA RA NGOÀI CONTAINER (giống xem thông tin bệnh nhân) -->
<h2 class="mb-4 bs-title-page">Thông tin bác sĩ</h2>

<?php if ($bacsi): ?>
    <div class="bs-container">

        <div class="bs-card">
            <!-- AVATAR + TÊN -->
            <div class="bs-avatar-col">
                <div class="bs-avatar-wrapper">
                    <img src="/KLTN_Benhvien/public/img/<?= htmlspecialchars($bacsi['HinhAnh'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                         alt="Ảnh đại diện">
                </div>
                <div class="bs-name-block">
                    <div class="bs-name">
                        <?= htmlspecialchars($bacsi['HovaTen'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                    </div>
                    <div class="bs-specialty">
                        <?= htmlspecialchars($bacsi['TenKhoa'] ?? 'Chuyên khoa: đang cập nhật', ENT_QUOTES, 'UTF-8') ?>
                    </div>
                </div>
            </div>

            <!-- THÔNG TIN -->
            <div class="bs-info-col">
                <table class="bs-table">
                    <tr><th>Mã bác sĩ</th><td><?= htmlspecialchars($bacsi['MaNV'] ?? '') ?></td></tr>
                    <tr><th>Họ tên</th><td><?= htmlspecialchars($bacsi['HovaTen'] ?? '') ?></td></tr>
                    <tr><th>Giới tính</th><td><?= htmlspecialchars($bacsi['GioiTinh'] ?? '') ?></td></tr>
                    <tr>
                        <th>Ngày sinh</th>
                        <td>
                            <?= !empty($bacsi['NgaySinh']) ? date("d-m-Y", strtotime($bacsi['NgaySinh'])) : "Đang cập nhật" ?>
                        </td>
                    </tr>
                    <tr><th>Số điện thoại</th><td><?= htmlspecialchars($bacsi['SoDT'] ?? '') ?></td></tr>
                    <tr><th>Email</th><td><?= htmlspecialchars($bacsi['EmailNV'] ?? '') ?></td></tr>
                    <tr><th>Chức vụ</th><td><?= htmlspecialchars($bacsi['ChucVu'] ?? "Bác sĩ") ?></td></tr>
                    <tr><th>Chuyên khoa</th><td><?= htmlspecialchars($bacsi['TenKhoa'] ?? '') ?></td></tr>
                    <tr><th>Trạng thái làm việc</th><td><?= htmlspecialchars($bacsi['TrangThaiLamViec'] ?? '') ?></td></tr>
                </table>
            </div>
        </div>
    </div>
<?php else: ?>
    <p class="bs-no-data">Không tìm thấy thông tin bác sĩ. Vui lòng kiểm tra lại tài khoản đăng nhập.</p>
<?php endif; ?>


<style>
    .bs-title-page {
        text-align: left !important;
        font-size: 26px;
        font-weight: bold;
        color: #111111ff;
        margin-bottom: 25px;
        margin-top: 0;
    }

    .bs-container {
        max-width: 900px;
        margin: 0 auto 40px;
        padding: 0 10px;
    }

    .bs-card {
        display: flex;
        flex-wrap: wrap;
        background: #ffffff;
        border-radius: 10px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        padding: 20px 25px;
        gap: 20px;
        border-left: 5px solid #1a73e8;
    }

    .bs-avatar-col {
        flex: 0 0 260px;
        display: flex;
        flex-direction: column;
        align-items: center;
        border-right: 1px solid #e3ecfa;
        padding-right: 16px;
    }

    .bs-avatar-wrapper img {
        width: 130px;
        height: 130px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #cce0ff;
        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        transition: 0.3s;
    }
    .bs-avatar-wrapper img:hover {
        transform: scale(1.05);
    }

    .bs-name {
        font-size: 20px;
        font-weight: bold;
        color: #0c4ca3;
    }
    .bs-specialty {
        color: #4a6fad;
        font-size: 14px;
    }

    .bs-info-col { flex: 1; }

    .bs-table {
        width: 100%;
        border-collapse: collapse;
    }

    .bs-table th {
        background: #f0f6ff;
        font-weight: bold;
        width: 35%;
        padding: 10px 12px;
        border-bottom: 1px solid #dbe4f0;
        color: #004a99;
    }
    .bs-table td {
        padding: 10px 12px;
        border-bottom: 1px solid #eaeaea;
    }
    .bs-table tr:hover td {
        background: #f8fbff;
    }

    .bs-no-data {
        color: red;
        font-style: italic;
    }

    @media (max-width: 768px) {
        .bs-card {
            flex-direction: column;
        }
        .bs-avatar-col {
            border-right: none;
            border-bottom: 1px solid #e3ecfa;
            padding-bottom: 16px;
        }
    }
</style>
