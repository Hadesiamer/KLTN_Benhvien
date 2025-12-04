<?php
// mvc/controllers/Chatbot.php

class Chatbot extends Controller
{
    // Endpoint của Gemini Developer API (generateContent)
    private $apiEndpoint = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent";

    public function __construct()
    {
        // Nạp file cấu hình Gemini
        require_once "./mvc/core/config_gemini.php";
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

        // --- Khởi tạo lịch sử chat trong session (nếu chưa có) ---
        if (!isset($_SESSION['chat_history']) || !is_array($_SESSION['chat_history'])) {
            $_SESSION['chat_history'] = [];
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

        // Persona cố định cho chatbot bệnh viện
        $persona = "Bạn là trợ lý ảo của Hệ thống Bệnh viện Đức Tâm. "
            . "Bạn trả lời bằng tiếng Việt thân thiện, xưng 'tôi' là bệnh viện và gọi người dùng là 'bạn'. "
            . "Bạn không nhắc đến việc mình là mô hình AI, Gemini hay ChatGPT. "
            . "Nhiệm vụ chính của bạn là giải thích thông tin về bệnh viện, các khoa, quy trình đăng ký khám"
            . "thanh toán và các câu hỏi chung. "
            . "Bạn không tự tạo lịch khám hay thay đổi dữ liệu trong hệ thống. "
            . "Nếu người dùng hỏi về đặt lịch khám, hãy hướng dẫn họ sử dụng chức năng Đặt lịch khám trên website."
            . "Nếu câu hỏi vượt quá phạm vi bệnh viện, hãy lịch sự từ chối và khuyên họ liên hệ trực tiếp bệnh viện."
            . "Nếu người dùng bị bệnh bất kỳ, hãy tư vấn triệu chứng đó là bệnh gì, và khuyên họ đến gặp bác sĩ chuyên môn bệnh viện Đức Tâm để được tư vấn."
            . "Khi tư vấn, hãy thường xuyên ngắt đoạn để dễ đọc."
        ;

        // --- Chuẩn bị lịch sử hội thoại để gửi lên Gemini (lưu trong session) ---
        $historyText = "";
        $history = $_SESSION['chat_history'];

        // Chỉ lấy tối đa 10 message gần nhất (5 lượt hỏi–đáp) cho gọn
        $maxMessages = 10;
        if (count($history) > $maxMessages) {
            $history = array_slice($history, -$maxMessages);
        }

        foreach ($history as $turn) {
            // Mỗi turn: ['role' => 'user'|'assistant', 'text' => '...']
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
            // Cấu hình sinh nội dung (có thể chỉnh tuỳ ý)
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

        if ($result === false) {
            $error = curl_error($ch);
            curl_close($ch);

            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error'   => 'Lỗi khi gọi Gemini: ' . $error
            ]);
            return;
        }

        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $responseData = json_decode($result, true);

        // Handle lỗi HTTP từ Gemini
        if ($httpStatus >= 400) {
            $msg = isset($responseData['error']['message'])
                ? $responseData['error']['message']
                : 'Không nhận được phản hồi hợp lệ từ Gemini.';

            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error'   => 'Lỗi từ Gemini: ' . $msg
            ]);
            return;
        }

        // Lấy text trả về
        $answer = '';

        if (
            isset($responseData['candidates'][0]['content']['parts'][0]['text'])
        ) {
            $answer = $responseData['candidates'][0]['content']['parts'][0]['text'];
        } elseif (isset($responseData['candidates'][0]['content']['parts'])) {
            foreach ($responseData['candidates'][0]['content']['parts'] as $part) {
                if (isset($part['text'])) {
                    $answer .= $part['text'];
                }
            }
        }

        if ($answer === '') {
            $answer = "Xin lỗi, hiện tại tôi chưa thể trả lời câu hỏi này. "
                . "Bạn vui lòng thử lại sau hoặc liên hệ trực tiếp bệnh viện để được hỗ trợ.";
        }

        // --- Lưu câu hỏi & câu trả lời vào lịch sử session ---
        $_SESSION['chat_history'][] = [
            'role' => 'user',
            'text' => $message
        ];

        $_SESSION['chat_history'][] = [
            'role' => 'assistant',
            'text' => $answer
        ];

        // Giới hạn tổng lịch sử còn tối đa 20 message để tránh phình session
        if (count($_SESSION['chat_history']) > 20) {
            $_SESSION['chat_history'] = array_slice($_SESSION['chat_history'], -20);
        }
        // --- Kết thúc lưu lịch sử chat ---

        echo json_encode([
            'success' => true,
            'answer'  => $answer
        ]);
    }
}
