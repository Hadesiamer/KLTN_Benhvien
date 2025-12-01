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
                    INNER JOIN 
                        benhnhan ON lichkham.MaBN = benhnhan.MaBN 
                    INNER JOIN 
                        bacsi ON lichkham.MaBS = bacsi.MaNV 
                    INNER JOIN 
                        nhanvien ON bacsi.MaNV = nhanvien.MaNV
                    INNER JOIN 
                        chuyenkhoa ON bacsi.MaKhoa = chuyenkhoa.MaKhoa 
                    WHERE 
                        lichkham.MaBN = '$MaBN'
                    AND lichkham.TrangThaiThanhToan = 'Chua thanh toan'
                    ORDER BY lichkham.MaLK DESC
                    ; "; 
            $tblLichKham = mysqli_query($this->con, $str);
            $result = [];
            while ($row = mysqli_fetch_assoc($tblLichKham)) {
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
                    INNER JOIN 
                        benhnhan ON lichkham.MaBN = benhnhan.MaBN 
                    INNER JOIN 
                        bacsi ON lichkham.MaBS = bacsi.MaNV 
                    INNER JOIN 
                        nhanvien ON bacsi.MaNV = nhanvien.MaNV
                    INNER JOIN 
                        chuyenkhoa ON bacsi.MaKhoa = chuyenkhoa.MaKhoa 
                    WHERE lichkham.MaLK = '$MaLK'
                    ORDER BY
                        lichkham.MaLK ASC";
            $tblCTLK = mysqli_query($this->con, $str);
            $result = [];
            while ($row = mysqli_fetch_assoc($tblCTLK)) {
                $result[] = $row;
            }
            return json_encode($result); 
        }
 

    }

?>
