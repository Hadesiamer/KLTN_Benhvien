<?php
class mBN extends DB
{
    public function get1BN($mabn){
        $str = "select * from benhnhan where MaBN='$mabn'";
        $rows = mysqli_query($this->con, $str);
        
        $mang = array();
        while($row = mysqli_fetch_array($rows)){
            $mang[] = $row;
        }
        return json_encode($mang);
    }

    public function emailExistsForUpdate($email, $mabn) {
        $str = "SELECT COUNT(*) as count FROM benhnhan WHERE Email = '$email' AND MaBN != $mabn";
        $result = mysqli_query($this->con, $str);
        $row = mysqli_fetch_assoc($result);
        return $row['count'] > 0;
    }
    public function UpdateBN($mabn, $hoten, $gioitinh, $ngaysinh, $diachi, $email, $bhyt) {
        if ($this->emailExistsForUpdate($email, $mabn)) {
            return json_encode(array(
                "success" => false,
                "message" => "Email đã tồn tại hoặc đã được sử dụng!"
            ));
        }
        $str = "UPDATE benhnhan SET HovaTen='$hoten', GioiTinh='$gioitinh', NgaySinh='$ngaysinh', 
                DiaChi='$diachi', Email='$email', BHYT='$bhyt' WHERE MaBN = $mabn";
        $result = mysqli_query($this->con, $str);
    
        return json_encode(array(
            "success" => $result,
            "message" => $result ? "Cập nhật thông tin thành công" : "Cập nhật thông tin thất bại"
        ));
    }

    public function getDKXN($ngayxn, $ketqua, $loaixn, $mabn){
        $str = "INSERT INTO xetnghiem (`MaXN`, `NgayXetNghiem`, `KetQua`, `LoaiXN`, `MaBN`) VALUES('', '$ngayxn', NULL, '$loaixn', '$mabn')";
        $result = false;
        if(mysqli_query($this->con, $str)){
            $result = true;
        }
        return json_encode($result);
    }
}
?>