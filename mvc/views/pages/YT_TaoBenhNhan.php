<div class="row g-3">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h2 class="h5 mb-3">Tạo bệnh nhân mới</h2>
                <p class="text-muted small">
                    Điền thông tin cơ bản của bệnh nhân để tạo hồ sơ mới trong hệ thống.
                </p>

                <form method="post" action="/KLTN_Benhvien/NVYT/TaoBenhNhanMoi" novalidate>
                    <?php
                    $old = isset($data['Old']) ? $data['Old'] : [];
                    ?>

                    <div class="mb-3">
                        <label for="HovaTen" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="HovaTen" name="HovaTen"
                               value="<?php echo htmlspecialchars($old['HovaTen'] ?? ''); ?>" required>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="NgaySinh" class="form-label">Ngày sinh <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="NgaySinh" name="NgaySinh"
                                   value="<?php echo htmlspecialchars($old['NgaySinh'] ?? ''); ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label for="GioiTinh" class="form-label">Giới tính <span class="text-danger">*</span></label>
                            <select class="form-select" id="GioiTinh" name="GioiTinh" required>
                                <?php $gtOld = $old['GioiTinh'] ?? ''; ?>
                                <option value="">-- Chọn giới tính --</option>
                                <option value="Nam"   <?php echo ($gtOld=='Nam') ? 'selected' : ''; ?>>Nam</option>
                                <option value="Nữ"    <?php echo ($gtOld=='Nữ') ? 'selected' : ''; ?>>Nữ</option>
                                <option value="Khác"  <?php echo ($gtOld=='Khác') ? 'selected' : ''; ?>>Khác</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="SoDT" class="form-label">Số điện thoại</label>
                            <input type="text" class="form-control" id="SoDT" name="SoDT"
                                   value="<?php echo htmlspecialchars($old['SoDT'] ?? ''); ?>"
                                   placeholder="Ví dụ: 0912345678">
                        </div>
                    </div>

                    <div class="mb-3 mt-3">
                        <label for="DiaChi" class="form-label">Địa chỉ</label>
                        <textarea class="form-control" id="DiaChi" name="DiaChi" rows="2"><?php
                            echo htmlspecialchars($old['DiaChi'] ?? '');
                        ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="BHYT" class="form-label">Mã BHYT</label>
                        <input type="text" class="form-control" id="BHYT" name="BHYT"
                               value="<?php echo htmlspecialchars($old['BHYT'] ?? ''); ?>">
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="reset" class="btn btn-outline-secondary btn-sm">
                            Xóa nhập
                        </button>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-save me-1"></i> Lưu hồ sơ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Cột thông tin / hướng dẫn -->
    <div class="col-lg-4">
        <?php if (isset($data["Message"])): ?>
            <div class="alert alert-success shadow-sm">
                <?php echo $data["Message"]; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($data["Error"])): ?>
            <div class="alert alert-danger shadow-sm">
                <?php echo $data["Error"]; ?>
            </div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h3 class="h6 mb-2">Lưu ý nhập liệu</h3>
                <ul class="small text-muted mb-0">
                    <li>Thông tin có dấu <span class="text-danger">*</span> là bắt buộc.</li>
                    <li>Nên kiểm tra kỹ họ tên và ngày sinh trước khi lưu.</li>
                    <li>Nếu bệnh nhân có BHYT, nhập đúng mã để thuận tiện thanh toán.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
