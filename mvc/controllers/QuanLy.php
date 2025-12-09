<?php
class QuanLy extends Controller {
    function SayHi()
    {
        $this->view("layoutQL");
    }
    //QuanLy
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

        // ================== XỬ LÝ DUYỆT / TỪ CHỐI NGHỈ PHÉP (MVC) ==================
        if (
            $_SERVER['REQUEST_METHOD'] === 'POST'
            && isset($_POST['action'])
            && in_array($_POST['action'], ['approve', 'reject'])
        ) {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $today = date('Y-m-d');

            $action = $_POST['action'];
            $maYC   = isset($_POST['MaYC'])  ? (int)$_POST['MaYC']  : 0;
            $maLLV  = isset($_POST['MaLLV']) ? (int)$_POST['MaLLV'] : 0;

            // Luôn cập nhật yêu cầu nghỉ phép sang "Da xu ly" cho cả duyệt và từ chối
            if ($maYC > 0) {
                $ql->MarkLeaveRequestProcessed($maYC);
            }

            if ($action === 'approve' && $maLLV > 0) {

                // ===== LẤY ĐẦY ĐỦ THÔNG TIN LỊCH LÀM VIỆC THEO MaLLV =====
                $workInfo = $ql->GetWorkScheduleByMaLLV($maLLV); // MaNV, NgayLamViec, CaLamViec

                if ($workInfo && isset($workInfo['NgayLamViec'], $workInfo['CaLamViec'], $workInfo['MaNV'])) {
                    $ngayLamViec = $workInfo['NgayLamViec'];
                    $caLamViec   = $workInfo['CaLamViec'];
                    $maNV        = (int)$workInfo['MaNV'];
                } else {
                    // Không tìm thấy lịch làm việc -> không thể duyệt
                    $_SESSION['message'] = "Không tìm thấy thông tin lịch làm việc cho yêu cầu nghỉ phép.";
                    $_SESSION['message_type'] = "error";

                    header('Location: ' . $_SERVER['REQUEST_URI']);
                    exit();
                }

                // Chỉ cho phép duyệt nếu ngày xin nghỉ ở tương lai
                if ($ngayLamViec && strtotime($ngayLamViec) > strtotime($today)) {

                    // ===== KIỂM TRA BÁC SĨ CÓ LỊCH KHÁM TRONG CA NÀY KHÔNG =====
                    // Nếu có lịch khám (lichkham) trong khoảng giờ ca tương ứng -> KHÔNG duyệt nghỉ
                    $hasAppointment = $ql->HasAppointmentInShift($maNV, $ngayLamViec, $caLamViec);

                    if ($hasAppointment) {
                        // Có lịch khám trong ca -> không cho nghỉ, giữ nguyên trạng thái lịch làm việc
                        $_SESSION['message'] = "Không thể duyệt nghỉ phép vì bác sĩ đã có lịch khám trong ca làm việc này.";
                        $_SESSION['message_type'] = "error";
                    } else {
                        // Không có lịch khám trong ca -> cho phép đổi lịch làm việc sang 'Nghỉ'
                        $ql->UpdateWorkScheduleStatusByMaLLV($maLLV, 'Nghỉ');

                        $_SESSION['message'] = "Duyệt nghỉ phép thành công.";
                        $_SESSION['message_type'] = "success";
                    }

                } else {
                    // Ngày xin nghỉ là hôm nay hoặc quá khứ -> không đổi trạng thái lịch làm việc
                    $_SESSION['message'] = "Duyệt không thành công, ngày xin nghỉ phải ở tương lai.";
                    $_SESSION['message_type'] = "error";
                }
            } elseif ($action === 'reject') {
                // Từ chối: không cần kiểm tra ngày, giữ lịch làm việc như cũ
                $_SESSION['message'] = "Đã từ chối yêu cầu nghỉ phép.";
                $_SESSION['message_type'] = "success";
            }

            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit();
        }
        // ================== HẾT PHẦN NGHỈ PHÉP ==================

