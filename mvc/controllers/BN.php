<?php
class BN extends Controller{
    //Đây là file mvc/controllers/BN.php
    public $HSModel;

    public function __construct(){
        $this->HSModel = $this->model("mBN");
    }
    
    function SayHi(){
        $mabn = $_SESSION['idbn'];
        //Models
        $bn = $this->model("mBN");
        //Views
        $this->view("layoutBN",[
            "Page"=>"thongtinbn",
            "TT"=>$bn->get1BN($mabn)
        ]);
    }

    public function DKXN(){
        $bn = $this->model("mBN");
        $mabn = $_SESSION['idbn'];

        $this->view("layoutDKXN",[
            "DKXN"=>$bn->get1BN($mabn)
        ]);
        if(isset($_POST["confirm"])){
            $mabn = $_SESSION["idbn"];
            $dayxn = $_POST["ngayxn"];
            $gioxn = $_POST["gioxn"];
            $ngayxn = $dayxn . ' ' . $gioxn;
            $ketqua = $_POST["kqxn"];
            $loaixn = $_POST["loaixn"];

            $result = $bn->getDKXN($ngayxn, $ketqua, $loaixn, $mabn);

            $this->view("layoutDKXN",[
                "DKXN"=>$bn->get1BN($mabn)
            ]);
        }
    }

    function UDTT(){
        // [NEW trước đây] Đồng bộ theo flow:
        // - GET hoặc btnUDTT: hiển thị form chỉnh sửa
        // - POST btn-updatebn: cập nhật, set flash, redirect về /BN/TTBN

        $udbn = $this->model("mBN");

        // Nếu submit form cập nhật
        if (isset($_POST["btn-updatebn"])) {
            $mabn     = $_SESSION["idbn"];
            $tenbn    = $_POST["hovaten"];
            $gioitinh = $_POST["gt"];
            $ngaysinh = $_POST["ngaysinh"];
            $diachi   = $_POST["diachi"];
            $email    = $_POST["email"];
            $bhyt     = $_POST["bhyt"];

            // Gọi model cập nhật
            $result     = $udbn->UpdateBN($mabn, $tenbn, $gioitinh, $ngaysinh, $diachi, $email, $bhyt);
            $resultData = json_decode($result, true);

            // Nếu thành công -> cập nhật tên lên session (header hiển thị)
            if (!empty($resultData['success']) && $resultData['success']) {
                $_SESSION["ten"] = $tenbn;
            }

            // Flash message cho trang hồ sơ BN (thongtinbn.php)
            $_SESSION["bn_flash"] = [
                "success" => !empty($resultData['success']) && $resultData['success'],
                "message" => isset($resultData['message'])
                    ? $resultData['message']
                    : (
                        (!empty($resultData['success']) && $resultData['success'])
                            ? "Cập nhật thông tin thành công."
                            : "Cập nhật thông tin thất bại, vui lòng thử lại."
                    )
            ];

            // Redirect về trang hồ sơ cá nhân để hiển thị toast
            header("Location: /KLTN_Benhvien/BN/TTBN");
            exit;
        }

        // Trường hợp GET /BN/UDTT hoặc bấm nút "Thay đổi thông tin" từ trang hồ sơ:
        // luôn load form chỉnh sửa với thông tin hiện tại
        $mabn = $_SESSION['idbn'];

        $this->view("layoutBN",[
            "Page"=>"udthongtinbn",
            "UD"  =>$udbn->get1BN($mabn)
        ]);
    }
  

    public function changePass() {
        $bnModel = $this->model("mBN");
        $mabn = $_SESSION['idbn'];
    
        if (isset($_POST["btnChangePass"])) {
            $oldPass = $_POST["oldPass"];
            $newPass = $_POST["newPass"];
            $confirmPass = $_POST["confirmPass"];
    
            // Kiểm tra xác nhận mật khẩu
            if ($newPass !== $confirmPass) {
                $this->view("layoutBN", [
                    "Page" => "changePass",
                    "CP" => [
                        "success" => false,
                        "message" => "Mật khẩu mới và xác nhận mật khẩu không khớp."
                    ]
                ]);
                return;
            }
    
            // Gọi model để đổi mật khẩu
            $result = $bnModel->changePass($mabn, $oldPass, $newPass);
    
            $this->view("layoutBN", [
                "Page" => "changePass",
                "CP" => $result
            ]);
            return;
        }
    
        // Load mặc định khi chưa bấm nút đổi mật khẩu
        $this->view("layoutBN", [
            "Page" => "changePass"
        ]);
    }

