<?php
class mBN extends DB
{
    public function get1BN($mabn){
        $str = "select * from benhnhan where MaBN='$mabn'";
        $rows = mysqli_query($this->con, $str);
        
        $mang = array();
        while($row = mysqli_fetch_array($rows)){
            $mang[] = $row;
        }
        return json_encode($mang);
    }

    public function emailExistsForUpdate($email, $mabn) {
        $str = "SELECT COUNT(*) as count FROM benhnhan WHERE Email = '$email' AND MaBN != $mabn";
        $result = mysqli_query($this->con, $str);
        $row = mysqli_fetch_assoc($result);
        return $row['count'] > 0;
    }

    public function UpdateBN($mabn, $hoten, $gioitinh, $ngaysinh, $diachi, $email, $bhyt) {
        if ($this->emailExistsForUpdate($email, $mabn)) {
            return json_encode(array(
                "success" => false,
                "message" => "Email đã tồn tại hoặc đã được sử dụng!"
            ));
        }
        $str = "UPDATE benhnhan SET HovaTen='$hoten', GioiTinh='$gioitinh', NgaySinh='$ngaysinh', 
                DiaChi='$diachi', Email='$email', BHYT='$bhyt' WHERE MaBN = $mabn";
        $result = mysqli_query($this->con, $str);
    
        return json_encode(array(
            "success" => $result,
            "message" => $result ? "Cập nhật thông tin thành công" : "Cập nhật thông tin thất bại"
        ));
    }

    public function getDKXN($ngayxn, $ketqua, $loaixn, $mabn){
        $str = "INSERT INTO xetnghiem (`MaXN`, `NgayXetNghiem`, `KetQua`, `LoaiXN`, `MaBN`) VALUES('', '$ngayxn', NULL, '$loaixn', '$mabn')";
        $result = false;
        if(mysqli_query($this->con, $str)){
            $result = true;
        }
        return json_encode($result);
    }


    public function changePass($mabn, $oldPass, $newPass) {
        // Lấy mật khẩu hiện tại
        $str = "SELECT password FROM benhnhan 
                INNER JOIN taikhoan ON benhnhan.ID = taikhoan.ID 
                WHERE MaBN = '$mabn'";
        $result = mysqli_query($this->con, $str);
    
        if ($row = mysqli_fetch_assoc($result)) {
            // Sai mật khẩu cũ
            if (md5($oldPass) !== $row['password']) {
                return [
                    "success" => false,
                    "message" => "Mật khẩu hiện tại không đúng!"
                ];
            }
    
            // Trùng mật khẩu
            if (md5($newPass) === $row['password']) {
                return [
                    "success" => false,
                    "message" => "Mật khẩu mới không được trùng với mật khẩu hiện tại!"
                ];
            }
    
            // Nếu hợp lệ → Cập nhật
            $updateStr = "UPDATE taikhoan 
                          SET password = '" . md5($newPass) . "' 
                          WHERE ID = (SELECT ID FROM benhnhan WHERE MaBN = '$mabn')";
            $updateResult = mysqli_query($this->con, $updateStr);
    
            if ($updateResult) {
                return [
                    "success" => true,
                    "message" => "Đổi mật khẩu thành công!"
                ];
            } else {
                return [
                    "success" => false,
                    "message" => "Đổi mật khẩu thất bại!"
                ];
            }
    
        } else {
            return [
                "success" => false,
                "message" => "Người dùng không tồn tại!"
            ];
        }
    }

    // ================== LỊCH KHÁM ĐÃ ĐẶT (ĐÃ THANH TOÁN) ==================

    // Lấy danh sách lịch khám đã ĐÃ THANH TOÁN của 1 bệnh nhân
    public function getLichKhamDaThanhToan($mabn) {
        $str = "
            SELECT 
                lichkham.*, 
                nhanvien.*,
                nhanvien.HovaTen as HovaTenNV,
                benhnhan.*, 
                chuyenkhoa.*
            FROM lichkham
            INNER JOIN benhnhan ON lichkham.MaBN = benhnhan.MaBN 
            INNER JOIN bacsi    ON lichkham.MaBS = bacsi.MaNV 
            INNER JOIN nhanvien ON bacsi.MaNV = nhanvien.MaNV
            INNER JOIN chuyenkhoa ON bacsi.MaKhoa = chuyenkhoa.MaKhoa
            WHERE 
                lichkham.MaBN = '$mabn'
                AND lichkham.TrangThaiThanhToan = 'Da thanh toan'
            ORDER BY lichkham.MaLK DESC
        ";
        $rows = mysqli_query($this->con, $str);

        $result = [];
        if ($rows) {
            while ($row = mysqli_fetch_assoc($rows)) {
                $result[] = $row;
            }
        }

        return json_encode($result);
    }

