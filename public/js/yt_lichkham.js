// ================== DOM CƠ BẢN ==================
const chuyenKhoaSel = document.getElementById("chuyenKhoa");
const dichvuButtonsContainer = document.getElementById("DichVuSelection");
const dichvuHiddenInput = document.getElementById("MaDV_Hidden");
const datLichSel = document.getElementById("datLich");
const bacsiSel = document.getElementById("MaBS");
const ngayKhamInput = document.getElementById("NgayKham");
const ngayKhamHidden = document.getElementById("NgayKhamHidden");
const gioKhamHidden = document.getElementById("GioKhamHidden");
const errorMsgDiv = document.getElementById("lich-error-msg");
const btnSubmit = document.getElementById("btnSubmitBooking");
const trieuChungInput = document.getElementById("TrieuChungKH");

const bookingStep1 = document.getElementById("booking-step-1");
const bookingStep2 = document.getElementById("booking-step-2");

const selectedPatientBox = document.getElementById("selected-patient-box");
const selectedMaBNInput = document.getElementById("SelectedMaBN");

// Modal chọn giờ
const panel = document.getElementById("GioKhamSelectionPanel");
const modalDateDisplay = document.getElementById("modal-date-display");
const gioKhamContainer = document.getElementById("GioKhamContainer");
const tabButtons = panel.querySelectorAll(".tab-button");
const closeTimeBtn = document.querySelector(".close-time-modal");

// Biến trạng thái
let finalBookingData_NVYT = {};
let isTrongGioMode = false; // Có thể dùng nếu cần giống logic "khám trong giờ"

// Endpoint API đặt lịch
const BOOKING_API_URL_NVYT = "/KLTN_Benhvien/mvc/views/pages/YT_QuanLyLichKham.php?api=1";

// ================== HÀM TIỆN ÍCH ==================
function openModal(modal) {
    modal.style.display = "flex";
    document.body.style.overflow = "hidden";
}

function closeModal(modal) {
    modal.style.display = "none";
    document.body.style.overflow = "auto";
}

if (closeTimeBtn) {
    closeTimeBtn.addEventListener("click", () => closeModal(panel));
}

function displayMessage(message = "", element = errorMsgDiv) {
    if (element) element.textContent = message;
}

// ================== FLATPICKR ==================
let fp_NVYT = flatpickr("#NgayKham", {
    locale: "vn",
    dateFormat: "Y-m-d",
    minDate: "today",
    clickOpens: false,
    enable: [],
    onChange: function (selectedDates, dateStr) {
        gioKhamHidden.value = "";

        if (dateStr) {
            ngayKhamHidden.value = dateStr;
            ngayKhamInput.value = dateStr + " - Chưa chọn giờ";

            const isTheoGioKham =
                datLichSel.value === "theoGioKham" && !isTrongGioMode;

            if (loadAvailableTimeSlots_NVYT(dateStr, isTheoGioKham)) {
                modalDateDisplay.textContent = `Ngày đã chọn: ${dateStr}`;
                openModal(panel);
            } else {
                ngayKhamInput.value = dateStr + " - Hết giờ trống";
                closeModal(panel);
                if (isTheoGioKham) bacsiSel.value = "";
            }
        } else {
            ngayKhamHidden.value = "";
            ngayKhamInput.value = "Chọn ngày khám";
            clearTimeSlots_NVYT();
            closeModal(panel);
            if (datLichSel.value === "theoGioKham" && !isTrongGioMode)
                bacsiSel.value = "";
        }
        validateAndToggleSubmit_NVYT();
    }
});

function forceOpenFlatpickr_NVYT() {
    fp_NVYT.open();
}

function updateFlatpickr_NVYT(enabledDates = [], openStatus = false, minDate = "today") {
    fp_NVYT.set("enable", enabledDates);
    fp_NVYT.set("minDate", minDate);

    if (openStatus) {
        ngayKhamInput.removeAttribute("readonly");
        ngayKhamInput.addEventListener("click", forceOpenFlatpickr_NVYT);
    } else {
        ngayKhamInput.setAttribute("readonly", true);
        ngayKhamInput.removeEventListener("click", forceOpenFlatpickr_NVYT);
    }
}

