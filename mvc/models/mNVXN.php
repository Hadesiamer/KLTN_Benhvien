<?php
// Model cho actor Nhân viên xét nghiệm (role = 6)
class mNVXN extends DB {

    // Kiểm tra mật khẩu cũ có đúng không (chỉ cho tài khoản MaPQ = 6)
    public function KiemTraMatKhauCu($idNV, $matkhaucu) {
        $sql = "SELECT password FROM taikhoan WHERE ID = ? AND MaPQ = 6";
        $stmt = mysqli_prepare($this->con, $sql);
        if (!$stmt) {
            return false;
        }

        mysqli_stmt_bind_param($stmt, "i", $idNV);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            // So sánh MD5 mật khẩu nhập với mật khẩu trong DB
            return $row["password"] === md5($matkhaucu);
        }

        return false;
    }

    // Cập nhật mật khẩu mới (mã hóa MD5) cho NVXN (MaPQ = 6)
    public function DoiMatKhau($idNV, $matkhaumoi) {
        $sql = "UPDATE taikhoan SET password = ? WHERE ID = ? AND MaPQ = 6";
        $stmt = mysqli_prepare($this->con, $sql);
        if (!$stmt) {
            return false;
        }

        $matkhaumoi_md5 = md5($matkhaumoi);
        mysqli_stmt_bind_param($stmt, "si", $matkhaumoi_md5, $idNV);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        return $ok;
    }

    // Lấy thông tin cá nhân của NVXN từ bảng nhanvien
    public function GetThongTinCaNhan($idNV) {
        $sql = "SELECT MaNV, HovaTen, NgaySinh, GioiTinh, SoDT, EmailNV
                FROM nhanvien
                WHERE MaNV = ? AND ChucVu = 'Nhân viên xét nghiệm'";
        $stmt = mysqli_prepare($this->con, $sql);
        if (!$stmt) {
            return [];
        }

        mysqli_stmt_bind_param($stmt, "i", $idNV);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $mang = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $mang[] = $row;
            }
        }

        mysqli_stmt_close($stmt);
        return $mang;
    }

    // Lấy lịch làm việc của NVXN từ bảng lichlamviec
    public function GetLichLamViec($idNV) {
        $sql = "SELECT NgayLamViec, CaLamViec, TrangThai
                FROM lichlamviec
                WHERE MaNV = ?
                ORDER BY NgayLamViec DESC, CaLamViec ASC";
        $stmt = mysqli_prepare($this->con, $sql);
        if (!$stmt) {
            return [];
        }

        mysqli_stmt_bind_param($stmt, "i", $idNV);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $mang = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $mang[] = $row;
            }
        }

        mysqli_stmt_close($stmt);
        return $mang;
    }
}
?>
