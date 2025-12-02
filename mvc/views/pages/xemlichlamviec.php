<?php
// Lấy dữ liệu do controller Bacsi::XemLichLamViec truyền sang
$lichLamViec  = $data['LichLamViec']  ?? [];
$lichNghiPhep = $data['LichNghiPhep'] ?? [];
$maNV         = $_SESSION['MaNV'] ?? $_SESSION['idnv'] ?? '';
?>

<!-- ====== HIỂN THỊ GIAO DIỆN ====== -->
<div class="container">
    <h2 class="mb-4">Lịch làm việc của bạn</h2>
    <div class="d-flex justify-content-between align-items-center mb-4 week-nav">
        <div class="btn-group week-nav-group" role="group" aria-label="Điều hướng tuần">
            <button id="prevWeek" class="btn btn-light week-nav-btn">
                <i class="bi bi-chevron-left"></i>
                <span class="d-none d-sm-inline">Tuần trước</span>
            </button>
            <button id="currentWeek" class="btn btn-primary week-nav-btn-current">
                <i class="bi bi-calendar-event"></i>
                <span class="d-none d-sm-inline">Hiện tại</span>
            </button>
            <button id="nextWeek" class="btn btn-light week-nav-btn">
                <span class="d-none d-sm-inline">Tuần sau</span>
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>
        
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

</div>

