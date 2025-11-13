<?php
// Bật lỗi
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// >>> THÊM LOGIC KIỂM TRA SESSION VÀ ĐĂNG NHẬP
if (session_status() === PHP_SESSION_NONE) {
    session_start(); 
}
// KHÔNG CẦN header('Access-Control-Allow-Origin: *'); vì request từ cùng domain

$response = ['success' => false, 'message' => 'Lỗi không xác định.'];

// 1. Nhận dữ liệu JSON từ request
$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

// Thiết lập Content-Type header chỉ khi nó là request POST AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Đảm bảo không có output nào trước đó. Nếu vẫn gặp lỗi header, hãy bật ob_start() ở file gốc.
    if (!headers_sent()) {
        header('Content-Type: application/json; charset=utf-8');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $data) {
    
    // 2. Thiết lập kết nối cơ sở dữ liệu (THAY THẾ THÔNG TIN NÀY)
    $servername = "localhost"; 
    $username = "root"; 
    $password = ""; 
    $dbname = "domdom"; 

    // Tạo kết nối
    $conn = new mysqli($servername, $username, $password, $dbname);
    $conn->set_charset("utf8"); // Thiết lập charset cho kết nối

    // Kiểm tra kết nối
    if ($conn->connect_error) {
        $response['message'] = "Lỗi kết nối CSDL: " . $conn->connect_error;
        echo json_encode($response);
        exit();
    }
    
    // 3. Chuẩn bị dữ liệu và KIỂM TRA ĐĂNG KÝ
    
    $maKH = $data['MaKH'] ?? null; 
    $maBS = $data['maBS'];
    $maCK = $data['maCK'];
    $maDV = $data['maDV'];
    $ngayKham = $data['ngayKham'];
    $gioKham = $data['gioKham'];
    $trieuChung = $data['TrieuChung'];
    $trangThai = 'Chưa thanh toán'; // Mặc định
    
    // *** LOGIC KIỂM TRA BẮT BUỘC ĐĂNG NHẬP ***
    if (empty($maKH) || !is_numeric($maKH) || $maKH <= 0) {
        $response['message'] = "Yêu cầu phải được gửi từ tài khoản đã đăng nhập và có Mã Khách hàng (MaKH) hợp lệ.";
        echo json_encode($response);
        $conn->close();
        exit();
    }
    // ****************************************
    
    // 4. Thực hiện truy vấn INSERT
    // Chèn dữ liệu vào bảng lichkham
    $stmt = $conn->prepare("INSERT INTO lichkham (MaBN, MaBS, MaKhoa, LoaiDichVu, NgayKham, GioKham, TrieuChung, TrangThaiThanhToan) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    
    // "i" là integer cho MaBN (MaKH)
    $stmt->bind_param("isssssss", $maKH, $maBS, $maCK, $maDV, $ngayKham, $gioKham, $trieuChung, $trangThai);

    if ($stmt->execute()) {
        $last_id = $conn->insert_id; // Lấy ID vừa được tạo (MaLH)
        $response['success'] = true;
        $response['message'] = "Đặt lịch thành công.";
        $response['MaLH'] = $last_id; 
    } else {
        $response['message'] = "Lỗi SQL: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    
    // Thoát khỏi script sau khi trả về JSON
    echo json_encode($response);
    exit(); 
    
} else {
    // Chỉ trả về lỗi JSON nếu là request POST, không phải request GET (tải trang)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Lỗi này chỉ ra request không phải là POST hoặc JSON không hợp lệ
        $response['message'] = "Yêu cầu API không hợp lệ hoặc thiếu dữ liệu.";
        echo json_encode($response);
        exit();
    }
}

// Kết nối CSDL
$conn = new mysqli("localhost", "root", "", "domdom");
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ********** LOGIC KIỂM TRA ĐĂNG NHẬP (QUAN TRỌNG) **********
$loginPageUrl = '/KLTN_Benhvien/login'; 
$registerPageUrl = '/KLTN_Benhvien/Register';

// Kiểm tra xem người dùng đã đăng nhập chưa
$isLoggedIn = isset($_SESSION['idbn']) && !empty($_SESSION['idbn']); 
//Lấy thông tin tài khỏan đã đăng nhập
if ($isLoggedIn) {
    $userId = $_SESSION['id'];
    $loggedInUserData = null;
    $userQuery = $conn->prepare("SELECT * FROM benhnhan WHERE ID = ?");
    $userQuery->bind_param("i", $userId);
    $userQuery->execute();
    $userResult = $userQuery->get_result();
    if ($userResult && $userResult->num_rows > 0) {
        $loggedInUserData = $userResult->fetch_assoc();
    }
    $userQuery->close();
}



// --- TẢI DỮ LIỆU KHOA (CHUYÊN KHOA) ---
$khoalist = [];
$ck_query = $conn->query("SELECT * FROM chuyenkhoa"); 
if ($ck_query) {
    $khoalist = $ck_query->fetch_all(MYSQLI_ASSOC);
}

// Lấy danh sách dịch vụ
$dv = $conn->query("SELECT * FROM loaidichvu");
$dichvulist = $dv->fetch_all(MYSQLI_ASSOC);

// Biến chuyên khoa 
$chuyenKhoa = $_REQUEST['chuyenKhoa'] ?? '';

// Lấy danh sách bác sĩ nếu có chọn chuyên khoa 
$bacsilist = [];
if ($chuyenKhoa !== '') {
    $sql = "SELECT nhanvien.MaNV, nhanvien.HovaTen 
            FROM nhanvien 
            INNER JOIN bacsi ON nhanvien.MaNV = bacsi.MaNV 
            WHERE ChucVu = 'Bác sĩ' AND MaKhoa = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $chuyenKhoa);
    $stmt->execute();
    $res = $stmt->get_result();
    $bacsilist = $res->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

// Lấy tất cả bác sĩ để JS dùng
$sqlAllBS = "SELECT nhanvien.MaNV, nhanvien.HovaTen, bacsi.MaKhoa 
             FROM nhanvien 
             INNER JOIN bacsi ON nhanvien.MaNV = bacsi.MaNV 
             WHERE ChucVu = 'Bác sĩ'";
$resAllBS = $conn->query($sqlAllBS);
$bacsiall = $resAllBS->fetch_all(MYSQLI_ASSOC);

// --- TẢI DỮ LIỆU LỊCH LÀM VIỆC ---
$sqlLich = "SELECT DATE_FORMAT(NgayLamViec, '%Y-%m-%d') as NgayKham, MaNV, CaLamViec 
            FROM lichlamviec 
            WHERE NgayLamViec >= CURDATE() AND TrangThai = 'Đang làm'";
$resLich = $conn->query($sqlLich);

$lichlamviecAll = [];
if ($resLich) {
    while($row = $resLich->fetch_assoc()) {
        $maNV = $row['MaNV'];
        $ngayKham = $row['NgayKham'];
        
        if (!isset($lichlamviecAll[$maNV])) {
            $lichlamviecAll[$maNV] = [];
        }
        $lichlamviecAll[$maNV][$ngayKham] = $row['CaLamViec'];
    }
}


// --- TẢI DỮ LIỆU GIỜ VÀ LỊCH HẸN ĐÃ ĐẶT  ---
$defaultTimeSlots = [
    "08:00", "09:00", "10:00", "11:00", 
    "13:00", "14:00", "15:00", "16:00"
];

$sqlBooked = "SELECT MaBS, DATE_FORMAT(NgayKham, '%Y-%m-%d') as NgayKham, 
                     TIME_FORMAT(GioKham, '%H:%i') as GioKham
              FROM lichkham 
              WHERE NgayKham >= CURDATE() AND TrangThaiThanhToan NOT IN ('Hủy', 'Chưa xác nhận')"; 
$resBooked = $conn->query($sqlBooked);

$bookedSlotsAll = [];
if ($resBooked) {
    while($row = $resBooked->fetch_assoc()) {
        $maNV = $row['MaBS'];
        $ngayKham = $row['NgayKham'];
        
        if (!isset($bookedSlotsAll[$maNV])) {
            $bookedSlotsAll[$maNV] = [];
        }
        if (!isset($bookedSlotsAll[$maNV][$ngayKham])) {
            $bookedSlotsAll[$maNV][$ngayKham] = [];
        }
        $bookedSlotsAll[$maNV][$ngayKham][] = $row['GioKham'];
    }
}

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lịch khám bệnh</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="public/css/formDK.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/vn.js"></script>
    <style>
/* --- Khắc phục lỗi không click được vào Input Ngày Khám (#NgayKham) --- */
#NgayKham {
    position: relative; 
    z-index: 99999; 
}

