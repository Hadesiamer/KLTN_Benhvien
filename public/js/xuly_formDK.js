// Biến DOM    
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
const trieuChungInput = document.getElementById('TrieuChungKH');

// DOM MODAL CHỌN GIỜ
const panel = document.getElementById("GioKhamSelectionPanel"); 
const modalDateDisplay = document.getElementById("modal-date-display");
const gioKhamContainer = document.getElementById("GioKhamContainer"); 
const tabButtons = panel.querySelectorAll('.tab-button');
const closeTimeBtn = document.querySelector('.close-time-modal');

// DOM MODAL XỬ LÝ ĐĂNG NHẬP/ĐĂNG KÝ
const authModal = document.getElementById("AuthDecisionModal");
const closeAuthBtn = document.querySelector('.close-auth-modal');
const btnContinueAsGuest = document.getElementById('btnContinueAsGuest');

// THÊM BIẾN DOM QUAN TRỌNG
const bacsiGroup = document.getElementById("bacsi-group");

// >>> THÊM BIẾN DOM MỚI CHO HIỂN THỊ BÁC SĨ TỰ ĐỘNG CHỌN <<<
const doctorDisplayBox = document.getElementById("doctor-selection-display");
const selectedDoctorInfo = document.getElementById("selected-doctor-info");

// ********** BIẾN DOM QUAN TRỌNG CHO LOGIC ĐĂNG NHẬP (ĐÃ THÊM) **********
const mainBookingArea = document.getElementById("booking-step-1");
const bookingStep2 = document.getElementById("booking-step-2");
const lichErrorMsgContainer = document.getElementById("lich-error-msg");
// ************************************************************************

// Biến tạm lưu dữ liệu
window.finalBookingData = {};
window.isTrongGioMode = false;

// Giả định file process_booking.php nằm cùng cấp hoặc có thể truy cập trực tiếp
const BOOKING_API_URL = "mvc/views/pages/formDK_KhamBenh.php";

// ********** CHỨC NĂNG MODAL CHUNG **********
function openModal(modal) { 
    modal.style.display = "flex"; 
    document.body.style.overflow = 'hidden'; 
}

function closeModal(modal) { 
    modal.style.display = "none"; 
    document.body.style.overflow = 'auto'; 
}

if (closeTimeBtn) {
    closeTimeBtn.addEventListener('click', () => closeModal(panel));
}
if (closeAuthBtn) {
    closeAuthBtn.addEventListener('click', () => closeModal(authModal));
}

// ********** HÀM XỬ LÝ LỊCH VÀ BƯỚC **********
let fp = flatpickr("#NgayKham", {
    locale: "vn",
    dateFormat: "Y-m-d",
    minDate: "today",
    clickOpens: false, 
    enable: [],
    onChange: function(selectedDates, dateStr, instance) {
        // ********* THÊM KIỂM TRA ĐĂNG NHẬP NGAY ĐẦU HÀM *********
        // Nếu form đang bị "chặn" (dù không bị làm mờ) và chưa đăng nhập, không cho xử lý logic
        if (!isLoggedIn && mainBookingArea.dataset.isBlocked === 'true') return; 
        // **********************************************************
        
        gioKhamHidden.value = "";
        
        // --- THÊM LOGIC ẨN HỘP HIỂN THỊ BÁC SĨ KHI NGÀY KHÁM THAY ĐỔI ---
        if (doctorDisplayBox) doctorDisplayBox.style.display = "none";
        
        if (dateStr) {
            ngayKhamHidden.value = dateStr;
            ngayKhamInput.value = dateStr + " - Chưa chọn giờ"; 
            
            if (window.isTrongGioMode && dateStr !== new Date().toISOString().split('T')[0]) {
                 displayMessage("⚠️ Chế độ 'Khám trong giờ' chỉ áp dụng cho ngày hôm nay.", errorMsgDiv);
                 ngayKhamHidden.value = "";
                 ngayKhamInput.value = "Chọn ngày khám";
                 validateAndToggleSubmit();
                 return;
            }

            const isTheoGioKham = datLichSel.value === "theoGioKham" && !window.isTrongGioMode;

            if (loadAvailableTimeSlots(dateStr, isTheoGioKham)) { 
                modalDateDisplay.textContent = `Ngày đã chọn: ${dateStr}`;
                openModal(panel);
            } else {
                 ngayKhamInput.value = dateStr + " - Hết giờ trống"; 
                 closeModal(panel); 
                 if(isTheoGioKham) bacsiSel.value = "";
            }
        } else {
            ngayKhamHidden.value = "";
            ngayKhamInput.value = "Chọn ngày khám";
            clearTimeSlots(); 
            closeModal(panel); 
            if(datLichSel.value === "theoGioKham" && !window.isTrongGioMode) bacsiSel.value = "";
        }
        validateAndToggleSubmit();
    }
});

