<?php
// Model chat dùng chung cho BN & BS
class mChat extends DB
{
    // Lấy danh sách bác sĩ mà BN được phép chat
    // Điều kiện: BN có lịch khám online (loaidichvu = 3) và Da thanh toan
    public function getDanhSachBacSiChoBN($maBN)
    {
        $maBN = intval($maBN);
        $ds   = [];

        $sql = "
            SELECT DISTINCT
                nv.MaNV      AS MaBS,
                nv.HovaTen   AS TenBacSi,
                ck.TenKhoa   AS TenKhoa,
                ck.MoTa      AS MoTaKhoa
            FROM lichkham lk
            JOIN bacsi bs       ON lk.MaBS = bs.MaNV
            JOIN nhanvien nv    ON bs.MaNV = nv.MaNV
            JOIN chuyenkhoa ck  ON bs.MaKhoa = ck.MaKhoa
            WHERE lk.MaBN = ?
              AND lk.TrangThaiThanhToan = 'Da thanh toan'
              AND lk.loaidichvu = 3
            ORDER BY nv.HovaTen ASC
        ";

        if ($stmt = $this->con->prepare($sql)) {
            $stmt->bind_param("i", $maBN);
            if ($stmt->execute()) {
                $res = $stmt->get_result();
                while ($row = $res->fetch_assoc()) {
                    $ds[] = $row;
                }
            }
            $stmt->close();
        }

        return $ds; // trả về mảng thuần PHP
    }

    // Kiểm tra BN có quyền chat với BS này không
    public function patientCanChatWithDoctor($maBN, $maBS)
    {
        $maBN = intval($maBN);
        $maBS = intval($maBS);
        $count = 0;

        $sql = "
            SELECT COUNT(*) AS total
            FROM lichkham
            WHERE MaBN = ?
              AND MaBS = ?
              AND TrangThaiThanhToan = 'Da thanh toan'
              AND loaidichvu = 3
        ";

        if ($stmt = $this->con->prepare($sql)) {
            $stmt->bind_param("ii", $maBN, $maBS);
            if ($stmt->execute()) {
                $res = $stmt->get_result();
                if ($row = $res->fetch_assoc()) {
                    $count = (int)$row['total'];
                }
            }
            $stmt->close();
        }

        return $count > 0;
    }

    // Lấy hoặc tạo mới một cuộc trò chuyện BN - BS, trả về MaCuocTrove
    public function getOrCreateConversation($maBN, $maBS)
    {
        $maBN = intval($maBN);
        $maBS = intval($maBS);

        // 1. Thử tìm cuộc trò chuyện đã có
        $sqlSelect = "
            SELECT MaCuocTrove
            FROM cuoc_tro_chuyen
            WHERE MaBN = ? AND MaBS = ?
            LIMIT 1
        ";
        if ($stmt = $this->con->prepare($sqlSelect)) {
            $stmt->bind_param("ii", $maBN, $maBS);
            if ($stmt->execute()) {
                $res = $stmt->get_result();
                if ($row = $res->fetch_assoc()) {
                    $stmt->close();
                    return (int)$row['MaCuocTrove'];
                }
            }
            $stmt->close();
        }

        // 2. Nếu chưa có → tạo mới
        $sqlInsert = "
            INSERT INTO cuoc_tro_chuyen (MaBN, MaBS, ThoiGianTao, ThoiGianCapNhat)
            VALUES (?, ?, NOW(), NOW())
        ";
        if ($stmt2 = $this->con->prepare($sqlInsert)) {
            $stmt2->bind_param("ii", $maBN, $maBS);
            if ($stmt2->execute()) {
                $newId = $this->con->insert_id;
                $stmt2->close();
                return (int)$newId;
            }
            $stmt2->close();
        }

        return null;
    }

