<?php
// mvc/models/mChatbotLK.php

class mChatbotLK extends DB
{
    // Lấy hồ sơ bệnh nhân theo ID tài khoản (cột ID trong benhnhan)
    public function getBenhNhanByUserId(int $userId)
    {
        $sql = "SELECT * FROM benhnhan WHERE ID = ? LIMIT 1";
        $stmt = mysqli_prepare($this->con, $sql);
        if (!$stmt) return null;

        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row    = $result ? mysqli_fetch_assoc($result) : null;
        mysqli_stmt_close($stmt);

        return $row;
    }

    // Lấy tất cả chuyên khoa
    public function getAllChuyenKhoa(): array
    {
        $data = [];
        $sql  = "SELECT MaKhoa, TenKhoa FROM chuyenkhoa ORDER BY MaKhoa ASC";
        $res  = mysqli_query($this->con, $sql);
        if ($res) {
            while ($row = mysqli_fetch_assoc($res)) {
                $data[] = $row;
            }
            mysqli_free_result($res);
        }
        return $data;
    }

    // Lấy tất cả loại dịch vụ
    public function getAllLoaiDichVu(): array
    {
        $data = [];
        $sql  = "SELECT MaLoai, LoaiDichVu FROM loaidichvu ORDER BY MaLoai ASC";
        $res  = mysqli_query($this->con, $sql);
        if ($res) {
            while ($row = mysqli_fetch_assoc($res)) {
                $data[] = $row;
            }
            mysqli_free_result($res);
        }
        return $data;
    }

    // Lấy text LoaiDichVu theo MaLoai
    public function getLoaiDichVuTextById(int $maLoai): string
    {
        $sql = "SELECT LoaiDichVu FROM loaidichvu WHERE MaLoai = ? LIMIT 1";
        $stmt = mysqli_prepare($this->con, $sql);
        if (!$stmt) return (string)$maLoai;

        mysqli_stmt_bind_param($stmt, "i", $maLoai);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row    = $result ? mysqli_fetch_assoc($result) : null;
        mysqli_stmt_close($stmt);

        return $row ? $row['LoaiDichVu'] : (string)$maLoai;
    }

    // Lấy các bác sĩ theo Khoa có lịch làm việc trong tương lai
    public function getDoctorsByKhoaWithFutureSchedule(int $maKhoa): array
    {
        $today = date('Y-m-d');

        // Chỉ lấy Bác sĩ thuộc khoa, có ít nhất 1 lịch làm việc 'Đang làm' từ hôm nay trở đi
        $sql = "
            SELECT DISTINCT nv.MaNV, nv.HovaTen
            FROM nhanvien nv
            INNER JOIN bacsi bs ON nv.MaNV = bs.MaNV
            INNER JOIN lichlamviec llv ON llv.MaNV = nv.MaNV
            WHERE bs.MaKhoa = ?
              AND nv.ChucVu = 'Bác sĩ'
              AND llv.TrangThai = 'Đang làm'
              AND llv.NgayLamViec >= ?
            ORDER BY nv.MaNV ASC
        ";

        $stmt = mysqli_prepare($this->con, $sql);
        if (!$stmt) return [];

        mysqli_stmt_bind_param($stmt, "is", $maKhoa, $today);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $data = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
            mysqli_free_result($result);
        }
        mysqli_stmt_close($stmt);