    public function getALLDV(){
        $dangky = $this->model("mDangKyLK");
        $dv= $dangky->GetAllDV(); 
        $this->view("layoutBN",[
            "Page"=>"formDK_KhamBenh",
            "DV"=>$dv
        ]);
    }

    // ================== LỊCH KHÁM ĐÃ ĐẶT (ĐÃ THANH TOÁN) ==================

    // Trang xem danh sách + chi tiết lịch khám đã ĐÃ THANH TOÁN
    public function LichKham() {
        $mabn = $_SESSION['idbn'];
        $bn   = $this->model("mBN");

        // Lấy danh sách lịch khám đã thanh toán (JSON để đẩy ra view)
        $lichKhamJson  = $bn->getLichKhamDaThanhToan($mabn);
        $lichKhamArray = json_decode($lichKhamJson, true) ?: [];

        // Lấy MaLK từ POST khi người dùng click list bên trái
        $MaLK = isset($_POST["MaLK"]) ? $_POST["MaLK"] : "";

        // [NEW] Nếu không chọn từ form, ưu tiên MaLK từ query (redirect sau khi thanh toán)
        if ($MaLK === "" && isset($_GET['MaLK']) && $_GET['MaLK'] !== "") {
            $MaLK = $_GET['MaLK'];
        }

        // Nếu chưa chọn MaLK, tự động chọn lịch mới nhất (MaLK lớn nhất)
        if ($MaLK === "" && !empty($lichKhamArray)) {
            $latestMaLK = $lichKhamArray[0]['MaLK'];
            foreach ($lichKhamArray as $lk) {
                if ($lk['MaLK'] > $latestMaLK) {
                    $latestMaLK = $lk['MaLK'];
                }
            }
            $MaLK = $latestMaLK;
        }

        // Lấy chi tiết lịch khám tương ứng MaLK (JSON cho view)
        $chiTietJson = ($MaLK != "") 
            ? $bn->getChiTietLichKhamDaThanhToan($MaLK) 
            : json_encode([]);

        // Load layout bệnh nhân với page lichkham.php
        $this->view("layoutBN", [
            "Page" => "lichkham",
            "LK"   => $lichKhamJson,
            "CTLK" => $chiTietJson
        ]);
    }

    // ================== [NEW] LỊCH SỬ THANH TOÁN (GIAO DỊCH SEPAY) ==================
    public function LichSuThanhToan() {
        // Bảo vệ: bắt buộc đăng nhập bệnh nhân
        if (!isset($_SESSION['idbn'])) {
            header("Location: /KLTN_Benhvien");
            exit;
        }

        $mabn = (int)$_SESSION['idbn'];
        $bn   = $this->model("mBN");

        // Lấy danh sách lịch sử thanh toán (JOIN lichkham + sepay_transactions)
        $lichSuJson  = $bn->getLichSuThanhToanBN($mabn);
        $lichSuArray = json_decode($lichSuJson, true);
        if (!is_array($lichSuArray)) {
            $lichSuArray = [];
        }

        // Xác định MaLK đang được chọn từ POST (click list) hoặc GET
        $MaLK = 0;
        if (isset($_POST['MaLK']) && $_POST['MaLK'] !== "") {
            $MaLK = (int)$_POST['MaLK'];
        } elseif (isset($_GET['MaLK']) && $_GET['MaLK'] !== "") {
            $MaLK = (int)$_GET['MaLK'];
        }

        // Nếu chưa có MaLK nào được chọn, lấy MaLK mới nhất trong danh sách
        if ($MaLK <= 0 && !empty($lichSuArray)) {
            $MaLK = (int)$lichSuArray[0]['MaLK'];
        }

        // Lấy chi tiết 1 giao dịch + lịch khám tương ứng
        if ($MaLK > 0) {
            $chiTietJson = $bn->getChiTietThanhToanBN($mabn, $MaLK);
        } else {
            $chiTietJson = json_encode([]);
        }

        // Render layout bệnh nhân với view bn_lichsuthanhtoan.php
        $this->view("layoutBN", [
            "Page"        => "bn_lichsuthanhtoan",
            "LSTT_List"   => $lichSuJson,
            "LSTT_Detail" => $chiTietJson,
            "CurrentMaLK" => $MaLK
        ]);
    }

