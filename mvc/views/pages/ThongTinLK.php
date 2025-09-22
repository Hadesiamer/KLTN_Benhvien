<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link href="./public/css/thongtinlk.css" rel="stylesheet">

<div class="col-12">
<?php if (isset($data["Message"]) && $data["Message"] != ""): ?>
    <div id="alert-message" 
         class="alert <?= $data["MessageType"] === 'error' ? 'alert-danger' : 'alert-success'; ?>" 
         role="alert">
        <?= $data["Message"]; ?>
    </div>
    <?php if ($data["MessageType"] === 'success'): ?>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                showBookingSuccessModal();
            });
        </script>
    <?php endif; ?>
<?php endif; ?>
</div>

<h3>Chọn thời gian đặt lịch khám</h3>
<div class="con">
    <form id="bookingForm" method="POST" action="/KLTN_Benhvien/DangKyLK">
        <input type="hidden" name="Action" value="CheckLich">

        <div class="date-container">
            <span class="arrow" id="prevDate">&#8592;</span> 
            <div class="date-scroll" id="dateScroll"></div>
            <span class="arrow" id="nextDate">&#8594;</span>
        </div>
        <hr>

        <div class="time-container">
            <div class="time-header" id="morningHeader">Sáng</div>
            <div class="time-slots" id="morningSlots"></div>
            <div class="time-header" id="afternoonHeader">Chiều</div>
            <div class="time-slots" id="afternoonSlots"></div>
        </div>
        
        <input type="hidden" name="NgayKham" id="NgayKham">
        <input type="hidden" name="GioKham" id="GioKham">
        <input type="hidden" name="Buoi" id="Buoi">
        <input type="hidden" name="MaBS" value="<?php echo $_SESSION["MaBS"]; ?>">

        <div class="button">
            <button type="submit" name="check" value="Đặt Lịch Khám" class="btn btn-primary">Đặt Lịch Khám</button>
        </div>
    </form>
</div>

<div class="modal fade" id="bookingSuccessModal" tabindex="-1" aria-labelledby="bookingSuccessModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="bookingSuccessModalLabel">Lịch Khám</h5>
      </div>
      <div class="modal-body">
        <p><strong>Ngày khám:</strong> 
            <?php echo isset($_POST["NgayKham"]) ? $_POST["NgayKham"] : "Chưa chọn ngày khám"; ?>
        </p>
        <p><strong>Giờ khám:</strong> 
            <?php echo isset($_POST["GioKham"]) ? $_POST["GioKham"] : "Chưa chọn giờ khám"; ?>
        </p>
      </div>
      <div class="modal-footer" >
        <button type="button" class="btn btn-change" id="closeModalButton" data-bs-dismiss="modal" >
          Đóng
        </button>
      </div>
    </div>
  </div>
</div>


