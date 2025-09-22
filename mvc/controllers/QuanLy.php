<?php
class QuanLy extends Controller {
    function SayHi()
    {
        $this->view("layoutQL");
    }
    //QuanLy_Nguoi thuc hien: Dkhuong
    function DSBS() {
        $ql = $this->model("mQLBS");
        $bacsi = json_decode($ql->GetAllBS(), true);

        $this->view("layoutQLyBS", [
            "Page" => "qlbs",
            "BacSi" => $bacsi
        ]);
    }

    function TTBN() {
        $ql = $this->model("mQuanLy");
        $benhnhan = null;
        $phieukham = [];
        $found = false;
        
        if(isset($_POST['nutBack'])) {
            $maBN = $_POST['back'];
            $benhnhan = json_decode($ql->Get1BN($maBN), true);
        
            if($benhnhan && !empty($benhnhan)) {
                $phieukham = json_decode($ql->GetPK($maBN), true);
                $found = true;
            }
        }
        else if(isset($_POST['btnsearch'])) {
            $maBN = $_POST['txtsearch'];
            $benhnhan = json_decode($ql->Get1BN($maBN), true);
        
            if($benhnhan && !empty($benhnhan)) {
                $phieukham = json_decode($ql->GetPK($maBN), true);
                $found = true;
            }
        }

        $this->view("layoutQLy", [
            "Page" => "qlpk",
            "QuanLy" => json_encode([
                "BNhan" => $benhnhan,
                "PhieuKham" => $phieukham,
                "Found" => $found
            ])
        ]);
    }

    function CTPK() {
        if (isset($_POST["btnCTPK"])) {
            $MaPK = $_POST["ctpk"];
            $ql = $this->model("mQuanLy");
            $this->view("layoutQLy", [
                "Page" => "qlchitietpk",
                "CTPK" => $ql->GetCTPK($MaPK)
            ]);
        }
    }

    function LLV($date = null) {
        $ql = $this->model("mQuanLy");

        if (isset($_POST['btnDKL'])) {
            $MaNV = $_POST['MaNVien'];
            $NgayLamViec = $_POST['NgayLamViec'];
            $CaLamViec = $_POST['cl'];
    
            if (empty($MaNV)) {
                $_SESSION['message'] = "Bạn phải chọn 1 bác sĩ để thêm lịch làm việc!";
                $_SESSION['message_type'] = "error";
                header('Location: ' . $_SERVER['REQUEST_URI']);
                exit();
            }
    
        $isEmployeeInShift = $ql->CheckEmployeeInShift($MaNV, $NgayLamViec, $CaLamViec);

        if ($isEmployeeInShift) {
            $_SESSION['message'] = "Bác sĩ đã có trong ca làm việc này!";
            $_SESSION['message_type'] = "error";
        } else {
            // Kiểm tra số lượng nhân viên trong ca làm việc
            $employeeCount = $ql->CountEmployeeInShift($NgayLamViec, $CaLamViec);

            if ($employeeCount < 5) {
                // Nếu số lượng nhân viên trong ca chưa đủ 5, thực hiện thêm lịch làm việc
                $result = $ql->AddLLV($MaNV, $NgayLamViec, $CaLamViec);

                if ($result) {
                    $_SESSION['message'] = "Thêm lịch làm việc thành công!";
                    $_SESSION['message_type'] = "success";
                } else {
                    $_SESSION['message'] = "Thêm lịch làm việc thất bại!";
                    $_SESSION['message_type'] = "error";
                }
            } else {
                // Nếu ca làm việc đã đầy (>= 5 người)
                $_SESSION['message'] = "Ca làm việc đã đầy, không thể thêm!";
                $_SESSION['message_type'] = "error";
            }
        }
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit();
    }
    
        if(isset($_POST['MaNV'])) {
            $maNV = $_POST['MaNV'];
            $NgayLamViec = $_POST['NgayLamViec'];
            $CaLamViec = $_POST['CaLamViec'];
            $result = $ql->DelLLV($maNV, $NgayLamViec, $CaLamViec);
            
            if($result) {
                $_SESSION['message'] = "Xóa ca làm việc thành công!";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "Xóa ca làm việc thất bại!";
                $_SESSION['message_type'] = "error";
            }
            header('Location: ' . $_SERVER['REQUEST_URI']);
        exit();
        }

        if (!$date) {
            $date = date('Y-m-d');
        }
    
        $khoa = $ql->GetDanhSachKhoa();
        $maKhoa = 'A';
        if (isset($_POST['khoaSelect']) && $_POST['khoaSelect'] != '') {
            $maKhoa = $_POST['khoaSelect'];
        }
        // Lấy danh sách bác sĩ theo khoa nếu không chọn tkb trống
        if ($maKhoa != 'A') {
            $listBacSi = $ql->GetLichLamViecTheoKhoa($maKhoa);
        } else {
            $listBacSi = $ql->GetLichLamViecTheoKhoa($maKhoa);
        }
    
        $this->view("layoutQly2", [
            "Page" => "qlllv",
            "LLV" => $listBacSi,
            "Khoa" => $khoa,
            "SelectedDate" => $date,
            "SelectedKhoa" => $maKhoa,
            "BS" => $ql->GetDSBS()
        ]);
    }
    
