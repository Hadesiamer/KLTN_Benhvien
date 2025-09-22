<?php
class mLogin extends DB
{
    public function GetND($user, $pass){
        $str = "SELECT tk.ID, tk.username, tk.password, tk.MaPQ, 
                COALESCE(bn.SoDT, ql.SoDT, nv.SoDT) AS SoDT, 
                bn.MaBN, ql.MaQL, nv.MaNV,
                COALESCE(bn.HovaTen, ql.HovaTen, nv.HovaTen) AS HovaTen, 
                pq.TenPQ, 
                CASE 
                    WHEN bn.ID IS NOT NULL THEN 'benhnhan'
                    WHEN ql.ID IS NOT NULL THEN 'quanly'
                    WHEN nv.ID IS NOT NULL THEN 'nhanvien'
                END AS role
        FROM taikhoan tk 
        LEFT JOIN benhnhan bn ON tk.ID = bn.ID 
        LEFT JOIN quanly ql ON tk.ID = ql.ID
        LEFT JOIN nhanvien nv ON tk.ID = nv.ID
        JOIN phanquyen pq ON tk.MaPQ = pq.MaPQ
        WHERE tk.username = '$user' AND tk.password = '$pass'";
        $tblND = mysqli_query($this->con, $str);
        return $tblND;  
    }
}
?>