// ================== KHUNG GIỜ ==================
function updateTimeSlotTabs_NVYT(hasMorning, hasAfternoon) {
    const tabSang = document.querySelector('.tab-button[data-time-group="sang"]');
    const tabChieu = document.querySelector('.tab-button[data-time-group="chieu"]');
    if (!tabSang || !tabChieu) return;
    tabSang.classList.remove("disabled-tab", "active");
    tabChieu.classList.remove("disabled-tab", "active");
    if (!hasMorning) tabSang.classList.add("disabled-tab");
    if (!hasAfternoon) tabChieu.classList.add("disabled-tab");
}

function clearTimeSlots_NVYT() {
    gioKhamContainer.innerHTML = "";
    gioKhamHidden.value = "";
    updateTimeSlotTabs_NVYT(true, true);
    const tabSang = document.querySelector('.tab-button[data-time-group="sang"]');
    if (tabSang) tabSang.classList.add("active");
}

tabButtons.forEach((button) => {
    button.addEventListener("click", function () {
        if (!this.classList.contains("disabled-tab")) {
            switchTab_NVYT(this.dataset.timeGroup);
        }
    });
});

function switchTab_NVYT(groupName) {
    const allSlots = gioKhamContainer.querySelectorAll("span.time-slot");
    let hasAvailableSlots = false;
    tabButtons.forEach((button) => {
        button.classList.remove("active");
        if (button.dataset.timeGroup === groupName) button.classList.add("active");
    });
    allSlots.forEach((slot) => {
        if (slot.dataset.group === groupName) {
            slot.style.display = "inline-block";
            hasAvailableSlots = true;
        } else {
            slot.style.display = "none";
        }
    });
    const tempMsg = document.querySelector("#GioKhamContainer p.no-slot-msg");
    if (tempMsg) tempMsg.remove();
    if (!hasAvailableSlots) {
        const p = document.createElement("p");
        p.className = "no-slot-msg";
        p.style.cssText =
            "color:#666; text-align:center; padding:10px 0; width: 100%;";
        p.textContent = "Không có khung giờ trống trong buổi này.";
        gioKhamContainer.appendChild(p);
    }
}

function handleTimeSlotClick_NVYT(event) {
    gioKhamContainer.querySelectorAll(".time-slot").forEach((slot) => {
        slot.classList.remove("selected");
    });
    event.target.classList.add("selected");

    const selectedTime = event.target.dataset.time;
    const maBsList = event.target.dataset.maBsList; // string "1,2,3"

    gioKhamHidden.value = selectedTime;

    // Chế độ theo giờ khám: tự chọn BS đầu tiên trong list
    if (datLichSel.value === "theoGioKham" && !isTrongGioMode) {
        const firstMaBS = maBsList.split(",")[0];
        bacsiSel.value = firstMaBS;
    }

    const selectedDate = ngayKhamHidden.value;
    const period = selectedTime < "12:00" ? "Sáng" : "Chiều";
    ngayKhamInput.value = `${selectedDate} (${selectedTime} - ${period})`;

    validateAndToggleSubmit_NVYT();
    closeModal(panel);
}