    function ThongKe() {
        $ql = $this->model("mQuanLy");
    
        // Lấy dữ liệu tổng tiền theo tháng
        $thongKeTheoThang = $ql->GetThongKeTheoThang();
        // xử lý lấy thời gian
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $homnay = date('Y-m-d');
        $week = date('w', strtotime($homnay));
        $dautuan = date('Y-m-d', strtotime($homnay . ' - ' . ($week ? $week - 1 : 6) . ' days'));
        $cuoituan =date('Y-m-d', strtotime($dautuan . ' + 6 days'));
        $thongKeTheoTuan = $ql->GetThongKeTheoTuan($dautuan, $cuoituan);
    
        $this->view("layoutQLy3", [
            "Page" => "thongke",
            "ThongKeThang" => $thongKeTheoThang,
            "ThongKeTuan" => $thongKeTheoTuan
        ]);
    }

    // phần của Quang Huy Quản Lý Bác sĩ/ NVYT
    public function GetDashboardCounts() {
        $qlBS = $this->model("mQLBS");
        $qlNVYT = $this->model("mQLNVYT");
        
        $doctorCount = $qlBS->GetDoctorCount();
        $staffCount = $qlNVYT->GetStaffCount();
        
        return [
            'doctorCount' => $doctorCount,
            'staffCount' => $staffCount
        ];
    }
    function CTBS() {
        if (isset($_POST["btnCTBS"])) {
            $MaNV = $_POST["ctbs"];

            if (!empty($MaNV)) {
                $ql = $this->model("mQLBS");
                $chitietBS = json_decode($ql->Get1BS($MaNV), true);

                if ($chitietBS) {
                    $this->view("layoutQLyBS", [
                        "Page" => "qlchitietbs",
                        "CTBS" => $chitietBS
                    ]);
                } else {
                    $this->view("layoutQLyBS", [
                        "Page" => "qlchitietbs",
                        "Error" => "Không tìm thấy thông tin bác sĩ với mã đã nhập."
                    ]);
                }
            } else {
                $this->view("layoutQLyBS", [
                    "Page" => "qlchitietbs",
                    "Error" => "Mã nhân viên không hợp lệ."
                ]);
            }
        } else {
            $this->view("layoutQLyBS", [
                "Page" => "qlchitietbs",
                "Error" => "Yêu cầu không hợp lệ."
            ]);
        }
    }


