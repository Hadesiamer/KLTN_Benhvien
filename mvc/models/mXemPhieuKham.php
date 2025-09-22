<?php
    class mXemPhieuKham extends DB
    {
        public function GetPK($MaBN)
        {
            $str = " SELECT 
                        phieukham.MaPK AS phieukham_MaPK, 
                        phieukham.*, 
                        nhanvien.*,
                        benhnhan.*, 
                        xetnghiem.*,
                        nhanvien.HovaTen AS HovaTenNV
                    FROM 
                        phieukham
                    INNER JOIN 
                        benhnhan ON phieukham.MaBN = benhnhan.MaBN 
                    INNER JOIN 
                        nhanvien ON phieukham.MaBS = nhanvien.MaNV 
                    INNER JOIN 
                        xetnghiem ON xetnghiem.MaXN = phieukham.MaXN
                    WHERE 
                        phieukham.MaBN = '$MaBN'
                    ORDER BY
                        phieukham.NgayTao ASC"; 
            $tblPhieuKham = mysqli_query($this->con, $str);
            $result = [];
            while ($row = mysqli_fetch_assoc($tblPhieuKham)) {
                if (!in_array($row, $result)) {
                    $result[] = $row;
                }
            }
            return json_encode($result);

        }

        public function getCTPK($MaPK)
        {
            $str = "SELECT 
                        phieukham.MaPK AS phieukham_MaPK, 
                        phieukham.*, 
                        nhanvien.*,
                        nhanvien.HovaTen AS HovaTenNV,
                        benhnhan.*, 
                        xetnghiem.* 
                    FROM 
                        phieukham
                    INNER JOIN 
                        benhnhan ON phieukham.MaBN = benhnhan.MaBN 
                    INNER JOIN 
                        nhanvien ON phieukham.MaBS = nhanvien.MaNV 
                    INNER JOIN 
                        xetnghiem ON xetnghiem.MaXN = phieukham.MaXN
                    WHERE 
                        phieukham.MaPK = '$MaPK'
                    ORDER BY
                        phieukham.NgayTao ASC"; 
            $tblCTPK = mysqli_query($this->con, $str);
            $result = [];
            while ($row = mysqli_fetch_assoc($tblCTPK)) {
                $result[] = $row;
            }
            return json_encode($result); 
        }

        

        
    }

?>