<div class="container">
    <h2 class="mb-4">Đăng ký lịch làm việc</h2>
    <?php if (isset($data['Message'])): ?>
        <?php if (!empty($data['Message']['success'])): ?>
            <div class="alert alert-success">
                <ul>
                    <?php ($data['Message']['success']) ?>
                        <li>Đăng ký lịch làm việc thành công</li>
                    <?php ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (!empty($data['Message']['failed'])): ?>
            <div class="alert alert-warning">
                <h5>Cảnh báo:</h5>
                <ul>
                    <?php foreach ($data['Message']['failed'] as $msg): ?>
                        <li><?= $msg ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    <?php endif; ?>
    <!-- Week Navigation -->
    <div class="d-flex justify-content-between mb-4">
        <button id="prevWeek" class="btn btn-secondary">Tuần trước</button>
        <button id="currentWeek" class="btn btn-primary">Hiện tại</button>
        <button id="nextWeek" class="btn btn-secondary">Tuần sau</button>
    </div>

    <!-- Week Range Display -->
    <div id="weekRange" class="text-center fw-bold mb-4"></div>

    <!-- Work Schedule Form -->
    <form id="workScheduleForm" method="POST">
        <input type="hidden" id="selectedDateRange" name="dateRange">

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
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox"
                                    name="schedule[<?= $day ?>][]"
                                    value="Sáng"
                                    id="checkbox-<?= $day ?>-sang">
                                <label class="form-check-label">Ca sáng</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox"
                                    name="schedule[<?= $day ?>][]"
                                    value="Chiều"
                                    id="checkbox-<?= $day ?>-chieu">
                                <label class="form-check-label">Ca chiều</label>
                            </div>
                        </td>
                    <?php endforeach; ?>
                </tr>
            </tbody>
        </table>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary mt-3">Lưu lịch làm việc</button>
    </form>
</div>

<script>
    document.getElementById('workScheduleForm').addEventListener('submit', function(e) {
        const checkboxes = document.querySelectorAll('.form-check-input:checked');
        if (checkboxes.length === 0) {
            e.preventDefault();
            alert('Vui lòng chọn ít nhất một ngày làm việc và ca làm việc.');
        }
    });

    function getMonday(date) {
        const d = new Date(date);
        const day = d.getDay();
        const diff = d.getDate() - day + (day === 0 ? -6 : 1);
        return new Date(d.setDate(diff));
    }

    function formatDate(date) {
        return `${date.getDate().toString().padStart(2, '0')}/${(date.getMonth() + 1).toString().padStart(2, '0')}/${date.getFullYear()}`;
    }

    function updateWeekRange(monday) {
        const sunday = new Date(monday);
        sunday.setDate(monday.getDate() + 6);
        const today = new Date();
        const maxNextWeek = getMonday(new Date(today.getFullYear(), today.getMonth(), today.getDate() + 7));

        // Cập nhật tuần hiển thị
        document.getElementById('weekRange').textContent = `${formatDate(monday)} - ${formatDate(sunday)}`;
        document.getElementById('selectedDateRange').value = `${formatDate(monday)} - ${formatDate(sunday)}`;

        // Cập nhật trạng thái các ngày và checkbox
        const days = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];
        days.forEach((day, index) => {
            const date = new Date(monday);
            date.setDate(date.getDate() + index);

            // Cập nhật nhãn ngày
            document.getElementById(`date-${day}`).textContent = formatDate(date);

            // Điều chỉnh trạng thái checkbox
            const isPastOrToday = date <= today;
            const checkboxes = document.querySelectorAll(`#checkbox-${day}-sang, #checkbox-${day}-chieu`);
            checkboxes.forEach(checkbox => {
                checkbox.disabled = isPastOrToday;
                checkbox.parentElement.style.color = isPastOrToday ? '#aaa' : '';
            });
        });

        // Kiểm tra trạng thái nút "Tuần sau"
        document.getElementById('nextWeek').disabled = monday >= maxNextWeek;
    }

    function changeWeek(direction) {
        currentMonday.setDate(currentMonday.getDate() + direction * 7);
        updateWeekRange(currentMonday);
    }

    let currentMonday = getMonday(new Date());

    // Các nút điều hướng
    document.getElementById('prevWeek').addEventListener('click', () => changeWeek(-1));
    document.getElementById('nextWeek').addEventListener('click', () => changeWeek(1));
    document.getElementById('currentWeek').addEventListener('click', () => {
        currentMonday = getMonday(new Date());
        updateWeekRange(currentMonday);
        document.getElementById('nextWeek').disabled = false;
    });

    // Cập nhật giao diện ban đầu
    updateWeekRange(currentMonday);
</script>