    // Nút "In lịch khám"
    // /BN/InLichKham/160
    public function InLichKham($MaLK = null) {
        // Phải là bệnh nhân đã đăng nhập
        if (!isset($_SESSION['idbn'])) {
            header("Location: /KLTN_Benhvien");
            exit;
        }

        // Lấy MaLK từ tham số URL (router App.php truyền vào)
        $MaLK = (int)$MaLK;

        if ($MaLK <= 0) {
            echo "Mã lịch khám không hợp lệ.";
            exit;
        }

        $bn = $this->model("mBN");
        $chiTietJson = $bn->getChiTietLichKhamDaThanhToan($MaLK);
        $chiTietArr  = json_decode($chiTietJson, true);

        if (!$chiTietArr || empty($chiTietArr)) {
            echo "Không tìm thấy lịch khám hoặc lịch khám không ở trạng thái đã thanh toán.";
            exit;
        }

        $ct = $chiTietArr[0];

        // Chuẩn hóa dữ liệu, phần nào null thì để chuỗi rỗng
        $maLK      = htmlspecialchars($ct['MaLK'] ?? '');
        $ngayKham  = !empty($ct['NgayKham']) ? date('d-m-Y', strtotime($ct['NgayKham'])) : '';
        $gioKham   = htmlspecialchars($ct['GioKham'] ?? '');
        $tenKhoa   = htmlspecialchars($ct['TenKhoa'] ?? '');
        $moTaKhoa  = htmlspecialchars($ct['MoTa'] ?? '');
        $bacSi     = htmlspecialchars($ct['HovaTenNV'] ?? '');
        $trangThai = "Đã thanh toán";

        $tenBN     = htmlspecialchars($ct['HovaTen'] ?? '');
        $maBN      = htmlspecialchars($ct['MaBN'] ?? '');
        $sdt       = htmlspecialchars($ct['SoDT'] ?? '');
        $ngaySinh  = !empty($ct['NgaySinh']) ? htmlspecialchars($ct['NgaySinh']) : '';
        $gioiTinh  = htmlspecialchars($ct['GioiTinh'] ?? '');
        $diaChi    = htmlspecialchars($ct['DiaChi'] ?? '');
        $bhyt      = htmlspecialchars($ct['BHYT'] ?? '');
        $trieuChung = htmlspecialchars($ct['TrieuChung'] ?? '');

        // HTML đơn giản để in -> trình duyệt mở hộp thoại In, chọn "Lưu dưới dạng PDF"
        $html = <<<HTML
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Phiếu lịch khám #{$maLK}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 13px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
        }
        .section-title {
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 5px;
            text-transform: uppercase;
            font-size: 13px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .info-table td {
            padding: 4px 6px;
            vertical-align: top;
        }
        .footer-note {
            margin-top: 20px;
            font-style: italic;
            font-size: 12px;
        }
        hr {
            margin: 10px 0;
        }
    </style>
    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</head>
<body>
    <div class="header">
        <h2>PHIẾU LỊCH KHÁM</h2>
        <p>Mã lịch khám: <strong>{$maLK}</strong></p>
    </div>

    <div>
        <div class="section-title">Thông tin khám bệnh</div>
        <table class="info-table">
            <tr>
                <td><strong>Ngày - giờ khám:</strong></td>
                <td>{$ngayKham} {$gioKham}</td>
            </tr>
            <tr>
                <td><strong>Chuyên khoa:</strong></td>
                <td>{$tenKhoa}</td>
            </tr>
            <tr>
                <td><strong>Vị trí khám:</strong></td>
                <td>{$moTaKhoa}</td>
            </tr>
            <tr>
                <td><strong>Bác sĩ phụ trách:</strong></td>
                <td>BS. {$bacSi}</td>
            </tr>
            <tr>
                <td><strong>Trạng thái:</strong></td>
                <td>{$trangThai}</td>
            </tr>
        </table>

        <div class="section-title">Thông tin bệnh nhân</div>
        <table class="info-table">
            <tr>
                <td><strong>Tên bệnh nhân:</strong></td>
                <td>{$tenBN}</td>
            </tr>
            <tr>
                <td><strong>Mã bệnh nhân:</strong></td>
                <td>{$maBN}</td>
            </tr>
            <tr>
                <td><strong>Số điện thoại:</strong></td>
                <td>{$sdt}</td>
            </tr>
            <tr>
                <td><strong>Năm sinh:</strong></td>
                <td>{$ngaySinh}</td>
            </tr>
            <tr>
                <td><strong>Giới tính:</strong></td>
                <td>{$gioiTinh}</td>
            </tr>
            <tr>
                <td><strong>Địa chỉ:</strong></td>
                <td>{$diaChi}</td>
            </tr>
            <tr>
                <td><strong>BHYT:</strong></td>
                <td>{$bhyt}</td>
            </tr>
            <tr>
                <td><strong>Triệu chứng:</strong></td>
                <td>{$trieuChung}</td>
            </tr>
        </table>

        <div class="footer-note">
            Vui lòng đến đúng thời gian và vị trí khám bệnh, chúng tôi sẽ không hoàn tiền nếu bạn vắng mặt.
        </div>
    </div>
</body>
</html>
HTML;

        echo $html;
        exit;
    }

