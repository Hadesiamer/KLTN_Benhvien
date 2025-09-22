<?php
class LichXetNghiem extends Controller
{

    function SayHi()
    {
        $maBN = $_SESSION['idbn'];
        $khachhang = $this->model("mLichXetNghiem");
        $maXN = isset($_POST["MaLXN"]) ? $_POST["MaLXN"] : "";
        if(isset($_POST["HuyLXN"])){
            $rs = $khachhang->HuyLXN($maXN);
            $this->view("layoutBN", [
                "Page" => "lichxetnghiem",
                "LXN"=> $khachhang->GetLXN($maBN),
                "CTLXN" => $khachhang->GetCTLXN($maXN),
                "rs" => $rs
            ]);
        }
        $this->view("layoutBN", [
            "Page" => "lichxetnghiem",
            "LXN"=> $khachhang->GetLXN($maBN),
            "CTLXN" => $khachhang->GetCTLXN($maXN),
        ]);
    }

}


?>