function displayMessage(message = '', element = errorMsgDiv) { 
    if (element) element.textContent = message; 
}

//  BẮT BUỘC mở lịch khi click vào input 
function forceOpenFlatpickr() {
    // Kiểm tra nếu đang ở trạng thái bị chặn (chưa đăng nhập) thì không mở
    if (mainBookingArea.dataset.isBlocked === 'true') return;

    if (!ngayKhamInput.hasAttribute('readonly')) {
        fp.open();
    }
}

// Hàm tối ưu hóa Flatpickr
function updateFlatpickr(enabledDates = [], openStatus = false, minDate = "today") {
    fp.set('enable', enabledDates);
    fp.set('minDate', minDate);
    
    if (openStatus) {
        ngayKhamInput.removeAttribute("readonly");
        // Thêm trình lắng nghe bắt buộc mở lịch
        ngayKhamInput.addEventListener('click', forceOpenFlatpickr);
    } else {
        ngayKhamInput.setAttribute("readonly", true);
        // Loại bỏ trình lắng nghe khi vô hiệu hóa
        ngayKhamInput.removeEventListener('click', forceOpenFlatpickr);
    }
}

function updateTimeSlotTabs(hasMorning, hasAfternoon) {
    const tabSang = document.querySelector('.tab-button[data-time-group="sang"]');
    const tabChieu = document.querySelector('.tab-button[data-time-group="chieu"]');
    if (!tabSang || !tabChieu) return;
    tabSang.classList.remove('disabled-tab', 'active');
    tabChieu.classList.remove('disabled-tab', 'active');
    if (!hasMorning) { tabSang.classList.add('disabled-tab'); }
    if (!hasAfternoon) { tabChieu.classList.add('disabled-tab'); }
}
function clearTimeSlots() {
    gioKhamContainer.innerHTML = '';
    gioKhamHidden.value = "";
    updateTimeSlotTabs(true, true); 
    const tabSang = document.querySelector('.tab-button[data-time-group="sang"]');
    if (tabSang) tabSang.classList.add('active');
}
tabButtons.forEach(button => {
    button.addEventListener('click', function() {
        if (!this.classList.contains('disabled-tab')) switchTab(this.dataset.timeGroup);
    });
});
function switchTab(groupName) {
    const allSlots = gioKhamContainer.querySelectorAll('span.time-slot'); 
    let hasAvailableSlots = false;
    tabButtons.forEach(button => { 
        button.classList.remove('active'); 
        if (button.dataset.timeGroup === groupName) button.classList.add('active'); 
    });
    allSlots.forEach(slot => {
        if (slot.dataset.group === groupName) { 
            slot.style.display = 'inline-block'; 
            hasAvailableSlots = true; 
        } else { 
            slot.style.display = 'none'; 
        }
    });
    const tempMsg = document.querySelector('#GioKhamContainer p.no-slot-msg'); 
    if (tempMsg) tempMsg.remove();
    if (!hasAvailableSlots) {
        const p = document.createElement('p'); 
        p.className = 'no-slot-msg';
        p.style.cssText = 'color:#666; text-align:center; padding:10px 0; width: 100%;';
        p.textContent = 'Không có khung giờ trống trong buổi này.'; 
        gioKhamContainer.appendChild(p);
    }
}

// Xử lý chọn giờ và tự động chọn Bác sĩ (ĐÃ CẬP NHẬT CHO TÍNH NĂNG MỚI)
function handleTimeSlotClick(event) {
    gioKhamContainer.querySelectorAll('.time-slot').forEach(slot => { 
        slot.classList.remove('selected'); 
    });
    event.target.classList.add('selected');
    const selectedTime = event.target.dataset.time;
    const maBsList = event.target.dataset.maBsList; 

    gioKhamHidden.value = selectedTime;
    
    if (datLichSel.value === "theoGioKham" && !window.isTrongGioMode) {
        const firstMaBS = maBsList.split(',')[0]; 
        bacsiSel.value = firstMaBS;
        
        // Đảm bảo tìm kiếm đúng Bác sĩ bằng cách ép kiểu sang String
        const bsName = bacsiData.find(b => String(b.MaNV) === firstMaBS)?.HovaTen || 'Bác sĩ đã chọn';
        
        // --- LOGIC HIỂN THỊ BÁC SĨ MỚI ---
        const infoHTML = `
            <strong>${bsName}</strong> (Mã NV: ${firstMaBS})<br>
            Giờ khám: ${selectedTime}
        `;
        selectedDoctorInfo.innerHTML = infoHTML;
        if(doctorDisplayBox) doctorDisplayBox.style.display = 'block';
        displayMessage(``, errorMsgDiv); // Xóa thông báo BS khỏi errorDiv
        
    } else { // Chế độ Theo Bác sĩ hoặc Khám trong giờ
         displayMessage("", errorMsgDiv);
         if(doctorDisplayBox) doctorDisplayBox.style.display = 'none'; // Ẩn khi không ở chế độ Theo Giờ Khám
    }
    
    const selectedDate = ngayKhamHidden.value;
    const period = selectedTime < '12:00' ? 'Sáng' : 'Chiều';
    ngayKhamInput.value = `${selectedDate} (${selectedTime} - ${period})`;
    
    validateAndToggleSubmit(); 
    closeModal(panel); 
}

