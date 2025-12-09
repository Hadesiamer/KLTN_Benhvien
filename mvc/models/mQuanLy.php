<?php 
class mQuanLy extends DB {

    public function Get1BN($MaBN) {
        $str = "SELECT * FROM benhnhan WHERE mabn = '$MaBN'";
        $tblBNhan = mysqli_query($this->con, $str);
        $mang = array();
        while ($row = mysqli_fetch_assoc($tblBNhan)) {
            $mang[] = $row;
        }
        return json_encode($mang);
    }

    public function GetPK($MaBN) {
        $str = "SELECT 
        pk.MaPK AS MaPK,
        pk.NgayTao AS NgayTaoPhieuKham,
        nv.HovaTen AS BacSiPhuTrach,
        pk.KetQua AS KetQua
    FROM 
        phieukham pk
    JOIN 
        nhanvien nv ON pk.MaBS = nv.MaNV
    WHERE 
        pk.MaBN = $MaBN";
        $tblPK = mysqli_query($this->con, $str);
        $mang = array();
        while ($row = mysqli_fetch_assoc($tblPK)) {
            $mang[] = $row;
        }
        return json_encode($mang);
    }

    public function GetCTPK($MaPK) {
        $str = "SELECT
    pk.MaPK,
    pk.NgayTao AS NgayTaoPhieuKham,
    nv.HovaTen AS BacSiPhuTrach,
    pk.KetQua,
    GROUP_CONCAT(DISTINCT CONCAT(t.TenThuoc, ' - ', ct.LieuDung, ' - ', ct.CachDung) SEPARATOR '; ') AS DonThuoc,
    dt.MoTa AS LoiDan,
    bn.HovaTen,          
    bn.NgaySinh,        
    bn.DiaChi,           
    bn.SoDT,
    bn.BHYT,
    bn.GioiTinh,
    bn.MaBN,
    xn.NgayXetNghiem,
    xn.LoaiXN,
    xn.MaXN,
    xn.KetQua AS KetQuaXN
    FROM 
        phieukham pk
    LEFT JOIN 
        bacsi bs ON pk.MaBS = bs.MaNV
    LEFT JOIN 
        nhanvien nv ON bs.MaNV = nv.MaNV
    LEFT JOIN 
        xetnghiem xn ON pk.MaXN = xn.MaXN
    LEFT JOIN 
        donthuoc dt ON pk.MaDT = dt.MaDT
    LEFT JOIN 
        chitietdonthuoc ct ON dt.MaDT = ct.MaDT
    LEFT JOIN 
        thuoc t ON ct.MaThuoc = t.MaThuoc
    LEFT JOIN
        benhnhan bn ON pk.MaBN = bn.MaBN   
    WHERE 
        pk.MaPK = '$MaPK'
    GROUP BY 
        pk.MaPK";
        
        $result = mysqli_query($this->con, $str);
        $mang = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $mang[] = $row;
        }
        return json_encode($mang);
    }

    //lấy khoa
    public function GetKhoa() {
        $str = 'SELECT * FROM chuyenkhoa';
        $tblKhoa = mysqli_query($this->con, $str);
        $mang = array();
        while ($row = mysqli_fetch_assoc($tblKhoa)) {
            $mang[] = $row;
        }
        return json_encode($mang);
    }

    //lấy danh sách bác sĩ đăng ký ca làm việc theo khoa
    public function GetKhoaBS($MaKhoa){
        $str="SELECT *
        FROM lichlamviec llv
        INNER JOIN
        nhanvien nv
        on llv.MaLLV=nv.MaLLV
        INNER JOIN
        bacsi bs
        on nv.MaNV=bs.MaNV
        INNER JOIN
        chuyenkhoa ck
        on bs.MaKhoa=ck.MaKhoa
        WHERE ck.MaKhoa='$MaKhoa'";
        $tblKhoaBS = mysqli_query($this->con, $str);
        $mang = array();
        while ($row = mysqli_fetch_assoc($tblKhoaBS)) {
            $mang[] = $row;
        }
        return json_encode($mang);
    }

    //lấy danh sách bác sĩ đăng ký ca làm việc khi không chọn khoa_không su dung
    public function GetBSLLV(){
        $str = 'select * 
                from bacsi as bs 
                join nhanvien as nv on bs.MaNV=nv.MaNV 
                join lichlamviec as lv on nv.MaNV=lv.MaNV
                ORDER BY lv.NgayLamViec'; 
        $rows = mysqli_query($this->con, $str);
        $mang = array();
        while ($row = mysqli_fetch_array($rows))
        {
            $mang[] = $row;
        }
        return json_encode($mang);
    }

    public function GetLichLamViecTheoKhoa($MaKhoa){
        $str = "
        SELECT *
        FROM lichlamviec llv
        INNER JOIN nhanvien nv ON llv.MaNV = nv.MaNV
        INNER JOIN bacsi bs ON nv.MaNV = bs.MaNV
        INNER JOIN chuyenkhoa ck ON bs.MaKhoa = ck.MaKhoa
        WHERE ck.MaKhoa = '$MaKhoa'
        ORDER BY llv.NgayLamViec";
        $result = mysqli_query($this->con, $str);
        $mang = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $mang[] = $row;
        }
        return json_encode($mang);
    }

    public function GetDanhSachKhoa() {
        $str = "SELECT * FROM chuyenkhoa";
        $result = mysqli_query($this->con, $str);
        $mang = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $mang[] = $row;
        }
        return json_encode($mang);
    }

    //lấy danh sách bác sĩ
    public function GetDSBS() {
        $str = "SELECT * FROM bacsi bs JOIN nhanvien nv
        ON bs.MaNV=nv.MaNV
        JOIN chuyenkhoa ck
        ON bs.MaKhoa=ck.MaKhoa
        WHERE nv.TrangThaiLamViec = 'Đang làm việc'";
        $tblBS = mysqli_query($this->con, $str);
        $mang = array();
        while ($row = mysqli_fetch_assoc($tblBS)) {
            $mang[] = $row;
        }
        return json_encode($mang);
    }

    // ===== ĐẾM NHÂN VIÊN NHÀ THUỐC =====
    public function GetPharmacyStaffCount() {
        $sql = "SELECT COUNT(*) AS total FROM nhanvien WHERE ChucVu = 'Nhân viên nhà thuốc'";
        $result = mysqli_query($this->con, $sql);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            return (int)$row['total'];
        }
        return 0;
    }

    // ===== ĐẾM NHÂN VIÊN XÉT NGHIỆM =====
    public function GetLabStaffCount() {
        $sql = "SELECT COUNT(*) AS total FROM nhanvien WHERE ChucVu = 'Nhân viên xét nghiệm'";
        $result = mysqli_query($this->con, $sql);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            return (int)$row['total'];
        }
        return 0;
    }

    //xoa ca lam viec
    public function DelLLV($maNV, $NgayLamViec, $CaLamViec) {
        $str = "UPDATE lichlamviec
        SET TrangThai = 'Nghỉ'
        WHERE MaNV = '$maNV'
        AND NgayLamViec = '$NgayLamViec'
        AND CaLamViec = '$CaLamViec'";
        $result = mysqli_query($this->con, $str);
        return json_encode(array("success" => $result));
    }

    //thêm ca nhân viên
    public function AddLLV($MaNV, $NgayLamViec, $CaLamViec) {
        $str = "INSERT INTO lichlamviec(MaNV, NgayLamViec, CaLamViec, TrangThai) 
                VALUES ('$MaNV', '$NgayLamViec', '$CaLamViec', 'Đang làm')";
        $result = mysqli_query($this->con, $str);
        return $result;
    }    

    //đêm số nhân viên trong ca làm việc
    public function CountEmployeeInShift($NgayLamViec, $CaLamViec, $ChuyenKhoa) {
        $str = "SELECT COUNT(*) AS Total FROM lichlamviec inner join nhanvien nv on lichlamviec.MaNV = nv.MaNV
        inner join bacsi bs on nv.MaNV = bs.MaNV
        inner join chuyenkhoa ck on bs.MaKhoa = ck.MaKhoa
        WHERE NgayLamViec = '$NgayLamViec' AND CaLamViec = '$CaLamViec' AND TrangThai = 'Đang làm' AND ck.MaKhoa = '$ChuyenKhoa'";
        $result = mysqli_query($this->con, $str);
        $row = mysqli_fetch_assoc($result);
        return $row['Total'];
    }

    // Kiểm tra xem nhân viên đã có trong ca làm việc chưa
    public function CheckEmployeeInShift($MaNV, $NgayLamViec, $CaLamViec) {
        $str = "SELECT * FROM lichlamviec WHERE MaNV = '$MaNV' AND NgayLamViec = '$NgayLamViec' AND CaLamViec = '$CaLamViec' AND TrangThai = 'Đang làm'";
        $result = mysqli_query($this->con, $str);
    
        if (mysqli_num_rows($result) > 0) {
            return true; //có tồn tại
        }
        return false;
    }
    
    public function GetHD(){
        $str = "SELECT * FROM hoadon hd 
        JOIN chitiethoadon ct
        on hd.MaHD=ct.MaHD";
        $tblHD = mysqli_query($this->con, $str);
        $mang = array();
        while ($row = mysqli_fetch_assoc($tblHD)) {
            $mang[] = $row;
        }
        return json_encode($mang);
    }

    public function GetThongKeTheoThang(){
        $str = "SELECT 
    ct.DichVu, hd.TrangThai,
    YEAR(hd.NgayLapHoaDon) AS Nam,
    MONTH(hd.NgayLapHoaDon) AS Thang,
    SUM(hd.TongTien) AS TongTienTheoThang
    FROM 
        hoadon hd
    JOIN 
        chitiethoadon ct ON hd.MaHD = ct.MaHD
        WHERE hd.TrangThai='Completed'
    GROUP BY 
        ct.DichVu, YEAR(hd.NgayLapHoaDon), MONTH(hd.NgayLapHoaDon)
    ORDER BY 
        Thang, Nam;";
                    
        $tblThongKe = mysqli_query($this->con, $str);
        $mang = array();
        while ($row = mysqli_fetch_assoc($tblThongKe)) {
            $mang[] = $row;
        }
        return json_encode($mang);
    }

    public function GetThongKeTheoTuan($dautuan, $cuoituan){
        $str = "SELECT 
                    ct.DichVu,hd.TrangThai,
                    SUM(hd.TongTien) AS TongTienTheoTuan
                FROM 
                    hoadon hd
                JOIN 
                    chitiethoadon ct ON hd.MaHD = ct.MaHD
                WHERE 
                    hd.NgayLapHoaDon BETWEEN '$dautuan' AND '$cuoituan' AND hd.TrangThai='Completed'
                GROUP BY 
                    ct.DichVu"; 
        $tblThongKe = mysqli_query($this->con, $str);
        $mang = array();
        while ($row = mysqli_fetch_assoc($tblThongKe)) {
            $mang[] = $row;
        }
        return json_encode($mang);
    }

    // ===================== THÊM NHIỀU LỊCH LÀM VIỆC THEO TUẦN =====================
    // $weekTemplate: mảng dạng ['mon' => ['Sáng','Chiều'], 'tue' => [...], ...]
    // $conflictOption: overwrite | skip | cancel
    public function GenerateWeeklySchedule($MaNV, $weekTemplate, $startDate, $endDate, $conflictOption = 'skip') {
        // Đảm bảo dữ liệu template là mảng
        if (!is_array($weekTemplate)) {
            return [
                'success' => false,
                'error' => 'Dữ liệu lịch tuần không hợp lệ.',
                'added' => 0,
                'conflicts' => 0,
                'skipped' => 0
            ];
        }

        // Map thứ trong tuần: 1 (Mon) ... 7 (Sun) -> key trong mảng
        $weekdayMap = [
            1 => 'mon',
            2 => 'tue',
            3 => 'wed',
            4 => 'thu',
            5 => 'fri',
            6 => 'sat',
            7 => 'sun'
        ];

        $added = 0;
        $conflicts = 0;
        $skipped = 0;

        // Bắt đầu transaction để có thể rollback nếu lỗi lớn
        mysqli_begin_transaction($this->con);
        try {
            $start = new DateTime($startDate);
            $end = new DateTime($endDate);
            $end->setTime(0, 0, 0);

            // Chuẩn bị statement kiểm tra trùng
            $stmtCheck = mysqli_prepare(
                $this->con,
                "SELECT MaLLV FROM lichlamviec WHERE MaNV = ? AND NgayLamViec = ? AND CaLamViec = ? LIMIT 1"
            );
            // Chuẩn bị statement xóa (ghi đè)
            $stmtDeleteByDay = mysqli_prepare(
                $this->con,
                "DELETE FROM lichlamviec WHERE MaNV = ? AND NgayLamViec = ?"
            );
            // Chuẩn bị statement insert
            $stmtInsert = mysqli_prepare(
                $this->con,
                "INSERT INTO lichlamviec (MaNV, NgayLamViec, CaLamViec, TrangThai) VALUES (?, ?, ?, 'Đang làm')"
            );

            if (!$stmtCheck || !$stmtDeleteByDay || !$stmtInsert) {
                throw new Exception("Không thể chuẩn bị truy vấn database.");
            }

            for ($d = clone $start; $d <= $end; $d->modify('+1 day')) {
                $dow = (int)$d->format('N'); // 1..7
                if (!isset($weekdayMap[$dow])) continue;
                $key = $weekdayMap[$dow];

                if (!isset($weekTemplate[$key]) || !is_array($weekTemplate[$key]) || empty($weekTemplate[$key])) {
                    continue; // Ngày này không chọn ca nào
                }

                $dateStr = $d->format('Y-m-d');

                foreach ($weekTemplate[$key] as $shift) {
                    // Chỉ cho phép 2 ca chuẩn
                    if ($shift !== 'Sáng' && $shift !== 'Chiều') {
                        continue;
                    }

                    // Kiểm tra trùng
                    mysqli_stmt_bind_param($stmtCheck, "iss", $MaNV, $dateStr, $shift);
                    mysqli_stmt_execute($stmtCheck);
                    $resCheck = mysqli_stmt_get_result($stmtCheck);

                    if ($resCheck && mysqli_num_rows($resCheck) > 0) {
                        $conflicts++;

                        if ($conflictOption === 'cancel') {
                            // Hủy toàn bộ – rollback
                            mysqli_rollback($this->con);
                            mysqli_stmt_close($stmtCheck);
                            mysqli_stmt_close($stmtDeleteByDay);
                            mysqli_stmt_close($stmtInsert);
                            return [
                                'success' => false,
                                'error' => 'Phát hiện trùng lịch và đã chọn Không thêm lịch.',
                                'added' => $added,
                                'conflicts' => $conflicts,
                                'skipped' => $skipped,
                                'option' => 'cancel'
                            ];
                        } elseif ($conflictOption === 'skip') {
                            // Bỏ qua ca này
                            $skipped++;
                            continue;
                        } elseif ($conflictOption === 'overwrite') {
                            // Ghi đè: xóa toàn bộ lịch của ngày đó cho nhân viên này, sau đó sẽ insert lại theo mẫu
                            mysqli_stmt_bind_param($stmtDeleteByDay, "is", $MaNV, $dateStr);
                            if (!mysqli_stmt_execute($stmtDeleteByDay)) {
                                throw new Exception("Lỗi khi xóa lịch ngày $dateStr.");
                            }
                            // Sau khi xóa, tiếp tục insert bình thường phía dưới
                        }
                    }

                    // Thêm lịch
                    mysqli_stmt_bind_param($stmtInsert, "iss", $MaNV, $dateStr, $shift);
                    if (!mysqli_stmt_execute($stmtInsert)) {
                        throw new Exception("Lỗi khi thêm lịch ngày $dateStr, ca $shift.");
                    }
                    $added++;
                }
            }

            mysqli_commit($this->con);

            mysqli_stmt_close($stmtCheck);
            mysqli_stmt_close($stmtDeleteByDay);
            mysqli_stmt_close($stmtInsert);

            return [
                'success' => true,
                'added' => $added,
                'conflicts' => $conflicts,
                'skipped' => $skipped,
                'option' => $conflictOption
            ];
        } catch (Exception $e) {
            mysqli_rollback($this->con);
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'added' => $added,
                'conflicts' => $conflicts,
                'skipped' => $skipped
            ];
        }
    }

    // ===================== NGHỈ PHÉP: HÀM DÙNG CHO MVC =====================

    // Lấy danh sách yêu cầu nghỉ phép đang chờ duyệt
    public function GetPendingLeaveRequests() {
        $sql = "SELECT lichnghiphep.*, 
                       lichlamviec.NgayLamViec, 
                       lichlamviec.CaLamViec, 
                       lichlamviec.MaNV, 
                       lichlamviec.MaLLV, 
                       nhanvien.HovaTen
                FROM lichnghiphep 
                INNER JOIN lichlamviec ON lichnghiphep.MaLLV = lichlamviec.MaLLV 
                INNER JOIN nhanvien ON lichlamviec.MaNV = nhanvien.MaNV 
                WHERE lichnghiphep.TrangThai = 'Chờ duyệt' 
                  AND lichlamviec.TrangThai = 'Đang làm'
                GROUP BY lichnghiphep.MaYC";
        $result = mysqli_query($this->con, $sql);
        $mang = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $mang[] = $row;
            }
        }
        return json_encode($mang);
    }

    // Đếm số yêu cầu nghỉ phép đang chờ duyệt
    public function CountPendingLeaveRequests() {
        $sql = "SELECT COUNT(*) AS total FROM lichnghiphep WHERE TrangThai='Chờ duyệt'";
        $result = mysqli_query($this->con, $sql);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            return (int)$row['total'];
        }
        return 0;
    }

    // Đánh dấu yêu cầu nghỉ phép đã xử lý
    public function MarkLeaveRequestProcessed($maYC) {
        $sql = "UPDATE lichnghiphep SET TrangThai = 'Da xu ly' WHERE MaYC = ?";
        $stmt = mysqli_prepare($this->con, $sql);
        if (!$stmt) return false;
        mysqli_stmt_bind_param($stmt, "i", $maYC);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $ok;
    }

    // Lấy ngày làm việc theo MaLLV
    public function GetNgayLamViecByMaLLV($maLLV) {
        $sql = "SELECT NgayLamViec FROM lichlamviec WHERE MaLLV = ? LIMIT 1";
        $stmt = mysqli_prepare($this->con, $sql);
        if (!$stmt) return null;
        mysqli_stmt_bind_param($stmt, "i", $maLLV);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $ngay);
        $ngayLamViec = null;
        if (mysqli_stmt_fetch($stmt)) {
            $ngayLamViec = $ngay;
        }
        mysqli_stmt_close($stmt);
        return $ngayLamViec;
    }

    // Cập nhật trạng thái lịch làm việc theo MaLLV
    public function UpdateWorkScheduleStatusByMaLLV($maLLV, $status) {
        $sql = "UPDATE lichlamviec SET TrangThai = ? WHERE MaLLV = ?";
        $stmt = mysqli_prepare($this->con, $sql);
        if (!$stmt) return false;
        mysqli_stmt_bind_param($stmt, "si", $status, $maLLV);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $ok;
    }

    // ===================== CHỨC NĂNG ĐỔI MẬT KHẨU CHO QUẢN LÝ =====================
    public function KiemTraMatKhauCu($id, $matkhaucu) {
        $sql = "SELECT * FROM taikhoan WHERE ID = ? AND password = ? AND MaPQ = 1";
        $stmt = mysqli_prepare($this->con, $sql);
        if (!$stmt) return false;

        $matkhaucu_md5 = md5($matkhaucu);
        mysqli_stmt_bind_param($stmt, "is", $id, $matkhaucu_md5);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        return mysqli_num_rows($result) > 0;
    }

    public function DoiMatKhau($id, $matkhaumoi) {
        $sql = "UPDATE taikhoan SET password = ? WHERE ID = ? AND MaPQ = 1";
        $stmt = mysqli_prepare($this->con, $sql);
        if (!$stmt) return false;

        $matkhaumoi_md5 = md5($matkhaumoi);
        mysqli_stmt_bind_param($stmt, "si", $matkhaumoi_md5, $id);
        $ok = mysqli_stmt_execute($stmt);

        return $ok;
    }

    
    // Lấy tất cả nhân viên xét nghiệm đang làm việc
    public function GetAll() {
        $sql = "SELECT MaNV, HovaTen, NgaySinh, GioiTinh
                FROM nhanvien
                WHERE ChucVu = 'Nhân viên xét nghiệm'
                  AND TrangThaiLamViec = 'Đang làm việc'";
        $result = mysqli_query($this->con, $sql);

        $mang = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $mang[] = $row;
            }
        }

        // Trả về JSON để view dùng json_decode giống NV nhà thuốc
        return json_encode($mang);
    }
    

    // ================== THÊM NHÂN VIÊN XÉT NGHIỆM + TẠO TÀI KHOẢN ==================
    // $HovaTen, $NgaySinh (Y-m-d), $SoDT, $EmailNV, $GioiTinh
    public function AddNVXN($HovaTen, $NgaySinh, $SoDT, $EmailNV, $GioiTinh) {
        $conn = $this->con;

        // Chuẩn hóa dữ liệu
        $HovaTen  = trim($HovaTen);
        $NgaySinh = trim($NgaySinh);
        $SoDT     = trim($SoDT);
        $EmailNV  = trim($EmailNV);
        $GioiTinh = trim($GioiTinh);

        // 1. Kiểm tra trùng số điện thoại
        $sqlCheckPhone = "SELECT MaNV FROM nhanvien WHERE SoDT = ?";
        $stmtPhone = mysqli_prepare($conn, $sqlCheckPhone);
        if (!$stmtPhone) {
            return "Lỗi hệ thống (prepare check phone).";
        }
        mysqli_stmt_bind_param($stmtPhone, "s", $SoDT);
        mysqli_stmt_execute($stmtPhone);
        $resPhone = mysqli_stmt_get_result($stmtPhone);
        if ($resPhone && mysqli_num_rows($resPhone) > 0) {
            mysqli_stmt_close($stmtPhone);
            return "Số điện thoại đã tồn tại trong hệ thống.";
        }
        mysqli_stmt_close($stmtPhone);

        // 2. Kiểm tra trùng email
        $sqlCheckEmail = "SELECT MaNV FROM nhanvien WHERE EmailNV = ?";
        $stmtEmail = mysqli_prepare($conn, $sqlCheckEmail);
        if (!$stmtEmail) {
            return "Lỗi hệ thống (prepare check email).";
        }
        mysqli_stmt_bind_param($stmtEmail, "s", $EmailNV);
        mysqli_stmt_execute($stmtEmail);
        $resEmail = mysqli_stmt_get_result($stmtEmail);
        if ($resEmail && mysqli_num_rows($resEmail) > 0) {
            mysqli_stmt_close($stmtEmail);
            return "Email đã tồn tại trong hệ thống.";
        }
        mysqli_stmt_close($stmtEmail);

        // Bắt đầu transaction để đảm bảo thêm NV + tài khoản đồng bộ
        mysqli_begin_transaction($conn);
        try {
            // 3. Insert vào bảng nhanvien
            $sqlInsertNV = "INSERT INTO nhanvien (HovaTen, NgaySinh, GioiTinh, SoDT, EmailNV, ChucVu, TrangThaiLamViec)
                            VALUES (?, ?, ?, ?, ?, 'Nhân viên xét nghiệm', 'Đang làm việc')";
            $stmtNV = mysqli_prepare($conn, $sqlInsertNV);
            if (!$stmtNV) {
                throw new Exception("Lỗi prepare insert nhanvien.");
            }
            mysqli_stmt_bind_param($stmtNV, "sssss", $HovaTen, $NgaySinh, $GioiTinh, $SoDT, $EmailNV);
            $okNV = mysqli_stmt_execute($stmtNV);
            if (!$okNV) {
                mysqli_stmt_close($stmtNV);
                throw new Exception("Không thể thêm nhân viên xét nghiệm.");
            }
            // Lấy MaNV vừa insert
            $MaNV = mysqli_insert_id($conn);
            mysqli_stmt_close($stmtNV);

            // 4. Tạo tài khoản cho nhân viên xét nghiệm
            // Giả định: ID trong bảng taikhoan trùng với MaNV
            // Password mặc định = md5(số điện thoại)
            $passwordDefault = md5('123456'); // Mặc định là '123456'

            // ✅ THAY ĐỔI: thêm username = số điện thoại vào bảng taikhoan
            $sqlInsertTK = "INSERT INTO taikhoan (ID, username, password, MaPQ) VALUES (?, ?, ?, 6)";
            $stmtTK = mysqli_prepare($conn, $sqlInsertTK);
            if (!$stmtTK) {
                throw new Exception("Lỗi prepare insert taikhoan.");
            }
            mysqli_stmt_bind_param($stmtTK, "iss", $MaNV, $SoDT, $passwordDefault);
            $okTK = mysqli_stmt_execute($stmtTK);
            mysqli_stmt_close($stmtTK);

            if (!$okTK) {
                throw new Exception("Không thể tạo tài khoản cho nhân viên xét nghiệm.");
            }

            // Nếu mọi thứ ok -> commit
            mysqli_commit($conn);
            return true; // controller sẽ hiểu là 'true'
        } catch (Exception $e) {
            // Lỗi thì rollback
            mysqli_rollback($conn);
            return $e->getMessage();
        }
    }

    // Lấy thông tin chi tiết 1 nhân viên xét nghiệm theo MaNV
    public function Get1NVXN($MaNV) {
        $sql = "SELECT MaNV, HovaTen, NgaySinh, GioiTinh, SoDT, EmailNV
                FROM nhanvien
                WHERE MaNV = ?
                  AND ChucVu = 'Nhân viên xét nghiệm'";
        $stmt = mysqli_prepare($this->con, $sql);
        if (!$stmt) {
            return json_encode([]);
        }
        mysqli_stmt_bind_param($stmt, "i", $MaNV);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $mang = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $mang[] = $row;
            }
        }
        mysqli_stmt_close($stmt);

        return json_encode($mang);
    }

    // Cập nhật thông tin NVXN (ngày sinh, giới tính, email)
    public function UpdateNVXN($MaNV, $NgaySinh, $GioiTinh, $EmailNV) {
        // Ràng buộc đơn giản: email hợp lệ
        if (!filter_var($EmailNV, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        // Không cho email trùng với NV khác
        $sqlCheck = "SELECT MaNV FROM nhanvien WHERE EmailNV = ? AND MaNV <> ?";
        $stmtCheck = mysqli_prepare($this->con, $sqlCheck);
        if (!$stmtCheck) {
            return false;
        }
        mysqli_stmt_bind_param($stmtCheck, "si", $EmailNV, $MaNV);
        mysqli_stmt_execute($stmtCheck);
        $resCheck = mysqli_stmt_get_result($stmtCheck);
        if ($resCheck && mysqli_num_rows($resCheck) > 0) {
            mysqli_stmt_close($stmtCheck);
            return false; // email đã tồn tại
        }
        mysqli_stmt_close($stmtCheck);

        $sql = "UPDATE nhanvien
                SET NgaySinh = ?, GioiTinh = ?, EmailNV = ?
                WHERE MaNV = ?
                  AND ChucVu = 'Nhân viên xét nghiệm'";
        $stmt = mysqli_prepare($this->con, $sql);
        if (!$stmt) {
            return false;
        }
        mysqli_stmt_bind_param($stmt, "sssi", $NgaySinh, $GioiTinh, $EmailNV, $MaNV);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        return $ok;
    }

    // Thôi việc NVXN (không xóa cứng, chỉ đổi trạng thái)
    public function DeleteNVXN($MaNV) {
        $sql = "UPDATE nhanvien
                SET TrangThaiLamViec = 'Thôi việc'
                WHERE MaNV = ?
                  AND ChucVu = 'Nhân viên xét nghiệm'";
        $stmt = mysqli_prepare($this->con, $sql);
        if (!$stmt) {
            return false;
        }
        mysqli_stmt_bind_param($stmt, "i", $MaNV);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $ok;
    }
}
?>
