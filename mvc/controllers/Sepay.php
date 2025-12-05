<?php
class Sepay extends Controller
{
    /**
     * Endpoint Webhook: /KLTN_Benhvien/Sepay/Webhook
     * SePay sẽ POST JSON vào đây mỗi khi có giao dịch mới.
     */
    public function Webhook()
    {
        // Đảm bảo trả JSON
        header('Content-Type: application/json; charset=utf-8');

        // Đọc raw body
        $rawBody = file_get_contents('php://input');
        if (!$rawBody) {
            echo json_encode([
                "status"  => "error",
                "message" => "Empty body",
                "MaLK"    => null,
                "updated_paid" => null
            ]);
            return;
        }

        // Decode JSON
        $data = json_decode($rawBody, true);
        if (!is_array($data)) {
            echo json_encode([
                "status"  => "error",
                "message" => "Invalid JSON",
                "MaLK"    => null,
                "updated_paid" => null
            ]);
            return;
        }

        // Lấy các field quan trọng theo đúng payload thật từ SePay
        $gateway          = isset($data['gateway']) ? $data['gateway'] : null;                // VD: "MBBank"
        $transactionDate  = isset($data['transactionDate']) ? $data['transactionDate'] : null; // không lưu riêng ở đây, đã có trong RawContent
        $accountNumber    = isset($data['accountNumber']) ? $data['accountNumber'] : null;     // "010401304888"
        $subAccount       = isset($data['subAccount']) ? $data['subAccount'] : null;
        $content          = isset($data['content']) ? $data['content'] : '';
        $transferType     = isset($data['transferType']) ? $data['transferType'] : null;      // "in" / "out"
        $description      = isset($data['description']) ? $data['description'] : null;
        $transferAmount   = isset($data['transferAmount']) ? (int)$data['transferAmount'] : 0; // 2000
        $referenceCode    = isset($data['referenceCode']) ? $data['referenceCode'] : null;    // mã tham chiếu
        $sepayTransId     = isset($data['id']) ? $data['id'] : null;                           // ID giao dịch bên SePay

        // Chỉ quan tâm giao dịch tiền vào
        if ($transferType !== 'in') {
            echo json_encode([
                "status"  => "success",
                "message" => "Ignore non-in transaction",
                "MaLK"    => null,
                "updated_paid" => false
            ]);
            return;
        }

        // Model
        $sepayModel = $this->model("mSepay");

        // Tránh lưu trùng theo TransactionCode (referenceCode hoặc id SePay)
        $transactionCode = $referenceCode ?: $sepayTransId;
        if ($transactionCode && $sepayModel->isTransactionCodeExists($transactionCode)) {
            echo json_encode([
                "status"  => "success",
                "message" => "Transaction already processed",
                "MaLK"    => null,
                "updated_paid" => false
            ]);
            return;
        }

        // Lưu giao dịch vào bảng sepay_transactions
        $insertId = $sepayModel->insertTransaction(
            $transferAmount,
            $gateway,
            $accountNumber,
            $transactionCode,
            $description,
            $rawBody // lưu full JSON
        );

        if (!$insertId) {
            echo json_encode([
                "status"  => "error",
                "message" => "Can not insert sepay_transactions",
                "MaLK"    => null,
                "updated_paid" => null
            ]);
            return;
        }

        // ================== TÌM MÃ LỊCH KHÁM TRONG NỘI DUNG ==================
        // Yêu cầu: nội dung chuyển khoản phải chứa chuỗi "LK{MaLK}", ví dụ: "Thanh toan lich kham LK160"
        $MaLK = null;
        if (!empty($content)) {
            if (preg_match('/LK(\d+)/i', $content, $matches)) {
                $MaLK = (int)$matches[1];
            }
        }

        $updatedPaid = false;

        if ($MaLK !== null) {
            // Gắn MaLK + Update lichkham.TrangThaiThanhToan = 'Da thanh toan'
            $updatedPaid = $sepayModel->attachMaLKAndMarkPaid($insertId, $MaLK);
        }

        // Luôn trả 200 OK cho SePay, kèm thông tin mình xử lý được gì
        echo json_encode([
            "status"        => "success",
            "message"       => "Webhook received",
            "MaLK"          => $MaLK,
            "updated_paid"  => $updatedPaid
        ]);
    }
}
?>