// Tải khung giờ trống (hỗ trợ cả 2 chế độ)
function loadAvailableTimeSlots(dateStr, isTheoGioKham = false) {
    const maCK = chuyenKhoaSel.value;
    clearTimeSlots();

    const currentDate = new Date();
    const currentDateTimeStr = currentDate.toISOString().split('T')[0];
    const currentTimeStr = currentDate.toTimeString().substring(0, 5); 

    let availableSlotsByTime = {};

    if (isTheoGioKham) {
        // --- CHẾ ĐỘ THEO GIỜ KHÁM ---
        bacsiSel.value = ""; 
        displayMessage("", errorMsgDiv);
        
        const doctorsInKhoa = bacsiData
            .filter(b => b.MaKhoa === maCK)
            .map(b => String(b.MaNV)); // Ép kiểu MaNV sang String

        doctorsInKhoa.forEach(maBS => {
            const caLamViec = lichLamViecData[maBS]?.[dateStr];
            if (!caLamViec) return; 

            const bookedSlotsOnDate = bookedSlotsData[maBS]?.[dateStr] || [];

            let slotsFilteredByCa = defaultTimeSlots.filter(slot => {
                const isMorning = slot < '12:00';
                const isAfternoon = slot >= '12:00';
                let isValidByCa = (caLamViec === 'Sáng' && isMorning) || 
                                  (caLamViec === 'Chiều' && isAfternoon) || 
                                  (caLamViec === 'Cả ngày');
                return isValidByCa;
            });

            let availableSlotsForBS = slotsFilteredByCa.filter(slot => 
                !bookedSlotsOnDate.includes(slot) && (dateStr !== currentDateTimeStr || slot > currentTimeStr)
            );
            
            availableSlotsForBS.forEach(slot => {
                if (!availableSlotsByTime[slot]) {
                    availableSlotsByTime[slot] = [];
                }
                availableSlotsByTime[slot].push(maBS); 
            });
        });

    } else {
        // --- CHẾ ĐỘ THEO BÁC SĨ (VÀ KHÁM TRONG GIỜ) ---
        const maBS = String(bacsiSel.value); // Ép kiểu MaBS sang String
        if (!maBS) { 
            displayMessage("⚠️ Vui lòng chọn bác sĩ trước khi chọn ngày khám.", errorMsgDiv); 
            return false; 
        }
        
        const caLamViec = lichLamViecData[maBS]?.[dateStr]; 
        if (!caLamViec) { 
            displayMessage("⚠️ Bác sĩ không có lịch làm việc trong ngày này.", errorMsgDiv); 
            return false; 
        } 
        displayMessage("", errorMsgDiv);
        
        const bookedSlotsOnDate = bookedSlotsData[maBS]?.[dateStr] || [];
        
        let slotsFilteredByCa = defaultTimeSlots.filter(slot => {
            const isMorning = slot < '12:00';
            const isAfternoon = slot >= '12:00';
            
            let isValidByCa = (caLamViec === 'Sáng' && isMorning) || 
                              (caLamViec === 'Chiều' && isAfternoon) || 
                              (caLamViec === 'Cả ngày');
            
            if (window.isTrongGioMode && dateStr === currentDateTimeStr) {
                const currentHour = currentDate.getHours();
                const currentPeriod = currentHour < 12 ? 'Sáng' : 'Chiều';

                if (currentPeriod === 'Sáng') { 
                    isValidByCa = isValidByCa && isMorning; 
                } else { 
                    isValidByCa = isValidByCa && isAfternoon; 
                }
            }
            return isValidByCa;
        });
        
        let availableSlots = slotsFilteredByCa.filter(slot => !bookedSlotsOnDate.includes(slot));
        
        if (dateStr === currentDateTimeStr) {
            availableSlots = availableSlots.filter(slot => slot > currentTimeStr);
        }
        
        availableSlots.forEach(slot => {
            availableSlotsByTime[slot] = [maBS];
        });
    }

    const availableTimes = Object.keys(availableSlotsByTime).sort();

    if (availableTimes.length > 0) {
        let hasMorning = false; 
        let hasAfternoon = false;
        
        availableTimes.forEach(slot => {
            const group = slot < '12:00' ? 'sang' : 'chieu';
            if (group === 'sang') hasMorning = true; 
            if (group === 'chieu') hasAfternoon = true;
            
            const numAvailable = availableSlotsByTime[slot].length;
            const slotSpan = document.createElement("span"); 
            slotSpan.textContent = slot + (numAvailable > 1 ? ` (+${numAvailable - 1} BS)` : ''); 
            
            slotSpan.classList.add('time-slot'); 
            slotSpan.dataset.time = slot; 
            slotSpan.dataset.group = group; 
            
            // Ép kiểu MaNV sang String khi gán vào dataset
            slotSpan.dataset.maBsList = availableSlotsByTime[slot].map(String).join(','); 
            
            slotSpan.addEventListener('click', handleTimeSlotClick);
            gioKhamContainer.appendChild(slotSpan);
        });
        
        updateTimeSlotTabs(hasMorning, hasAfternoon); 
        let initialGroup = hasMorning ? 'sang' : (hasAfternoon ? 'chieu' : '');
        if (initialGroup) { 
            switchTab(initialGroup); 
            return true; 
        } 
    }
    return false;
}

