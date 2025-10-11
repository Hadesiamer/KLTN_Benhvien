<?php
$dt = json_decode($data["LLV"], true);
$K = json_decode($data["Khoa"], true);
$BS = json_decode($data["BS"], true);

if(isset($_SESSION['message'])): ?>
    <div class="alert alert-<?php echo $_SESSION['message_type'] == 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
        <?php 
        echo $_SESSION['message'];
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif;
// Lấy ngày hiện tại và xác định tuần hiện tại
date_default_timezone_set('Asia/Ho_Chi_Minh');
$homnay = date('Y-m-d');
$maxDate = date('Y-m-d', strtotime('+14 days'));
$week = date('w');
// Tính ngày đầu tuần và cuối tuần
$ngaydautuan = date('Y-m-d', strtotime("-" . (0 + $week) . " days"));
$ngaycuoituan = date('Y-m-d', strtotime("+" . (6 - $week) . " days"));
// Lấy dữ liệu từ POST
$selectedKhoa = isset($_POST['khoaSelect']) ? $_POST['khoaSelect'] : '';
$last_selectedKhoa = isset($_GET['khoaSelect']) ? $_GET['khoaSelect'] : '';
if ($selectedKhoa == '' && $last_selectedKhoa != '') {
    $selectedKhoa = $last_selectedKhoa;
}
// Kiểm tra thay đổi tuần
if (isset($_POST['changeWeek'])) {
    if ($_POST['changeWeek'] === 'prev') {
        $ngaydautuan = date('Y-m-d', strtotime($ngaydautuan . " -7 days"));
    } elseif ($_POST['changeWeek'] === 'next') {
        $ngaydautuan = date('Y-m-d', strtotime($ngaydautuan . " +7 days"));
    }elseif ($_POST['changeWeek'] === 'current') {
        $week = date('w');
        $ngaydautuan = date('Y-m-d', strtotime("-" . (0 + $week) . " days"));
    }
}
// Kiểm tra ngày đầu tuần
if (isset($_POST['currentWeekStart'])) {
    $ngaydautuan = $_POST['currentWeekStart'];
} else {
    $week = date('w');
    $ngaydautuan = date('Y-m-d', strtotime("-" . (0 + $week) . " days"));
}
// Tạo ngày trong tuần
for ($i = 1; $i <= 7; $i++) {
    ${'t' . ($i + 1)} = date('d-m-Y', strtotime($ngaydautuan . " +$i days"));
}
// Tạo mảng ngày trong tuần
$daysOfWeek = [];
for ($i = 1; $i < 8; $i++) {
    $daysOfWeek[] = date('Y-m-d', strtotime($ngaydautuan . " +{$i} days"));
}
echo '<h2 class="text-center mb-4" style="background-color: #007bff; color: white; font-weight: bold; padding: 5px; border-radius: 5px;">Quản lý lịch làm việc</h2>';
echo '<div class="row mb-4">';
    // Danh sách khoa
    echo '<div class="col-md-3 d-flex align-items-center">';
        echo '<form method="POST" action="./LLV" class="w-100">';
            echo '<div class="d-flex align-items-center">';
                echo '<select class="form-select" id="khoaSelect" name="khoaSelect" onchange="this.form.submit()" type="hidden" name="form_type" value="outside_khoa">';
                    echo '<option value="">Danh sách khoa</option>';
                    foreach ($K as $k) {
                    $selected = (isset($selectedKhoa) && $selectedKhoa == $k["MaKhoa"]) ?  "selected" : "";
                    
                    echo '<option value="' . $k["MaKhoa"] . '" ' . $selected . '>' . $k["TenKhoa"] . '</option>';
                    }
                echo '</select>';
            echo '</div>';
        echo '</form>';
    echo '</div>';
    //chuyển tuần
