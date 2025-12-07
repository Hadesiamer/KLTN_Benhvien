<?php
class MBacsi extends DB
{
    // Thêm lịch làm việc
    public function themLichLamViec($maNV, $ngayLamViec, $caLamViec)
    {
        $str = "INSERT INTO LichLamViec (MaNV, NgayLamViec, CaLamViec, TrangThai) 
                VALUES ('$maNV', '$ngayLamViec', '$caLamViec', 'Đang làm')";
        return mysqli_query($this->con, $str);
    }

    // Kiểm tra lịch làm việc đã tồn tại
    public function kiemTraLichDaTonTai($maNV, $ngayLamViec, $caLamViec)
    {
        $str = "SELECT COUNT(*) as count 
                FROM LichLamViec 
                WHERE MaNV = '$maNV' AND NgayLamViec = '$ngayLamViec' AND CaLamViec = '$caLamViec'";
        $result = mysqli_query($this->con, $str);
        $row = mysqli_fetch_assoc($result);
        return $row['count'] > 0;
    }

    // Kiểm tra số lượng bác sĩ đã đăng ký trong ca làm việc
    public function kiemTraSoLuongCaLamViec($ngayLamViec, $caLamViec)
    {
        $str = "SELECT COUNT(*) as count 
                FROM LichLamViec 
                WHERE NgayLamViec = '$ngayLamViec' AND CaLamViec = '$caLamViec'";
        $result = mysqli_query($this->con, $str);
        $row = mysqli_fetch_assoc($result);
        return $row['count'];
    }

