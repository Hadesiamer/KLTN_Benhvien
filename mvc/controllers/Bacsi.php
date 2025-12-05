<?php
// NhatCuong: Đặt múi giờ VN cho toàn bộ controller
date_default_timezone_set('Asia/Ho_Chi_Minh');


class Bacsi extends Controller
{
    // Hàm mặc định khi vào trang bác sĩ
    function SayHi()
    {
        // NhatCuong: vào /Bacsi sẽ vào thống kê bác sĩ luôn
        $this->thongkebs();
    }

    function DangKyLichLamViec()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $maNV = $_SESSION['idnv']; // Mã nhân viên (bác sĩ đăng nhập)
            $schedule = $_POST['schedule']; // Dữ liệu lịch làm việc
            $dateRange = $_POST['dateRange']; // Khoảng thời gian của tuần được chọn

            $model = $this->model("MBacsi");
            $success = [];
            $failed = [];

            // Chuyển khoảng thời gian thành ngày bắt đầu và kết thúc
            list($startDate, $endDate) = explode(" - ", $dateRange);
            $startDate = DateTime::createFromFormat('d/m/Y', $startDate)->format('Y-m-d');
            $endDate = DateTime::createFromFormat('d/m/Y', $endDate)->format('Y-m-d');

            // Tính ngày làm việc cho tuần được chọn
            $daysMap = ['mon' => 0, 'tue' => 1, 'wed' => 2, 'thu' => 3, 'fri' => 4, 'sat' => 5, 'sun' => 6];
            $monday = new DateTime($startDate);

            foreach ($schedule as $day => $shifts) {
                foreach ($shifts as $shift) {
                    // Tạo bản sao của $monday để tránh thay đổi giá trị gốc
                    $currentDay = clone $monday;

                    // Tính ngày làm việc dựa vào thứ
                    $ngayLamViec = $currentDay->modify("+{$daysMap[$day]} days")->format('Y-m-d');

                    // Kiểm tra số lượng bác sĩ đã đăng ký trong ca làm việc
                    $soLuong = $model->kiemTraSoLuongCaLamViec($ngayLamViec, $shift);
                    if ($soLuong >= 10) {
                        $ngayLamViec = date('d/m/Y', strtotime($ngayLamViec));
                        $failed[] = "Ngày $ngayLamViec ($shift) đã đạt giới hạn số lượng bác sĩ.";
                        continue;
                    }

                    // Kiểm tra xem lịch đã tồn tại chưa
                    if ($model->kiemTraLichDaTonTai($maNV, $ngayLamViec, $shift)) {
                        $ngayLamViec = date('d/m/Y', strtotime($ngayLamViec));
                        $failed[] = "Ngày $ngayLamViec ($shift) đã được đăng ký.";
                    } else {
                        // Thêm lịch làm việc
                        if ($model->themLichLamViec($maNV, $ngayLamViec, $shift)) {
                            $ngayLamViec = date('d/m/Y', strtotime($ngayLamViec));
                            $success[] = "Ngày $ngayLamViec ($shift) đã được đăng ký thành công.";
                        } else {
                            $ngayLamViec = date('d/m/Y', strtotime($ngayLamViec));
                            $failed[] = "Có lỗi xảy ra khi đăng ký ngày $ngayLamViec ($shift).";
                        }
                    }
                }
                $monday = new DateTime($startDate); // Reset lại thứ 2
            }