    // Kiểm tra cuộc trò chuyện thuộc về BN hiện tại
    public function patientOwnsConversation($maBN, $maCuocTrove)
    {
        $maBN        = intval($maBN);
        $maCuocTrove = intval($maCuocTrove);
        $count       = 0;

        $sql = "
            SELECT COUNT(*) AS total
            FROM cuoc_tro_chuyen
            WHERE MaCuocTrove = ? AND MaBN = ?
        ";
        if ($stmt = $this->con->prepare($sql)) {
            $stmt->bind_param("ii", $maCuocTrove, $maBN);
            if ($stmt->execute()) {
                $res = $stmt->get_result();
                if ($row = $res->fetch_assoc()) {
                    $count = (int)$row['total'];
                }
            }
            $stmt->close();
        }
        return $count > 0;
    }

    // Kiểm tra cuộc trò chuyện thuộc về BS hiện tại
    public function doctorOwnsConversation($maBS, $maCuocTrove)
    {
        $maBS        = intval($maBS);
        $maCuocTrove = intval($maCuocTrove);
        $count       = 0;

        $sql = "
            SELECT COUNT(*) AS total
            FROM cuoc_tro_chuyen
            WHERE MaCuocTrove = ? AND MaBS = ?
        ";
        if ($stmt = $this->con->prepare($sql)) {
            $stmt->bind_param("ii", $maCuocTrove, $maBS);
            if ($stmt->execute()) {
                $res = $stmt->get_result();
                if ($row = $res->fetch_assoc()) {
                    $count = (int)$row['total'];
                }
            }
            $stmt->close();
        }
        return $count > 0;
    }

    /**
     * Thêm tin nhắn mới (dùng chung cho BN & BS)
     * $fileMeta: mảng optional [
     *   'original_name' => string,
     *   'public_path'   => string,  // VD: /KLTN_Benhvien/public/uploads/chat/abc.png
     *   'mime_type'     => string|null,
     *   'size'          => int,
     *   'is_image'      => 0|1
     * ]
     */
    public function addMessage($maCuocTrove, $nguoiGuiLoai, $maNguoiGui, $noiDung, $fileMeta = null)
    {
        $maCuocTrove  = intval($maCuocTrove);
        $maNguoiGui   = intval($maNguoiGui);
        $nguoiGuiLoai = ($nguoiGuiLoai === 'BS') ? 'BS' : 'BN'; // chuẩn hóa
        $noiDung      = trim((string)$noiDung);

        // Yêu cầu: phải có hoặc nội dung text, hoặc file đính kèm
        $hasFile = (is_array($fileMeta) && !empty($fileMeta['public_path']));
        if ($maCuocTrove <= 0 || $maNguoiGui <= 0) {
            return false;
        }
        if ($noiDung === '' && !$hasFile) {
            // không có gì để gửi
            return false;
        }

        // Xác định cờ đã xem cho BN/BS
        $daXemBN = 0;
        $daXemBS = 0;
        if ($nguoiGuiLoai === 'BN') {
            $daXemBN = 1;
            $daXemBS = 0;
        } else {
            $daXemBN = 0;
            $daXemBS = 1;
        }

        $sql = "
            INSERT INTO tin_nhan
                (MaCuocTrove, NguoiGuiLoai, MaNguoiGui, NoiDung, ThoiGianGui, DaXemBN, DaXemBS)
            VALUES (?, ?, ?, ?, NOW(), ?, ?)
        ";
        if ($stmt = $this->con->prepare($sql)) {
            $stmt->bind_param(
                "isissi",
                $maCuocTrove,
                $nguoiGuiLoai,
                $maNguoiGui,
                $noiDung,
                $daXemBN,
                $daXemBS
            );
            $ok = $stmt->execute();
            $insertId = $ok ? $this->con->insert_id : 0;
            $stmt->close();

            if ($ok) {
                // Nếu có file đính kèm, lưu vào bảng chat_files
                if ($hasFile && $insertId > 0) {
                    $this->insertChatFileForMessage($insertId, $fileMeta);
                }

                // Cập nhật trạng thái trong cuoc_tro_chuyen
                if ($nguoiGuiLoai === 'BN') {
                    $this->updateConversationStatusForBN($maCuocTrove);
                } else {
                    $this->updateConversationStatusForBS($maCuocTrove);
                }
            }

            return $ok;
        }

        return false;
    }

