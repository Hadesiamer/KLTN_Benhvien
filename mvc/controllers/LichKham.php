<?php
class LichKham extends Controller
{
    function SayHi()
    {
        $MaBN = $_SESSION["idbn"];
        $khachhang = $this->model("mLichKham");
        $MaLK = isset($_POST["MaLK"]) ? $_POST["MaLK"] : "";
        $HuyLK = isset($_POST["HuyLK"]) ? $_POST["HuyLK"] : "";
        $ThayDoiLK = isset($_POST["ThayDoiLK"]) ? $_POST["ThayDoiLK"] : "";
        $NgayKham = isset($_POST["NgayKham"]) ? $_POST["NgayKham"] : "";
        $GioKham = isset($_POST["GioKham"]) ? $_POST["GioKham"] : "";
       
        if ($ThayDoiLK != "" && $NgayKham != "" && $GioKham != "") {
            $ThayDoiResult = $khachhang->ThayDoiLK($ThayDoiLK, $NgayKham, $GioKham);
            $message = $ThayDoiResult["message"];
            $messageType = $ThayDoiResult["status"] ? "success" : "error";
        } elseif ($HuyLK != "") {
            $HuyLKResult = $khachhang->huyLK($HuyLK);
            $message = $HuyLKResult["message"];
            $messageType = $HuyLKResult["status"] ? "success" : "error";
        } else {
            $message = "";
            $messageType = "";
        }
        
        $lichKham = $khachhang->GetLK($MaBN);
        $chiTietLichKham = ($MaLK != "") ? $khachhang->getCTLK($MaLK) : [];
        $this->view("layoutBN", [
            "Page" => "LichKham",
            "LK" => $lichKham,
            "CTLK" => $chiTietLichKham,
            "Message" => $message,
            "MessageType" => $messageType,
        ]);
    }

}


?>