    function SuaBS() {
        if (isset($_POST["btnSuaBS"])) {
            $MaNV = $_POST["MaNV"];
            $NgaySinh = $_POST["NgaySinh"];
            $GioiTinh = $_POST["GioiTinh"];
            $EmailNV = $_POST["EmailNV"];
            $MaKhoa = $_POST["MaKhoa"];

            $ql = $this->model("mQLBS");

            // Lấy thông tin hiện tại của bác sĩ
            $currentBS = json_decode($ql->Get1BS($MaNV), true);

            if ($EmailNV !== $currentBS['EmailNV'] && $ql->CheckExistingEmail($EmailNV, $MaNV)) {
                $this->view("layoutQLyBS", [
                    "Page" => "qlchitietbs",
                    "Error" => "Email đã tồn tại trong hệ thống.",
                    "CTBS" => $currentBS
                ]);
                return;
            }

            $result = $ql->UpdateBS($MaNV, $NgaySinh, $GioiTinh, $EmailNV, $MaKhoa);

            if ($result) {
                $_SESSION['success_message'] = "Cập nhật thông tin Bác sĩ thành công.";
                header("Location: ./DSBS");
            } else {
                $this->view("layoutQLyBS", [
                    "Page" => "qlchitietbs",
                    "Error" => "Không thể cập nhật thông tin bác sĩ.",
                    "CTBS" => $currentBS
                ]);
            }
        } else {
            $this->view("layoutQLyBS", [
                "Page" => "qlchitietbs",
                "Error" => "Yêu cầu không hợp lệ."
            ]);
        }
    }
    function XoaBS() {
        if (isset($_POST["btnXoaBS"])) {
            $MaNV = $_POST["MaNV"];

            $ql = $this->model("mQLBS");
            $result = $ql->DeleteBS($MaNV);

            if ($result) {
                $_SESSION['success_message'] = "Xóa thông tin Bác sĩ thành công.";
                header("Location: ./DSBS");
            } else {
                $this->view("layoutQLyBS", [
                    "Page" => "qlbs",
                    "Error" => "Không thể xóa bác sĩ."
                ]);
            }
        } else {
            $this->view("layoutQLyBS", [
                "Page" => "qlbs",
                "Error" => "Yêu cầu không hợp lệ."
            ]);
        }
    }

    function ThemBS() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $HovaTen = $_POST['HovaTen'];
            $NgaySinh = $_POST['NgaySinh'];
            $GioiTinh = $_POST['GioiTinh'];
            $SoDT = $_POST['SoDT'];
            $EmailNV = $_POST['EmailNV'];
            $MaKhoa = $_POST['MaKhoa'];
    
            // Kiểm tra dữ liệu đầu vào
            if (!preg_match("/^[a-zA-ZÀ-ỹ\s]+$/u", $HovaTen)) {
                $this->view("layoutQLyBS", [
                    "Page" => "thembacsi",
                    "Error" => "Họ tên không được chứa ký tự đặc biệt và số."
                ]);
                return;
            }
    
            if (!preg_match("/^[0-9]+$/", $SoDT)) {
                $this->view("layoutQLyBS", [
                    "Page" => "thembacsi",
                    "Error" => "Số điện thoại chỉ được chứa số."
                ]);
                return;
            }
    
            $ql = $this->model("mQLBS");
            
            // Kiểm tra số điện thoại và email trùng lặp
            if ($ql->CheckExistingPhoneNumber($SoDT)) {
                $this->view("layoutQLyBS", [
                    "Page" => "thembacsi",
                    "Error" => "Số điện thoại đã tồn tại trong hệ thống."
                ]);
                return;
            }
    
            if ($ql->CheckExistingEmail($EmailNV)) {
                $this->view("layoutQLyBS", [
                    "Page" => "thembacsi",
                    "Error" => "Email đã tồn tại trong hệ thống."
                ]);
                return;
            }
            
            $result = $ql->AddBS($HovaTen, $NgaySinh, $GioiTinh, $SoDT, $EmailNV, $MaKhoa);
    
