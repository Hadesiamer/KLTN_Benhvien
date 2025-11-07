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

// Xử lý AJAX lấy khung giờ làm việc
if (isset($_POST['action']) && $_POST['action'] === 'getNgayLamViec') {
    $MaBS = $_POST['MaBS'] ?? '';
    if ($MaBS === '') {
        exit(json_encode([]));
    }

    $sqlNgay = "SELECT NgayLamViec FROM lichlamviec 
                WHERE MaNV = ? AND TrangThai = 'Đang làm'";
    $stmt = $conn->prepare($sqlNgay);
    $stmt->bind_param("s", $MaBS);
    $stmt->execute();
    $result = $stmt->get_result();

    $ngayList = [];
    while ($row = $result->fetch_assoc()) {
        $ngayList[] = $row['NgayLamViec'];
    }

    header('Content-Type: application/json');
    echo json_encode($ngayList);
    exit;
}





?>
<div class="booking-form-wrapper">
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
        <input type="date" id="NgayKham" name="NgayKham" required disabled>
    </div>
   


    
</form>
</div>
<script>
const bacsiData = <?= json_encode($bacsiall) ?>;

const chuyenKhoaSel = document.getElementById("chuyenKhoa");
const dichvuSel = document.getElementById("MaDV");
const datLichSel = document.getElementById("datLich");
const bacsiSel = document.getElementById("MaBS");
const ngayKhamInput = document.getElementById("NgayKham");

let ngayLamViec = []; // lưu danh sách ngày bác sĩ có làm

// --- Khi chọn chuyên khoa ---
chuyenKhoaSel.addEventListener("change", function() {
    if (this.value !== "") {
        dichvuSel.disabled = false;  // mở khóa dịch vụ
    } else {
        dichvuSel.disabled = true;
        datLichSel.disabled = true;
        bacsiSel.innerHTML = "<option value=''>Chọn bác sĩ</option>";
        return;
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

// --- Khi chọn bác sĩ ---
bacsiSel.addEventListener("change", function() {
    const MaBS = this.value;
    if (!MaBS) {
        ngayKhamInput.disabled = true;
        return;
    }

    // mở khóa input ngày
    ngayKhamInput.disabled = false;
    ngayKhamInput.value = "";

    // gọi AJAX để lấy danh sách ngày bác sĩ có làm việc
    fetch("", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "action=getNgayLamViec&MaBS=" + encodeURIComponent(MaBS)
    })
    .then(res => res.json())
    .then(list => {
        ngayLamViec = list.map(n => new Date(n).toISOString().split("T")[0]);
        console.log("Ngày làm việc của bác sĩ:", ngayLamViec);

        // reset lắng nghe sự kiện input (đảm bảo không bị chồng chéo)
        ngayKhamInput.oninput = function() {
            const val = this.value;
            if (!ngayLamViec.includes(val)) {
                alert("❌ Bác sĩ không làm việc vào ngày này!");
                this.value = "";
            }
        };
    })
    .catch(err => console.error("Lỗi khi tải ngày làm việc:", err));
});

// --- Khi chọn dịch vụ ---
dichvuSel.addEventListener("change", function(){
    datLichSel.disabled = (this.value === "");
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



</script>



<style>
.booking-form-wrapper .form-group {
    margin-bottom: 15px;
}

.booking-form-wrapper label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

.booking-form-wrapper input[type="text"],
.booking-form-wrapper select {
    width: 100%;
    padding: 8px;
    box-sizing: border-box;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.booking-form-wrapper select {
    height: 35px;
}

.booking-form-wrapper ul {
    list-style-type: disc;
    border-radius: 4px;
    overflow-y: auto;
    text-align: center;
    width: 100%;
}

.booking-form-wrapper li {
    display: inline-block;
    background: #f0f0f0;
    border: 1px solid #ccc;
    width: auto;
    padding: 10px;
    height: 50px;
    line-height: 30px;
}

.booking-form-wrapper .khunggio-box {
    border: 1px solid #ddd;
    border-radius: 6px;
    padding: 10px;
    background: #f9f9f9;
    min-height: 60px;
}

.booking-form-wrapper .time-slot {
    margin: 4px;
    padding: 6px 10px;
    background: white;
    border: 1px solid #007bff;
    border-radius: 4px;
    cursor: pointer;
    color: #007bff;
    transition: all 0.2s;
}

.booking-form-wrapper .time-slot:hover {
    background: #007bff;
    color: white;
}


</style>
<?php
$conn->close();
?>