    /**
     * Lưu bản ghi file đính kèm cho 1 tin nhắn
     */
    protected function insertChatFileForMessage($maTinNhan, array $fileMeta)
    {
        $maTinNhan    = intval($maTinNhan);
        $fileNameGoc  = isset($fileMeta['original_name']) ? (string)$fileMeta['original_name'] : '';
        $filePath     = isset($fileMeta['public_path'])   ? (string)$fileMeta['public_path']   : '';
        $fileType     = isset($fileMeta['mime_type'])     ? (string)$fileMeta['mime_type']     : null;
        $fileSize     = isset($fileMeta['size'])          ? (int)$fileMeta['size']             : 0;
        $isImage      = !empty($fileMeta['is_image']) ? 1 : 0;

        if ($maTinNhan <= 0 || $fileNameGoc === '' || $filePath === '') {
            return false;
        }

        $sql = "
            INSERT INTO chat_files
                (MaTinNhan, FileNameGoc, FilePath, FileType, FileSize, IsImage)
            VALUES (?, ?, ?, ?, ?, ?)
        ";

        if ($stmt = $this->con->prepare($sql)) {
            $stmt->bind_param(
                "isssii",
                $maTinNhan,
                $fileNameGoc,
                $filePath,
                $fileType,
                $fileSize,
                $isImage
            );
            $ok = $stmt->execute();
            $stmt->close();
            return $ok;
        }

        return false;
    }

    // Lấy danh sách tin nhắn mới hơn lastId
    // KÈM THEO THÔNG TIN FILE (nếu có)
    public function getMessages($maCuocTrove, $lastId = 0)
    {
        $maCuocTrove = intval($maCuocTrove);
        $lastId      = intval($lastId);
        $ds          = [];

        $sql = "
            SELECT 
                tn.MaTinNhan,
                tn.MaCuocTrove,
                tn.NguoiGuiLoai,
                tn.MaNguoiGui,
                tn.NoiDung,
                tn.ThoiGianGui,
                f.MaFile,
                f.FileNameGoc,
                f.FilePath,
                f.FileType,
                f.FileSize,
                f.IsImage
            FROM tin_nhan tn
            LEFT JOIN chat_files f ON f.MaTinNhan = tn.MaTinNhan
            WHERE tn.MaCuocTrove = ?
              AND tn.MaTinNhan > ?
            ORDER BY tn.MaTinNhan ASC
        ";
        if ($stmt = $this->con->prepare($sql)) {
            $stmt->bind_param("ii", $maCuocTrove, $lastId);
            if ($stmt->execute()) {
                $res = $stmt->get_result();
                while ($row = $res->fetch_assoc()) {
                    // Đảm bảo kiểu bool nhỏ gọn khi trả JSON
                    $row['IsImage'] = isset($row['IsImage']) ? (int)$row['IsImage'] : 0;
                    $ds[] = $row;
                }
            }
            $stmt->close();
        }

        return $ds;
    }

    // Đánh dấu tin đã xem phía BN
    public function markAsReadForPatient($maCuocTrove, $maBN)
    {
        $maCuocTrove = intval($maCuocTrove);
        $maBN        = intval($maBN);

        // Đánh dấu trong tin_nhan
        $sql = "
            UPDATE tin_nhan tn
            JOIN cuoc_tro_chuyen c ON tn.MaCuocTrove = c.MaCuocTrove
            SET tn.DaXemBN = 1
            WHERE tn.MaCuocTrove = ?
              AND c.MaBN = ?
        ";
        if ($stmt = $this->con->prepare($sql)) {
            $stmt->bind_param("ii", $maCuocTrove, $maBN);
            $stmt->execute();
            $stmt->close();
        }

        // Đặt lại cờ trong cuoc_tro_chuyen
        $sql2 = "
            UPDATE cuoc_tro_chuyen
            SET TrangThaiChoBN = 0
            WHERE MaCuocTrove = ? AND MaBN = ?
        ";
        if ($stmt2 = $this->con->prepare($sql2)) {
            $stmt2->bind_param("ii", $maCuocTrove, $maBN);
            $stmt2->execute();
            $stmt2->close();
        }
    }

