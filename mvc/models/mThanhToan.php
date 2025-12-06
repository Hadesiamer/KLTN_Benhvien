<?php
class mThanhToan extends DB
{
    public function GetLK($MaBN)
    {
        $str = " SELECT 
                    lichkham.*, 
                    nhanvien.*,
                    nhanvien.HovaTen as HovaTenNV,
                    benhnhan.*, 
                    chuyenkhoa.* 
                FROM 
                    lichkham
                INNER JOIN benhnhan ON lichkham.MaBN = benhnhan.MaBN 
                INNER JOIN bacsi ON lichkham.MaBS = bacsi.MaNV 
                INNER JOIN nhanvien ON bacsi.MaNV = nhanvien.MaNV
                INNER JOIN chuyenkhoa ON bacsi.MaKhoa = chuyenkhoa.MaKhoa 
                WHERE 
                    lichkham.MaBN = '$MaBN'
                AND lichkham.TrangThaiThanhToan = 'Chua thanh toan'
                ORDER BY lichkham.MaLK DESC";

        $tbl = mysqli_query($this->con, $str);
        $result = [];

        while ($row = mysqli_fetch_assoc($tbl)) {
            if (!in_array($row, $result)) {
                $result[] = $row;
            }
        }
        return json_encode($result);
    }

    public function getCTLK($MaLK)
    {
        $str = "SELECT 
                    lichkham.*, 
                    nhanvien.*,
                    benhnhan.*, 
                    chuyenkhoa.* 
                FROM 
                    lichkham
                INNER JOIN benhnhan ON lichkham.MaBN = benhnhan.MaBN 
                INNER JOIN bacsi ON lichkham.MaBS = bacsi.MaNV 
                INNER JOIN nhanvien ON bacsi.MaNV = nhanvien.MaNV
                INNER JOIN chuyenkhoa ON bacsi.MaKhoa = chuyenkhoa.MaKhoa 
                WHERE lichkham.MaLK = '$MaLK'
                ORDER BY lichkham.MaLK ASC";

        $tbl = mysqli_query($this->con, $str);
        $result = [];

        while ($row = mysqli_fetch_assoc($tbl)) {
            $result[] = $row;
        }
        return json_encode($result);
    }


    // ================================================================
    //       HÀM MỚI: LẤY TRẠNG THÁI THANH TOÁN CHO POLLING
    // ================================================================
    public function getPaymentStatus($MaLK)
    {
        $MaLK = (int)$MaLK;

        $sql = "SELECT TrangThaiThanhToan 
                FROM lichkham 
                WHERE MaLK = $MaLK
                LIMIT 1";

        $result = mysqli_query($this->con, $sql);

        if ($row = mysqli_fetch_assoc($result)) {
            return $row["TrangThaiThanhToan"]; // "Da thanh toan" | "Chua thanh toan"
        }

        return "not_found";
    }
}
?>
