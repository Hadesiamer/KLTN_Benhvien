<?php
class mNVNT extends DB {

    // Đếm tổng số đơn thuốc KE_DON (có áp dụng lọc + tìm kiếm)
    public function GetTotalInvoicesFiltered($loc, $keyword) {
        $allowed = ['all', 'Chua thanh toan', 'Da thanh toan', 'Huy'];
        if (!in_array($loc, $allowed, true)) {
            $loc = 'all';
        }

        $keyword = trim($keyword);
        $conditions = [];

        // Chỉ lấy đơn bác sĩ kê
        $conditions[] = "d.LoaiDon = 'KE_DON'";

        // Lọc trạng thái
        if ($loc === 'Chua thanh toan') {
            $conditions[] = "(h.TrangThai = 'Chua thanh toan' OR h.MaDon IS NULL)";
        } elseif ($loc === 'Da thanh toan' || $loc === 'Huy') {
            $conditions[] = "h.TrangThai = '" . mysqli_real_escape_string($this->con, $loc) . "'";
        }

        // Tìm kiếm theo MaBN hoặc SoDT
        if ($keyword !== '') {
            $kwEsc = mysqli_real_escape_string($this->con, $keyword);
            $maBN  = (int)$keyword;
            $conditions[] = "(b.MaBN = $maBN OR b.SoDT LIKE '%$kwEsc%')";
        }

        $where = '';
        if (!empty($conditions)) {
            $where = 'WHERE ' . implode(' AND ', $conditions);
        }

        $str = "
            SELECT COUNT(*) AS total
            FROM don_thuoc AS d
            LEFT JOIN hoadon   AS h ON h.MaDon = d.MaDon
            LEFT JOIN benhnhan AS b ON d.MaBN  = b.MaBN
            $where
        ";

        $result = mysqli_query($this->con, $str);
        $row = mysqli_fetch_assoc($result);
        return $row ? (int)$row['total'] : 0;
    }

    // Giữ lại để dùng chung nếu cần (đếm tất cả KE_DON, không search)
    public function GetTotalInvoices() {
        return $this->GetTotalInvoicesFiltered('all', '');
    }

    // Lấy danh sách đơn thuốc KE_DON theo lọc + tìm kiếm
    // $loc: 'all' | 'Chua thanh toan' | 'Da thanh toan' | 'Huy'
    public function GetDTTheoLoc($offset, $limit, $loc, $keyword = '') {
        $offset = (int)$offset;
        $limit  = (int)$limit;

        $allowed = ['all', 'Chua thanh toan', 'Da thanh toan', 'Huy'];
        if (!in_array($loc, $allowed, true)) {
            $loc = 'all';
        }

        $keyword = trim($keyword);
        $conditions = [];

        // Chỉ lấy đơn bác sĩ kê
        $conditions[] = "d.LoaiDon = 'KE_DON'";

        // Lọc trạng thái
        if ($loc === 'Chua thanh toan') {
            $conditions[] = "(h.TrangThai = 'Chua thanh toan' OR h.MaDon IS NULL)";
        } elseif ($loc === 'Da thanh toan' || $loc === 'Huy') {
            $conditions[] = "h.TrangThai = '" . mysqli_real_escape_string($this->con, $loc) . "'";
        }

        // Tìm kiếm theo MaBN hoặc SoDT
        if ($keyword !== '') {
            $kwEsc = mysqli_real_escape_string($this->con, $keyword);
            $maBN  = (int)$keyword;
            $conditions[] = "(b.MaBN = $maBN OR b.SoDT LIKE '%$kwEsc%')";
        }

        $where = '';
        if (!empty($conditions)) {
            $where = 'WHERE ' . implode(' AND ', $conditions);
        }

        $str = "
            SELECT 
                d.MaDon AS MaDT,
                d.NgayKe AS NgayTao,
                COALESCE(h.TrangThai, 'Chua thanh toan') AS TrangThai,
                d.GhiChuChung AS MoTa,
                b.HovaTen
            FROM don_thuoc AS d
            LEFT JOIN hoadon   AS h ON h.MaDon = d.MaDon
            LEFT JOIN benhnhan AS b ON d.MaBN  = b.MaBN
            $where
            ORDER BY d.MaDon DESC
            LIMIT $offset, $limit
        ";

        $rows = mysqli_query($this->con, $str);
        $mang = array();
        while ($row = mysqli_fetch_array($rows)) {
            $mang[] = $row;
        }
        return json_encode($mang);
    }

