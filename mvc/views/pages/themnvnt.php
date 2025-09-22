<div class="card">
    <div class="card-header">
        <h3>Thêm Nhân viên y tế mới</h3>
    </div>
    <div class="card-body">
        <?php if (isset($data["Error"])): ?>
            <div class="alert alert-danger"><?= $data["Error"] ?></div>
        <?php endif; ?>
        <form action="" method="POST">
            <div class="mb-3">
                <label for="HovaTen" class="form-label">Họ và Tên</label>
                <input type="text" class="form-control" id="HovaTen" name="HovaTen" required pattern="^[a-zA-ZÀ-ỹ\s]+$">
                <small class="form-text text-muted">Chỉ chấp nhận chữ cái và khoảng trắng.</small>
            </div>
            <div class="mb-3">
                <label for="NgaySinh" class="form-label">Ngày sinh</label>
                <input type="date" class="form-control" id="NgaySinh" name="NgaySinh" required>
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
            <button type="submit" class="btn btn-success" name="btnThemNVNT">Thêm Nhân viên</button>
        </form>
    </div>
</div>
