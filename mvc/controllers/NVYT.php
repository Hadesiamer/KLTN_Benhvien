<?php
class NVYT extends Controller {

    // Mặc định vào /NVYT sẽ vào layoutNVYT + trang quản lý lịch khám
    public function SayHi() {
        // Có thể load trước dữ liệu lịch khám nếu muốn
        $this->view("layoutNVYT", [
            "Page" => "YT_QuanLyLichKham"
        ]);
    }

    // ================== QUẢN LÝ LỊCH KHÁM ==================
    public function QuanLyLichKham() {
        // Giả sử đã có MaNV trong session (ID nhân viên y tế)
        $maNV = isset($_SESSION['MaNV']) ? $_SESSION['MaNV'] : null;

        $model = $this->model("mNVYT");

        // Lấy ngày được chọn hoặc hôm nay
        $selectedDate = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

        $lichKham = [];
        if ($maNV) {
            $lichKham = $model->GetLichKhamTheoNgay($maNV, $selectedDate);
        }

        $this->view("layoutNVYT", [
            "Page"       => "YT_QuanLyLichKham",
            "SelectedDate" => $selectedDate,
            "LichKham"   => $lichKham
        ]);
    }

    // ================== TẠO BỆNH NHÂN MỚI ==================
    public function TaoBenhNhanMoi() {
        $model = $this->model("mNVYT");

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $hovaten  = trim($_POST['HovaTen'] ?? '');
            $ngaysinh = $_POST['NgaySinh'] ?? '';
            $gioitinh = $_POST['GioiTinh'] ?? '';
            $diachi   = trim($_POST['DiaChi'] ?? '');
            $sodt     = trim($_POST['SoDT'] ?? '');
            $bhyt     = trim($_POST['BHYT'] ?? '');

            // Validate đơn giản phía server
            $errors = [];

            if ($hovaten === '') {
                $errors[] = "Họ và tên không được để trống.";
            }

            if ($ngaysinh === '') {
                $errors[] = "Ngày sinh không được để trống.";
            }

            if (!in_array($gioitinh, ['Nam', 'Nữ', 'Khác'])) {
                $errors[] = "Giới tính không hợp lệ.";
            }

            if ($sodt !== '' && !preg_match("/^[0-9]{8,15}$/", $sodt)) {
                $errors[] = "Số điện thoại chỉ được chứa số (8–15 chữ số).";
            }

            if (empty($errors)) {
                $rs = $model->TaoBenhNhanMoi($hovaten, $ngaysinh, $gioitinh, $diachi, $sodt, $bhyt);
                if ($rs['success']) {
                    $this->view("layoutNVYT", [
                        "Page"     => "YT_TaoBenhNhan",
                        "Message"  => "Tạo bệnh nhân mới thành công. Mã bệnh nhân: " . $rs['MaBN'],
                    ]);
                    return;
                } else {
                    $errors[] = "Không thể tạo bệnh nhân. Vui lòng thử lại.";
                }
            }

            // Có lỗi -> trả lại form + lỗi
            $this->view("layoutNVYT", [
                "Page"  => "YT_TaoBenhNhan",
                "Error" => implode("<br>", $errors),
                "Old"   => [
                    "HovaTen"  => $hovaten,
                    "NgaySinh" => $ngaysinh,
                    "GioiTinh" => $gioitinh,
                    "DiaChi"   => $diachi,
                    "SoDT"     => $sodt,
                    "BHYT"     => $bhyt
                ]
            ]);
        } else {
            // GET -> hiển thị form trống
            $this->view("layoutNVYT", [
                "Page" => "YT_TaoBenhNhan"
            ]);
        }
    }

    // ================== THÔNG TIN CÁ NHÂN ==================
    public function ThongTinCaNhan() {
        $model = $this->model("mNVYT");

        // Giả sử session có MaNV của nhân viên y tế
        $maNV = isset($_SESSION['MaNV']) ? $_SESSION['MaNV'] : null;

        $profile = null;
        if ($maNV) {
            $profile = $model->GetThongTinCaNhan($maNV);
        }

        $this->view("layoutNVYT", [
            "Page"    => "YT_ThongTinCaNhan",
            "Profile" => $profile
        ]);
    }

    public function CapNhatThongTinCaNhan() {
        $model = $this->model("mNVYT");
        $maNV  = isset($_SESSION['MaNV']) ? $_SESSION['MaNV'] : null;

        if (!$maNV) {
            $this->view("layoutNVYT", [
                "Page"  => "YT_ThongTinCaNhan",
                "Error" => "Không xác định được nhân viên đang đăng nhập."
            ]);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ngaysinh = $_POST['NgaySinh'] ?? '';
            $gioitinh = $_POST['GioiTinh'] ?? '';
            $email    = trim($_POST['EmailNV'] ?? '');
            $sodt     = trim($_POST['SoDT'] ?? '');

            $errors = [];

            if ($ngaysinh === '') {
                $errors[] = "Ngày sinh không được để trống.";
            }

            if (!in_array($gioitinh, ['Nam', 'Nữ', 'Khác'])) {
                $errors[] = "Giới tính không hợp lệ.";
            }

            if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Email không hợp lệ.";
            }

            if ($sodt !== '' && !preg_match("/^[0-9]{8,15}$/", $sodt)) {
                $errors[] = "Số điện thoại chỉ được chứa số (8–15 chữ số).";
            }

            if (empty($errors)) {
                $ok = $model->CapNhatThongTinCaNhan($maNV, $ngaysinh, $gioitinh, $email, $sodt);
                if ($ok) {
                    $profile = $model->GetThongTinCaNhan($maNV);
                    $this->view("layoutNVYT", [
                        "Page"    => "YT_ThongTinCaNhan",
                        "Profile" => $profile,
                        "Message" => "Cập nhật thông tin cá nhân thành công."
                    ]);
                    return;
                } else {
                    $errors[] = "Lỗi hệ thống khi cập nhật thông tin.";
                }
            }

            $profile = $model->GetThongTinCaNhan($maNV);
            $this->view("layoutNVYT", [
                "Page"    => "YT_ThongTinCaNhan",
                "Profile" => $profile,
                "Error"   => implode("<br>", $errors)
            ]);
        }
    }

    // ================== ĐỔI MẬT KHẨU ==================
    public function DoiMatKhau() {
        // Kiểm tra đăng nhập & đúng role (giả sử role = 2 là NVYT)
        if (!isset($_SESSION["id"]) || !isset($_SESSION["role"]) || $_SESSION["role"] != 3) {
            echo "<script>alert('Bạn không có quyền truy cập vào trang này');</script>";
            header("refresh: 0; url='/KLTN_Benhvien'");
            exit;
        }

        $model = $this->model("mNVYT");

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $idTK   = $_SESSION["id"]; // ID tài khoản trong bảng taikhoan
            $old    = $_POST["old_password"] ?? '';
            $new    = $_POST["new_password"] ?? '';
            $confirm= $_POST["confirm_password"] ?? '';

            if ($new === '' || $confirm === '' || $old === '') {
                $this->view("layoutNVYT", [
                    "Page"  => "YT_DoiMatKhau",
                    "Error" => "Vui lòng nhập đầy đủ các trường."
                ]);
                return;
            }

            if ($new !== $confirm) {
                $this->view("layoutNVYT", [
                    "Page"  => "YT_DoiMatKhau",
                    "Error" => "Mật khẩu xác nhận không khớp."
                ]);
                return;
            }

            // Kiểm tra mật khẩu cũ
            $kq = $model->KiemTraMatKhauCuNVYT($idTK, $old);
            if ($kq) {
                $doi = $model->DoiMatKhauNVYT($idTK, $new);
                if ($doi) {
                    $this->view("layoutNVYT", [
                        "Page"    => "YT_DoiMatKhau",
                        "Message" => "Đổi mật khẩu thành công."
                    ]);
                    return;
                } else {
                    $this->view("layoutNVYT", [
                        "Page"  => "YT_DoiMatKhau",
                        "Error" => "Lỗi hệ thống, không thể đổi mật khẩu."
                    ]);
                    return;
                }
            } else {
                $this->view("layoutNVYT", [
                    "Page"  => "YT_DoiMatKhau",
                    "Error" => "Mật khẩu hiện tại không chính xác."
                ]);
                return;
            }
        }

        // GET -> hiển thị form đổi mật khẩu
        $this->view("layoutNVYT", [
            "Page" => "YT_DoiMatKhau"
        ]);
    }

    
}
?>