    // Lấy thông tin chung của 1 đơn thuốc (header đơn KE_DON)
    public function getCTDT($MaDT) {
        $MaDT = (int)$MaDT;

        $str = "
            SELECT 
                d.MaDon AS MaDT,
                d.NgayKe AS NgayTao,
                d.GhiChuChung AS MoTa,
                d.LoaiDon,
                COALESCE(h.TrangThai, 'Chua thanh toan') AS TrangThai,
                b.HovaTen,
                b.Email,
                b.SoDT,
                b.BHYT,
                nv.HovaTen AS TenBS
            FROM don_thuoc AS d
            LEFT JOIN hoadon   AS h ON h.MaDon = d.MaDon
            LEFT JOIN benhnhan AS b ON d.MaBN  = b.MaBN
            LEFT JOIN nhanvien AS nv ON d.MaBS = nv.MaNV
            WHERE d.MaDon = $MaDT
            LIMIT 1
        ";

        $rows = mysqli_query($this->con, $str);
        $mang = array();
        while ($row = mysqli_fetch_array($rows)) {
            $mang[] = $row;
        }
        return json_encode($mang);
    }

    // Lấy danh sách thuốc trong 1 đơn (dùng chung KE_DON + BAN_LE)
    public function getThuoc($MaDT) {
        $MaDT = (int)$MaDT;

        $str = "
            SELECT 
                dt.MaCT,
                dt.MaDon,
                dt.MaThuoc,
                dt.SoLuong,
                dt.LieuDung,
                dt.GhiChu,
                t.TenThuoc,
                t.DonGiaBan
            FROM ct_don_thuoc AS dt
            JOIN thuoc        AS t ON dt.MaThuoc = t.MaThuoc
            WHERE dt.MaDon = $MaDT
            ORDER BY dt.MaCT ASC
        ";

        $rows = mysqli_query($this->con, $str);
        $mang = array();
        while ($row = mysqli_fetch_array($rows)) {
            $mang[] = $row;
        }
        return json_encode($mang);
    }

    // Tính tổng tiền của đơn thuốc (từ ct_don_thuoc * DonGiaBan)
    private function tinhTongTienDonThuoc($MaDT) {
        $MaDT = (int)$MaDT;

        $str = "
            SELECT 
                SUM(dt.SoLuong * t.DonGiaBan) AS TongTien,
                MAX(d.MaBN) AS MaBN
            FROM don_thuoc AS d
            JOIN ct_don_thuoc AS dt ON d.MaDon = dt.MaDon
            JOIN thuoc        AS t  ON dt.MaThuoc = t.MaThuoc
            WHERE d.MaDon = $MaDT
        ";

        $result = mysqli_query($this->con, $str);
        $row = mysqli_fetch_assoc($result);
        if (!$row || $row['TongTien'] === null) {
            return null;
        }

        return [
            'TongTien' => (float)$row['TongTien'],
            'MaBN'     => (int)$row['MaBN']
        ];
    }

    // Tạo hoặc cập nhật hóa đơn thành "Đã thanh toán" cho KE_DON
    public function xacNhanThanhToan($MaDT) {
        $MaDT = (int)$MaDT;

        $info = $this->tinhTongTienDonThuoc($MaDT);
        if ($info === null) {
            return false;
        }

        $TongTien = (int)$info['TongTien'];
        $MaBN     = (int)$info['MaBN'];

        // Kiểm tra đã có hóa đơn cho đơn này chưa
        $sqlCheck = "SELECT MaHD FROM hoadon WHERE MaDon = $MaDT LIMIT 1";
        $rsCheck  = mysqli_query($this->con, $sqlCheck);
        $rowCheck = mysqli_fetch_assoc($rsCheck);

        if ($rowCheck) {
            // Cập nhật hóa đơn cũ
            $sqlUpdate = "
                UPDATE hoadon
                SET TongTien = $TongTien,
                    TrangThai = 'Da thanh toan',
                    NgayLapHoaDon = NOW()
                WHERE MaDon = $MaDT
                LIMIT 1
            ";
            return mysqli_query($this->con, $sqlUpdate) ? true : false;
        } else {
            // Tạo hóa đơn mới, 1 bước: luôn 'Da thanh toan'
            $sqlInsert = "
                INSERT INTO hoadon (MaBN, MaDon, NgayLapHoaDon, TongTien, MaPTTT, TrangThai)
                VALUES ($MaBN, $MaDT, NOW(), $TongTien, 0, 'Da thanh toan')
            ";
            return mysqli_query($this->con, $sqlInsert) ? true : false;
        }
    }