    // ================== HỒ SƠ PHIẾU KHÁM ==================

    // Trang hồ sơ phiếu khám cho bệnh nhân
    public function Hosophieukham() {
        if (!isset($_SESSION['idbn'])) {
            header("Location: /KLTN_Benhvien");
            exit;
        }

        $mabn = $_SESSION['idbn'];
        $bn   = $this->model("mBN");

        // Thông tin bệnh nhân
        $thongTinBNJson = $bn->get1BN($mabn);

        // Danh sách phiếu khám của bệnh nhân
        $phieuKhamJson  = $bn->getPhieuKhamBenhNhan($mabn);

        $this->view("layoutBN", [
            "Page"        => "hosophieukham",
            "ThongTinBN"  => $thongTinBNJson,
            "PhieuKhamBN" => $phieuKhamJson
        ]);
    }

    // In phiếu khám (giống cách in lịch khám, nhưng theo MaPK)
    // /BN/InPhieuKham/123
    public function InPhieuKham($MaPK = null) {
        if (!isset($_SESSION['idbn'])) {
            header("Location: /KLTN_Benhvien");
            exit;
        }

        $MaPK = (int)$MaPK;
        if ($MaPK <= 0) {
            echo "Mã phiếu khám không hợp lệ.";
            exit;
        }

        $mabn = (int)$_SESSION['idbn'];
        $bn   = $this->model("mBN");

        // Lấy chi tiết phiếu khám
        $chiTietJson = $bn->getChiTietPhieuKham($MaPK, $mabn);
        $chiTietArr  = json_decode($chiTietJson, true);

        if (!$chiTietArr || empty($chiTietArr)) {
            echo "Không tìm thấy phiếu khám.";
            exit;
        }

        // Lấy 1 dòng đầu cho thông tin chung (nếu có nhiều thuốc sẽ lặp ở dưới)
        $ct = $chiTietArr[0];

        // Lấy thông tin bệnh nhân
        $ttJson = $bn->get1BN($mabn);
        $ttArr  = json_decode($ttJson, true);
        $tt     = $ttArr[0] ?? [];

        // Chuẩn hóa dữ liệu
        $maPK       = htmlspecialchars($ct['MaPK'] ?? '');
        $ngayTao    = !empty($ct['NgayTao'])   ? date('d-m-Y H:i', strtotime($ct['NgayTao'])) : '';
        $ngayKham   = !empty($ct['NgayKham'])  ? date('d-m-Y',       strtotime($ct['NgayKham'])) : '';
        $gioKham    = htmlspecialchars($ct['GioKham']    ?? '');
        $tenKhoa    = htmlspecialchars($ct['TenKhoa']    ?? '');
        $viTriKhoa  = htmlspecialchars($ct['ViTriKhoa']  ?? '');
        $tenBacSi   = htmlspecialchars($ct['TenBacSi']   ?? '');

        $trieuChung = htmlspecialchars($ct['TrieuChung'] ?? '');
        $ketQua     = htmlspecialchars($ct['KetQua']     ?? '');
        $chuanDoan  = htmlspecialchars($ct['ChuanDoan']  ?? '');
        $loiDan     = htmlspecialchars($ct['LoiDan']     ?? '');
        $ngayTaiKham= !empty($ct['NgayTaikham']) ? date('d-m-Y', strtotime($ct['NgayTaikham'])) : '';

        $loaiXN     = htmlspecialchars($ct['LoaiXN']     ?? '');
        $ketQuaXN   = htmlspecialchars($ct['KetQuaXN']   ?? '');

        // Thông tin BN
        $tenBN      = htmlspecialchars($tt['HovaTen'] ?? '');
        $maBN       = htmlspecialchars($tt['MaBN']   ?? '');
        $sdt        = htmlspecialchars($tt['SoDT']   ?? '');
        $ngaySinh   = !empty($tt['NgaySinh']) ? date('d-m-Y', strtotime($tt['NgaySinh'])) : '';
        $gioiTinh   = htmlspecialchars($tt['GioiTinh'] ?? '');
        $diaChi     = htmlspecialchars($tt['DiaChi']   ?? '');
        $bhyt       = htmlspecialchars($tt['BHYT']     ?? '');

        // Chuẩn bị HTML, liệt kê thuốc (có thể nhiều dòng)
        $thuocRows = "";
        foreach ($chiTietArr as $dong) {
            $tenThuoc = htmlspecialchars($dong['TenThuoc'] ?? '');
            $soLuong  = htmlspecialchars($dong['SoLuong']  ?? '');
            $lieuDung = htmlspecialchars($dong['LieuDung'] ?? '');
            $cachDung = htmlspecialchars($dong['CachDung'] ?? '');

            if ($tenThuoc === '' && $soLuong === '' && $lieuDung === '' && $cachDung === '') {
                continue;
            }

            $thuocRows .= "
                <tr>
                    <td>{$tenThuoc}</td>
                    <td style=\"text-align:center;\">{$soLuong}</td>
                    <td>{$lieuDung}</td>
                    <td>{$cachDung}</td>
                </tr>
            ";
        }

        if ($thuocRows === "") {
            $thuocRows = "
                <tr>
                    <td colspan=\"4\">Không có thông tin đơn thuốc.</td>
                </tr>
            ";
        }

        $html = <<<HTML
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Phiếu khám #{$maPK}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 13px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
        }
        .section-title {
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 5px;
            text-transform: uppercase;
            font-size: 13px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .info-table td {
            padding: 4px 6px;
            vertical-align: top;
        }
        .drug-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        .drug-table th,
        .drug-table td {
            border: 1px solid #ccc;
            padding: 5px 6px;
            font-size: 12px;
        }
        .drug-table th {
            background: #f0f0f0;
        }
        .footer-note {
            margin-top: 20px;
            font-style: italic;
            font-size: 12px;
        }
        hr {
            margin: 10px 0;
        }
    </style>
    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</head>
<body>
    <div class="header">
        <h2>PHIẾU KHÁM BỆNH</h2>
        <p>Mã phiếu khám: <strong>{$maPK}</strong></p>
        <p>Ngày lập phiếu: {$ngayTao}</p>
    </div>

    <div>
        <div class="section-title">Thông tin bệnh nhân</div>
        <table class="info-table">
            <tr>
                <td><strong>Tên bệnh nhân:</strong></td>
                <td>{$tenBN}</td>
            </tr>
            <tr>
                <td><strong>Mã bệnh nhân:</strong></td>
                <td>{$maBN}</td>
            </tr>
            <tr>
                <td><strong>Ngày sinh:</strong></td>
                <td>{$ngaySinh}</td>
            </tr>
            <tr>
                <td><strong>Giới tính:</strong></td>
                <td>{$gioiTinh}</td>
            </tr>
            <tr>
                <td><strong>Số điện thoại:</strong></td>
                <td>{$sdt}</td>
            </tr>
            <tr>
                <td><strong>Địa chỉ:</strong></td>
                <td>{$diaChi}</td>
            </tr>
            <tr>
                <td><strong>BHYT:</strong></td>
                <td>{$bhyt}</td>
            </tr>
        </table>

        <div class="section-title">Thông tin khám bệnh</div>
        <table class="info-table">
            <tr>
                <td><strong>Ngày - giờ khám:</strong></td>
                <td>{$ngayKham} {$gioKham}</td>
            </tr>
            <tr>
                <td><strong>Chuyên khoa:</strong></td>
                <td>{$tenKhoa}</td>
            </tr>
            <tr>
                <td><strong>Vị trí khám:</strong></td>
                <td>{$viTriKhoa}</td>
            </tr>
            <tr>
                <td><strong>Bác sĩ phụ trách:</strong></td>
                <td>BS. {$tenBacSi}</td>
            </tr>
        </table>

        <div class="section-title">Kết quả khám</div>
        <table class="info-table">
            <tr>
                <td><strong>Triệu chứng:</strong></td>
                <td>{$trieuChung}</td>
            </tr>
            <tr>
                <td><strong>Kết quả khám:</strong></td>
                <td>{$ketQua}</td>
            </tr>
            <tr>
                <td><strong>Chuẩn đoán:</strong></td>
                <td>{$chuanDoan}</td>
            </tr>
            <tr>
                <td><strong>Lời dặn:</strong></td>
                <td>{$loiDan}</td>
            </tr>
            <tr>
                <td><strong>Ngày tái khám:</strong></td>
                <td>{$ngayTaiKham}</td>
            </tr>
        </table>

        <div class="section-title">Xét nghiệm</div>
        <table class="info-table">
            <tr>
                <td><strong>Loại xét nghiệm:</strong></td>
                <td>{$loaiXN}</td>
            </tr>
            <tr>
                <td><strong>Kết quả xét nghiệm:</strong></td>
                <td>{$ketQuaXN}</td>
            </tr>
        </table>

        <div class="section-title">Đơn thuốc</div>
        <table class="drug-table">
            <thead>
                <tr>
                    <th style="width: 30%;">Tên thuốc</th>
                    <th style="width: 10%;">SL</th>
                    <th style="width: 25%;">Liều dùng</th>
                    <th style="width: 35%;">Cách dùng / Ghi chú</th>
                </tr>
            </thead>
            <tbody>
                {$thuocRows}
            </tbody>
        </table>

        <div class="footer-note">
            Vui lòng mang theo phiếu này khi đến khám hoặc tái khám.
        </div>
    </div>
</body>
</html>
HTML;

        echo $html;
        exit;
    }