function filterDoctorsWithFutureSchedule(doctors) {
    const todayStr = new Date().toISOString().split('T')[0];
    
    return doctors.filter(b => {
        const maBS = String(b.MaNV); 
        const lich = lichLamViecData[maBS];
        
        if (!lich) return false;
        
        for (const date in lich) {
            // Đảm bảo so sánh ngày luôn ở dạng chuỗi (YYYY-MM-DD)
            if (String(date) >= todayStr) { 
                return true; 
            }
        }
        return false;
    });
}

function filterDoctorsForCurrentShift(doctors) {
    const currentDate = new Date();
    const todayStr = currentDate.toISOString().split('T')[0];
    const currentHour = currentDate.getHours();
    const currentPeriod = currentHour < 12 ? 'Sáng' : 'Chiều';

    return doctors.filter(b => {
        const maBS = String(b.MaNV); // Ép kiểu MaNV sang String
        const caLamViec = lichLamViecData[maBS]?.[todayStr];
        if (!caLamViec) return false;
        
        if (caLamViec === 'Cả ngày') return true;
        if (currentPeriod === 'Sáng' && caLamViec === 'Sáng') return true;
        if (currentPeriod === 'Chiều' && caLamViec === 'Chiều') return true;
        
        return false;
    });
}

function validateAndToggleSubmit() {
    const maBS = bacsiSel.value;
    const ngayKham = ngayKhamHidden.value;
    const gioKham = gioKhamHidden.value;
    const maCK = chuyenKhoaSel.value;
    const maDV = dichvuHiddenInput.value; // Dùng Hidden Input
    
    // Nếu chưa đăng nhập, nút Submit luôn bị disabled (xử lý trong disableFormInteractions)
    if (!isLoggedIn) {
        disableFormInteractions(true);
        return;
    }

    if (maBS && ngayKham && gioKham && maCK && maDV) {
        btnSubmit.disabled = false;
        btnSubmit.style.backgroundColor = '#007bff'; 
        btnSubmit.style.color = 'white';
    } else {
        btnSubmit.disabled = true;
        btnSubmit.style.backgroundColor = '#ccc'; 
        btnSubmit.style.color = '#666';
    }
}

function goToNextStep() {
    window.finalBookingData = {
        maBS: bacsiSel.value,
        ngayKham: ngayKhamHidden.value,
        gioKham: gioKhamHidden.value,
        maCK: chuyenKhoaSel.value,
        maDV: dichvuHiddenInput.value // Dùng Hidden Input
    };
    
    trieuChungInput.value = ""; 
    document.getElementById('booking-step-1').style.display = 'none';
    document.getElementById('booking-step-2').style.display = 'block';
    
    window.scrollTo({ 
        top: document.getElementById('booking-step-2').offsetTop - 20, 
        behavior: 'smooth' 
    });
}

function goToPreviousStep() {
    document.getElementById('booking-step-2').style.display = 'none';
    document.getElementById('booking-step-1').style.display = 'block';
    window.scrollTo({ 
        top: document.getElementById('booking-step-1').offsetTop - 20, 
        behavior: 'smooth' 
    });
}
// ********** HẾT HÀM XỬ LÝ LỊCH VÀ BƯỚC **********

function populateBacsiSelect(doctors, addDefaultOption = true) {
    bacsiSel.innerHTML = "";
    if (addDefaultOption) {
        bacsiSel.innerHTML = "<option value=''>Chọn bác sĩ</option>";
    }
    
    doctors.forEach(b => {
        const opt = document.createElement("option");
        opt.value = b.MaNV;
        opt.textContent = b.HovaTen;
        bacsiSel.appendChild(opt);
    });
}