            if ($result === true) {
                $_SESSION['success_message'] = "Thêm thông tin bác sĩ mới thành công.";
                header("Location: ./DSBS");
            } else {
                $this->view("layoutQLyBS", [
                    "Page" => "thembacsi",
                    "Error" => $result
                ]);
            }
        } else {
            $this->view("layoutQLyBS", [
                "Page" => "thembacsi"
            ]);
        }
    }
    function DSNVYT() {
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $ql = $this->model("mQLNVYT");
        $nhanvien = $ql->GetAllNVYT($search);

        $this->view("layoutQLyBS", [
            "Page" => "qlnvyt",
            "NhanVien" => $nhanvien
        ]);
    }

    function CTNVYT() {
        if (isset($_POST["btnCTNVYT"])) {
            $MaNV = $_POST["ctnv"];

            if (!empty($MaNV)) {
                $ql = $this->model("mQLNVYT");
                $chitietNV = json_decode($ql->Get1NVYT($MaNV), true);

                if ($chitietNV) {
                    $this->view("layoutQLyBS", [
                        "Page" => "qlchitietnvyt",
                        "CTNV" => $chitietNV
                    ]);
                } else {
                    $this->view("layoutQLyBS", [
                        "Page" => "qlchitietnvyt",
                        "Error" => "Không tìm thấy thông tin bác sĩ với mã đã nhập."
                    ]);
                }
            } else {
                $this->view("layoutQLyBS", [
                    "Page" => "qlchitietnvyt",
                    "Error" => "Mã nhân viên không hợp lệ."
                ]);
            }
        } else {
            $this->view("layoutQLyBS", [
                "Page" => "qlchitietnvyt",
                "Error" => "Yêu cầu không hợp lệ."
            ]);
        }
    }
    
    function SuaNVYT() {
        if (isset($_POST["btnSuaNVYT"])) {
            $MaNV = $_POST["MaNV"];
            $NgaySinh = $_POST["NgaySinh"];
            $GioiTinh = $_POST["GioiTinh"];
            $EmailNV = $_POST["EmailNV"];

            $ql = $this->model("mQLNVYT");

            // Lấy thông tin hiện tại của nhân viên y tế
            $currentNV = json_decode($ql->Get1NVYT($MaNV), true);

            if ($EmailNV !== $currentNV['EmailNV'] && $ql->CheckExistingEmail($EmailNV, $MaNV)) {
                $this->view("layoutQLyBS", [
                    "Page" => "qlchitietnvyt",
                    "Error" => "Email đã tồn tại trong hệ thống.",
                    "CTNV" => $currentNV
                ]);
                return;
            }

            $result = $ql->UpdateNVYT($MaNV, $NgaySinh, $GioiTinh, $EmailNV);

            if ($result) {
                $_SESSION['success_message'] = "Cập nhật thông tin Nhân viên y tế thành công.";
                header("Location: ./DSNVYT");
            } else {
                $this->view("layoutQLyBS", [
                    "Page" => "qlchitietnvyt",
                    "Error" => "Không thể cập nhật thông tin nhân viên y tế.",
                    "CTNV" => $currentNV
                ]);
            }
        } else {
            $this->view("layoutQLyBS", [
                "Page" => "qlchitietnvyt",
                "Error" => "Yêu cầu không hợp lệ."
            ]);
        }
    }

    function XoaNVYT() {
        if (isset($_POST["btnXoaNVYT"])) {
            $MaNV = $_POST["MaNV"];

            $ql = $this->model("mQLNVYT");
            $result = json_decode($ql->DeleteNVYT($MaNV), true);

            if ($result['success']) {
                $_SESSION['success_message'] = "Xóa thông tin nhân viên y tế thành công.";
                header("Location: ./DSNVYT");
            } else {
                $this->view("layoutQLyBS", [
                    "Page" => "qlnvyt",
                    "Error" => "Không thể xóa nhân viên."
                ]);
            }
        }
    }

    function ThemNVYT() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $HovaTen = $_POST['HovaTen'];
            $NgaySinh = $_POST['NgaySinh'];
            $GioiTinh = $_POST['GioiTinh'];
            $SoDT = $_POST['SoDT'];
            $EmailNV = $_POST['EmailNV'];
    
            // Kiểm tra dữ liệu đầu vào
            if (!preg_match("/^[a-zA-ZÀ-ỹ\s]+$/u", $HovaTen)) {
                $this->view("layoutQLyBS", [
                    "Page" => "themnvyt",
                    "Error" => "Họ tên không được chứa ký tự đặc biệt và số."
                ]);
                return;
            }
    
            if (!preg_match("/^[0-9]+$/", $SoDT)) {
                $this->view("layoutQLyBS", [
                    "Page" => "themnvyt",
                    "Error" => "Số điện thoại chỉ được chứa số."
                ]);
                return;
            }
    
            $ql = $this->model("mQLNVYT");
            
            // Kiểm tra số điện thoại và email trùng lặp
            if ($ql->CheckExistingPhoneNumber($SoDT)) {
                $this->view("layoutQLyBS", [
                    "Page" => "themnvyt",
                    "Error" => "Số điện thoại đã tồn tại trong hệ thống."
                ]);
                return;
            }
    
            if ($ql->CheckExistingEmail($EmailNV)) {
                $this->view("layoutQLyBS", [
                    "Page" => "themnvyt",
                    "Error" => "Email đã tồn tại trong hệ thống."
                ]);
                return;
            }
            
            $result = $ql->AddNVYT($HovaTen, $NgaySinh, $GioiTinh, $SoDT, $EmailNV);
    
            if ($result === true) {
                $_SESSION['success_message'] = "Thêm thông tin nhân viên y tế mới thành công.";
                header("Location: ./DSNVYT");
            } else {
                $this->view("layoutQLyBS", [
                    "Page" => "themnvyt",
                    "Error" => $result
                ]);
            }
        } else {
            $this->view("layoutQLyBS", [
                "Page" => "themnvyt"
            ]);
        }
    }
    //Phan cua HBao quan ly nhan vien nha thuoc
    function DSNVNT() {
        $ql = $this->model("mQLNVNT");

        $this->view("layoutQLyBS", [
            "Page" => "qlnvnt",
            "NhanVien" => $ql->GetAll()
        ]);
    }

    function CTNVNT() {
        if (isset($_POST["btnCTNVNT"])) {
            $MaNV = $_POST["ctnv"];
            $ql =  $this->model("mQLNVNT");
            $this->view("layoutQLyBS", [
                "Page" => "chitietnvnt",
                "CTNV" => $ql->GetCTNV($MaNV)
            ]);
            
        }
        if(isset($_POST["btnSuaNVNT"]))
        {
            $ql =  $this->model("mQLNVNT");
            $MaNV = $_POST["MaNV"];
            $NgaySinh = $_POST["NgaySinh"];
            $GioiTinh = $_POST["GioiTinh"];
            $EmailNV = $_POST["EmailNV"];
            $rs = $ql->UpdateNVNT($MaNV, $NgaySinh, $GioiTinh, $EmailNV);
            $this->view("layoutQLyBS", [
                "Page" => "chitietnvnt",
                "CTNV" => $ql->GetCTNV($MaNV),
                "rs" => $rs
            ]);

        }
        if(isset($_POST["btnXoaNVNT"]))
        {
            $ql =  $this->model("mQLNVNT");
            $MaNV = $_POST["MaNV"];
            $rs = $ql->DeleteNVNT($MaNV);
            $this->view("layoutQLyBS", [
                "Page" => "qlnvnt",
                "NhanVien" => $ql->GetAll(),
                "rs" => 3
            ]);
        }
    }
    function ThemNVNT(){
        $ql = $this->model("mQLNVNT");
        
        if(isset($_POST["btnThemNVNT"]))
        {   
            $hovaten = $_POST["HovaTen"];
            $sdt = $_POST["SoDT"];
            $NgaySinh = $_POST["NgaySinh"];
            $GioiTinh = $_POST["GioiTinh"];
            $EmailNV = $_POST["EmailNV"];
            $rs = $ql->AddNVNT($hovaten, $NgaySinh, $sdt, $EmailNV,$GioiTinh);
            $this->view("layoutQLyBS", [
                "Page" => "qlnvnt",
                "NhanVien" => $ql->GetAll(),
                "rs" => $rs
            ]);
        }
        else
        {
            $this -> view("layoutQLyBS",[
                "Page" => "themnvnt"
            ]);
         }
}
}

    
?>

