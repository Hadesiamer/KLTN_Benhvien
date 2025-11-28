<?php
// Ngày đang xem & ca đang xem được truyền từ controller (lần load đầu)
$today       = date('Y-m-d');
$ngayDangXem = isset($data["NgayKham"]) ? $data["NgayKham"] : $today;
$shift       = isset($data["Shift"]) ? $data["Shift"] : 'all';

// Chuẩn hóa lại cho chắc
$d = DateTime::createFromFormat('Y-m-d', $ngayDangXem);
if (!$d || $d->format('Y-m-d') !== $ngayDangXem) {
    $ngayDangXem = $today;
}

$isToday = ($ngayDangXem === $today);

// Chuẩn bị dữ liệu danh sách khám (lần load đầu từ PHP)
if (isset($data["DanhSachKham"])) {
    $danhSach = is_string($data["DanhSachKham"])
        ? json_decode($data["DanhSachKham"], true)
        : $data["DanhSachKham"];
} else {
    $danhSach = [];
}

// Hàm map loại dịch vụ (PHP)
function mapLoaiDichVuLabel($code)
{
    $code = (int)$code;
    switch ($code) {
        case 1:
            return "Khám trong giờ";
        case 2:
            return "Khám ngoài giờ";
        case 3:
            return "Khám online";
        default:
            return "Không xác định";
    }
}
?>