            // Trả về giao diện với thông báo chi tiết
            $this->view("layoutBacsi", [
                "Page" => "dangkylichlamviec",
                "Message" => [
                    "success" => $success,
                    "failed" => $failed
                ]
            ]);
        } else {
            $this->view("layoutBacsi", [
                "Page" => "dangkylichlamviec"
            ]);
        }
    }

    // ===========================
    // NhatCuong: Xem lịch làm việc (chuẩn MVC + xử lý yêu cầu nghỉ phép qua AJAX)
    // ===========================
    function XemLichLamViec()
    {
        $model = $this->model("MBacsi");

        // Chuẩn hóa lấy MaNV của bác sĩ đang đăng nhập
        $maNV = $_SESSION['MaNV'] ?? $_SESSION['idnv'] ?? null;

        // Bảo vệ: chưa đăng nhập thì quay về trang Login
        if (!$maNV) {
            echo "<script>alert('Vui lòng đăng nhập lại!');</script>";
            header("refresh:0; url='/KLTN_Benhvien/Login'");
            return;
        }

        // ========== 1. XỬ LÝ AJAX: Gửi yêu cầu nghỉ phép ==========
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'request_leave') {
            $ngayNghi   = $_POST['NgayNghi']   ?? '';
            $caLamViec  = $_POST['CaLamViec']  ?? '';
            $lyDo       = trim($_POST['LyDo'] ?? '');

            // Gọi Model xử lý nghiệp vụ nghỉ phép
            $result = $model->TaoYeuCauNghiPhep($maNV, $ngayNghi, $caLamViec, $lyDo);

            // Map sang class Bootstrap
            $alertClass = 'danger';
            if (!empty($result['status'])) {
                if ($result['status'] === 'success') {
                    $alertClass = 'success';
                } elseif ($result['status'] === 'warning') {
                    $alertClass = 'warning';
                }
            }

            // Trả về HTML alert giống code cũ để JS phía view tái sử dụng
            echo '<div class="alert alert-' . $alertClass . ' alert-dismissible fade show mt-3" role="alert">'
                . htmlspecialchars($result['message'] ?? 'Đã xảy ra lỗi.', ENT_QUOTES, 'UTF-8') .
                '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
              </div>';
            exit; // rất quan trọng, tránh render cả layout
        }

        // ========== 2. LOAD LỊCH LÀM VIỆC + LỊCH NGHỈ PHÉP CHO VIEW ==========
        $lichLamViec   = [];
        $lichNghiPhep  = [];

        // Lịch làm việc (đang làm) theo bác sĩ
        $jsonLLV = $model->XemLichLamViec($maNV);
        if (!empty($jsonLLV)) {
            $decoded = json_decode($jsonLLV, true);
            if (is_array($decoded)) {
                $lichLamViec = $decoded;
            }
        }

        // Lịch nghỉ phép đã gửi
        $jsonLNP = $model->GetLichNghiPhepByMaNV($maNV);
        if (!empty($jsonLNP)) {
            $decoded2 = json_decode($jsonLNP, true);
            if (is_array($decoded2)) {
                $lichNghiPhep = $decoded2;
            }
        }

        // Truyền dữ liệu sang view
        $this->view("layoutBacsi", [
            "Page"          => "xemlichlamviec",
            "LichLamViec"   => $lichLamViec,
            "LichNghiPhep"  => $lichNghiPhep
        ]);
    }

    // ===========================
    // NhatCuong: Xem Danh Sách Khám Bệnh
    // - Lần load đầu: lấy ngày hôm nay + ca TẤT CẢ
    // - Đổi ngày / ca: dùng AJAX gọi hàm GetDanhSach() bên dưới
    // ===========================
    function XemDanhSachKham()
    {
        $bacsi = $this->model("MBacsi");
        $maNV  = $_SESSION['idnv'];          // MaNV của bác sĩ đang đăng nhập
        $today = date('Y-m-d');

        // Lần đầu mở trang: mặc định lấy hôm nay + tất cả ca
        $ds = $bacsi->GetDanhSachKhamTheoBSAll($maNV, $today);

        $this->view("layoutBacsi", [
            "Page"         => "Danhsachkham",
            "DanhSachKham" => $ds,
            "NgayKham"     => $today,
            "Shift"        => "all"
        ]);
    }

    // ===========================
    // AJAX: trả về JSON danh sách khám theo bác sĩ + ngày + ca
    // ===========================
    function GetDanhSach()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo "Method Not Allowed";
            return;
        }

        if (!isset($_SESSION['idnv'])) {
            http_response_code(401);
            echo "Not authenticated";
            return;
        }

        $bacsi = $this->model("MBacsi");
        $maNV  = $_SESSION['idnv'];

        // Lấy ngày (YYYY-mm-dd), mặc định hôm nay
        $ngayXem = isset($_POST['date']) && !empty($_POST['date'])
            ? $_POST['date']
            : date('Y-m-d');

        $d = DateTime::createFromFormat('Y-m-d', $ngayXem);
        if (!$d || $d->format('Y-m-d') !== $ngayXem) {
            $ngayXem = date('Y-m-d');
        }

        // Lấy ca (all/morning/afternoon)
        $shift = isset($_POST['shift']) ? $_POST['shift'] : 'all';

        switch ($shift) {
            case "morning":
                $ds = $bacsi->GetDanhSachKhamTheoBSSang($maNV, $ngayXem);
                break;
            case "afternoon":
                $ds = $bacsi->GetDanhSachKhamTheoBSChieu($maNV, $ngayXem);
                break;
            default:
                $shift = "all";
                $ds = $bacsi->GetDanhSachKhamTheoBSAll($maNV, $ngayXem);
                break;
        }

        // $ds là JSON (do model trả về json_encode), ta decode lại thành mảng
        $arr = [];
        if (!empty($ds)) {
            $decoded = json_decode($ds, true);
            if (is_array($decoded)) {
                $arr = $decoded;
            }
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            "success" => true,
            "data"    => $arr,
            "date"    => $ngayXem,
            "shift"   => $shift,
            "today"   => date('Y-m-d')
        ]);
        exit;
    }

    function XemThongTinBenhNhan()
    {
        if (isset($_POST['search'])) {
            $maBN = $_POST['maBN'];
            $model = $this->model("MBacsi");

            // Cho phép tìm theo MaBN / BHYT / SoDT (logic đã sửa trong model)
            $thongTinBenhNhan = $model->GetThongTinBenhNhan($maBN);

            $timmaBN = json_decode($thongTinBenhNhan, true);
            if (isset($timmaBN[0]['MaBN'])) {
                $maBN = $timmaBN[0]['MaBN'];
            }

            $phieuKham = $model->GetPhieuKham($maBN);

            $this->view("layoutBacsi", [
                "Page" => "xemthongtinbenhnhan",
                "ThongTinBenhNhan"   => $thongTinBenhNhan,
                "PhieuKhamBenhNhan"  => $phieuKham
            ]);
        } else {
            $this->view("layoutBacsi", [
                "Page" => "xemthongtinbenhnhan"
            ]);
        }
    }

    // NhatCuong: usecase: Xem lịch sử khám bệnh
    function XemLichSuKhamBenh()
    {
        if (isset($_POST['search'])) {
            $maBN = $_POST['maBN'];
            $model = $this->model("MBacsi");
            $thongTinBenhNhan   = $model->GetThongTinBenhNhan($maBN);
            $phieuKhamBenhNhan  = $model->GetPhieuKhamBenhNhan($maBN);
            $soLanKhamBenh      = $model->GetSoLanKhamBenh($maBN);

            $this->view("LayoutXemLichSuKhamBenh", [
                "Page"               => "DanhSachLichSuKham",
                "ThongTinBenhNhan"   => $thongTinBenhNhan,
                "PhieuKhamBenhNhan"  => $phieuKhamBenhNhan,
                "SoLanKhamBenh"      => $soLanKhamBenh
            ]);
        } else {
            $this->view("LayoutXemLichSuKhamBenh", [
                "Page" => "DanhSachLichSuKham"
            ]);
        }
    }

    // Lập phiếu khám
    function Lapphieukham()
    {
        $bs = $this->model("mBacSi");

        // Bước 1: Bấm nút "Lập phiếu khám" từ danh sách khám
        if (isset($_POST["btnLPK"])) {
            $mabn  = $_POST["MaBN"];
            $malk  = $_POST["MaLK"];
            $manv  = $_SESSION["idnv"];

            $model        = $this->model("mBacsi");
            $benhNhanInfo = $model->GetThongTinBenhNhan1($mabn, $malk);
            $bacSiInfo    = $model->getBacSiInfo($manv);
            $thuocList    = $model->getThuocList();

            $this->view("LayoutLapPhieuKham", [
                "Page"         => "Lapphieukham",
                "BenhNhanInfo" => $benhNhanInfo,
                "BacSiInfo"    => $bacSiInfo,
                "ThuocList"    => $thuocList
            ]);
        }

        // Bước 2: Submit form lập phiếu khám
        if (isset($_POST["lap"])) {
            $mabn        = $_POST["maBN"];
            $malk        = $_POST["maLK"];
            $ngaytao     = $_POST["ngayTao"];
            $bsi         = $_SESSION["idnv"];
            $trieuchung  = $_POST["trieuChung"];
            $kq          = $_POST["ketQua"];
            $chuandoan   = $_POST["chuanDoan"];
            $loidan      = $_POST["loiDan"];
            $ngaytaikham = $_POST["ngayTaiKham"];

            $model = $this->model("mBacsi");

            // --- Lấy và lọc danh sách thuốc hợp lệ ---
            $thuocPost = isset($_POST["thuoc"]) && is_array($_POST["thuoc"])
                ? $_POST["thuoc"]
                : [];

            $thuocValid = [];
            foreach ($thuocPost as $idx => $item) {
                $maThuoc  = isset($item["MaThuoc"]) ? trim($item["MaThuoc"]) : "";
                $soLuong  = isset($item["SoLuong"]) ? (int)$item["SoLuong"] : 0;
                $lieuDung = isset($item["LieuDung"]) ? trim($item["LieuDung"]) : "";
                $cachDung = isset($item["CachDung"]) ? trim($item["CachDung"]) : "";

                // Điều kiện 1 dòng thuốc hợp lệ: có mã thuốc, số lượng >0, có liều dùng
                if ($maThuoc !== "" && $soLuong > 0 && $lieuDung !== "") {
                    $thuocValid[] = [
                        "MaThuoc"  => $maThuoc,
                        "SoLuong"  => $soLuong,
                        "LieuDung" => $lieuDung,
                        "CachDung" => $cachDung
                    ];
                }
            }

            // ====================================================
            // KIỂM TRA + XÁC NHẬN GHI ĐÈ PHIẾU KHÁM THEO MaLK
            // ====================================================
            $confirmSave = isset($_POST['confirm_save']) ? $_POST['confirm_save'] : '0';
            $daTonTai    = $model->checkPhieuKhamTonTai($malk); // hàm trong MBacsi

            if ($daTonTai && $confirmSave !== '1') {
                // Lần submit đầu tiên: đã có phiếu cũ nhưng chưa confirm -> hiển thị hộp thoại
                // Render trang tạm với JS confirm, nếu đồng ý thì submit lại form với cờ confirm_save=1
                echo "<!DOCTYPE html>
<html lang='vi'>
<head>
    <meta charset='UTF-8'>
    <title>Xác nhận ghi đè phiếu khám</title>
</head>
<body>
    <form id='repostForm' method='POST' action=''>
        <input type='hidden' name='lap' value='1'>
        <input type='hidden' name='confirm_save' value='1'>
        <input type='hidden' name='maBN' value='" . htmlspecialchars($mabn, ENT_QUOTES) . "'>
        <input type='hidden' name='maLK' value='" . htmlspecialchars($malk, ENT_QUOTES) . "'>
        <input type='hidden' name='ngayTao' value='" . htmlspecialchars($ngaytao, ENT_QUOTES) . "'>
        <input type='hidden' name='trieuChung' value='" . htmlspecialchars($trieuchung, ENT_QUOTES) . "'>
        <input type='hidden' name='ketQua' value='" . htmlspecialchars($kq, ENT_QUOTES) . "'>
        <input type='hidden' name='chuanDoan' value='" . htmlspecialchars($chuandoan, ENT_QUOTES) . "'>
        <input type='hidden' name='loiDan' value='" . htmlspecialchars($loidan, ENT_QUOTES) . "'>
        <input type='hidden' name='ngayTaiKham' value='" . htmlspecialchars($ngaytaikham, ENT_QUOTES) . "'>";

                // Đổ lại dữ liệu mảng thuốc
                if (!empty($thuocPost)) {
                    foreach ($thuocPost as $idx => $item) {
                        $idxSafe = htmlspecialchars($idx, ENT_QUOTES);
                        $maThuoc  = isset($item['MaThuoc']) ? htmlspecialchars($item['MaThuoc'], ENT_QUOTES) : '';
                        $soLuong  = isset($item['SoLuong']) ? htmlspecialchars($item['SoLuong'], ENT_QUOTES) : '';
                        $lieuDung = isset($item['LieuDung']) ? htmlspecialchars($item['LieuDung'], ENT_QUOTES) : '';
                        $cachDung = isset($item['CachDung']) ? htmlspecialchars($item['CachDung'], ENT_QUOTES) : '';

                        echo "
        <input type='hidden' name='thuoc[$idxSafe][MaThuoc]' value='$maThuoc'>
        <input type='hidden' name='thuoc[$idxSafe][SoLuong]' value='$soLuong'>
        <input type='hidden' name='thuoc[$idxSafe][LieuDung]' value='$lieuDung'>
        <input type='hidden' name='thuoc[$idxSafe][CachDung]' value='$cachDung'>";
                    }
                }

                echo "
    </form>
    <script>
        var c = confirm('Bạn đã lập phiếu khám trước đó, nếu lưu một lần nữa, phiếu khám cũ sẽ mất');
        if (c) {
            document.getElementById('repostForm').submit();
        } else {
            alert('Đã hủy lưu phiếu khám.');
            window.history.back();
        }
    </script>
</body>
</html>";
                exit; // Dừng tại đây, chờ người dùng confirm
            }

            // Nếu đã confirm (confirm_save = 1) và có phiếu cũ -> xóa phiếu cũ trước khi thêm mới
            if ($daTonTai && $confirmSave === '1') {
                $model->deletePhieuKhamByMaLK($malk);
            }

            // ====================================================
            // 1) Chỉ tạo đơn thuốc nếu có ít nhất 1 thuốc hợp lệ
            // ====================================================
            if (count($thuocValid) > 0) {
                // Không truyền chẩn đoán vào GhiChuChung của don_thuoc nữa -> truyền chuỗi rỗng ""
                if ($model->TaoDT($ngaytao, "", $bsi, $mabn, $malk)) {
                    // Tạo chi tiết đơn thuốc (ct_don_thuoc) cho từng dòng thuốc
                    foreach ($thuocValid as $t) {
                        $mathuoc  = $t["MaThuoc"];
                        $soluong  = $t["SoLuong"];
                        $lieudung = $t["LieuDung"];
                        $cachdung = $t["CachDung"];

                        // Lưu từng dòng chi tiết; map sang ct_don_thuoc
                        // TaoCTDT vẫn dùng MaDon mới nhất trong don_thuoc
                        $rs3 = $model->TaoCTDT($mathuoc, $soluong, $lieudung, $cachdung);
                    }
                }
            }

            // ====================================================
            // 2) Tạo phiếu khám (AddPK tự gắn MaDon nếu có)
            // ====================================================
            $rs = $model->AddPK(
                $ngaytao,
                $trieuchung,
                $kq,
                $chuandoan,
                $loidan,
                $ngaytaikham,
                $malk,
                $bsi,
                $mabn
            );

            // 3) Quay lại danh sách khám như cũ
            $ngayHienTai     = date('Y-m-d');
            $danhSachSauLap  = $model->GetDanhSachKhamTheoBSAll($bsi, $ngayHienTai);

            $this->view("layoutBacsi", [
                "Page"         => "Danhsachkham",
                "DanhSachKham" => $danhSachSauLap,
                "NgayKham"     => $ngayHienTai,
                "Shift"        => "all",
                "result"       => isset($rs3) ? $rs3 : null
            ]);
        }
    }

    // Thông tin bác sĩ đang đăng nhập
    function ThongTinBacSi()
    {
        // Bảo vệ: chỉ cho role bác sĩ (2) vào, giống layoutBacsi.php
        if (!isset($_SESSION["role"]) || $_SESSION["role"] != 2) {
            echo "<script>alert('Bạn không có quyền truy cập');</script>";
            header("refresh:0; url='/KLTN_Benhvien'");
            return;
        }

        // Kiểm tra đã có session MaNV chưa
        if (!isset($_SESSION["idnv"]) || empty($_SESSION["idnv"])) {
            echo "<script>alert('Không tìm thấy thông tin tài khoản bác sĩ. Vui lòng đăng nhập lại.');</script>";
            header("refresh:0; url='/KLTN_Benhvien/Login'");
            return;
        }

        // MaNV của bác sĩ đang đăng nhập (được set trong Login.php)
        $maNV = $_SESSION["idnv"];

        // Gọi đúng model MBacsi (class trong mBacsi.php)
        $model = $this->model("MBacsi");

        // Lấy thông tin bác sĩ và truyền sang view thongtinbacsi
        $this->view("layoutBacsi", [
            "Page"        => "thongtinbacsi",
            "thongtinbs"  => $model->get1BS($maNV)
        ]);
    }

    // ===========================
    // NhatCuong: Thống kê bác sĩ (hôm nay / 7 ngày / tháng này / tất cả)
    // ===========================
    function thongkebs()
    {
        // Chỉ cho bác sĩ đã đăng nhập
        if (!isset($_SESSION["idnv"])) {
            echo "<script>alert('Vui lòng đăng nhập lại!');</script>";
            header("refresh:0; url='/KLTN_Benhvien/Login'");
            return;
        }

        $maNV = $_SESSION["idnv"];
        $model = $this->model("MBacsi");

        // NhatCuong: đổi sang lọc bằng POST, KHÔNG dùng query URL nữa
        $filter = 'today';
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['filter'])) {
            $filter = $_POST['filter'];
        }

        $allowed = ['today', '7days', 'month', 'all'];
        if (!in_array($filter, $allowed)) {
            $filter = 'today';
        }

        $thongKe = $model->getThongKeBacSi($maNV, $filter);

        $this->view("layoutBacsi", [
            "Page"    => "thongkebs",
            "ThongKe" => $thongKe,
            "Filter"  => $filter
        ]);
    }

    function Doimatkhau()
    {
        if (!isset($_SESSION["idnv"])) {
            echo "Chưa đăng nhập!";
            return;
        }

        $maNV = $_SESSION["idnv"];
        $model = $this->model("mBacSi");

        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            $old = $_POST["old_password"];
            $new = $_POST["new_password"];
            $confirm = $_POST["confirm_password"];

            // Kiểm tra nhập lại
            if ($new !== $confirm) {
                $this->view("layoutBacsi", [
                    "Page" => "bacsidoimk",
                    "msg" => "Mật khẩu xác nhận không khớp!"
                ]);
                return;
            }

            // Gọi model kiểm tra & đổi mật khẩu
            $result = $model->DoiMatKhau($maNV, $old, $new);

            $this->view("layoutBacsi", [
                "Page" => "bacsidoimk",
                "msg" => $result
            ]);
        } else {
            $this->view("layoutBacsi", [
                "Page" => "bacsidoimk"
            ]);
        }
    }

    // ================== CHAT: DANH SÁCH CUỘC TRÒ CHUYỆN CỦA BÁC SĨ ==================
    public function DanhSachCuocTroChuyen()
    {
        if (!isset($_SESSION['idnv'])) {
            echo "<script>alert('Vui lòng đăng nhập lại!');</script>";
            header("refresh:0; url='/KLTN_Benhvien/Login'");
            return;
        }

        $maBS      = (int)$_SESSION['idnv'];
        $chatModel = $this->model("mChat");
        $dsCuoc    = $chatModel->getConversationsForDoctor($maBS);

        $this->view("layoutBacsi", [
            "Page"              => "bs_danhsach_chat",
            "DanhSachCuocTroChuyen" => $dsCuoc
        ]);
    }

    // ================== CHAT: MÀN HÌNH CHAT VỚI 1 BỆNH NHÂN (BÁC SĨ) ==================
    public function ChatVoiBenhNhan($maCuocTrove = null)
    {
        if (!isset($_SESSION['idnv'])) {
            echo "<script>alert('Vui lòng đăng nhập lại!');</script>";
            header("refresh:0; url='/KLTN_Benhvien/Login'");
            return;
        }

        $maBS        = (int)$_SESSION['idnv'];
        $maCuocTrove = (int)$maCuocTrove;

        if ($maCuocTrove <= 0) {
            echo "Mã cuộc trò chuyện không hợp lệ.";
            exit;
        }

        $chatModel = $this->model("mChat");

        // Bảo vệ: chỉ cho BS sở hữu cuộc trò chuyện này
        if (!$chatModel->doctorOwnsConversation($maBS, $maCuocTrove)) {
            echo "Bạn không có quyền truy cập cuộc trò chuyện này.";
            exit;
        }

        // Lấy header cuộc trò chuyện (thông tin bệnh nhân)
        $header = $chatModel->getConversationHeaderForDoctor($maCuocTrove, $maBS);
        if (!$header) {
            echo "Không tìm thấy thông tin cuộc trò chuyện.";
            exit;
        }

        $maBN = (int)$header['MaBN'];

        // Lấy tin nhắn ban đầu (từ đầu)
        $messages = $chatModel->getMessages($maCuocTrove, 0);

        // Đánh dấu đã xem phía BS
        $chatModel->markAsReadForDoctor($maCuocTrove, $maBS);

        $this->view("layoutBacsi", [
            "Page"        => "bs_chat_benhnhan",
            "MaCuocTrove" => $maCuocTrove,
            "MaBN"        => $maBN,
            "Header"      => $header,
            "Messages"    => $messages
        ]);
    }

    // ================== AJAX: BS GỬI TIN NHẮN ==================
    public function AjaxGuiTinNhanBS()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo "Method Not Allowed";
            return;
        }

        if (!isset($_SESSION['idnv'])) {
            http_response_code(401);
            echo "Not authenticated";
            return;
        }

        // TODO: nếu bạn có CSRF token chung, hãy kiểm tra tại đây

        $maBS        = (int)$_SESSION['idnv'];
        $maCuocTrove = isset($_POST['MaCuocTrove']) ? (int)$_POST['MaCuocTrove'] : 0;
        $noiDung     = isset($_POST['NoiDung']) ? trim($_POST['NoiDung']) : '';

        // Kiểm tra file đính kèm (nếu có)
        $hasFile = isset($_FILES['FileDinhKem']) && $_FILES['FileDinhKem']['error'] !== UPLOAD_ERR_NO_FILE;

        if ($maCuocTrove <= 0) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                "success" => false,
                "message" => "Thiếu MaCuocTrove."
            ]);
            return;
        }

        if ($noiDung === '' && !$hasFile) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                "success" => false,
                "message" => "Vui lòng nhập nội dung hoặc chọn file đính kèm."
            ]);
            return;
        }

        $chatModel = $this->model("mChat");

        if (!$chatModel->doctorOwnsConversation($maBS, $maCuocTrove)) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                "success" => false,
                "message" => "Bạn không có quyền gửi tin trong cuộc trò chuyện này."
            ]);
            return;
        }

        // Chuẩn bị meta file (nếu có)
        $fileMeta = null;
        if ($hasFile) {
            $fileInfo = $_FILES['FileDinhKem'];

            if ($fileInfo['error'] !== UPLOAD_ERR_OK) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode([
                    "success" => false,
                    "message" => "Lỗi upload file (mã lỗi: " . $fileInfo['error'] . ")."
                ]);
                return;
            }

            $maxSize = 5 * 1024 * 1024; // 5MB
            if ($fileInfo['size'] > $maxSize) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode([
                    "success" => false,
                    "message" => "File quá lớn, vui lòng chọn file <= 5MB."
                ]);
                return;
            }

            $origName = basename($fileInfo['name']);
            $ext      = strtolower(pathinfo($origName, PATHINFO_EXTENSION));

            $allowedExt = [
                'jpg','jpeg','png','gif','webp',
                'pdf','doc','docx','xls','xlsx','txt'
            ];
            if (!in_array($ext, $allowedExt)) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode([
                    "success" => false,
                    "message" => "Định dạng file không được hỗ trợ."
                ]);
                return;
            }

            $rootPath   = dirname(__DIR__, 2); // tới KLTN_Benhvien
            $uploadDir  = $rootPath . '/public/uploads/chat/';
            if (!is_dir($uploadDir)) {
                @mkdir($uploadDir, 0775, true);
            }

            $randomPart = bin2hex(random_bytes(4));
            $newName    = date('Ymd_His') . '_' . $randomPart . '.' . $ext;
            $destPath   = $uploadDir . $newName;

            if (!move_uploaded_file($fileInfo['tmp_name'], $destPath)) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode([
                    "success" => false,
                    "message" => "Không thể lưu file lên server."
                ]);
                return;
            }

            $mimeType = !empty($fileInfo['type']) ? $fileInfo['type'] : null;
            $isImage  = in_array($ext, ['jpg','jpeg','png','gif','webp']) ? 1 : 0;

            $publicPath = '/KLTN_Benhvien/public/uploads/chat/' . $newName;

            $fileMeta = [
                'original_name' => $origName,
                'public_path'   => $publicPath,
                'mime_type'     => $mimeType,
                'size'          => (int)$fileInfo['size'],
                'is_image'      => $isImage
            ];
        }

        $ok = $chatModel->addMessage($maCuocTrove, 'BS', $maBS, $noiDung, $fileMeta);

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            "success" => $ok,
            "message" => $ok ? "Gửi tin nhắn thành công." : "Gửi tin nhắn thất bại, vui lòng thử lại."
        ]);
    }

    // ================== AJAX: BS LẤY TIN NHẮN MỚI (POLLING) ==================
    public function AjaxFetchTinNhanBS()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo "Method Not Allowed";
            return;
        }

        if (!isset($_SESSION['idnv'])) {
            http_response_code(401);
            echo "Not authenticated";
            return;
        }

        $maBS        = (int)$_SESSION['idnv'];
        $maCuocTrove = isset($_POST['MaCuocTrove']) ? (int)$_POST['MaCuocTrove'] : 0;
        $lastId      = isset($_POST['LastId']) ? (int)$_POST['LastId'] : 0;

        if ($maCuocTrove <= 0) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                "success" => false,
                "message" => "Thiếu MaCuocTrove."
            ]);
            return;
        }

        $chatModel = $this->model("mChat");

        if (!$chatModel->doctorOwnsConversation($maBS, $maCuocTrove)) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                "success" => false,
                "message" => "Bạn không có quyền xem cuộc trò chuyện này."
            ]);
            return;
        }

        $messages = $chatModel->getMessages($maCuocTrove, $lastId);

        // Đánh dấu đã xem phía BS
        $chatModel->markAsReadForDoctor($maCuocTrove, $maBS);

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            "success"  => true,
            "messages" => $messages
        ]);
    }

}
?>