    // Đánh dấu tin đã xem phía BS
    public function markAsReadForDoctor($maCuocTrove, $maBS)
    {
        $maCuocTrove = intval($maCuocTrove);
        $maBS        = intval($maBS);

        $sql = "
            UPDATE tin_nhan tn
            JOIN cuoc_tro_chuyen c ON tn.MaCuocTrove = c.MaCuocTrove
            SET tn.DaXemBS = 1
            WHERE tn.MaCuocTrove = ?
              AND c.MaBS = ?
        ";
        if ($stmt = $this->con->prepare($sql)) {
            $stmt->bind_param("ii", $maCuocTrove, $maBS);
            $stmt->execute();
            $stmt->close();
        }

        $sql2 = "
            UPDATE cuoc_tro_chuyen
            SET TrangThaiChoBS = 0
            WHERE MaCuocTrove = ? AND MaBS = ?
        ";
        if ($stmt2 = $this->con->prepare($sql2)) {
            $stmt2->bind_param("ii", $maCuocTrove, $maBS);
            $stmt2->execute();
            $stmt2->close();
        }
    }

    // Cập nhật trạng thái khi BN gửi tin
    protected function updateConversationStatusForBN($maCuocTrove)
    {
        $maCuocTrove = intval($maCuocTrove);
        $sql = "
            UPDATE cuoc_tro_chuyen
            SET ThoiGianCapNhat = NOW(),
                TrangThaiChoBS = 1
            WHERE MaCuocTrove = ?
        ";
        if ($stmt = $this->con->prepare($sql)) {
            $stmt->bind_param("i", $maCuocTrove);
            $stmt->execute();
            $stmt->close();
        }
    }

    // Cập nhật trạng thái khi BS gửi tin
    protected function updateConversationStatusForBS($maCuocTrove)
    {
        $maCuocTrove = intval($maCuocTrove);
        $sql = "
            UPDATE cuoc_tro_chuyen
            SET ThoiGianCapNhat = NOW(),
                TrangThaiChoBN = 1
            WHERE MaCuocTrove = ?
        ";
        if ($stmt = $this->con->prepare($sql)) {
            $stmt->bind_param("i", $maCuocTrove);
            $stmt->execute();
            $stmt->close();
        }
    }

    // Danh sách cuộc trò chuyện cho BÁC SĨ (hộp thư)
    public function getConversationsForDoctor($maBS)
    {
        $maBS = intval($maBS);
        $ds   = [];

        $sql = "
            SELECT
                c.MaCuocTrove,
                c.MaBN,
                c.ThoiGianCapNhat,
                c.TrangThaiChoBS,
                bn.HovaTen AS TenBenhNhan,
                bn.SoDT    AS SoDT,
                bn.BHYT    AS BHYT
            FROM cuoc_tro_chuyen c
            JOIN benhnhan bn ON c.MaBN = bn.MaBN
            WHERE c.MaBS = ?
            ORDER BY c.ThoiGianCapNhat DESC
        ";
        if ($stmt = $this->con->prepare($sql)) {
            $stmt->bind_param("i", $maBS);
            if ($stmt->execute()) {
                $res = $stmt->get_result();
                while ($row = $res->fetch_assoc()) {
                    $ds[] = $row;
                }
            }
            $stmt->close();
        }
        return $ds;
    }

    // Lấy header hội thoại cho BS (đảm bảo BS sở hữu hội thoại này)
    public function getConversationHeaderForDoctor($maCuocTrove, $maBS)
    {
        $maCuocTrove = intval($maCuocTrove);
        $maBS        = intval($maBS);
        $info        = null;

        $sql = "
            SELECT
                c.MaCuocTrove,
                bn.MaBN,
                bn.HovaTen AS TenBenhNhan,
                bn.SoDT    AS SoDT
            FROM cuoc_tro_chuyen c
            JOIN benhnhan bn ON c.MaBN = bn.MaBN
            WHERE c.MaCuocTrove = ? AND c.MaBS = ?
            LIMIT 1
        ";
        if ($stmt = $this->con->prepare($sql)) {
            $stmt->bind_param("ii", $maCuocTrove, $maBS);
            if ($stmt->execute()) {
                $res = $stmt->get_result();
                $info = $res->fetch_assoc();
            }
            $stmt->close();
        }
        return $info;
    }
}
?>
