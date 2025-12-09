<div class="mb-3">
    <!-- Header section -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h2 class="h5 mb-1">Danh sách Bác sĩ</h2>
            <p class="text-muted small mb-0">
                Nếu thêm bác sĩ mới, tài khoản bác sĩ đó là số điện thoại, mật khẩu là 123456
            </p>
        </div>
        <a href="./ThemBS" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i> Thêm Bác sĩ mới
        </a>
    </div>

    <!-- Thông báo session (thêm/sửa/xóa) -->
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success shadow-sm">
            <?php 
                echo $_SESSION['success_message'];
                unset($_SESSION['success_message']);
            ?>
        </div>
    <?php endif; ?>

    <!-- Thông báo theo data -->
    <?php if (isset($data["Message"])): ?>
        <div class="alert alert-success shadow-sm"><?= $data["Message"] ?></div>
    <?php endif; ?>

    <?php if (isset($data["Error"])): ?>
        <div class="alert alert-danger shadow-sm"><?= $data["Error"] ?></div>
    <?php endif; ?>

    <!-- Bộ lọc chuyên khoa -->
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <div class="row g-2 align-items-center">
                <div class="col-sm-4 col-md-3">
                    <label for="specialtyFilter" class="form-label mb-0 fw-semibold">
                        Chuyên khoa
                    </label>
                </div>
                <div class="col-sm-8 col-md-5">
                    <select id="specialtyFilter" class="form-select form-select-sm" onchange="filterDoctors()">
                        <option value="">Tất cả chuyên khoa</option>
                        <?php
                        $ql = $this->model("mQLBS");
                        $specialties = $ql->GetAllChuyenKhoa();
                        while ($specialty = $specialties->fetch_assoc()) {
                            echo "<option value='{$specialty['MaKhoa']}'>{$specialty['TenKhoa']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-12 col-md-4 text-muted small mt-2 mt-md-0">
                    <i class="bi bi-info-circle me-1"></i>
                    Chọn chuyên khoa để thu hẹp danh sách hiển thị.
                </div>
            </div>
        </div>
    </div>

    <!-- Bảng danh sách bác sĩ -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive" style="max-height: 360px; overflow-y: auto;">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light" style="position: sticky; top: 0;">
                        <tr>
                            <th scope="col" style="width: 60px;">STT</th>
                            <th scope="col">Mã NV</th>
                            <th scope="col">Họ và Tên</th>
                            <th scope="col">Chuyên khoa</th>
                            <th scope="col" class="text-center" style="width: 140px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (isset($data['BacSi']) && !empty($data['BacSi'])) {
                            $stt = 1;
                            foreach ($data['BacSi'] as $row) {
                                echo "<tr data-specialty='{$row['MaKhoa']}'>
                                    <td>{$stt}</td>
                                    <td class='text-nowrap'>{$row['MaNV']}</td>
                                    <td>{$row['HovaTen']}</td>
                                    <td>{$row['TenKhoa']}</td>
                                    <td class='text-center'>
                                        <form action='./CTBS' method='POST' class='d-inline'>
                                            <input type='hidden' name='ctbs' value='{$row['MaNV']}'>
                                            <button class='btn btn-sm btn-outline-primary' style='min-width: 110px;' type='submit' name='btnCTBS'>
                                                Xem chi tiết
                                            </button>
                                        </form>
                                    </td>
                                </tr>";
                                $stt++;
                            }
                        } else {
                            echo "<tr><td colspan='5' class='text-center text-muted'>Không có dữ liệu bác sĩ.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
// Lọc bác sĩ theo chuyên khoa (giữ nguyên logic cũ, chỉ format lại)
// UI: ẩn/hiện hàng theo data-specialty
function filterDoctors() {
    var specialty = document.getElementById('specialtyFilter').value;
    var rows = document.querySelectorAll('table tbody tr');
    
    rows.forEach(function(row) {
        var rowSpecialty = row.getAttribute('data-specialty');
        if (!rowSpecialty) {
            // Hàng "Không có dữ liệu" vẫn hiển thị khi không có bác sĩ
            row.style.display = '';
            return;
        }

        if (specialty === '' || rowSpecialty === specialty) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>