        return $data;
    }

    // Lấy các ngày làm việc tương lai của 1 bác sĩ
    public function getFutureWorkingDates(int $maBS): array
    {
        $today = date('Y-m-d');

        $sql = "
            SELECT DISTINCT NgayLamViec
            FROM lichlamviec
            WHERE MaNV = ?
              AND TrangThai = 'Đang làm'
              AND NgayLamViec >= ?
            ORDER BY NgayLamViec ASC
        ";

        $stmt = mysqli_prepare($this->con, $sql);
        if (!$stmt) return [];

        mysqli_stmt_bind_param($stmt, "is", $maBS, $today);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $dates = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                // NgayLamViec là date (Y-m-d)
                $dates[] = $row['NgayLamViec'];
            }
            mysqli_free_result($result);
        }
        mysqli_stmt_close($stmt);

        return $dates;
    }

    // Chuẩn hóa CaLamViec thành 'Sáng', 'Chiều' hoặc 'Cả ngày'
    private function normalizeShift(?string $raw): ?string
    {
        if ($raw === null) return null;
        $m = mb_strtolower(trim($raw), 'UTF-8');

        // Các biến thể có thể gặp
        if ($m === 'sáng' || $m === 'sang' || $m === 'ca sáng' || $m === 'ca sang') {
            return 'Sáng';
        }
        if ($m === 'chiều' || $m === 'chieu' || $m === 'ca chiều' || $m === 'ca chieu') {
            return 'Chiều';
        }
        if ($m === 'cả ngày' || $m === 'ca ngay' || $m === 'ca cả ngày' || $m === 'ca ca ngay') {
            return 'Cả ngày';
        }
        // Nếu không khớp thì trả về raw để debug
        return $raw;
    }

    // Lấy ca làm việc (Sáng / Chiều / Cả ngày) của BS trong 1 ngày
    private function getShiftForDoctorOnDate(int $maBS, string $date): ?string
    {
        $sql = "
            SELECT CaLamViec
            FROM lichlamviec
            WHERE MaNV = ?
              AND NgayLamViec = ?
              AND TrangThai = 'Đang làm'
        ";
        $stmt = mysqli_prepare($this->con, $sql);
        if (!$stmt) return null;

        mysqli_stmt_bind_param($stmt, "is", $maBS, $date);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $shifts = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $shifts[] = $this->normalizeShift($row['CaLamViec']);
            }
            mysqli_free_result($result);
        }
        mysqli_stmt_close($stmt);

        if (empty($shifts)) return null;

        // Nếu có "Cả ngày" thì ưu tiên
        if (in_array('Cả ngày', $shifts, true)) {
            return 'Cả ngày';
        }

        // Nếu có cả Sáng & Chiều thì xem như Cả ngày
        $hasMorning   = in_array('Sáng', $shifts, true);
        $hasAfternoon = in_array('Chiều', $shifts, true);

        if ($hasMorning && $hasAfternoon) return 'Cả ngày';
        if ($hasMorning) return 'Sáng';
        if ($hasAfternoon) return 'Chiều';

        // Nếu không rõ
        return $shifts[0];
    }

    // Lấy danh sách giờ đã được đặt trong lichkham cho BS, ngày cụ thể
    private function getBookedSlots(int $maBS, string $date): array
    {
        $sql = "
            SELECT TIME_FORMAT(GioKham, '%H:%i') AS GioKham
            FROM lichkham
            WHERE MaBS = ?
              AND NgayKham = ?
              AND TrangThaiThanhToan NOT IN ('', 'Huy')
        ";
        $stmt = mysqli_prepare($this->con, $sql);
        if (!$stmt) return [];

        mysqli_stmt_bind_param($stmt, "is", $maBS, $date);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $slots = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $slots[] = $row['GioKham'];
            }
            mysqli_free_result($result);
        }
        mysqli_stmt_close($stmt);

        return $slots;
    }

    // Lấy danh sách khung giờ trống cho 1 bác sĩ, 1 ngày, dựa trên defaultTimeSlots & lịch đã đặt
    public function getAvailableTimeSlotsForDoctor(int $maBS, string $date, array $defaultTimeSlots): array
    {
        $shift = $this->getShiftForDoctorOnDate($maBS, $date);
        if ($shift === null) {
            return [];
        }

        $booked = $this->getBookedSlots($maBS, $date);

        $today       = date('Y-m-d');
        $currentTime = date('H:i');

        $available = [];

        foreach ($defaultTimeSlots as $slot) {
            // Áp dụng ca làm việc
            $isMorning   = ($slot < '12:00');
            $isAfternoon = ($slot >= '12:00');

            $validByShift = false;
            if ($shift === 'Cả ngày') {
                $validByShift = true;
            } elseif ($shift === 'Sáng' && $isMorning) {
                $validByShift = true;
            } elseif ($shift === 'Chiều' && $isAfternoon) {
                $validByShift = true;
            }

            if (!$validByShift) continue;

            // Loại bỏ giờ đã được đặt
            if (in_array($slot, $booked, true)) {
                continue;
            }

            // Nếu là ngày hôm nay, không cho slot đã qua
            if ($date === $today && $slot <= $currentTime) {
                continue;
            }

            $available[] = $slot;
        }

        return $available;
    }

    // Tạo lịch khám mới trong lichkham
    public function createLichKham(array $data): array
    {
        $sql = "
            INSERT INTO lichkham
                (NgayKham, GioKham, MaBS, TrangThaiThanhToan,
                 MaBN, STT, LoaiDichVu, MaKhoa, TrieuChung)
            VALUES
                (?, ?, ?, ?, ?, NULL, ?, ?, ?)
        ";

        $stmt = mysqli_prepare($this->con, $sql);
        if (!$stmt) {
            return [
                'success' => false,
                'error'   => 'Không chuẩn bị được câu lệnh SQL.'
            ];
        }

        $NgayKham   = $data['NgayKham'];          // Y-m-d
        $GioKham    = $data['GioKham'];           // H:i
        $MaBS       = (int)$data['MaBS'];
        $TrangThai  = $data['TrangThaiThanhToan']; // 'Chua thanh toan'
        $MaBN       = (int)$data['MaBN'];
        $LoaiDichVu = $data['LoaiDichVu'];        // '1','2','3'
        $MaKhoa     = (int)$data['MaKhoa'];
        $TrieuChung = $data['TrieuChung'];

        // SỬA BIND_PARAM 8 THAM SỐ: s,s,i,s,i,s,i,s
        mysqli_stmt_bind_param(
            $stmt,
            "ssisisis",
            $NgayKham,
            $GioKham,
            $MaBS,
            $TrangThai,
            $MaBN,
            $LoaiDichVu,
            $MaKhoa,
            $TrieuChung
        );

        if (!mysqli_stmt_execute($stmt)) {
            $err = mysqli_stmt_error($stmt);
            mysqli_stmt_close($stmt);

            return [
                'success' => false,
                'error'   => $err
            ];
        }

        $newId = mysqli_insert_id($this->con);
        mysqli_stmt_close($stmt);

        return [
            'success' => true,
            'MaLK'    => $newId
        ];
    }
}
