<?php
    class mLichXetNghiem extends DB
    {
        public function GetLXN($MaBN)
        {
            $str = "SELECT *
                    FROM 
                        xetnghiem xn
                    INNER JOIN 
                        benhnhan ON xn.MaBN = benhnhan.MaBN 
                    WHERE 
                        xn.MaBN = '$MaBN'
                    ORDER BY
                    xn.MaXN ASC"; 
            $rows = mysqli_query($this->con, $str);
            $mang = array();
            while ($row = mysqli_fetch_array($rows)) {
                $mang[] = $row;
            }
            return json_encode($mang);

        }
        public function GetCTLXN($MaLK)
        {
            $str = "SELECT *
                    FROM 
                        xetnghiem xn
                    INNER JOIN 
                        benhnhan ON xn.MaBN = benhnhan.MaBN 
                    WHERE 
                        xn.MaXN = '$MaLK'"; 
            $rows = mysqli_query($this->con, $str);
            $mang = array();
            while ($row = mysqli_fetch_array($rows)) {
                $mang[] = $row;
            }
            return json_encode($mang);
        }
        public function HuyLXN($MaLK)
        {
            $str = "UPDATE xetnghiem 
                SET TrangThai = 'Đã hủy'
                WHERE MaXN = '$MaLK'";
            $tblPTTT = mysqli_query($this->con, $str);
            return $tblPTTT;
        }
    }
?>