<?php
$danhSach    = isset($data["DanhSach"]) ? $data["DanhSach"] : [];
$dsKhoa      = isset($data["DanhSachKhoa"]) ? $data["DanhSachKhoa"] : [];
$dsNhanVien  = isset($data["NhanVienList"]) ? $data["NhanVienList"] : [];

$filterFrom   = isset($data["FilterFrom"]) ? $data["FilterFrom"] : date("Y-m-d");
$filterTo     = isset($data["FilterTo"]) ? $data["FilterTo"] : date("Y-m-d");
$filterChucVu = isset($data["FilterChucVu"]) ? $data["FilterChucVu"] : "";
$filterMaKhoa = isset($data["FilterMaKhoa"]) ? $data["FilterMaKhoa"] : "";
$filterSoDT   = isset($data["FilterSoDT"]) ? $data["FilterSoDT"] : "";
$filterMaNV   = isset($data["FilterMaNV"]) ? (int)$data["FilterMaNV"] : 0;
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-0">
            <i class="bi bi-list-check text-primary"></i>
            Danh sách điểm danh theo ngày
        </h4>
        <small class="text-muted">
            Có thể lọc theo khoảng ngày, chức vụ, chuyên khoa, nhân viên và số điện thoại.
        </small>
    </div>
</div>

<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <!-- [SỬA] Dùng POST, không truyền filter lên URL -->
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
                <select name="chucvu" id="filterChucVu" class="form-select form-select-sm">
                    <option value="">Tất cả</option>
                    <option value="Bác sĩ" <?php echo $filterChucVu === 'Bác sĩ' ? 'selected' : ''; ?>>Bác sĩ</option>
                    <option value="Nhân viên y tế" <?php echo $filterChucVu === 'Nhân viên y tế' ? 'selected' : ''; ?>>Nhân viên y tế</option>
                    <option value="Nhân viên nhà thuốc" <?php echo $filterChucVu === 'Nhân viên nhà thuốc' ? 'selected' : ''; ?>>Nhân viên nhà thuốc</option>
                </select>
            </div>

            <!-- Chuyên khoa (chỉ áp dụng cho Bác sĩ) -->
            <div class="col-md-2">
                <label class="form-label form-label-sm mb-1">Chuyên khoa (Bác sĩ)</label>
                <select name="makhoa" id="filterMaKhoa" class="form-select form-select-sm">
                    <option value="">Tất cả</option>
                    <?php foreach ($dsKhoa as $khoa): ?>
                        <option value="<?php echo (int)$khoa["MaKhoa"]; ?>"
                            <?php echo ($filterMaKhoa == (int)$khoa["MaKhoa"]) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($khoa["TenKhoa"]); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Nhân viên -->
            

            <!-- Số điện thoại -->
            <div class="col-md-2">
                <label class="form-label form-label-sm mb-1">Số điện thoại</label>
                <input type="text"
                       name="sdt"
                       class="form-control form-control-sm"
                       placeholder="Nhập SĐT"
                       value="<?php echo htmlspecialchars($filterSoDT); ?>">
            </div>

            <!-- Nút -->
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-primary mt-3 w-100">
                    <i class="bi bi-search"></i> Lọc
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <?php if (!empty($danhSach)): ?>
            <div class="table-responsive">
                <table class="table table-sm align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width:40px;">#</th>
                            <th style="min-width:100px;">Ngày</th>
                            <th style="min-width:80px;">Ca</th>
                            <th style="min-width:140px;">Thời gian điểm danh</th>
                            <th style="min-width:80px;">Mã NV</th>
                            <th style="min-width:160px;">Họ và tên</th>
                            <th style="min-width:110px;">SĐT</th>
                            <th style="min-width:130px;">Chức vụ</th>
                            <th style="min-width:150px;">Chuyên khoa</th>
                            <th style="min-width:140px;">Kết quả</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($danhSach as $index => $row): ?>
                            <?php
                                $ngay  = $row["NgayLamViec"];
                                $ca    = $row["CaLamViec"];
                                $time  = $row["ThoiGianDiemDanh"];
                                $maNV  = (int)$row["MaNV"];
                                $hoTen = $row["HovaTen"];
                                $sdt   = $row["SoDT"];
                                $chuc  = $row["ChucVu"];
                                $khoa  = isset($row["TenKhoa"]) ? $row["TenKhoa"] : "";
                                $kq    = $row["KetQua"];

                                // Tô màu badge kết quả theo nội dung
                                $kqClass = "bg-secondary";
                                if (strpos($kq, "Đúng giờ") === 0) {
                                    $kqClass = "bg-success";
                                } elseif (strpos($kq, "Đi sớm") === 0) {
                                    $kqClass = "bg-info";
                                } elseif (strpos($kq, "Đi trễ") === 0) {
                                    $kqClass = "bg-danger";
                                }
                            ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo htmlspecialchars($ngay); ?></td>
                                <td><?php echo htmlspecialchars($ca); ?></td>
                                <td><?php echo htmlspecialchars($time); ?></td>
                                <td><?php echo $maNV; ?></td>
                                <td><?php echo htmlspecialchars($hoTen); ?></td>
                                <td><?php echo htmlspecialchars($sdt); ?></td>
                                <td><?php echo htmlspecialchars($chuc); ?></td>
                                <td><?php echo htmlspecialchars($khoa); ?></td>
                                <td>
                                    <span class="badge <?php echo $kqClass; ?>">
                                        <?php echo htmlspecialchars($kq); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info mb-0">
                Không có bản ghi điểm danh nào trong khoảng 
                <strong><?php echo htmlspecialchars($filterFrom); ?></strong>
                đến
                <strong><?php echo htmlspecialchars($filterTo); ?></strong>
                với bộ lọc hiện tại.
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
// Ẩn/hiện filter chuyên khoa theo chức vụ
document.addEventListener("DOMContentLoaded", function () {
    const selChucVu = document.getElementById("filterChucVu");
    const selKhoa   = document.getElementById("filterMaKhoa");

    function updateKhoaVisibility() {
        if (!selChucVu || !selKhoa) return;
        if (selChucVu.value === "Bác sĩ") {
            selKhoa.removeAttribute("disabled");
        } else {
            selKhoa.value = "";
            selKhoa.setAttribute("disabled", "disabled");
        }
    }

    if (selChucVu && selKhoa) {
        updateKhoaVisibility();
        selChucVu.addEventListener("change", updateKhoaVisibility);
    }
});
</script>