// --------- Tính khung giờ trống ----------
function loadAvailableTimeSlots_NVYT(dateStr, isTheoGioKham = false) {
    const maCK = chuyenKhoaSel.value;
    clearTimeSlots_NVYT();

    const currentDate = new Date();
    const todayStr = currentDate.toISOString().split("T")[0];
    const currentTimeStr = currentDate.toTimeString().substring(0, 5);

    let availableSlotsByTime = {};

    if (isTheoGioKham) {
        // Mode: theo giờ khám – tổng hợp nhiều bác sĩ trong khoa
        bacsiSel.value = "";
        displayMessage("", errorMsgDiv);

        const doctorsInKhoa = bacsiData_NVYT
            .filter((b) => String(b.MaKhoa) === String(maCK))
            .map((b) => String(b.MaNV));

        doctorsInKhoa.forEach((maBS) => {
            const caLamViec =
                lichLamViecData_NVYT[maBS] &&
                lichLamViecData_NVYT[maBS][dateStr];
            if (!caLamViec) return;

            const bookedSlotsOnDate =
                (bookedSlotsData_NVYT[maBS] &&
                    bookedSlotsData_NVYT[maBS][dateStr]) ||
                [];

            let slotsFilteredByCa = defaultTimeSlots_NVYT.filter((slot) => {
                const isMorning = slot < "12:00";
                const isAfternoon = slot >= "12:00";
                let isValidByCa =
                    (caLamViec === "Sáng" && isMorning) ||
                    (caLamViec === "Chiều" && isAfternoon) ||
                    caLamViec === "Cả ngày";
                return isValidByCa;
            });

            let availableSlotsForBS = slotsFilteredByCa.filter(
                (slot) =>
                    !bookedSlotsOnDate.includes(slot) &&
                    (dateStr !== todayStr || slot > currentTimeStr)
            );

            availableSlotsForBS.forEach((slot) => {
                if (!availableSlotsByTime[slot]) {
                    availableSlotsByTime[slot] = [];
                }
                availableSlotsByTime[slot].push(maBS);
            });
        });
    } else {
        // Mode: theo bác sĩ
        const maBS = String(bacsiSel.value);
        if (!maBS) {
            displayMessage(
                "⚠️ Vui lòng chọn bác sĩ trước khi chọn ngày khám.",
                errorMsgDiv
            );
            return false;
        }

        const caLamViec =
            lichLamViecData_NVYT[maBS] &&
            lichLamViecData_NVYT[maBS][dateStr];
        if (!caLamViec) {
            displayMessage(
                "⚠️ Bác sĩ không có lịch làm việc trong ngày này.",
                errorMsgDiv
            );
            return false;
        }
        displayMessage("", errorMsgDiv);

        const bookedSlotsOnDate =
            (bookedSlotsData_NVYT[maBS] &&
                bookedSlotsData_NVYT[maBS][dateStr]) ||
            [];

        let slotsFilteredByCa = defaultTimeSlots_NVYT.filter((slot) => {
            const isMorning = slot < "12:00";
            const isAfternoon = slot >= "12:00";
            return (
                (caLamViec === "Sáng" && isMorning) ||
                (caLamViec === "Chiều" && isAfternoon) ||
                caLamViec === "Cả ngày"
            );
        });

        let availableSlots = slotsFilteredByCa.filter(
            (slot) => !bookedSlotsOnDate.includes(slot)
        );
        if (dateStr === todayStr) {
            availableSlots = availableSlots.filter(
                (slot) => slot > currentTimeStr
            );
        }

        availableSlots.forEach((slot) => {
            availableSlotsByTime[slot] = [maBS];
        });
    }

    const availableTimes = Object.keys(availableSlotsByTime).sort();

    if (availableTimes.length > 0) {
        let hasMorning = false;
        let hasAfternoon = false;

        availableTimes.forEach((slot) => {
            const group = slot < "12:00" ? "sang" : "chieu";
            if (group === "sang") hasMorning = true;
            if (group === "chieu") hasAfternoon = true;

            const numAvailable = availableSlotsByTime[slot].length;
            const slotSpan = document.createElement("span");
            slotSpan.textContent =
                slot + (numAvailable > 1 ? ` (+${numAvailable - 1} BS)` : "");

            slotSpan.classList.add("time-slot");
            slotSpan.dataset.time = slot;
            slotSpan.dataset.group = group;
            slotSpan.dataset.maBsList = availableSlotsByTime[slot]
                .map(String)
                .join(",");

            slotSpan.addEventListener("click", handleTimeSlotClick_NVYT);
            gioKhamContainer.appendChild(slotSpan);
        });

        updateTimeSlotTabs_NVYT(hasMorning, hasAfternoon);
        let initialGroup = hasMorning ? "sang" : hasAfternoon ? "chieu" : "";
        if (initialGroup) {
            switchTab_NVYT(initialGroup);
            return true;
        }
    }

    return false;
}