<script>
    const currentDate = new Date();
    let selectedDate = new Date(); 

    function getMonday(date) {
        const day = date.getDay();
        const diff = (day === 0 ? -6 : 1) - day;
        const monday = new Date(date);
        monday.setDate(date.getDate() + diff);
        return monday;
    }

    function generateDates(startDate, numDays = 7) {
    const dates = [];
    for (let i = 0; i < numDays; i++) {
        const date = new Date(startDate);
        date.setDate(date.getDate() + i);

        if (date >= currentDate) {
            dates.push(date);
        }
    }
    return dates;
}


    function renderDates(dates) {
        const dateScroll = document.getElementById('dateScroll');
        dateScroll.innerHTML = '';
        dates.forEach(date => {
            const div = document.createElement('div');
            div.className = 'date-item';
            div.textContent = `${['CN', 'Th 2', 'Th 3', 'Th 4', 'Th 5', 'Th 6', 'Th 7'][date.getDay()]}, ${date.getDate()}-${date.getMonth() + 1}`;
            if (date.toDateString() === selectedDate.toDateString()) {
                div.classList.add('selected');
            }
            div.addEventListener('click', () => {
                selectedDate = date;
                renderDates(dates); 
                updateTimeSlots(); 
                document.getElementById('NgayKham').value = selectedDate.toLocaleDateString('en-CA').split('T')[0];
            });
            dateScroll.appendChild(div);
        });
    }

    function generateTimeSlots(startHour, endHour, isToday) {
    const slots = [];
    let currentTime = new Date();

    if (isToday) {
        const now = new Date();
        currentTime.setHours(now.getHours(), now.getMinutes(), 0, 0); 
        if (currentTime.getHours() < startHour) {
            currentTime.setHours(startHour, 0, 0, 0); 
        } else {
            currentTime.setMinutes(currentTime.getMinutes() + 20 - (currentTime.getMinutes() % 20));
        }
    } else {
        currentTime.setHours(startHour, 0, 0, 0); 
    }

    while (currentTime.getHours() < endHour) {
        const timeSlot = `${String(currentTime.getHours()).padStart(2, '0')}:${String(currentTime.getMinutes()).padStart(2, '0')}`;
        slots.push(timeSlot);
        currentTime.setMinutes(currentTime.getMinutes() + 20);
    }

    return slots;
}


    function renderTimeSlots(containerId, slots) {
        const container = document.getElementById(containerId);
        container.innerHTML = '';
        slots.forEach(slot => {
            const div = document.createElement('div');
            div.className = 'time-slot';
            div.textContent = slot;
            div.addEventListener('click', () => {
                document.querySelectorAll('.time-slot').forEach(slot => slot.classList.remove('selected'));
                div.classList.add('selected');
                document.getElementById('GioKham').value = slot;
                updateSession(slot);
            });
            container.appendChild(div);
        });
    }

    function updateTimeSlots() {
    const isToday = selectedDate.toDateString() === currentDate.toDateString();

    const morningSlots = generateTimeSlots(8, 13, isToday);
    const afternoonSlots = generateTimeSlots(13, 21, isToday);

    const morningHeader = document.getElementById('morningHeader');
    const morningContainer = document.getElementById('morningSlots');

    if (isToday && currentDate.getHours() >= 12) {
        morningHeader.style.display = 'none';
        morningContainer.style.display = 'none';
    } else {
        morningHeader.style.display = 'block';
        morningContainer.style.display = 'flex';
        renderTimeSlots('morningSlots', morningSlots);
    }

    renderTimeSlots('afternoonSlots', afternoonSlots);
}


    function updateSession(time) {
        const hour = parseInt(time.split(':')[0]);
        if (hour < 12) {
            document.getElementById('Buoi').value = 'Sáng';
        } else {
            document.getElementById('Buoi').value = 'Chiều';
        }
    }

    document.getElementById('prevDate').addEventListener('click', () => {
        const startDate = getMonday(selectedDate);
        startDate.setDate(startDate.getDate() - 7);
        renderDates(generateDates(startDate));
    });

    document.getElementById('nextDate').addEventListener('click', () => {
        const startDate = getMonday(selectedDate);
        startDate.setDate(startDate.getDate() + 7);
        renderDates(generateDates(startDate));
    });

    function checkForm(event) {
        const ngayKham = document.getElementById('NgayKham').value;
        const gioKham = document.getElementById('GioKham').value;
        const buoi = document.getElementById('Buoi').value;
    }

    document.getElementById('bookingForm').addEventListener('submit', checkForm);
    const startDate = getMonday(currentDate);
    renderDates(generateDates(startDate));
    updateTimeSlots();

    document.addEventListener("DOMContentLoaded", function () {
        const alertMessage = document.getElementById("alert-message");
        if (alertMessage) {
            setTimeout(function () {
                alertMessage.style.display = "none";
            }, 1000);
        }
    });

    function showBookingSuccessModal() {
        const modal = new bootstrap.Modal(document.getElementById('bookingSuccessModal'));
        modal.show();
    }

    document.getElementById("closeModalButton").addEventListener("click", function () {
      window.location.href = "/KLTN_Benhvien/"; 
  });
</script>
