<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');

class Qlydd extends Controller
{
    // Trang mặc định khi vào /Qlydd
    public function SayHi()
    {
        $model = $this->model("mQlydd");
        $dsCauHinhCa = $model->GetCauHinhCa();

        $this->view("layoutQLDiemDanh", [
            "Page"      => "dd_cauhinh_ca",
            "CauHinhCa" => $dsCauHinhCa
        ]);
    }

    // ================== CHECK QUYỀN QUẢN LÝ ==================
    private function requireManager()
    {
        if (!isset($_SESSION["role"]) || $_SESSION["role"] != 1) {
            echo "<script>alert('Bạn không có quyền truy cập');</script>";
            header("refresh: 0; url='/KLTN_Benhvien'");
            exit;
        }
    }

    // ================== CẤU HÌNH CA LÀM VIỆC ==================
    public function DD_CauHinhCa()
    {
        $this->requireManager();

        /** @var mQlydd $model */
        $model = $this->model("mQlydd");

        // Lưu toàn bộ cấu hình
        if ($_SERVER["REQUEST_METHOD"] === "POST"
            && isset($_POST["action"])
            && $_POST["action"] === "save_all"
            && isset($_POST["rows"])
            && is_array($_POST["rows"])
        ) {
            $rows = $_POST["rows"];
            $hasError = false;

            foreach ($rows as $maCa => $row) {
                $maCa = (int)$maCa;

                $gioBatDau = isset($row["GioBatDau"]) ? trim($row["GioBatDau"]) : "";
                $gioKetThuc = isset($row["GioKetThuc"]) ? trim($row["GioKetThuc"]) : "";
                $ghiChu = isset($row["GhiChu"]) ? trim($row["GhiChu"]) : "";

                // [MỚI] Lấy giới hạn sớm / trễ
                $gioiHanSomPhut = isset($row["GioiHanSomPhut"]) ? (int)$row["GioiHanSomPhut"] : 0;
                $gioiHanTrePhut = isset($row["GioiHanTrePhut"]) ? (int)$row["GioiHanTrePhut"] : 0;

                if ($gioBatDau === "" || $gioKetThuc === "") {
                    $hasError = true;
                    continue;
                }

                if ($gioBatDau >= $gioKetThuc) {
                    $hasError = true;
                    continue;
                }

                // Không cho phép giá trị âm
                if ($gioiHanSomPhut < 0) {
                    $gioiHanSomPhut = 0;
                }
                if ($gioiHanTrePhut < 0) {
                    $gioiHanTrePhut = 0;
                }

                $ok = $model->UpdateCauHinhCa(
                    $maCa,
                    $gioBatDau . ":00",
                    $gioKetThuc . ":00",
                    $gioiHanSomPhut,
                    $gioiHanTrePhut,
                    $ghiChu
                );

                if (!$ok) {
                    $hasError = true;
                }
            }

            if ($hasError) {
                $_SESSION["toast_type"] = "error";
                $_SESSION["toast_message"] = "Một số cấu hình ca không được lưu. Vui lòng kiểm tra lại dữ liệu.";
            } else {
                $_SESSION["toast_type"] = "success";
                $_SESSION["toast_message"] = "Cập nhật cấu hình ca làm việc thành công.";
            }

            header("Location: ./DD_CauHinhCa");
            exit;
        }

        $dsCauHinhCa = $model->GetCauHinhCa();

        $this->view("layoutQLDiemDanh", [
            "Page"      => "dd_cauhinh_ca",
            "CauHinhCa" => $dsCauHinhCa
        ]);
    }

    // ================== QUẢN LÝ MẪU KHUÔN MẶT: DANH SÁCH ==================
    public function DD_QuanLyMau()
    {
        $this->requireManager();

        /** @var mQlydd $model */
        $model = $this->model("mQlydd");

        // Dùng REQUEST cho chắc: GET hoặc POST đều đọc được
        $chucVu = isset($_REQUEST["chucvu"]) ? trim($_REQUEST["chucvu"]) : "";
        $maKhoa = isset($_REQUEST["makhoa"]) ? trim($_REQUEST["makhoa"]) : "";
        $soDT   = isset($_REQUEST["sdt"]) ? trim($_REQUEST["sdt"]) : "";

        // Nếu chức vụ khác Bác sĩ thì bỏ lọc khoa
        if ($chucVu !== 'Bác sĩ') {
            $maKhoa = "";
        }

        $dsKhoa     = $model->GetDanhSachKhoa();
        $dsNhanVien = $model->GetNhanVienForFace($chucVu, $maKhoa, $soDT);

        $this->view("layoutQLDiemDanh", [
            "Page"         => "dd_quanly_mau",
            "NhanVien"     => $dsNhanVien,
            "DanhSachKhoa" => $dsKhoa,
            "FilterChucVu" => $chucVu,
            "FilterMaKhoa" => $maKhoa,
            "FilterSoDT"   => $soDT
        ]);
    }

