<?php
class Login extends Controller{
    function SayHi(){
        if (isset($_POST['nut'])) {
            $user = $_POST['username'];
            $pass = md5($_POST['pass']);
            //model
            $p = $this->model("mLogin");
            $login = $p->GetND($user, $pass);
    
            if ($login && mysqli_num_rows($login) > 0) {
                while ($r = mysqli_fetch_assoc($login)) {
                    $_SESSION['role'] = $r['MaPQ'];
                    $_SESSION['id'] = $r['ID'];
                    $_SESSION['idbn'] = $r['MaBN'];
					$_SESSION['ten'] = $r['HovaTen'];
                    $_SESSION['idql'] = $r['MaQL'];
                    $_SESSION['idnv'] = $r['MaNV'];
                }
                echo "<script>alert('Đăng nhập thành công');</script>";
                switch($_SESSION['role'])
                {   
                    case 1: header("refresh:0; url='/KLTN_Benhvien/QuanLy'"); break;
                    case 2: header("refresh:0; url='/KLTN_Benhvien/Bacsi'"); break;
                    case 3: header("refresh:0; url='/KLTN_Benhvien/NVYT'"); break;
                    case 4: header("refresh:0; url='/KLTN_Benhvien/NVNT'"); break;
                    case 5: header("refresh:0; url='/KLTN_Benhvien'"); break;
                }
                exit;
            } else {
                echo "<script>alert('Sai tên đăng nhập hoặc mật khẩu');</script>";
                header("refresh:0; url='Login'");
                exit;
            }
            
        }
        //view
        $this->view("layoutLogin");
    }
}
?>