echo '<div class="col-md-3 ">';
        echo '<form method="POST" action="" class="d-flex gap-4">'; 
            echo '<input type="hidden" name="currentWeekStart" value="' . $ngaydautuan . '">';
            echo '<input type="hidden" name="khoaSelect" value="' . $selectedKhoa . '">';
            
            echo '<button class="btn btn-outline-secondary" type="submit" name="changeWeek" value="prev">';
                echo 'Tuần trước';
            echo '</button>';
            echo '<button class="btn btn-outline-primary" type="submit" name="changeWeek" value="current">';
                echo 'Hiện tại';
            echo '</button>';
            echo '<button class="btn btn-outline-secondary" type="submit" name="changeWeek" value="next">';
                echo 'Tuần sau';
            echo '</button>';
        echo '</form>';
    echo '</div>';
    // Thêm lịch
    echo '<div class="col-md-4">';
        echo '<button class="btn btn-outline-secondary" type="button" name="them" data-bs-toggle="modal" data-bs-target="#addDoctorModal">';
            echo 'Thêm lịch';
        echo '</button>';
    echo '</div>';
    
echo '</div>
        <div class="schedule-grid mb-4" id="schedule-container">
            <table class="schedule-table table table-bordered">
                <thead>
                    <tr>
                        <th id="a" class="shift">Ca</th>
                        <th id="a">Thứ 2<br>' . $t2 . '</th>
                        <th id="a">Thứ 3<br>' . $t3 . '</th>
                        <th id="a">Thứ 4<br>' . $t4 . '</th>
                        <th id="a">Thứ 5<br>' . $t5 . '</th>
                        <th id="a">Thứ 6<br>' . $t6 . '</th>
                        <th id="a">Thứ 7<br>' . $t7 . '</th>
                        <th id="a">Chủ nhật<br>' . $t8 . '</th>
                    </tr>
                </thead>
                <tbody>';
                echo '<tr>';
                echo '<td class="ca morning-shift shift">Ca Sáng</td>';
                // Hiển thị lịch làm việc của bác sĩ theo từng ngày trong tuần và ca làm việc sáng
                foreach ($daysOfWeek as $day) {
                    echo '<td class="shift-cell morning editable-cell">';
                    foreach ($dt as $data) {
                        if ($data['NgayLamViec'] === $day && $data['CaLamViec'] === 'Sáng' && $data['TrangThai'] === 'Đang làm') {
                            echo $data['HovaTen'] . ' 
                            <form method="POST" action="" style="display: inline;">
                            <input type="hidden" name="MaNV" value="' . $data['MaNV'] . '">
                            <input type="hidden" name="NgayLamViec" value="' . $data['NgayLamViec'] . '">
                            <input type="hidden" name="CaLamViec" value="' . $data['CaLamViec'] . '">';
                        if(strtotime($day) > strtotime($homnay)){
                            echo'
                            <button type="submit" class="delete-btn" onclick="return confirm(\'Bạn có chắc chắn muốn xóa ca làm việc của bác sĩ này không?\')">
                            <i class="bi bi-person-dash"></i>
                            </button>

                            </form><hr><br>';
                        }
                        else{
                            echo'</form><hr><br>';
                        }
                        }
                    }
                    if(strtotime($day) > strtotime($homnay . ' +2 days')){
                        echo '
                        <button 
                            type="button" 
                            class="add-btn" 
                            
                            data-bs-toggle="modal" 
                            data-bs-target="#addDoctorModal"
                            data-day="' . $day . '"
                            data-shift="Sáng"
                            
                        >
                            <i class="bi bi-person-plus"></i> Thêm
                        </button>';
                    
                        echo '</td>';

                        echo '</td>';
                    echo '</td>';
                    }
                }

                echo '</tr>';

                echo '<tr>';
                echo '<td class="ca afternoon-shift shift">Ca Chiều</td>';
                // Hiển thị lịch làm việc của bác sĩ theo từng ngày trong tuần và ca làm việc chiều
                foreach ($daysOfWeek as $day) {
                    echo '<td class="shift-cell afternoon editable-cell">';
                    $hasWork = false;
                    foreach ($dt as $data) {
                        if ($data['NgayLamViec'] === $day && $data['CaLamViec'] === 'Chiều' && $data['TrangThai'] === 'Đang làm') {
                            echo $data['HovaTen'] . ' 
                            <form method="POST" action="./LLV" style="display: inline;">
                            <input type="hidden" name="MaNV" value="' . $data['MaNV'] . '">
                            <input type="hidden" name="NgayLamViec" value="' . $data['NgayLamViec'] . '">
                            <input type="hidden" name="CaLamViec" value="' . $data['CaLamViec'] . '">';
                            if($homnay < $day){
                            echo'
                            <button type="submit" class="delete-btn" onclick="return confirm(\'Bạn có chắc chắn muốn xóa ca làm việc của bác sĩ này không?\')">
                            <i class="bi bi-person-dash"></i>
                            </button>
                            </form><hr><br>';
                            }
                            else{
                                echo'</form><hr><br>';
                            }
                            
                    }
                    }
                    if(strtotime($day) > strtotime($homnay . ' +2 days')){
                        echo '
                        <button 
                            type="button" 
                            class="add-btn" 
                            
                            data-bs-toggle="modal" 
                            data-bs-target="#addDoctorModal"
                            data-day="' . $day . '"
                            data-shift="Chiều"
                            
                        >
                            <i class="bi bi-person-plus"></i> Thêm
                        </button>';
                    
                        echo '</td>';

                        echo '</td>';
                    echo '</td>';
                    }
                    echo '</td>';
                }

                echo '</tr>';
        echo '  </tbody>
            </table>
        </div>';



        
        // modal thêm lịch làm việc
        echo '
        <div class="modal fade" id="addDoctorModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm bác sĩ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="./LLV" method="POST">
                    <!-- Hidden input để lưu mã khoa đã chọn -->
                    <input type="hidden" name="form_type" value="add_schedule">
                    <input type="hidden" name="khoaSelect" value="' . $selectedKhoa . '">';
                    if($selectedKhoa == '') {
                        echo '<div class="alert alert-warning" role="alert">
                                Vui lòng chọn khoa trước khi thêm lịch làm việc!
                              </div>';
                        // chon khoa
                        echo '<div class="mb-3">
                                <label for="khoaSelectModal" class="form-label">Chọn khoa</label>
                                <select class="form-select" id="khoaSelectModal" name="khoaSelect" required type="hidden" name="form_type" value="modal_khoa">
                                    <option value="">-- Chọn khoa --</option>';
                                    foreach ($K as $k) {
                                        $selected = (isset($_POST["khoaSelect"]) && $_POST["khoaSelect"] == $k["MaKhoa"]) ? "selected" : "";
                                        if (isset($_POST['ajax']) && $_POST['ajax'] == '1') {
                                            foreach($BS as $data):
                                                if($data["MaKhoa"] == $_POST["khoaSelect"]):
                                                    echo '
                                                    <tr style="text-align:center;">
                                                        <td><input type="radio" class="doctor-checkbox" value="' . $data["MaNV"] . '" name="MaNVien"></td>
                                                        <td>' . $data["HovaTen"] . '</td>
                                                        <td>' . $data["TenKhoa"] . '</td>
                                                    </tr>';
                                                endif;
                                            endforeach;
                                            exit; // Dừng ở đây để không render toàn bộ trang
                                        }
                                        echo '<option value="' . $k["MaKhoa"] . '" ' . $selected . '>' . $k["TenKhoa"] . '</option>';
                                    }
                        echo    '</select>
                              </div>';
                     
                    }

                    
                       
        echo'
                    <table class="table mb-3">
                        <thead>
                            <tr>
                                <th><i class="bi bi-person-plus-fill"></i></th>
                                <th>Tên</th>
                                <th>Khoa</th>
                            </tr>
                        </thead>
                        <tbody id="doctorTableBody">';
                        echo'<div>';
                            foreach($BS as $data):
                                if($data["MaKhoa"] == $selectedKhoa):
                                    echo '
                                    <tr style="text-align:center;">
                                        <td><input type="radio" class="doctor-checkbox" value="' . $data["MaNV"] . '" name="MaNVien"></td>
                                        <td>' . $data["HovaTen"] . '</td>
                                        <td>' . $data["TenKhoa"] . '</td>
                                    </tr>';
                                endif;
                            endforeach;
                        echo '</div>';
                echo '
                        </tbody>
                    </table>
                    <!-- Chọn lịch -->
                    <div class="mb-3">
                        <label for="scheduleDate" class="form-label">Chọn lịch</label>
                        <input type="date" class="form-control" id="NgayLamViec" name="NgayLamViec" required min="'.strtotime($homnay . ' +2 days').'" max="'.$maxDate.'">
                    </div>
                    <div class="mb-3">
                        <label for="scheduleShift" class="form-label" >Chọn ca</label>
                        <select class="form-select" id="scheduleShift" name="cl" required>
                            <option value="">-- Chọn ca --</option>
                            <option value="Sáng">Ca sáng</option>
                            <option value="Chiều">Ca chiều</option>
                        </select>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary" name="btnDKL">Xác nhận</button>
                </div>
                </form>
                </div>
            </div>
        </div>';
        if (
            isset($_POST['khoaSelect']) 
            && $_POST['khoaSelect'] != '' 
            && isset($_POST['form_type']) 
            && $_POST['form_type'] === 'modal_khoa'
        ) {
            echo "
            <script>
              document.addEventListener('DOMContentLoaded', function() {
                var modal = new bootstrap.Modal(document.getElementById('addDoctorModal'));
                modal.show();
              });
            </script>
            ";
        }
       
        
        
