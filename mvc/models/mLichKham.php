<?php
    class mLichKham extends DB
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
                    ORDER BY
                        lichkham.MaLK ASC"; 
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
                    WHERE lichkham.MaLK = '$MaLK'";
            $tblCTLK = mysqli_query($this->con, $str);
            $result = [];
            while ($row = mysqli_fetch_assoc($tblCTLK)) {
                $result[] = $row;
            }
            return json_encode($result); 
        }

        public function huyLK($HuyLK)
        {
            $str = "SELECT GioKham, NgayKham FROM lichkham WHERE MaLK = '$HuyLK'";
            $result = mysqli_query($this->con, $str);

            if ($result && $row = mysqli_fetch_assoc($result)) {
                $gioKham = $row['GioKham']; 
                $ngayKham = $row['NgayKham']; 

                $thoiGianHienTai = new DateTime(); 
                $thoiGianLichKham = new DateTime("$ngayKham $gioKham");

                $khoangCach = $thoiGianLichKham->getTimestamp() - $thoiGianHienTai->getTimestamp(); 
                $tongGio = $khoangCach / 3600; 

                if ($thoiGianLichKham <= $thoiGianHienTai) {
                    return ["status" => false, "message" => "Lịch khám đã qua, không thể hủy!"];
                }

                if ($tongGio < 5) {
                    return ["status" => false, "message" => "Lịch khám chỉ được hủy trước ít nhất 5 giờ!"];
                }

                $deleteQuery = "DELETE FROM lichkham WHERE MaLK = '$HuyLK'";
                $deleteResult = mysqli_query($this->con, $deleteQuery);

                if ($deleteResult) {
                    return ["status" => true, "message" => "Hủy lịch thành công!"];
                } else {
                    return ["status" => false, "message" => "Lỗi khi hủy lịch khám. Vui lòng thử lại!"];
                }
            }

            return ["status" => false, "message" => "Lịch khám không tồn tại."];
        }
       
        public function ThayDoiLK($ThayDoiLK, $NgayKham, $GioKham)
        {
            $currentDateTime = time(); 

            $queryCurrent = "SELECT NgayKham, GioKham FROM lichkham WHERE MaLK = '$ThayDoiLK'";
            $resultCurrent = mysqli_query($this->con, $queryCurrent);

            if ($resultCurrent && $row = mysqli_fetch_assoc($resultCurrent)) {
                $currentLichKhamDate = $row['NgayKham']; 
                $currentLichKhamTime = $row['GioKham']; 
                $currentLichKhamTimestamp = strtotime("$currentLichKhamDate $currentLichKhamTime"); 

                if ($currentLichKhamTimestamp <= $currentDateTime) {
                    return ["status" => false, "message" => "Lịch khám đã qua, không thể thay đổi!"];
                }

                $timeDiffHoursOld = ($currentLichKhamTimestamp - $currentDateTime) / 3600;
                if ($timeDiffHoursOld < 5) {
                    return ["status" => false, "message" => "Lịch khám chỉ được thay đổi trước ít nhất 5 giờ!"];
                }
            } else {
                return ["status" => false, "message" => "Không tìm thấy thông tin lịch khám!"];
            }

            $inputDateTime = strtotime("$NgayKham $GioKham"); 
            if ($inputDateTime <= $currentDateTime) {
                return ["status" => false, "message" => "Ngày giờ mới phải lớn hơn thời điểm hiện tại!"];
            }

            $query = "SELECT GioKham FROM lichkham WHERE NgayKham = '$NgayKham' AND MaLK != '$ThayDoiLK'";
            $result = mysqli_query($this->con, $query);

            while ($row = mysqli_fetch_assoc($result)) {
                $timeDiff = abs($inputDateTime - strtotime("$NgayKham " . $row['GioKham'])) / 60; 
                if ($timeDiff < 20) {
                    return ["status" => false, "message" => "Thời gian mới trùng với lịch khám khác!"];
                }
            }

            $updateQuery = "UPDATE lichkham SET NgayKham = '$NgayKham', GioKham = '$GioKham' WHERE MaLK = '$ThayDoiLK'";
            $updateResult = mysqli_query($this->con, $updateQuery);

            if ($updateResult) {
                return ["status" => true, "message" => "Thay đổi lịch khám thành công!"];
            } else {
                return ["status" => false, "message" => "Không thể thay đổi lịch. Vui lòng thử lại!"];
            }
        }        
    }

?>