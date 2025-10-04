<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Kết nối CSDL
$conn = new mysqli("localhost", "root", "", "domdom");
$conn->set_charset("utf8");

$dv = $conn->query("SELECT * FROM loaidichvu");
$dichvulist = $dv->fetch_all(MYSQLI_ASSOC);

// KHỞI TẠO biến chuyenKhoa an toàn (tránh undefined / null)
$chuyenKhoa = $_REQUEST['chuyenKhoa'] ?? '';

// Lấy danh sách bác sĩ chỉ khi đã chọn chuyên khoa
$bacsilist = [];
if ($chuyenKhoa !== '') {
    $sql = "SELECT nhanvien.MaNV, nhanvien.HovaTen 
            FROM nhanvien 
            INNER JOIN bacsi ON nhanvien.MaNV = bacsi.MaNV 
            WHERE ChucVu = 'Bác sĩ' AND MaKhoa = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $chuyenKhoa);
    $stmt->execute();
    $res = $stmt->get_result();
    $bacsilist = $res->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}
// Lấy tất cả bác sĩ để sử dụng trong JavaScript
$sqlAllBS = "SELECT nhanvien.MaNV, nhanvien.HovaTen, bacsi.MaKhoa 
             FROM nhanvien 
             INNER JOIN bacsi ON nhanvien.MaNV = bacsi.MaNV 
             WHERE ChucVu = 'Bác sĩ'";
