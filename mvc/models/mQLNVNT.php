<?php
class mQLNVNT extends DB {
    public function GetAll()
    {
        $str = "SELECT * 
                FROM nhanvien 
                WHERE ChucVu = 'Nhân viên nhà thuốc'
                AND TrangThaiLamViec = 'Đang làm việc'";
        $result = $this->con->query($str);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return json_encode($data);
    }
    public function GetCTNV($manv)
    {
        $str = "SELECT * 
                FROM nhanvien 
                WHERE ChucVu = 'Nhân viên nhà thuốc'
                AND MaNV = $manv";
        $result = $this->con->query($str);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return json_encode($data);
    }
    public function UpdateNVNT($MaNV, $NgaySinh, $GioiTinh, $EmailNV)
    {
        $str = "UPDATE nhanvien 
                SET  NgaySinh = '$NgaySinh', GioiTinh = '$GioiTinh', EmailNV = '$EmailNV'
                WHERE MaNV = $MaNV";
        $tblPTTT = mysqli_query($this->con, $str);
        return $tblPTTT;
    }
    public function DeleteNVNT($MaNV)
    {
        $str = "UPDATE nhanvien 
                SET TrangThaiLamViec = 'Nghỉ làm', ID=null
                WHERE MaNV = $MaNV";
        $tblPTTT = mysqli_query($this->con, $str);
        return $tblPTTT;
    }
    public function AddNVNT($hovaten, $ngaysinh, $sodt, $email,$gioitinh)
    {
        $str= "INSERT INTO `nhanvien` (`MaNV`, `HovaTen`, `NgaySinh`, `SoDT`, `ChucVu`, `GioiTinh`, `TrangThaiLamViec`, `EmailNV`, `ID`, `img`) 
        VALUES ('', '$hovaten', '$ngaysinh', '$sodt', 'Nhân viên nhà thuốc', '$gioitinh', 'Đang làm việc', '$email', NULL, NULL);";
        $tblPTTT = mysqli_query($this->con, $str);
        return $tblPTTT;
    }

}
?>