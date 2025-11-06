<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'request_leave') {
    $conn = new mysqli("localhost", "root", "", "domdom");
    $conn->set_charset("utf8");

    $manv = $_POST['MaNV'] ?? $_SESSION['MaNV'] ?? $_SESSION['idnv'] ?? null;
    $ngaynghi = $_POST['NgayNghi'] ?? '';
    $calamviec = $_POST['CaLamViec'] ?? '';
    $lydo = trim($_POST['LyDo'] ?? '');
    $trangthai = "Chờ duyệt";

    // --- Kiểm tra thiếu thông tin
    if (!$manv || !$ngaynghi || !$calamviec || !$lydo) {
        echo '<div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                Thiếu thông tin. Vui lòng thử lại!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
              </div>';
        exit;
    }

    // --- Tìm MaLLV
    $conn->query("SET NAMES 'utf8'");
    $find = $conn->prepare("SELECT MaLLV FROM lichlamviec WHERE MaNV = ? AND NgayLamViec = ? AND CaLamViec = ? AND TrangThai='Đang làm' LIMIT 1");
    $find->bind_param("sss", $manv, $ngaynghi, $calamviec);
    $find->execute();
    $res = $find->get_result();
    $row = $res->fetch_assoc();
    $mallv = $row['MaLLV'] ?? null;
    $find->close();

    if (!$mallv) {
        echo '<div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                Không tìm thấy ca làm việc tương ứng!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
              </div>';
        exit;
    }

    // --- Kiểm tra trùng
    $check = $conn->prepare("SELECT 1 FROM lichnghiphep WHERE MaNV = ? AND MaLLV = ? LIMIT 1");
    $check->bind_param("ss", $manv, $mallv);
    $check->execute();
    $res = $check->get_result();
    if ($res->num_rows > 0) {
        echo '<div class="alert alert-warning alert-dismissible fade show mt-3" role="alert">
                Bạn đã gửi yêu cầu nghỉ cho ca này rồi!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
              </div>';
        $check->close();
        $conn->close();
        exit;
    }
    $check->close();

    // --- Insert yêu cầu nghỉ
    $stmt = $conn->prepare("INSERT INTO lichnghiphep (MaNV, MaLLV, LyDo, TrangThai) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $manv, $mallv, $lydo, $trangthai);
    if ($stmt->execute()) {
        echo '<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                Gửi yêu cầu nghỉ phép thành công!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
              </div>';
    } else {
        echo '<div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                Gửi yêu cầu nghỉ phép thất bại. Vui lòng thử lại.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
              </div>';
    }

    $stmt->close();
    $conn->close();
    exit;
}

// ================== LẤY DỮ LIỆU LỊCH LÀM VÀ NGHỈ PHÉP ==================
$conn = new mysqli("localhost", "root", "", "domdom");
$conn->set_charset("utf8");

$manv = $_SESSION['MaNV'] ?? $_SESSION['idnv'] ?? null;

// Lịch làm việc
$sql1 = $conn->prepare("SELECT MaLLV, MaNV, NgayLamViec, CaLamViec FROM lichlamviec WHERE MaNV = ? AND TrangThai = 'Đang làm'");
$sql1->bind_param("s", $manv);
$sql1->execute();
$res1 = $sql1->get_result();
$data['LichLamViec'] = $res1->fetch_all(MYSQLI_ASSOC);
$sql1->close();

// Lịch nghỉ phép (đã gửi)
$sql2 = $conn->prepare("SELECT MaLLV, TrangThai FROM lichnghiphep WHERE MaNV = ?");
$sql2->bind_param("s", $manv);
$sql2->execute();
$res2 = $sql2->get_result();
$data['LichNghiPhep'] = $res2->fetch_all(MYSQLI_ASSOC);
$sql2->close();
$conn->close();
?>


<!-- ====== HIỂN THỊ GIAO DIỆN ====== -->
<div class="container">
    <h2 class="mb-4">Lịch làm việc của bạn</h2>
    <div class="d-flex justify-content-between mb-4">
        <button id="prevWeek" class="btn btn-secondary">Tuần trước</button>
        <button id="currentWeek" class="btn btn-primary">Hiện tại</button>
        <button id="nextWeek" class="btn btn-secondary">Tuần sau</button>
    </div>

    <div id="weekRange" class="text-center fw-bold mb-4"></div>

    <div id="schedule-container">
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>Thứ 2<br><span id="date-mon" class="text-muted small"></span></th>
                    <th>Thứ 3<br><span id="date-tue" class="text-muted small"></span></th>
                    <th>Thứ 4<br><span id="date-wed" class="text-muted small"></span></th>
                    <th>Thứ 5<br><span id="date-thu" class="text-muted small"></span></th>
                    <th>Thứ 6<br><span id="date-fri" class="text-muted small"></span></th>
                    <th>Thứ 7<br><span id="date-sat" class="text-muted small"></span></th>
                    <th>Chủ nhật<br><span id="date-sun" class="text-muted small"></span></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <?php $days = ['mon','tue','wed','thu','fri','sat','sun'];
                    foreach ($days as $day): ?>
                        <td id="schedule-<?= $day ?>"></td>
                    <?php endforeach; ?>
                </tr>
            </tbody>
        </table>
    </div>

    <button id="print-schedule" class="btn btn-primary mt-3">In Lịch Làm Việc</button>
</div>

<!-- Modal xin nghỉ -->
<div id="leave-modal" aria-hidden="true">
    <div class="modal-content">
        <span id="close-modal" class="close">&times;</span>
        <h3>Đăng ký nghỉ phép</h3>

        <form id="leave-form" method="post" action="">
            <input type="hidden" name="action" value="request_leave">
            <input type="hidden" id="leave-manv" name="MaNV" value="<?= htmlspecialchars($_SESSION['MaNV'] ?? $_SESSION['idnv'] ?? '') ?>">

            <div class="mb-3">
                <label class="form-label">Ngày nghỉ</label>
                <input type="date" id="leave-date" name="NgayNghi" class="form-control" readonly />
            </div>

            <div class="mb-3">
                <label class="form-label">Ca làm việc</label>
                <input type="text" id="leave-shift" name="CaLamViec" class="form-control" readonly />
            </div>

            <div class="mb-3">
                <label class="form-label">Lý do nghỉ</label>
                <textarea name="LyDo" class="form-control" required></textarea>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-primary">Gửi yêu cầu</button>
            </div>
        </form>
    </div>
</div>

<script>
window.addEventListener('DOMContentLoaded', () => {
    const workSchedule = <?= json_encode($data['LichLamViec'] ?? []); ?>;
    const leaveRequests = <?= json_encode($data['LichNghiPhep'] ?? []); ?>;

    const leaveModal = document.getElementById('leave-modal');
    const closeModal = document.getElementById('close-modal');

    function parseLocalDate(dateStr) { return new Date(dateStr + 'T00:00:00'); }

    function getMonday(d) {
        const date = new Date(d);
        const day = date.getDay();
        const diff = day === 0 ? -6 : 1 - day;
        date.setDate(date.getDate() + diff);
        date.setHours(0,0,0,0);
        return date;
    }

    function formatDate(date) {
        return date.getDate().toString().padStart(2,'0') + '/' + (date.getMonth()+1).toString().padStart(2,'0') + '/' + date.getFullYear();
    }

    function updateWeekRange(monday) {
        const sunday = new Date(monday);
        sunday.setDate(monday.getDate() + 6);
        document.getElementById('weekRange').textContent = `${formatDate(monday)} - ${formatDate(sunday)}`;

        const days = ['mon','tue','wed','thu','fri','sat','sun'];
        days.forEach((day, i) => {
            const date = new Date(monday);
            date.setDate(monday.getDate() + i);
            document.getElementById(`date-${day}`).textContent = formatDate(date);
            document.getElementById(`schedule-${day}`).innerHTML = '';
        });

        workSchedule.forEach(s => {
            const date = parseLocalDate(s.NgayLamViec);
            if (date >= monday && date <= sunday) {
                const dayIndex = (date.getDay() + 6) % 7;
                const dayKey = days[dayIndex];
                const cell = document.getElementById(`schedule-${dayKey}`);
                if (!cell) return;

                const box = document.createElement('div');
                box.className = 'shift-box';

                const shift = document.createElement('span');
                shift.textContent = s.CaLamViec;
                shift.className = s.CaLamViec.toLowerCase() === 'sáng'
                    ? 'badge bg-primary text-light'
                    : 'badge bg-warning text-dark';

                const leaveBtn = document.createElement('button');
                leaveBtn.type = 'button';

                const foundLeave = leaveRequests.find(l => l.MaLLV === s.MaLLV);
                if (foundLeave) {
                    leaveBtn.innerHTML = `<i class="bi bi-person-dash"></i>`;
                    leaveBtn.className = 'btn btn-secondary btn-sm';
                    leaveBtn.disabled = true;
                } else {
                    leaveBtn.innerHTML = '<i class="bi bi-person-dash"></i>';
                    leaveBtn.className = 'btn btn-outline-danger btn-sm';
                    leaveBtn.addEventListener('click', (ev) => {
                        ev.stopPropagation();
                        document.getElementById('leave-date').value = s.NgayLamViec;
                        document.getElementById('leave-shift').value = s.CaLamViec;
                        leaveModal.style.display = 'flex';
                        leaveModal.setAttribute('aria-hidden', 'false');
                    });
                }

                const shiftBox = document.createElement('div');
                shiftBox.className = 'shift-box';
                shiftBox.appendChild(shift);
                shiftBox.appendChild(leaveBtn);
                cell.appendChild(shiftBox);
            }
        });
    }

    let currentMonday = getMonday(new Date());
    updateWeekRange(currentMonday);

    document.getElementById('prevWeek').addEventListener('click', () => {
        currentMonday.setDate(currentMonday.getDate() - 7);
        updateWeekRange(currentMonday);
    });
    document.getElementById('nextWeek').addEventListener('click', () => {
        currentMonday.setDate(currentMonday.getDate() + 7);
        updateWeekRange(currentMonday);
    });
    document.getElementById('currentWeek').addEventListener('click', () => {
        currentMonday = getMonday(new Date());
        updateWeekRange(currentMonday);
    });
    document.getElementById('print-schedule').addEventListener('click', () => window.print());

    closeModal.addEventListener('click', () => {
        leaveModal.style.display = 'none';
        leaveModal.setAttribute('aria-hidden','true');
    });
    window.addEventListener('click', (e) => {
        if (e.target === leaveModal) {
            leaveModal.style.display = 'none';
            leaveModal.setAttribute('aria-hidden','true');
        }
    });
});

document.getElementById('leave-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);

    try {
        const res = await fetch(window.location.href, { method: 'POST', body: formData });
        const html = await res.text();
        const alertBox = document.createElement('div');
        alertBox.innerHTML = html.trim();
        const alertElement = alertBox.querySelector('.alert');
        if (alertElement) document.body.insertAdjacentElement('afterbegin', alertElement);
        const leaveModal = document.getElementById('leave-modal');
        leaveModal.style.display = 'none';
        leaveModal.setAttribute('aria-hidden', 'true');
        setTimeout(() => {
            const alert = document.querySelector('.alert');
            if (alert) alert.remove();
        }, 4000);
    } catch (err) {
        console.error(err);
        alert('Đã xảy ra lỗi khi gửi yêu cầu nghỉ phép.');
    }
});
</script>

