<?php 
class ThanhToan extends Controller
{

    function SayHi()
    {
        // Lấy mã bệnh nhân từ session
        $MaBN = $_SESSION['idbn'];

        // Gọi model
        $khachhang = $this->model("mThanhToan");

        // ================= BIẾN THÔNG BÁO (CHO UI) =================
        $Message = "";
        $MessageType = ""; // 'success' | 'error'

        // ================= XỬ LÝ NÚT HỦY LỊCH KHÁM =================
        if (isset($_POST["MaLK_Huy"])) {
            $MaLKHuy = (int)$_POST["MaLK_Huy"]; // Ép kiểu int cho an toàn

            // Gọi model xóa lịch khám
            $rsHuy = $khachhang->deleteLK($MaLKHuy);

            if ($rsHuy) {
                $Message = "Hủy lịch khám mã $MaLKHuy thành công.";
                $MessageType = "success";
            } else {
                $Message = "Hủy lịch khám mã $MaLKHuy thất bại. Vui lòng thử lại.";
                $MessageType = "error";
            }
        }

        // ================= LẤY DANH SÁCH LỊCH KHÁM CHƯA THANH TOÁN =================
        // GetLK trả về JSON, ta giữ lại chuỗi JSON để truyền ra view,
        // đồng thời decode sang array để tìm MaLK mới nhất
        $lichKhamJson  = $khachhang->GetLK($MaBN);      // JSON cho view
        $lichKhamArray = json_decode($lichKhamJson, true) ?: []; // Array để xử lý

        // ================= XÁC ĐỊNH MaLK ĐƯỢC CHỌN =================
        // Nếu người dùng chọn từ list (click vào item bên trái)
        $MaLK = isset($_POST["MaLK"]) ? $_POST["MaLK"] : "";

        // Nếu chưa chọn MaLK từ POST và vẫn còn lịch khám -> tự động chọn lịch mới nhất
        if ($MaLK === "" && !empty($lichKhamArray)) {
            // Tìm MaLK lớn nhất trong danh sách lịch khám
            $latestMaLK = $lichKhamArray[0]['MaLK'];
            foreach ($lichKhamArray as $lk) {
                if ($lk['MaLK'] > $latestMaLK) {
                    $latestMaLK = $lk['MaLK'];
                }
            }
            $MaLK = $latestMaLK; // Mặc định xem chi tiết lịch khám mới nhất
        }

        // ================= LẤY CHI TIẾT LỊCH KHÁM THEO MaLK =================
        // getCTLK trả về JSON, truyền thẳng cho view
        $chiTietLichKham = ($MaLK != "") ? $khachhang->getCTLK($MaLK) : [];

        // ================= LOAD VIEW MẶC ĐỊNH =================
        $this->view("layoutBN", [
            "Page"        => "ThanhToan",
            "LK"          => $lichKhamJson,
            "CTLK"        => $chiTietLichKham,
            "Message"     => $Message,
            "MessageType" => $MessageType
        ]);
    }

}
?>
