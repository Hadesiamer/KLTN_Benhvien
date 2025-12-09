<?php
class mNVYT extends DB {

    // =============== LỊCH KHÁM ===============
    // Lấy lịch khám theo ngày cho 1 NVYT (nếu có cơ chế riêng, bạn chỉnh lại SQL)
    public function GetLichKhamTheoNgay($maNV, $ngay) {
        // Ví dụ: lấy từ bảng phieukham hoặc lichlamviec + phân loại NVYT
        // Ở đây mình để SQL rất generic, bạn chỉnh theo schema thực tế.
        $sql = "SELECT pk.MaPK, pk.NgayTao AS NgayKham, bn.HovaTen AS TenBenhNhan, pk.KetQua
                FROM phieukham pk
                JOIN benhnhan bn ON pk.MaBN = bn.MaBN
                WHERE pk.NgayTao = ?";

        $stmt = mysqli_prepare($this->con, $sql);
        if (!$stmt) return [];

        mysqli_stmt_bind_param($stmt, "s", $ngay);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $data = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
        }
        mysqli_stmt_close($stmt);

        return $data; // trả mảng thuần cho controller
    }

    // =============== BỆNH NHÂN ===============
    public function TaoBenhNhanMoi($hovaten, $ngaysinh, $gioitinh, $diachi, $sodt, $bhyt) {
        $sql = "INSERT INTO benhnhan (HovaTen, NgaySinh, GioiTinh, DiaChi, SoDT, BHYT)
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($this->con, $sql);
        if (!$stmt) {
            return ["success" => false];
        }

        mysqli_stmt_bind_param($stmt, "ssssss", $hovaten, $ngaysinh, $gioitinh, $diachi, $sodt, $bhyt);
        $ok = mysqli_stmt_execute($stmt);

        if ($ok) {
            $maBN = mysqli_insert_id($this->con);
            mysqli_stmt_close($stmt);
            return [
                "success" => true,
                "MaBN"    => $maBN
            ];
        } else {
            mysqli_stmt_close($stmt);
            return ["success" => false];
        }
    }

    // =============== THÔNG TIN CÁ NHÂN NVYT ===============
    public function GetThongTinCaNhan($maNV) {
        $sql = "SELECT MaNV, HovaTen, NgaySinh, GioiTinh, EmailNV, SoDT
                FROM nhanvien
                WHERE MaNV = ?
                LIMIT 1";

        $stmt = mysqli_prepare($this->con, $sql);
        if (!$stmt) return null;

        mysqli_stmt_bind_param($stmt, "s", $maNV);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $row = null;
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
        }

        mysqli_stmt_close($stmt);
        return $row;
    }

    public function CapNhatThongTinCaNhan($maNV, $ngaysinh, $gioitinh, $email, $sodt) {
        $sql = "UPDATE nhanvien
                SET NgaySinh = ?, GioiTinh = ?, EmailNV = ?, SoDT = ?
                WHERE MaNV = ?";

        $stmt = mysqli_prepare($this->con, $sql);
        if (!$stmt) return false;

        mysqli_stmt_bind_param($stmt, "sssss", $ngaysinh, $gioitinh, $email, $sodt, $maNV);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        return $ok;
    }

    // =============== ĐỔI MẬT KHẨU NVYT ===============
    public function KiemTraMatKhauCuNVYT($id, $matkhaucu) {
        // Giả sử MaPQ = 3 là nhân viên y tế. Nếu khác, chỉnh lại.
        $sql = "SELECT * FROM taikhoan WHERE ID = ? AND password = ? AND MaPQ = 3";
        $stmt = mysqli_prepare($this->con, $sql);
        if (!$stmt) return false;

        $matkhaucu_md5 = md5($matkhaucu);
        mysqli_stmt_bind_param($stmt, "is", $id, $matkhaucu_md5);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $ok = ($result && mysqli_num_rows($result) > 0);
        mysqli_stmt_close($stmt);

        return $ok;
    }

    public function DoiMatKhauNVYT($id, $matkhaumoi) {
        $sql = "UPDATE taikhoan SET password = ? WHERE ID = ? AND MaPQ = 3";
        $stmt = mysqli_prepare($this->con, $sql);
        if (!$stmt) return false;

        $matkhaumoi_md5 = md5($matkhaumoi);
        mysqli_stmt_bind_param($stmt, "si", $matkhaumoi_md5, $id);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        return $ok;
    }
}
?>