    // ================== QUẢN LÝ MẪU KHUÔN MẶT: ENROLL MỘT NV ==================

    // Trang hiển thị camera + thông tin NV để đăng ký/cập nhật mẫu
    public function DD_Enroll()
    {
        $this->requireManager();

        $maNV = 0;
        if (isset($_POST["MaNV"])) {
            $maNV = (int)$_POST["MaNV"];
        } elseif (isset($_GET["MaNV"])) {
            $maNV = (int)$_GET["MaNV"];
        }

        if ($maNV <= 0) {
            $_SESSION["toast_type"] = "error";
            $_SESSION["toast_message"] = "Không xác định được nhân viên cần đăng ký khuôn mặt.";
            header("Location: ./DD_QuanLyMau");
            exit;
        }

        /** @var mQlydd $model */
        $model = $this->model("mQlydd");

        $nv = $model->GetNhanVienById($maNV);
        if (!$nv) {
            $_SESSION["toast_type"] = "error";
            $_SESSION["toast_message"] = "Không tìm thấy thông tin nhân viên.";
            header("Location: ./DD_QuanLyMau");
            exit;
        }

        $template = $model->GetFaceTemplateByMaNV($maNV);

        $this->view("layoutQLDiemDanh", [
            "Page"     => "dd_enroll",
            "NhanVien" => $nv,
            "Template" => $template
        ]);
    }

    // API AJAX lưu descriptor khuôn mặt
    public function DD_SaveTemplate()
    {
        $this->requireManager();

        header("Content-Type: application/json; charset=utf-8");

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            http_response_code(405);
            echo json_encode([
                "success" => false,
                "message" => "Phương thức không được hỗ trợ."
            ]);
            exit;
        }

        $raw = file_get_contents("php://input");
        $payload = json_decode($raw, true);