<style>
/* modal styles */
#leave-modal {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.45);
    justify-content: center;
    align-items: center;
    z-index: 2000;
}
#leave-modal[aria-hidden="false"] {
    display: flex;
}
#leave-modal .modal-content {
    background: #fff;
    padding: 22px;
    border-radius: 10px;
    width: 100%;
    max-width: 480px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.2);
    position: relative;
}
#leave-modal .close {
    position: absolute;
    right: 12px;
    top: 8px;
    font-size: 26px;
    cursor: pointer;
    color: #666;
}
#leave-modal .close:hover { color: #000; }
.leave-day-btn { font-size: 12px; padding: 4px 8px; border-radius: 6px; }
/* Ô ca làm việc */
#schedule-container td {
    vertical-align: top;
    height: 120px;
    width: 14.28%;
    position: relative;
}

/* Khung chứa mỗi ca + nút xin nghỉ */
.shift-box {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    padding: 6px 10px;
    border-radius: 6px;
    margin-bottom: 6px;
}

/* Ca làm việc (chữ Sáng / Chiều) to hơn, đậm hơn */
.shift-box .badge {
    font-size: 15px;
    font-weight: 600;
    padding: 6px 10px;
}

/* Nút xin nghỉ nhỏ, ngắn, gọn */
.shift-box button {
    font-size: 11px;
    padding: 2px 4px; 
    border-radius: 4px;
    line-height: 1.2;
    min-width: auto;    
    width: auto;         
}

/* Ca sáng */
.badge.bg-primary {
    background-color: #0d6efd !important;
}

/* Ca chiều */
.badge.bg-warning {
    background-color: #ffc107 !important;
}

/* Hover nhẹ cho nút */
.shift-box button:hover {
    opacity: 0.85;
}


</style>