<!-- Modal xin nghỉ -->
<div id="leave-modal" aria-hidden="true">
    <div class="modal-content">
        <span id="close-modal" class="close">&times;</span>
        <h3>Đăng ký nghỉ phép</h3>

        <form id="leave-form" method="post" action="">
            <input type="hidden" name="action" value="request_leave">
            <input type="hidden" id="leave-manv" name="MaNV" value="<?= htmlspecialchars($maNV, ENT_QUOTES, 'UTF-8') ?>">

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
    // Dữ liệu lấy từ controller, không còn truy vấn DB trong view
    const workSchedule   = <?= json_encode($lichLamViec, JSON_UNESCAPED_UNICODE); ?>;
    const leaveRequests  = <?= json_encode($lichNghiPhep, JSON_UNESCAPED_UNICODE); ?>;

    const leaveModal     = document.getElementById('leave-modal');
    const closeModal     = document.getElementById('close-modal');
    const leaveForm      = document.getElementById('leave-form');

    let currentMonday         = null;          // Thứ 2 của tuần đang xem
    let currentLeaveButton    = null;          // Nút xin nghỉ vừa bấm
    let currentLeaveMaLLV     = null;          // MaLLV tương ứng (để cập nhật mảng leaveRequests)

    function parseLocalDate(dateStr) {
        // dateStr dạng "YYYY-mm-dd"
        return new Date(dateStr + 'T00:00:00');
    }

    function getMonday(d) {
        const date = new Date(d);
        const day = date.getDay();
        const diff = day === 0 ? -6 : 1 - day;
        date.setDate(date.getDate() + diff);
        date.setHours(0,0,0,0);
        return date;
    }

    function formatDate(date) {
        return date.getDate().toString().padStart(2,'0') + '/' +
               (date.getMonth()+1).toString().padStart(2,'0') + '/' +
               date.getFullYear();
    }

    function updateWeekRange(monday) {
        const sunday = new Date(monday);
        sunday.setDate(monday.getDate() + 6);
        document.getElementById('weekRange').textContent =
            `${formatDate(monday)} - ${formatDate(sunday)}`;

        const days = ['mon','tue','wed','thu','fri','sat','sun'];
        days.forEach((day, i) => {
            const date = new Date(monday);
            date.setDate(monday.getDate() + i);
            document.getElementById(`date-${day}`).textContent = formatDate(date);
            document.getElementById(`schedule-${day}`).innerHTML = '';
        });

        // Chuẩn hóa mốc thời gian hôm nay & ngày mai
        const today = new Date();
        today.setHours(0,0,0,0);
        const tomorrow = new Date(today);
        tomorrow.setDate(today.getDate() + 1);

        workSchedule.forEach(s => {
            const date = parseLocalDate(s.NgayLamViec);
            if (date >= monday && date <= sunday) {
                const dayIndex = (date.getDay() + 6) % 7;
                const daysArr = ['mon','tue','wed','thu','fri','sat','sun'];
                const dayKey = daysArr[dayIndex];
                const cell = document.getElementById(`schedule-${dayKey}`);
                if (!cell) return;

                const shiftBadge = document.createElement('span');
                shiftBadge.textContent = s.CaLamViec;
                shiftBadge.className = s.CaLamViec.toLowerCase() === 'sáng'
                    ? 'badge bg-primary text-light'
                    : 'badge bg-warning text-dark';

                const leaveBtn = document.createElement('button');
                leaveBtn.type = 'button';
                leaveBtn.setAttribute('data-mallv', s.MaLLV);

                const foundLeave = leaveRequests.find(l => String(l.MaLLV) === String(s.MaLLV));

                // Quy tắc xin nghỉ:
                // - Nếu đã có yêu cầu nghỉ cho MaLLV => nút xám, disable
                // - Nếu ngày < ngày mai => không cho xin nghỉ (nút xám, disable)
                // - Nếu ngày >= ngày mai => nút đỏ, click được mở modal
                const isFutureAllowed = (date.getTime() >= tomorrow.getTime());

                if (foundLeave) {
                    leaveBtn.innerHTML = `<i class="bi bi-person-dash"></i>`;
                    leaveBtn.className = 'btn btn-secondary btn-sm';
                    leaveBtn.disabled = true;
                    leaveBtn.title = 'Bạn đã gửi yêu cầu nghỉ cho ca này.';
                } else if (!isFutureAllowed) {
                    leaveBtn.innerHTML = '<i class="bi bi-person-dash"></i>';
                    leaveBtn.className = 'btn btn-secondary btn-sm';
                    leaveBtn.disabled = true;
                    leaveBtn.title = 'Chỉ được xin nghỉ cho các ca ở tương lai (trước ít nhất 1 ngày).';
                } else {
                    leaveBtn.innerHTML = '<i class="bi bi-person-dash"></i>';
                    leaveBtn.className = 'btn btn-outline-danger btn-sm';
                    leaveBtn.title = 'Xin nghỉ ca làm việc này';
                    leaveBtn.addEventListener('click', (ev) => {
                        ev.stopPropagation();
                        // Lưu lại nút & MaLLV đang xin nghỉ để xử lý sau khi gửi thành công
                        currentLeaveButton = leaveBtn;
                        currentLeaveMaLLV  = s.MaLLV;

                        document.getElementById('leave-date').value  = s.NgayLamViec;
                        document.getElementById('leave-shift').value = s.CaLamViec;
                        leaveModal.style.display = 'flex';
                        leaveModal.setAttribute('aria-hidden', 'false');
                    });
                }

                const shiftBox = document.createElement('div');
                shiftBox.className = 'shift-box';
                shiftBox.appendChild(shiftBadge);
                shiftBox.appendChild(leaveBtn);
                cell.appendChild(shiftBox);
            }
        });
    }

    // Khởi tạo tuần hiện tại
    currentMonday = getMonday(new Date());
    updateWeekRange(currentMonday);

    // Điều hướng tuần
    const prevWeekBtn    = document.getElementById('prevWeek');
    const nextWeekBtn    = document.getElementById('nextWeek');
    const currentWeekBtn = document.getElementById('currentWeek');

    if (prevWeekBtn) {
        prevWeekBtn.addEventListener('click', () => {
            currentMonday.setDate(currentMonday.getDate() - 7);
            updateWeekRange(currentMonday);
        });
    }
    if (nextWeekBtn) {
        nextWeekBtn.addEventListener('click', () => {
            currentMonday.setDate(currentMonday.getDate() + 7);
            updateWeekRange(currentMonday);
        });
    }
    if (currentWeekBtn) {
        currentWeekBtn.addEventListener('click', () => {
            currentMonday = getMonday(new Date());
            updateWeekRange(currentMonday);
        });
    }

    const printBtn = document.getElementById('print-schedule');
    if (printBtn) {
        printBtn.addEventListener('click', () => window.print());
    }

    // Đóng modal
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

    // Submit form nghỉ phép bằng fetch AJAX
    if (leaveForm) {
        leaveForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(leaveForm);

            try {
                const res = await fetch(window.location.href, { method: 'POST', body: formData });
                const html = await res.text();

                // Chèn alert từ server trả về (giữ nguyên behavior cũ)
                const alertBox = document.createElement('div');
                alertBox.innerHTML = html.trim();
                const alertElement = alertBox.querySelector('.alert');
                if (alertElement) document.body.insertAdjacentElement('afterbegin', alertElement);

                // Nếu server trả về thành công hoặc cảnh báo, vẫn đóng modal
                leaveModal.style.display = 'none';
                leaveModal.setAttribute('aria-hidden', 'true');

                // Nếu đang có nút xin nghỉ được gắn (click từ một ca cụ thể)
                if (currentLeaveButton) {
                    // Đổi nút sang trạng thái "đã gửi yêu cầu": xám + disable
                    currentLeaveButton.className = 'btn btn-secondary btn-sm';
                    currentLeaveButton.disabled  = true;
                    currentLeaveButton.title     = 'Bạn đã gửi yêu cầu nghỉ cho ca này.';

                    // Thêm vào mảng leaveRequests để tránh người dùng chuyển tuần rồi quay lại bị render lại sai
                    if (currentLeaveMaLLV) {
                        leaveRequests.push({
                            MaLLV: String(currentLeaveMaLLV),
                            TrangThai: 'Chờ duyệt'
                        });
                    }
                }

                // Tự động ẩn alert sau 4 giây
                setTimeout(() => {
                    const alert = document.querySelector('.alert');
                    if (alert) alert.remove();
                }, 4000);
            } catch (err) {
                console.error(err);
                alert('Đã xảy ra lỗi khi gửi yêu cầu nghỉ phép.');
            }
        });
    }
});
</script>

<style>
/* Thanh điều hướng tuần đẹp hơn */
.week-nav {
    gap: 12px;
}

.week-nav-group {
    border-radius: 999px;
    overflow: hidden;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
}

.week-nav-btn,
.week-nav-btn-current {
    border-radius: 0;
    border: none;
    padding: 8px 14px;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.week-nav-btn {
    background-color: #ffffff;
}

.week-nav-btn:hover {
    background-color: #f1f3f5;
}

.week-nav-btn-current {
    font-weight: 600;
}

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

/* Nút xin nghỉ nhỏ, gọn */
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