    // Hủy đơn thuốc KE_DON: hóa đơn gắn với đơn chuyển sang 'Huy'
    public function huyDonThuoc($MaDT) {
        $MaDT = (int)$MaDT;

        $info = $this->tinhTongTienDonThuoc($MaDT);
        if ($info === null) {
            return false;
        }

        $TongTien = (int)$info['TongTien'];
        $MaBN     = (int)$info['MaBN'];

        // Kiểm tra đã có hóa đơn cho đơn này chưa
        $sqlCheck = "SELECT MaHD FROM hoadon WHERE MaDon = $MaDT LIMIT 1";
        $rsCheck  = mysqli_query($this->con, $sqlCheck);
        $rowCheck = mysqli_fetch_assoc($rsCheck);

        if ($rowCheck) {
            $sqlUpdate = "
                UPDATE hoadon
                SET TongTien = $TongTien,
                    TrangThai = 'Huy',
                    NgayLapHoaDon = NOW()
                WHERE MaDon = $MaDT
                LIMIT 1
            ";
            return mysqli_query($this->con, $sqlUpdate) ? true : false;
        } else {
            $sqlInsert = "
                INSERT INTO hoadon (MaBN, MaDon, NgayLapHoaDon, TongTien, MaPTTT, TrangThai)
                VALUES ($MaBN, $MaDT, NOW(), $TongTien, 0, 'Huy')
            ";
            return mysqli_query($this->con, $sqlInsert) ? true : false;
        }
    }

    // ====================================
    // CÁC HÀM MỚI: BÁN LẺ THUỐC (BAN_LE)
    // ====================================

    // LẤY MaNV TỪ BẢNG NHANVIEN DỰA TRÊN ID TÀI KHOẢN
    // LƯU Ý: Nếu cột liên kết trong bảng nhanvien không phải là 'ID' thì đổi lại ở đây
    public function getMaNhanVienByTaiKhoan($idTaiKhoan) {
        $idTaiKhoan = (int)$idTaiKhoan;
        if ($idTaiKhoan <= 0) {
            return 0;
        }

        // GIẢ ĐỊNH cột liên kết là 'ID'. Nếu bạn dùng tên khác (IdTK, MaTK...) thì sửa lại:
        // Ví dụ: SELECT MaNV FROM nhanvien WHERE IdTK = $idTaiKhoan LIMIT 1
        $sql = "
            SELECT MaNV 
            FROM nhanvien 
            WHERE ID = $idTaiKhoan
            LIMIT 1
        ";

        $result = mysqli_query($this->con, $sql);
        $row = mysqli_fetch_assoc($result);
        if ($row && isset($row['MaNV'])) {
            return (int)$row['MaNV'];
        }
        return 0;
    }

    // Danh sách đơn bán lẻ theo nhân viên + khoảng ngày
    // Lưu ý: $maNhanVien ở đây là MaNV (không phải ID tài khoản)
    public function getBanLeList($maNhanVien, $fromDate, $toDate) {
        $maNhanVien = (int)$maNhanVien;
        $from = mysqli_real_escape_string($this->con, $fromDate);
        $to   = mysqli_real_escape_string($this->con, $toDate);

        $str = "
            SELECT 
                d.MaDon,
                d.NgayKe,
                d.GhiChuChung,
                COALESCE(h.TongTien, 0) AS TongTien
            FROM don_thuoc AS d
            LEFT JOIN hoadon AS h ON h.MaDon = d.MaDon
            WHERE d.LoaiDon = 'BAN_LE'
              AND d.MaNhanVien = $maNhanVien
              AND DATE(d.NgayKe) BETWEEN '$from' AND '$to'
            ORDER BY d.MaDon DESC
        ";

        $rows = mysqli_query($this->con, $str);
        $mang = [];
        while ($row = mysqli_fetch_array($rows)) {
            $mang[] = $row;
        }
        return json_encode($mang);
    }