// ================== FILTER BÁC SĨ ==================
function filterDoctorsWithFutureSchedule_NVYT(doctors) {
    const todayStr = new Date().toISOString().split("T")[0];

    return doctors.filter((b) => {
        const maBS = String(b.MaNV);
        const lich = lichLamViecData_NVYT[maBS];
        if (!lich) return false;
        for (const date in lich) {
            if (String(date) >= todayStr) return true;
        }
        return false;
    });
}

// ================== VALIDATE & NÚT TIẾP TỤC ==================
function validateAndToggleSubmit_NVYT() {
    const maBN = selectedMaBNInput.value;
    const maBS = bacsiSel.value;
    const ngayKham = ngayKhamHidden.value;
    const gioKham = gioKhamHidden.value;
    const maCK = chuyenKhoaSel.value;
    const maDV = dichvuHiddenInput.value;

    if (maBN && maBS && ngayKham && gioKham && maCK && maDV) {
        btnSubmit.disabled = false;
        btnSubmit.style.backgroundColor = "#007bff";
        btnSubmit.style.color = "white";
    } else {
        btnSubmit.disabled = true;
        btnSubmit.style.backgroundColor = "#ccc";
        btnSubmit.style.color = "#666";
    }
}

// ================== CHUYỂN BƯỚC ==================
function goToNextStep_NVYT() {
    finalBookingData_NVYT = {
        MaBN: selectedMaBNInput.value,
        MaBS: bacsiSel.value,
        MaKhoa: chuyenKhoaSel.value,
        MaDV: dichvuHiddenInput.value,
        NgayKham: ngayKhamHidden.value,
        GioKham: gioKhamHidden.value
    };

    if (!finalBookingData_NVYT.MaBN) {
        alert("Vui lòng chọn bệnh nhân trước khi tiếp tục.");
        return;
    }

    trieuChungInput.value = "";
    bookingStep1.style.display = "none";
    bookingStep2.style.display = "block";
    window.scrollTo({
        top: bookingStep2.offsetTop - 20,
        behavior: "smooth"
    });
}

function goToPreviousStep_NVYT() {
    bookingStep2.style.display = "none";
    bookingStep1.style.display = "block";
    window.scrollTo({
        top: bookingStep1.offsetTop - 20,
        behavior: "smooth"
    });
}

// ================== ĐIỀN DANH SÁCH BÁC SĨ ==================
function populateBacsiSelect_NVYT(doctors, addDefaultOption = true) {
    bacsiSel.innerHTML = "";
    if (addDefaultOption) {
        bacsiSel.innerHTML = "<option value=''>Chọn bác sĩ</option>";
    }
    doctors.forEach((b) => {
        const opt = document.createElement("option");
        opt.value = b.MaNV;
        opt.textContent = b.HovaTen;
        bacsiSel.appendChild(opt);
    });
}

