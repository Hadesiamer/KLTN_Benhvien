<?php
$nhanvien = isset($data["NhanVien"]) ? $data["NhanVien"] : [];
$dsKhoa   = isset($data["DanhSachKhoa"]) ? $data["DanhSachKhoa"] : [];

$filterChucVu = isset($data["FilterChucVu"]) ? $data["FilterChucVu"] : "";
$filterMaKhoa = isset($data["FilterMaKhoa"]) ? $data["FilterMaKhoa"] : "";
$filterSoDT   = isset($data["FilterSoDT"]) ? $data["FilterSoDT"] : "";

// Đếm thống kê nhanh
$totalNV   = count($nhanvien);
$daDangKy  = 0;
foreach ($nhanvien as $nv) {
    if (!empty($nv["HasFace"])) {
        $daDangKy++;
    }
}
$chuaDangKy = $totalNV - $daDangKy;

// Danh sách chức vụ dùng cho filter
$dsChucVu = [
    ""                     => "Tất cả chức vụ",
    "Bác sĩ"               => "Bác sĩ",
    "Nhân viên y tế"       => "Nhân viên y tế",
    "Nhân viên nhà thuốc"  => "Nhân viên nhà thuốc",
    "Nhân viên xét nghiệm" => "Nhân viên xét nghiệm"
];
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-0">
            <i class="bi bi-person-badge text-primary"></i>
            Quản lý mẫu khuôn mặt nhân viên
        </h4>
        <small class="text-muted">
            Lọc theo chức vụ / khoa hoặc tìm nhanh theo số điện thoại. 
            Cột <strong>Trạng thái khuôn mặt</strong> cho biết nhân viên đã đăng ký mẫu hay chưa.
        </small>
    </div>
</div>

<!-- Hàng Filter + Search -->
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <div class="row g-3 align-items-end">
            <!-- FORM 1: FILTER (chức vụ + khoa) -->
            <div class="col-lg-8">
                <form class="row g-2 align-items-end" method="POST" action="./DD_QuanLyMau">
                    <div class="col-md-6">
                        <label class="form-label mb-1">Chức vụ</label>
                        <select name="chucvu" id="filterChucVu" class="form-select form-select-sm">
                            <?php foreach ($dsChucVu as $value => $label): ?>
                                <option value="<?php echo htmlspecialchars($value); ?>"
                                    <?php echo ($filterChucVu === $value ? "selected" : ""); ?>>
                                    <?php echo htmlspecialchars($label); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label mb-1">Chuyên khoa (Bác sĩ)</label>
                        <select name="makhoa" id="filterMaKhoa" class="form-select form-select-sm">
                            <option value="">Tất cả khoa</option>
                            <?php foreach ($dsKhoa as $khoa): ?>
                                <option value="<?php echo (int)$khoa["MaKhoa"]; ?>"
                                    <?php echo ($filterMaKhoa == $khoa["MaKhoa"] ? "selected" : ""); ?>>
                                    <?php echo htmlspecialchars($khoa["TenKhoa"]); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-2 d-grid">
                        <button type="submit" name="btnFilter" class="btn btn-sm btn-primary">
                            <i class="bi bi-funnel"></i> Lọc
                        </button>
                    </div>
                </form>
            </div>

            <!-- FORM 2: SEARCH SĐT RIÊNG -->
            <div class="col-lg-4">
                <form class="row g-2 align-items-end" method="POST" action="./DD_QuanLyMau">
                    <!-- Giữ lại filter hiện tại khi search -->
                    <input type="hidden" name="chucvu" value="<?php echo htmlspecialchars($filterChucVu); ?>">
                    <input type="hidden" name="makhoa" value="<?php echo htmlspecialchars($filterMaKhoa); ?>">

                    <div class="col-7 col-md-8">
                        <label class="form-label mb-1">Tìm theo SĐT</label>
                        <div class="input-group input-group-sm">
                            <input type="text"
                                   name="sdt"
                                   class="form-control"
                                   placeholder="Nhập số điện thoại..."
                                   value="<?php echo htmlspecialchars($filterSoDT); ?>">
                        </div>
                    </div>
                    <div class="col-5 col-md-4 d-grid">
                        <button type="submit" name="btnSearch" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-search"></i> Tìm
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Thống kê nhanh -->
<div class="row mb-3">
    <div class="col-md-4 mb-2">
        <div class="card border-start border-primary border-3 shadow-sm h-100">
            <div class="card-body py-2">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">Tổng nhân viên</div>
                        <div class="fs-5 fw-bold"><?php echo $totalNV; ?></div>
                    </div>
                    <i class="bi bi-people fs-3 text-primary"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-2">
        <div class="card border-start border-success border-3 shadow-sm h-100">
            <div class="card-body py-2">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">Đã đăng ký khuôn mặt</div>
                        <div class="fs-5 fw-bold text-success"><?php echo $daDangKy; ?></div>
                    </div>
                    <i class="bi bi-check-circle fs-3 text-success"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-2">
        <div class="card border-start border-danger border-3 shadow-sm h-100">
            <div class="card-body py-2">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">Chưa đăng ký</div>
                        <div class="fs-5 fw-bold text-danger"><?php echo $chuaDangKy; ?></div>
                    </div>
                    <i class="bi bi-exclamation-circle fs-3 text-danger"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bảng danh sách nhân viên -->