        if (!is_array($payload)) {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "message" => "Dữ liệu không hợp lệ."
            ]);
            exit;
        }

        $maNV = isset($payload["MaNV"]) ? (int)$payload["MaNV"] : 0;
        $descriptor = isset($payload["Descriptor"]) ? $payload["Descriptor"] : null;

        if ($maNV <= 0 || !is_array($descriptor) || empty($descriptor)) {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "message" => "Thiếu MaNV hoặc Descriptor."
            ]);
            exit;
        }

        // Ép từng phần tử về float
        $cleanDescriptor = [];
        foreach ($descriptor as $v) {
            $cleanDescriptor[] = (float)$v;
        }

        $descriptorJson = json_encode($cleanDescriptor);

        /** @var mQlydd $model */
        $model = $this->model("mQlydd");
        $ok = $model->UpsertFaceTemplate($maNV, $descriptorJson);

        if ($ok) {
            echo json_encode([
                "success" => true,
                "message" => "Lưu mẫu khuôn mặt thành công."
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                "success" => false,
                "message" => "Lỗi hệ thống, không thể lưu mẫu khuôn mặt."
            ]);
        }
        exit;
    }

    // ================== ĐIỂM DANH KHUÔN MẶT ==================

    // Trang quét khuôn mặt
    public function DD_QuetMat()
    {
        $this->requireManager();

        /** @var mQlydd $model */
        $model = $this->model("mQlydd");

        $templates  = $model->GetAllFaceTemplates();
        $cauHinhCa  = $model->GetCauHinhCa();

        $this->view("layoutQLDiemDanh", [
            "Page"          => "dd_quetmat",
            "FaceTemplates" => $templates,
            "CauHinhCa"     => $cauHinhCa
        ]);
    }

    // API: ghi nhận điểm danh sau khi đã nhận diện được MaNV trên client
    public function DD_CheckIn()
    {
        $this->requireManager();
        header("Content-Type: application/json; charset=utf-8");

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            http_response_code(405);
            echo json_encode([
                "success" => false,
                "message" => "Phương thức không được hỗ trợ."
            ]);
            exit;
        }

        $raw = file_get_contents("php://input");
        $payload = json_decode($raw, true);

        if (!is_array($payload)) {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "message" => "Dữ liệu không hợp lệ."
            ]);
            exit;
        }

        $maNV     = isset($payload["MaNV"]) ? (int)$payload["MaNV"] : 0;
        $distance = isset($payload["Distance"]) ? (float)$payload["Distance"] : null;

        if ($maNV <= 0) {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "message" => "Thiếu mã nhân viên."
            ]);
            exit;
        }

        // Thời gian hiện tại
        $now = new DateTime(); // dùng timezone mặc định của PHP
        $dateStr = $now->format("Y-m-d");
        $timeStr = $now->format("H:i:s");
        $dateTimeStr = $now->format("Y-m-d H:i:s");

        /** @var mQlydd $model */
        $model = $this->model("mQlydd");

        // Tìm ca làm việc hiện tại theo clock
        $caRow = $model->GetCaLamViecByTime($timeStr);
        if (!$caRow) {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "message" => "Hiện không nằm trong khung giờ ca làm việc nào, không thể điểm danh."
            ]);
            exit;
        }

        $caLamViec = $caRow["CaLamViec"]; // 'Sáng' hoặc 'Chiều'

        // Tìm lịch làm việc của NV hôm nay với ca tương ứng
        $llvRow = $model->GetLichLamViecByNVDateCa($maNV, $dateStr, $caLamViec);
        if (!$llvRow) {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "message" => "Hôm nay nhân viên không có lịch làm việc ca " . $caLamViec . "."
            ]);
            exit;
        }

        $maLLV = (int)$llvRow["MaLLV"];

        // Kiểm tra đã điểm danh chưa
        $ddRow = $model->GetDiemDanhByLLVNV($maLLV, $maNV);
        if ($ddRow) {
            echo json_encode([
                "success" => true,
                "already" => true,
                "message" => "Nhân viên đã điểm danh lúc " . $ddRow["ThoiGianDiemDanh"] . ".",
                "KetQua"  => $ddRow["KetQua"]
            ]);
            exit;
        }

        // ========== Tính kết quả Đúng giờ / Đi sớm / Đi trễ dựa trên cấu hình ==========
        $caStart = DateTime::createFromFormat("Y-m-d H:i:s", $dateStr . " " . $caRow["GioBatDau"]);
        $diffSeconds = $now->getTimestamp() - $caStart->getTimestamp();
        $diffMinutes = (int) floor($diffSeconds / 60);

        $gioiHanSomPhut = isset($caRow["GioiHanSomPhut"]) ? (int)$caRow["GioiHanSomPhut"] : 0;
        $gioiHanTrePhut = isset($caRow["GioiHanTrePhut"]) ? (int)$caRow["GioiHanTrePhut"] : 0;

        // Nếu đến quá sớm hơn giới hạn cho phép -> không cho điểm danh
        if ($gioiHanSomPhut > 0 && $diffSeconds < -$gioiHanSomPhut * 60) {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "message" => "Bạn đến quá sớm so với giờ bắt đầu ca. Chỉ cho phép điểm danh trước tối đa "
                    . $gioiHanSomPhut . " phút."
            ]);
            exit;
        }

        if ($diffSeconds < 0) {
            // Đến sớm nhưng trong khoảng cho phép
            $ketQua = "Đi sớm " . abs($diffMinutes) . " phút";
        } elseif ($diffSeconds <= $gioiHanTrePhut * 60) {
            // Trong khoảng trễ cho phép vẫn xem là đúng giờ
            $ketQua = "Đúng giờ";
        } else {
            // Trễ hơn giới hạn trễ
            $ketQua = "Đi trễ " . $diffMinutes . " phút";
        }

        $ghiChu = "Điểm danh khuôn mặt";
        if ($distance !== null) {
            $ghiChu .= " (distance = " . number_format($distance, 3, '.', '') . ")";
        }

        $ok = $model->InsertDiemDanh($maLLV, $maNV, $dateTimeStr, $ketQua, $ghiChu);

        if ($ok) {
            echo json_encode([
                "success"   => true,
                "already"   => false,
                "message"   => "Điểm danh thành công.",
                "KetQua"    => $ketQua,
                "ThoiGian"  => $dateTimeStr
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                "success" => false,
                "message" => "Lỗi hệ thống, không thể ghi nhận điểm danh."
            ]);
        }
        exit;
    }

    // ================== STUB CÁC CHỨC NĂNG KHÁC ==================
    public function DD_DanhSachNgay()
    {
        $this->requireManager();

        $this->view("layoutQLDiemDanh", [
            "Page" => "dd_danhsach_ngay"
        ]);
    }

    public function DD_DoiChieu()
    {
        $this->requireManager();

        $this->view("layoutQLDiemDanh", [
            "Page" => "dd_doichieu_lich"
        ]);
    }

    public function DD_ThongKe()
    {
        $this->requireManager();

        $this->view("layoutQLDiemDanh", [
            "Page" => "dd_thongke"
        ]);
    }
}
?>
