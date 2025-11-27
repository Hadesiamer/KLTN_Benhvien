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

    // Nút "In lịch khám" -> mở trang in đơn giản, dùng Ctrl+P để lưu PDF
    // LƯU Ý: MaLK nhận từ URL theo dạng /BN/InLichKham/160
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

}
?>