// ================== TOGGLE THEO CHẾ ĐỘ ĐẶT LỊCH ==================
function toggleBacsi_NVYT() {
    const datLich = datLichSel.value;
    const maCK = chuyenKhoaSel.value;

    ngayKhamInput.value = "Chọn ngày khám";
    ngayKhamHidden.value = "";
    gioKhamHidden.value = "";
    displayMessage();
    clearTimeSlots_NVYT();
    updateFlatpickr_NVYT([], false);

    if (!maCK) {
        bacsiSel.innerHTML = "<option value=''>Chọn bác sĩ</option>";
        validateAndToggleSubmit_NVYT();
        return;
    }

    // Lọc bác sĩ theo khoa
    const doctorsByKhoa = bacsiData_NVYT.filter(
        (b) => String(b.MaKhoa) === String(maCK)
    );

    if (datLich === "theoBacSi") {
        const futureDoctors = filterDoctorsWithFutureSchedule_NVYT(doctorsByKhoa);
        populateBacsiSelect_NVYT(futureDoctors, true);
        displayMessage("ℹ️ Chọn bác sĩ để xem lịch khám.", errorMsgDiv);
    } else if (datLich === "theoGioKham") {
        // Không cho NVYT chọn BS thủ công, BS sẽ được chọn theo giờ
        bacsiSel.value = "";
        bacsiSel.innerHTML = "<option value=''>Tự động chọn theo giờ</option>";

        // union các ngày có lịch của BS trong khoa
        const todayStr = new Date().toISOString().split("T")[0];
        const futureDoctors = filterDoctorsWithFutureSchedule_NVYT(doctorsByKhoa);
        const futureDoctorIDs = futureDoctors.map((b) => String(b.MaNV));

        const allDates = futureDoctorIDs
            .flatMap((maBS) => Object.keys(lichLamViecData_NVYT[maBS] || {}))
            .filter(
                (date, index, self) =>
                    self.indexOf(date) === index && date >= todayStr
            );

        if (allDates.length > 0) {
            updateFlatpickr_NVYT(allDates, true, "today");
            displayMessage(
                "ℹ️ Chọn ngày để xem giờ trống của toàn bộ bác sĩ trong chuyên khoa.",
                errorMsgDiv
            );
        } else {
            updateFlatpickr_NVYT([], false);
            displayMessage(
                "⚠️ Không có bác sĩ nào có lịch làm việc trong tương lai.",
                errorMsgDiv
            );
        }
    }

    validateAndToggleSubmit_NVYT();
}

// ================== HANDLE SỰ KIỆN ==================

// click chọn bệnh nhân
document.querySelectorAll(".btn-select-patient").forEach((btn) => {
    btn.addEventListener("click", function () {
        const maBN = this.dataset.mabn;
        const hoten = this.dataset.hoten;
        const sdt = this.dataset.sdt;

        selectedMaBNInput.value = maBN;
        selectedPatientBox.innerHTML =
            `<div><strong>${hoten}</strong> (Mã BN: ${maBN})</div>` +
            `<div class="small text-muted">SĐT: ${sdt}</div>`;

        validateAndToggleSubmit_NVYT();
    });
});

if (dichvuButtonsContainer) {
    dichvuButtonsContainer.querySelectorAll(".service-button").forEach((btn) => {
        btn.addEventListener("click", function () {
            dichvuButtonsContainer
                .querySelectorAll(".service-button")
                .forEach((b) => b.classList.remove("selected"));

            this.classList.add("selected");
            const maDV = this.dataset.dvId;
            dichvuHiddenInput.value = maDV;

            datLichSel.disabled = maDV === "";
            if (datLichSel.value === "" && maDV !== "") {
                datLichSel.value = "theoBacSi";
            }
            toggleBacsi_NVYT();
        });
    });
}

chuyenKhoaSel.addEventListener("change", function () {
    datLichSel.disabled = (dichvuHiddenInput.value === "");
    toggleBacsi_NVYT();
});

