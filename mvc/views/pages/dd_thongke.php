<?php
$thongKeNV   = isset($data["ThongKeNhanVien"]) ? $data["ThongKeNhanVien"] : [];
$dsKhoa      = isset($data["DanhSachKhoa"]) ? $data["DanhSachKhoa"] : [];
$dsNhanVien  = isset($data["NhanVienList"]) ? $data["NhanVienList"] : [];

$filterFrom   = isset($data["FilterFrom"]) ? $data["FilterFrom"] : date("Y-m-d");
$filterTo     = isset($data["FilterTo"]) ? $data["FilterTo"] : date("Y-m-d");
$filterChucVu = isset($data["FilterChucVu"]) ? $data["FilterChucVu"] : "";
$filterMaKhoa = isset($data["FilterMaKhoa"]) ? $data["FilterMaKhoa"] : "";
$filterMaNV   = isset($data["FilterMaNV"]) ? (int)$data["FilterMaNV"] : 0;

$tkTongCa       = isset($data["TK_TongCa"]) ? (int)$data["TK_TongCa"] : 0;
$tkDaDiemDanh   = isset($data["TK_DaDiemDanh"]) ? (int)$data["TK_DaDiemDanh"] : 0;
$tkVang         = isset($data["TK_Vang"]) ? (int)$data["TK_Vang"] : 0;
$tkDungGio      = isset($data["TK_DungGio"]) ? (int)$data["TK_DungGio"] : 0;
$tkDiSom        = isset($data["TK_DiSom"]) ? (int)$data["TK_DiSom"] : 0;
$tkDiTre        = isset($data["TK_DiTre"]) ? (int)$data["TK_DiTre"] : 0;
$tkTyLeDD       = isset($data["TK_TyLeDiemDanh"]) ? $data["TK_TyLeDiemDanh"] : 0;
$tkTyLeDungGio  = isset($data["TK_TyLeDungGio"]) ? $data["TK_TyLeDungGio"] : 0.0;
?>