<div class="card shadow-sm border-0">
    <div class="card-body">
        <?php if ($totalNV > 0): ?>
            <div class="table-responsive">
                <table class="table table-sm table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width:60px;">Mã NV</th>
                            <th style="min-width:160px;">Họ và tên</th>
                            <th style="min-width:120px;">Chức vụ</th>
                            <th style="min-width:140px;">Chuyên khoa</th>
                            <th style="min-width:120px;">SĐT</th>
                            <th style="min-width:160px;">Email</th>
                            <th style="min-width:140px;">Trạng thái khuôn mặt</th>
                            <th style="min-width:140px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($nhanvien as $nv): ?>
                            <?php
                                $hasFace = !empty($nv["HasFace"]);
                                $tenKhoa = $nv["TenKhoa"] ?? "";
                            ?>
                            <tr>
                                <td><?php echo (int)$nv["MaNV"]; ?></td>
                                <td><?php echo htmlspecialchars($nv["HovaTen"]); ?></td>
                                <td><?php echo htmlspecialchars($nv["ChucVu"]); ?></td>
                                <td><?php echo htmlspecialchars($tenKhoa); ?></td>
                                <td><?php echo htmlspecialchars($nv["SoDT"]); ?></td>
                                <td><?php echo htmlspecialchars($nv["EmailNV"]); ?></td>
                                <td>
                                    <?php if ($hasFace): ?>
                                        <span class="badge rounded-pill bg-success">
                                            <i class="bi bi-check2-circle"></i> Đã đăng ký
                                        </span>
                                        <?php if (!empty($nv["UpdatedAt"])): ?>
                                            <div class="small text-muted">
                                                Cập nhật: <?php echo htmlspecialchars($nv["UpdatedAt"]); ?>
                                            </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="badge rounded-pill bg-danger">
                                            <i class="bi bi-x-circle"></i> Chưa đăng ký
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <form method="POST" action="./DD_Enroll" class="d-inline">
                                        <input type="hidden" name="MaNV" value="<?php echo (int)$nv["MaNV"]; ?>">
                                        <button type="submit"
                                                class="btn btn-sm <?php echo $hasFace ? 'btn-outline-primary' : 'btn-outline-success'; ?>">
                                            <i class="bi bi-camera-video"></i>
                                            <?php echo $hasFace ? 'Cập nhật mẫu' : 'Đăng ký mẫu'; ?>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info mb-0">
                Không tìm thấy nhân viên nào phù hợp với bộ lọc / tìm kiếm hiện tại.
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
// JS: nếu chức vụ khác 'Bác sĩ' thì disable dropdown khoa
document.addEventListener("DOMContentLoaded", function () {
    const chucVuSelect = document.getElementById("filterChucVu");
    const khoaSelect   = document.getElementById("filterMaKhoa");

    function updateKhoaState() {
        if (!chucVuSelect || !khoaSelect) return;
        if (chucVuSelect.value === "Bác sĩ") {
            khoaSelect.disabled = false;
            khoaSelect.classList.remove("bg-light");
        } else {
            khoaSelect.disabled = true;
            khoaSelect.classList.add("bg-light");
        }
    }

    if (chucVuSelect) {
        chucVuSelect.addEventListener("change", updateKhoaState);
        updateKhoaState();
    }
});
</script>
