<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h2 class="h5 mb-3">Đổi mật khẩu</h2>
                <p class="text-muted small">
                    Để bảo mật tài khoản, nên đổi mật khẩu định kỳ và không chia sẻ cho người khác.
                </p>

                <form method="post" action="/KLTN_Benhvien/NVYT/DoiMatKhau" novalidate>
                    <div class="mb-3">
                        <label for="old_password" class="form-label">Mật khẩu hiện tại</label>
                        <input type="password" class="form-control" id="old_password" name="old_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">Mật khẩu mới</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Nhập lại mật khẩu mới</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="reset" class="btn btn-outline-secondary btn-sm">Nhập lại</button>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-key me-1"></i> Đổi mật khẩu
                        </button>
                    </div>
                </form>

                <?php if (isset($data["Message"])): ?>
                    <div class="alert alert-success shadow-sm mt-3 mb-0">
                        <?php echo $data["Message"]; ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($data["Error"])): ?>
                    <div class="alert alert-danger shadow-sm mt-3 mb-0">
                        <?php echo $data["Error"]; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
