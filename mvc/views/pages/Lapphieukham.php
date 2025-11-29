<?php
    // NhatCuong: giải mã dữ liệu từ controller
    $dt        = json_decode($data["BenhNhanInfo"], true);
    $bs        = json_decode($data["BacSiInfo"], true);
    $thuocList = isset($data["ThuocList"]) ? $data["ThuocList"] : [];

    // Lấy dòng đầu tiên làm mặc định (thông tin BN + LK + Triệu chứng)
    $first = isset($dt[0]) ? $dt[0] : null;

    $maBN  = $first ? $first['MaBN'] : '';
    $maLK  = $first ? $first['MaLK'] : '';
    $trieuChungDefault = $first && isset($first['TrieuChung']) ? $first['TrieuChung'] : '';
    $ngayHienTai = date('Y-m-d');

    $tenBS = "";
    if (!empty($bs)) {
        $rowBS = $bs[0];
        $tenBS = isset($rowBS['HovaTen']) ? $rowBS['HovaTen'] : "";
    }
?>

<style>
    /* ====== LAYOUT DỌC: Thông tin BN ở trên, Form ở dưới ====== */
    .lpk-grid {
        display: flex;
        flex-direction: column;
        gap: 24px;
        max-width: 100%;
    }

    /* ====== CARD THÔNG TIN BỆNH NHÂN (Trên đầu) ====== */
    .lpk-patient-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        padding: 24px;
        color: white;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
    }

    .lpk-patient-header {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }

    .lpk-patient-avatar {
        width: 64px;
        height: 64px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        font-weight: 700;
        border: 2px solid white;
    }

    .lpk-patient-name {
        flex: 1;
    }

    .lpk-patient-name h3 {
        margin: 0 0 4px 0;
        font-size: 20px;
        font-weight: 600;
    }

    .lpk-patient-id {
        font-size: 13px;
        opacity: 0.9;
    }

    .lpk-patient-info {
        display: grid;
        gap: 12px;
    }

    .lpk-info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
    }

    .lpk-info-label {
        font-size: 13px;
        opacity: 0.9;
        font-weight: 500;
    }

    .lpk-info-value {
        font-size: 14px;
        font-weight: 600;
        text-align: right;
    }

    .lpk-schedule-badge {
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 8px;
        padding: 12px;
        margin-top: 16px;
    }

    .lpk-schedule-badge-title {
        font-size: 12px;
        opacity: 0.8;
        margin-bottom: 6px;
    }

    .lpk-schedule-badge-value {
        font-size: 16px;
        font-weight: 600;
    }

    /* ====== FORM PHIẾU KHÁM (Phải) ====== */
    .lpk-form-card {
        background: white;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        padding: 24px;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
    }

    .lpk-form-section {
        margin-bottom: 24px;
    }

    .lpk-form-section:last-child {
        margin-bottom: 0;
    }

    .lpk-section-title {
        font-size: 16px;
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .lpk-section-icon {
        width: 20px;
        height: 20px;
        color: #2563eb;
    }

    .lpk-form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 16px;
        margin-bottom: 16px;
    }

    .lpk-form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .lpk-label {
        font-size: 14px;
        font-weight: 500;
        color: #334155;
    }

    .lpk-label-required::after {
        content: " *";
        color: #dc2626;
    }

    .lpk-input,
    .lpk-textarea,
    .lpk-select {
        padding: 10px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 14px;
        color: #0f172a;
        transition: all 0.2s;
        background-color: white;
    }

    .lpk-input:focus,
    .lpk-textarea:focus,
    .lpk-select:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .lpk-input:read-only {
        background-color: #f1f5f9;
        cursor: not-allowed;
    }

    .lpk-textarea {
        resize: vertical;
        min-height: 100px;
        font-family: inherit;
    }

    .lpk-help-text {
        font-size: 13px;
        color: #64748b;
        margin-top: 4px;
    }

    /* ====== BẢNG THUỐC ====== */
    .lpk-medicine-section {
        background: #f8fafc;
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #e2e8f0;
    }

    .lpk-medicine-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
        flex-wrap: wrap;
        gap: 12px;
    }

    .lpk-table-wrapper {
        overflow-x: auto;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }

    .lpk-medicine-table {
        width: 100%;
        min-width: 1100px;
        border-collapse: collapse;
        font-size: 13px;
        background: white;
    }

    .lpk-medicine-table thead {
        background: #f1f5f9;
    }

    .lpk-medicine-table th {
        padding: 12px 10px;
        text-align: left;
        font-weight: 600;
        color: #334155;
        border-bottom: 2px solid #e2e8f0;
        white-space: nowrap;
    }

    .lpk-medicine-table td {
        padding: 10px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .lpk-medicine-table tbody tr:hover {
        background-color: #f8fafc;
    }

    .lpk-medicine-table th:first-child,
    .lpk-medicine-table td:first-child {
        width: 40px;
        text-align: center;
    }

    .lpk-medicine-table th:last-child,
    .lpk-medicine-table td:last-child {
        width: 80px;
        text-align: center;
    }

    /* ====== BUTTONS ====== */
    .btn {
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary {
        background-color: #2563eb;
        color: white;
    }

    .btn-primary:hover {
        background-color: #1d4ed8;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.3);
    }

    .btn-success {
        background-color: #16a34a;
        color: white;
    }

    .btn-success:hover {
        background-color: #15803d;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(22, 163, 74, 0.3);
    }

    .btn-danger {
        background-color: #dc2626;
        color: white;
        padding: 6px 12px;
        font-size: 13px;
    }

    .btn-danger:hover {
        background-color: #b91c1c;
    }

    .lpk-form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 24px;
        padding-top: 24px;
        border-top: 1px solid #e2e8f0;
    }

    /* SVG Icons */
    .icon {
        width: 16px;
        height: 16px;
    }
</style>

<div class="lpk-grid">
    <!-- ====== CỘT TRÁI: THÔNG TIN BỆNH NHÂN ====== -->
    <div>
        <div class="lpk-patient-card">
            <?php if (!empty($dt)): ?>
                <?php $r = $dt[0]; ?>
                <div class="lpk-patient-header">
                    <div class="lpk-patient-avatar">
                        <?php 
                            $name = $r['HovaTen'];
                            $nameParts = explode(' ', $name);
                            echo strtoupper(substr(end($nameParts), 0, 1));
                        ?>
                    </div>
                    <div class="lpk-patient-name">
                        <h3><?php echo htmlspecialchars($r['HovaTen']); ?></h3>
                        <div class="lpk-patient-id">Mã BN: <?php echo htmlspecialchars($r['MaBN']); ?></div>
                    </div>
                </div>

                <div class="lpk-patient-info">
                    <div class="lpk-info-item">
                        <span class="lpk-info-label">Ngày sinh</span>
                        <span class="lpk-info-value"><?php echo htmlspecialchars($r['NgaySinh']); ?></span>
                    </div>
                    <div class="lpk-info-item">
                        <span class="lpk-info-label">Giới tính</span>
                        <span class="lpk-info-value"><?php echo htmlspecialchars($r['GioiTinh']); ?></span>
                    </div>
                    <div class="lpk-info-item">
                        <span class="lpk-info-label">Số điện thoại</span>
                        <span class="lpk-info-value"><?php echo htmlspecialchars($r['SoDT']); ?></span>
                    </div>
                    <div class="lpk-info-item">
                        <span class="lpk-info-label">BHYT</span>
                        <span class="lpk-info-value"><?php echo htmlspecialchars($r['BHYT']); ?></span>
                    </div>
                    <div class="lpk-info-item">
                        <span class="lpk-info-label">Địa chỉ</span>
                        <span class="lpk-info-value"><?php echo htmlspecialchars($r['DiaChi']); ?></span>
                    </div>
                </div>

                <div class="lpk-schedule-badge">
                    <div class="lpk-schedule-badge-title">Mã lịch khám</div>
                    <div class="lpk-schedule-badge-value"><?php echo htmlspecialchars($r['MaLK']); ?></div>
                </div>
            <?php else: ?>
                <p>Không tìm thấy thông tin bệnh nhân.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- ====== CỘT PHẢI: FORM LẬP PHIẾU KHÁM ====== -->
    <div>
        <form action="" method="POST" class="lpk-form-card">
            <!-- Hidden fields -->
            <input type="hidden" name="maLK" value="<?php echo htmlspecialchars($maLK); ?>">
            <input type="hidden" name="maBN" value="<?php echo htmlspecialchars($maBN); ?>">

            <!-- Thông tin phiếu khám -->
            <div class="lpk-form-section">
                <div class="lpk-section-title">
                    <svg class="lpk-section-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Thông tin phiếu khám
                </div>
                <div class="lpk-form-row">
                    <div class="lpk-form-group">
                        <label for="ngayTao" class="lpk-label">Ngày tạo phiếu</label>
                        <input type="date" id="ngayTao" name="ngayTao" 
                               value="<?php echo htmlspecialchars($ngayHienTai); ?>" 
                               class="lpk-input" readonly>
                    </div>
                    <div class="lpk-form-group">
                        <label for="bacSi" class="lpk-label">Bác sĩ phụ trách</label>
                        <input type="text" id="bacSi" name="bacSi" 
                               value="<?php echo htmlspecialchars($tenBS); ?>" 
                               class="lpk-input" readonly>
                    </div>
                </div>
            </div>

            <!-- Triệu chứng -->
            <div class="lpk-form-section">
                <div class="lpk-section-title">
                    <svg class="lpk-section-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Triệu chứng lâm sàng
                </div>
                <div class="lpk-form-group">
                    <label for="trieuChung" class="lpk-label lpk-label-required">Triệu chứng</label>
                    <textarea id="trieuChung" name="trieuChung" class="lpk-textarea" required><?php echo htmlspecialchars($trieuChungDefault); ?></textarea>
                    <span class="lpk-help-text">Mô tả các triệu chứng mà bệnh nhân trình bày</span>
                </div>
            </div>

            <!-- Kết quả khám -->
            <div class="lpk-form-section">
                <div class="lpk-form-group">
                    <label for="ketQua" class="lpk-label lpk-label-required">Kết quả khám</label>
                    <textarea id="ketQua" name="ketQua" class="lpk-textarea" required></textarea>
                    <span class="lpk-help-text">Ghi nhận các dấu hiệu lâm sàng, kết quả thăm khám</span>
                </div>
            </div>

            <!-- Chẩn đoán -->
            <div class="lpk-form-section">
                <div class="lpk-form-group">
                    <label for="chuanDoan" class="lpk-label lpk-label-required">Chẩn đoán</label>
                    <input type="text" id="chuanDoan" name="chuanDoan" class="lpk-input" 
                           placeholder="Nhập chẩn đoán bệnh" required>
                </div>
            </div>

            <!-- Đơn thuốc -->
            <div class="lpk-form-section">
                <div class="lpk-medicine-section">
                    <div class="lpk-medicine-header">
                        <div class="lpk-section-title" style="margin-bottom: 0;">
                            <svg class="lpk-section-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                            </svg>
                            Đơn thuốc
                        </div>
                        <button type="button" class="btn btn-primary" onclick="addMedicineRow()">
                            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Thêm thuốc
                        </button>
                    </div>

                    <div class="lpk-table-wrapper">
                        <table class="lpk-medicine-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tên thuốc</th>
                                    <th>Hoạt chất</th>
                                    <th>Hàm lượng</th>
                                    <th>Dạng BC</th>
                                    <th>ĐVT</th>
                                    <th>Đường dùng</th>
                                    <th>Số lượng</th>
                                    <th>Liều dùng</th>
                                    <th>Ghi chú</th>
                                    <th>Xóa</th>
                                </tr>
                            </thead>
                            <tbody id="medicineBody">
                                <!-- JS sẽ thêm các dòng thuốc -->
                            </tbody>
                        </table>
                    </div>
                    <p class="lpk-help-text" style="margin-top: 12px;">
                        * Chọn thuốc từ danh sách, hệ thống sẽ tự động điền thông tin dược lý
                    </p>
                </div>
            </div>

            <!-- Lời dặn -->
            <div class="lpk-form-section">
                <div class="lpk-form-group">
                    <label for="loiDan" class="lpk-label lpk-label-required">Lời dặn của bác sĩ</label>
                    <textarea id="loiDan" name="loiDan" class="lpk-textarea" required></textarea>
                    <span class="lpk-help-text">Hướng dẫn chăm sóc, lưu ý cho bệnh nhân</span>
                </div>
            </div>

            <!-- Ngày tái khám -->
            <div class="lpk-form-section">
                <div class="lpk-form-group">
                    <label for="ngayTaiKham" class="lpk-label">Ngày tái khám</label>
                    <input type="date" id="ngayTaiKham" name="ngayTaiKham" 
                           min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" 
                           class="lpk-input">
                    <span class="lpk-help-text">Để trống nếu không cần tái khám</span>
                </div>
            </div>

            <!-- Actions -->
            <div class="lpk-form-actions">
                <button type="submit" name="lap" class="btn btn-success">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Lưu phiếu khám
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Dữ liệu thuốc từ PHP
    const thuocData = <?php echo json_encode($thuocList, JSON_UNESCAPED_UNICODE); ?> || [];
    let medicineIndex = 0;

    function escapeHtml(str) {
        if (str === null || str === undefined) return "";
        return String(str)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    function buildThuocOptions() {
        let html = '<option value="">-- Chọn thuốc --</option>';
        thuocData.forEach(t => {
            html += `<option 
                        value="${t.MaThuoc}"
                        data-ten="${escapeHtml(t.TenThuoc || '')}"
                        data-hoatchat="${escapeHtml(t.TenHoatChat || '')}"
                        data-hamluong="${escapeHtml(t.HamLuong || '')}"
                        data-dang="${escapeHtml(t.DangBaoChe || '')}"
                        data-dvt="${escapeHtml(t.DonViTinh || '')}"
                        data-duong="${escapeHtml(t.DuongDung || '')}"
                    >${escapeHtml(t.TenThuoc || '')}</option>`;
        });
        return html;
    }

    function addMedicineRow() {
        const tbody = document.getElementById('medicineBody');
        if (!tbody) return;

        medicineIndex++;
        const rowNumber = medicineIndex;

        const tr = document.createElement('tr');
        tr.setAttribute('data-index', rowNumber);

        tr.innerHTML = `
            <td>${rowNumber}</td>
            <td>
                <select name="thuoc[${rowNumber}][MaThuoc]"
                        class="lpk-select medicine-select"
                        onchange="onThuocChange(this)" required>
                    ${buildThuocOptions()}
                </select>
            </td>
            <td class="col-hoatchat"></td>
            <td class="col-hamluong"></td>
            <td class="col-dang"></td>
            <td class="col-dvt"></td>
            <td class="col-duong"></td>
            <td>
                <input type="number"
                       name="thuoc[${rowNumber}][SoLuong]"
                       min="1"
                       class="lpk-input"
                       required>
            </td>
            <td>
                <input type="text"
                       name="thuoc[${rowNumber}][LieuDung]"
                       placeholder="VD: 1 viên x 2 lần/ngày"
                       class="lpk-input"
                       required>
            </td>
            <td>
                <input type="text"
                       name="thuoc[${rowNumber}][CachDung]"
                       placeholder="Ghi chú (nếu có)"
                       class="lpk-input">
            </td>
            <td>
                <button type="button"
                        class="btn btn-danger"
                        onclick="removeMedicineRow(this)">
                    Xóa
                </button>
            </td>
        `;
        tbody.appendChild(tr);
    }

    function onThuocChange(selectEl) {
        const tr = selectEl.closest('tr');
        if (!tr) return;

        const option = selectEl.selectedOptions[0];
        if (!option || !option.value) {
            tr.querySelector('.col-hoatchat').textContent = '';
            tr.querySelector('.col-hamluong').textContent = '';
            tr.querySelector('.col-dang').textContent = '';
            tr.querySelector('.col-dvt').textContent = '';
            tr.querySelector('.col-duong').textContent = '';
            return;
        }

        tr.querySelector('.col-hoatchat').textContent = option.getAttribute('data-hoatchat') || '';
        tr.querySelector('.col-hamluong').textContent = option.getAttribute('data-hamluong') || '';
        tr.querySelector('.col-dang').textContent = option.getAttribute('data-dang') || '';
        tr.querySelector('.col-dvt').textContent = option.getAttribute('data-dvt') || '';
        tr.querySelector('.col-duong').textContent = option.getAttribute('data-duong') || '';
    }

    function removeMedicineRow(btn) {
        const tr = btn.closest('tr');
        if (!tr) return;
        tr.remove();

        // Cập nhật lại STT
        const tbody = document.getElementById('medicineBody');
        const rows = tbody.querySelectorAll('tr');
        rows.forEach((r, idx) => {
            r.querySelector('td:first-child').textContent = idx + 1;
        });
    }

    // Tự động thêm 1 dòng thuốc khi load trang
    document.addEventListener('DOMContentLoaded', function () {
        addMedicineRow();
    });
</script>