<div class="dd-page-wrapper" style="max-width: 1200px; margin: 0 auto;">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">
                <i class="bi bi-graph-up text-primary"></i>
                Thống kê tổng hợp điểm danh
            </h4>
            <small class="text-muted">
                Thống kê số ca làm việc, ca đã điểm danh, vắng, đúng giờ, đi sớm, đi trễ theo khoảng ngày và bộ lọc nhân sự.
            </small>
        </div>
    </div>

    <!-- FORM BỘ LỌC -->
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body">
            <form method="POST" action="" class="row g-3 align-items-end">
                <!-- Khoảng ngày -->
                <div class="col-md-3">
                    <label class="form-label form-label-sm mb-1">Từ ngày</label>
                    <input type="date"
                           name="from"
                           class="form-control form-control-sm"
                           value="<?php echo htmlspecialchars($filterFrom); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-sm mb-1">Đến ngày</label>
                    <input type="date"
                           name="to"
                           class="form-control form-control-sm"
                           value="<?php echo htmlspecialchars($filterTo); ?>">
                </div>

                <!-- Chức vụ -->
                <div class="col-md-2">
                    <label class="form-label form-label-sm mb-1">Chức vụ</label>
                    <select name="chucvu" id="filterChucVuTK" class="form-select form-select-sm">
                        <option value="">Tất cả</option>
                        <option value="Bác sĩ" <?php echo $filterChucVu === 'Bác sĩ' ? 'selected' : ''; ?>>Bác sĩ</option>
                        <option value="Nhân viên y tế" <?php echo $filterChucVu === 'Nhân viên y tế' ? 'selected' : ''; ?>>Nhân viên y tế</option>
                        <option value="Nhân viên nhà thuốc" <?php echo $filterChucVu === 'Nhân viên nhà thuốc' ? 'selected' : ''; ?>>Nhân viên nhà thuốc</option>
                        <option value="Nhân viên xét nghiệm" <?php echo $filterChucVu === 'Nhân viên xét nghiệm' ? 'selected' : ''; ?>>Nhân viên xét nghiệm</option>
                    </select>
                </div>

                <!-- Chuyên khoa -->
                <div class="col-md-2">
                    <label class="form-label form-label-sm mb-1">Chuyên khoa (Bác sĩ)</label>
                    <select name="makhoa" id="filterMaKhoaTK" class="form-select form-select-sm">
                        <option value="">Tất cả</option>
                        <?php foreach ($dsKhoa as $khoa): ?>
                            <option value="<?php echo (int)$khoa["MaKhoa"]; ?>"
                                <?php echo ($filterMaKhoa == (int)$khoa["MaKhoa"]) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($khoa["TenKhoa"]); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Nhân viên cụ thể -->
                <div class="col-md-4">
                    <label class="form-label form-label-sm mb-1">Nhân viên</label>
                    <select name="manv" class="form-select form-select-sm">
                        <option value="0">Tất cả nhân viên</option>
                        <?php foreach ($dsNhanVien as $nv): ?>
                            <option value="<?php echo (int)$nv["MaNV"]; ?>"
                                <?php echo ($filterMaNV === (int)$nv["MaNV"]) ? 'selected' : ''; ?>>
                                <?php
                                    $label = $nv["HovaTen"];
                                    if (!empty($nv["SoDT"])) {
                                        $label .= " - " . $nv["SoDT"];
                                    }
                                ?>
                                <?php echo htmlspecialchars($label); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Nút áp dụng -->
                <div class="col-md-2 d-flex">
                    <button type="submit"
                            class="btn btn-sm btn-primary mt-3 w-100">
                        <i class="bi bi-funnel"></i> Xem thống kê
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- THẺ THỐNG KÊ TỔNG QUAN -->
    <div class="row mb-3 g-3">
        <div class="col-md-3">
            <div class="card border-start border-primary border-3 shadow-sm h-100">
                <div class="card-body py-2">
                    <div class="small text-muted mb-1">Tổng số ca làm việc</div>
                    <div class="fs-4 fw-bold"><?php echo $tkTongCa; ?></div>
                    <div class="small text-muted">
                        Khoảng: <?php echo htmlspecialchars($filterFrom); ?> → <?php echo htmlspecialchars($filterTo); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-start border-success border-3 shadow-sm h-100">
                <div class="card-body py-2">
                    <div class="small text-muted mb-1">Ca đã điểm danh</div>
                    <div class="fs-4 fw-bold text-success"><?php echo $tkDaDiemDanh; ?></div>
                    <div class="small text-muted">
                        Tỷ lệ: <?php echo number_format($tkTyLeDD, 1); ?>%
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-start border-danger border-3 shadow-sm h-100">
                <div class="card-body py-2">
                    <div class="small text-muted mb-1">Ca vắng (chưa điểm danh)</div>
                    <div class="fs-4 fw-bold text-danger"><?php echo $tkVang; ?></div>
                    <div class="small text-muted">
                        Còn lại chưa có bản ghi điểm danh.
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-start border-info border-3 shadow-sm h-100">
                <div class="card-body py-2">
                    <div class="small text-muted mb-1">Đúng giờ trong số ca đã điểm danh</div>
                    <div class="fs-4 fw-bold text-info"><?php echo $tkDungGio; ?></div>
                    <div class="small text-muted">
                        Tỷ lệ: <?php echo number_format($tkTyLeDungGio, 1); ?>%
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- BẢNG THỐNG KÊ THEO NHÂN VIÊN -->
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h6 class="mb-2">
                <i class="bi bi-people text-primary"></i>
                Thống kê theo nhân viên
            </h6>
            <p class="text-muted small mb-3">
                Mỗi dòng là tổng hợp các ca làm việc của một nhân viên trong khoảng ngày đã chọn (sau khi áp dụng bộ lọc).
            </p>

            <?php if (!empty($thongKeNV)): ?>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width:40px;">#</th>
                                <th style="width:70px;">Mã NV</th>
                                <th style="min-width:150px;">Họ và tên</th>
                                <th style="min-width:110px;">Chức vụ</th>
                                <th style="min-width:130px;">Chuyên khoa</th>
                                <th style="width:80px;" class="text-center">Tổng ca</th>
                                <th style="width:90px;" class="text-center">Đã điểm danh</th>
                                <th style="width:70px;" class="text-center">Vắng</th>
                                <th style="width:80px;" class="text-center">Đúng giờ</th>
                                <th style="width:80px;" class="text-center">Đi sớm</th>
                                <th style="width:80px;" class="text-center">Đi trễ</th>
                                <th style="width:90px;" class="text-center">% đúng giờ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($thongKeNV as $index => $row): ?>
                                <?php
                                    $maNV     = (int)$row["MaNV"];
                                    $hoTen    = $row["HovaTen"];
                                    $chucVu   = $row["ChucVu"];
                                    $tenKhoa  = isset($row["TenKhoa"]) ? $row["TenKhoa"] : "";

                                    $tongCaNV     = (int)$row["TongCa"];
                                    $daDDNV       = (int)$row["SoCaDaDiemDanh"];
                                    $vangNV       = (int)$row["SoCaVang"];
                                    $dungGioNV    = (int)$row["SoCaDungGio"];
                                    $diSomNV      = (int)$row["SoCaDiSom"];
                                    $diTreNV      = (int)$row["SoCaDiTre"];
                                    $tyLeDungGioNV = $daDDNV > 0 ? round($dungGioNV * 100 / $daDDNV, 1) : 0;
                                ?>
                                <tr>
                                    <td><?php echo $index + 1; ?></td>
                                    <td><?php echo $maNV; ?></td>
                                    <td><?php echo htmlspecialchars($hoTen); ?></td>
                                    <td><?php echo htmlspecialchars($chucVu); ?></td>
                                    <td><?php echo htmlspecialchars($tenKhoa); ?></td>
                                    <td class="text-center"><?php echo $tongCaNV; ?></td>
                                    <td class="text-center text-success fw-semibold"><?php echo $daDDNV; ?></td>
                                    <td class="text-center text-danger fw-semibold"><?php echo $vangNV; ?></td>
                                    <td class="text-center"><?php echo $dungGioNV; ?></td>
                                    <td class="text-center"><?php echo $diSomNV; ?></td>
                                    <td class="text-center"><?php echo $diTreNV; ?></td>
                                    <td class="text-center">
                                        <?php echo number_format($tyLeDungGioNV, 1); ?>%
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info mb-0">
                    Không có dữ liệu thống kê trong khoảng
                    <strong><?php echo htmlspecialchars($filterFrom); ?></strong>
                    đến
                    <strong><?php echo htmlspecialchars($filterTo); ?></strong>
                    với bộ lọc hiện tại.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// JS: nếu chức vụ khác 'Bác sĩ' thì disable dropdown khoa
document.addEventListener("DOMContentLoaded", function () {
    const chucVuSelect = document.getElementById("filterChucVuTK");
    const khoaSelect   = document.getElementById("filterMaKhoaTK");

    function updateKhoaState() {
        if (!chucVuSelect || !khoaSelect) return;
        if (chucVuSelect.value === "Bác sĩ") {
            khoaSelect.disabled = false;
            khoaSelect.classList.remove("bg-light");
        } else {
            khoaSelect.value = "";
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