// ********** HÀM XỬ LÝ SỰ KIỆN **********
chuyenKhoaSel.addEventListener("change", function() {
    datLichSel.disabled = (dichvuHiddenInput.value === "" || window.isTrongGioMode); // Dùng Hidden Input
    bacsiSel.value = ""; 
    toggleBacsi(); 
    validateAndToggleSubmit();
});

bacsiSel.addEventListener("change", function() {
    const maBS = this.value;
    const datLichMode = datLichSel.value;
    const todayStr = new Date().toISOString().split('T')[0];

    ngayKhamInput.value = "Chọn ngày khám";
    ngayKhamHidden.value = "";
    gioKhamHidden.value = ""; 
    displayMessage();
    clearTimeSlots();
    closeModal(panel); 
    
    // Ẩn hộp hiển thị bác sĩ khi chọn bác sĩ thủ công
    if (doctorDisplayBox) doctorDisplayBox.style.display = 'none'; 
    
    if (maBS !== "") {
        const allAvailableDates = Object.keys(lichLamViecData[String(maBS)] || {}); // Ép kiểu MaBS sang String

        if (window.isTrongGioMode || datLichMode === "theoBacSi") {
             
            if (window.isTrongGioMode) {
                if (allAvailableDates.includes(todayStr)) {
                    updateFlatpickr([todayStr], true, "today"); 
                } else {
                    updateFlatpickr([], false);
                    displayMessage("⚠️ Bác sĩ này không có lịch làm việc hôm nay để khám trong giờ.", errorMsgDiv); 
                }
            } else { // Chế độ theoBacSi thuần
                 if (allAvailableDates.length > 0) {
                    updateFlatpickr(
                        allAvailableDates.filter(date => date >= todayStr), 
                        true, 
                        "today"
                    ); 
                } else {
                    updateFlatpickr([], false);
                    displayMessage("⚠️ Bác sĩ này chưa có lịch làm việc trong tương lai gần.", errorMsgDiv); 
                }
            }
        } else {
             // Đảm bảo không còn ở chế độ "Theo giờ khám" khi BS bị thay đổi thủ công
             toggleBacsi();
             displayMessage("ℹ️ Vui lòng chọn Ngày khám để xem giờ trống của Bác sĩ.", errorMsgDiv);
        }
    } else {
        updateFlatpickr([], false);
    }
    validateAndToggleSubmit();
});

// THAY THẾ TOÀN BỘ KHỐI dichvuSel.addEventListener("change", ...) BẰNG LOGIC DÀNH CHO BUTTONS
if (dichvuButtonsContainer) {
    dichvuButtonsContainer.querySelectorAll('.service-button').forEach(button => {
        button.addEventListener('click', function() {
            
            // 1. Xóa trạng thái chọn của tất cả các nút
            dichvuButtonsContainer.querySelectorAll('.service-button').forEach(btn => {
                btn.classList.remove('selected');
            });
            
            // 2. Đặt trạng thái chọn cho nút hiện tại
            this.classList.add('selected');
            
            // 3. Lấy giá trị MaDV và gán vào Hidden Input
            const maDV = this.dataset.dvId; 
            dichvuHiddenInput.value = maDV; 

            // 4. Gọi lại logic xử lý chính (giống như trong change event cũ)
            if (maDV === "1") {
                window.isTrongGioMode = true; 
                datLichSel.value = "theoBacSi"; 
                datLichSel.disabled = true; 
            } else {
                window.isTrongGioMode = false; 
                datLichSel.disabled = (maDV === "");
                
                if (maDV !== "" && datLichSel.value === "") {
                     datLichSel.value = datLichSel.options[0] 
                        ? datLichSel.options[0].value 
                        : 'theoBacSi'; 
                } else if (maDV === "") {
                     datLichSel.value = datLichSel.options[0] 
                        ? datLichSel.options[0].value 
                        : ''; 
                }
            }
            
            // 5. Cập nhật giao diện và trạng thái
            toggleBacsi(); 
            validateAndToggleSubmit();
        });
    });
}
// HẾT KHỐI THAY THẾ DỊCH VỤ

datLichSel.addEventListener("change", function() {
    if (window.isTrongGioMode) return; 
    
    if (datLichSel.value !== "") {
        if (!chuyenKhoaSel.value) {
            alert("Vui lòng chọn Chuyên khoa trước khi chọn chế độ Đặt lịch.");
            datLichSel.value = datLichSel.options[0] 
                ? datLichSel.options[0].value 
                : 'theoBacSi'; 
            return;
        }
    }
    toggleBacsi();
});

window.toggleBacsi = toggleBacsi; // Đặt hàm vào window để gọi từ HTML/PHP