/* --- Đảm bảo bộ lịch Flatpickr hiển thị trên các Modal --- */
.flatpickr-calendar {
    /* Cần cao hơn modal-overlay (z-index: 9999) */
    z-index: 100000 !important; 
}

/* ========================================= */
/* === TÙY CHỈNH CSS CHO MODAL XÁC NHẬN TÀI KHOẢN (#AuthDecisionModal) === */
/* ========================================= */

#AuthDecisionModal .modal-content {
    max-width: 450px;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    /* Giống màu nền modal chọn giờ */
    background-color: #f8faff; 
    border-top: 5px solid #007bff;
}

#AuthDecisionModal h2 {
    font-size: 1.5em;
    color: #007bff;
    margin-bottom: 15px;
}

#AuthDecisionModal p {
    color: #555;
    margin-bottom: 25px;
    font-size: 1em;
}

.login-modal-buttons {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.login-modal-buttons .btn-login,
.login-modal-buttons .btn-register,
#btnContinueAsGuest {
    display: block;
    padding: 12px 15px;
    text-align: center;
    text-decoration: none;
    border-radius: 6px;
    font-weight: bold;
    transition: background-color 0.3s, color 0.3s, border-color 0.3s;
    font-size: 1em;
    border: none;
    cursor: pointer;
}

