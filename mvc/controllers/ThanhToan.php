<?php  
class ThanhToan extends Controller
{   
    // Cấu hình SePay VA cho thanh toán lịch khám (demo KLTN)
    const SEPAY_VA_ACC   = 'VQRQAFSGX7208';
    const SEPAY_VA_BANK  = 'MBBank';
    const SEPAY_FEE_LK   = 10000;

    function SayHi()
    {
        $MaBN = $_SESSION['idbn'];
        $khachhang = $this->model("mThanhToan");

        $Message = "";
        $MessageType = "";

        // ================= XỬ LÝ HỦY LỊCH =================
        if (isset($_POST["MaLK_Huy"])) {
            $MaLKHuy = (int)$_POST["MaLK_Huy"];
            $rsHuy = $khachhang->deleteLK($MaLKHuy);

            if ($rsHuy) {
                $Message = "Hủy lịch khám mã $MaLKHuy thành công.";
                $MessageType = "success";
            } else {
                $Message = "Hủy lịch khám mã $MaLKHuy thất bại. Vui lòng thử lại.";
                $MessageType = "error";
            }
        }

        // ================= LẤY DSS LỊCH CHƯA THANH TOÁN =================
        $lichKhamJson  = $khachhang->GetLK($MaBN);
        $lichKhamArray = json_decode($lichKhamJson, true) ?: [];

        // ================= XÁC ĐỊNH MaLK ĐƯỢC CHỌN =================
        $MaLK = isset($_POST["MaLK"]) ? $_POST["MaLK"] : "";

        if ($MaLK === "" && !empty($lichKhamArray)) {
            $latestMaLK = $lichKhamArray[0]['MaLK'];
            foreach ($lichKhamArray as $lk) {
                if ($lk['MaLK'] > $latestMaLK) {
                    $latestMaLK = $lk['MaLK'];
                }
            }
            $MaLK = $latestMaLK;
        }

        // ================= LẤY CHI TIẾT =================
        $chiTietLichKham = ($MaLK != "") ? $khachhang->getCTLK($MaLK) : [];

        // ================= CẤU HÌNH SEPAY TRUYỀN VIEW =================
        $sepayConfig = [
            "va_acc" => self::SEPAY_VA_ACC,
            "bank"   => self::SEPAY_VA_BANK,
            "amount" => self::SEPAY_FEE_LK,
        ];

        // ================= LOAD VIEW =================
        $this->view("layoutBN", [
            "Page"        => "ThanhToan",
            "LK"          => $lichKhamJson,
            "CTLK"        => $chiTietLichKham,
            "Message"     => $Message,
            "MessageType" => $MessageType,
            "SePay"       => $sepayConfig
        ]);
    }


    // ==================================================================
    //  API POLLING CHECK THANH TOÁN — AJAX gọi mỗi 3 giây
    //  URL: /KLTN_Benhvien/ThanhToan/CheckPaymentAPI/{MaLK}
    // ==================================================================
    public function CheckPaymentAPI($MaLK = null)
    {
        header("Content-Type: application/json; charset=UTF-8");

        // Ưu tiên lấy MaLK từ tham số URL /ThanhToan/CheckPaymentAPI/198
        if ($MaLK === null || !is_numeric($MaLK)) {
            // Fallback: thử lấy từ query string ?MaLK=...
            if (isset($_GET["MaLK"]) && is_numeric($_GET["MaLK"])) {
                $MaLK = (int)$_GET["MaLK"];
            } else {
                echo json_encode([
                    "MaLK"   => null,
                    "status" => "error",
                    "message"=> "Thiếu MaLK hợp lệ"
                ]);
                return;
            }
        } else {
            $MaLK = (int)$MaLK;
        }

        $model = $this->model("mThanhToan");
        $rawStatus = $model->getPaymentStatus($MaLK); // "Da thanh toan" | "Chua thanh toan" | "not_found"

        // Chuẩn hóa status cho frontend
        switch ($rawStatus) {
            case 'Da thanh toan':
                $status = 'paid';
                break;
            case 'Chua thanh toan':
                $status = 'unpaid';
                break;
            case 'not_found':
                $status = 'not_found';
                break;
            default:
                $status = 'unknown';
                break;
        }

        echo json_encode([
            "MaLK"       => $MaLK,
            "status"     => $status,
            "raw_status" => $rawStatus
        ]);
    }
}
?>