?>
<script>
document.addEventListener("DOMContentLoaded", function () {

    // === Xử lý chuyển tuần ===
    const weekButtons = document.querySelectorAll("button[name='changeWeek']");
    weekButtons.forEach(btn => {
        btn.addEventListener("click", function(e) {
            e.preventDefault();
            const weekChange = btn.value;
            let currentWeekStart = new Date(document.querySelector("input[name='currentWeekStart']").value);

            if (weekChange === 'prev') {
                currentWeekStart.setDate(currentWeekStart.getDate() - 7);
            } else if (weekChange === 'next') {
                currentWeekStart.setDate(currentWeekStart.getDate() + 7);
            } else if (weekChange === 'current') {
                const today = new Date();
                currentWeekStart = new Date(today.setDate(today.getDate() - today.getDay()));
            }

            document.querySelector("input[name='currentWeekStart']").value = currentWeekStart.toISOString().split('T')[0];

            const selectedKhoa = document.querySelector("select[name='khoaSelect']")?.value || "";

            updateSchedule({ 
                changeWeek: weekChange, 
                currentWeekStart: currentWeekStart.toISOString().split('T')[0], 
                khoaSelect: selectedKhoa 
            });
        });
    });

    function updateSchedule(params) {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", window.location.href, true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                const parser = new DOMParser();
                const doc = parser.parseFromString(xhr.responseText, "text/html");
                const newSchedule = doc.querySelector("#schedule-container");
                if (newSchedule) document.querySelector("#schedule-container").innerHTML = newSchedule.innerHTML;
            }
        };
        const paramString = Object.keys(params).map(k => `${k}=${encodeURIComponent(params[k])}`).join("&");
        xhr.send(paramString);
    }

    // === Xử lý thêm lịch trực tiếp trong bảng và hiển thị ngay ===
    const editableCells = document.querySelectorAll(".editable-cell");
    editableCells.forEach(cell => {
        cell.addEventListener("click", function () {
            const day = this.dataset.day;
            const shift = this.dataset.shift;
            if (this.innerText.trim() !== "") return;

            this.innerHTML = `
                <form class="inline-add-schedule" style="font-size:13px">
                    <select name="MaNV" class="form-select form-select-sm">
                        <option value="">-- Bác sĩ --</option>
                        <?php foreach ($BS as $data): ?>
                            <option value="<?php echo $data["MaNV"]; ?>"><?php echo $data["HovaTen"]; ?> (<?php echo $data["TenKhoa"]; ?>)</option>
                        <?php endforeach; ?>
                    </select>
                    <input type="hidden" name="NgayLamViec" value="${day}">
                    <input type="hidden" name="CaLamViec" value="${shift}">
                    <button type="submit" class="btn btn-sm btn-success mt-1">Thêm</button>
                </form>
            `;

            const form = this.querySelector(".inline-add-schedule");
            form.addEventListener("submit", function(e) {
                e.preventDefault();
                const formData = new FormData(this);

                fetch("./LLV", { method: "POST", body: formData })
                    .then(res => res.json()) // giả sử server trả về JSON với tên bác sĩ vừa thêm
                    .then(data => {
                        if (data.success) {
                            // Hiển thị tên bác sĩ vừa thêm trong ô
                            cell.innerHTML = `
                                ${data.HovaTen}
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="MaNV" value="${data.MaNV}">
                                    <input type="hidden" name="NgayLamViec" value="${day}">
                                    <input type="hidden" name="CaLamViec" value="${shift}">
                                    <button type="submit" class="delete-btn" onclick="return confirm('Bạn có chắc chắn muốn xóa ca làm việc của bác sĩ này không?')">
                                        <i class="bi bi-person-dash"></i>
                                    </button>
                                </form>
                            `;
                        } else {
                            alert("Thêm bác sĩ thất bại!");
                        }
                    })
                    .catch(err => console.error("Lỗi thêm lịch:", err));
            });
        });
    });

    // === Xử lý chọn khoa trong modal ===
    const khoaSelectModal = document.getElementById("khoaSelectModal");
    const doctorTableBody = document.getElementById("doctorTableBody");
    if (khoaSelectModal) {
        khoaSelectModal.addEventListener("change", function () {
            const selectedKhoa = this.value;
            if (!selectedKhoa) return;

            fetch("./LLV", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `ajax=1&khoaSelect=${encodeURIComponent(selectedKhoa)}`
            })
            .then(res => res.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, "text/html");
                const newTable = doc.querySelector("#doctorTableBody");
                if (newTable) doctorTableBody.innerHTML = newTable.innerHTML;
            })
            .catch(err => console.error("Lỗi tải danh sách bác sĩ:", err));
        });
    }

    // === Modal thêm bác sĩ hiển thị đúng ngày ca ===
    const addDoctorModal = document.getElementById("addDoctorModal");
    if (addDoctorModal) {
        addDoctorModal.addEventListener("show.bs.modal", function (event) {
            const button = event.relatedTarget;
            if (!button) return;

            const ngay = button.getAttribute("data-day");
            const ca = button.getAttribute("data-shift");

            const dateInput = addDoctorModal.querySelector("input[name='NgayLamViec']");
            const shiftSelect = addDoctorModal.querySelector("select[name='cl']");

            if (dateInput) dateInput.value = ngay;
            if (shiftSelect) shiftSelect.value = ca;
        });
    }

});
</script>


<style>
    .add-btn, .delete-btn, .edit-btn {
    border: none;
    background: none;
    cursor: pointer;
    margin: 0 3px;
}

.add-btn i { color: green; }
.edit-btn i { color: #007bff; }
.delete-btn i { color: red; }

.add-btn:hover i,
.edit-btn:hover i,
.delete-btn:hover i {
    opacity: 0.8;
}

</style>
