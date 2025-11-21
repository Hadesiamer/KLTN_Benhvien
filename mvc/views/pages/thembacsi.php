<div class="card">
    <div class="card-header">
        <h3>Thêm Bác sĩ mới</h3>
    </div>
    <div class="card-body">
        <?php if (isset($data["Error"])): ?>
            <div class="alert alert-danger"><?= $data["Error"] ?></div>
        <?php endif; ?>

        <form action="./ThemBS" method="POST">
            <div class="mb-3">
                <label for="HovaTen" class="form-label">Họ và Tên</label>
                <input type="text" class="form-control" id="HovaTen" name="HovaTen" required pattern="^[a-zA-ZÀ-ỹ\s]+$">
                <small class="form-text text-muted">Chỉ chấp nhận chữ cái và khoảng trắng.</small>
            </div>

            <div class="mb-3">
                <label for="NgaySinh" class="form-label">Ngày sinh</label>
                <!-- RÀNG BUỘC: ngày sinh trong khoảng từ 01/01/1930 đến 31/12/2003 -->
                <input type="date" 
                       class="form-control" 
                       id="NgaySinh" 
                       name="NgaySinh" 
                       required
                       min="1930-01-01"
                       max="2003-12-31">
                <!-- Gợi ý chung -->
                <small class="form-text text-muted">
                    Bác sĩ phải sinh trong khoảng từ năm 1930 đến năm 2003 (bao gồm). Không chấp nhận nhỏ hơn 1930 hoặc lớn hơn 2003.
                </small>
                <!-- Vùng hiển thị lỗi màu đỏ ngay dưới trường ngày sinh -->
                <small id="NgaySinhError" class="form-text text-danger"></small>
            </div>

            <div class="mb-3">
                <label for="GioiTinh" class="form-label">Giới tính</label>
                <select class="form-select" id="GioiTinh" name="GioiTinh" required>
                    <option value="Nam">Nam</option>
                    <option value="Nữ">Nữ</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="SoDT" class="form-label">Số điện thoại</label>
                <input type="tel" class="form-control" id="SoDT" name="SoDT" required pattern="[0-9]+">
                <small class="form-text text-muted">Chỉ chấp nhận số.</small>
            </div>

            <div class="mb-3">
                <label for="EmailNV" class="form-label">Email</label>
                <input type="email" class="form-control" id="EmailNV" name="EmailNV" required>
            </div>

            <div class="mb-3">
                <label for="MaKhoa" class="form-label">Chuyên khoa</label>
                <select class="form-select" id="MaKhoa" name="MaKhoa" required>
                <?php
                    // Lấy danh sách chuyên khoa
                    $ql = $this->model("mQLBS");
                    $specialties = $ql->GetAllChuyenKhoa();
                    if ($specialties && $specialties->num_rows > 0) {
                        foreach ($specialties as $specialty) {
                            echo "<option value='{$specialty['MaKhoa']}'>{$specialty['TenKhoa']}</option>";
                        }
                    } else {
                        echo "<option value=''>Không có chuyên khoa nào</option>";
                    }
                ?>
                </select>
            </div>

            <div class="text-center mt-3">
                <button type="submit" class="btn btn-success" name="btnThemBS">Thêm Bác sĩ</button>
            </div>
        </form>
    </div>
</div>

<!-- JS ràng buộc phía client:
     - Năm sinh phải <= 2003 (không chấp nhận 2004 trở lên)
     - Năm sinh phải >= 1930 (không chấp nhận 1929 trở xuống)
     - Hiển thị lỗi màu đỏ ngay dưới trường NgaySinh -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('form[action="./ThemBS"]');
        const ngaySinhInput = document.getElementById('NgaySinh');
        const ngaySinhError = document.getElementById('NgaySinhError');

        // Giới hạn hợp lệ: từ 01-01-1930 đến 31-12-2003
        const MAX_DATE = new Date('2003-12-31');  // trẻ nhất được phép
        const MIN_DATE = new Date('1930-01-01');  // già nhất được phép

        // Hàm kiểm tra hợp lệ ngày sinh
        function validateNgaySinh() {
            const value = ngaySinhInput.value;

            // Xóa trạng thái lỗi cũ
            ngaySinhError.textContent = '';
            ngaySinhInput.classList.remove('is-invalid');

            if (!value) {
                return true; // đã có required xử lý, ở đây coi như tạm OK
            }

            const birthday = new Date(value);

            // Check quá trẻ (sinh sau 31-12-2003)
            if (birthday > MAX_DATE) {
                ngaySinhError.textContent =
                    'Không hợp lệ: Bác sĩ phải sinh từ năm 2003 trở về trước (không chấp nhận 2004 trở lên).';
                ngaySinhInput.classList.add('is-invalid'); // thêm viền đỏ theo Bootstrap
                return false;
            }

            // Check quá già (sinh trước 01-01-1930)
            if (birthday < MIN_DATE) {
                ngaySinhError.textContent =
                    'Không hợp lệ: Bác sĩ phải sinh từ năm 1930 trở lên (không chấp nhận 1929 trở xuống).';
                ngaySinhInput.classList.add('is-invalid');
                return false;
            }

            // Hợp lệ
            return true;
        }

        if (form && ngaySinhInput && ngaySinhError) {
            // Kiểm tra mỗi khi người dùng thay đổi ngày sinh
            ngaySinhInput.addEventListener('change', validateNgaySinh);
            ngaySinhInput.addEventListener('input', validateNgaySinh);

            // Kiểm tra lại trước khi submit form
            form.addEventListener('submit', function (e) {
                const isValid = validateNgaySinh();
                if (!isValid) {
                    e.preventDefault(); // chặn submit nếu không hợp lệ
                    ngaySinhInput.focus();
                }
            });
        }
    });
</script>
