<?php
$profile = isset($data['Profile']) ? $data['Profile'] : null;
?>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <h2 class="h5 mb-3">Thông tin cá nhân</h2>
        <p class="text-muted small">
            Cập nhật thông tin cơ bản của bạn trong hệ thống.
        </p>

        <?php if (!$profile): ?>
            <div class="alert alert-warning">
                Không tìm thấy thông tin nhân viên. Vui lòng liên hệ quản trị hệ thống.
            </div>
        <?php else: ?>
            <form method="post" action="/KLTN_Benhvien/NVYT/CapNhatThongTinCaNhan" novalidate>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Mã nhân viên</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($profile['MaNV']); ?>" disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Họ và tên</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($profile['HovaTen']); ?>" disabled>
                    </div>
                </div>

                <div class="row g-3 mt-1">
                    <div class="col-md-4">
                        <label for="NgaySinh" class="form-label">Ngày sinh</label>
                        <input type="date" class="form-control" id="NgaySinh" name="NgaySinh"
                               value="<?php echo htmlspecialchars($profile['NgaySinh']); ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="GioiTinh" class="form-label">Giới tính</label>
                        <?php $gt = $profile['GioiTinh']; ?>
                        <select class="form-select" id="GioiTinh" name="GioiTinh">
                            <option value="Nam"  <?php echo ($gt=='Nam')?'selected':''; ?>>Nam</option>
                            <option value="Nữ"   <?php echo ($gt=='Nữ')?'selected':''; ?>>Nữ</option>
                            <option value="Khác" <?php echo ($gt=='Khác')?'selected':''; ?>>Khác</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="SoDT" class="form-label">Số điện thoại</label>
                        <input type="text" class="form-control" id="SoDT" name="SoDT"
                               value="<?php echo htmlspecialchars($profile['SoDT']); ?>">
                    </div>
                </div>

                <div class="mt-3">
                    <label for="EmailNV" class="form-label">Email</label>
                    <input type="email" class="form-control" id="EmailNV" name="EmailNV"
                           value="<?php echo htmlspecialchars($profile['EmailNV']); ?>">
                </div>

                <div class="d-flex justify-content-end gap-2 mt-3">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-save me-1"></i> Lưu thay đổi
                    </button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>
