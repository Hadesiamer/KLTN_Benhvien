<?php
// Controller cho Nhân viên xét nghiệm (role = 6)
class NVXN extends Controller {

    public function __construct() {
        // Có thể thêm kiểm tra session/role nếu cần,
        // hiện tại layoutNVXN.php đã chặn role != 6.
    }

    // Trang mặc định: Quản lý xét nghiệm (dashboard NVXN)
    public function SayHi() {
        $this->view("layoutNVXN", [
            "Page" => "XN_quanlyxetnghiem" // file pages/XN_quanlyxetnghiem.php
        ]);
    }

    // Trang lịch làm việc của nhân viên xét nghiệm
    public function LichLamViec() {
        $idNV = $_SESSION["id"] ?? 0; // ID tài khoản / MaNV đang đăng nhập

        $mNVXN = $this->model("mNVXN");
        $lichLamViec = [];

        if ($idNV > 0) {
            // Lấy lịch làm việc từ model
            $lichLamViec = $mNVXN->GetLichLamViec($idNV);
        }

        $this->view("layoutNVXN", [
            "Page"        => "XN_lichlamviec",      // file pages/XN_lichlamviec.php
            "LichLamViec" => $lichLamViec
        ]);
    }

    // Trang thông tin cá nhân của NVXN
    public function ThongTinCaNhan() {
        $idNV = $_SESSION["id"] ?? 0;

        $mNVXN = $this->model("mNVXN");
        $thongTin = [];

        if ($idNV > 0) {
            // Lấy thông tin cá nhân từ bảng nhanvien
            $thongTin = $mNVXN->GetThongTinCaNhan($idNV);
        }

        $this->view("layoutNVXN", [
            "Page"     => "XN_thongtincanhan",  // file pages/XN_thongtincanhan.php
            "ThongTin" => $thongTin
        ]);
    }

    // Trang đổi mật khẩu + xử lý POST cho NVXN
    public function DoiMK() {
        // Nếu gửi form POST → xử lý đổi mật khẩu
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $old     = trim($_POST["old_password"] ?? "");
            $new     = trim($_POST["new_password"] ?? "");
            $confirm = trim($_POST["confirm_password"] ?? "");
            $idNV    = $_SESSION["id"] ?? 0;

            // Gọi model mNVXN
            $m = $this->model("mNVXN");
            $message = "";

            // Các bước kiểm tra tương tự Admin::DoiMK
            if ($idNV == 0) {
                $message = "Phiên đăng nhập không hợp lệ.";
            } elseif ($old === "" || $new === "" || $confirm === "") {
                $message = "Vui lòng điền đầy đủ thông tin.";
            } elseif (strlen($new) < 8 || strlen($new) > 16) {
                $message = "Mật khẩu mới phải từ 8 đến 16 ký tự.";
            } elseif ($new !== $confirm) {
                $message = "Mật khẩu xác nhận không khớp.";
            } else {
                // Kiểm tra mật khẩu cũ có đúng không
                if (!$m->KiemTraMatKhauCu($idNV, $old)) {
                    $message = "Mật khẩu cũ không đúng.";
                } else {
                    // Tiến hành đổi mật khẩu
                    if ($m->DoiMatKhau($idNV, $new)) {
                        $message = "Đổi mật khẩu thành công!";
                    } else {
                        $message = "Có lỗi khi cập nhật mật khẩu.";
                    }
                }
            }

            // Lưu flash message để layoutNVXN hiển thị
            $_SESSION["flash"] = $message;

            // Refresh chính trang DoiMK để tránh gửi lại POST
            header("Location: /KLTN_Benhvien/NVXN/DoiMK");
            exit;
        }

        // Nếu không phải POST → hiển thị form đổi mật khẩu
        $this->view("layoutNVXN", [
            "Page" => "XN_doimatkhau" // file pages/XN_doimatkhau.php
        ]);
    }
}
?>