// Khi chọn bác sĩ -> bật các ngày mà bác sĩ đó có lịch làm việc
bacsiSel.addEventListener("change", function () {
    const maBS = String(this.value);

    // Reset ngày & giờ mỗi khi đổi bác sĩ
    ngayKhamInput.value = "Chọn ngày khám";
    ngayKhamHidden.value = "";
    gioKhamHidden.value = "";
    clearTimeSlots_NVYT();

    // Nếu chưa chọn bác sĩ
    if (!maBS) {
        updateFlatpickr_NVYT([], false);
        displayMessage("⚠️ Vui lòng chọn bác sĩ.", errorMsgDiv);
        validateAndToggleSubmit_NVYT();
        return;
    }

    // Lấy danh sách ngày làm việc của bác sĩ
    const lichBS = lichLamViecData_NVYT[maBS] || {};
    const allDates = Object.keys(lichBS);
    const todayStr = new Date().toISOString().split("T")[0];

    // Chỉ cho phép chọn những ngày từ hôm nay trở về sau
    const futureDates = allDates.filter(d => d >= todayStr);

    if (futureDates.length > 0) {
        // Bật Flatpickr + cho phép click input Ngày khám
        updateFlatpickr_NVYT(futureDates, true, "today");
        displayMessage("ℹ️ Chọn ngày khám trong các ngày bác sĩ có lịch làm việc.", errorMsgDiv);
    } else {
        // Không có ngày nào -> khóa lịch
        updateFlatpickr_NVYT([], false);
        displayMessage("⚠️ Bác sĩ này chưa có lịch làm việc trong tương lai.", errorMsgDiv);
    }

    validateAndToggleSubmit_NVYT();
});


datLichSel.addEventListener("change", function () {
    const val = this.value;
    if (!chuyenKhoaSel.value) {
        alert("Vui lòng chọn chuyên khoa trước.");
        this.value = "theoBacSi";
        return;
    }
    toggleBacsi_NVYT();
});

// nút tiếp tục
btnSubmit.addEventListener("click", goToNextStep_NVYT);

// ================== HOÀN TẤT BƯỚC 2 – GỌI API ==================
async function handleBookingFinalStep_NVYT() {
    const trieuChung = trieuChungInput.value.trim();
    if (!trieuChung) {
        alert("Vui lòng mô tả triệu chứng để hoàn tất đăng ký.");
        return;
    }

    finalBookingData_NVYT.TrieuChung = trieuChung;

    if (!finalBookingData_NVYT.MaBN) {
        alert("Chưa chọn bệnh nhân.");
        return;
    }

    document.getElementById("btnCompleteBooking_NVYT").disabled = true;

    try {
        const response = await fetch(BOOKING_API_URL_NVYT, {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(finalBookingData_NVYT)
        });

        const result = await response.json();
        if (response.ok && result.success) {
            alert("Đăng ký lịch khám thành công (ĐÃ THANH TOÁN).");
            resetAllSteps_NVYT();
        } else {
            alert(
                "Lỗi đăng ký lịch khám:\n" +
                (result.message || "Có lỗi xảy ra, vui lòng thử lại.")
            );
        }
    } catch (err) {
        console.error(err);
        alert("Không thể kết nối máy chủ. Vui lòng kiểm tra lại.");
    } finally {
        document.getElementById("btnCompleteBooking_NVYT").disabled = false;
    }
}

function resetAllSteps_NVYT() {
    // Reset form
    document.getElementById("form-step-1").reset();
    document.getElementById("form-step-2").reset();
    selectedMaBNInput.value = "";
    selectedPatientBox.innerHTML = "Chưa chọn bệnh nhân.";

    // Reset UI
    bookingStep2.style.display = "none";
    bookingStep1.style.display = "block";
    clearTimeSlots_NVYT();
    updateFlatpickr_NVYT([], false);
    displayMessage("", errorMsgDiv);

    // Reset chọn dịch vụ
    if (dichvuButtonsContainer) {
        dichvuButtonsContainer
            .querySelectorAll(".service-button")
            .forEach((b) => b.classList.remove("selected"));
    }
    dichvuHiddenInput.value = "";

    datLichSel.value = "theoBacSi";
    datLichSel.disabled = true;
    populateBacsiSelect_NVYT([], true);

    validateAndToggleSubmit_NVYT();
}

// ================== INIT ==================
document.addEventListener("DOMContentLoaded", () => {
    datLichSel.disabled = true;
    updateFlatpickr_NVYT([], false);
    validateAndToggleSubmit_NVYT();
});
