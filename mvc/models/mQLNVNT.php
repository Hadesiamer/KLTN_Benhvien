<?php
class mQLNVNT extends DB {

    //Lấy danh sách toàn bộ nhân viên nhà thuốc
    public function GetAll() {
        $str = "SELECT * 
                FROM nhanvien 
                WHERE ChucVu = 'Nhân viên nhà thuốc'
                AND TrangThaiLamViec = 'Đang làm việc'
                ORDER BY MaNV DESC";
        $result = $this->con->query($str);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return json_encode($data);
    }

    //Lấy chi tiết 1 nhân viên
    public function GetCTNV($manv) {
        $str = "SELECT * 
                FROM nhanvien 
                WHERE ChucVu = 'Nhân viên nhà thuốc'
                AND MaNV = ?";
        $stmt = $this->con->prepare($str);
        $stmt->bind_param("i", $manv);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return json_encode($data);
    }

    //Cập nhật thông tin nhân viên
    public function UpdateNVNT($MaNV, $NgaySinh, $GioiTinh, $EmailNV) {
        $this->con->begin_transaction();
        try {
            // Kiểm tra trùng email
            $check = "SELECT COUNT(*) as cnt FROM nhanvien WHERE EmailNV = ? AND MaNV != ?";
            $stmt = $this->con->prepare($check);
            $stmt->bind_param("si", $EmailNV, $MaNV);
            $stmt->execute();
            $res = $stmt->get_result()->fetch_assoc();
            if ($res['cnt'] > 0) {
                throw new Exception("Email đã tồn tại trong hệ thống");
            }

            $str = "UPDATE nhanvien 
                    SET NgaySinh = ?, GioiTinh = ?, EmailNV = ?
                    WHERE MaNV = ?";
            $stmt2 = $this->con->prepare($str);
            $stmt2->bind_param("sssi", $NgaySinh, $GioiTinh, $EmailNV, $MaNV);
            $stmt2->execute();

            $this->con->commit();
            return true;
        } catch (Exception $e) {
            $this->con->rollback();
            return false;
        }
    }

    //Xóa nhân viên nhà thuốc + tài khoản liên kết
    public function DeleteNVNT($MaNV) {
        $this->con->begin_transaction();
        try {
            // Lấy ID tài khoản liên kết
            $getID = "SELECT ID FROM nhanvien WHERE MaNV = ? AND ChucVu = 'Nhân viên nhà thuốc'";
            $stmt = $this->con->prepare($getID);
            $stmt->bind_param("i", $MaNV);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $accountId = $row['ID'] ?? null;

            if (!$accountId) {
                throw new Exception("Không tìm thấy tài khoản liên kết với nhân viên này.");
            }

            // Cập nhật trạng thái nghỉ làm
            $updateNV = "UPDATE nhanvien 
                        SET TrangThaiLamViec = 'Nghỉ làm'
                        WHERE MaNV = ? AND ChucVu = 'Nhân viên nhà thuốc'";
            $stmt2 = $this->con->prepare($updateNV);
            $stmt2->bind_param("i", $MaNV);
            $stmt2->execute();

            // Xóa tài khoản tương ứng
            $deleteTK = "DELETE FROM taikhoan WHERE ID = ?";
            $stmt3 = $this->con->prepare($deleteTK);
            $stmt3->bind_param("i", $accountId);
            $stmt3->execute();

            $this->con->commit();
            return json_encode(['success' => true]);
        } catch (Exception $e) {
            $this->con->rollback();
            return json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    //Thêm nhân viên nhà thuốc mới + tạo tài khoản tự động
    public function AddNVNT($hovaten, $ngaysinh, $sodt, $email, $gioitinh) {
        $this->con->begin_transaction();
        try {
            // Kiểm tra trùng SĐT hoặc email
            if ($this->CheckExistingPhoneNumber($sodt)) {
                return "Số điện thoại đã tồn tại trong hệ thống.";
            }
            if ($this->CheckExistingEmail($email)) {
                return "Email đã tồn tại trong hệ thống.";
            }

            // 1️⃣ Tạo tài khoản mới
            $username = $sodt;
            $password = md5('123456'); // mật khẩu mặc định
            $MaPQ = 4; // Nhân viên nhà thuốc
            $insertTK = "INSERT INTO taikhoan (username, password, MaPQ) VALUES (?, ?, ?)";
            $stmt1 = $this->con->prepare($insertTK);
            $stmt1->bind_param("ssi", $username, $password, $MaPQ);
            $stmt1->execute();
            $newAccountId = $this->con->insert_id;

            // 2️⃣ Thêm vào bảng nhân viên
            $insertNV = "INSERT INTO nhanvien (HovaTen, NgaySinh, SoDT, ChucVu, GioiTinh, TrangThaiLamViec, EmailNV, ID) 
                         VALUES (?, ?, ?, 'Nhân viên nhà thuốc', ?, 'Đang làm việc', ?, ?)";
            $stmt2 = $this->con->prepare($insertNV);
            $stmt2->bind_param("sssssi", $hovaten, $ngaysinh, $sodt, $gioitinh, $email, $newAccountId);
            $stmt2->execute();

            $this->con->commit();
            return true;
        } catch (Exception $e) {
            $this->con->rollback();
            return "Lỗi: " . $e->getMessage();
        }
    }

    //Kiểm tra trùng số điện thoại
    private function CheckExistingPhoneNumber($SoDT) {
        $str = "SELECT COUNT(*) as count FROM nhanvien WHERE SoDT = ?";
        $stmt = $this->con->prepare($str);
        $stmt->bind_param("s", $SoDT);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'] > 0;
    }

    //Kiểm tra trùng email
    private function CheckExistingEmail($EmailNV) {
        $str = "SELECT COUNT(*) as count FROM nhanvien WHERE EmailNV = ?";
        $stmt = $this->con->prepare($str);
        $stmt->bind_param("s", $EmailNV);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'] > 0;
    }
}
?>