function toggleBacsi() {
    const datLich = datLichSel.value;
    const maCK = chuyenKhoaSel.value;
    
    // Phần reset giữ nguyên
    ngayKhamInput.value = "Chọn ngày khám";
    ngayKhamHidden.value = "";
    gioKhamHidden.value = "";
    displayMessage();
    clearTimeSlots();
    updateFlatpickr([], false);
    
    // --- THÊM LOGIC ẨN HỘP HIỂN THỊ BÁC SĨ ---
    if (doctorDisplayBox) doctorDisplayBox.style.display = "none";
    
    if (!bacsiGroup || !maCK) {
        bacsiGroup.style.display = "block"; 
        return;
    }

    let doctorsByKhoa = bacsiData.filter(b => b.MaKhoa === maCK);

    if (window.isTrongGioMode) {
        // --- CHẾ ĐỘ KHÁM TRONG GIỜ ---
        bacsiGroup.style.display = "block";
        
        const currentHour = new Date().getHours();
        const currentPeriod = currentHour < 12 ? 'Sáng' : 'Chiều';
        const doctorsInShift = filterDoctorsForCurrentShift(doctorsByKhoa);
        
        if (doctorsInShift.length > 0) {
            populateBacsiSelect(doctorsInShift, true);
            displayMessage(
                `ℹ️ Chế độ Khám Trong Giờ. Vui lòng chọn Bác sĩ có lịch làm việc trong ca ${currentPeriod} hôm nay.`,
                errorMsgDiv
            );
            updateFlatpickr(
                [new Date().toISOString().split('T')[0]], 
                true, 
                "today"
            ); 
        } else {
            populateBacsiSelect([], true);
            updateFlatpickr([], false); 
            displayMessage(
                `⚠️ Không có Bác sĩ nào trong chuyên khoa này có lịch trong ca ${currentPeriod} hôm nay để khám trong giờ.`,
                errorMsgDiv
            );
        }

    } else if (datLich === "theoBacSi") {
        // --- CHẾ ĐỘ THEO BÁC SĨ ---
        bacsiGroup.style.display = "block";
        
        const futureDoctors = filterDoctorsWithFutureSchedule(doctorsByKhoa);
        
        if (futureDoctors.length > 0) {
            populateBacsiSelect(futureDoctors, true);
            displayMessage("ℹ️ Chọn Bác sĩ để xem lịch khám trong tương lai.", errorMsgDiv);
        } else {
            populateBacsiSelect([], true);
            displayMessage(
                "⚠️ Không có Bác sĩ nào trong chuyên khoa này có lịch làm việc trong tương lai.",
                errorMsgDiv
            );
        }

    } else if (datLich === "theoGioKham") {
        // --- CHẾ ĐỘ THEO GIỜ KHÁM: TỔNG HỢP LỊCH TẤT CẢ BS ---
        bacsiGroup.style.display = "none"; 
        bacsiSel.value = ""; 
        
        // 1. Chỉ lấy những Bác sĩ có lịch trong tương lai
        const futureDoctors = filterDoctorsWithFutureSchedule(doctorsByKhoa);
        // Vẫn populate select ẩn để logic chọn tự động sau này hoạt động
        populateBacsiSelect(futureDoctors, true); 
        
        // 2. Lấy IDs của các bác sĩ CÓ lịch trong tương lai
        const futureDoctorIDs = futureDoctors.map(b => String(b.MaNV)); // Ép kiểu MaNV sang String
        
        const todayStr = new Date().toISOString().split('T')[0];
        
        // 3. Tính toán union (tổng hợp) các ngày làm việc từ tất cả BS có lịch
        const allCKDates = futureDoctorIDs
                            .flatMap(maBS => Object.keys(lichLamViecData[maBS] || {}))
                            .filter((date, index, self) => 
                                self.indexOf(date) === index && date >= todayStr
                            );
                            
        if (allCKDates.length > 0) {
            updateFlatpickr(allCKDates, true, "today"); 
            displayMessage(
                "ℹ️ Vui lòng chọn Ngày khám để xem giờ trống của tất cả Bác sĩ trong chuyên khoa.",
                errorMsgDiv
            );
        } else {
            updateFlatpickr([], false);
            displayMessage(
                "⚠️ Không có Bác sĩ nào trong chuyên khoa này có lịch làm việc trong tương lai.",
                errorMsgDiv
            );
        }
    } 
    
    validateAndToggleSubmit();
}

// ********** HÀM XỬ LÝ LOGIC ĐĂNG NHẬP (ĐÃ CẬP NHẬT THEO YÊU CẦU MỚI) **********