        // ================== THÊM NHIỀU LỊCH LÀM VIỆC THEO TUẦN ==================
        if (isset($_POST['btnGenerateSchedule'])) {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $today = date('Y-m-d');

            $MaNV = isset($_POST['MaNVien_multi']) ? trim($_POST['MaNVien_multi']) : '';
            $khoaSelectMulti = isset($_POST['khoaSelect_multi']) ? trim($_POST['khoaSelect_multi']) : '';
            $startDate = isset($_POST['start_date']) ? $_POST['start_date'] : '';
            $endDate = isset($_POST['end_date']) ? $_POST['end_date'] : '';
            $conflictOption = isset($_POST['conflict_option']) ? $_POST['conflict_option'] : 'skip';
            $weekTemplate = isset($_POST['week']) && is_array($_POST['week']) ? $_POST['week'] : [];

            // E1: Không chọn nhân viên
            if ($MaNV === '') {
                $_SESSION['message'] = "Vui lòng chọn nhân viên để tạo lịch.";
                $_SESSION['message_type'] = "error";
                header('Location: ' . $_SERVER['REQUEST_URI']);
                exit();
            }

            // E2: Không chọn ca nào trong lịch tuần
            $hasShift = false;
            if (!empty($weekTemplate)) {
                foreach ($weekTemplate as $dayKey => $shifts) {
                    if (is_array($shifts) && count($shifts) > 0) {
                        $hasShift = true;
                        break;
                    }
                }
            }
            if (!$hasShift) {
                $_SESSION['message'] = "Bạn chưa chọn ca nào trong lịch tuần.";
                $_SESSION['message_type'] = "error";
                header('Location: ' . $_SERVER['REQUEST_URI']);
                exit();
            }

            // Kiểm tra ngày hợp lệ
            $startValid = DateTime::createFromFormat('Y-m-d', $startDate);
            $endValid = DateTime::createFromFormat('Y-m-d', $endDate);

            if (!$startValid || !$endValid) {
                $_SESSION['message'] = "Khoảng thời gian không hợp lệ.";
                $_SESSION['message_type'] = "error";
                header('Location: ' . $_SERVER['REQUEST_URI']);
                exit();
            }

            // E3: Ngày bắt đầu > ngày kết thúc
            if ($startDate > $endDate) {
                $_SESSION['message'] = "Khoảng thời gian không hợp lệ (Ngày bắt đầu phải nhỏ hơn hoặc bằng ngày kết thúc).";
                $_SESSION['message_type'] = "error";
                header('Location: ' . $_SERVER['REQUEST_URI']);
                exit();
            }

            // B6: Chỉ thêm được lịch có ngày bắt đầu lớn hơn ngày hiện tại
            if ($startDate <= $today) {
                $_SESSION['message'] = "Ngày bắt đầu phải lớn hơn ngày hiện tại.";
                $_SESSION['message_type'] = "error";
                header('Location: ' . $_SERVER['REQUEST_URI']);
                exit();
            }

            // Chuẩn hóa option xử lý trùng
            if (!in_array($conflictOption, ['overwrite', 'skip', 'cancel'])) {
                $conflictOption = 'skip';
            }

            // Gọi model sinh lịch
            $result = $ql->GenerateWeeklySchedule($MaNV, $weekTemplate, $startDate, $endDate, $conflictOption);

            if ($result['success']) {
                $added = isset($result['added']) ? (int)$result['added'] : 0;
                $conflicts = isset($result['conflicts']) ? (int)$result['conflicts'] : 0;
                $skipped = isset($result['skipped']) ? (int)$result['skipped'] : 0;

                $_SESSION['message'] = "Tạo lịch thành công: $added ca được thêm mới. Trùng: $conflicts, bỏ qua: $skipped.";
                $_SESSION['message_type'] = "success";
            } else {
                // E4: Người dùng chọn "Không thêm lịch"
                if (isset($result['option']) && $result['option'] === 'cancel') {
                    $conflicts = isset($result['conflicts']) ? (int)$result['conflicts'] : 0;
                    $_SESSION['message'] = "Phát hiện $conflicts ca trùng. Bạn đã chọn \"Không thêm lịch\" nên không có ca nào được thêm.";
                    $_SESSION['message_type'] = "error";
                } else {
                    // E5: Lỗi database
                    $_SESSION['message'] = "Tạo lịch thất bại. Vui lòng thử lại.";
                    $_SESSION['message_type'] = "error";
                }
            }

            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit();
        }
        // ================== HẾT PHẦN THÊM NHIỀU LỊCH ==================

