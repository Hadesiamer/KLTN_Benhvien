<?php
// mvc/controllers/Chatbot.php

class Chatbot extends Controller
{
    // Endpoint của Gemini Developer API (generateContent)
    private $apiEndpoint = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent";

    /** @var mChatbotLK */
    private $bookingModel;

    // Các khung giờ mặc định
    private $defaultTimeSlots = [
        "08:00", "09:00", "10:00", "11:00", // Buổi sáng
        "13:00", "14:00", "15:00", "16:00"  // Buổi chiều
    ];

    public function __construct()
    {
        // Nạp file cấu hình Gemini
        require_once "./mvc/core/config_gemini.php";

        // Nạp model đặt lịch qua chatbot
        $this->bookingModel = $this->model("mChatbotLK");
    }

    // Action nhận câu hỏi và trả lời JSON: /KLTN_Benhvien/Chatbot/Ask
    public function Ask()
    {
        // Trả về JSON
        header('Content-Type: application/json; charset=utf-8');

        // Chỉ cho phép POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'error'   => 'Phương thức không được hỗ trợ.'
            ]);
            return;
        }

        // Đảm bảo session tồn tại (đa phần index đã session_start, nhưng thêm cho chắc)
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // --- Khởi tạo lịch sử chat trong session (nếu chưa có) ---
        if (!isset($_SESSION['chat_history']) || !is_array($_SESSION['chat_history'])) {
            $_SESSION['chat_history'] = [];
        }

        // --- Khởi tạo state đặt lịch trong session ---
        if (!isset($_SESSION['booking_flow']) || !is_array($_SESSION['booking_flow'])) {
            $_SESSION['booking_flow'] = null; // null = chưa ở mode đặt lịch
        }

        // Đọc dữ liệu gửi lên
        $rawBody = file_get_contents('php://input');
        $message = '';

        // Ưu tiên JSON: fetch(..., {headers: {'Content-Type':'application/json'}})
        $contentType = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : '';

        if (!empty($rawBody) && strpos($contentType, 'application/json') !== false) {
            $decoded = json_decode($rawBody, true);
            if (json_last_error() === JSON_ERROR_NONE && isset($decoded['message'])) {
                $message = trim($decoded['message']);
            }
        } elseif (isset($_POST['message'])) {
            // Trường hợp gửi kiểu form-urlencoded thông thường
            $message = trim($_POST['message']);
        }

        // Kiểm tra rỗng
        if ($message === '') {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error'   => 'Nội dung câu hỏi trống.'
            ]);
            return;
        }

        // Giới hạn độ dài để tránh spam quá dài
        if (mb_strlen($message, 'UTF-8') > 500) {
            $message = mb_substr($message, 0, 500, 'UTF-8');
        }

        // --- Nếu user muốn HỦY / THOÁT đặt lịch, xử lý ngay ---
        if ($this->isCancelIntent($message)) {
            $_SESSION['booking_flow'] = null;
            $answer = "Tôi đã hủy quy trình đặt lịch hiện tại.\n\n"
                    . "Bạn có thể gõ: \"Đặt lịch khám\" bất kỳ lúc nào nếu muốn bắt đầu lại.";
            $this->appendHistory($message, $answer);
            echo json_encode([
                'success' => true,
                'answer'  => $answer
            ]);
            return;
        }

        // --- Kiểm tra xem đã ở chế độ đặt lịch hay chưa ---
        $bookingFlow = $_SESSION['booking_flow'];

        // Nếu hiện đang ở trong flow đặt lịch: xử lý bằng state machine, không gọi Gemini
        if (is_array($bookingFlow)) {
            $answer = $this->handleBookingFlow($message);
            $this->appendHistory($message, $answer);

            echo json_encode([
                'success' => true,
                'answer'  => $answer
            ]);
            return;
        }

        // Nếu chưa ở flow đặt lịch, kiểm tra xem đây có phải ý định bắt đầu đặt lịch không
        if ($this->isBookingIntent($message)) {
            // Chỉ cho phép bệnh nhân đã đăng nhập
            if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
                $answer = "Để đặt lịch khám qua chat, bạn cần đăng nhập tài khoản bệnh nhân trước.\n\n"
                        . "Bạn vui lòng sử dụng menu Đăng nhập trên website, sau đó quay lại đây gõ: \"Đặt lịch khám\".";
                $this->appendHistory($message, $answer);
                echo json_encode([
                    'success' => true,
                    'answer'  => $answer
                ]);
                return;
            }

            // Kiểm tra xem tài khoản đã có hồ sơ bệnh nhân (MaBN) chưa
            $userId          = (int)$_SESSION['id'];
            $benhNhanRow     = $this->bookingModel->getBenhNhanByUserId($userId);
            $hasCompleteInfo = $benhNhanRow && !empty($benhNhanRow['MaBN']) && (int)$benhNhanRow['MaBN'] > 0;

            if (!$hasCompleteInfo) {
                $answer = "Tài khoản của bạn chưa có hồ sơ bệnh nhân đầy đủ (MaBN, thông tin cá nhân).\n\n"
                        . "Bạn vui lòng vào chức năng tạo/cập nhật hồ sơ bệnh nhân trên website trước, "
                        . "sau đó quay lại đây và gõ: \"Đặt lịch khám\".";
                $this->appendHistory($message, $answer);
                echo json_encode([
                    'success' => true,
                    'answer'  => $answer
                ]);
                return;
            }

            // Khởi tạo flow đặt lịch
            $_SESSION['booking_flow'] = [
                'step' => 'choose_khoa',
                'data' => [
                    'MaBN'          =>(int)$benhNhanRow['MaBN'],
                    'HovaTenBN'     =>$benhNhanRow['HovaTen'],
                    'SoDTBN'        =>$benhNhanRow['SoDT'],
                    'selectedKhoa'  => null,
                    'selectedDV'    => null,
                    'datMode'       => null, // theoBacSi / theoGio (giai đoạn 1 chủ yếu theo bác sĩ)
                    'selectedBS'    => null,
                    'selectedDate'  => null,
                    'selectedTime'  => null,
                    'trieuChung'    => null,
                    'preview'       => []
                ]
            ];

            // Hỏi bước 1: chọn chuyên khoa
            $answer = $this->promptChuyenKhoa();
            $this->appendHistory($message, $answer);

            echo json_encode([
                'success' => true,
                'answer'  => $answer
            ]);
            return;
        }

        // --- Nếu không liên quan đặt lịch → gọi Gemini như cũ ---

        $answer = $this->callGeminiWithHistory($message);
        $this->appendHistory($message, $answer);

        echo json_encode([
            'success' => true,
            'answer'  => $answer
        ]);
    }

    // ==========================
    //  CÁC HÀM PHỤ TRỢ ĐẶT LỊCH
    // ==========================

    // Intent bắt đầu đặt lịch
    private function isBookingIntent(string $message): bool
    {
        $m = mb_strtolower($message, 'UTF-8');
        $keywords = [
            'có, tôi muốn đặt lịch khám',
            'đặt lịch khám',
            'đăng ký khám',
            'đặt khám',
            'đặt lịch bác sĩ',
            'muốn khám',
            'muon kham',
            'dat lich',
            'muốn gặp bác sĩ',
            'gặp bác sĩ',
        ];
        foreach ($keywords as $kw) {
            if (mb_strpos($m, $kw, 0, 'UTF-8') !== false) {
                return true;
            }
        }
        return false;
    }

    // Intent hủy flow đặt lịch
    private function isCancelIntent(string $message): bool
    {
        $m = mb_strtolower($message, 'UTF-8');
        $keywords = ['hủy lịch', 'huy lich', 'thoát', 'thoat', 'dừng lại', 'dung lai', 'hủy đặt', 'huy dat'];
        foreach ($keywords as $kw) {
            if (mb_strpos($m, $kw, 0, 'UTF-8') !== false) {
                return true;
            }
        }
        return false;
    }

    // Lưu hỏi & đáp vào lịch sử chat session
    private function appendHistory(string $userMsg, string $botMsg): void
    {
        $_SESSION['chat_history'][] = [
            'role' => 'user',
            'text' => $userMsg
        ];
        $_SESSION['chat_history'][] = [
            'role' => 'assistant',
            'text' => $botMsg
        ];

        // Giới hạn tối đa 20 message gần nhất
        if (count($_SESSION['chat_history']) > 20) {
            $_SESSION['chat_history'] = array_slice($_SESSION['chat_history'], -20);
        }
    }

    // Hỏi danh sách chuyên khoa
    private function promptChuyenKhoa(): string
    {
        $khoaList = $this->bookingModel->getAllChuyenKhoa();

        if (empty($khoaList)) {
            // Nếu không load được khoa → không thể đặt lịch qua chatbot
            $_SESSION['booking_flow'] = null;
            return "Hiện tại hệ thống không tải được danh sách chuyên khoa, nên tôi chưa hỗ trợ đặt lịch qua chat được.\n\n"
                 . "Bạn vui lòng dùng form Đặt lịch khám trên website giúp tôi.";
        }

        $msg = "Bạn muốn khám ở chuyên khoa nào?\n";

        $i = 1;
        $map = [];
        foreach ($khoaList as $row) {
            $msg .= $i . ". " . $row['TenKhoa'] . " \n\n";
            $map[$i] = $row;
            $i++;
        }

        // Lưu map vào booking_flow
        $_SESSION['booking_flow']['step'] = 'choose_khoa';
        $_SESSION['booking_flow']['data']['khoa_options'] = $map;

        $msg .= "\nBạn có thể trả lời bằng **số thứ tự** (ví dụ: 1, 2, 3) hoặc gõ tên chuyên khoa.";
        $msg .= "\n\n(Để hủy quy trình đặt lịch, bạn có thể gõ: \"Hủy lịch\".)";

        return $msg;
    }

    // Hỏi loại dịch vụ
    private function promptLoaiDichVu(): string
    {
        $dvList = $this->bookingModel->getAllLoaiDichVu();
        if (empty($dvList)) {
            $_SESSION['booking_flow'] = null;
            return "Hiện tại hệ thống không tải được danh sách loại dịch vụ (Khám trong giờ / ngoài giờ / online), "
                 . "nên chưa thể tiếp tục đặt lịch qua chat.\n\n"
                 . "Bạn vui lòng dùng form Đặt lịch khám trên website.";
        }

        $msg = "Bạn muốn đặt loại dịch vụ nào?\n";

        $i = 1;
        $map = [];
        foreach ($dvList as $row) {
            $msg .= $i . ". " . $row['LoaiDichVu'] . "\n";
            $map[$i] = $row;
            $i++;
        }

        $_SESSION['booking_flow']['step'] = 'choose_loaidv';
        $_SESSION['booking_flow']['data']['dv_options'] = $map;

        $msg .= "\nBạn chọn số 1 / 2 / 3.";

        return $msg;
    }

    // Hỏi chọn kiểu đặt lịch (theo bác sĩ / theo giờ) – hiện tại chỉ hỗ trợ Theo Bác sĩ
    private function promptDatMode(): string
    {
        $_SESSION['booking_flow']['step'] = 'choose_mode';

        $msg = "Bạn muốn đặt lịch theo cách nào?\n"
             . "1. Chọn **theo Bác sĩ** (giống trên website)\n"
             . "2. Chọn **theo giờ khám**\n\n"
             . "Hiện tại phiên bản chatbot mới hỗ trợ tốt nhất chế độ **1 – Theo Bác sĩ**.\n"
             . "Bạn vui lòng chọn 1 giúp tôi nhé.";

        return $msg;
    }

    // Hỏi danh sách bác sĩ trong khoa đã chọn
    private function promptBacSi(): string
    {
        $data = $_SESSION['booking_flow']['data'];
        $maKhoa = (int)$data['selectedKhoa']['MaKhoa'];

        // Lấy danh sách bác sĩ theo Khoa có lịch làm việc trong tương lai
        $doctors = $this->bookingModel->getDoctorsByKhoaWithFutureSchedule($maKhoa);

        if (empty($doctors)) {
            $_SESSION['booking_flow'] = null;
            return "Trong chuyên khoa \"" . $data['selectedKhoa']['TenKhoa'] . "\" hiện chưa có Bác sĩ nào có lịch làm việc trong thời gian tới.\n\n"
                 . "Bạn vui lòng thử chọn chuyên khoa khác, hoặc sử dụng form Đặt lịch khám trên website.";
        }

        $msg = "Các Bác sĩ đang làm việc ở chuyên khoa " . $data['selectedKhoa']['TenKhoa'] . ":\n";

        $i = 1;
        $map = [];
        foreach ($doctors as $row) {
            $msg .= $i . ". " . $row['HovaTen'] . " (Mã NV: " . $row['MaNV'] . ")\n";
            $map[$i] = $row;
            $i++;
        }

        $_SESSION['booking_flow']['step'] = 'choose_doctor';
        $_SESSION['booking_flow']['data']['bs_options'] = $map;

        $msg .= "\nBạn muốn chọn Bác sĩ số mấy? (trả lời bằng số: 1, 2, 3, ...)";

        return $msg;
    }

    // Hỏi ngày khám cho bác sĩ đã chọn
    private function promptNgayKham(): string
    {
        $data   = $_SESSION['booking_flow']['data'];
        $maBS   = (int)$data['selectedBS']['MaNV'];
        $dates  = $this->bookingModel->getFutureWorkingDates($maBS);

        if (empty($dates)) {
            $_SESSION['booking_flow'] = null;
            return "Bác sĩ " . $data['selectedBS']['HovaTen'] . " hiện chưa có lịch làm việc trong thời gian tới.\n\n"
                 . "Bạn vui lòng thử chọn lại Bác sĩ khác hoặc sử dụng form Đặt lịch khám trên website.";
        }

        $msg = "Bác sĩ " . $data['selectedBS']['HovaTen'] . " có lịch làm việc vào các ngày:\n";

        $i   = 1;
        $map = [];
        foreach ($dates as $d) {
            $msg .= $i . ". " . $d . "\n"; // d dạng Y-m-d
            $map[$i] = $d;
            $i++;
        }

        $_SESSION['booking_flow']['step'] = 'choose_date';
        $_SESSION['booking_flow']['data']['date_options'] = $map;

        $msg .= "\nBạn chọn ngày số mấy? (ví dụ: 1, 2, 3)";

        return $msg;
    }

    // Hỏi khung giờ khám cho BS + ngày đã chọn
    private function promptGioKham(): string
    {
        $data  = $_SESSION['booking_flow']['data'];
        $maBS  = (int)$data['selectedBS']['MaNV'];
        $date  = $data['selectedDate']; // Y-m-d
        $slots = $this->bookingModel->getAvailableTimeSlotsForDoctor(
            $maBS,
            $date,
            $this->defaultTimeSlots
        );

        if (empty($slots)) {
            $_SESSION['booking_flow']['step'] = 'choose_date';
            return "Xin lỗi, trong ngày " . $date . " Bác sĩ hiện không còn khung giờ trống phù hợp.\n\n"
                 . "Bạn vui lòng chọn lại một ngày khác.";
        }

        $msg = "Ngày " . $date . " Bác sĩ còn các khung giờ sau:\n";

        $i   = 1;
        $map = [];
        foreach ($slots as $time) {
            $msg .= $i . ". " . $time . "\n";
            $map[$i] = $time;
            $i++;
        }

        $_SESSION['booking_flow']['step'] = 'choose_time';
        $_SESSION['booking_flow']['data']['time_options'] = $map;

        $msg .= "\nBạn chọn khung giờ số mấy?";

        return $msg;
    }

    // Hỏi mô tả triệu chứng
    private function promptTrieuChung(): string
    {
        $_SESSION['booking_flow']['step'] = 'enter_trieuchung';

        return "Bạn vui lòng mô tả ngắn gọn triệu chứng chính (tối đa khoảng 60 ký tự) để bác sĩ tiện theo dõi.";
    }

    // Gửi tóm tắt và hỏi xác nhận
    private function promptXacNhan(): string
    {
        $data = $_SESSION['booking_flow']['data'];

        $dvText = $this->bookingModel->getLoaiDichVuTextById(
            (int)$data['selectedDV']['MaLoai']
        );

        $msg = "Bạn xác nhận đặt lịch khám với thông tin sau:\n\n"
             . "- Bệnh nhân: " . $data['HovaTenBN'] . " (Mã BN: " . $data['MaBN'] . ")\n"
             . "- Chuyên khoa: " . $data['selectedKhoa']['TenKhoa'] . "\n"
             . "- Dịch vụ: " . $dvText . " (Mã loại: " . $data['selectedDV']['MaLoai'] . ")\n"
             . "- Bác sĩ: " . $data['selectedBS']['HovaTen'] . " (Mã NV: " . $data['selectedBS']['MaNV'] . ")\n"
             . "- Ngày khám: " . $data['selectedDate'] . "\n"
             . "- Giờ khám: " . $data['selectedTime'] . "\n"
             . "- Triệu chứng: " . $data['trieuChung'] . "\n\n"
             . "Bạn có muốn đặt lịch với thông tin trên không? (trả lời: \"có\" hoặc \"không\")";

        $_SESSION['booking_flow']['step'] = 'confirm';

        return $msg;
    }

    // Xử lý state machine đặt lịch
    private function handleBookingFlow(string $message): string
    {
        $messageTrim = trim($message);
        $lower       = mb_strtolower($messageTrim, 'UTF-8');

        $flow = $_SESSION['booking_flow'];
        if (!is_array($flow) || !isset($flow['step'])) {
            $_SESSION['booking_flow'] = null;
            return "Quy trình đặt lịch hiện tại đã bị lỗi trạng thái, tôi sẽ khởi động lại.\n\n"
                 . "Bạn vui lòng gõ: \"Đặt lịch khám\" để bắt đầu lại giúp tôi.";
        }

        $step = $flow['step'];

        // =========================
        //  BƯỚC: CHỌN CHUYÊN KHOA
        // =========================
        if ($step === 'choose_khoa') {
            $options = $_SESSION['booking_flow']['data']['khoa_options'] ?? [];

            if (empty($options)) {
                // Nếu mất dữ liệu options → hỏi lại từ đầu
                return $this->promptChuyenKhoa();
            }

            $chosen = null;

            // Nếu user nhập số
            if (ctype_digit($messageTrim)) {
                $idx = (int)$messageTrim;
                if (isset($options[$idx])) {
                    $chosen = $options[$idx];
                }
            }

            // Hoặc user nhập tên
            if (!$chosen) {
                foreach ($options as $opt) {
                    if (isset($opt['TenKhoa'])) {
                        if (mb_stripos($opt['TenKhoa'], $messageTrim, 0, 'UTF-8') !== false) {
                            $chosen = $opt;
                            break;
                        }
                    }
                }
            }

            if (!$chosen) {
                return "Xin lỗi, tôi chưa nhận diện được chuyên khoa bạn chọn.\n"
                     . "Bạn vui lòng trả lời lại bằng **số thứ tự** trong danh sách, "
                     . "ví dụ: 1, 2 hoặc 3.";
            }

            // Lưu lại khoa đã chọn
            $_SESSION['booking_flow']['data']['selectedKhoa'] = $chosen;

            // Chuyển sang bước chọn loại dịch vụ
            $msg = "Bạn đã chọn chuyên khoa: " . $chosen['TenKhoa'] . ".\n\n";
            $msg .= $this->promptLoaiDichVu();
            return $msg;
        }

        // =========================
        //  BƯỚC: CHỌN LOẠI DỊCH VỤ
        // =========================
        if ($step === 'choose_loaidv') {
            $options = $_SESSION['booking_flow']['data']['dv_options'] ?? [];
            if (empty($options)) {
                return $this->promptLoaiDichVu();
            }

            $chosen = null;
            if (ctype_digit($messageTrim)) {
                $idx = (int)$messageTrim;
                if (isset($options[$idx])) {
                    $chosen = $options[$idx];
                }
            }

            if (!$chosen) {
                return "Xin lỗi, tôi chưa nhận diện được loại dịch vụ bạn chọn.\n"
                     . "Bạn vui lòng trả lời bằng số 1 / 2 / 3 tương ứng với danh sách vừa rồi.";
            }

            $_SESSION['booking_flow']['data']['selectedDV'] = $chosen;

            $msg = "Bạn đã chọn dịch vụ: " . $chosen['LoaiDichVu'] . ".\n\n";
            // Tiếp theo: chọn mode (theo bác sĩ / theo giờ)
            $msg .= $this->promptDatMode();
            return $msg;
        }

        // =========================
        //  BƯỚC: CHỌN MODE ĐẶT LỊCH
        // =========================
        if ($step === 'choose_mode') {
            // Giai đoạn này CHỈ hỗ trợ 1 (theo Bác sĩ)
            if ($messageTrim === '1') {
                $_SESSION['booking_flow']['data']['datMode'] = 'theoBacSi';
                return $this->promptBacSi();
            }

            if ($messageTrim === '2') {
                return "Hiện tại phiên bản chatbot mới chưa hỗ trợ đầy đủ chế độ \"Đặt theo giờ khám\".\n"
                     . "Bạn vui lòng chọn **1 – Đặt theo Bác sĩ** giúp tôi.";
            }

            return "Bạn vui lòng chọn **1** (Theo Bác sĩ) hoặc **2** (Theo giờ khám).\n"
                 . "Khuyến khích chọn 1 để hệ thống xử lý được đầy đủ.";
        }

        // =========================
        //  BƯỚC: CHỌN BÁC SĨ
        // =========================
        if ($step === 'choose_doctor') {
            $options = $_SESSION['booking_flow']['data']['bs_options'] ?? [];
            if (empty($options)) {
                return $this->promptBacSi();
            }

            $chosen = null;
            if (ctype_digit($messageTrim)) {
                $idx = (int)$messageTrim;
                if (isset($options[$idx])) {
                    $chosen = $options[$idx];
                }
            }

            if (!$chosen) {
                return "Xin lỗi, tôi chưa nhận được lựa chọn Bác sĩ hợp lệ.\n"
                     . "Bạn vui lòng trả lời lại bằng số thứ tự trong danh sách.";
            }

            $_SESSION['booking_flow']['data']['selectedBS'] = $chosen;

            $msg = "Bạn đã chọn Bác sĩ: " . $chosen['HovaTen'] . " (Mã NV: " . $chosen['MaNV'] . ").\n\n";
            $msg .= $this->promptNgayKham();
            return $msg;
        }

        // =========================
        //  BƯỚC: CHỌN NGÀY KHÁM
        // =========================
        if ($step === 'choose_date') {
            $options = $_SESSION['booking_flow']['data']['date_options'] ?? [];
            if (empty($options)) {
                return $this->promptNgayKham();
            }

            $chosenDate = null;
            if (ctype_digit($messageTrim)) {
                $idx = (int)$messageTrim;
                if (isset($options[$idx])) {
                    $chosenDate = $options[$idx];
                }
            }

            if (!$chosenDate) {
                return "Xin lỗi, tôi chưa xác định được ngày bạn chọn.\n"
                     . "Bạn vui lòng trả lời lại bằng số thứ tự trong danh sách.";
            }

            $_SESSION['booking_flow']['data']['selectedDate'] = $chosenDate;

            $msg = "Bạn đã chọn ngày khám: " . $chosenDate . ".\n\n";
            $msg .= $this->promptGioKham();
            return $msg;
        }

        // =========================
        //  BƯỚC: CHỌN GIỜ KHÁM
        // =========================
        if ($step === 'choose_time') {
            $options = $_SESSION['booking_flow']['data']['time_options'] ?? [];
            if (empty($options)) {
                return $this->promptGioKham();
            }

            $chosenTime = null;
            if (ctype_digit($messageTrim)) {
                $idx = (int)$messageTrim;
                if (isset($options[$idx])) {
                    $chosenTime = $options[$idx];
                }
            }

            if (!$chosenTime) {
                return "Xin lỗi, tôi chưa xác định được khung giờ bạn chọn.\n"
                     . "Bạn vui lòng chọn lại bằng số thứ tự trong danh sách.";
            }

            $_SESSION['booking_flow']['data']['selectedTime'] = $chosenTime;

            $msg = "Bạn đã chọn giờ khám: " . $chosenTime . ".\n\n";
            $msg .= $this->promptTrieuChung();
            return $msg;
        }

        // =========================
        //  BƯỚC: NHẬP TRIỆU CHỨNG
        // =========================
        if ($step === 'enter_trieuchung') {
            $trieuChung = mb_substr($messageTrim, 0, 60, 'UTF-8'); // phù hợp cột VARCHAR(60)
            if ($trieuChung === '') {
                return "Bạn vui lòng mô tả ngắn triệu chứng (ví dụ: \"Ho, sốt nhẹ 2 ngày\").";
            }

            $_SESSION['booking_flow']['data']['trieuChung'] = $trieuChung;

            return $this->promptXacNhan();
        }

        // =========================
        //  BƯỚC: XÁC NHẬN CUỐI
        // =========================
        if ($step === 'confirm') {
            if ($lower === 'có' || $lower === 'co' || $lower === 'yes' || $lower === 'ok') {
                // Thực hiện INSERT lịch khám
                $data = $_SESSION['booking_flow']['data'];

                $insertResult = $this->bookingModel->createLichKham([
                    'MaBN'             => (int)$data['MaBN'],
                    'MaBS'             => (int)$data['selectedBS']['MaNV'],
                    'MaKhoa'           => (int)$data['selectedKhoa']['MaKhoa'],
                    'LoaiDichVu'       => (string)$data['selectedDV']['MaLoai'], // lưu đúng 1/2/3
                    'NgayKham'         => $data['selectedDate'],
                    'GioKham'          => $data['selectedTime'],
                    'TrieuChung'       => $data['trieuChung'],
                    'TrangThaiThanhToan' => 'Chua thanh toan'
                ]);

                if ($insertResult['success']) {
                    // Xóa flow sau khi tạo lịch thành công
                    $_SESSION['booking_flow'] = null;

                    $msg = "Tôi đã tạo lịch khám cho bạn thành công.\n\n"
                         . "Mã lịch khám: " . $insertResult['MaLK'] . "\n"
                         . "Bạn vui lòng đến quầy tiếp nhận để hoàn tất thanh toán trước khi khám.\n\n"
                         . "(Sau này khi tích hợp thanh toán Sepay, lịch này sẽ tự động chuyển sang trạng thái \"Đã thanh toán\" nếu bạn thanh toán online.)";

                    return $msg;
                } else {
                    // Có thể bị lỗi trùng slot hoặc lỗi SQL
                    $errorMsg = $insertResult['error'] ?? 'Không xác định';
                    return "Xin lỗi, đã xảy ra lỗi khi tạo lịch khám: " . $errorMsg . "\n\n"
                         . "Có thể khung giờ vừa chọn đã được bệnh nhân khác đặt trước. "
                         . "Bạn vui lòng chọn lại giờ khác hoặc tạo lịch lại giúp tôi.";
                }

            } elseif ($lower === 'không' || $lower === 'khong' || $lower === 'no') {
                $_SESSION['booking_flow'] = null;
                return "Tôi đã không tạo lịch khám nào.\n\n"
                     . "Nếu bạn muốn đặt lại, chỉ cần gõ: \"Đặt lịch khám\".";
            } else {
                return "Bạn vui lòng trả lời \"có\" nếu muốn tạo lịch, hoặc \"không\" nếu muốn hủy.";
            }
        }

        // Nếu rơi vào case không xác định
        $_SESSION['booking_flow'] = null;
        return "Trạng thái đặt lịch hiện tại không hợp lệ. Tôi sẽ kết thúc phiên đặt lịch.\n\n"
             . "Bạn có thể gõ \"Đặt lịch khám\" để bắt đầu lại.";
    }

    // ==========================
    //  HÀM GỌI GEMINI (GIỮ NGUYÊN Ý)
    // ==========================

        // ==========================
    //  HÀM GỌI GEMINI (TỐI ƯU + DEBUG)
    // ==========================

    private function callGeminiWithHistory(string $message): string
    {
        // Persona cố định cho chatbot bệnh viện
        $persona = "Bạn là trợ lý ảo của Hệ thống Bệnh viện Đức Tâm. "
            . "Trả lời ngắn gọn, tối đa 100 từ."
            . "Bạn trả lời bằng tiếng Việt thân thiện, xưng 'tôi' là bệnh viện và gọi người dùng là 'bạn'. "
            . "Bạn không nhắc đến việc mình là mô hình AI, Gemini hay ChatGPT. "
            . "Ngắt đoạn 2 lần khoảng trắng để dễ đọc. ";

        
        //Thông tin bệnh viện
        $hospitalInfo = "Thông tin Bệnh viện Đức Tâm:
        Địa chỉ: Số 12 Nguyễn Văn Bảo, P. Hạnh Thông, Thành phố Hồ Chí Minh
        Hotline: 115
        Giờ làm việc: 8 giờ sáng - 5 giờ chiều tất cả ngày trong tuần
        Khoa chính: Thần kinh, Tim mạch, Nội tiết, ngoại khoa, sản phụ, nhi khoa, mắt, răng hàm mặt, tai mũi họng, da liễu, dinh dưỡng.
        Khi bệnh nhân hỏi các thông tin về địa chỉ, giờ làm, hotline, dịch vụ, hãy trả lời đúng theo dữ liệu trên và không tự bịa.";

        //Xác thực bệnh viện:
        $fictionRule = "Bệnh viện Đức Tâm trong hệ thống này là mô hình giả lập thuộc đồ án tốt nghiệp của Nhật Cường và Kim Nhàn. 
        Nếu bệnh nhân hỏi 'bệnh viện có thật không', hoặc hỏi về tính pháp lý, hãy trả lời rõ ràng rằng đây là mô hình học tập, không phải bệnh viện có thật.";

        // Chuẩn bị lịch sử hội thoại để gửi lên Gemini (lưu trong session)
        $historyText = "";
        $history     = $_SESSION['chat_history'] ?? [];

        // Chỉ lấy tối đa 10 message gần nhất (5 lượt hỏi–đáp) cho gọn
        $maxMessages = 10;
        if (count($history) > $maxMessages) {
            $history = array_slice($history, -$maxMessages);
        }

        foreach ($history as $turn) {
            if (!isset($turn['role'], $turn['text'])) {
                continue;
            }

            if ($turn['role'] === 'user') {
                $historyText .= "Bệnh nhân: " . $turn['text'] . "\n";
            } else {
                $historyText .= "Trợ lý: " . $turn['text'] . "\n";
            }
        }

        // Gộp persona + lịch sử + câu hỏi mới
        $promptText = $persona . "\n\n";
        //Thông tin cố định về bệnh viện
        $promptText .= "Thông tin cố định về bệnh viện:\n"
                    .  $hospitalInfo . "\n\n";
        //Tính chân thật
        $promptText .= "bệnh viện có tồn tại thật không:\n"
                    .  $fictionRule . "\n\n";
        if ($historyText !== "") {
            $promptText .= "Dưới đây là một phần cuộc hội thoại trước giữa bạn và bệnh nhân:\n"
                . $historyText . "\n";
        }
        $promptText .= "Câu hỏi mới nhất của bệnh nhân: " . $message
            . "\n\nHãy trả lời ngắn gọn, dễ đọc, chia đoạn rõ ràng.";

        // Payload gửi lên Gemini Developer API
        $payload = [
            "contents" => [
                [
                    "parts" => [
                        ["text" => $promptText]
                    ]
                ]
            ],
            "generationConfig" => [
                "temperature"     => 0.7,
                "maxOutputTokens" => 512
            ]
        ];

        // Gọi Gemini bằng cURL
        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL            => $this->apiEndpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'x-goog-api-key: ' . GEMINI_API_KEY,
            ],
            CURLOPT_POSTFIELDS     => json_encode($payload),
            CURLOPT_TIMEOUT        => 15, // timeout 15 giây
        ]);

        $result = curl_exec($ch);

        // [DEBUG] Lỗi cURL (không gọi được API)
        if ($result === false) {
            $error = curl_error($ch);
            error_log('[Gemini] cURL error: ' . $error);
            curl_close($ch);

            return "Xin lỗi, hệ thống tư vấn tự động đang gặp sự cố kỹ thuật (lỗi kết nối). "
                 . "Bạn vui lòng thử lại sau hoặc liên hệ trực tiếp bệnh viện.";
        }

        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // [DEBUG] Thử parse JSON
        $responseData = json_decode($result, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log('[Gemini] JSON decode error: ' . json_last_error_msg()
                . ' | RAW: ' . substr($result, 0, 1000));
            return "Xin lỗi, hệ thống tư vấn tự động đang gặp sự cố (lỗi dữ liệu trả về). "
                 . "Bạn vui lòng thử lại sau hoặc liên hệ trực tiếp bệnh viện.";
        }

        // [DEBUG] HTTP status lỗi (4xx, 5xx)
        if ($httpStatus >= 400) {
            $msg = isset($responseData['error']['message'])
                ? $responseData['error']['message']
                : 'Không nhận được phản hồi hợp lệ từ Gemini.';
            error_log('[Gemini] HTTP ' . $httpStatus . ' - ' . $msg
                . ' | RAW: ' . substr($result, 0, 1000));

            return "Xin lỗi, hệ thống tư vấn tự động đang gặp sự cố: " . $msg;
        }

        // [DEBUG] Không có candidates (thường là bị safety chặn)
        if (!isset($responseData['candidates']) || empty($responseData['candidates'])) {
            // Nếu có thông tin safety thì log lại
            if (isset($responseData['promptFeedback']['safetyRatings'])) {
                error_log('[Gemini] No candidates - blocked by safety. Feedback: '
                    . json_encode($responseData['promptFeedback']));
                return "Xin lỗi, nội dung câu hỏi của bạn có thể liên quan đến chủ đề nhạy cảm, "
                     . "nên tôi không thể trả lời chi tiết qua chatbot.\n"
                     . "Bạn vui lòng liên hệ trực tiếp bệnh viện để được tư vấn thêm.";
            }

            error_log('[Gemini] No candidates and no safety feedback. RAW: '
                . substr(json_encode($responseData), 0, 1000));

            return "Xin lỗi, hiện tại tôi chưa thể trả lời câu hỏi này. "
                 . "Bạn vui lòng thử lại sau hoặc liên hệ trực tiếp bệnh viện để được hỗ trợ.";
        }

        $candidate    = $responseData['candidates'][0];
        $finishReason = $candidate['finishReason'] ?? null;

        // [DEBUG] Nếu bị chặn bởi safety ở mức candidate
        if ($finishReason === 'SAFETY' || $finishReason === 'OTHER') {
            error_log('[Gemini] finishReason=' . $finishReason . ' | candidate='
                . substr(json_encode($candidate), 0, 1000));

            return "Xin lỗi, nội dung câu hỏi của bạn liên quan đến chủ đề mà tôi không thể tư vấn chi tiết "
                 . "qua chatbot.\nBạn vui lòng liên hệ trực tiếp bệnh viện để được hỗ trợ an toàn hơn.";
        }

        // Ghép nội dung text trong các parts
        $answer = '';

        if (isset($candidate['content']['parts']) && is_array($candidate['content']['parts'])) {
            foreach ($candidate['content']['parts'] as $part) {
                if (isset($part['text'])) {
                    $answer .= $part['text'];
                }
            }
        }

        // [DEBUG] Nếu vẫn rỗng, ghi log để anh soi
        if ($answer === '') {
            error_log('[Gemini] Empty text in candidate. Full candidate: '
                . substr(json_encode($candidate), 0, 1000));

            $answer = "Xin lỗi, hiện tại tôi chưa thể trả lời câu hỏi này. "
                . "Bạn vui lòng thử lại sau hoặc liên hệ trực tiếp bệnh viện để được hỗ trợ.";
        }

        return $answer;
    }

}
