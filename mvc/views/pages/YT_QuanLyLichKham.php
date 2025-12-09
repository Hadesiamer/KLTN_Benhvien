<?php
// ================== API ĐẶT LỊCH (NVYT) ==================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Chỉ cho phép nhân viên y tế (role = 3) dùng API
if (isset($_GET['api']) && $_GET['api'] == '1' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');

    if (!isset($_SESSION['role']) || $_SESSION['role'] != 3) {
        echo json_encode([
            'success' => false,
            'message' => 'Bạn không có quyền thực hiện thao tác này.'
        ]);
        exit;
    }

    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);

    if (!$data) {
        echo json_encode([
            'success' => false,
            'message' => 'Dữ liệu gửi lên không hợp lệ.'
        ]);
        exit;
    }

    $maBN       = isset($data['MaBN']) ? (int)$data['MaBN'] : 0;
    $maBS       = isset($data['MaBS']) ? (int)$data['MaBS'] : 0;
    $maCK       = isset($data['MaKhoa']) ? (int)$data['MaKhoa'] : 0;
    $maDV       = isset($data['MaDV']) ? trim($data['MaDV']) : '';
    $ngayKham   = isset($data['NgayKham']) ? trim($data['NgayKham']) : '';
    $gioKham    = isset($data['GioKham']) ? trim($data['GioKham']) : '';
    $trieuChung = isset($data['TrieuChung']) ? trim($data['TrieuChung']) : '';

    if ($maBN <= 0 || $maBS <= 0 || $maCK <= 0 || $maDV === '' || $ngayKham === '' || $gioKham === '' || $trieuChung === '') {
        echo json_encode([
            'success' => false,
            'message' => 'Vui lòng nhập đầy đủ thông tin lịch khám.'
        ]);
        exit;
    }

    // Kết nối CSDL
    $conn = new mysqli("localhost", "root", "", "domdom");
    $conn->set_charset("utf8");

    if ($conn->connect_error) {
        echo json_encode([
            'success' => false,
            'message' => 'Lỗi kết nối CSDL: ' . $conn->connect_error
        ]);
        exit;
    }

    // INSERT lịch khám – mặc định "Da thanh toan"
    $sql = "INSERT INTO lichkham 
                (MaBN, MaBS, MaKhoa, LoaiDichVu, NgayKham, GioKham, TrieuChung, TrangThaiThanhToan)
            VALUES 
                (?, ?, ?, ?, ?, ?, ?, 'Da thanh toan')";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode([
            'success' => false,
            'message' => 'Lỗi chuẩn bị câu lệnh SQL.'
        ]);
        $conn->close();
        exit;
    }

    $stmt->bind_param(
        "iiissss",
        $maBN,
        $maBS,
        $maCK,
        $maDV,
        $ngayKham,
        $gioKham,
        $trieuChung
    );

    if ($stmt->execute()) {
        $last_id = $conn->insert_id;
        echo json_encode([
            'success' => true,
            'message' => 'Đăng ký lịch khám thành công.',
            'MaLK'    => $last_id
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Lỗi SQL: ' . $stmt->error
        ]);
    }

    $stmt->close();
    $conn->close();
    exit;
}

// ================== PHẦN HIỂN THỊ UI (GET) ==================
// Ở đây file được include bên trong layoutNVYT.php
if (!isset($_SESSION['role']) || $_SESSION['role'] != 3) {
    echo "<div class='alert alert-danger mt-3'>Bạn không có quyền truy cập chức năng này.</div>";
    return;
}

// Kết nối CSDL để load dữ liệu
$conn = new mysqli("localhost", "root", "", "domdom");
$conn->set_charset("utf8");

if ($conn->connect_error) {
    echo "<div class='alert alert-danger mt-3'>Lỗi kết nối CSDL: " . htmlspecialchars($conn->connect_error) . "</div>";
    return;
}

// ------ Tìm kiếm bệnh nhân ------
$keyword = isset($_GET['q']) ? trim($_GET['q']) : '';
$patients = [];

if ($keyword !== '') {
    // Tìm theo MaBN chính xác hoặc SĐT chứa chuỗi
    $sqlBN = "SELECT MaBN, HovaTen, SoDT, NgaySinh, GioiTinh 
              FROM benhnhan 
              WHERE MaBN = ? OR SoDT LIKE ?";
    $stmtBN = $conn->prepare($sqlBN);
    $like = "%" . $keyword . "%";
    $maBNSearch = ctype_digit($keyword) ? (int)$keyword : 0;
    $stmtBN->bind_param("is", $maBNSearch, $like);
    $stmtBN->execute();
    $rsBN = $stmtBN->get_result();
    while ($row = $rsBN->fetch_assoc()) {
        $patients[] = $row;
    }
    $stmtBN->close();
} else {
    // Danh sách mặc định 20 bệnh nhân mới nhất
    $sqlBN = "SELECT MaBN, HovaTen, SoDT, NgaySinh, GioiTinh 
              FROM benhnhan 
              ORDER BY MaBN DESC
              LIMIT 20";
    $rsBN = $conn->query($sqlBN);
    if ($rsBN) {
        $patients = $rsBN->fetch_all(MYSQLI_ASSOC);
    }
}