    // Lấy chi tiết 1 lịch khám ĐÃ THANH TOÁN theo MaLK (và MaBN nếu có)
    public function getChiTietLichKhamDaThanhToan($MaLK, $mabn = null) {
        // Ép kiểu an toàn
        $MaLK = (int)$MaLK;

        $whereMaBN = "";
        if ($mabn !== null) {
            $mabn = (int)$mabn;
            $whereMaBN = " AND lichkham.MaBN = '$mabn'";
        }

        $str = "
            SELECT 
                lichkham.*, 
                nhanvien.*,
                nhanvien.HovaTen as HovaTenNV,
                benhnhan.*, 
                chuyenkhoa.*
            FROM lichkham
            INNER JOIN benhnhan   ON lichkham.MaBN = benhnhan.MaBN 
            INNER JOIN bacsi      ON lichkham.MaBS = bacsi.MaNV 
            INNER JOIN nhanvien   ON bacsi.MaNV = nhanvien.MaNV
            INNER JOIN chuyenkhoa ON bacsi.MaKhoa = chuyenkhoa.MaKhoa 
            WHERE 
                lichkham.MaLK = '$MaLK'
                $whereMaBN
                AND lichkham.TrangThaiThanhToan = 'Da thanh toan'
            ORDER BY lichkham.MaLK ASC
        ";

        $rows = mysqli_query($this->con, $str);

        $result = [];
        if ($rows) {
            while ($row = mysqli_fetch_assoc($rows)) {
                $result[] = $row;
            }
        }

        return json_encode($result);
    }

    // ================== HỒ SƠ PHIẾU KHÁM BỆNH NHÂN ==================

    // Lấy danh sách phiếu khám + thông tin liên quan của 1 bệnh nhân
    public function getPhieuKhamBenhNhan($mabn) {
        $mabn = (int)$mabn;

        $sql = "
            SELECT 
                pk.MaPK,
                pk.NgayTao,
                pk.TrieuChung,
                pk.KetQua,
                pk.ChuanDoan,
                pk.LoiDan,
                pk.NgayTaikham,
                pk.MaXN,
                pk.MaLK,
                pk.MaDon,

                -- Thông tin lịch khám
                lk.NgayKham,
                lk.GioKham,

                -- Thông tin bác sĩ & khoa
                nv.HovaTen AS TenBacSi,
                ck.TenKhoa,
                ck.MoTa AS ViTriKhoa,

                -- Thông tin xét nghiệm (nếu có)
                xn.LoaiXN,
                xn.KetQua AS KetQuaXN,

                -- Thông tin 1 thuốc trong đơn (nếu có)
                t.TenThuoc,
                cdt.SoLuong,
                cdt.LieuDung,
                cdt.GhiChu AS CachDung
            FROM phieukham pk
            LEFT JOIN lichkham     lk  ON pk.MaLK  = lk.MaLK
            LEFT JOIN bacsi        bs  ON lk.MaBS  = bs.MaNV
            LEFT JOIN nhanvien     nv  ON bs.MaNV  = nv.MaNV
            LEFT JOIN chuyenkhoa   ck  ON bs.MaKhoa = ck.MaKhoa

            LEFT JOIN xetnghiem    xn  ON pk.MaXN  = xn.MaXN

            LEFT JOIN don_thuoc    dt  ON pk.MaDon = dt.MaDon
            LEFT JOIN ct_don_thuoc cdt ON dt.MaDon = cdt.MaDon
            LEFT JOIN thuoc        t   ON cdt.MaThuoc = t.MaThuoc

            WHERE pk.MaBN = ?
            ORDER BY pk.NgayTao DESC, pk.MaPK DESC
        ";

        $result = [];
        if ($stmt = $this->con->prepare($sql)) {
            $stmt->bind_param("i", $mabn);
            if ($stmt->execute()) {
                $res = $stmt->get_result();
                while ($row = $res->fetch_assoc()) {
                    $result[] = $row;
                }
            }
            $stmt->close();
        }

        return json_encode($result);
    }

    // Chi tiết 1 phiếu khám (dùng để in), theo MaPK + MaBN
    public function getChiTietPhieuKham($maPK, $maBN) {
        $maPK = (int)$maPK;
        $maBN = (int)$maBN;

        $sql = "
            SELECT 
                pk.MaPK,
                pk.NgayTao,
                pk.TrieuChung,
                pk.KetQua,
                pk.ChuanDoan,
                pk.LoiDan,
                pk.NgayTaikham,
                pk.MaXN,
                pk.MaLK,
                pk.MaDon,

                lk.NgayKham,
                lk.GioKham,

                nv.HovaTen AS TenBacSi,
                ck.TenKhoa,
                ck.MoTa AS ViTriKhoa,

                xn.LoaiXN,
                xn.KetQua AS KetQuaXN,

                t.TenThuoc,
                cdt.SoLuong,
                cdt.LieuDung,
                cdt.GhiChu AS CachDung
            FROM phieukham pk
            LEFT JOIN lichkham     lk  ON pk.MaLK  = lk.MaLK
            LEFT JOIN bacsi        bs  ON lk.MaBS  = bs.MaNV
            LEFT JOIN nhanvien     nv  ON bs.MaNV  = nv.MaNV
            LEFT JOIN chuyenkhoa   ck  ON bs.MaKhoa = ck.MaKhoa

            LEFT JOIN xetnghiem    xn  ON pk.MaXN  = xn.MaXN

            LEFT JOIN don_thuoc    dt  ON pk.MaDon = dt.MaDon
            LEFT JOIN ct_don_thuoc cdt ON dt.MaDon = cdt.MaDon
            LEFT JOIN thuoc        t   ON cdt.MaThuoc = t.MaThuoc

            WHERE pk.MaPK = ? AND pk.MaBN = ?
            ORDER BY cdt.MaCT ASC
        ";

        $result = [];
        if ($stmt = $this->con->prepare($sql)) {
            $stmt->bind_param("ii", $maPK, $maBN);
            if ($stmt->execute()) {
                $res = $stmt->get_result();
                while ($row = $res->fetch_assoc()) {
                    $result[] = $row;
                }
            }
            $stmt->close();
        }

        return json_encode($result);
    }
} 
?>
