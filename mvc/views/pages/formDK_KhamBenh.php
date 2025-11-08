<?php
// Bật lỗi
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// >>> THÊM LOGIC KIỂM TRA SESSION VÀ ĐĂNG NHẬP
if (session_status() === PHP_SESSION_NONE) {
    session_start(); 
}

// Kết nối CSDL
$conn = new mysqli("localhost", "root", "", "domdom");
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ********** LOGIC KIỂM TRA ĐĂNG NHẬP (QUAN TRỌNG) **********
// THAY ĐỔI ĐƯỜNG DẪN NÀY CHO PHÙ HỢP VỚI HỆ THỐNG CỦA BẠN
$loginPageUrl = '/KLTN_Benhvien/login'; // <<< ĐÃ SỬA: Dùng 'login' theo yêu cầu
$registerPageUrl = '/KLTN_Benhvien/Register'; // Giữ nguyên 'dangky'

// Kiểm tra xem người dùng đã đăng nhập chưa
$isLoggedIn = isset($_SESSION['user']) && !empty($_SESSION['user']); 
$loggedInUserData = $isLoggedIn ? $_SESSION['user'] : [
    // Dữ liệu mẫu nếu cần
    'MaKH' => null, // Sửa thành null để phù hợp với Khách lẻ nếu không đăng nhập
    'HoTen' => '', 
    'SDT' => '',
    'Email' => ''
];
// ********** KẾT THÚC LOGIC ĐĂNG NHẬP **********


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
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/vn.js"></script>
    
