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
        $str = "SELECT NgayLamViec, CaLamViec 
                FROM LichLamViec 
                WHERE MaNV = '$maNV' AND TrangThai = 'Đang làm'";
        $result = mysqli_query($this->con, $str);
        $mang = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $mang[] = $row;
        }
        return json_encode($mang);
    }


    //NhatCuong; Usecase: Xem danh sách khám bệnh1/3; Hàm truy vấn for input-radio:Sáng 
    public function GetDanhSachKhamSang()
    {
        $str = 'SELECT 
                    bn.MaBN,
                    lk.MaLK, 
                    bn.HovaTen, 
                    bn.NgaySinh, 
                    bn.SoDT,
                    lk.GioKham
                FROM 
                    lichkham lk
                JOIN 
                    benhnhan bn ON lk.MaBN = bn.MaBN
                WHERE 
                    DATE(lk.NgayKham) = CURDATE()
                    AND HOUR(lk.Giokham) < 12
                ORDER BY 
                    lk.GioKham ASC';
        $rows = mysqli_query($this->con, $str);
        $mang = array();
        while ($row = mysqli_fetch_array($rows)) {
            $mang[] = $row;
        }
        return json_encode($mang);
    }

    //NhatCuong; Usecase: Xem danh sách khám bệnh2/3; Hàm truy vấn for input-radio:Chiều
    public function GetDanhSachKhamChieu()
    {
        $str = 'SELECT 
                    bn.MaBN,
                    lk.MaLK, 
                    bn.HovaTen, 
                    bn.NgaySinh, 
                    bn.SoDT,
                    lk.GioKham
                FROM 
                    lichkham lk
                JOIN 
                    benhnhan bn ON lk.MaBN = bn.MaBN
                WHERE 
                    DATE(lk.NgayKham) = CURDATE()
                    AND HOUR(lk.GioKham) >= 12
                ORDER BY 
                    lk.GioKham ASC';
        $rows = mysqli_query($this->con, $str);
        $mang = array();
        while ($row = mysqli_fetch_array($rows)) {
            $mang[] = $row;
        }
        return json_encode($mang);
    }

    //NhatCuong; Usecase: Xem danh sách khám bệnh3/3; Hàm truy vấn for input-radio:Tất cả
    public function GetDanhSachKhamAll()
    {
        $str = 'SELECT 
                    bn.MaBN,
                    lk.MaLK, 
                    bn.HovaTen, 
                    bn.NgaySinh, 
                    bn.SoDT,
                    lk.GioKham
                FROM 
                    lichkham lk
                JOIN 
                    benhnhan bn ON lk.MaBN = bn.MaBN
                WHERE 
                    DATE(lk.NgayKham) = CURDATE()
                ORDER BY 
                    lk.GioKham ASC';
        $rows = mysqli_query($this->con, $str);
        $mang = array();
        while ($row = mysqli_fetch_array($rows)) {
            $mang[] = $row;
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
    public function GetThongTinBenhNhan($maBN)
    {

        $str = "SELECT MaBN, HovaTen, NgaySinh, GioiTinh, BHYT, DiaChi, SoDT, Email
            FROM benhnhan WHERE MaBN = '$maBN' or BHYT = '$maBN'";

        $result = mysqli_query($this->con, $str);
        $mang = array();
        while ($row = mysqli_fetch_array($result)) {
            $mang[] = $row;
        }
        return json_encode($mang);
    }
    public function GetThongTinBenhNhan1($maBN, $malk)
    {
        $str = "SELECT bn.MaBN, bn.HovaTen, bn.NgaySinh, bn.GioiTinh, bn.BHYT, bn.DiaChi, bn.SoDT,lk.MaLK
            FROM benhnhan bn JOIN lichkham lk
            on bn.MaBN=lk.MaBN
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

    //NhatCuong: Lapphieukham 1/6
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

    public function getThuocList()
    {
        $query = "SELECT MaThuoc, TenThuoc FROM thuoc";
        $result = $this->con->query($query);
        $thuocList = array();
        while ($row = $result->fetch_assoc()) {
            $thuocList[] = $row;
        }
        return $thuocList;
    }

    public function AddPK($ntao, $tchung, $kq, $cdoan, $ldan, $ngaytaikham, $malk, $mabs, $mabn)
    {
        $str = "SELECT MaDT FROM `donthuoc` ORDER BY MaDT DESC LIMIT 1";
        $result = mysqli_query($this->con, $str);
        
        // Kiểm tra nếu có dữ liệu trả về
        if ($result && $row = mysqli_fetch_assoc($result)) {
            $madt_moi = $row['MaDT']; // Lấy giá trị MaDT
        } else {
            // Xử lý khi không tìm thấy MaDT
            return false; // Hoặc throw lỗi nếu cần
        }
    
        $str = "INSERT INTO phieukham (`MaPK`, `NgayTao`, `TrieuChung`, `KetQua`, `ChuanDoan`, `LoiDan`, `NgayTaikham`, `MaXN`, `MaLK`, `MaHD`, `MaDT`, `MaBN`, `MaBS`)
        VALUES (NULL, '$ntao', '$tchung', '$kq', '$cdoan', '$ldan', '$ngaytaikham', NULL, '$malk', NULL, '$madt_moi', '$mabn', '$mabs');";
        $result = mysqli_query($this->con, $str);
        return $result;
    }

    public function createDonThuoc($data)
    {
        $query = "INSERT INTO donthuoc (NgayTao, MaBS, MaBN, TrangThai) VALUES (?, ?, ?, 'Pending')";
        $stmt = $this->con->prepare($query);
        $stmt->bind_param("sii", $data['NgayTao'], $data['MaBS'], $data['MaBN']);
        if ($stmt->execute()) {
            return $this->con->insert_id;
        }
        return false;
    }

    public function createChiTietDonThuoc($maDT, $thuocData)
    {
        $query = "INSERT INTO chitietdonthuoc (MaDT, MaThuoc, SoLuong, LieuDung, CachDung) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->con->prepare($query);
        foreach ($thuocData as $thuoc) {
            $stmt->bind_param("iiiss", $maDT, $thuoc['MaThuoc'], $thuoc['SoLuong'], $thuoc['LieuDung'], $thuoc['CachDung']);
            $stmt->execute();
        }
        return true;
    }

    public function GetPhieuKham($maBN)
    {
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
                dt.CachDung
            FROM 
                PhieuKham pk2
            JOIN 
                NhanVien nv ON pk2.MaBS = nv.MaNV
            left JOIN 
                XetNghiem xn ON pk2.MaXN = xn.MAXN
            left JOIN
                donthuoc d on d.MaBN = pk2.MaBN
            JOIN 
                chitietdonthuoc dt ON d.MaDT = dt.MaDT
            JOIN 
                thuoc as t ON dt.MaThuoc = t.MaThuoc
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


    public function TaoDT($date, $mota, $mabs, $mabn) {
        $str = "INSERT INTO donthuoc (NgayTao, MoTa, MaBS, MaBN, TrangThai) VALUES (NOW(),'$mota' , '$mabs', '$mabn', 'Pending')";
        return mysqli_query($this->con, $str);
    }
    public function TaoCTDT($mathuoc, $soluong, $lieudung, $cachdung)
{
    // Truy vấn để lấy MaDT mới nhất
    $str = "SELECT MaDT FROM `donthuoc` ORDER BY MaDT DESC LIMIT 1";
    $result = mysqli_query($this->con, $str);
    
    // Kiểm tra nếu có dữ liệu trả về
    if ($result && $row = mysqli_fetch_assoc($result)) {
        $madt_moi = $row['MaDT']; // Lấy giá trị MaDT
    } else {
        // Xử lý khi không tìm thấy MaDT
        return false; // Hoặc throw lỗi nếu cần
    }
    
    // Thực hiện câu lệnh INSERT
    $str2 = "INSERT INTO chitietdonthuoc (MaDT, MaThuoc, SoLuong, LieuDung, CachDung) 
             VALUES ('$madt_moi', '$mathuoc', '$soluong', '$lieudung', '$cachdung')";
    return mysqli_query($this->con, $str2);
}


    public function get1BS($maNV)
    {
        $str = "SELECT nv.MaNV, nv.HovaTen, nv.NgaySinh, nv.SoDT, nv.ChucVu, nv.GioiTinh, nv.EmailNV, nv.HinhAnh, ck.TenKhoa
                FROM nhanvien nv
                JOIN 
                     bacsi bs ON nv.maNV = bs.maNV
                JOIN 
                     chuyenkhoa ck ON bs.MaKhoa = ck.MaKhoa
                where 
                     nv.MaNV = $maNV";
        $rows = mysqli_query($this->con, $str);

        $mang = array();
        while ($row = mysqli_fetch_array($rows)) {
            $mang[] = $row;
        }
        return json_encode($mang);
    }

}
