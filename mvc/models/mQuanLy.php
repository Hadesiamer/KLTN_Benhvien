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
    public function CountEmployeeInShift($NgayLamViec, $CaLamViec) {
        $str = "SELECT COUNT(*) AS Total FROM lichlamviec 
        WHERE NgayLamViec = '$NgayLamViec' AND CaLamViec = '$CaLamViec' AND TrangThai = 'Đang làm'";
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
    
}
?>
