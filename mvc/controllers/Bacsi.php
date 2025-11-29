<?php
// NhatCuong: Đặt múi giờ VN cho toàn bộ controller
date_default_timezone_set('Asia/Ho_Chi_Minh');

class Bacsi extends Controller
{
    // Hàm mặc định khi vào trang bác sĩ
    function SayHi()
    {
        $this->view("layoutBacsi", [
            "Page"
        ]);
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

    function XemLichLamViec()
    {
        $model = $this->model("MBacsi");
        $maNV = $_SESSION['idnv'];
        $lichLamViec = json_decode($model->XemLichLamViec($maNV), true);

        // Truyền dữ liệu sang view
        $this->view("layoutBacsi", [
            "Page" => "xemlichlamviec",
            "LichLamViec" => $lichLamViec
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
            $thongTinBenhNhan = $model->GetThongTinBenhNhan($maBN);
            $timmaBN = json_decode($thongTinBenhNhan, true);
            if (isset($timmaBN[0]['MaBN'])) {
                $maBN = $timmaBN[0]['MaBN'];
            }
            $phieuKham = $model->GetPhieuKham($maBN);

            $this->view("layoutBacsi", [
                "Page" => "xemthongtinbenhnhan",
                "ThongTinBenhNhan" => $thongTinBenhNhan,
                "PhieuKhamBenhNhan" => $phieuKham
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
            $thongTinBenhNhan = $model->GetThongTinBenhNhan($maBN);
            $phieuKhamBenhNhan = $model->GetPhieuKhamBenhNhan($maBN);
            $soLanKhamBenh = $model->GetSoLanKhamBenh($maBN);

            $this->view("LayoutXemLichSuKhamBenh", [
                "Page" => "DanhSachLichSuKham",
                "ThongTinBenhNhan" => $thongTinBenhNhan,
                "PhieuKhamBenhNhan" => $phieuKhamBenhNhan,
                "SoLanKhamBenh" => $soLanKhamBenh
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

            // 1) Chỉ tạo đơn thuốc (don_thuoc + ct_don_thuoc) nếu có ít nhất 1 thuốc hợp lệ
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

            // 2) Tạo phiếu khám
            // AddPK sẽ tự tìm MaDon tương ứng với MaLK + MaBN + MaBS
            // Nếu không có đơn thuốc -> MaDon trong phieukham sẽ là NULL
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

            // 3) Tạm thời: quay lại danh sách khám như cũ
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

    function Doimatkhau()
    {
        if (!isset($_SESSION["idnv"])) {
            echo "Chưa đăng nhập!";
            return;
        }

        $maNV = $_SESSION["idnv"];
        $model = $this->model("mBacsi");

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
}
