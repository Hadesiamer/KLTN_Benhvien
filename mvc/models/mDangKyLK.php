<?php
    class mDangKyLK extends DB
    {
        public function GetAllCK()
        {
            $str = "SELECT * FROM chuyenkhoa";
            $tblChuyenKhoa = mysqli_query($this->con, $str);
            $result = [];
            while ($row = mysqli_fetch_assoc($tblChuyenKhoa)) {
                $result[] = $row;
            }
            return json_encode($result); 
        }
    
        public function GetBS($MaKhoa)
        {
            $str = "SELECT nhanvien.*, bacsi.* 
                    FROM nhanvien
                    INNER JOIN bacsi ON nhanvien.MaNV = bacsi.MaNV 
                    WHERE bacsi.MaKhoa = '$MaKhoa'";
            $tblListBS = mysqli_query($this->con, $str);
            $result = [];
            while ($row = mysqli_fetch_assoc($tblListBS)) {
                if (!in_array($row, $result)) {
                    $result[] = $row;
                }
            }
            return json_encode($result);
        }
    
       
    
        public function CheckTrungLich($MaBS, $NgayKham, $GioKham)
        {
            $str = "SELECT * FROM lichkham WHERE MaBS = '$MaBS' AND NgayKham = '$NgayKham' AND GioKham = '$GioKham'";
            $result = mysqli_query($this->con, $str);
            if (!$result) {
                die("Lỗi truy vấn: " . mysqli_error($this->con));
            }
            $exists = mysqli_num_rows($result) > 0;
            return json_encode(['exists' => $exists]);
        }
    
        public function InsertLichKham($MaBS, $NgayKham, $GioKham, $MaBN)
        {
            $str = "INSERT INTO lichkham (MaBS, NgayKham, GioKham, MaBN) VALUES ('$MaBS', '$NgayKham', '$GioKham', '$MaBN')";
            $result = mysqli_query($this->con, $str);
            if (!$result) {
                die("Lỗi truy vấn: " . mysqli_error($this->con));
            }
            return json_encode(['success' => $result]);
        }

        public function SearchChuyenKhoa($searchTerm)
        {
            $str = "SELECT * FROM chuyenkhoa WHERE TenKhoa LIKE '%$searchTerm%'";
            $result = mysqli_query($this->con, $str);
            if (!$result) {
                die(json_encode([
                    "success" => false,
                    "message" => "Lỗi truy vấn: " . mysqli_error($this->con),
                ]));
            }
            $chuyenKhoaList = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $chuyenKhoaList[] = $row;
                
            }
            return json_encode($chuyenKhoaList);
        }

    }
    
     


?>