    // Lấy thông tin header đơn bán lẻ
    public function getBanLeHeader($MaDon) {
        $MaDon = (int)$MaDon;

        $str = "
            SELECT 
                d.MaDon,
                d.NgayKe,
                d.GhiChuChung,
                d.LoaiDon,
                COALESCE(h.TongTien, 0) AS TongTien,
                nv.HovaTen AS TenNV
            FROM don_thuoc AS d
            LEFT JOIN hoadon AS h ON h.MaDon = d.MaDon
            LEFT JOIN nhanvien AS nv ON d.MaNhanVien = nv.MaNV
            WHERE d.MaDon = $MaDon
              AND d.LoaiDon = 'BAN_LE'
            LIMIT 1
        ";

        $rows = mysqli_query($this->con, $str);
        $mang = [];
        while ($row = mysqli_fetch_array($rows)) {
            $mang[] = $row;
        }
        return json_encode($mang);
    }

    // Kiểm tra có được phép sửa đơn bán lẻ không (chỉ trong ngày hôm đó)
    public function coTheSuaDonBanLe($MaDon) {
        $MaDon = (int)$MaDon;

        $str = "
            SELECT 
                CASE 
                    WHEN d.LoaiDon = 'BAN_LE' AND DATE(d.NgayKe) = CURDATE() 
                        THEN 1 
                    ELSE 0 
                END AS ChoPhep
            FROM don_thuoc AS d
            WHERE d.MaDon = $MaDon
            LIMIT 1
        ";

        $result = mysqli_query($this->con, $str);
        $row = mysqli_fetch_assoc($result);

        if ($row && (int)$row['ChoPhep'] === 1) {
            return true;
        }
        return false;
    }

    // Tạo đơn bán lẻ mới (MaBN = 19 - KHÁCH VÃNG LAI) + hóa đơn ĐÃ THANH TOÁN
    // Lưu ý: $maNhanVien ở đây là MaNV đã tra ra từ bảng nhanvien
    public function taoDonBanLe($maNhanVien, $ghiChuChung, $dsThuoc) {
        $maNhanVien = (int)$maNhanVien;
        $ghiChuEsc  = mysqli_real_escape_string($this->con, $ghiChuChung);

        // Tạo don_thuoc
        $sqlDon = "
            INSERT INTO don_thuoc (MaLK, MaPK, MaBN, MaBS, MaNhanVien, LoaiDon, NgayKe, GhiChuChung)
            VALUES (NULL, NULL, 19, NULL, $maNhanVien, 'BAN_LE', NOW(), '$ghiChuEsc')
        ";

        if (!mysqli_query($this->con, $sqlDon)) {
            return 0;
        }

        $maDon = mysqli_insert_id($this->con);
        if ($maDon <= 0) {
            return 0;
        }

        // Thêm chi tiết đơn thuốc
        foreach ($dsThuoc as $item) {
            $maThuoc = (int)$item['MaThuoc'];
            $soLuong = (int)$item['SoLuong'];
            $lieuDung = mysqli_real_escape_string($this->con, $item['LieuDung']);

            if ($maThuoc <= 0 || $soLuong <= 0) {
                continue;
            }

            $sqlCT = "
                INSERT INTO ct_don_thuoc (MaDon, MaThuoc, SoLuong, LieuDung, GhiChu)
                VALUES ($maDon, $maThuoc, $soLuong, '$lieuDung', '')
            ";
            mysqli_query($this->con, $sqlCT);
        }

        // Tính tổng tiền và tạo hóa đơn 'Da thanh toan'
        $info = $this->tinhTongTienDonThuoc($maDon);
        if ($info === null) {
            return $maDon; // vẫn trả về mã đơn, nhưng không có hóa đơn
        }

        $TongTien = (int)$info['TongTien'];
        $MaBN     = 19; // khách vãng lai

        // Tạo hóa đơn mới
        $sqlHD = "
            INSERT INTO hoadon (MaBN, MaDon, NgayLapHoaDon, TongTien, MaPTTT, TrangThai)
            VALUES ($MaBN, $maDon, NOW(), $TongTien, 0, 'Da thanh toan')
        ";
        mysqli_query($this->con, $sqlHD);

        return $maDon;
    }