// --- TẢI DỮ LIỆU KHOA (CHUYÊN KHOA) ---
$khoalist = [];
$ck_query = $conn->query("SELECT * FROM chuyenkhoa");
if ($ck_query) {
    $khoalist = $ck_query->fetch_all(MYSQLI_ASSOC);
}

// Lấy danh sách dịch vụ
$dichvulist = [];
$dv = $conn->query("SELECT * FROM loaidichvu");
if ($dv) {
    $dichvulist = $dv->fetch_all(MYSQLI_ASSOC);
}

// Lấy tất cả bác sĩ (dùng cho JS)
$bacsiall = [];
$sqlAllBS = "SELECT nhanvien.MaNV, nhanvien.HovaTen, bacsi.MaKhoa 
             FROM nhanvien 
             INNER JOIN bacsi ON nhanvien.MaNV = bacsi.MaNV 
             WHERE ChucVu = 'Bác sĩ'";
$resAllBS = $conn->query($sqlAllBS);
if ($resAllBS) {
    $bacsiall = $resAllBS->fetch_all(MYSQLI_ASSOC);
}

// --- LỊCH LÀM VIỆC ---
$lichlamviecAll = [];
$sqlLich = "SELECT DATE_FORMAT(NgayLamViec, '%Y-%m-%d') as NgayKham, MaNV, CaLamViec 
            FROM lichlamviec 
            WHERE NgayLamViec >= CURDATE() AND TrangThai = 'Đang làm'";
$resLich = $conn->query($sqlLich);
if ($resLich) {
    while ($row = $resLich->fetch_assoc()) {
        $maNV = $row['MaNV'];
        $ngayKham = $row['NgayKham'];
        if (!isset($lichlamviecAll[$maNV])) {
            $lichlamviecAll[$maNV] = [];
        }
        $lichlamviecAll[$maNV][$ngayKham] = $row['CaLamViec'];
    }
}

// --- KHUNG GIỜ ĐÃ ĐẶT ---
$defaultTimeSlots = [
    "08:00", "09:00", "10:00", "11:00",
    "13:00", "14:00", "15:00", "16:00"
];

$bookedSlotsAll = [];
$sqlBooked = "SELECT MaBS, DATE_FORMAT(NgayKham, '%Y-%m-%d') as NgayKham,
                     TIME_FORMAT(GioKham, '%H:%i') as GioKham
              FROM lichkham 
              WHERE NgayKham >= CURDATE() 
                AND TrangThaiThanhToan NOT IN ('', 'Huy')"; // <<-- Ẩn Chua thanh toan + Da thanh toan
