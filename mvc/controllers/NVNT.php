<?php 
class NVNT extends Controller {

    // Xử lý đơn thuốc bác sĩ kê (KE_DON) - GIỮ NGUYÊN
    public function SayHi() {
        $nvnt = $this->model("mNVNT");

        // Trang hiện tại
        if (isset($_POST['page'])) {
            $page = (int)$_POST['page'];
        } else {
            $page = 1;
        }

        // Trạng thái lọc: all / Chua thanh toan / Da thanh toan / Huy
        if (isset($_POST['loc'])) {
            $loc = $_POST['loc'];
        } else {
            $loc = 'all';
        }

        // Từ khóa tìm kiếm: mã BN hoặc số điện thoại
        $keyword = '';
        if (isset($_POST['keyword'])) {
            $keyword = trim($_POST['keyword']);
        }

        // Tổng số đơn sau khi áp dụng lọc + tìm kiếm
        $totalInvoices = $nvnt->GetTotalInvoicesFiltered($loc, $keyword);
        $itemsPerPage  = 5;
        $pagination    = new Pagination($totalInvoices, $itemsPerPage, $page); 

        // Luôn dùng hàm lọc, loc = 'all' thì sẽ ra tất cả KE_DON, có áp dụng search
        $invoices = $nvnt->GetDTTheoLoc(
            $pagination->getOffset(), 
            $pagination->getLimit(), 
            $loc,
            $keyword
        );

        $this->view("layoutNVNT", [
            "Page"       => "donthuoc",
            "DT"         => $invoices,
            "Pagination" => $pagination,
            "loc"        => $loc,
            "keyword"    => $keyword
        ]);
    }

    // Chi tiết đơn thuốc bác sĩ kê (KE_DON) - GIỮ NGUYÊN
    public function CTDT() {
        $nvnt = $this->model("mNVNT");

        // Xác nhận thanh toán
        if (isset($_POST["nutXN"])) {
            $MaDT = $_POST["MaDT"];
            $ok   = $nvnt->xacNhanThanhToan($MaDT);

            $this->view("layoutNVNT", [
                "Page"   => "chitietdonthuoc",
                "CTDT"   => $nvnt->getCTDT($MaDT),
                "Thuoc"  => $nvnt->getThuoc($MaDT),
                "Result" => $ok ? 1 : 0
            ]);
            return;
        }

        // Hủy đơn thuốc
        if (isset($_POST["nutHuy"])) {
            $MaDT = $_POST["MaDT"];
            $ok   = $nvnt->huyDonThuoc($MaDT);

            $this->view("layoutNVNT", [
                "Page"   => "chitietdonthuoc",
                "CTDT"   => $nvnt->getCTDT($MaDT),
                "Thuoc"  => $nvnt->getThuoc($MaDT),
                "Result" => $ok ? 3 : 0
            ]);
            return;
        }

        // Xem chi tiết đơn thuốc (từ danh sách)
        if (isset($_POST["btnCTDT"])) {
            $MaDT = $_POST["ctdt"];

            $this->view("layoutNVNT", [
                "Page"  => "chitietdonthuoc",
                "CTDT"  => $nvnt->getCTDT($MaDT),
                "Thuoc" => $nvnt->getThuoc($MaDT)
            ]);
            return;
        }

        // Nếu không có gì, quay lại danh sách
        header("Location: /KLTN_Benhvien/NVNT");
        exit;
    }

    // ===========================
    // BÁN LẺ THUỐC (BAN_LE)
    // ===========================

