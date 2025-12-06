<?php  
class ThanhToan extends Controller
{
    // Cấu hình SePay VA cho thanh toán lịch khám (demo KLTN)
    // Sau này nếu bạn đổi VA / ngân hàng / số tiền,
    // chỉ cần sửa 3 hằng số này.
    const SEPAY_VA_ACC   = 'VQRQAFSGX7208'; // Mã tài khoản ảo VA của SePay
    const SEPAY_VA_BANK  = 'MBBank';        // Tên ngân hàng
    const SEPAY_FEE_LK   = 10000;           // 10.000 VND / 1 lịch khám (demo)

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
            // (Hàm deleteLK hiện đang nằm trong mThanhToan)
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

        // ================= CẤU HÌNH SEPAY TRUYỀN RA VIEW =================
        // Để view chỉ cần đọc $data['SePay'] là dùng được.
        $sepayConfig = [
            "va_acc" => self::SEPAY_VA_ACC,
            "bank"   => self::SEPAY_VA_BANK,
            "amount" => self::SEPAY_FEE_LK,
        ];

        // ================= LOAD VIEW MẶC ĐỊNH =================
        $this->view("layoutBN", [
            "Page"        => "ThanhToan",
            "LK"          => $lichKhamJson,
            "CTLK"        => $chiTietLichKham,
            "Message"     => $Message,
            "MessageType" => $MessageType,
            "SePay"       => $sepayConfig // truyền cấu hình SePay sang view
        ]);
    }

}
?>