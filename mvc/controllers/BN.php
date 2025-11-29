<?php
class BN extends Controller{
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
        $udbn = $this->model("mBN");
        if(isset($_POST["btnUDTT"])){
            // Call models
            $udbn = $this->model("mBN");
            $mabn = $_SESSION['idbn'];
            // Call Views
            
            $this->view("layoutBN",[
                "Page"=>"udthongtinbn",
                "UD"=>$udbn->get1BN($mabn)
            ]);
            
        }
        if (isset($_POST["btn-updatebn"])) {
            $mabn = $_SESSION["idbn"];
            $tenbn = $_POST["hovaten"];
            $gioitinh = $_POST["gt"];
            $ngaysinh = $_POST["ngaysinh"];
            $diachi = $_POST["diachi"];
            $email = $_POST["email"];
            $bhyt = $_POST["bhyt"];
        
            $result = $udbn->UpdateBN($mabn, $tenbn, $gioitinh, $ngaysinh, $diachi, $email, $bhyt);
            $resultData = json_decode($result, true); 
        
            if ($resultData['success']) {
                $_SESSION["ten"] = $tenbn;
            }
            $this->view("layoutBN", [
                "Page" => "udthongtinbn",
                "UD" => $udbn->get1BN($mabn),
                "XL" => $resultData
            ]);
        }
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
}
?>
