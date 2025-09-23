
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
<?php
    if (isset($data["CP"])) {
        $cp = json_decode($data["CP"], true);
        if ($cp['success']) {
            echo '<div class="alert alert-success" role="alert">' . $cp['message'] . '</div>';
        } else {
            echo '<div class="alert alert-danger" role="alert">' . $cp['message'] . '</div>';
        }
    }
?>


