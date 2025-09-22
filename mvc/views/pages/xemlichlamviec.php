<div class="container">
    <h2 class="mb-4">Lịch làm việc của bạn</h2>

    <?php if (isset($data['LichLamViec']) && !empty($data['LichLamViec'])): ?>
        <!-- Week Navigation -->
        <div class="d-flex justify-content-between mb-4">
            <button id="prevWeek" class="btn btn-secondary">Tuần trước</button>
            <button id="currentWeek" class="btn btn-primary">Hiện tại</button>
            <button id="nextWeek" class="btn btn-secondary">Tuần sau</button>
        </div>

        <!-- Week Range Display -->
        <div id="weekRange" class="text-center fw-bold mb-4"></div>
        <div id="schedule-container">
            <!-- Schedule Table -->
            <table class="table table-bordered">
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
                        <?php
                        $days = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];
                        foreach ($days as $day):
                        ?>
                            <td id="schedule-<?= $day ?>"></td>
                        <?php endforeach; ?>
                    </tr>
                </tbody>
            </table>
        </div>
        <button id="print-schedule" class="btn btn-primary mt-3">In Lịch Làm Việc</button>

    <?php else: ?>
        <p>Không có lịch làm việc nào được đăng ký.</p>
    <?php endif; ?>
</div>

<script>
    // Dữ liệu lịch làm việc từ PHP
const workSchedule = <?= json_encode($data['LichLamViec'] ?? []); ?>;

function getMonday(date) {
        const d = new Date(date);
        const day = d.getUTCDay(); // Lấy ngày theo UTC
        const diff = day === 0 ? -6 : 1 - day; // Chủ Nhật trở về Thứ 2
        d.setUTCDate(d.getUTCDate() + diff); // Tính lại ngày
        return new Date(d.toISOString().slice(0, 10)); // Chuyển về định dạng ISO
    }
    
// Hàm định dạng ngày
function formatDate(date) {
    return `${date.getDate().toString().padStart(2, '0')}/${(date.getMonth() + 1).toString().padStart(2, '0')}/${date.getFullYear()}`;
}

// Hàm cập nhật khoảng thời gian tuần
function updateWeekRange(monday) {
    const sunday = new Date(monday);
    sunday.setDate(monday.getDate() + 6);

    // Hiển thị khoảng thời gian tuần
    document.getElementById('weekRange').textContent = `${formatDate(monday)} - ${formatDate(sunday)}`;

    // Xóa lịch làm việc cũ
    const days = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];
    days.forEach((day, index) => {
        const date = new Date(monday);
        date.setDate(date.getDate() + index);

        // Cập nhật ngày hiển thị dưới "Thứ"
        document.getElementById(`date-${day}`).textContent = formatDate(date);

        // Xóa nội dung lịch làm việc cũ
        const cell = document.getElementById(`schedule-${day}`);
        if (cell) cell.innerHTML = '';
    });

    // Cập nhật nội dung lịch làm việc
    workSchedule.forEach(schedule => {
        const date = new Date(schedule.NgayLamViec);
        if (date >= monday && date <= sunday) {
            const dayIndex = (date.getDay() + 6) % 7; // Thứ 2 = 0, Chủ Nhật = 6
            const dayKey = days[dayIndex];
            const cell = document.getElementById(`schedule-${dayKey}`);

            if (cell) {
                const shift = document.createElement('div');
                shift.textContent = schedule.CaLamViec;

                // Đặt màu sắc cho từng ca làm việc
                if (schedule.CaLamViec.toLowerCase() === 'sáng') {
                    shift.className = 'badge bg-primary text-light'; // Màu xanh
                } else if (schedule.CaLamViec.toLowerCase() === 'chiều') {
                    shift.className = 'badge bg-warning text-dark'; // Màu vàng
                }

                cell.appendChild(shift);
                cell.appendChild(document.createElement('br')); // Ngắt dòng
            }
        }
    });
}

// Hàm thay đổi tuần
function changeWeek(direction) {
    currentMonday.setDate(currentMonday.getDate() + direction * 7);
    updateWeekRange(currentMonday);
}

// Thiết lập tuần hiện tại ban đầu
let currentMonday = getMonday(new Date());

// Sự kiện điều hướng tuần
document.getElementById('prevWeek').addEventListener('click', () => changeWeek(-1));
document.getElementById('nextWeek').addEventListener('click', () => changeWeek(1));
document.getElementById('currentWeek').addEventListener('click', () => {
    currentMonday = getMonday(new Date());
    updateWeekRange(currentMonday);
});

// Cập nhật giao diện ban đầu
updateWeekRange(currentMonday);

// Sự kiện in lịch làm việc
document.getElementById('print-schedule').addEventListener('click', () => window.print());

</script>