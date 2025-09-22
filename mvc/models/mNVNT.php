<?php
class mNVNT extends DB{
    public function GetTotalInvoices() {
        $str = 'SELECT COUNT(*) as total FROM donthuoc';
        $result = mysqli_query($this->con, $str);
        $row = mysqli_fetch_assoc($result);
        return $row['total'];
    }

    public function GetDT($offset, $limit) {
        $str = "SELECT d.*, b.HovaTen
                FROM donthuoc AS d
                JOIN benhnhan AS b ON d.MaBN = b.MaBN
                ORDER by d.MaDT desc
                LIMIT $offset, $limit";
        $rows = mysqli_query($this->con, $str);
        $mang = array();
        while ($row = mysqli_fetch_array($rows)) {
            $mang[] = $row;
        }
        return json_encode($mang);
    }
    public function GetDTTheoLoc($offset, $limit,$loc) {
        $str = "SELECT d.*, b.HovaTen
                FROM donthuoc AS d
                JOIN benhnhan AS b ON d.MaBN = b.MaBN
                WHERE d.TrangThai = $loc
                ORDER by d.MaDT desc
                LIMIT $offset, $limit";
        $rows = mysqli_query($this->con, $str);
        $mang = array();
        while ($row = mysqli_fetch_array($rows)) {
            $mang[] = $row;
        }
        return json_encode($mang);
    }
    public function getCTDT($MaDT)
    {
        $str = 'SELECT d.*, b.*, nv.HovaTen as TenBS
                FROM donthuoc AS d
                JOIN chitietdonthuoc AS dt ON d.MaDT = dt.MaDT
                JOIN benhnhan AS b ON d.MaBN = b.MaBN
                JOIN nhanvien AS nv ON d.MaBS = nv.MaNV
                WHERE d.MaDT = '.$MaDT.'
                ORDER by d.MaDT desc
                LIMIT 1';
        $rows = mysqli_query($this->con, $str);
        $mang = array();
        while ($row = mysqli_fetch_array($rows))
        {
            $mang[] = $row;
        }
        return json_encode($mang);
    }
    public function getThuoc($MaDT)
    {
        $str = 'SELECT *
                FROM donthuoc AS d
                JOIN chitietdonthuoc AS dt ON d.MaDT = dt.MaDT
                JOIN thuoc as t ON dt.MaThuoc = t.MaThuoc
                WHERE d.MaDT = '.$MaDT.'
                ORDER by d.MaDT desc';
        $rows = mysqli_query($this->con, $str);
        $mang = array();
        while ($row = mysqli_fetch_array($rows))
        {
            $mang[] = $row;
        }
        return json_encode($mang);
    }
    public function setPTTT($MaDT,$TT)
    {
        $str = 'UPDATE donthuoc 
                SET TrangThai = '.$TT.'
                WHERE MaDT = '.$MaDT.'
                LIMIT 1';
        $tblPTTT = mysqli_query($this->con, $str);
        return $tblPTTT;
    }
}
?>