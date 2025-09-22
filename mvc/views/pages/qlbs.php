<div class="container">
<h2 class="mb-4">Danh sách Bác Sĩ</h2>
<?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success">
        <?php 
        echo $_SESSION['success_message'];
        unset($_SESSION['success_message']);
        ?>
    </div>
<?php endif; ?>
    <?php if (isset($data["Message"])): ?>
        <div class="alert alert-success"><?= $data["Message"] ?></div>
    <?php endif; ?>
    <?php if (isset($data["Error"])): ?>
        <div class="alert alert-danger"><?= $data["Error"] ?></div>
    <?php endif; ?>
<div class="mb-3">
    <label for="specialtyFilter" class="form-label">Chọn chuyên khoa:</label>
    <select id="specialtyFilter" class="form-select" onchange="filterDoctors()">
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
<div class="table-container" style="max-height: 300px; overflow-y: auto;">
    <table class="table table-striped table-bordered" style="width: 100%; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);">
        <thead style="position: sticky; top: 0; background-color: #fff;">
            <tr>
                <th>STT</th>
                <th>Mã NV</th>
                <th>Họ và Tên</th>
                <th>Chuyên khoa</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (isset($data['BacSi']) && !empty($data['BacSi'])) {
                $stt = 1;
                foreach ($data['BacSi'] as $row) {
                    echo "<tr data-specialty='{$row['MaKhoa']}'>
                        <td>{$stt}</td>
                        <td>{$row['MaNV']}</td>
                        <td>{$row['HovaTen']}</td>
                        <td>{$row['TenKhoa']}</td>
                        <td>
                            <form action='./CTBS' method='POST'>
                                <input type='hidden' name='ctbs' value='{$row['MaNV']}'>
                                <button class='btn btn-sm btn-primary' style='width: 100px;' type='submit' name='btnCTBS'>Xem chi tiết</button>
                            </form>
                        </td>
                    </tr>";
                    $stt++;
                }
            } else {
                echo "<tr><td colspan='5'>Không có dữ liệu bác sĩ.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
<div class="text-center mt-3">
    <a href="./ThemBS" class="btn btn-success">Thêm Bác sĩ mới</a>
</div>
</div>
<script>
function filterDoctors() {
    var specialty = document.getElementById('specialtyFilter').value;
    var rows = document.querySelectorAll('table tbody tr');
    
    rows.forEach(function(row) {
        if (specialty === '' || row.getAttribute('data-specialty') === specialty) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>

