<?php
class Admin extends Controller{

    public function __construct(){
        
    }

    // Trang tổng quan admin
    function SayHi(){
        $this->view("layoutadmin",["Page"=>"admintongquan"]);
    }

    // Trang thêm nhân viên
    function ThemNV(){
        $this->view("layoutadmin",["Page"=>"adminthemnhanvien"]);
    }

    // Trang đổi mật khẩu + xử lý POST
    function DoiMK(){
        // Nếu gửi form POST → xử lý đổi mật khẩu
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Lấy dữ liệu từ form
            $old = trim($_POST["old_password"] ?? "");
            $new = trim($_POST["new_password"] ?? "");
            $confirm = trim($_POST["confirm_password"] ?? "");
            $idAdmin = $_SESSION["id"] ?? 0;

            // Model admin
            $m = $this->model("mAdmin");

            // Kiểm tra hợp lệ cơ bản
            if ($idAdmin == 0) {
                $message = "Phiên đăng nhập không hợp lệ.";
            } elseif ($old === "" || $new === "" || $confirm === "") {
                $message = "Vui lòng điền đầy đủ thông tin.";
            } elseif (strlen($new) < 8 || strlen($new) > 16) {
                $message = "Mật khẩu mới phải từ 8 đến 16 ký tự.";
            } elseif ($new !== $confirm) {
                $message = "Mật khẩu xác nhận không khớp.";
            } else {
                // Kiểm tra mật khẩu cũ có đúng không
                if (!$m->KiemTraMatKhauCu($idAdmin, $old)) {
                    $message = "Mật khẩu cũ không đúng.";
                } else {
                    // Tiến hành đổi mật khẩu
                    if ($m->DoiMatKhau($idAdmin, $new)) {
                        $message = "Đổi mật khẩu thành công!";
                    } else {
                        $message = "Có lỗi khi cập nhật mật khẩu.";
                    }
                }
            }

            // Lưu thông báo tạm vào session để hiển thị lại
            $_SESSION["flash"] = $message;

            // Refresh lại trang đổi mật khẩu để tránh gửi lại POST
            header("Location: /KLTN_Benhvien/Admin/DoiMK");
            exit;
        }

        // Nếu không phải POST → hiển thị giao diện đổi mật khẩu
        $this->view("layoutadmin", [
            "Page" => "admindoimatkhau"
        ]);
    }

    // Trang chặn truy cập
    function ChanTruyCap(){
        $this->view("layoutadmin",["Page"=>"adminchantruycap"]);
    }
}
?>
