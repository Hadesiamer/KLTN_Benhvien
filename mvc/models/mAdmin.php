<?php
// Model cho actor Admin – xử lý đổi mật khẩu
class mAdmin extends DB {

    // Hàm kiểm tra mật khẩu cũ có đúng không - chức năng đổi mật khẩu
    public function KiemTraMatKhauCu($idAdmin, $matkhaucu) {
        $sql = "SELECT password FROM taikhoan WHERE ID = ?";
        $stmt = mysqli_prepare($this->con, $sql);
        mysqli_stmt_bind_param($stmt, "i", $idAdmin);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            // So sánh MD5 mật khẩu nhập vào với mật khẩu trong DB
            return $row['password'] === md5($matkhaucu);
        }
        return false;
    }

    // Hàm cập nhật mật khẩu mới (mã hóa MD5) - chức năng đổi mật khẩu
    public function DoiMatKhau($idAdmin, $matkhaumoi) {
        $sql = "UPDATE taikhoan SET password = ? WHERE ID = ?";
        $stmt = mysqli_prepare($this->con, $sql);
        $matkhaumoi_md5 = md5($matkhaumoi);
        mysqli_stmt_bind_param($stmt, "si", $matkhaumoi_md5, $idAdmin);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $ok;
    }
}
?>