    // Cập nhật đơn bán lẻ trong ngày (xóa chi tiết cũ, thêm lại, cập nhật hóa đơn)
    public function capNhatDonBanLe($MaDon, $ghiChuChung, $dsThuoc) {
        $MaDon = (int)$MaDon;

        // Chỉ cho phép sửa đơn BAN_LE trong ngày hôm nay
        if (!$this->coTheSuaDonBanLe($MaDon)) {
            return false;
        }

        $ghiChuEsc = mysqli_real_escape_string($this->con, $ghiChuChung);

        // Cập nhật ghi chú chung
        $sqlUpdateDon = "
            UPDATE don_thuoc
            SET GhiChuChung = '$ghiChuEsc'
            WHERE MaDon = $MaDon
              AND LoaiDon = 'BAN_LE'
            LIMIT 1
        ";
        mysqli_query($this->con, $sqlUpdateDon);

        // Xóa chi tiết cũ
        $sqlDelCT = "DELETE FROM ct_don_thuoc WHERE MaDon = $MaDon";
        mysqli_query($this->con, $sqlDelCT);

        // Thêm chi tiết mới
        foreach ($dsThuoc as $item) {
            $maThuoc = (int)$item['MaThuoc'];
            $soLuong = (int)$item['SoLuong'];
            $lieuDung = mysqli_real_escape_string($this->con, $item['LieuDung']);

            if ($maThuoc <= 0 || $soLuong <= 0) {
                continue;
            }

            $sqlCT = "
                INSERT INTO ct_don_thuoc (MaDon, MaThuoc, SoLuong, LieuDung, GhiChu)
                VALUES ($MaDon, $maThuoc, $soLuong, '$lieuDung', '')
            ";
            mysqli_query($this->con, $sqlCT);
        }

        // Tính lại tổng tiền
        $info = $this->tinhTongTienDonThuoc($MaDon);
        if ($info === null) {
            return true;
        }

        $TongTien = (int)$info['TongTien'];
        $MaBN     = 19;

        // Kiểm tra hóa đơn đã tồn tại chưa
        $sqlCheck = "SELECT MaHD FROM hoadon WHERE MaDon = $MaDon LIMIT 1";
        $rsCheck  = mysqli_query($this->con, $sqlCheck);
        $rowCheck = mysqli_fetch_assoc($rsCheck);

        if ($rowCheck) {
            $sqlUpdateHD = "
                UPDATE hoadon
                SET TongTien = $TongTien,
                    TrangThai = 'Da thanh toan',
                    NgayLapHoaDon = NOW()
                WHERE MaDon = $MaDon
                LIMIT 1
            ";
            mysqli_query($this->con, $sqlUpdateHD);
        } else {
            $sqlInsertHD = "
                INSERT INTO hoadon (MaBN, MaDon, NgayLapHoaDon, TongTien, MaPTTT, TrangThai)
                VALUES ($MaBN, $MaDon, NOW(), $TongTien, 0, 'Da thanh toan')
            ";
            mysqli_query($this->con, $sqlInsertHD);
        }

        return true;
    }

    // Danh sách thuốc cho dropdown (thuốc đang hoạt động)
    public function getDanhSachThuocDropdown() {
        $str = "
            SELECT 
                MaThuoc,
                TenThuoc,
                TenHoatChat,
                HamLuong,
                DangBaoChe,
                DonViTinh,
                DonGiaBan
            FROM thuoc
            WHERE TrangThai = 1
            ORDER BY TenThuoc ASC
        ";

        $rows = mysqli_query($this->con, $str);
        $mang = [];
        while ($row = mysqli_fetch_assoc($rows)) {
            $mang[] = $row;
        }
        return json_encode($mang);
    }

    // Tìm thuốc theo tên / hoạt chất cho autosuggest
    public function timThuocTheoTen($keyword) {
        $keyword = trim($keyword);
        if ($keyword === '') {
            return [];
        }

        $kwEsc = mysqli_real_escape_string($this->con, $keyword);

        $str = "
            SELECT 
                MaThuoc,
                TenThuoc,
                TenHoatChat,
                HamLuong,
                DangBaoChe,
                DonViTinh,
                DonGiaBan
            FROM thuoc
            WHERE TrangThai = 1
              AND (
                    TenThuoc    LIKE '%$kwEsc%' 
                 OR TenHoatChat LIKE '%$kwEsc%'
              )
            ORDER BY TenThuoc ASC
            LIMIT 10
        ";

        $rows = mysqli_query($this->con, $str);
        $mang = [];
        while ($row = mysqli_fetch_assoc($rows)) {
            $mang[] = $row;
        }
        return $mang; // controller sẽ json_encode
    }
}
?>