// HÀM VÔ HIỆU HÓA TƯƠNG TÁC FORM
function disableFormInteractions(shouldDisable) {
    // Theo yêu cầu mới: KHÔNG VÔ HIỆU HÓA hay LÀM MỜ các trường (select, input)
    // Chỉ cần:
    // 1. Vô hiệu hóa nút Submit
    // 2. Đánh dấu trạng thái "bị chặn" cho khu vực form chính để logic khác kiểm tra
    
    // 1. Vô hiệu hóa nút Submit
    btnSubmit.disabled = shouldDisable;
    btnSubmit.style.backgroundColor = shouldDisable ? '#ccc' : '#007bff';
    btnSubmit.style.color = shouldDisable ? '#666' : 'white';

    // 2. Đánh dấu trạng thái bị chặn
    mainBookingArea.dataset.isBlocked = shouldDisable ? 'true' : 'false';
}

// HÀM KIỂM TRA ĐĂNG NHẬP VÀ HIỂN THỊ MODAL
function checkLoginAndPrompt(event) {
    // Nếu đã đăng nhập, hoặc người dùng đang đóng modal, thì bỏ qua
    if (isLoggedIn || event.target.classList.contains('close-button')) {
        return;
    }
    
    // Các phần tử cần bị chặn khi chưa đăng nhập: SELECT, nút Dịch vụ, input Ngày khám
    const isInteractiveElement = event.target.tagName === 'SELECT' || 
                                 event.target.classList.contains('service-button') || 
                                 event.target.id === 'NgayKham' ||
                                 event.target.id === 'btnSubmitBooking';
                                 
    // Bắt sự kiện click lên bất kỳ phần tử tương tác nào
    if (isInteractiveElement) {
        
        event.preventDefault(); // <<< CHẶN HÀNH ĐỘNG MẶC ĐỊNG (Không cho mở Select, không cho mở lịch)
        event.stopPropagation(); // Ngăn chặn lan truyền sự kiện

        // Vô hiệu hóa form (chủ yếu là nút Submit) và đánh dấu trạng thái chặn
        disableFormInteractions(true);
        displayMessage(
            "⚠️ Vui lòng Đăng nhập hoặc Đăng ký để bắt đầu đặt lịch.", 
            lichErrorMsgContainer
        );
        
        // Mở Modal (Đảm bảo gọi cuối cùng)
        openModal(authModal);
    }
}
// *****************************************************************************

document.addEventListener("DOMContentLoaded", function() {
    // ⚠️ Đã cập nhật để dùng dichvuHiddenInput.value
    if (dichvuHiddenInput.value === "1") { 
        window.isTrongGioMode = true;
        datLichSel.value = "theoBacSi";
        datLichSel.disabled = true;
    } else {
        window.isTrongGioMode = false;
        datLichSel.disabled = (dichvuHiddenInput.value === "");
        
        if (datLichSel.value === "" && dichvuHiddenInput.value !== "") {
            datLichSel.value = datLichSel.options[0] 
                ? datLichSel.options[0].value 
                : 'theoBacSi'; 
        } else if (datLichSel.value === "") {
             datLichSel.value = datLichSel.options[0] 
                ? datLichSel.options[0].value 
                : ''; 
        }
    }
    
    toggleBacsi();
    validateAndToggleSubmit();

    // Logic để chọn nút mặc định nếu có giá trị đã được gán sẵn
    if (dichvuHiddenInput.value && dichvuButtonsContainer) {
        const initialButton = dichvuButtonsContainer.querySelector(
            `[data-dv-id="${dichvuHiddenInput.value}"]`
        );
        if (initialButton) {
            initialButton.classList.add('selected');
        }
    }
    
    // ********** LOGIC KIỂM TRA ĐĂNG NHẬP NGAY KHI TƯƠNG TÁC **********
    if (mainBookingArea && !isLoggedIn) {
        // Vô hiệu hóa nút Submit và đánh dấu trạng thái chặn
        disableFormInteractions(true);
        displayMessage(
            "⚠️ Vui lòng Đăng nhập hoặc Đăng ký để bắt đầu đặt lịch.",
            lichErrorMsgContainer
        );
        
        // Gắn listener CHẶN (Capture Phase: true)
        // Listener này sẽ chạy trước hành động mặc định của trình duyệt (vd: mở select)
        mainBookingArea.addEventListener('click', checkLoginAndPrompt, true); 
        mainBookingArea.addEventListener('change', checkLoginAndPrompt, true); 
    }
    // *****************************************************************************
});