$resBooked = $conn->query($sqlBooked);
if ($resBooked) {
    while ($row = $resBooked->fetch_assoc()) {
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

// Dữ liệu này sẽ dùng cho JS
?>

<!-- ================== UI: QUẢN LÝ LỊCH KHÁM (NVYT) ================== -->

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="/KLTN_Benhvien/public/css/formDK.css">

<div class="container-fluid py-3">

    <!-- Tiêu đề + mô tả -->
    <div class="mb-3">
        <h2 class="h5 mb-1">Quản lý lịch khám</h2>
        <p class="text-muted small mb-0">
            Nhân viên y tế hỗ trợ bệnh nhân đăng ký lịch khám. Lịch đặt từ đây mặc định được đánh dấu là
            <strong>ĐÃ THANH TOÁN</strong>.
        </p>
    </div>

    <div class="row g-3">
        <!-- ========== CỘT 1: CHỌN BỆNH NHÂN ========== -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="bi bi-person-search me-1"></i> Tìm & chọn bệnh nhân
                    </h5>

                    <form method="get" class="row g-2 mb-3">
                        <div class="col-12">
                            <label class="form-label small mb-1">
                                Tìm theo Mã BN hoặc Số điện thoại
                            </label>
                            <input type="text" name="q" class="form-control form-control-sm"
                                   placeholder="Nhập mã BN hoặc số điện thoại..."
                                   value="<?php echo htmlspecialchars($keyword); ?>">
                        </div>
                        <div class="col-12 d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="bi bi-search me-1"></i> Tìm kiếm
                            </button>
                            <a href="/KLTN_Benhvien/NVYT/QuanLyLichKham" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-arrow-repeat me-1"></i> Reset
                            </a>
                        </div>
                    </form>

                    <div class="table-responsive" style="max-height: 260px; overflow-y: auto;">
                        <table class="table table-sm table-hover align-middle mb-0">
                            <thead class="table-light sticky-top">
                            <tr>
                                <th scope="col">Mã BN</th>
                                <th scope="col">Họ tên</th>
                                <th scope="col">SĐT</th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (!empty($patients)): ?>
                                <?php foreach ($patients as $bn): ?>
                                    <tr>
                                        <td class="text-nowrap"><?php echo htmlspecialchars($bn['MaBN']); ?></td>
                                        <td><?php echo htmlspecialchars($bn['HovaTen']); ?></td>
                                        <td class="text-nowrap"><?php echo htmlspecialchars($bn['SoDT']); ?></td>
                                        <td class="text-end">
                                            <button type="button"
                                                    class="btn btn-outline-primary btn-sm btn-select-patient"
                                                    data-mabn="<?php echo htmlspecialchars($bn['MaBN']); ?>"
                                                    data-hoten="<?php echo htmlspecialchars($bn['HovaTen']); ?>"
                                                    data-sdt="<?php echo htmlspecialchars($bn['SoDT']); ?>">
                                                Chọn
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted small">
                                        Không tìm thấy bệnh nhân phù hợp.
                                    </td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <hr>

                    <div class="small">
                        <div class="fw-semibold mb-1">Bệnh nhân đang chọn:</div>
                        <div id="selected-patient-box" class="p-2 border rounded bg-light small text-muted">
                            Chưa chọn bệnh nhân.
                        </div>
                        <input type="hidden" id="SelectedMaBN" value="">
                    </div>
                </div>
            </div>
        </div>

        <!-- ========== CỘT 2+3: FORM ĐẶT LỊCH ========== -->
        <div class="col-lg-8">
            <div class="booking-form-wrapper" id="booking-step-1">
                <h2 style="text-align: center">Đăng ký lịch khám cho bệnh nhân</h2>
                <p class="text-muted small text-center mb-3">
                    Vui lòng chọn bệnh nhân ở cột bên trái trước khi đặt lịch.
                </p>
                <form id="form-step-1">
                    <div class="form-group">
                        <label>Chuyên khoa</label>
                        <select name="chuyenKhoa" id="chuyenKhoa">
                            <option value="">Chọn chuyên khoa</option>
                            <?php foreach ($khoalist as $khoa): ?>
                                <option value="<?php echo htmlspecialchars($khoa['MaKhoa']); ?>">
                                    <?php echo htmlspecialchars($khoa['TenKhoa']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Dịch vụ</label>
                        <input type="hidden" name="MaDV" id="MaDV_Hidden" value="">
                        <div id="DichVuSelection" class="service-selection-buttons">
                            <?php foreach ($dichvulist as $dichvu): ?>
                                <?php
                                $maLoai = htmlspecialchars($dichvuh['MaLoai'] ?? $dichvu['MaLoai']);
                                $loaiDV = htmlspecialchars($dichvuh['LoaiDichVu'] ?? $dichvu['LoaiDichVu']);
                                ?>
                            <?php endforeach; ?>

                            <?php foreach ($dichvulist as $dvRow): ?>
                                <button type="button"
                                        class="service-button"
                                        data-dv-id="<?php echo htmlspecialchars($dvRow['MaLoai']); ?>">
                                    <?php echo htmlspecialchars($dvRow['LoaiDichVu']); ?>
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
                <form id="form-step-2" onsubmit="event.preventDefault(); handleBookingFinalStep_NVYT();">
                    <h2 style="text-align: center">Mô tả triệu chứng</h2>
                    <p>Ghi nhận triệu chứng để bác sĩ nắm thông tin trước khi khám.</p>

                    <div class="form-group">
                        <label for="TrieuChungKH">Triệu chứng (*)</label>
                        <textarea id="TrieuChungKH" name="TrieuChungKH" rows="5" required
                                  style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;"></textarea>
                    </div>

                    <div class="form-group" style="text-align: center; margin-top: 30px;">
                        <button type="button"
                                onclick="goToPreviousStep_NVYT()"
                                class="submit-button"
                                style="background-color: #6c757d; color: white;">Quay lại
                        </button>
                        <button type="submit"
                                id="btnCompleteBooking_NVYT"
                                class="submit-button"
                                style="background-color: #28a745; color: white;">Hoàn tất đăng ký
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal chọn giờ khám -->
    <div id="GioKhamSelectionPanel" class="modal-overlay">
        <div class="modal-content">
            <span class="close-button close-time-modal">&times;</span>
            <h2 style="font-size: 1.2em; margin-top: 0;">Chọn khung giờ khám</h2>
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

                    <div id="GioKhamContainer" class="time-slot-container"></div>
                </div>
            </div>

            <div id="gio-error-msg" style="color: red; font-size: 0.9em; margin-top: 5px;"></div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/vn.js"></script>

<script>
// ======== DỮ LIỆU PHP TRUYỀN SANG JS ========
const bacsiData_NVYT       = <?php echo json_encode($bacsiall); ?>;
const lichLamViecData_NVYT = <?php echo json_encode($lichlamviecAll); ?>;
const defaultTimeSlots_NVYT = <?php echo json_encode($defaultTimeSlots); ?>;
const bookedSlotsData_NVYT = <?php echo json_encode($bookedSlotsAll); ?>;
</script>

<script src="/KLTN_Benhvien/public/js/yt_lichkham.js"></script>

<?php
$conn->close();
?>
