<?php
class mNVYT extends DB
{
    public function GetTotalInvoices() {
        $str = 'SELECT COUNT(*) as total FROM hoadon';
        $result = mysqli_query($this->con, $str);
        $row = mysqli_fetch_assoc($result);
        return $row['total'];
    }
    public function GetHD($offset, $limit)
    {
        $str = "select * 
                from hoadon as h 
                join chitiethoadon as hd on h.MaHD = hd.MaHD 
                join benhnhan as b on h.MaBN=b.MaBN 
                join phuongthucthanhtoan as t on h.MaPTTT = t.MaPTTT
                order by h.MaHD desc
                LIMIT $offset, $limit";
        $rows = mysqli_query($this->con, $str);
        $mang = array();
        while ($row = mysqli_fetch_array($rows))
        {
            $mang[] = $row;
        }
        return json_encode($mang);
    }
    public function GetHDTheoLoc($offset, $limit,$loc)
    {
        $str = "select * 
                from hoadon as h 
                join chitiethoadon as hd on h.MaHD = hd.MaHD 
                join benhnhan as b on h.MaBN=b.MaBN 
                join phuongthucthanhtoan as t on h.MaPTTT = t.MaPTTT
                WHERE h.TrangThai = $loc
                order by h.MaHD desc
                LIMIT $offset, $limit";
        $rows = mysqli_query($this->con, $str);
        $mang = array();
        while ($row = mysqli_fetch_array($rows))
        {
            $mang[] = $row;
        }
        return json_encode($mang);
    }
    public function getCTHD($MaHD)
    {
        $str = 'SELECT *
                FROM hoadon AS h
                JOIN chitiethoadon AS hd ON h.MaHD = hd.MaHD
                JOIN benhnhan AS b ON h.MaBN = b.MaBN
                WHERE h.MaHD = '.$MaHD.'
                ORDER BY h.MaHD DESC';
        $rows = mysqli_query($this->con, $str);
        $mang = array();
        while ($row = mysqli_fetch_array($rows))
        {
            $mang[] = $row;
        }
        return json_encode($mang);
    }

    public function getPTTT()
    {
        $str = 'SELECT * 
                FROM phuongthucthanhtoan 
                WHERE MaPTTT > 0';
        $rows = mysqli_query($this->con, $str);
        $mang = array();
        while ($row = mysqli_fetch_array($rows))
        {
            $mang[] = $row;
        }
        return json_encode($mang);
    }
    public function setPTTT($MaHD,$PT)
    {
        $str = 'UPDATE hoadon SET MaPTTT = '.$PT.' WHERE MaHD = '.$MaHD.'';
        $tblPTTT = mysqli_query($this->con, $str);
        return $tblPTTT;
    }
    public function setTrangThai($MaHD,$TT)
    {
        $str = 'UPDATE hoadon SET TrangThai = '.$TT.' WHERE MaHD = '.$MaHD.'';
        $tblTT = mysqli_query($this->con, $str);
        return $tblTT;
    }

    // Lịch khám
    public function GetTotalInvoicesLK() {
        $str = 'SELECT COUNT(*) as total FROM lichkham';
        $result = mysqli_query($this->con, $str);
        $row = mysqli_fetch_assoc($result);
        return $row['total'];
    }
    public function GetAllLK($offset, $limit)
    {
        $str = "SELECT * 
                FROM lichkham lk
                JOIN phieukham pk on lk.MaLK = pk.MaLK
                JOIN benhnhan bn  on pk.MaPK = bn.MaPK
                LIMIT $offset, $limit";
        $rows = mysqli_query($this->con, $str);
        $mang = array();
        while ($row = mysqli_fetch_array($rows))
        {
            $mang[] = $row;
        }
        return json_encode($mang);
    }
    public function GetLKTheoLoc($offset, $limit, $loc)
    {
        $str = "SELECT * 
                FROM lichkham lk
                JOIN benhnhan bn  on lk.MaBN = bn.MaBN
                WHERE lk.NgayKham = '$loc'
                LIMIT $offset, $limit";
        $rows = mysqli_query($this->con, $str);
        $mang = array();
        while ($row = mysqli_fetch_array($rows))
        {
            $mang[] = $row;
        }
        return json_encode($mang);
    }
    public function getCTLK($MaLK)
    {
        $str = "SELECT * 
                FROM lichkham lk
                JOIN benhnhan bn  on lk.MaBN = bn.MaBN
                JOIN  bacsi bs ON lk.MaBS = bs.MaNV 
                JOIN nhanvien nv ON bs.MaNV = nv.MaNV
                JOIN chuyenkhoa ck ON bs.MaKhoa = ck.MaKhoa
                WHERE lk.MaLK = '$MaLK'";
        $rows = mysqli_query($this->con, $str);
        $mang = array();
        while ($row = mysqli_fetch_array($rows))
        {
            $mang[] = $row;
        }
        return json_encode($mang);
    }
    public function ThayDoiLK($MaLK, $NgayKham, $GioKham)
        {
            $str = "UPDATE lichkham 
                    SET NgayKham = '$NgayKham', GioKham = '$GioKham' WHERE MaLK = $MaLK";
            $tblPTTT = mysqli_query($this->con, $str);
            return $tblPTTT;
        }
}



?>