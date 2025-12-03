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

    // Lấy thông tin chung của 1 đơn thuốc (header đơn)
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

    // Lấy danh sách thuốc trong 1 đơn
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

    // Tạo hoặc cập nhật hóa đơn thành "Đã thanh toán"
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

    // Hủy đơn thuốc: hóa đơn gắn với đơn chuyển sang 'Huy'
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
}
?>
