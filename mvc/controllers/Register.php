<?php
class Register extends Controller{
    public $DKModel;

    public function __construct(){
        $this->DKModel = $this->model("mRegister");
    }
    
    public function SayHi(){
        $this->view("layoutRegister",[]);
    }

    public function BNDK() {
        if(isset($_POST["btn-dk"])) {
            $username = $_POST["txtuser"];
            $password = $_POST["password"];
            $password2 = $_POST['password2'];
            
            if ($password !== $password2) {
                echo "<script>alert('Mật khẩu và mật khẩu nhập lại không khớp!')</script>";
                exit;
            }
            
            $password = md5($password);

            $kq = $this->DKModel->DK($username, $password);

            $result = json_decode($kq, true);
            if ($result['success']) {
                $_SESSION['last_id'] = $result['last_id'];
                echo "<script>alert('Đăng ký thành công! Mời bạn tạo hồ sơ.')</script>";
            } else {
                echo "<script>alert('" . $result['message'] . "')</script>";
            }
            
            $this->view("layoutRegister", [
                "result" => $kq
            ]);
        }
    }

    public function BNHS(){
        if(isset($_SESSION['last_id'])){
            $id = $_SESSION['last_id'];
        }else if(isset($_SESSION['id'])){
            $id = $_SESSION['id'];
        }

        $kq = $this->DKModel->GetSDT($id);
        
        $this->view("layoutTaoHS",[
            "SDT"=>$kq
        ]);
    }
    public function XNHS(){
        if(isset($_POST['btn-xn'])){
            if(isset($_SESSION['last_id'])){
                $id = $_SESSION['last_id'];
            }else if(isset($_SESSION['id'])){
                $id = $_SESSION['id'];
            }
            $hoten = $_POST["ten"];
            $gioitinh = $_POST["gt"];
            $ngaysinh = $_POST["ns"];
            $sdt = $_POST["sdt"];
            $diachi = $_POST["diachi"];
            $email = $_POST["email"];
            $bhyt = $_POST["bhyt"];
            $mapk = $_POST["maphieukham"];
            $hs = $this->DKModel->TaoHS($hoten, $gioitinh, $ngaysinh, $sdt, $diachi, $email, $bhyt, $mapk, $id);
            $result = json_decode($hs, true);

            if ($result['success']) {
                $message = $result['message']; // "Thêm hồ sơ thành công"
            } else {
                $message = $result['message']; // "Email đã tồn tại hoặc đã được sử dụng!"
            }

            $this->view("layoutTaoHS", [
                "result" => $result,
                "message" => $message
            ]);
        }
    }
}
?>