<div class="appointment-card">
    <!-- TIÊU ĐỀ -->
    <div class="appointment-header">
        <h2 class="page-title">Danh sách khám bệnh</h2>
        <span class="page-subtitle">
            Xem danh sách bệnh nhân theo ngày &amp; ca khám. Chỉ lập phiếu khám trong ngày hiện tại.
        </span>
    </div>

    <div class="appointment-filter">
        <div class="filter-left">
            <label for="datePicker" class="filter-label">Ngày khám</label>
            <div class="filter-date-group">
                <button type="button" id="btnPrevDay" class="btn-filter btn-icon">&lt;</button>
                <input type="date" id="datePicker" class="filter-date-input"
                       value="<?php echo htmlspecialchars($ngayDangXem); ?>">
                <button type="button" id="btnNextDay" class="btn-filter btn-icon">&gt;</button>
                <button type="button" id="btnToday" class="btn-filter btn-today">Hôm nay</button>
            </div>
        </div>
        <div class="filter-right">
            <span class="filter-label">Ca khám</span>
            <div class="filter-shift-group">
                <label class="shift-pill">
                    <input type="radio" name="shift" value="all" <?php echo ($shift === 'all') ? 'checked' : ''; ?>>
                    <span>Tất cả</span>
                </label>
                <label class="shift-pill">
                    <input type="radio" name="shift" value="morning" <?php echo ($shift === 'morning') ? 'checked' : ''; ?>>
                    <span>Sáng</span>
                </label>
                <label class="shift-pill">
                    <input type="radio" name="shift" value="afternoon" <?php echo ($shift === 'afternoon') ? 'checked' : ''; ?>>
                    <span>Chiều</span>
                </label>
            </div>
        </div>
    </div>

    <div class="appointment-table-wrapper">
        <table class="patient-list">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Mã LK</th>
                    <th>Tên Bệnh nhân</th>
                    <th>Ngày sinh</th>
                    <th>Số điện thoại</th>
                    <th>Giờ khám</th>
                    <th>Loại dịch vụ</th>
                    <th>Triệu chứng</th>
                    <th>Chức năng</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($danhSach) && is_array($danhSach)) {
                    $STT = 1;
                    foreach ($danhSach as $benhnhan) {
                        // Format ngày sinh dd-mm-yyyy
                        $ngaySinhFormatted = "";
                        if (!empty($benhnhan['NgaySinh'])) {
                            $ngaySinhFormatted = date('d-m-Y', strtotime($benhnhan['NgaySinh']));
                        }

                        $trieuChung = isset($benhnhan['TrieuChung']) ? $benhnhan['TrieuChung'] : "";
                        $loaiDichVuCode = isset($benhnhan['LoaiDichVu']) ? $benhnhan['LoaiDichVu'] : null;
                        $loaiDichVuText = mapLoaiDichVuLabel($loaiDichVuCode);

                        $btnDisabledAttr = $isToday ? "" : "disabled";
                        $btnExtraClass   = $isToday ? "" : " btn-disabled";
                        $btnTitle        = $isToday ? "" : ' title="Chỉ lập phiếu khám trong ngày hiện tại"';
                        ?>
                        <tr data-malk="<?php echo htmlspecialchars($benhnhan['MaLK']); ?>">
                            <td><?php echo $STT; ?></td>
                            <td><?php echo htmlspecialchars($benhnhan['MaLK']); ?></td>
                            <td><?php echo htmlspecialchars($benhnhan['HovaTen']); ?></td>
                            <td><?php echo htmlspecialchars($ngaySinhFormatted); ?></td>
                            <td><?php echo htmlspecialchars($benhnhan['SoDT']); ?></td>
                            <td><?php echo htmlspecialchars($benhnhan['GioKham']); ?></td>
                            <td class="loai-dv-cell"><?php echo htmlspecialchars($loaiDichVuText); ?></td>
                            <td class="trieu-chung-cell">
                                <?php echo htmlspecialchars($trieuChung); ?>
                            </td>
                            <td>
                                <form action="/KLTN_Benhvien/Bacsi/Lapphieukham" method="POST">
                                    <input type="hidden" name="MaBN" value="<?php echo htmlspecialchars($benhnhan['MaBN']); ?>">
                                    <input type="hidden" name="MaLK" value="<?php echo htmlspecialchars($benhnhan['MaLK']); ?>">
                                    <button type="submit"
                                            name="btnLPK"
                                            class="btn-submit btn-lpk<?php echo $btnExtraClass; ?>"
                                            <?php echo $btnDisabledAttr . $btnTitle; ?>>
                                        Lập phiếu khám
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php
                        $STT++;
                    }
                } else {
                    echo "<tr><td colspan='9'>Không có dữ liệu</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    /* CARD CHÍNH */
    .appointment-card {
        background-color: #ffffff;
        border-radius: 10px;
        padding: 18px 20px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
    }

    .appointment-header {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        margin-bottom: 15px;
        gap: 10px;
        flex-wrap: wrap;
    }

    .page-title {
        margin: 0;
        font-size: 20px;
        font-weight: 600;
        color: #2c3e50;
    }

    .page-subtitle {
        font-size: 13px;
        color: #6c757d;
    }

    /* THANH FILTER */
    .appointment-filter {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        gap: 16px;
        flex-wrap: wrap;
        padding: 10px 12px;
        border-radius: 8px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
    }

    .filter-left,
    .filter-right {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .filter-label {
        font-size: 13px;
        font-weight: 600;
        color: #374151;
    }

    .filter-date-group {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
    }

    .filter-date-input {
        padding: 6px 8px;
        border-radius: 6px;
        border: 1px solid #cbd5e1;
        font-size: 14px;
        min-width: 150px;
    }

    .btn-filter {
        padding: 5px 10px;
        border-radius: 6px;
        border: 1px solid #0d6efd;
        background-color: #0d6efd;
        color: #fff;
        font-size: 13px;
        cursor: pointer;
        transition: background-color 0.15s ease, box-shadow 0.15s ease, transform 0.05s ease;
        white-space: nowrap;
    }

    .btn-filter.btn-icon {
        padding: 5px 8px;
        min-width: 32px;
        text-align: center;
    }

    .btn-filter.btn-today {
        background-color: #10b981;
        border-color: #10b981;
    }

    .btn-filter:hover {
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.15);
        transform: translateY(-1px);
    }

    .filter-shift-group {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }

    .shift-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 999px;
        border: 1px solid #cbd5e1;
        background-color: #f9fafb;
        font-size: 13px;
        cursor: pointer;
        transition: background-color 0.15s ease, border-color 0.15s ease;
    }

    .shift-pill input {
        margin: 0;
    }

    .shift-pill input:checked + span {
        font-weight: 600;
        color: #0d6efd;
    }

    .shift-pill:hover {
        background-color: #e5f1ff;
        border-color: #93c5fd;
    }

    /* BẢNG */
    .appointment-table-wrapper {
        max-height: 480px;
        margin-top: 10px;
        overflow-y: auto;
        overflow-x: auto;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        background-color: #ffffff;
    }

    .patient-list {
        width: 100%;
        border-collapse: collapse;
        min-width: 1000px; /* cho bảng rộng ra thêm 1 chút để chứa cột Loại DV */
    }

    .patient-list thead {
        background: linear-gradient(to right, #eff6ff, #f9fafb);
    }

    .patient-list th,
    .patient-list td {
        border: 1px solid #e5e7eb;
        padding: 6px 8px;
        font-size: 14px;
        vertical-align: top;
    }

    .patient-list th {
        font-weight: 600;
        color: #374151;
        white-space: nowrap;
    }

    .patient-list tbody tr:nth-child(even) {
        background-color: #f9fafb;
    }

    .patient-list tbody tr:hover {
        background-color: #e5f1ff;
    }

    .patient-list th:nth-child(1),
    .patient-list td:nth-child(1) {
        width: 50px;
        text-align: center;
        white-space: nowrap;
    }

    .patient-list th:nth-child(2),
    .patient-list td:nth-child(2) {
        width: 70px;
        text-align: center;
        white-space: nowrap;
    }

    .patient-list th:nth-child(4),
    .patient-list td:nth-child(4) {
        width: 110px;
        white-space: nowrap;
    }

    .patient-list th:nth-child(5),
    .patient-list td:nth-child(5) {
        width: 120px;
        white-space: nowrap;
    }

    .patient-list th:nth-child(6),
    .patient-list td:nth-child(6) {
        width: 80px;
        white-space: nowrap;
        text-align: center;
    }

    /* Loại dịch vụ: cho rộng 140px */
    .patient-list th:nth-child(7),
    .patient-list td:nth-child(7) {
        width: 140px;
    }

    .trieu-chung-cell {
        max-width: 260px;
        white-space: normal;
        word-break: break-word;
    }

    .loai-dv-cell {
        white-space: nowrap;
    }

    /* NÚT LẬP PHIẾU KHÁM */
    .btn-lpk {
        padding: 5px 10px;
        font-size: 13px;
        border-radius: 6px;
        border: 1px solid #2563eb;
        background-color: #2563eb;
        color: #fff;
        cursor: pointer;
        transition: background-color 0.15s ease, box-shadow 0.15s ease, transform 0.05s ease;
        white-space: nowrap;
    }

    .btn-lpk:hover {
        background-color: #1d4ed8;
        box-shadow: 0 1px 4px rgba(37, 99, 235, 0.4);
        transform: translateY(-1px);
    }

    .btn-submit.btn-disabled {
        opacity: 0.5;
        cursor: not-allowed;
        pointer-events: none;
        box-shadow: none;
        transform: none;
    }
</style>

<script>
    const baseAjaxUrl = "/KLTN_Benhvien/Bacsi/GetDanhSach";

    const dateInput   = document.getElementById("datePicker");
    const btnPrev     = document.getElementById("btnPrevDay");
    const btnNext     = document.getElementById("btnNextDay");
    const btnToday    = document.getElementById("btnToday");
    const shiftRadios = document.querySelectorAll("input[name='shift']");
    const tbody       = document.querySelector(".patient-list tbody");
    const todayPhp    = "<?php echo $today; ?>";

    function getCurrentShift() {
        let val = "all";
        shiftRadios.forEach(r => {
            if (r.checked) val = r.value;
        });
        return val;
    }

    // Escape HTML đơn giản để tránh lỗi khi render
    function escapeHtml(str) {
        if (str === null || str === undefined) return "";
        return String(str)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    function formatDateDDMMYYYY(isoDate) {
        if (!isoDate) return "";
        const d = new Date(isoDate);
        if (Number.isNaN(d.getTime())) return "";
        const dd = String(d.getDate()).padStart(2, "0");
        const mm = String(d.getMonth() + 1).padStart(2, "0");
        const yyyy = d.getFullYear();
        return `${dd}-${mm}-${yyyy}`;
    }

    // Map loại dịch vụ (JS, dùng cho dữ liệu trả về từ AJAX)
    function mapLoaiDichVuLabel(code) {
        const c = parseInt(code, 10);
        switch (c) {
            case 1:
                return "Khám trong giờ";
            case 2:
                return "Khám ngoài giờ";
            case 3:
                return "Khám online";
            default:
                return "Không xác định";
        }
    }

    // Hàm load danh sách bằng AJAX
    function loadDanhSach(dateStr, shift) {
        const params = new URLSearchParams();
        params.append("date", dateStr);
        params.append("shift", shift);

        fetch(baseAjaxUrl, {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8"
            },
            body: params.toString()
        })
            .then(res => res.json())
            .then(res => {
                if (!res || !res.success) {
                    tbody.innerHTML = "<tr><td colspan='9'>Không có dữ liệu</td></tr>";
                    return;
                }

                const data  = Array.isArray(res.data) ? res.data : [];
                const date  = res.date || dateStr;
                const today = res.today || todayPhp;

                // Cập nhật lại ô date (trong trường hợp server chỉnh lại)
                dateInput.value = date;

                // Cập nhật trạng thái nút radio theo shift trả về
                shiftRadios.forEach(r => {
                    r.checked = (r.value === res.shift);
                });

                tbody.innerHTML = "";

                if (data.length === 0) {
                    tbody.innerHTML = "<tr><td colspan='9'>Không có dữ liệu</td></tr>";
                    return;
                }

                const isToday = (date === today);

                data.forEach((benhnhan, idx) => {
                    const tr = document.createElement("tr");
                    tr.setAttribute("data-malk", escapeHtml(benhnhan.MaLK));

                    const ngaySinhFormatted = formatDateDDMMYYYY(benhnhan.NgaySinh);
                    const trieuChung = benhnhan.TrieuChung || "";
                    const loaiDvText = mapLoaiDichVuLabel(benhnhan.LoaiDichVu);

                    const btnDisabledAttr = isToday ? "" : "disabled";
                    const btnExtraClass   = isToday ? "" : " btn-disabled";
                    const btnTitle        = isToday ? "" : ' title="Chỉ lập phiếu khám trong ngày hiện tại"';

                    tr.innerHTML = `
                        <td>${idx + 1}</td>
                        <td>${escapeHtml(benhnhan.MaLK)}</td>
                        <td>${escapeHtml(benhnhan.HovaTen)}</td>
                        <td>${escapeHtml(ngaySinhFormatted)}</td>
                        <td>${escapeHtml(benhnhan.SoDT)}</td>
                        <td>${escapeHtml(benhnhan.GioKham)}</td>
                        <td class="loai-dv-cell">${escapeHtml(loaiDvText)}</td>
                        <td class="trieu-chung-cell">${escapeHtml(trieuChung)}</td>
                        <td>
                            <form action="/KLTN_Benhvien/Bacsi/Lapphieukham" method="POST">
                                <input type="hidden" name="MaBN" value="${escapeHtml(benhnhan.MaBN)}">
                                <input type="hidden" name="MaLK" value="${escapeHtml(benhnhan.MaLK)}">
                                <button type="submit"
                                        name="btnLPK"
                                        class="btn-submit btn-lpk${btnExtraClass}"
                                        ${btnDisabledAttr}${btnTitle}>
                                    Lập phiếu khám
                                </button>
                            </form>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });
            })
            .catch(err => {
                console.error(err);
                tbody.innerHTML = "<tr><td colspan='9'>Lỗi khi tải dữ liệu</td></tr>";
            });
    }

    // Sự kiện chọn ngày
    dateInput.addEventListener("change", function () {
        const date = this.value || todayPhp;
        const shift = getCurrentShift();
        loadDanhSach(date, shift);
    });

    // Hôm qua / ngày mai
    function changeDay(delta) {
        let current = dateInput.value || todayPhp;
        const d = new Date(current);
        if (Number.isNaN(d.getTime())) {
            d.setTime(Date.now());
        }
        d.setDate(d.getDate() + delta);

        const yyyy = d.getFullYear();
        const mm = String(d.getMonth() + 1).padStart(2, '0');
        const dd = String(d.getDate()).padStart(2, '0');
        const newDate = `${yyyy}-${mm}-${dd}`;

        const shift = getCurrentShift();
        loadDanhSach(newDate, shift);
    }

    btnPrev.addEventListener("click", function () {
        changeDay(-1);
    });

    btnNext.addEventListener("click", function () {
        changeDay(1);
    });

    btnToday.addEventListener("click", function () {
        const shift = getCurrentShift();
        loadDanhSach(todayPhp, shift);
    });

    // Đổi ca khám
    shiftRadios.forEach(radio => {
        radio.addEventListener("change", function () {
            const date = dateInput.value || todayPhp;
            const shift = getCurrentShift();
            loadDanhSach(date, shift);
        });
    });
</script>
