<?php
class mSepay extends DB
{
    //Đây là file mvc/models/mSePay.php
    // Lưu một giao dịch mới vào bảng sepay_transactions
    public function insertTransaction($amount, $bank, $accountNumber, $transactionCode, $description, $rawContent)
    {
        $sql = "INSERT INTO sepay_transactions 
                (Amount, Bank, AccountNumber, TransactionCode, Description, RawContent) 
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($this->con, $sql);
        if (!$stmt) {
            return false;
        }

        mysqli_stmt_bind_param(
            $stmt,
            "isssss",
            $amount,
            $bank,
            $accountNumber,
            $transactionCode,
            $description,
            $rawContent
        );

        if (!mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            return false;
        }

        $insertId = mysqli_insert_id($this->con);
        mysqli_stmt_close($stmt);
        return $insertId; // Id tự tăng của sepay_transactions
    }

    // Gắn MaLK cho giao dịch + cập nhật lichkham thành "Da thanh toan"
    public function attachMaLKAndMarkPaid($transactionId, $MaLK)
    {
        $MaLK = (int)$MaLK;
        $transactionId = (int)$transactionId;

        // 1. Update MaLK cho giao dịch
        $sqlUpdateTrans = "UPDATE sepay_transactions SET MaLK = ? WHERE Id = ?";
        $stmt1 = mysqli_prepare($this->con, $sqlUpdateTrans);
        if (!$stmt1) {
            return false;
        }
        mysqli_stmt_bind_param($stmt1, "ii", $MaLK, $transactionId);
        $ok1 = mysqli_stmt_execute($stmt1);
        mysqli_stmt_close($stmt1);

        // 2. Update lichkham
        $sqlUpdateLK = "UPDATE lichkham 
                        SET TrangThaiThanhToan = 'Da thanh toan' 
                        WHERE MaLK = ? AND TrangThaiThanhToan = 'Chua thanh toan'";
        $stmt2 = mysqli_prepare($this->con, $sqlUpdateLK);
        if (!$stmt2) {
            return false;
        }
        mysqli_stmt_bind_param($stmt2, "i", $MaLK);
        $ok2 = mysqli_stmt_execute($stmt2);
        $affectedRows = mysqli_stmt_affected_rows($stmt2);
        mysqli_stmt_close($stmt2);

        // Trả về true nếu có lịch khám nào được update
        return $ok1 && $ok2 && ($affectedRows > 0);
    }

    // Kiểm tra xem TransactionCode này đã được lưu chưa (tránh lưu trùng)
    public function isTransactionCodeExists($transactionCode)
    {
        $sql = "SELECT COUNT(*) AS total FROM sepay_transactions WHERE TransactionCode = ?";
        $stmt = mysqli_prepare($this->con, $sql);
        if (!$stmt) {
            return false;
        }
        mysqli_stmt_bind_param($stmt, "s", $transactionCode);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        return isset($row['total']) && $row['total'] > 0;
    }
}
?>
