<?php
class XemPhieuKham extends Controller
{

    function SayHi()
    {
        $MaBN = $_SESSION["idbn"];
        $khachhang = $this->model("mXemPhieuKham");
        $MaPK = isset($_POST["MaPK"]) ? $_POST["MaPK"] : "";
        $NgayTao = isset($_POST["NgayTao"]) ? $_POST["NgayTao"] : "";    
        
        $phieuKham = $khachhang->GetPK($MaBN);
        $chiTietPhieuKham = ($MaPK != "") ? $khachhang->getCTPK($MaPK) : [];
        $this->view("layoutBN", [
            "Page" => "XemPhieuKham",
            "PK" => $phieuKham,
            "CTPK" => $chiTietPhieuKham,

        ]);
    }

}


?>