    // Danh sách đơn bán lẻ của nhân viên hiện tại + lọc theo khoảng ngày
    public function BanLe() {
        $nvnt = $this->model("mNVNT");

        // Thiết lập timezone cho đúng ngày giờ VN (tránh lệch ngày khi lọc)
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        // Lấy id tài khoản đăng nhập từ session
        $idTaiKhoan = isset($_SESSION['id']) ? (int)$_SESSION['id'] : 0;

        // Lấy MaNV tương ứng trong bảng nhanvien
        $maNhanVien = $nvnt->getMaNhanVienByTaiKhoan($idTaiKhoan);

        // Lọc theo khoảng ngày (YYYY-MM-DD)
        $today = date('Y-m-d');
        $fromDate = isset($_POST['from_date']) && $_POST['from_date'] !== '' ? $_POST['from_date'] : $today;
        $toDate   = isset($_POST['to_date'])   && $_POST['to_date']   !== '' ? $_POST['to_date']   : $today;

        // Đảm bảo fromDate <= toDate, nếu không thì hoán đổi
        if (strtotime($fromDate) > strtotime($toDate)) {
            $tmp = $fromDate;
            $fromDate = $toDate;
            $toDate = $tmp;
        }

        // Nếu không lấy được MaNV thì cho danh sách rỗng (tránh lỗi)
        if ($maNhanVien <= 0) {
            $dsBanLe = json_encode([]);
        } else {
            // Gọi model với MaNV (không phải ID tài khoản)
            $dsBanLe = $nvnt->getBanLeList($maNhanVien, $fromDate, $toDate);
        }

        $this->view("layoutNVNT", [
            "Page"      => "banle_danhsach",
            "DS"        => $dsBanLe,
            "from_date" => $fromDate,
            "to_date"   => $toDate
        ]);
    }

