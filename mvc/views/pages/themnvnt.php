<div class="card">
    <div class="card-header">
        <h3>Thêm Nhân viên y tế mới</h3>
    </div>
    <div class="card-body">
        <?php if (isset($data["Error"])): ?>
            <div class="alert alert-danger"><?= $data["Error"] ?></div>
        <?php endif; ?>

        <form action="./ThemNVYT" method="POST">
            <div class="mb-3">
                <label for="HovaTen" class="form-label">Họ và Tên</label>
                <input type="text" class="form-control" id="HovaTen" name="HovaTen" required pattern="^[a-zA-ZÀ-ỹ\s]+$">
                <small class="form-text text-muted">Chỉ chấp nhận chữ cái và khoảng trắng.</small>
            </div>

            <div class="mb-3">
                <label for="NgaySinh" class="form-label">Ngày sinh</label>
                <!-- RÀNG BUỘC: ngày sinh từ 01/01/1930 đến 31/12/2003 -->
                <input type="date" 
                       class="form-control" 
                       id="NgaySinh" 
                       name="NgaySinh" 
                       required
                       min="1930-01-01"
                       max="2003-12-31">
                <small class="form-text text-muted">
                    Nhân viên phải sinh trong khoảng từ 1930 đến 2003 (bao gồm).
                </small>
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

            <div class="text-center mt-3">
                <button type="submit" class="btn btn-success" name="btnThemNVYT">Thêm Nhân viên</button>
            </div>
        </form>
    </div>
</div>

<!-- JS ràng buộc ngày sinh: [1930 – 2003], hiển thị lỗi màu đỏ ngay dưới trường -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('form[action="./ThemNVYT"]');
        const ngaySinhInput = document.getElementById('NgaySinh');
        const ngaySinhError = document.getElementById('NgaySinhError');

        const MAX_DATE = new Date('2003-12-31');  
        const MIN_DATE = new Date('1930-01-01');

        function validateNgaySinh() {
            const value = ngaySinhInput.value;

            ngaySinhError.textContent = '';
            ngaySinhInput.classList.remove('is-invalid');

            if (!value) return true;

            const birthday = new Date(value);

            // Check quá trẻ
            if (birthday > MAX_DATE) {
                ngaySinhError.textContent =
                    'Không hợp lệ: Nhân viên phải sinh từ năm 2003 trở về trước (không chấp nhận 2004 trở lên).';
                ngaySinhInput.classList.add('is-invalid');
                return false;
            }

            // Check quá già
            if (birthday < MIN_DATE) {
                ngaySinhError.textContent =
                    'Không hợp lệ: Nhân viên phải sinh từ năm 1930 trở lên (không chấp nhận 1929 trở xuống).';
                ngaySinhInput.classList.add('is-invalid');
                return false;
            }

            return true;
        }

        if (form && ngaySinhInput && ngaySinhError) {
            ngaySinhInput.addEventListener('change', validateNgaySinh);
            ngaySinhInput.addEventListener('input', validateNgaySinh);

            form.addEventListener('submit', function (e) {
                if (!validateNgaySinh()) {
                    e.preventDefault();
                    ngaySinhInput.focus();
                }
            });
        }
    });
</script>
