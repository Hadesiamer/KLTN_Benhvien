
<div>
    <h2>Đổi mật khẩu</h2>
    <form method="POST" action="/KLTN_Benhvien/BN/changePass">
        <div class="form-group">
            <label for="oldPass">Mật khẩu hiện tại:</label>
            <input type="password" id="oldPass" name="oldPass" required class="form-control">
        </div>

        <div class="form-group">
            <label for="newPass">Mật khẩu mới:</label>
            <input type="password" id="newPass" name="newPass" required class="form-control">
            <label for="confirmPass">Nhập lại mật khẩu mới</label>
            <input type="password" id="confirmPass" name="confirmPass" required class="form-control">
        </div>
        <div>
            <input type="submit" name="btnChangePass" value="Đổi mật khẩu" class="btn btn-primary mt-3">
        </div>
            

    </form>
</div>

    <?php if (isset($data['CP'])): ?>
        <div class="alert <?= $data['CP']['success'] ? 'alert-success' : 'alert-danger' ?> mt-3" role="alert">
            <?= $data['CP']['message'] ?>
        </div>
    <?php endif; ?>
    



