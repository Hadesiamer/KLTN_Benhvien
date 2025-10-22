<?php
class mQLNVYT extends DB {
    // Đếm số lượng nhân viên y tế đang làm việc
    public function GetStaffCount() {
        $str = "SELECT COUNT(*) as count FROM nhanvien
                WHERE ChucVu = 'Nhân viên y tế' AND TrangThaiLamViec = 'Đang làm việc'";
        $result = $this->con->query($str);
        $row = $result->fetch_assoc();
        return $row['count'];
    }

    // Lấy danh sách toàn bộ nhân viên y tế (tìm kiếm nếu có)
    public function GetAllNVYT($search = '') {
        $str = "SELECT nv.MaNV, nv.HovaTen, nv.NgaySinh, nv.GioiTinh, nv.SoDT, nv.EmailNV
                FROM nhanvien nv
                WHERE nv.TrangThaiLamViec = 'Đang làm việc'
                AND nv.ChucVu = 'Nhân viên y tế'
                AND (nv.MaNV LIKE ? OR nv.HovaTen LIKE ?)
                ORDER BY nv.MaNV DESC";
        $search = "%$search%";
        $stmt = $this->con->prepare($str);
        $stmt->bind_param("ss", $search, $search);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return json_encode($data);
    }

    // Lấy thông tin 1 nhân viên y tế
    public function Get1NVYT($MaNV) {
        $str = "SELECT nv.MaNV, nv.HovaTen, nv.NgaySinh, nv.GioiTinh, nv.SoDT, nv.EmailNV
                FROM nhanvien nv
                JOIN nhanvienyte nvyt ON nv.MaNV = nvyt.MaNV
                WHERE nv.MaNV = ? AND nv.ChucVu = 'Nhân viên y tế'";
        $stmt = $this->con->prepare($str);
        $stmt->bind_param("i", $MaNV);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        return json_encode($data);
    }

    // Cập nhật thông tin NVYT
    public function UpdateNVYT($MaNV, $NgaySinh, $GioiTinh, $EmailNV) {
        $this->con->begin_transaction();
        try {
            $str_get_email = "SELECT EmailNV FROM nhanvien WHERE MaNV = ?";
            $stmt_get_email = $this->con->prepare($str_get_email);
            $stmt_get_email->bind_param("i", $MaNV);
            $stmt_get_email->execute();
            $result = $stmt_get_email->get_result();
            $current_email = $result->fetch_assoc()['EmailNV'];
            if ($current_email !== $EmailNV) {
                if ($this->CheckExistingEmail($EmailNV)) {
                    return "Email đã tồn tại";
                }
            }

            $str = "UPDATE nhanvien SET NgaySinh = ?, GioiTinh = ?, EmailNV = ? WHERE MaNV = ?";
            $stmt = $this->con->prepare($str);
            $stmt->bind_param("sssi", $NgaySinh, $GioiTinh, $EmailNV, $MaNV);
            $stmt->execute();

            $this->con->commit();
            return true;
        } catch (Exception $e) {
            $this->con->rollback();
            return false;
        }
    }

    //Xóa NVYT + tài khoản liên kết
    public function DeleteNVYT($MaNV) {
        $this->con->begin_transaction();
        try {
            // 1. Lấy ID tài khoản gắn với nhân viên
            $getIdQuery = "SELECT ID FROM nhanvien WHERE MaNV = ? AND ChucVu = 'Nhân viên y tế'";
            $stmtGetId = $this->con->prepare($getIdQuery);
            $stmtGetId->bind_param("i", $MaNV);
            $stmtGetId->execute();
            $result = $stmtGetId->get_result();
            $row = $result->fetch_assoc();
            $accountId = $row['ID'] ?? null;

            if (!$accountId) {
                throw new Exception("Không tìm thấy tài khoản liên kết.");
            }

            // 2. Cập nhật trạng thái nhân viên sang "Nghỉ làm"
            $updateNV = "UPDATE nhanvien SET TrangThaiLamViec = 'Nghỉ làm' WHERE MaNV = ? AND ChucVu = 'Nhân viên y tế'";
            $stmtUpdate = $this->con->prepare($updateNV);
            $stmtUpdate->bind_param("i", $MaNV);
            $stmtUpdate->execute();

            // 3. Xóa tài khoản gắn với nhân viên đó
            $deleteTK = "DELETE FROM taikhoan WHERE ID = ?";
            $stmtDelete = $this->con->prepare($deleteTK);
            $stmtDelete->bind_param("i", $accountId);
            $stmtDelete->execute();

            $this->con->commit();
            return json_encode(['success' => true]);
        } catch (Exception $e) {
            $this->con->rollback();
            return json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    // Thêm mới NVYT
    public function AddNVYT($HovaTen, $NgaySinh, $GioiTinh, $SoDT, $EmailNV) {
        $this->con->begin_transaction();
        try {
            if ($this->CheckExistingPhoneNumber($SoDT)) {
                return "Số điện thoại đã tồn tại";
            }
            if ($this->CheckExistingEmail($EmailNV)) {
                return "Email đã tồn tại";
            }

            // Tạo tài khoản mới
            $username = $SoDT;
            $password = md5('123456'); // Mật khẩu mặc định
            $str1 = "INSERT INTO taikhoan (username, password, MaPQ) VALUES (?, ?, 3)";
            $stmt1 = $this->con->prepare($str1);
            $stmt1->bind_param("ss", $username, $password);
            $stmt1->execute();
    
            $newAccountId = $this->con->insert_id;

            // Tạo nhân viên y tế mới
            $str2 = "INSERT INTO nhanvien (HovaTen, NgaySinh, GioiTinh, SoDT, EmailNV, ChucVu, TrangThaiLamViec, ID) 
                     VALUES (?, ?, ?, ?, ?, 'Nhân viên y tế', 'Đang làm việc', ?)";
            $stmt2 = $this->con->prepare($str2);
            $stmt2->bind_param("sssssi", $HovaTen, $NgaySinh, $GioiTinh, $SoDT, $EmailNV, $newAccountId);
            $stmt2->execute();

            // Thêm vào bảng nhanvienyte
            $MaNV = $this->con->insert_id;
            $str3 = "INSERT INTO nhanvienyte (MaNV) VALUES (?)";
            $stmt3 = $this->con->prepare($str3);
            $stmt3->bind_param("i", $MaNV);
            $stmt3->execute();

            $this->con->commit();
            return true;
        } catch (Exception $e) {
            $this->con->rollback();
            return "Lỗi: " . $e->getMessage();
        }
    }

    // Kiểm tra trùng số điện thoại
    public function CheckExistingPhoneNumber($SoDT) {
        $str = "SELECT COUNT(*) as count FROM nhanvien WHERE SoDT = ?";
        $stmt = $this->con->prepare($str);
        $stmt->bind_param("s", $SoDT);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'] > 0;
    }

    // Kiểm tra trùng email
    public function CheckExistingEmail($EmailNV) {
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
