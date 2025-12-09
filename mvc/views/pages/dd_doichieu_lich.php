<?php
$danhSach    = isset($data["DanhSach"]) ? $data["DanhSach"] : [];
$dsKhoa      = isset($data["DanhSachKhoa"]) ? $data["DanhSachKhoa"] : [];

$filterFrom   = isset($data["FilterFrom"]) ? $data["FilterFrom"] : date("Y-m-d");
$filterTo     = isset($data["FilterTo"]) ? $data["FilterTo"] : date("Y-m-d");
$filterChucVu = isset($data["FilterChucVu"]) ? $data["FilterChucVu"] : "";
$filterMaKhoa = isset($data["FilterMaKhoa"]) ? $data["FilterMaKhoa"] : "";
$filterSoDT   = isset($data["FilterSoDT"]) ? $data["FilterSoDT"] : "";
?>

<div class="dd-page-wrapper" style="max-width: 1200px; margin: 0 auto;">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">
                <i class="bi bi-arrow-left-right text-primary"></i>
                Đối chiếu lịch làm việc &amp; điểm danh
            </h4>
            <small class="text-muted">
                Mỗi dòng là một ca trong lịch làm việc. Hệ thống sẽ kiểm tra xem ca đó đã được điểm danh hay chưa.
            </small>
        </div>
    </div>

    <!-- FORM TÌM KIẾM SĐT -->
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body py-2">
            <form method="POST" action="" class="row g-2 align-items-end">
                <div class="col-md-4 col-lg-3">
                    <label class="form-label form-label-sm mb-1">Tìm kiếm theo số điện thoại</label>
                    <div class="input-group input-group-sm">
                        <input type="text"
                               name="sdt"
                               class="form-control"
                               placeholder="Nhập SĐT cần tìm"
                               value="<?php echo htmlspecialchars($filterSoDT); ?>">
                        <button type="submit"
                                name="action"
                                value="search"
                                class="btn btn-outline-primary">
                            <i class="bi bi-search"></i>
                            Tìm kiếm
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- FORM BỘ LỌC (KHÔNG CÒN NHÂN VIÊN) -->
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body">
            <form method="POST" action="" class="row g-3 align-items-end">

                <!-- Khoảng ngày -->
                <div class="col-md-3 col-lg-3">
                    <label class="form-label form-label-sm mb-1">Từ ngày</label>
                    <input type="date" name="from"
                           class="form-control form-control-sm"
                           value="<?php echo htmlspecialchars($filterFrom); ?>">
                </div>

                <div class="col-md-3 col-lg-3">
                    <label class="form-label form-label-sm mb-1">Đến ngày</label>
                    <input type="date" name="to"
                           class="form-control form-control-sm"
                           value="<?php echo htmlspecialchars($filterTo); ?>">
                </div>

                <!-- Chức vụ -->
                <div class="col-md-2 col-lg-2">
                    <label class="form-label form-label-sm mb-1">Chức vụ</label>
                    <select name="chucvu" id="filterChucVu" class="form-select form-select-sm">
                        <option value="">Tất cả</option>
                        <option value="Bác sĩ" <?php echo $filterChucVu === 'Bác sĩ' ? 'selected' : ''; ?>>Bác sĩ</option>
                        <option value="Nhân viên y tế" <?php echo $filterChucVu === 'Nhân viên y tế' ? 'selected' : ''; ?>>Nhân viên y tế</option>
                        <option value="Nhân viên nhà thuốc" <?php echo $filterChucVu === 'Nhân viên nhà thuốc' ? 'selected' : ''; ?>>Nhân viên nhà thuốc</option>
                    </select>
                </div>

                <!-- Chuyên khoa -->
                <div class="col-md-2 col-lg-2">
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

                <!-- Nút áp dụng -->
                <div class="col-md-2 col-lg-2 d-flex">
                    <button type="submit"
                            name="action"
                            value="filter"
                            class="btn btn-sm btn-primary mt-3 w-100">
                        <i class="bi bi-funnel"></i> Áp dụng
                    </button>
                </div>

            </form>
        </div>
    </div>

    <!-- BẢNG DỮ LIỆU -->
    <div class="card shadow-sm border-0">
        <div class="card-body">

            <?php if (!empty($danhSach)): ?>
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Ngày</th>
                                <th>Ca</th>
                                <th>Mã NV</th>
                                <th>Họ và tên</th>
                                <th>SĐT</th>
                                <th>Chức vụ</th>
                                <th>Chuyên khoa</th>
                                <th>Trạng thái</th>
                                <th>Thời gian</th>
                                <th>Kết quả</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($danhSach as $index => $row): ?>
                                <?php
                                    $ngay      = $row["NgayLamViec"];
                                    $ca        = $row["CaLamViec"];
                                    $maNV      = (int)$row["MaNV"];
                                    $hoTen     = $row["HovaTen"];
                                    $sdt       = $row["SoDT"];
                                    $chuc      = $row["ChucVu"];
                                    $khoa      = isset($row["TenKhoa"]) ? $row["TenKhoa"] : "";
                                    $trangThai = $row["TrangThaiDoiChieu"];
                                    $timeDDRaw = $row["ThoiGianDiemDanh"];
                                    $kq        = $row["KetQua"];

                                    $timeDD = $timeDDRaw ? date("H:i:s", strtotime($timeDDRaw)) : null;

                                    $ttClass = $trangThai === "Đã điểm danh" ? "bg-success" : "bg-danger";

                                    if (strpos($kq, "Đúng giờ") === 0)      $kqClass = "bg-success";
                                    elseif (strpos($kq, "Đi sớm") === 0)   $kqClass = "bg-info";
                                    elseif (strpos($kq, "Đi trễ") === 0)   $kqClass = "bg-danger";
                                    else                                   $kqClass = "bg-secondary";
                                ?>
                                <tr>
                                    <td><?php echo $index + 1; ?></td>
                                    <td><?php echo $ngay; ?></td>
                                    <td><?php echo $ca; ?></td>
                                    <td><?php echo $maNV; ?></td>
                                    <td><?php echo htmlspecialchars($hoTen); ?></td>
                                    <td><?php echo htmlspecialchars($sdt); ?></td>
                                    <td><?php echo htmlspecialchars($chuc); ?></td>
                                    <td><?php echo htmlspecialchars($khoa); ?></td>
                                    <td><span class="badge <?php echo $ttClass; ?>"><?php echo $trangThai; ?></span></td>
                                    <td><?php echo $timeDD ?: '<span class="text-muted">-</span>'; ?></td>
                                    <td>
                                        <?php if (!empty($kq)): ?>
                                            <span class="badge <?php echo $kqClass; ?>"><?php echo $kq; ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            <?php else: ?>
                <div class="alert alert-info mb-0">
                    Không có lịch làm việc trong khoảng 
                    <strong><?php echo $filterFrom; ?></strong> –
                    <strong><?php echo $filterTo; ?></strong>.
                </div>
            <?php endif; ?>

        </div>
    </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const selChucVu = document.getElementById("filterChucVu");
    const selKhoa   = document.getElementById("filterMaKhoa");

    function updateKhoa() {
        if (selChucVu.value === "Bác sĩ") selKhoa.removeAttribute("disabled");
        else { selKhoa.value = ""; selKhoa.setAttribute("disabled", "disabled"); }
    }

    updateKhoa();
    selChucVu.addEventListener("change", updateKhoa);
});
</script>