<style>
/* CSS CƠ BẢN */
body { min-height: 100vh; font-family: sans-serif; }
.main-booking-area { display: block; width: 95%; max-width: 500px; margin: 20px auto; gap: 30px; }
.booking-form-wrapper { width: 100%; padding: 20px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; flex-shrink: 0; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); background-color: white; }
.booking-form-wrapper .form-group { margin-bottom: 15px; }
.booking-form-wrapper label { display: block; margin-bottom: 5px; font-weight: bold; color: #333; }
.booking-form-wrapper input, .booking-form-wrapper select, .booking-form-wrapper textarea { 
    width: 100%; padding: 8px; box-sizing: border-box; 
    border: 1px solid #ccc; border-radius: 4px; 
    background-color: white; /* Đảm bảo input cũng có nền trắng */
}
.booking-form-wrapper select { height: 35px; }
/* CSS NÚT SUBMIT */
.submit-button { padding: 10px 20px; border: none; border-radius: 4px; font-size: 1.1em; cursor: pointer; transition: background-color 0.3s; background-color: #ccc; color: #666; }
.submit-button:not(:disabled):hover { background-color: #0056b3; color: white; }

/* CSS MODAL CHUNG (ĐÃ CHỈNH SỬA) */
.modal-overlay { 
    display: none; position: fixed; z-index: 9999; left: 0; top: 0; 
    width: 100%; height: 100%; overflow: auto; 
    background-color: rgba(0, 0, 0, 0.6); 
    justify-content: center; align-items: center; 
    backdrop-filter: blur(2px);
}
.modal-content { 
    background-color: **white**; /* ĐẢM BẢO NỀN TRẮNG CHO TẤT CẢ MODAL */
    padding: 30px; 
    border: none;
    width: 90%; max-width: 480px; 
    border-radius: 12px; 
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3); 
    animation-name: animatetop; animation-duration: 0.4s;
    position: relative;
}
@keyframes animatetop { from {opacity: 0; transform: translateY(-50px);} to {opacity: 1; transform: translateY(0);} }
.close-button { 
    color: #aaa; 
    position: absolute; 
    top: 10px; right: 15px; 
    font-size: 32px; 
    font-weight: 300; 
    cursor: pointer; 
    line-height: 1; 
    transition: color 0.2s;
}
.close-button:hover, .close-button:focus { color: #343a40; text-decoration: none; }

/* CSS cho Modal Chọn Giờ (ĐÃ TÙY CHỈNH THEO YÊU CẦU NỀN TRẮNG) */
#GioKhamSelectionPanel .modal-content { 
    border: 2px solid #007bff; /* Giữ border nổi bật */
    background-color: white; /* Đảm bảo nền trắng */
}
.time-slot-tabs { 
    border: 1px solid #007bff; 
    border-radius: 8px; 
    overflow: hidden; 
    margin-top: 15px; 
    background-color: white; /* Đảm bảo khu vực này cũng nền trắng */
}
.tab-header { display: flex; border-bottom: 1px solid #007bff; }
.tab-button { 
    flex-grow: 1; text-align: center; padding: 10px 0; 
    cursor: pointer; 
    background-color: **white**; /* Nền trắng */
    border-right: 1px solid #007bff; 
    font-size: 0.95em; color: #007bff; 
    transition: all 0.2s; 
    font-weight: 500;
}
.tab-button:last-child { border-right: none; }
.tab-button.active { 
    background-color: #007bff; 
    color: white; 
    font-weight: bold; 
    border-bottom: 2px solid #007bff;
}
.tab-button.disabled-tab { opacity: 0.5; cursor: not-allowed; pointer-events: none; background-color: #f1f1f1; color: #6c757d; }
#GioKhamContainer { 
    padding: 15px; 
    display: flex; flex-wrap: wrap; gap: 10px; 
    min-height: 40px; justify-content: flex-start;
    background-color: **white**; /* Nền trắng */
}
.time-slot { 
    padding: 10px 15px; 
    min-width: 75px; 
    text-align: center; 
    border: 1px solid #ced4da; 
    border-radius: 50px; 
    cursor: pointer; 
    background-color: **white**; /* Nền trắng */
    transition: all 0.2s ease; 
    font-size: 0.9em; 
    user-select: none; 
    display: none;
    color: #495057;
}
.time-slot:hover {
    background-color: #e9ecef;
}
.time-slot.selected { 
    background-color: #28a745; 
    color: white; 
    border-color: #28a745; 
    font-weight: bold; 
    box-shadow: 0 2px 5px rgba(40, 167, 69, 0.4);
}

/* CSS cho Modal Đăng nhập/Đăng ký */
.login-modal-buttons {
    display: flex;
    flex-direction: column;
    gap: 15px;
    margin-top: 20px;
}
.login-modal-buttons button, .login-modal-buttons a {
    padding: 12px;
    font-size: 1.1em;
    border-radius: 6px;
    text-align: center;
    text-decoration: none;
    cursor: pointer;
    transition: background-color 0.3s;
    border: 1px solid transparent;
}
.btn-login {
    background-color: #007bff;
    color: white;
}
.btn-login:hover {
    background-color: #0056b3;
}
.btn-register {
    background-color: #28a745;
    color: white;
}
.btn-register:hover {
    background-color: #1e7e34;
}
.btn-continue-as-guest {
    background-color: #f8f9fa;
    color: #343a40;
    border-color: #ccc;
}
.btn-continue-as-guest:hover {
    background-color: #e2e6ea;
}
</style>
</head>
<body>
<div class="main-booking-area">
    
    <div class="booking-form-wrapper" id="booking-step-1">
        <form id="form-step-1">
            <h2 style="text-align: center">Bước 1: Chọn Lịch Khám</h2>
            <p>Chọn chuyên khoa, bác sĩ và thời gian khám.</p>

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
                <select name="MaDV" id="MaDV">
                    <option value="">Chọn dịch vụ</option>
                    <?php foreach($dichvulist as $dichvu): ?>
                        <option value="<?= htmlspecialchars($dichvu['MaLoai']) ?>">
                            <?= htmlspecialchars($dichvu['LoaiDichVu']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div> 

            <div class="form-group">
                <label>Đặt lịch</label>
                <select name="datLich" id="datLich" disabled onchange="toggleBacSi()">
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
            
        </form>
        <div class="form-group" style="text-align: center; margin-top: 20px;">
            <button type="button" id="btnSubmitBooking" class="submit-button" disabled>Tiếp tục</button>
        </div>
    </div>
    <div class="booking-form-wrapper" id="booking-step-2" style="display: none;">
        <form id="form-step-2" onsubmit="event.preventDefault(); handleBookingFinalStep();">
            <h2 style="text-align: center">Bước 2: Mô tả Triệu chứng</h2>
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
            <h2 style="text-align: center; font-size: 1.4em; color: #007bff;"><i class="fas fa-lock"></i> Xác nhận Tài khoản</h2>
            <p style="text-align: center;">Vui lòng Đăng nhập hoặc Đăng ký để hoàn tất lịch khám. Nếu không, bạn có thể **Tiếp tục với tư cách Khách**:</p>
            <div class="login-modal-buttons">
                <a href="<?= $loginPageUrl ?>" class="btn-login"><i class="fas fa-sign-in-alt"></i> **Đã có tài khoản** - Đăng nhập</a>
                <a href="<?= $registerPageUrl ?>" class="btn-register"><i class="fas fa-user-plus"></i> **Chưa có tài khoản** - Đăng ký ngay</a>
                <button type="button" id="btnContinueAsGuest" class="btn-continue-as-guest"><i class="fas fa-user"></i> Tiếp tục với tư cách Khách</button>
            </div>
        </div>
    </div>

    <div id="GuestInfoModal" class="modal-overlay">
        <div class="modal-content">
            <span class="close-button close-guest-modal">&times;</span>
            <h2 style="text-align: center; font-size: 1.4em; color: #28a745;"><i class="fas fa-info-circle"></i> Thông tin Khách hàng</h2>
            <p style="text-align: center; font-size: 0.9em; color: #6c757d;">Vui lòng điền thông tin để đặt lịch. Thông tin sẽ không được lưu vào hệ thống tài khoản.</p>
            <form id="form-guest-info">
                <div class="form-group">
                    <label for="guestHoTenKH">Họ và Tên (*)</label>
                    <input type="text" id="guestHoTenKH" required>
                </div>
                <div class="form-group">
                    <label for="guestSDTKH">Số Điện Thoại (*)</label>
                    <input type="tel" id="guestSDTKH" required>
                </div>
                <div class="form-group">
                    <label for="guestEmailKH">Email (Tùy chọn)</label>
                    <input type="email" id="guestEmailKH">
                </div>
                <div class="form-group" style="text-align: center; margin-top: 20px;">
                    <button type="submit" class="submit-button" style="background-color: #28a745; color: white;">Xác nhận & Đặt lịch</button>
                </div>
            </form>
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

// Biến DOM 
const chuyenKhoaSel = document.getElementById("chuyenKhoa");
const dichvuSel = document.getElementById("MaDV");
const datLichSel = document.getElementById("datLich");
const bacsiSel = document.getElementById("MaBS");
const ngayKhamInput = document.getElementById("NgayKham"); 
const ngayKhamHidden = document.getElementById("NgayKhamHidden"); 
const gioKhamHidden = document.getElementById("GioKhamHidden");
const errorMsgDiv = document.getElementById("lich-error-msg");
const btnSubmit = document.getElementById("btnSubmitBooking");
const trieuChungInput = document.getElementById('TrieuChungKH');

// DOM MODAL CHỌN GIỜ
const panel = document.getElementById("GioKhamSelectionPanel"); 
const modalDateDisplay = document.getElementById("modal-date-display");
const gioKhamContainer = document.getElementById("GioKhamContainer"); 
const tabButtons = panel.querySelectorAll('.tab-button');
const closeTimeBtn = document.querySelector('.close-time-modal');

// DOM MODAL XỬ LÝ ĐĂNG NHẬP/ĐĂNG KÝ
const authModal = document.getElementById("AuthDecisionModal");
const closeAuthBtn = document.querySelector('.close-auth-modal');
const btnContinueAsGuest = document.getElementById('btnContinueAsGuest');

// DOM MODAL NHẬP THÔNG TIN KHÁCH LẺ
const guestModal = document.getElementById("GuestInfoModal");
const closeGuestBtn = document.querySelector('.close-guest-modal');
const formGuestInfo = document.getElementById('form-guest-info');
const guestHoTenInput = document.getElementById('guestHoTenKH');
const guestSdtInput = document.getElementById('guestSDTKH');
const guestEmailInput = document.getElementById('guestEmailKH');


// Biến tạm lưu dữ liệu
window.finalBookingData = {};

// ********** CHỨC NĂNG MODAL CHUNG **********
function openModal(modal) { modal.style.display = "flex"; document.body.style.overflow = 'hidden'; }
function closeModal(modal) { modal.style.display = "none"; document.body.style.overflow = 'auto'; }

closeTimeBtn.addEventListener('click', () => closeModal(panel));
closeAuthBtn.addEventListener('click', () => closeModal(authModal));
// Kiểm tra nếu modal và nút đóng tồn tại trước khi thêm sự kiện
if (guestModal && closeGuestBtn) {
    closeGuestBtn.addEventListener('click', () => closeModal(guestModal));
}

window.onclick = function(event) {
    if (event.target == panel) closeModal(panel);
    if (event.target == authModal) closeModal(authModal);
    if (event.target == guestModal) closeModal(guestModal);
}
// ********** HẾT CHỨC NĂNG MODAL CHUNG **********


// ********** HÀM XỬ LÝ LỊCH VÀ BƯỚC **********
let fp = flatpickr("#NgayKham", {
    locale: "vn",
    dateFormat: "Y-m-d",
    minDate: "today",
    clickOpens: false, 
    enable: [],
    onChange: function(selectedDates, dateStr, instance) {
        gioKhamHidden.value = "";
        if (dateStr) {
            ngayKhamHidden.value = dateStr;
            ngayKhamInput.value = dateStr + " - Chưa chọn giờ"; 
            if (loadAvailableTimeSlots(dateStr)) {
                modalDateDisplay.textContent = `Ngày đã chọn: ${dateStr}`;
                openModal(panel);
            } else {
                 ngayKhamInput.value = dateStr + " - Hết giờ trống"; 
                 closeModal(panel); 
            }
        } else {
            ngayKhamHidden.value = "";
            ngayKhamInput.value = "Chọn ngày khám";
            clearTimeSlots(); 
            closeModal(panel); 
        }
        validateAndToggleSubmit();
    }
});

function displayMessage(message = '', element = errorMsgDiv) { element.textContent = message; }
function updateFlatpickr(enabledDates = [], openStatus = false) {
    fp.set('enable', enabledDates);
    fp.set('clickOpens', openStatus);
    // Vô hiệu hóa/Kích hoạt ô nhập ngày
    openStatus ? ngayKhamInput.removeAttribute("readonly") : ngayKhamInput.setAttribute("readonly", true);
}
function updateTimeSlotTabs(hasMorning, hasAfternoon) {
    const tabSang = document.querySelector('.tab-button[data-time-group="sang"]');
    const tabChieu = document.querySelector('.tab-button[data-time-group="chieu"]');
    tabSang.classList.remove('disabled-tab', 'active');
    tabChieu.classList.remove('disabled-tab', 'active');
    if (!hasMorning) { tabSang.classList.add('disabled-tab'); }
    if (!hasAfternoon) { tabChieu.classList.add('disabled-tab'); }
}
function clearTimeSlots() {
    gioKhamContainer.innerHTML = '';
    gioKhamHidden.value = "";
    updateTimeSlotTabs(true, true); 
    const tabSang = document.querySelector('.tab-button[data-time-group="sang"]');
    if (tabSang) tabSang.classList.add('active');
}
tabButtons.forEach(button => {
    button.addEventListener('click', function() {
        if (!this.classList.contains('disabled-tab')) switchTab(this.dataset.timeGroup);
    });
});
function switchTab(groupName) {
    const allSlots = gioKhamContainer.querySelectorAll('span.time-slot'); 
    let hasAvailableSlots = false;
    tabButtons.forEach(button => { button.classList.remove('active'); if (button.dataset.timeGroup === groupName) button.classList.add('active'); });
    allSlots.forEach(slot => {
        if (slot.dataset.group === groupName) { slot.style.display = 'inline-block'; hasAvailableSlots = true; } 
        else { slot.style.display = 'none'; }
    });
    const tempMsg = document.querySelector('#GioKhamContainer p.no-slot-msg'); if (tempMsg) tempMsg.remove();
    if (!hasAvailableSlots) {
        const p = document.createElement('p'); p.className = 'no-slot-msg';
        p.style.cssText = 'color:#666; text-align:center; padding:10px 0; width: 100%;';
        p.textContent = 'Không có khung giờ trống trong buổi này.'; gioKhamContainer.appendChild(p);
    }
}
function handleTimeSlotClick(event) {
    gioKhamContainer.querySelectorAll('.time-slot').forEach(slot => { slot.classList.remove('selected'); });
    event.target.classList.add('selected');
    const selectedTime = event.target.dataset.time;
    gioKhamHidden.value = selectedTime;
    const selectedDate = ngayKhamHidden.value;
    const period = selectedTime < '12:00' ? 'Sáng' : 'Chiều';
    ngayKhamInput.value = `${selectedDate} (${selectedTime} - ${period})`;
    validateAndToggleSubmit(); closeModal(panel); 
}
function loadAvailableTimeSlots(dateStr) {
    const maBS = bacsiSel.value; clearTimeSlots();
    // LƯU Ý: Nếu datLich là "theoGioKham" thì cần tìm tất cả BS có lịch, nhưng logic hiện tại chỉ hỗ trợ "theoBacSi".
    if (!maBS) { displayMessage("⚠️ Vui lòng chọn bác sĩ trước khi chọn ngày khám.", errorMsgDiv); return false; }
    const caLamViec = lichLamViecData[maBS]?.[dateStr]; 
    if (!caLamViec) { displayMessage("⚠️ Bác sĩ không có lịch làm việc trong ngày này.", errorMsgDiv); return false; } 
    displayMessage("", errorMsgDiv);
    const bookedSlotsOnDate = bookedSlotsData[maBS]?.[dateStr] || [];
    const slotsFilteredByCa = defaultTimeSlots.filter(slot => {
        const isMorning = slot < '12:00';
        const isAfternoon = slot >= '12:00';
        return (caLamViec === 'Sáng' && isMorning) || (caLamViec === 'Chiều' && isAfternoon) || (caLamViec === 'Cả ngày');
    });
    const availableSlots = slotsFilteredByCa.filter(slot => !bookedSlotsOnDate.includes(slot));
    
    if (availableSlots.length > 0) {
        let hasMorning = false; let hasAfternoon = false;
        availableSlots.forEach(slot => {
            const group = slot < '12:00' ? 'sang' : 'chieu';
            if (group === 'sang') hasMorning = true; if (group === 'chieu') hasAfternoon = true;
            const slotSpan = document.createElement("span"); slotSpan.textContent = slot;
            slotSpan.classList.add('time-slot'); slotSpan.dataset.time = slot; 
            slotSpan.dataset.group = group; slotSpan.addEventListener('click', handleTimeSlotClick);
            gioKhamContainer.appendChild(slotSpan);
        });
        updateTimeSlotTabs(hasMorning, hasAfternoon); 
        let initialGroup = hasMorning ? 'sang' : (hasAfternoon ? 'chieu' : '');
        if (initialGroup) { switchTab(initialGroup); return true; } 
    }
    return false;
}
function validateAndToggleSubmit() {
    const maBS = bacsiSel.value;
    const ngayKham = ngayKhamHidden.value;
    const gioKham = gioKhamHidden.value;
    const maCK = chuyenKhoaSel.value;
    const maDV = dichvuSel.value;
    
    // Yêu cầu tối thiểu: Bác sĩ, Ngày, Giờ, Chuyên khoa, Dịch vụ
    if (maBS && ngayKham && gioKham && maCK && maDV) {
        btnSubmit.disabled = false;
        btnSubmit.style.backgroundColor = '#007bff'; 
        btnSubmit.style.color = 'white';
    } else {
        btnSubmit.disabled = true;
        btnSubmit.style.backgroundColor = '#ccc'; 
        btnSubmit.style.color = '#666';
    }
}

// Hàm chuyển từ B1 sang B2
function goToNextStep() {
    window.finalBookingData = {
        maBS: bacsiSel.value,
        ngayKham: ngayKhamHidden.value,
        gioKham: gioKhamHidden.value,
        maCK: chuyenKhoaSel.value,
        maDV: dichvuSel.value
    };
    
    trieuChungInput.value = ""; 
    document.getElementById('booking-step-1').style.display = 'none';
    document.getElementById('booking-step-2').style.display = 'block';
    
    window.scrollTo({ top: document.getElementById('booking-step-2').offsetTop - 20, behavior: 'smooth' });
}

// Hàm quay lại từ B2 về B1
function goToPreviousStep() {
    document.getElementById('booking-step-2').style.display = 'none';
    document.getElementById('booking-step-1').style.display = 'block';
    window.scrollTo({ top: document.getElementById('booking-step-1').offsetTop - 20, behavior: 'smooth' });
}
// ********** HẾT HÀM XỬ LÝ LỊCH VÀ BƯỚC **********


// ********** HÀM XỬ LÝ LOGIC ĐĂNG NHẬP (QUAN TRỌNG) **********

function handleBookingFinalStep() {
    const trieuChung = trieuChungInput.value.trim();
    if (!trieuChung) {
        alert("Vui lòng mô tả triệu chứng của bạn để hoàn tất đăng ký.");
        return;
    }
    
    window.finalBookingData.TrieuChung = trieuChung;

    if (isLoggedIn) {
        // TRƯỜNG HỢP 1: ĐÃ ĐĂNG NHẬP -> Hoàn tất ngay lập tức
        completeBooking({
            isLoggedIn: true,
            MaKH: userData.MaKH,
            HoTen: userData.HoTen,
            SDT: userData.SDT,
            Email: userData.Email || '',
        });
    } else {
        // TRƯỜNG HỢP 2: CHƯA ĐĂNG NHẬP -> Yêu cầu quyết định Đăng nhập/Đăng ký
        openModal(authModal);
    }
}

// Xử lý khi chọn "Tiếp tục với tư cách Khách"
if (btnContinueAsGuest) { // Kiểm tra sự tồn tại của nút (vì đã thêm vào HTML)
    btnContinueAsGuest.addEventListener('click', function() {
        closeModal(authModal);
        // Mở modal nhập thông tin khách lẻ
        openModal(guestModal);
    });
}

// Xử lý form Khách lẻ submit
if (formGuestInfo) {
    formGuestInfo.addEventListener('submit', function(event) {
        event.preventDefault();
        
        const guestData = {
            isLoggedIn: false,
            HoTen: guestHoTenInput.value.trim(),
            SDT: guestSdtInput.value.trim(),
            Email: guestEmailInput.value.trim(),
        };
        
        if (!guestData.HoTen || !guestData.SDT) {
            alert("Vui lòng điền đầy đủ Họ Tên và Số Điện Thoại.");
            return;
        }
        
        closeModal(guestModal);
        completeBooking(guestData);
    });
}


// HÀM HOÀN TẤT VÀ GỬI DỮ LIỆU
function completeBooking(customerInfo) {
    const finalData = {
        ...window.finalBookingData,
        CustomerType: customerInfo.isLoggedIn ? 'Registered' : 'Guest',
        MaKH: customerInfo.MaKH || null,
        HoTenKH: customerInfo.HoTen,
        SDTKH: customerInfo.SDT,
        EmailKH: customerInfo.Email,
    };
    
    // Thay thế alert này bằng lệnh POST/AJAX thực tế
    alert("✅ ĐẶT LỊCH THÀNH CÔNG (Giả lập)!\n\n" +
          `Loại Khách hàng: ${finalData.CustomerType}` +
          "\n\nDữ liệu gửi đi:\n" + JSON.stringify(finalData, null, 2));

    // ******************************************************************
    // ** THỰC HIỆN LỆNH POST/AJAX Ở ĐÂY để lưu finalData vào CSDL **
    // ******************************************************************
    
    resetAllSteps();
}

function resetAllSteps() {
    document.getElementById('form-step-1').reset();
    document.getElementById('form-step-2').reset();
    if (formGuestInfo) formGuestInfo.reset();
    goToPreviousStep(); // Quay lại bước 1
    validateAndToggleSubmit(); 
}

// Gắn sự kiện cho nút "Tiếp tục" (bước 1)
btnSubmit.addEventListener('click', goToNextStep);

// ********** HÀM XỬ LÝ SỰ KIỆN CŨ **********
chuyenKhoaSel.addEventListener("change", function() {
    // Luôn reset thông tin lịch và BS khi chọn lại Chuyên khoa
    bacsiSel.innerHTML = "<option value=''>Chọn bác sĩ</option>";
    bacsiSel.value = "";
    ngayKhamInput.value = "Chọn ngày khám";
    ngayKhamHidden.value = "";
    updateFlatpickr([], false);
    displayMessage();
    clearTimeSlots();
    closeModal(panel); 
    
    // Kích hoạt/Vô hiệu hóa Đặt lịch
    if (this.value !== "") {
        // Dịch vụ đã được kích hoạt ở HTML, ta chỉ cần điều chỉnh Đặt lịch
        // Dịch vụ được kích hoạt/vô hiệu hóa dựa trên MaDV.value (xem dichvuSel listener)
        // datLichSel.disabled = false; // Chuyển logic này sang dichvuSel listener
    } else {
        datLichSel.disabled = true; // Vô hiệu hóa Đặt lịch nếu Chuyên khoa trống
    }

    // Tải Bác sĩ theo Chuyên khoa
    const makhoa = this.value;
    bacsiData.filter(b => b.MaKhoa === makhoa).forEach(b => {
        const opt = document.createElement("option");
        opt.value = b.MaNV;
        opt.textContent = b.HovaTen;
        bacsiSel.appendChild(opt);
    });
    validateAndToggleSubmit();
});

bacsiSel.addEventListener("change", function() {
    const maBS = this.value;
    ngayKhamInput.value = "Chọn ngày khám";
    ngayKhamHidden.value = "";
    displayMessage();
    clearTimeSlots();
    closeModal(panel); 
    
    if (maBS !== "") {
        const availableDates = Object.keys(lichLamViecData[maBS] || {});

        if (availableDates.length > 0) {
            updateFlatpickr(availableDates, true); 
        } else {
            updateFlatpickr([], false);
            displayMessage("⚠️ Bác sĩ này chưa có lịch làm việc trong tương lai gần.", errorMsgDiv); 
        }
    } else {
        updateFlatpickr([], false);
    }
    validateAndToggleSubmit();
});

dichvuSel.addEventListener("change", function() {
    // Đặt lịch chỉ được kích hoạt khi đã chọn Dịch vụ
    datLichSel.disabled = (this.value === "");
    validateAndToggleSubmit();
});


function toggleBacSi() {
    const datLich = datLichSel.value;
    const bacsiGroup = document.getElementById("bacsi-group");
    bacsiGroup.style.display = datLich === "theoBacSi" ? "block" : "none";
    
    if (datLich === "theoGioKham") {
        // Cần reset BS và lịch khám nếu chuyển sang chế độ không chọn BS
        bacsiSel.value = "";
        bacsiSel.dispatchEvent(new Event('change')); 
    }
    // Cần reset BS và lịch khám nếu chuyển sang chế độ chọn BS mà chưa chọn
    // Không cần làm gì thêm ở đây, vì logic reset đã có trong BS change và CK change
}
document.addEventListener("DOMContentLoaded", function() {
    toggleBacSi();
    validateAndToggleSubmit();
});
</script>

</body>
</html>

<?php
$conn->close();
?>