        if (isset($_POST['btnDKL'])) {
            $MaNV = $_POST['MaNVien'];
            $NgayLamViec = $_POST['NgayLamViec'];
            $CaLamViec = $_POST['cl'];
            $chuyenKhoa = $_POST['khoaSelect'];
    
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
                $employeeCount = $ql->CountEmployeeInShift($NgayLamViec, $CaLamViec, $chuyenKhoa);

                if ($employeeCount < 2) {
                    // Nếu số lượng nhân viên trong ca chưa đủ 2, thực hiện thêm lịch làm việc
                    $result = $ql->AddLLV($MaNV, $NgayLamViec, $CaLamViec);

                    if ($result) {
                        $_SESSION['message'] = "Thêm lịch làm việc thành công!";
                        $_SESSION['message_type'] = "success";
                        header('Location: ./LLV');
                        exit();
                        
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
    
    
        //chỉ xóa ca làm việc khi KHÔNG phải form duyệt / từ chối nghỉ phép
        if (isset($_POST['MaNV']) && !isset($_POST['action'])) {
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

        // Lấy danh sách yêu cầu nghỉ phép + số lượng (cho view)
        $dsNghiPhep = $ql->GetPendingLeaveRequests();
        $soYeuCau   = $ql->CountPendingLeaveRequests();
    
        $this->view("layoutQly2", [
            "Page" => "qlllv",
            "LLV" => $listBacSi,
            "Khoa" => $khoa,
            "SelectedDate" => $date,
            "SelectedKhoa" => $maKhoa,
            "BS" => $ql->GetDSBS(),
            "NghiPhep" => $dsNghiPhep,
            "SoYCNghiPhep" => $soYeuCau
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

    // phần của Quản Lý Bác sĩ/ NVYT
    public function GetDashboardCounts() {
        $qlBS   = $this->model("mQLBS");
        $qlNVYT = $this->model("mQLNVYT");
        $ql     = $this->model("mQuanLy");

        // Đếm bác sĩ và NVYT (dữ liệu cũ)
        $doctorCount = $qlBS->GetDoctorCount();
        $staffCount  = $qlNVYT->GetStaffCount();

        // Đếm nhân viên nhà thuốc & xét nghiệm từ model mQuanLy
        $pharmacyCount = $ql->GetPharmacyStaffCount();   // Nhân viên nhà thuốc
        $labCount      = $ql->GetLabStaffCount();        // Nhân viên xét nghiệm

        return [
            'doctorCount'    => $doctorCount,
            'staffCount'     => $staffCount,
            'pharmacyCount'  => $pharmacyCount,
            'labCount'       => $labCount
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
    //Phan cua quan ly nhan vien nha thuoc
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

    // ===================== CHỨC NĂNG ĐỔI MẬT KHẨU CHO QUẢN LÝ =====================
    function DoiMK() {
        // Kiểm tra đăng nhập và đúng quyền (role = 1 là Quản lý)
        if (!isset($_SESSION["id"]) || $_SESSION["role"] != 1) {
            echo "<script>alert('Bạn không có quyền truy cập vào trang này');</script>";
            header("refresh: 0; url='/KLTN_Benhvien'");
            exit;
        }

        $model = $this->model("mQuanLy");

        // Nếu có POST từ form
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $idQL = $_SESSION["id"];
            $old = $_POST["old_password"] ?? '';
            $new = $_POST["new_password"] ?? '';
            $confirm = $_POST["confirm_password"] ?? '';

            if ($new !== $confirm) {
                echo "<script>alert('Mật khẩu xác nhận không khớp!');</script>";
            } else {
                $kq = $model->KiemTraMatKhauCu($idQL, $old);
                if ($kq) {
                    $doi = $model->DoiMatKhau($idQL, $new);
                    if ($doi) {
                        echo "<script>alert('Đổi mật khẩu thành công!');</script>";
                        echo "<script>window.location.href='/KLTN_Benhvien/QuanLy/DoiMK';</script>";
                        exit;
                    } else {
                        echo "<script>alert('Lỗi hệ thống, không thể đổi mật khẩu!');</script>";
                    }
                } else {
                    echo "<script>alert('Mật khẩu hiện tại không chính xác!');</script>";
                }
            }
        }

        // Hiển thị giao diện đổi mật khẩu
        $this->view("LayoutQLdoimatkhau");
    }


    //Phan cua quan ly nhan vien xet nghiem
    function DSNVXN() {
        // dùng model mQuanLy cho NVXN
        $ql = $this->model("mQuanLy");

        // Lấy danh sách nhân viên xét nghiệm (JSON string giống NV nhà thuốc)
        $nhanvien = $ql->GetAll();

        // Gọi layout quản lý BS/NV, nạp page qlnvxn
        $this->view("layoutQLyBS", [
            "Page" => "qlnvxn",
            "NhanVien" => $nhanvien
        ]);
    }

    // ====== NVXN: CHI TIẾT / CẬP NHẬT / THÔI VIỆC ======
    function CTNVXN() {
        $ql = $this->model("mQuanLy");

        // 1) Xem chi tiết NVXN (từ danh sách DSNVXN)
        if (isset($_POST["btnCTNVXN"])) {
            $MaNV = $_POST["ctnv"];

            if (!empty($MaNV)) {
                $chitietNV = $ql->Get1NVXN($MaNV);
                $this->view("layoutQLyBS", [
                    "Page" => "qlchitietnvxn",
                    "CTNV" => $chitietNV
                ]);
                return;
            } else {
                $this->view("layoutQLyBS", [
                    "Page" => "qlchitietnvxn",
                    "Error" => "Mã nhân viên không hợp lệ."
                ]);
                return;
            }
        }

        // 2) Cập nhật thông tin NVXN
        if (isset($_POST["btnSuaNVXN"])) {
            $MaNV     = $_POST["MaNV"] ?? '';
            $NgaySinh = $_POST["NgaySinh"] ?? '';
            $GioiTinh = $_POST["GioiTinh"] ?? '';
            $EmailNV  = $_POST["EmailNV"] ?? '';

            $rs = $ql->UpdateNVXN($MaNV, $NgaySinh, $GioiTinh, $EmailNV);

            $this->view("layoutQLyBS", [
                "Page" => "qlchitietnvxn",
                "CTNV" => $ql->Get1NVXN($MaNV),
                "rs"   => $rs ? 'true' : 'false'
            ]);
            return;
        }

        // 3) Thôi việc / xóa NVXN
        if (isset($_POST["btnXoaNVXN"])) {
            $MaNV = $_POST["MaNV"] ?? '';

            $rs = $ql->DeleteNVXN($MaNV);

            // Sau khi thôi việc -> quay về danh sách NVXN
            $this->view("layoutQLyBS", [
                "Page"     => "qlnvxn",
                "NhanVien" => $ql->GetAll(),
                "rs"       => 3   // để view qlchitietnvxn/qlnvxn show alert "Thôi việc nhân viên thành công"
            ]);
            return;
        }

        // Nếu không có action hợp lệ
        $this->view("layoutQLyBS", [
            "Page"  => "qlchitietnvxn",
            "Error" => "Yêu cầu không hợp lệ."
        ]);
    }

    // ================== THÊM NHÂN VIÊN XÉT NGHIỆM ==================
    function ThemNVXN() {
        // Dùng chung model mQuanLy để xử lý NVXN
        $ql = $this->model("mQuanLy");

        // Nếu submit form thêm NVXN
        if (isset($_POST["btnThemNVXN"])) {
            $hovaten  = $_POST["HovaTen"] ?? '';
            $sdt      = $_POST["SoDT"] ?? '';
            $NgaySinh = $_POST["NgaySinh"] ?? '';
            $GioiTinh = $_POST["GioiTinh"] ?? '';
            $EmailNV  = $_POST["EmailNV"] ?? '';

            // Ràng buộc cơ bản phía server (song song với pattern HTML)
            if (!preg_match("/^[a-zA-ZÀ-ỹ\s]+$/u", $hovaten)) {
                $this->view("layoutQLyBS", [
                    "Page"   => "ThemNVXN",
                    "Error"  => "Họ tên không được chứa ký tự đặc biệt và số."
                ]);
                return;
            }

            if (!preg_match("/^[0-9]+$/", $sdt)) {
                $this->view("layoutQLyBS", [
                    "Page"   => "ThemNVXN",
                    "Error"  => "Số điện thoại chỉ được chứa số."
                ]);
                return;
            }

            if (!filter_var($EmailNV, FILTER_VALIDATE_EMAIL)) {
                $this->view("layoutQLyBS", [
                    "Page"   => "ThemNVXN",
                    "Error"  => "Email không hợp lệ."
                ]);
                return;
            }

            // Gọi model thêm nhân viên xét nghiệm + tạo tài khoản
            $rs = $ql->AddNVXN($hovaten, $NgaySinh, $sdt, $EmailNV, $GioiTinh);

            if ($rs === true || $rs === 'true') {
                // Lấy lại danh sách NVXN sau khi thêm
                $this->view("layoutQLyBS", [
                    "Page"     => "qlnvxn",
                    "NhanVien" => $ql->GetAll(),
                    "rs"       => 'true' // để view qlnvxn.php show alert "Thêm nhân viên xét nghiệm thành công"
                ]);
            } else {
                // Trường hợp lỗi: có thể $rs là chuỗi message hoặc code
                $errorMsg = "Thêm nhân viên xét nghiệm thất bại.";
                if (is_string($rs)) {
                    $errorMsg = $rs;
                }

                $this->view("layoutQLyBS", [
                    "Page"  => "ThemNVXN",
                    "Error" => $errorMsg
                ]);
            }
        } else {
            // Lần đầu load form thêm NVXN
            $this->view("layoutQLyBS", [
                "Page" => "ThemNVXN"
            ]);
        }
    }

        // ================== XỬ LÝ CHUYỂN LỊCH KHÁM KHI BÁC SĨ XIN NGHỈ ==================
    public function TransferSchedule() {
        // Chỉ chấp nhận POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: ./LLV");
            exit();
        }

        $ql = $this->model("mQuanLy");

        $maYC      = isset($_POST['MaYC']) ? (int)$_POST['MaYC'] : 0;
        $maLLV     = isset($_POST['MaLLV']) ? (int)$_POST['MaLLV'] : 0;
        $maBS_Old  = isset($_POST['MaBS_Old']) ? (int)$_POST['MaBS_Old'] : 0;
        $maBS_New  = isset($_POST['MaBS_New']) ? (int)$_POST['MaBS_New'] : 0;
        $ngayLV    = isset($_POST['NgayLamViec']) ? $_POST['NgayLamViec'] : '';
        $caLV      = isset($_POST['CaLamViec']) ? $_POST['CaLamViec'] : '';

        if ($maYC <= 0 || $maLLV <= 0 || $maBS_Old <= 0 || $maBS_New <= 0 || $ngayLV === '' || $caLV === '') {
            $_SESSION['message'] = "Dữ liệu chuyển lịch không hợp lệ.";
            $_SESSION['message_type'] = "error";
            header("Location: ./LLV");
            exit();
        }

        // Kiểm tra ngày xin nghỉ phải ở tương lai (giống logic duyệt nghỉ)
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $today = date('Y-m-d');
        if (strtotime($ngayLV) <= strtotime($today)) {
            $_SESSION['message'] = "Chỉ được chuyển lịch cho các ca làm việc trong tương lai.";
            $_SESSION['message_type'] = "error";
            header("Location: ./LLV");
            exit();
        }

        // Gọi model thực hiện chuyển lịch
        $result = $ql->TransferShiftAppointments($maLLV, $maBS_Old, $maBS_New, $ngayLV, $caLV);

        if ($result['success']) {
            // Đánh dấu yêu cầu nghỉ phép đã xử lý
            if ($maYC > 0) {
                $ql->MarkLeaveRequestProcessed($maYC);
            }

            $moved = isset($result['moved']) ? (int)$result['moved'] : 0;
            $_SESSION['message'] = "Đã chuyển {$moved} lịch khám (đã thanh toán) sang bác sĩ mới và cập nhật ca làm việc thành Nghỉ.";
            $_SESSION['message_type'] = "success";
        } else {
            $errorMsg = isset($result['error']) ? $result['error'] : "Chuyển lịch thất bại. Vui lòng thử lại.";
            $_SESSION['message'] = $errorMsg;
            $_SESSION['message_type'] = "error";
        }

        header("Location: ./LLV");
        exit();
    }


}
?>
