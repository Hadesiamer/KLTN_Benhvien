<?php
// ĐÂY LÀ FILE: mvc/views/pages/bn_lichsuthanhtoan.php

// Lấy dữ liệu từ controller
$lsttListJson   = isset($data['LSTT_List'])   ? $data['LSTT_List']   : '[]';
$lsttDetailJson = isset($data['LSTT_Detail']) ? $data['LSTT_Detail'] : '[]';
$currentMaLK    = isset($data['CurrentMaLK']) ? (int)$data['CurrentMaLK'] : 0;

$lichSuList   = json_decode($lsttListJson, true);
$lichSuList   = is_array($lichSuList) ? $lichSuList : [];

$chiTietArr   = json_decode($lsttDetailJson, true);
$chiTietArr   = is_array($chiTietArr) ? $chiTietArr : [];
$ct           = isset($chiTietArr[0]) ? $chiTietArr[0] : null;

// Hàm định dạng ngày/giờ an toàn
function bn_format_datetime($str, $format = 'd/m/Y H:i') {
    if (empty($str)) return '';
    $ts = strtotime($str);
    if ($ts === false) return htmlspecialchars($str);
    return date($format, $ts);
}
?>
<div class="bn-lichsu-thanhtoan">
    <h3 class="bn-main-title">Lịch sử thanh toán</h3>

    <div class="row">
        <!-- DANH SÁCH GIAO DỊCH -->
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-header">
                    <strong>Danh sách giao dịch</strong>
                </div>
                <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                    <?php if (empty($lichSuList)): ?>
                        <p class="text-muted mb-0">Chưa có giao dịch thanh toán nào.</p>
                    <?php else: ?>
                        <form id="form-chon-giao-dich" method="post" action="/KLTN_Benhvien/BN/LichSuThanhToan">
                            <input type="hidden" name="MaLK" id="input-selected-malk" value="">

                            <ul class="list-group">
                                <?php foreach ($lichSuList as $item): 
                                    $maLK   = (int)($item['MaLK'] ?? 0);
                                    $isActive = ($currentMaLK > 0 && $maLK === $currentMaLK);
                                    $created = bn_format_datetime($item['CreatedAt'] ?? '', 'd/m/Y H:i');
                                    $amount  = isset($item['Amount']) ? number_format((float)$item['Amount'], 0, ',', '.') : '0';
                                    $loaiDV  = htmlspecialchars($item['LoaiDichVu'] ?? '');
                                    $tenKhoa = htmlspecialchars($item['TenKhoa'] ?? '');
                                    $tenBS   = htmlspecialchars($item['TenBacSi'] ?? '');
                                ?>
                                    <li class="list-group-item list-group-item-action <?php echo $isActive ? 'active' : ''; ?>"
                                        style="cursor:pointer;"
                                        data-malk="<?php echo $maLK; ?>">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <div><strong>LK<?php echo $maLK; ?></strong></div>
                                                <div style="font-size: 0.85rem;">
                                                    <?php echo $created; ?>
                                                </div>
                                                <div style="font-size: 0.85rem;">
                                                    <?php echo $loaiDV !== '' ? $loaiDV : 'Khám bệnh'; ?>
                                                    <?php if ($tenKhoa !== ''): ?>
                                                        - <?php echo $tenKhoa; ?>
                                                    <?php endif; ?>
                                                </div>
                                                <?php if ($tenBS !== ''): ?>
                                                <div style="font-size: 0.85rem;">
                                                    BS. <?php echo $tenBS; ?>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="text-end">
                                                <div><strong><?php echo $amount; ?></strong></div>
                                                <div style="font-size: 0.85rem;">VNĐ</div>
                                            </div>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- CHI TIẾT GIAO DỊCH -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <strong>Chi tiết thanh toán</strong>
                </div>
                <div class="card-body">
                    <?php if ($ct === null): ?>
                        <p class="text-muted mb-0">Vui lòng chọn một giao dịch ở danh sách bên trái.</p>
                    <?php else: 
                        $maLK        = (int)($ct['MaLK'] ?? 0);
                        $ngayKham    = bn_format_datetime($ct['NgayKham'] ?? '', 'd/m/Y');
                        $gioKham     = htmlspecialchars($ct['GioKham'] ?? '');
                        $loaiDV      = htmlspecialchars($ct['LoaiDichVu'] ?? '');
                        $tenKhoa     = htmlspecialchars($ct['TenKhoa'] ?? '');
                        $moTaKhoa    = htmlspecialchars($ct['MoTaKhoa'] ?? '');
                        $tenBacSi    = htmlspecialchars($ct['TenBacSi'] ?? '');
                        $trangThai   = htmlspecialchars($ct['TrangThaiThanhToan'] ?? '');
                        $trieuChung  = htmlspecialchars($ct['TrieuChung'] ?? '');

                        $tenBN       = htmlspecialchars($ct['TenBenhNhan'] ?? '');
                        $maBN        = htmlspecialchars($ct['MaBN'] ?? '');
                        $ngaySinh    = bn_format_datetime($ct['NgaySinh'] ?? '', 'd/m/Y');
                        $gioiTinh    = htmlspecialchars($ct['GioiTinh'] ?? '');
                        $diaChi      = htmlspecialchars($ct['DiaChi'] ?? '');
                        $soDT        = htmlspecialchars($ct['SoDTBenhNhan'] ?? '');
                        $bhyt        = htmlspecialchars($ct['BHYT'] ?? '');

                        $amount      = isset($ct['Amount']) ? number_format((float)$ct['Amount'], 0, ',', '.') : '0';
                        $createdAt   = bn_format_datetime($ct['CreatedAt'] ?? '', 'd/m/Y H:i');
                        $bank        = htmlspecialchars($ct['Bank'] ?? '');
                        $accNumber   = htmlspecialchars($ct['AccountNumber'] ?? '');
                        $transCode   = htmlspecialchars($ct['TransactionCode'] ?? '');
                        $desc        = htmlspecialchars($ct['Description'] ?? '');
                        $rawContent  = htmlspecialchars($ct['RawContent'] ?? '');
                    ?>
                        <div class="mb-3">
                            <span class="badge bg-success">ĐÃ THANH TOÁN</span>
                            <span class="ms-2">Mã lịch khám: <strong>LK<?php echo $maLK; ?></strong></span>
                        </div>

                        <h6 class="mt-3">1. Thông tin khám bệnh</h6>
                        <table class="table table-sm">
                            <tr>
                                <th style="width: 25%;">Ngày - giờ khám</th>
                                <td><?php echo $ngayKham . ' ' . $gioKham; ?></td>
                            </tr>
                            <tr>
                                <th>Loại dịch vụ</th>
                                <td><?php echo $loaiDV !== '' ? $loaiDV : 'Khám bệnh'; ?></td>
                            </tr>
                            <tr>
                                <th>Chuyên khoa</th>
                                <td>
                                    <?php echo $tenKhoa; ?>
                                    <?php if ($moTaKhoa !== ''): ?>
                                        - <?php echo $moTaKhoa; ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Bác sĩ</th>
                                <td>BS. <?php echo $tenBacSi; ?></td>
                            </tr>
                            <tr>
                                <th>Triệu chứng</th>
                                <td><?php echo $trieuChung; ?></td>
                            </tr>
                        </table>

                        <h6 class="mt-3">2. Thông tin bệnh nhân</h6>
                        <table class="table table-sm">
                            <tr>
                                <th style="width: 25%;">Họ và tên</th>
                                <td><?php echo $tenBN; ?></td>
                            </tr>
                            <tr>
                                <th>Mã bệnh nhân</th>
                                <td><?php echo $maBN; ?></td>
                            </tr>
                            <tr>
                                <th>Ngày sinh</th>
                                <td><?php echo $ngaySinh; ?></td>
                            </tr>
                            <tr>
                                <th>Giới tính</th>
                                <td><?php echo $gioiTinh; ?></td>
                            </tr>
                            <tr>
                                <th>Số điện thoại</th>
                                <td><?php echo $soDT; ?></td>
                            </tr>
                            <tr>
                                <th>Địa chỉ</th>
                                <td><?php echo $diaChi; ?></td>
                            </tr>
                            <tr>
                                <th>BHYT</th>
                                <td><?php echo $bhyt; ?></td>
                            </tr>
                        </table>

                        <h6 class="mt-3">3. Thông tin thanh toán SePay</h6>
                        <table class="table table-sm">
                            <tr>
                                <th style="width: 25%;">Số tiền</th>
                                <td><strong><?php echo $amount; ?> VNĐ</strong></td>
                            </tr>
                            <tr>
                                <th>Thời điểm thanh toán</th>
                                <td><?php echo $createdAt; ?></td>
                            </tr>
                            <tr>
                                <th>Ngân hàng</th>
                                <td><?php echo $bank; ?></td>
                            </tr>
                            <tr>
                                <th>Tài khoản nhận</th>
                                <td><?php echo $accNumber; ?></td>
                            </tr>
                            <tr>
                                <th>Mã giao dịch</th>
                                <td><?php echo $transCode; ?></td>
                            </tr>
                            <tr>
                                <th>Nội dung chuyển khoản</th>
                                <td><?php echo $desc; ?></td>
                            </tr>
                        </table>

                        <?php if ($rawContent !== ''): ?>
                        <h6 class="mt-3">4. Dữ liệu kỹ thuật (Webhook)</h6>
                        <details>
                            <summary>Xem JSON webhook gốc</summary>
                            <pre style="max-height: 200px; overflow:auto;"><?php echo $rawContent; ?></pre>
                        </details>
                        <?php endif; ?>

                        <div class="mt-3">
                            <!-- [FIX] Dùng path param: /BN/LichKham/{MaLK} -->
                            <a href="/KLTN_Benhvien/BN/LichKham/<?php echo $maLK; ?>" class="btn btn-outline-primary btn-sm">
                                Xem lịch khám này
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Click 1 item bên trái -> submit form với MaLK tương ứng
document.addEventListener('DOMContentLoaded', function () {
    var items = document.querySelectorAll('#form-chon-giao-dich .list-group-item[data-malk]');
    var inputMaLK = document.getElementById('input-selected-malk');
    var form      = document.getElementById('form-chon-giao-dich');

    if (!items || !inputMaLK || !form) return;

    items.forEach(function (item) {
        item.addEventListener('click', function () {
            var malk = this.getAttribute('data-malk');
            if (malk) {
                inputMaLK.value = malk;
                form.submit();
            }
        });
    });
});
</script>