    // Xem lịch làm việc của bác sĩ
    public function XemLichLamViec($maNV)
    {
        // NhatCuong: chuẩn hóa, lấy luôn MaLLV, MaNV để dùng cho chức năng nghỉ phép
        $maNV = intval($maNV);

        $str = "SELECT MaLLV, MaNV, NgayLamViec, CaLamViec 
                FROM LichLamViec 
                WHERE MaNV = '$maNV' AND TrangThai = 'Đang làm'";
        $result = mysqli_query($this->con, $str);
        $mang = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $mang[] = $row;
            }
        }
        return json_encode($mang);
    }

    // NhatCuong: lấy danh sách nghỉ phép của bác sĩ
    public function GetLichNghiPhepByMaNV($maNV)
    {
        $maNV = intval($maNV);

        $str = "SELECT MaLLV, TrangThai 
                FROM lichnghiphep
                WHERE MaNV = '$maNV'";
        $result = mysqli_query($this->con, $str);
        $mang = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $mang[] = $row;
            }
        }
        return json_encode($mang);
    }

    // NhatCuong: xử lý nghiệp vụ tạo yêu cầu nghỉ phép cho 1 ca làm việc
    public function TaoYeuCauNghiPhep($maNV, $ngayNghi, $caLamViec, $lyDo)
    {
        $maNV      = intval($maNV);
        $ngayNghi  = trim($ngayNghi);
        $caLamViec = trim($caLamViec);
        $lyDo      = trim($lyDo);

        // 1. Kiểm tra thiếu thông tin
        if (!$maNV || $ngayNghi === '' || $caLamViec === '' || $lyDo === '') {
            return [
                'status'  => 'error',
                'message' => 'Thiếu thông tin. Vui lòng thử lại!'
            ];
        }

        // 1b. Chỉ cho xin nghỉ các ca ở tương lai (ít nhất là ngày mai)
        //     - ngày nghỉ phải > hôm nay (so sánh theo định dạng Y-m-d)
        $todayObj     = new DateTime('today'); // ngày hiện tại
        $ngayNghiObj  = DateTime::createFromFormat('Y-m-d', $ngayNghi);

        if (!$ngayNghiObj || $ngayNghiObj <= $todayObj) {
            return [
                'status'  => 'error',
                'message' => 'Chỉ được xin nghỉ cho ca ở tương lai (trước ít nhất 1 ngày).'
            ];
        }

        // 2. Tìm MaLLV tương ứng (ca làm việc đang làm)
        $sqlFind = "SELECT MaLLV 
                    FROM lichlamviec 
                    WHERE MaNV = ? AND NgayLamViec = ? AND CaLamViec = ? AND TrangThai = 'Đang làm'
                    LIMIT 1";
        $stmtFind = $this->con->prepare($sqlFind);
        if (!$stmtFind) {
            return [
                'status'  => 'error',
                'message' => 'Lỗi hệ thống. Không thể kiểm tra ca làm việc.'
            ];
        }
        $stmtFind->bind_param("iss", $maNV, $ngayNghi, $caLamViec);
        $stmtFind->execute();
        $resFind = $stmtFind->get_result();
        $rowFind = $resFind->fetch_assoc();
        $stmtFind->close();

        $maLLV = $rowFind['MaLLV'] ?? null;
        if (!$maLLV) {
            return [
                'status'  => 'error',
                'message' => 'Không tìm thấy ca làm việc tương ứng!'
            ];
        }

        // 3. Kiểm tra trùng yêu cầu nghỉ cho cùng MaLLV
        $sqlCheck = "SELECT 1 FROM lichnghiphep WHERE MaNV = ? AND MaLLV = ? LIMIT 1";
        $stmtCheck = $this->con->prepare($sqlCheck);
        if (!$stmtCheck) {
            return [
                'status'  => 'error',
                'message' => 'Lỗi hệ thống. Không thể kiểm tra lịch nghỉ phép.'
            ];
        }
        $stmtCheck->bind_param("ii", $maNV, $maLLV);
        $stmtCheck->execute();
        $resCheck = $stmtCheck->get_result();
        $hasRow   = $resCheck->num_rows > 0;
        $stmtCheck->close();

        if ($hasRow) {
            return [
                'status'  => 'warning',
                'message' => 'Bạn đã gửi yêu cầu nghỉ cho ca này rồi!'
            ];
        }

        // 4. Insert yêu cầu nghỉ phép (trạng thái Chờ duyệt)
        $trangThai = "Chờ duyệt";
        $sqlInsert = "INSERT INTO lichnghiphep (MaNV, MaLLV, LyDo, TrangThai)
                      VALUES (?, ?, ?, ?)";
        $stmtInsert = $this->con->prepare($sqlInsert);
        if (!$stmtInsert) {
            return [
                'status'  => 'error',
                'message' => 'Lỗi hệ thống. Không thể lưu yêu cầu nghỉ phép.'
            ];
        }
        $stmtInsert->bind_param("iiss", $maNV, $maLLV, $lyDo, $trangThai);
        $ok = $stmtInsert->execute();
        $stmtInsert->close();

        if ($ok) {
            return [
                'status'  => 'success',
                'message' => 'Gửi yêu cầu nghỉ phép thành công!'
            ];
        }

        return [
            'status'  => 'error',
            'message' => 'Gửi yêu cầu nghỉ phép thất bại. Vui lòng thử lại.'
        ];
    }

    // ===========================
    // NhatCuong: DS KHÁM THEO BÁC SĨ + NGÀY + ĐÃ THANH TOÁN
    // ===========================

    // Tất cả (không phân ca)
    public function GetDanhSachKhamTheoBSAll($maBS, $ngayKham)
    {
        $maBS = intval($maBS);
        $ngayKham = date('Y-m-d', strtotime($ngayKham)); // Chuẩn hóa ngày

        $str = "
            SELECT 
                bn.MaBN,
                lk.MaLK,
                bn.HovaTen,
                bn.NgaySinh,
                bn.SoDT,
                lk.GioKham,
                lk.TrieuChung,
                lk.loaidichvu AS LoaiDichVu
            FROM 
                lichkham lk
            JOIN 
                benhnhan bn ON lk.MaBN = bn.MaBN
            WHERE 
                lk.MaBS = $maBS
                AND lk.NgayKham = '$ngayKham'
                AND lk.TrangThaiThanhToan = 'Da thanh toan'
            ORDER BY 
                lk.GioKham ASC
        ";

        $rows = mysqli_query($this->con, $str);
        $mang = array();
        if ($rows) {
            while ($row = mysqli_fetch_array($rows)) {
                $mang[] = $row;
            }
        }
        return json_encode($mang);
    }

    // Ca sáng
    public function GetDanhSachKhamTheoBSSang($maBS, $ngayKham)
    {
        $maBS = intval($maBS);
        $ngayKham = date('Y-m-d', strtotime($ngayKham));

        $str = "
            SELECT 
                bn.MaBN,
                lk.MaLK,
                bn.HovaTen,
                bn.NgaySinh,
                bn.SoDT,
                lk.GioKham,
                lk.TrieuChung,
                lk.loaidichvu AS LoaiDichVu
            FROM 
                lichkham lk
            JOIN 
                benhnhan bn ON lk.MaBN = bn.MaBN
            WHERE 
                lk.MaBS = $maBS
                AND lk.NgayKham = '$ngayKham'
                AND lk.TrangThaiThanhToan = 'Da thanh toan'
                AND HOUR(lk.GioKham) < 12
            ORDER BY 
                lk.GioKham ASC
        ";

        $rows = mysqli_query($this->con, $str);
        $mang = array();
        if ($rows) {
            while ($row = mysqli_fetch_array($rows)) {
                $mang[] = $row;
            }
        }
        return json_encode($mang);
    }

    // Ca chiều
    public function GetDanhSachKhamTheoBSChieu($maBS, $ngayKham)
    {
        $maBS = intval($maBS);
        $ngayKham = date('Y-m-d', strtotime($ngayKham));

        $str = "
            SELECT 
                bn.MaBN,
                lk.MaLK,
                bn.HovaTen,
                bn.NgaySinh,
                bn.SoDT,
                lk.GioKham,
                lk.TrieuChung,
                lk.loaidichvu AS LoaiDichVu
            FROM 
                lichkham lk
            JOIN 
                benhnhan bn ON lk.MaBN = bn.MaBN
            WHERE 
                lk.MaBS = $maBS
                AND lk.NgayKham = '$ngayKham'
                AND lk.TrangThaiThanhToan = 'Da thanh toan'
                AND HOUR(lk.GioKham) >= 12
            ORDER BY 
                lk.GioKham ASC
        ";

        $rows = mysqli_query($this->con, $str);
        $mang = array();
        if ($rows) {
            while ($row = mysqli_fetch_array($rows)) {
                $mang[] = $row;
            }
        }
        return json_encode($mang);
    }

    //NhatCuong; Usecase 1/3: Xem lịch sử khám bệnh, phiếu khám
    public function GetPhieuKhamBenhNhan($maBN)
    {
        $str = "SELECT 
                pk2.NgayTao,
                nv.HoVaTen AS BacSi,
                pk2.TrieuChung,
                pk2.ChuanDoan,
                pk2.KetQua,
                pk2.LoiDan,
                pk2.NgayTaiKham
            FROM 
                PhieuKham pk2
            JOIN 
                NhanVien nv ON pk2.MaBS = nv.MaNV
            WHERE 
                pk2.MaBN = '$maBN'
            ORDER BY 
                pk2.NgayTao";
        $result = mysqli_query($this->con, $str);
        $mang = array();
        while ($row = mysqli_fetch_array($result)) {
            $mang[] = $row;
        }
        return json_encode($mang);
    }

    //NhatCuong; Usecase 2/3: Xem lịch sử khám bệnh, thông tin bệnh nhân
    public function GetThongTinBenhNhan($tuKhoa)
    {
        // NhatCuong: cho phép tìm theo MaBN, BHYT hoặc SoDT
        // Lọc chuỗi đơn giản tránh lỗi câu lệnh SQL
        $tuKhoa = mysqli_real_escape_string($this->con, $tuKhoa);

        $str = "SELECT MaBN, HovaTen, NgaySinh, GioiTinh, BHYT, DiaChi, SoDT, Email
                FROM benhnhan 
                WHERE MaBN = '$tuKhoa'
                OR BHYT = '$tuKhoa'
                OR SoDT = '$tuKhoa'";

        $result = mysqli_query($this->con, $str);
        $mang = array();
        while ($row = mysqli_fetch_array($result)) {
            $mang[] = $row;
        }
        return json_encode($mang);
    }

    public function GetThongTinBenhNhan1($maBN, $malk)
    {
        // NhatCuong: thêm lk.TrieuChung để đổ sẵn vào form Lập phiếu khám
        $str = "SELECT 
                    bn.MaBN, 
                    bn.HovaTen, 
                    bn.NgaySinh, 
                    bn.GioiTinh, 
                    bn.BHYT, 
                    bn.DiaChi, 
                    bn.SoDT,
                    lk.MaLK,
                    lk.TrieuChung
                FROM benhnhan bn JOIN lichkham lk
                  ON bn.MaBN = lk.MaBN
                WHERE bn.MaBN = '$maBN' AND lk.MaLK ='$malk'";
        $result = mysqli_query($this->con, $str);
        $mang = array();
        while ($row = mysqli_fetch_array($result)) {
            $mang[] = $row;
        }
        return json_encode($mang);
    }

    //NhatCuong; Usecase 2/3: Xem lịch sử khám bệnh //count (phieukham)
    public function GetSoLanKhamBenh($maBN)
    {
        $str = "SELECT COUNT(*) as SoLanKham
            FROM PhieuKham
            WHERE MaBN = '$maBN'";
        $result = mysqli_query($this->con, $str);
        $row = mysqli_fetch_assoc($result);
        return $row['SoLanKham'];
    }

    //NhatCuong: Lapphieukham 1/6 (chưa dùng trong Lapphieukham hiện tại)
    public function getBenhNhanInfo($maLK)
    {
        $query = "SELECT bn.MaBN, bn.HovaTen, bn.NgaySinh, bn.GioiTinh, bn.BHYT, bn.DiaChi, bn.SoDT
                  FROM benhnhan bn
                  JOIN lichkham lk ON bn.MaBN = lk.MaBN
                  WHERE lk.MaLK = ?";
        $stmt = $this->con->prepare($query);
        $stmt->bind_param("i", $maLK);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getBacSiInfo($maNV)
    {
        $str = "SELECT * FROM nhanvien WHERE MaNV = '$maNV'";
        $result = mysqli_query($this->con, $str);
        $mang = array();
        while ($row = mysqli_fetch_array($result)) {
            $mang[] = $row;
        }
        return json_encode($mang);
    }

    // Lấy danh mục thuốc theo bảng thuoc mới
    public function getThuocList()
    {
        $query = "SELECT 
                    MaThuoc,
                    TenThuoc,
                    TenHoatChat,
                    HamLuong,
                    DangBaoChe,
                    DonViTinh,
                    DuongDung,
                    NhomThuoc,
                    DonGiaBan,
                    TrangThai
                  FROM thuoc
                  WHERE TrangThai = 1
                  ORDER BY TenThuoc ASC";

        $result = $this->con->query($query);
        $thuocList = array();
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $thuocList[] = $row;
            }
        }
        return $thuocList;
    }

    // ===========================
    // PHIẾU KHÁM + ĐƠN THUỐC
    // ===========================

    // AddPK: thêm phiếu khám, gán với MaDon tương ứng với MaLK + MaBN + MaBS (nếu có)
    public function AddPK($ntao, $tchung, $kq, $cdoan, $ldan, $ngaytaikham, $malk, $mabs, $mabn)
    {
        // Ép kiểu cho khóa ngoại
        $malk = intval($malk);
        $mabs = intval($mabs);
        $mabn = intval($mabn);

        // Tìm MaDon gắn với cuộc khám này (nếu đã tạo đơn thuốc)
        $madon_moi = null;
        $sqlDon = "SELECT MaDon 
                   FROM don_thuoc 
                   WHERE MaLK = '$malk' AND MaBN = '$mabn' AND MaBS = '$mabs'
                   ORDER BY MaDon DESC
                   LIMIT 1";
        $resultDon = mysqli_query($this->con, $sqlDon);
        if ($resultDon && $rowDon = mysqli_fetch_assoc($resultDon)) {
            $madon_moi = $rowDon['MaDon'];
        }

        // Chuẩn bị giá trị cho cột MaDon (cho phép NULL nếu không có đơn thuốc)
        if ($madon_moi !== null) {
            $maDonValue = "'" . intval($madon_moi) . "'";
        } else {
            $maDonValue = "NULL";
        }

        // CHÚ Ý: cột trong phieukham đã đổi từ MaDT -> MaDon theo cập nhật của bạn
        $str = "INSERT INTO phieukham 
                (`MaPK`, `NgayTao`, `TrieuChung`, `KetQua`, `ChuanDoan`, `LoiDan`, `NgayTaikham`, 
                 `MaXN`, `MaLK`, `MaHD`, `MaDon`, `MaBN`, `MaBS`)
        VALUES (NULL, '$ntao', '$tchung', '$kq', '$cdoan', '$ldan', '$ngaytaikham',
                NULL, '$malk', NULL, $maDonValue, '$mabn', '$mabs')";
        $result = mysqli_query($this->con, $str);
        return $result;
    }

    // Hàm generic: tạo 1 đơn thuốc (dùng cho nơi khác, nếu có)
    public function createDonThuoc($data)
    {
        // Map: NgayTao -> NgayKe, GhiChuChung = NULL, LoaiDon = KE_DON, MaLK/MaPK tạm NULL
        $query = "INSERT INTO don_thuoc 
                    (MaLK, MaPK, MaBN, MaBS, MaNhanVien, LoaiDon, NgayKe, GhiChuChung)
                  VALUES (NULL, NULL, ?, ?, NULL, 'KE_DON', ?, NULL)";
        $stmt = $this->con->prepare($query);
        if (!$stmt) return false;

        $maBN   = isset($data['MaBN']) ? (int)$data['MaBN'] : 0;
        $maBS   = isset($data['MaBS']) ? (int)$data['MaBS'] : 0;
        $ngayKe = isset($data['NgayTao']) ? $data['NgayTao'] : date('Y-m-d H:i:s');

        $stmt->bind_param("iis", $maBN, $maBS, $ngayKe);
        if ($stmt->execute()) {
            return $this->con->insert_id; // MaDon
        }
        return false;
    }

    // Hàm generic: tạo chi tiết đơn thuốc cho MaDon (dùng cho nơi khác, nếu có)
    public function createChiTietDonThuoc($maDon, $thuocData)
    {
        $query = "INSERT INTO ct_don_thuoc (MaDon, MaThuoc, SoLuong, LieuDung, GhiChu) 
                  VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->con->prepare($query);
        if (!$stmt) return false;

        foreach ($thuocData as $thuoc) {
            $maThuoc = (int)$thuoc['MaThuoc'];
            $soLuong = (int)$thuoc['SoLuong'];
            $lieuDung = $thuoc['LieuDung'];
            $ghiChu   = isset($thuoc['CachDung']) ? $thuoc['CachDung'] : '';

            $stmt->bind_param("iiiss", $maDon, $maThuoc, $soLuong, $lieuDung, $ghiChu);
            $stmt->execute();
        }
        return true;
    }

    // Lấy phiếu khám + thuốc
    public function GetPhieuKham($maBN)
    {
        // Map sang bảng mới: don_thuoc, ct_don_thuoc, MaDon
        $str = "SELECT
                    pk2.NgayTao,
                    nv.HoVaTen AS BacSi,
                    pk2.TrieuChung,
                    pk2.ChuanDoan,
                    pk2.KetQua,
                    pk2.LoiDan,
                    pk2.NgayTaiKham,
                    xn.NgayXetNghiem,
                    xn.KetQua as KetQuaXN,
                    xn.LoaiXN,
                    t.TenThuoc,
                    dt.SoLuong,
                    dt.LieuDung,
                    dt.GhiChu AS CachDung
                FROM 
                    PhieuKham pk2
                JOIN 
                    NhanVien nv ON pk2.MaBS = nv.MaNV
                LEFT JOIN 
                    XetNghiem xn ON pk2.MaXN = xn.MAXN
                LEFT JOIN
                    don_thuoc d ON pk2.MaDon = d.MaDon
                LEFT JOIN 
                    ct_don_thuoc dt ON d.MaDon = dt.MaDon
                LEFT JOIN 
                    thuoc AS t ON dt.MaThuoc = t.MaThuoc
                WHERE 
                    pk2.MaBN = '$maBN'
                ORDER BY 
                    pk2.NgayTao";
        $result = mysqli_query($this->con, $str);
        $mang = array();
        while ($row = mysqli_fetch_array($result)) {
            $mang[] = $row;
        }
        return json_encode($mang);
    }

    // TaoDT: dùng trong Lapphieukham (bác sĩ lập phiếu khám + đơn kê)
    // Map sang bảng don_thuoc mới
    public function TaoDT($date, $mota, $mabs, $mabn, $malk)
    {
        // Loại đơn: KE_DON, gán MaLK, MaBN, MaBS.
        $date  = !empty($date) ? $date : date('Y-m-d H:i:s');
        $mabn  = intval($mabn);
        $mabs  = intval($mabs);
        $malk  = intval($malk);

        // Không dùng $mota (chẩn đoán) để lưu vào GhiChuChung nữa, tránh bug logic
        $ghiChuChung = '';

        $str = "INSERT INTO don_thuoc 
                    (MaLK, MaPK, MaBN, MaBS, MaNhanVien, LoaiDon, NgayKe, GhiChuChung)
                VALUES 
                    ('$malk', NULL, '$mabn', '$mabs', NULL, 'KE_DON', '$date', '$ghiChuChung')";
        return mysqli_query($this->con, $str);
    }

    // TaoCTDT: thêm chi tiết đơn thuốc cho MaDon mới nhất
    public function TaoCTDT($mathuoc, $soluong, $lieudung, $cachdung)
    {
        // Lấy MaDon mới nhất
        $str = "SELECT MaDon FROM `don_thuoc` ORDER BY MaDon DESC LIMIT 1";
        $result = mysqli_query($this->con, $str);
        
        if ($result && $row = mysqli_fetch_assoc($result)) {
            $madon_moi = $row['MaDon'];
        } else {
            return false;
        }
        
        // Thực hiện INSERT vào ct_don_thuoc
        $mathuoc = intval($mathuoc);
        $soluong = intval($soluong);

        $str2 = "INSERT INTO ct_don_thuoc (MaDon, MaThuoc, SoLuong, LieuDung, GhiChu) 
                 VALUES ('$madon_moi', '$mathuoc', '$soluong', '$lieudung', '$cachdung')";
        return mysqli_query($this->con, $str2);
    }

    // Lấy thông tin 1 bác sĩ theo MaNV (bác sĩ đang đăng nhập)
    public function get1BS($maNV)
    {
        // Ép int cho an toàn
        $maNV = intval($maNV);

        $str = "SELECT 
                    nv.MaNV,
                    nv.HovaTen,
                    nv.NgaySinh,
                    nv.SoDT,
                    nv.ChucVu,
                    nv.GioiTinh,
                    nv.TrangThaiLamViec,
                    nv.EmailNV,
                    nv.HinhAnh,
                    ck.TenKhoa
                FROM nhanvien nv
                JOIN bacsi bs ON nv.MaNV = bs.MaNV
                JOIN chuyenkhoa ck ON bs.MaKhoa = ck.MaKhoa
                WHERE nv.MaNV = $maNV";

        $rows = mysqli_query($this->con, $str);

        $mang = array();
        if ($rows) {
            while ($row = mysqli_fetch_assoc($rows)) {
                $mang[] = $row;
            }
        }
        return json_encode($mang);
    }

    // Đổi mật khẩu cho BÁC SĨ (dựa trên bảng taikhoan)
    public function DoiMatKhau($maNV, $oldPass, $newPass)
    {
        // Ép int cho an toàn
        $maNV = intval($maNV);

        // 1. Lấy mật khẩu hiện tại từ bảng taikhoan thông qua nhanvien.ID
        $sql = "SELECT tk.password 
                FROM taikhoan tk
                INNER JOIN nhanvien nv ON tk.ID = nv.ID
                WHERE nv.MaNV = ?";

        $stmt = $this->con->prepare($sql);
        if (!$stmt) {
            return "Lỗi hệ thống (không chuẩn bị được truy vấn).";
        }

        $stmt->bind_param("i", $maNV);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows === 0) {
            return "Không tìm thấy tài khoản bác sĩ!";
        }

        $row = $res->fetch_assoc();
        $currentHash = $row["password"];

        // 2. Xác định mật khẩu đang lưu dạng gì: md5 hay plain text
        $isMd5 = (strlen($currentHash) === 32 && ctype_xdigit($currentHash));

        // 3. Kiểm tra mật khẩu hiện tại
        $oldPassOk = false;
        if ($isMd5) {
            if (md5($oldPass) === $currentHash) {
                $oldPassOk = true;
            }
        } else {
            if ($oldPass === $currentHash) {
                $oldPassOk = true;
            }
        }

        if (!$oldPassOk) {
            return "Mật khẩu hiện tại không đúng!";
        }

        // 4. Không cho trùng mật khẩu cũ
        if ($isMd5 && md5($newPass) === $currentHash) {
            return "Mật khẩu mới không được trùng với mật khẩu hiện tại!";
        }
        if (!$isMd5 && $newPass === $currentHash) {
            return "Mật khẩu mới không được trùng với mật khẩu hiện tại!";
        }

        // 5. Tính giá trị sẽ lưu xuống DB
        $newToSave = $isMd5 ? md5($newPass) : $newPass;

        // 6. Cập nhật mật khẩu mới vào bảng taikhoan
        $sql2 = "UPDATE taikhoan tk
                 INNER JOIN nhanvien nv ON tk.ID = nv.ID
                 SET tk.password = ?
                 WHERE nv.MaNV = ?";

        $stmt2 = $this->con->prepare($sql2);
        if (!$stmt2) {
            return "Lỗi hệ thống (không chuẩn bị được câu lệnh cập nhật).";
        }

        $stmt2->bind_param("si", $newToSave, $maNV);

        if ($stmt2->execute()) {
            return "Đổi mật khẩu thành công!";
        }

        return "Đổi mật khẩu thất bại!";
    }

    public function checkPhieuKhamTonTai($maLK)
    {
        $maLK = intval($maLK);
        $sql = "SELECT COUNT(*) AS total FROM phieukham WHERE MaLK = $maLK";
        $res = mysqli_query($this->con, $sql);
        $row = mysqli_fetch_assoc($res);
        return $row['total'] > 0;
    }

    // Xóa tất cả phiếu khám có cùng MaLK
    public function deletePhieuKhamByMaLK($maLK)
    {
        $maLK = intval($maLK);
        $sql = "DELETE FROM phieukham WHERE MaLK = $maLK";
        return mysqli_query($this->con, $sql);
    }

    // ==========================================
    // NhatCuong: Thống kê bác sĩ (ca làm việc / lịch khám / phiếu khám)
    // ==========================================
    public function getThongKeBacSi($maNV, $filterType = 'today')
    {
        $maNV = intval($maNV);
        $allowed = ['today', '7days', 'month', 'all'];
        if (!in_array($filterType, $allowed)) {
            $filterType = 'today';
        }

        $today = new DateTime('today');
        $start = null;
        $end   = null;
        $label = '';

        switch ($filterType) {
            case '7days':
                // 7 ngày gần nhất (bao gồm hôm nay)
                $end = clone $today;
                $start = (clone $today)->modify('-6 days');
                $label = '7 ngày gần nhất';
                break;

            case 'month':
                // Tháng hiện tại
                $start = new DateTime($today->format('Y-m-01'));
                $end = (clone $start)->modify('last day of this month');
                $label = 'Tháng này';
                break;

            case 'all':
                // Tất cả – không giới hạn thời gian
                $start = null;
                $end   = null;
                $label = 'Tất cả';
                break;

            case 'today':
            default:
                $start = clone $today;
                $end   = clone $today;
                $label = 'Hôm nay';
                break;
        }

        $startStr = $start ? $start->format('Y-m-d') : null;
        $endStr   = $end   ? $end->format('Y-m-d')   : null;

        // -----------------------------
        // 1. Đếm số CA LÀM VIỆC (lichlamviec)
        // -----------------------------
        $soCaLamViec = 0;

        if ($startStr && $endStr) {
            $sql = "SELECT COUNT(*) AS total 
                    FROM lichlamviec 
                    WHERE MaNV = ? AND NgayLamViec BETWEEN ? AND ?";
            $stmt = $this->con->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("iss", $maNV, $startStr, $endStr);
                $stmt->execute();
                $res = $stmt->get_result();
                if ($row = $res->fetch_assoc()) {
                    $soCaLamViec = (int)$row['total'];
                }
                $stmt->close();
            }
        } else {
            $sql = "SELECT COUNT(*) AS total 
                    FROM lichlamviec 
                    WHERE MaNV = ?";
            $stmt = $this->con->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("i", $maNV);
                $stmt->execute();
                $res = $stmt->get_result();
                if ($row = $res->fetch_assoc()) {
                    $soCaLamViec = (int)$row['total'];
                }
                $stmt->close();
            }
        }

        // -----------------------------
        // 2. Đếm số LỊCH KHÁM (lichkham)
        // -----------------------------
        $soLichKham = 0;

        if ($startStr && $endStr) {
            $sql = "SELECT COUNT(*) AS total 
                    FROM lichkham 
                    WHERE MaBS = ? AND NgayKham BETWEEN ? AND ?";
            $stmt = $this->con->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("iss", $maNV, $startStr, $endStr);
                $stmt->execute();
                $res = $stmt->get_result();
                if ($row = $res->fetch_assoc()) {
                    $soLichKham = (int)$row['total'];
                }
                $stmt->close();
            }
        } else {
            $sql = "SELECT COUNT(*) AS total 
                    FROM lichkham 
                    WHERE MaBS = ?";
            $stmt = $this->con->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("i", $maNV);
                $stmt->execute();
                $res = $stmt->get_result();
                if ($row = $res->fetch_assoc()) {
                    $soLichKham = (int)$row['total'];
                }
                $stmt->close();
            }
        }

        // -----------------------------
        // 3. Đếm số PHIẾU KHÁM đã lập (phieukham)
        // -----------------------------
        $soPhieuKham = 0;

        if ($startStr && $endStr) {
            $sql = "SELECT COUNT(*) AS total 
                    FROM phieukham 
                    WHERE MaBS = ? AND DATE(NgayTao) BETWEEN ? AND ?";
            $stmt = $this->con->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("iss", $maNV, $startStr, $endStr);
                $stmt->execute();
                $res = $stmt->get_result();
                if ($row = $res->fetch_assoc()) {
                    $soPhieuKham = (int)$row['total'];
                }
                $stmt->close();
            }
        } else {
            $sql = "SELECT COUNT(*) AS total 
                    FROM phieukham 
                    WHERE MaBS = ?";
            $stmt = $this->con->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("i", $maNV);
                $stmt->execute();
                $res = $stmt->get_result();
                if ($row = $res->fetch_assoc()) {
                    $soPhieuKham = (int)$row['total'];
                }
                $stmt->close();
            }
        }

        return [
            'filter'        => $filterType,
            'label'         => $label,
            'start_date'    => $startStr,
            'end_date'      => $endStr,
            'so_ca_lam_viec'=> $soCaLamViec,
            'so_lich_kham'  => $soLichKham,
            'so_phieu_kham' => $soPhieuKham
        ];
    }

    // Cập nhật thông tin cơ bản cho bác sĩ
public function updateThongTinBacSi($maNV, $ngaySinh, $gioiTinh, $email)
{
    $sql = "UPDATE nhanvien
            SET NgaySinh = ?, GioiTinh = ?, EmailNV = ?
            WHERE MaNV = ?";

    $stmt = $this->con->prepare($sql);
    if (!$stmt) return false;

    $stmt->bind_param("sssi", $ngaySinh, $gioiTinh, $email, $maNV);
    return $stmt->execute();
}

}