function handleBookingFinalStep() {
    const trieuChung = trieuChungInput.value.trim();
    if (!trieuChung) {
        alert("Vui lòng mô tả triệu chứng của bạn để hoàn tất đăng ký.");
        return;
    }
    
    window.finalBookingData.TrieuChung = trieuChung;

    if (isLoggedIn) {
        // --- LOGIC KIỂM TRA THÔNG TIN HỒ SƠ ĐẦY ĐỦ ---
        // Sử dụng key chính xác từ PHP: userData.HovaTen, userData.SoDT, userData.MaBN
        const customerHoTen = userData && userData.HovaTen ? userData.HovaTen.trim() : '';
        const customerSDT = userData && userData.SoDT ? userData.SoDT.trim() : '';
        const customerMaBN = userData && userData.MaBN ? userData.MaBN : null; // Dùng MaBN
        
        // Chỉ chấp nhận nếu có đủ Họ Tên, SĐT và Mã Bệnh nhân (MaBN > 0)
        if (!customerHoTen || !customerSDT || !customerMaBN || customerMaBN <= 0) {
            alert("⚠️ Hồ sơ khám bệnh chưa hoàn chỉnh (thiếu Họ Tên, SĐT, hoặc Mã Khách hàng). Bạn sẽ được chuyển hướng đến trang tạo hồ sơ.");
            
            // CHUYỂN HƯỚNG ĐẾN TRANG TẠO HỒ SƠ/CẬP NHẬT THÔNG TIN
            window.location.href = "/KLTN_Benhvien/Register/BNHS";
            
            return; 
        } else {
            // Đã có đủ thông tin, tiến hành hoàn tất
            completeBooking({
                isLoggedIn: true,
                // TRUYỀN DỮ LIỆU VỚI KEY CHUẨN XÁC:
                MaBN: customerMaBN, 
                HovaTen: userData.HovaTen, 
                SoDT: userData.SoDT, 
                Email: userData.Email || '',
            });
        }
    } else {
        // --- CHƯA ĐĂNG NHẬP: Mở modal Auth và BẮT BUỘC đăng nhập/đăng ký ---
        openModal(authModal);
    }
}

// ********** HÀM HOÀN TẤT VÀ GỌI API THỰC TẾ (CHỈ DÙNG CHO KHÁCH ĐĂNG KÝ) **********
async function completeBooking(customerInfo) {
    // Chuẩn hóa dữ liệu gửi đi để khớp với PHP backend
    // PHP Backend (trong PHP gốc) dùng: $maKH (MaBN), $maBS, $maCK, $maDV, $ngayKham, $gioKham, $trieuChung
    
    const dataToSend = {
        // 1. Dữ liệu bệnh nhân (MaBN)
        MaKH: customerInfo.MaBN, 
        // 2. Dữ liệu lịch khám (từ Bước 1)
        maBS: window.finalBookingData.maBS,
        maCK: window.finalBookingData.maCK,
        maDV: window.finalBookingData.maDV,
        ngayKham: window.finalBookingData.ngayKham,
        gioKham: window.finalBookingData.gioKham,
        
        // 3. Triệu chứng (từ Bước 2)
        TrieuChung: window.finalBookingData.TrieuChung 
    };
    
    // Vô hiệu hóa nút để tránh gửi nhiều lần
    document.getElementById('btnCompleteBooking').disabled = true;

    try {
        const response = await fetch(BOOKING_API_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(dataToSend), // Gửi dữ liệu đã chuẩn hóa
        });

        const result = await response.json();

        if (response.ok && result.success) {
            // ✅ ĐÚNG YÊU CẦU: KHÔNG alert, KHÔNG truyền mã, CHUYỂN THẲNG TRANG THANH TOÁN
            console.log("Đặt lịch thành công, chuyển sang trang Thanh toán...");
            window.location.href = "/KLTN_Benhvien/ThanhToan";
            return;
        } else {
            // Hiển thị lỗi từ server
            alert(
                `❌ LỖI ĐẶT LỊCH:\n` + 
                (result.message || 'Có lỗi xảy ra trong quá trình xử lý.')
            );
        }

    } catch (error) {
        console.error('Lỗi khi gửi yêu cầu:', error);
        alert('❌ LỖI KẾT NỐI: Không thể kết nối đến máy chủ. Vui lòng kiểm tra lại đường dẫn API.');
    } finally {
         // Kích hoạt lại nút sau khi xử lý xong
         document.getElementById('btnCompleteBooking').disabled = false;
    }
}

function resetAllSteps() {
    document.getElementById('form-step-1').reset();
    document.getElementById('form-step-2').reset();  
    goToPreviousStep(); 
    
    // Gọi toggleBacsi() để reset trạng thái lựa chọn và lịch
    toggleBacsi(); 
    
    validateAndToggleSubmit(); 
    
    // Reset trạng thái nút dịch vụ sau khi reset form
    if (dichvuButtonsContainer) {
         dichvuButtonsContainer.querySelectorAll('.service-button').forEach(btn => {
            btn.classList.remove('selected');
        });
        dichvuHiddenInput.value = ""; // Đảm bảo hidden input reset
    }
    
    // Bỏ trạng thái bị chặn khi reset
    disableFormInteractions(false);
}

btnSubmit.addEventListener('click', goToNextStep);
