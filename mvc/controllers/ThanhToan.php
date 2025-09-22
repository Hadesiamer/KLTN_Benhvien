<?php
class ThanhToan extends Controller
{

    function SayHi()
    {
        $MaBN = $_SESSION['idbn'];
        $khachhang = $this->model("mThanhToan");
        $MaLK = isset($_POST["MaLK"]) ? $_POST["MaLK"] : "";
        $NgayKham = isset($_POST["NgayKham"]) ? $_POST["NgayKham"] : "";
        $GioKham = isset($_POST["GioKham"]) ? $_POST["GioKham"] : "";
       
        $lichKham = $khachhang->GetLK($MaBN);
        $chiTietLichKham = ($MaLK != "") ? $khachhang->getCTLK($MaLK) : [];
        if(isset($_POST["thanhtoan"]))
        {
            $MaBN = $_POST["MaBN1"];
            $rs = $khachhang->insertHD($MaBN);
            $this->view("layoutBN", [
                "Page" => "ThanhToan",
                "LK" => $lichKham,
                "CTLK" => $chiTietLichKham,
                "rs" => $rs
            ]);
        }
        $this->view("layoutBN", [
            "Page" => "ThanhToan",
            "LK" => $lichKham,
            "CTLK" => $chiTietLichKham,
            
        ]);

    }

}


?>