.login-modal-buttons .btn-login {
    background-color: #007bff;
    color: white;
}

.login-modal-buttons .btn-login:hover {
    background-color: #0056b3;
}

.login-modal-buttons .btn-register {
    background-color: #28a745; 
    color: white;
}

.login-modal-buttons .btn-register:hover {
    background-color: #1e7e34;
}

/* Nút Tiếp tục với tư cách khách */
#btnContinueAsGuest {
    margin-top: 15px;
    background-color: #6c757d; 
    color: white;
}

#btnContinueAsGuest:hover {
    background-color: #5a6268;
}

/* Tùy chỉnh icon */
#AuthDecisionModal .fa-lock {
    margin-right: 8px;
}

    </style>

</head>
<body>
<div class="main-booking-area">
    <div class="booking-form-wrapper" id="booking-step-1">
        <form id="form-step-1">
            <h2 style="text-align: center">Đăng ký khám bệnh</h2>
            <div class="form-group">
                <label>Chuyên khoa</label>
                <select name="chuyenKhoa" id="chuyenKhoa">
                    <option value="">Chọn chuyên khoa</option>
                    <?php 
                    foreach ($khoalist as $khoa): ?>
                        <option value="<?= htmlspecialchars($khoa['MaKhoa']) ?>">
                            <?= htmlspecialchars($khoa['TenKhoa']) ?>
                        </option>
                    <?php endforeach; 
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label>Dịch vụ</label>
                <input type="hidden" name="MaDV" id="MaDV_Hidden" value=""> 
                
                <div id="DichVuSelection" class="service-selection-buttons">
                    <?php 
                    foreach ($dichvulist as $dichvu): 
                        $maLoai = htmlspecialchars($dichvu['MaLoai']);
                        $loaiDV = htmlspecialchars($dichvu['LoaiDichVu']);
                    ?>
                        <button type="button" class="service-button" data-dv-id="<?= $maLoai ?>">
                            <?= $loaiDV ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="form-group">
                <label>Đặt lịch</label>
                <select name="datLich" id="datLich" disabled> 
                <option value="theoBacSi">Theo bác sĩ</option>
                    <option value="theoGioKham">Theo giờ khám</option>
                </select>
            </div>

            <div class="form-group" id="bacsi-group">
                <label>Bác sĩ</label>
                <select name="MaBS" id="MaBS">
                    <option value="">Chọn bác sĩ</option>
                    <?php foreach($bacsilist as $bacsi): ?>
                        <option value="<?= htmlspecialchars($bacsi['MaNV']) ?>">
                            <?= htmlspecialchars($bacsi['HovaTen']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Ngày khám & Khung giờ</label>
                <input type="text" id="NgayKham" placeholder="Chọn ngày khám" readonly>
                
                <input type="hidden" name="NgayKham" id="NgayKhamHidden">
                <input type="hidden" name="GioKham" id="GioKhamHidden">
                
                <div id="lich-error-msg" style="color: red; font-size: 0.9em; margin-top: 5px;"></div>
            </div>

            <div id="doctor-selection-display" class="doctor-display-box" style="display: none; padding: 10px; border: 1px solid #007bff; border-left: 5px solid #007bff; margin-top: 10px; background-color: #e9f5ff; border-radius: 4px;">
                <p style="font-weight: bold; margin-bottom: 5px; color: #007bff;"><i class="fas fa-user-md"></i> Bác sĩ được chọn:</p>
                <div id="selected-doctor-info" style="font-size: 0.95em;"></div>
            </div>
            
        </form>
        <div class="form-group" style="text-align: center; margin-top: 20px;">
            <button type="button" id="btnSubmitBooking" class="submit-button" disabled>Tiếp tục</button>
        </div>
    </div>
    <div class="booking-form-wrapper" id="booking-step-2" style="display: none;">
        <form id="form-step-2" onsubmit="event.preventDefault(); handleBookingFinalStep();">
            <h2 style="text-align: center">Mô tả Triệu chứng</h2>
            <p>Vui lòng cung cấp chi tiết về vấn đề sức khỏe của bạn.</p>

            <div class="form-group">
                <label for="TrieuChungKH">Triệu chứng (*)</label>
                <textarea id="TrieuChungKH" name="TrieuChungKH" rows="5" required style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;"></textarea>
            </div>

            <div class="form-group" style="text-align: center; margin-top: 30px;">
                <button type="button" onclick="goToPreviousStep()" class="submit-button" style="background-color: #6c757d; color: white;">Quay lại</button>
                <button type="submit" id="btnCompleteBooking" class="submit-button" style="background-color: #28a745; color: white;">Hoàn tất Đăng ký</button>
            </div>
        </form>
    </div>
    
    <div id="GioKhamSelectionPanel" class="modal-overlay">
        <div class="modal-content">
            <span class="close-button close-time-modal">&times;</span>
            <h2 style="font-size: 1.2em; margin-top: 0;">Chọn Khung Giờ Khám</h2>
            <p id="modal-date-display" style="font-weight: bold; margin-bottom: 10px; font-size: 0.9em;"></p>
            
            <div class="form-group" id="khunggio-group">
                <div class="time-slot-tabs">
                    <div class="tab-header">
                        <div class="tab-button active" data-time-group="sang">
                            <i class="fas fa-sun"></i> Sáng
                        </div>
                        <div class="tab-button" data-time-group="chieu">
                            <i class="fas fa-cloud"></i> Chiều
                        </div>
                    </div>
                    
                    <div id="GioKhamContainer" class="time-slot-container">
                        </div>
                </div>
            </div>
            
            <div id="gio-error-msg" style="color: red; font-size: 0.9em; margin-top: 5px;"></div>
        </div>
    </div>
    
    <div id="AuthDecisionModal" class="modal-overlay">
        <div class="modal-content">
            <span class="close-button close-auth-modal">&times;</span>
            <h2 style="text-align: center;"><i class="fas fa-lock"></i> Xác nhận Tài khoản</h2>
            <p style="text-align: center;">Vui lòng Đăng nhập hoặc Đăng ký để hoàn tất lịch khám. Nếu không, bạn có thể chọn **Tiếp tục với tư cách Khách**:</p>
            <div class="login-modal-buttons">
                <a href="<?= $loginPageUrl ?>" class="btn-login"><i class="fas fa-sign-in-alt"></i> **Đã có tài khoản** - Đăng nhập</a>
                <a href="<?= $registerPageUrl ?>" class="btn-register"><i class="fas fa-user-plus"></i> **Chưa có tài khoản** - Đăng ký ngay</a>
            </div>
        </div>
    </div>
    
</div>
<script>
// Dữ liệu từ PHP
const bacsiData = <?= json_encode($bacsiall) ?>;
const lichLamViecData = <?= json_encode($lichlamviecAll) ?>; 
const defaultTimeSlots = <?= json_encode($defaultTimeSlots) ?>;
const bookedSlotsData = <?= json_encode($bookedSlotsAll) ?>; 
const isLoggedIn = <?= json_encode($isLoggedIn) ?>; // Biến kiểm tra Đăng nhập
const userData = <?= json_encode($loggedInUserData) ?>;


</script>
<script src="public/js/xuly_formDK.js"></script>

</body>
</html>

<?php
$conn->close();
?>