    // ================== CHAT: DANH SÁCH BÁC SĨ CÓ THỂ CHAT ==================
    public function DanhSachBacSiChat()
    {
        if (!isset($_SESSION['idbn'])) {
            header("Location: /KLTN_Benhvien");
            exit;
        }

        $maBN     = $_SESSION['idbn'];
        $chat     = $this->model("mChat");
        $dsBacSi  = $chat->getDanhSachBacSiChoBN($maBN);

        $this->view("layoutBN", [
            "Page"          => "bn_danhsach_chat",
            "DanhSachBacSi" => $dsBacSi
        ]);
    }

    // ================== CHAT: MÀN HÌNH CHAT VỚI 1 BÁC SĨ ==================
    public function ChatVoiBacSi($maBS = null)
    {
        if (!isset($_SESSION['idbn'])) {
            header("Location: /KLTN_Benhvien");
            exit;
        }

        $maBN = (int)$_SESSION['idbn'];
        $maBS = (int)$maBS;

        if ($maBS <= 0) {
            echo "Mã bác sĩ không hợp lệ.";
            exit;
        }

        $chatModel = $this->model("mChat");

        // Bảo vệ: chỉ cho chat nếu BN có lịch khám online đã thanh toán với BS này
        if (!$chatModel->patientCanChatWithDoctor($maBN, $maBS)) {
            echo "Bạn không có quyền chat với bác sĩ này (chỉ áp dụng cho lịch khám online đã thanh toán).";
            exit;
        }

        // Lấy hoặc tạo cuộc trò chuyện
        $maCuocTrove = $chatModel->getOrCreateConversation($maBN, $maBS);
        if (!$maCuocTrove) {
            echo "Không thể khởi tạo cuộc trò chuyện. Vui lòng thử lại sau.";
            exit;
        }

        // Lấy danh sách tin nhắn ban đầu (từ đầu, lastId = 0)
        $messages = $chatModel->getMessages($maCuocTrove, 0);

        // Đánh dấu tin đã xem phía BN
        $chatModel->markAsReadForPatient($maCuocTrove, $maBN);

        // Lấy thông tin bác sĩ để hiển thị (dùng model MBacsi đã có)
        $mbs       = $this->model("MBacsi");
        $bsJson    = $mbs->get1BS($maBS);
        $bsArr     = json_decode($bsJson, true);
        $bacSiInfo = isset($bsArr[0]) ? $bsArr[0] : null;

        $this->view("layoutBN", [
            "Page"        => "bn_chat_bacsi",
            "MaCuocTrove" => $maCuocTrove,
            "MaBS"        => $maBS,
            "Messages"    => $messages,
            "BacSi"       => $bacSiInfo
        ]);
    }

