<?php
// Model cho các chức năng Điểm danh / Cấu hình ca / Quản lý mẫu khuôn mặt
class mQlydd extends DB
{
    // ================== CẤU HÌNH CA LÀM VIỆC ==================

    // Lấy danh sách cấu hình ca (Sáng / Chiều)
    public function GetCauHinhCa()
    {
        $sql = "
            SELECT 
                MaCa, 
                CaLamViec, 
                GioBatDau, 
                GioKetThuc, 
                GioiHanSomPhut,   -- [MỚI]
                GioiHanTrePhut,   -- [MỚI]
                GhiChu
            FROM cauhinh_ca
            ORDER BY MaCa
        ";
        $result = mysqli_query($this->con, $sql);

        $rows = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $rows[] = $row;
            }
        }

        return $rows;
    }

    // Cập nhật 1 dòng cấu hình ca
    // [SỬA] thêm tham số $gioiHanSomPhut, $gioiHanTrePhut
    public function UpdateCauHinhCa($maCa, $gioBatDau, $gioKetThuc, $gioiHanSomPhut, $gioiHanTrePhut, $ghiChu)
    {
        $sql = "
            UPDATE cauhinh_ca
            SET 
                GioBatDau       = ?, 
                GioKetThuc      = ?, 
                GioiHanSomPhut  = ?, 
                GioiHanTrePhut  = ?, 
                GhiChu          = ?
            WHERE MaCa = ?
        ";

        $stmt = mysqli_prepare($this->con, $sql);
        if (!$stmt) {
            return false;
        }

        // s = time, s = time, i = int, i = int, s = text, i = int
        mysqli_stmt_bind_param(
            $stmt,
            "ssiisi",
            $gioBatDau,
            $gioKetThuc,
            $gioiHanSomPhut,
            $gioiHanTrePhut,
            $ghiChu,
            $maCa
        );

        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        return $ok;
    }

    // ================== QUẢN LÝ MẪU KHUÔN MẶT: DANH SÁCH ==================

    // Lấy danh sách khoa (dùng cho filter Bác sĩ)
    public function GetDanhSachKhoa()
    {
        $sql = "SELECT MaKhoa, TenKhoa FROM chuyenkhoa ORDER BY TenKhoa";
        $result = mysqli_query($this->con, $sql);

        $rows = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $rows[] = $row;
            }
        }

        return $rows;
    }

    /**
     * Lấy danh sách nhân viên + trạng thái đã/chưa có mẫu khuôn mặt
     * @param string $chucVu   Lọc theo chức vụ ('' = tất cả)
     * @param string $maKhoa   Lọc theo mã khoa (chỉ áp dụng khi ChucVu = 'Bác sĩ')
     * @param string $soDT     Tìm kiếm theo SĐT (LIKE)
     * @return array
     */
    public function GetNhanVienForFace($chucVu = '', $maKhoa = '', $soDT = '')
    {
        // Base query
        $sql = "
            SELECT 
                nv.MaNV,
                nv.HovaTen,
                nv.SoDT,
                nv.ChucVu,
                nv.EmailNV,
                nv.TrangThaiLamViec,
                ck.TenKhoa,
                ft.UpdatedAt,
                CASE WHEN ft.MaNV IS NULL THEN 0 ELSE 1 END AS HasFace
            FROM nhanvien nv
            LEFT JOIN bacsi bs ON nv.MaNV = bs.MaNV
            LEFT JOIN chuyenkhoa ck ON bs.MaKhoa = ck.MaKhoa
            LEFT JOIN face_template ft ON nv.MaNV = ft.MaNV
            WHERE 1 = 1
        ";

        $params = [];
        $types  = "";

        // Lọc theo chức vụ (nếu có chọn)
        if ($chucVu !== '') {
            $sql   .= " AND nv.ChucVu = ? ";
            $types .= "s";
            $params[] = $chucVu;
        }

        // Nếu là bác sĩ và có chọn khoa thì lọc khoa
        if ($chucVu === 'Bác sĩ' && $maKhoa !== '') {
            $sql   .= " AND ck.MaKhoa = ? ";
            $types .= "i";
            $params[] = (int)$maKhoa;
        }

        // Tìm theo SĐT (LIKE)
        if ($soDT !== '') {
            $sql   .= " AND nv.SoDT LIKE ? ";
            $types .= "s";
            $params[] = '%' . $soDT . '%';
        }

        // Sắp xếp theo Mã NV giảm dần
        $sql .= " ORDER BY nv.MaNV DESC ";

        $stmt = mysqli_prepare($this->con, $sql);
        if (!$stmt) {
            return [];
        }

        if ($types !== "") {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }

        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $rows = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $rows[] = $row;
            }
        }

        mysqli_stmt_close($stmt);
        return $rows;
    }

    // ================== QUẢN LÝ MẪU KHUÔN MẶT: ENROLL 1 NV ==================

    // Lấy thông tin 1 nhân viên theo MaNV
    public function GetNhanVienById($maNV)
    {
        $sql = "
            SELECT 
                nv.MaNV,
                nv.HovaTen,
                nv.SoDT,
                nv.ChucVu,
                nv.EmailNV,
                nv.TrangThaiLamViec,
                ck.TenKhoa
            FROM nhanvien nv
            LEFT JOIN bacsi bs ON nv.MaNV = bs.MaNV
            LEFT JOIN chuyenkhoa ck ON bs.MaKhoa = ck.MaKhoa
            WHERE nv.MaNV = ?
            LIMIT 1
        ";

        $stmt = mysqli_prepare($this->con, $sql);
        if (!$stmt) {
            return null;
        }

        mysqli_stmt_bind_param($stmt, "i", $maNV);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $row = null;
        if ($result) {
            $row = mysqli_fetch_assoc($result);
        }

        mysqli_stmt_close($stmt);
        return $row;
    }

    // Lấy template khuôn mặt hiện tại của NV (nếu có)
    public function GetFaceTemplateByMaNV($maNV)
    {
        $sql = "
            SELECT MaTemplate, MaNV, Descriptor, CreatedAt, UpdatedAt
            FROM face_template
            WHERE MaNV = ?
            LIMIT 1
        ";

        $stmt = mysqli_prepare($this->con, $sql);
        if (!$stmt) {
            return null;
        }

        mysqli_stmt_bind_param($stmt, "i", $maNV);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $row = null;
        if ($result) {
            $row = mysqli_fetch_assoc($result);
        }

        mysqli_stmt_close($stmt);
        return $row;
    }

    // Thêm / cập nhật template khuôn mặt cho NV
    public function UpsertFaceTemplate($maNV, $descriptorJson)
    {
        // Kiểm tra đã tồn tại hay chưa
        $checkSql = "SELECT MaTemplate FROM face_template WHERE MaNV = ? LIMIT 1";
        $stmtCheck = mysqli_prepare($this->con, $checkSql);
        if (!$stmtCheck) {
            return false;
        }

        mysqli_stmt_bind_param($stmtCheck, "i", $maNV);
        mysqli_stmt_execute($stmtCheck);
        mysqli_stmt_bind_result($stmtCheck, $maTemplate);
        $exists = mysqli_stmt_fetch($stmtCheck);
        mysqli_stmt_close($stmtCheck);

        if ($exists) {
            // Cập nhật
            $updateSql = "
                UPDATE face_template
                SET Descriptor = ?, UpdatedAt = NOW()
                WHERE MaTemplate = ?
            ";
            $stmtUpdate = mysqli_prepare($this->con, $updateSql);
            if (!$stmtUpdate) {
                return false;
            }
            mysqli_stmt_bind_param($stmtUpdate, "si", $descriptorJson, $maTemplate);
            $ok = mysqli_stmt_execute($stmtUpdate);
            mysqli_stmt_close($stmtUpdate);
            return $ok;
        } else {
            // Thêm mới
            $insertSql = "
                INSERT INTO face_template (MaNV, Descriptor, CreatedAt, UpdatedAt)
                VALUES (?, ?, NOW(), NOW())
            ";
            $stmtInsert = mysqli_prepare($this->con, $insertSql);
            if (!$stmtInsert) {
                return false;
            }
            mysqli_stmt_bind_param($stmtInsert, "is", $maNV, $descriptorJson);
            $ok = mysqli_stmt_execute($stmtInsert);
            mysqli_stmt_close($stmtInsert);
            return $ok;
        }
    }

    // ================== ĐIỂM DANH KHUÔN MẶT ==================

    // Lấy toàn bộ template + thông tin nhân viên (chỉ nhân viên đang làm việc)
    public function GetAllFaceTemplates()
    {
        $sql = "
            SELECT 
                ft.MaTemplate,
                ft.MaNV,
                ft.Descriptor,
                ft.CreatedAt,
                ft.UpdatedAt,
                nv.HovaTen,
                nv.ChucVu,
                nv.SoDT,
                nv.EmailNV,
                nv.TrangThaiLamViec
            FROM face_template ft
            INNER JOIN nhanvien nv ON nv.MaNV = ft.MaNV
            WHERE nv.TrangThaiLamViec = 'Đang làm việc'
        ";

        $result = mysqli_query($this->con, $sql);
        $rows = [];

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $rows[] = $row;
            }
        }

        return $rows;
    }

    // Tìm ca làm việc hiện tại dựa trên giờ (H:i:s)
    // [SỬA] Cho phép tìm ca ngay cả khi đến sớm trong giới hạn GioiHanSomPhut
    public function GetCaLamViecByTime($timeStr)
    {
        $sql = "
            SELECT 
                MaCa, 
                CaLamViec, 
                GioBatDau, 
                GioKetThuc, 
                GioiHanSomPhut,
                GioiHanTrePhut,
                GhiChu
            FROM cauhinh_ca
            WHERE 
                -- Cho phép điểm danh từ (GioBatDau - GioiHanSomPhut phút) đến GioKetThuc
                SUBTIME(GioBatDau, SEC_TO_TIME(GioiHanSomPhut * 60)) <= ?
                AND GioKetThuc >= ?
            LIMIT 1
        ";

        $stmt = mysqli_prepare($this->con, $sql);
        if (!$stmt) {
            return null;
        }

        mysqli_stmt_bind_param($stmt, "ss", $timeStr, $timeStr);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $row = null;
        if ($result) {
            $row = mysqli_fetch_assoc($result);
        }

        mysqli_stmt_close($stmt);
        return $row;
    }

    // Lấy lịch làm việc của NV theo ngày + ca
    public function GetLichLamViecByNVDateCa($maNV, $ngayLamViec, $caLamViec)
    {
        $sql = "
            SELECT MaLLV
            FROM lichlamviec
            WHERE MaNV = ? AND NgayLamViec = ? AND CaLamViec = ?
            LIMIT 1
        ";

        $stmt = mysqli_prepare($this->con, $sql);
        if (!$stmt) {
            return null;
        }

        mysqli_stmt_bind_param($stmt, "iss", $maNV, $ngayLamViec, $caLamViec);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $row = null;
        if ($result) {
            $row = mysqli_fetch_assoc($result);
        }

        mysqli_stmt_close($stmt);
        return $row; // có thể là null
    }

    // Kiểm tra đã có bản ghi điểm danh cho lịch đó hay chưa
    public function GetDiemDanhByLLVNV($maLLV, $maNV)
    {
        $sql = "
            SELECT MaDD, ThoiGianDiemDanh, KetQua, GhiChu
            FROM diemdanh
            WHERE MaLLV = ? AND MaNV = ?
            ORDER BY ThoiGianDiemDanh ASC
            LIMIT 1
        ";

        $stmt = mysqli_prepare($this->con, $sql);
        if (!$stmt) {
            return null;
        }

        mysqli_stmt_bind_param($stmt, "ii", $maLLV, $maNV);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $row = null;
        if ($result) {
            $row = mysqli_fetch_assoc($result);
        }

        mysqli_stmt_close($stmt);
        return $row;
    }

    // Thêm bản ghi điểm danh mới
    public function InsertDiemDanh($maLLV, $maNV, $dateTimeStr, $ketQua, $ghiChu)
    {
        $sql = "
            INSERT INTO diemdanh (MaLLV, MaNV, ThoiGianDiemDanh, KetQua, GhiChu)
            VALUES (?, ?, ?, ?, ?)
        ";

        $stmt = mysqli_prepare($this->con, $sql);
        if (!$stmt) {
            return false;
        }

        mysqli_stmt_bind_param($stmt, "iisss", $maLLV, $maNV, $dateTimeStr, $ketQua, $ghiChu);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        return $ok;
    }

    // ================== DANH SÁCH ĐIỂM DANH THEO NGÀY ==================
    // [MỚI] Lấy danh sách điểm danh theo khoảng ngày + bộ lọc
    public function GetDiemDanhTheoNgay($fromDate, $toDate, $chucVu = '', $maKhoa = '', $soDT = '', $maNV = 0)
    {
        $sql = "
            SELECT
                dd.MaDD,
                dd.ThoiGianDiemDanh,
                dd.KetQua,
                dd.GhiChu,
                llv.MaLLV,
                llv.NgayLamViec,
                llv.CaLamViec,
                nv.MaNV,
                nv.HovaTen,
                nv.SoDT,
                nv.ChucVu,
                ck.TenKhoa
            FROM diemdanh dd
            INNER JOIN lichlamviec llv ON dd.MaLLV = llv.MaLLV
            INNER JOIN nhanvien nv ON dd.MaNV = nv.MaNV
            LEFT JOIN bacsi bs ON nv.MaNV = bs.MaNV
            LEFT JOIN chuyenkhoa ck ON bs.MaKhoa = ck.MaKhoa
            WHERE 1 = 1
              AND llv.NgayLamViec BETWEEN ? AND ?
        ";

        $params = [];
        $types  = "";

        // Hai tham số ngày
        $types   .= "ss";
        $params[] = $fromDate;
        $params[] = $toDate;

        // Lọc theo chức vụ
        if ($chucVu !== '') {
            $sql   .= " AND nv.ChucVu = ? ";
            $types .= "s";
            $params[] = $chucVu;
        }

        // Nếu là bác sĩ và có chọn khoa thì lọc khoa
        if ($chucVu === 'Bác sĩ' && $maKhoa !== '') {
            $sql   .= " AND ck.MaKhoa = ? ";
            $types .= "i";
            $params[] = (int)$maKhoa;
        }

        // Lọc theo SĐT
        if ($soDT !== '') {
            $sql   .= " AND nv.SoDT LIKE ? ";
            $types .= "s";
            $params[] = '%' . $soDT . '%';
        }

        // Lọc theo một nhân viên cụ thể (nếu có)
        if (!empty($maNV) && (int)$maNV > 0) {
            $sql   .= " AND nv.MaNV = ? ";
            $types .= "i";
            $params[] = (int)$maNV;
        }

        // Sắp xếp: ngày mới nhất trước, trong ngày thì theo giờ điểm danh
        $sql .= "
            ORDER BY 
                llv.NgayLamViec DESC,
                dd.ThoiGianDiemDanh ASC
        ";

        $stmt = mysqli_prepare($this->con, $sql);
        if (!$stmt) {
            return [];
        }

        mysqli_stmt_bind_param($stmt, $types, ...$params);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $rows = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $rows[] = $row;
            }
        }

        mysqli_stmt_close($stmt);
        return $rows;
    }

    // [MỚI] Lấy danh sách nhân viên đang làm việc (dùng cho filter dropdown)
    public function GetAllNhanVienDangLam()
    {
        $sql = "
            SELECT 
                nv.MaNV,
                nv.HovaTen,
                nv.SoDT,
                nv.ChucVu,
                nv.TrangThaiLamViec,
                ck.TenKhoa
            FROM nhanvien nv
            LEFT JOIN bacsi bs ON nv.MaNV = bs.MaNV
            LEFT JOIN chuyenkhoa ck ON bs.MaKhoa = ck.MaKhoa
            WHERE nv.TrangThaiLamViec = 'Đang làm việc'
            ORDER BY nv.HovaTen ASC
        ";

        $result = mysqli_query($this->con, $sql);
        $rows = [];

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $rows[] = $row;
            }
        }

        return $rows;
    }
}
?>