    // Tạo đơn bán lẻ mới
    public function BanLeTao() {
        $nvnt = $this->model("mNVNT");

        // Lấy id tài khoản đăng nhập
        $idTaiKhoan = isset($_SESSION['id']) ? (int)$_SESSION['id'] : 0;

        // Lấy MaNV tương ứng trong bảng nhanvien
        $maNhanVien = $nvnt->getMaNhanVienByTaiKhoan($idTaiKhoan);

        $error = "";
        $success = "";
        $ghiChuChung = "";

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['luuDon'])) {
            $ghiChuChung = isset($_POST['GhiChuChung']) ? trim($_POST['GhiChuChung']) : "";

            $maThuocArr  = isset($_POST['ma_thuoc'])  ? $_POST['ma_thuoc']  : [];
            $soLuongArr  = isset($_POST['so_luong'])  ? $_POST['so_luong']  : [];
            $lieuDungArr = isset($_POST['lieu_dung']) ? $_POST['lieu_dung'] : [];

            $dsThuoc = [];
            $count = count($maThuocArr);

            for ($i = 0; $i < $count; $i++) {
                $maThuoc = isset($maThuocArr[$i]) ? (int)$maThuocArr[$i] : 0;
                $soLuong = isset($soLuongArr[$i]) ? (int)$soLuongArr[$i] : 0;
                $lieuDung = isset($lieuDungArr[$i]) ? trim($lieuDungArr[$i]) : "";

                if ($maThuoc > 0 && $soLuong > 0) {
                    $dsThuoc[] = [
                        "MaThuoc"  => $maThuoc,
                        "SoLuong"  => $soLuong,
                        "LieuDung" => $lieuDung
                    ];
                }
            }

            if ($maNhanVien <= 0) {
                $error = "Không xác định được nhân viên nhà thuốc. Vui lòng kiểm tra lại cấu hình tài khoản - nhân viên.";
            } elseif (empty($dsThuoc)) {
                $error = "Vui lòng chọn ít nhất 1 loại thuốc với số lượng hợp lệ.";
            } else {
                $maDonMoi = $nvnt->taoDonBanLe($maNhanVien, $ghiChuChung, $dsThuoc);

                if ($maDonMoi > 0) {
                    header("Location: /KLTN_Benhvien/NVNT/BanLeChiTiet/" . $maDonMoi);
                    exit;
                } else {
                    $error = "Không thể tạo đơn bán lẻ. Vui lòng thử lại.";
                }
            }
        }

        // Lấy danh sách thuốc cho dropdown
        $dsThuocDropdown = $nvnt->getDanhSachThuocDropdown();

        $this->view("layoutNVNT", [
            "Page"          => "banle_taodon",
            "error"         => $error,
            "success"       => $success,
            "GhiChuChung"   => $ghiChuChung,
            "DanhSachThuoc" => $dsThuocDropdown
        ]);
    }

    // Xem / chỉnh sửa chi tiết đơn bán lẻ
    public function BanLeChiTiet($MaDon = 0) {
        $nvnt = $this->model("mNVNT");

        // Lấy MaDon từ POST nếu có
        if (isset($_POST['MaDon'])) {
            $MaDon = (int)$_POST['MaDon'];
        } else {
            $MaDon = (int)$MaDon;
        }

        if ($MaDon <= 0) {
            header("Location: /KLTN_Benhvien/NVNT/BanLe");
            exit;
        }

        $error = "";
        $success = "";
        $resultFlag = 0;

        // Lưu thay đổi đơn bán lẻ trong ngày
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['luuThayDoi'])) {
            $ghiChuChung = isset($_POST['GhiChuChung']) ? trim($_POST['GhiChuChung']) : "";

            $maThuocArr  = isset($_POST['ma_thuoc'])  ? $_POST['ma_thuoc']  : [];
            $soLuongArr  = isset($_POST['so_luong'])  ? $_POST['so_luong']  : [];
            $lieuDungArr = isset($_POST['lieu_dung']) ? $_POST['lieu_dung'] : [];

            $dsThuoc = [];
            $count = count($maThuocArr);

            for ($i = 0; $i < $count; $i++) {
                $maThuoc = isset($maThuocArr[$i]) ? (int)$maThuocArr[$i] : 0;
                $soLuong = isset($soLuongArr[$i]) ? (int)$soLuongArr[$i] : 0;
                $lieuDung = isset($lieuDungArr[$i]) ? trim($lieuDungArr[$i]) : "";

                if ($maThuoc > 0 && $soLuong > 0) {
                    $dsThuoc[] = [
                        "MaThuoc"  => $maThuoc,
                        "SoLuong"  => $soLuong,
                        "LieuDung" => $lieuDung
                    ];
                }
            }

            if (empty($dsThuoc)) {
                $error = "Vui lòng giữ lại ít nhất 1 loại thuốc với số lượng hợp lệ.";
            } else {
                $ok = $nvnt->capNhatDonBanLe($MaDon, $ghiChuChung, $dsThuoc);
                if ($ok) {
                    $success = "Cập nhật đơn bán lẻ thành công.";
                    $resultFlag = 1;
                } else {
                    $error = "Chỉ được chỉnh sửa đơn bán lẻ trong ngày hôm nay, hoặc đã có lỗi xảy ra.";
                }
            }
        }

        // Lấy thông tin header + thuốc
        $header   = $nvnt->getBanLeHeader($MaDon);
        $thuoc    = $nvnt->getThuoc($MaDon);
        $editable = $nvnt->coTheSuaDonBanLe($MaDon);

        // Lấy danh sách thuốc cho dropdown
        $danhSachThuoc = $nvnt->getDanhSachThuocDropdown();

        $this->view("layoutNVNT", [
            "Page"          => "banle_chitietbanle",
            "Header"        => $header,
            "Thuoc"         => $thuoc,
            "editable"      => $editable,
            "error"         => $error,
            "success"       => $success,
            "Result"        => $resultFlag,
            "DanhSachThuoc" => $danhSachThuoc
        ]);
    }

    // ===========================
    // ĐỔI MẬT KHẨU NHÂN VIÊN NHÀ THUỐC
    // ===========================
    public function DoiMatKhau() {
        $nvnt   = $this->model("mNVNT");
        $error  = "";
        $success = "";

        $idTaiKhoan = isset($_SESSION['id']) ? (int)$_SESSION['id'] : 0;

        if ($idTaiKhoan <= 0) {
            $error = "Phiên làm việc không hợp lệ, vui lòng đăng nhập lại.";
            $this->view("layoutNVNT", [
                "Page"   => "NT_Doimatkhau",
                "error"  => $error,
                "success"=> $success
            ]);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnDoiMK'])) {
            $oldPassword = isset($_POST['old_password']) ? trim($_POST['old_password']) : "";
            $newPassword = isset($_POST['new_password']) ? trim($_POST['new_password']) : "";
            $cfPassword  = isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : "";

            if ($newPassword === "" || $cfPassword === "") {
                $error = "Vui lòng nhập đầy đủ mật khẩu mới và xác nhận.";
            } elseif (strlen($newPassword) <= 8) {
                $error = "Mật khẩu mới phải dài hơn 8 ký tự.";
            } elseif ($newPassword !== $cfPassword) {
                $error = "Mật khẩu xác nhận không khớp.";
            } else {
                $ok = $nvnt->doiMatKhau($idTaiKhoan, $oldPassword, $newPassword);
                if ($ok) {
                    $success = "Đổi mật khẩu thành công.";
                } else {
                    $error = "Mật khẩu hiện tại không đúng hoặc có lỗi xảy ra.";
                }
            }
        }

        $this->view("layoutNVNT", [
            "Page"   => "NT_Doimatkhau",
            "error"  => $error,
            "success"=> $success
        ]);
    }

    // ===========================
    // THÔNG TIN CÁ NHÂN - TRANG XEM
    // ===========================
    public function ThongTinCaNhan() {
        $nvnt  = $this->model("mNVNT");
        $error = "";
        $toast = "";
        $thongTin = null;

        $idTaiKhoan = isset($_SESSION['id']) ? (int)$_SESSION['id'] : 0;
        if ($idTaiKhoan <= 0) {
            $error = "Phiên làm việc không hợp lệ, vui lòng đăng nhập lại.";
        } else {
            $thongTin = $nvnt->getThongTinCaNhan($idTaiKhoan);
            if (!$thongTin) {
                $error = "Không tìm thấy thông tin nhân viên.";
            }
        }

        // Lấy toast sau khi cập nhật xong
        if (isset($_SESSION['toast_nvnt_profile'])) {
            $toast = $_SESSION['toast_nvnt_profile'];
            unset($_SESSION['toast_nvnt_profile']);
        }

        $this->view("layoutNVNT", [
            "Page"     => "NT_thongtincanhan_xem",
            "ThongTin" => $thongTin,
            "error"    => $error,
            "toast"    => $toast
        ]);
    }

    // ===========================
    // THÔNG TIN CÁ NHÂN - TRANG SỬA
    // ===========================
    public function ThongTinCaNhanSua() {
        $nvnt    = $this->model("mNVNT");
        $error   = "";
        $success = "";
        $thongTin = null;

        $idTaiKhoan = isset($_SESSION['id']) ? (int)$_SESSION['id'] : 0;
        if ($idTaiKhoan <= 0) {
            $error = "Phiên làm việc không hợp lệ, vui lòng đăng nhập lại.";
        } else {
            $thongTin = $nvnt->getThongTinCaNhan($idTaiKhoan);
            if (!$thongTin) {
                $error = "Không tìm thấy thông tin nhân viên.";
            }
        }

        if ($idTaiKhoan > 0 && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnLuuThongTin'])) {
            $ngaySinh = isset($_POST['NgaySinh']) ? trim($_POST['NgaySinh']) : "";
            $gioiTinh = isset($_POST['GioiTinh']) ? trim($_POST['GioiTinh']) : "";
            $emailNV  = isset($_POST['EmailNV'])  ? trim($_POST['EmailNV'])  : "";

            if ($emailNV !== "" && !filter_var($emailNV, FILTER_VALIDATE_EMAIL)) {
                $error = "Địa chỉ email không hợp lệ.";
            }

            if ($error === "") {
                $ok = $nvnt->capNhatThongTinCaNhan($idTaiKhoan, $ngaySinh, $gioiTinh, $emailNV);
                if ($ok) {
                    $_SESSION['toast_nvnt_profile'] = "Cập nhật thông tin cá nhân thành công.";
                    header("Location: /KLTN_Benhvien/NVNT/ThongTinCaNhan");
                    exit;
                } else {
                    $error = "Không thể cập nhật thông tin, vui lòng thử lại.";
                }
            }

            // cập nhật lại thông tin hiển thị theo dữ liệu vừa nhập (nếu lỗi)
            if ($thongTin) {
                $thongTin['NgaySinh'] = $ngaySinh;
                $thongTin['GioiTinh'] = $gioiTinh;
                $thongTin['EmailNV']  = $emailNV;
            }
        }

        $this->view("layoutNVNT", [
            "Page"     => "NT_thongtincanhan_sua",
            "ThongTin" => $thongTin,
            "error"    => $error,
            "success"  => $success
        ]);
    }

    // Autosuggest tìm thuốc (JSON)
    public function TimThuoc() {
        header('Content-Type: application/json; charset=utf-8');

        $term = "";
        if (isset($_GET['term'])) {
            $term = $_GET['term'];
        } elseif (isset($_POST['term'])) {
            $term = $_POST['term'];
        }

        $nvnt = $this->model("mNVNT");
        $data = $nvnt->timThuocTheoTen($term);

        echo json_encode($data);
        exit;
    }
}
?>
