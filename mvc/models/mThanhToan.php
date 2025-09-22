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
                        lichkham.MaBN = '$MaBN'; 
                    "; 
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
        public function insertHD($MaBN)
        {
            $pdo = new PDO('mysql:host=localhost;dbname=domdom', 'domdom', '1234');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $date = date('Y-m-d');
            $stmt = $pdo->prepare("INSERT INTO hoadon (MaBN, NgayLapHoaDon, TongTien) VALUES (?, ?, ?)");
            $stmt->execute([$MaBN, $date, '200000']);
            $manv_moi = $pdo->lastInsertId();
            $str2 = "INSERT INTO `chitiethoadon` (`MaHD`, `DonGia`, `GiamGia`, `ThanhTien`, `DichVu`) VALUES ('$manv_moi', '500000', '50', '250000', 'Khám Bệnh');";
            $rs = mysqli_query($this->con, $str2);
            return $rs;
        }

    }

?>