    // ================== AJAX: BN GỬI TIN NHẮN ==================
    public function AjaxGuiTinNhanBN()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo "Method Not Allowed";
            return;
        }

        if (!isset($_SESSION['idbn'])) {
            http_response_code(401);
            echo "Not authenticated";
            return;
        }

        // TODO: nếu bạn có hệ thống CSRF token chung, hãy kiểm tra tại đây

        $maBN        = (int)$_SESSION['idbn'];
        $maCuocTrove = isset($_POST['MaCuocTrove']) ? (int)$_POST['MaCuocTrove'] : 0;
        $noiDung     = isset($_POST['NoiDung']) ? trim($_POST['NoiDung']) : '';

        // Kiểm tra file đính kèm (nếu có)
        $hasFile = isset($_FILES['FileDinhKem']) && $_FILES['FileDinhKem']['error'] !== UPLOAD_ERR_NO_FILE;
        if ($maCuocTrove <= 0) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                "success" => false,
                "message" => "Thiếu MaCuocTrove."
            ]);
            return;
        }

        if ($noiDung === '' && !$hasFile) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                "success" => false,
                "message" => "Vui lòng nhập nội dung hoặc chọn file đính kèm."
            ]);
            return;
        }

        $chatModel = $this->model("mChat");

        // Bảo vệ: kiểm tra BN có sở hữu cuộc trò chuyện này không
        if (!$chatModel->patientOwnsConversation($maBN, $maCuocTrove)) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                "success" => false,
                "message" => "Bạn không có quyền gửi tin trong cuộc trò chuyện này."
            ]);
            return;
        }

        // Chuẩn bị meta file (nếu có)
        $fileMeta = null;
        if ($hasFile) {
            $fileInfo = $_FILES['FileDinhKem'];

            if ($fileInfo['error'] !== UPLOAD_ERR_OK) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode([
                    "success" => false,
                    "message" => "Lỗi upload file (mã lỗi: " . $fileInfo['error'] . ")."
                ]);
                return;
            }

            // Giới hạn kích thước ~5MB
            $maxSize = 5 * 1024 * 1024;
            if ($fileInfo['size'] > $maxSize) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode([
                    "success" => false,
                    "message" => "File quá lớn, vui lòng chọn file <= 5MB."
                ]);
                return;
            }

            $origName = basename($fileInfo['name']);
            $ext      = strtolower(pathinfo($origName, PATHINFO_EXTENSION));

            // Cho phép một số định dạng phổ biến
            $allowedExt = [
                'jpg','jpeg','png','gif','webp',
                'pdf','doc','docx','xls','xlsx','txt'
            ];
            if (!in_array($ext, $allowedExt)) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode([
                    "success" => false,
                    "message" => "Định dạng file không được hỗ trợ."
                ]);
                return;
            }

            // Xác định đường dẫn lưu vật lý
            $rootPath   = dirname(__DIR__, 2); // tới KLTN_Benhvien
            $uploadDir  = $rootPath . '/public/uploads/chat/';
            if (!is_dir($uploadDir)) {
                @mkdir($uploadDir, 0775, true);
            }

            // Tạo tên file mới tránh trùng
            $randomPart = bin2hex(random_bytes(4));
            $newName    = date('Ymd_His') . '_' . $randomPart . '.' . $ext;
            $destPath   = $uploadDir . $newName;

            if (!move_uploaded_file($fileInfo['tmp_name'], $destPath)) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode([
                    "success" => false,
                    "message" => "Không thể lưu file lên server."
                ]);
                return;
            }

            // MIME type (tạm thời dùng từ client hoặc mime_content_type nếu muốn chắc hơn)
            $mimeType = !empty($fileInfo['type']) ? $fileInfo['type'] : null;
            $isImage  = in_array($ext, ['jpg','jpeg','png','gif','webp']) ? 1 : 0;

            // Đường dẫn public dùng cho <img src> / <a href>
            $publicPath = '/KLTN_Benhvien/public/uploads/chat/' . $newName;

            $fileMeta = [
                'original_name' => $origName,
                'public_path'   => $publicPath,
                'mime_type'     => $mimeType,
                'size'          => (int)$fileInfo['size'],
                'is_image'      => $isImage
            ];
        }

        // Lưu tin nhắn + meta file (nếu có)
        $ok = $chatModel->addMessage($maCuocTrove, 'BN', $maBN, $noiDung, $fileMeta);

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            "success" => $ok,
            "message" => $ok ? "Gửi tin nhắn thành công." : "Gửi tin nhắn thất bại, vui lòng thử lại."
        ]);
    }

    // ================== AJAX: BN LẤY TIN NHẮN MỚI (POLLING) ==================
    public function AjaxFetchTinNhanBN()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo "Method Not Allowed";
            return;
        }

        if (!isset($_SESSION['idbn'])) {
            http_response_code(401);
            echo "Not authenticated";
            return;
        }

        $maBN        = (int)$_SESSION['idbn'];
        $maCuocTrove = isset($_POST['MaCuocTrove']) ? (int)$_POST['MaCuocTrove'] : 0;
        $lastId      = isset($_POST['LastId']) ? (int)$_POST['LastId'] : 0;

        if ($maCuocTrove <= 0) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                "success" => false,
                "message" => "Thiếu MaCuocTrove."
            ]);
            return;
        }

        $chatModel = $this->model("mChat");

        // Bảo vệ: kiểm tra quyền sở hữu
        if (!$chatModel->patientOwnsConversation($maBN, $maCuocTrove)) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                "success" => false,
                "message" => "Bạn không có quyền xem cuộc trò chuyện này."
            ]);
            return;
        }

        $messages = $chatModel->getMessages($maCuocTrove, $lastId);

        // Đánh dấu đã xem phía BN
        $chatModel->markAsReadForPatient($maCuocTrove, $maBN);

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            "success"  => true,
            "messages" => $messages
        ]);
    }

}
?>