$resAllBS = $conn->query($sqlAllBS);
$bacsiall = $resAllBS->fetch_all(MYSQLI_ASSOC);


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'getKhungGio') {
    $MaBS = $conn->real_escape_string($_POST['MaBS']);
    $Ngay = $conn->real_escape_string($_POST['Ngay']);
    $res = $conn->query("SELECT KhungGio FROM lichkham WHERE MaBS='$MaBS' AND Ngay='$Ngay' AND TrangThai='trong'");
    if ($res && $res->num_rows > 0) {
        while ($r = $res->fetch_assoc()) {
            echo "<button type='button' class='time-slot'>{$r['KhungGio']}</button>";
        }
    } else {
        echo "<p>Không còn khung giờ trống trong ngày này.</p>";
    }
    exit;
}
?>
<form action="" >
    <h2 style="text-align: center">Đăng ký khám bệnh</h2>
    <p>Đặt lịch khám nhanh chóng, dễ dàng và thuận tiện tại nhà.</p>
    <div class="form-group">
        <label for="name">Chuyen khoa</label>
        <select name="chuyenKhoa" id="chuyenKhoa">
            <option value="">Chon chuyen khoa</option>
            <?php foreach (json_decode($data['CK'], true) as $r): ?>
                <option value="<?=htmlspecialchars($r['MaKhoa']) ?>"><?=htmlspecialchars($r['TenKhoa']) ?> </option>
            <?php endforeach; ?>
            
        </select>
    </div>
    <div class="form-group">
        <label for="name">Dịch vụ</label>
        <select name="MaDV" id="MaDV" disabled>
            <option value="">Chọn dịch vụ</option>
            <?php foreach($dichvulist as $dichvu): ?>
                <option value="<?= htmlspecialchars($dichvu['MaLoai']) ?>">
                    <?= htmlspecialchars($dichvu['LoaiDichVu']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div> 
    <div class="form-group">
        <label for="name">Dat lich</label>
        <select name="datLich" id="datLich" disabled  onchange="toggleBacSi()">
            <option value="theoBacSi">Theo Bac Si</option>
            <option value="theoGioKham">Theo Gio Kham</option>
        </select>
    </div>
    <div class="form-group" id="bacsi-group">
        <label for="name">Bac si</label>
        <select name="MaBS" id="MaBS">
            <option value="">Chọn bác sĩ</option>
            <?php foreach($bacsilist as $bacsi){
                echo "<option value='".htmlspecialchars($bacsi['MaNV'])."'>".htmlspecialchars($bacsi['HovaTen'])."</option>";
            } ?>
        </select>
    </div>
    <div class="form-group">
        <label for="name">Ngay Kham</label>
        <input type="date" id="NgayKham" name="NgayKham" min="<?= date('Y-m-d') ?>" required>
    </div>
    <div class="form-group" id="khunggio-group">
        <label for="name">Khung gio</label>
        <div class="khunggio-box" id="khungGioList">
            <p>Vui lòng chọn bác sĩ và ngày khám để xem khung giờ trống.</p>
        </div>
    </div>


    
</form>
<script>

const bacsiData = <?= json_encode($bacsiall) ?>;

const chuyenKhoaSel = document.getElementById("chuyenKhoa");
const dichvuSel = document.getElementById("MaDV");
const datLichSel = document.getElementById("datLich");
const bacsiSel = document.getElementById("MaBS");

chuyenKhoaSel.addEventListener("change", function(){
    if (this.value !== "") {
        dichvuSel.disabled = false;  // mở khóa dịch vụ
    } else {
        dichvuSel.disabled = true;
        datLichSel.disabled = true;
        bacsiSel.innerHTML = "<option value=''>Chọn bác sĩ</option>";
    }

    // load bác sĩ cho chuyên khoa đã chọn
    const makhoa = this.value;
    bacsiSel.innerHTML = "<option value=''>Chọn bác sĩ</option>";
    bacsiData.filter(b => b.MaKhoa === makhoa).forEach(b => {
        const opt = document.createElement("option");
        opt.value = b.MaNV;
        opt.textContent = b.HovaTen;
        bacsiSel.appendChild(opt);
    });
});

dichvuSel.addEventListener("change", function(){
    if (this.value !== "") {
        datLichSel.disabled = false; // mở khóa đặt lịch
    } else {
        datLichSel.disabled = true;
    }
});

function toggleBacSi() {
    let datLich = datLichSel.value;
    let bacsiGroup = document.getElementById("bacsi-group");

    if (datLich === "theoBacSi") {
        bacsiGroup.style.display = "block";
    } else {
        bacsiGroup.style.display = "none";
    }
}

document.addEventListener("DOMContentLoaded", function() {
    toggleBacSi();
});

const ngayKhamInput = document.getElementById("NgayKham");
const khungGioGroup = document.getElementById("khunggio-group");
const khungGioList = document.getElementById("khungGioList");

// Giả sử bạn đã có bảng lichkham trong DB (MaBS, Ngay, KhungGio, TrangThai)
// Khi chọn ngày, ta gọi AJAX tới chính file này để lấy khung giờ
ngayKhamInput.addEventListener("change", function() {
    const ngay = this.value;
    const bacsi = bacsiSel.value;
    if (!ngay || !bacsi) {
        khungGioGroup.style.display = "block";
        khungGioList.innerHTML = "<p>Vui lòng chọn bác sĩ trước</p>";
        return;
    }

    khungGioGroup.style.display = "block";
    khungGioList.innerHTML = "<p>Đang tải khung giờ...</p>";

    fetch("", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "action=getKhungGio&MaBS=" + encodeURIComponent(bacsi) + "&Ngay=" + encodeURIComponent(ngay)
    })
    .then(res => res.text())
    .then(html => {
        khungGioList.innerHTML = html;
    })
    .catch(err => {
        khungGioList.innerHTML = "<p>Lỗi khi tải khung giờ!</p>";
        console.error(err);
    });
});
</script>



<style>

    .form-group{
        margin-bottom: 15px;
    }
    label{
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }
    input[type="text"], select{
        width: 100%;
        padding: 8px;
        box-sizing: border-box;
        border: 1px solid #ccc;
        border-radius: 4px;
    }
    select{
        height: 35px;
    }
    ul{
        list-style-type: disc;
        border-radius: 4px;
        overflow-y: auto;
        text-align: center;
        width: 100%;
    }
    li{
        display: inline-block; 
        background: #f0f0f0;
        border: 1px solid #ccc;
        width: auto;
        padding: 10px;
        height: 50px;
        line-height: 30px;


    }
    .khunggio-box {
    border: 1px solid #ddd;
    border-radius: 6px;
    padding: 10px;
    background: #f9f9f9;
    min-height: 60px;
}
.time-slot {
    margin: 4px;
    padding: 6px 10px;
    background: white;
    border: 1px solid #007bff;
    border-radius: 4px;
    cursor: pointer;
    color: #007bff;
    transition: all 0.2s;
}
.time-slot:hover {
    background: #007bff;
    color: white;
}

</style>
<?php
$conn->close();
?>




