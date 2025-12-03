<?php
$error        = isset($data["error"]) ? $data["error"] : "";
$success      = isset($data["success"]) ? $data["success"] : "";
$GhiChuChung  = isset($data["GhiChuChung"]) ? $data["GhiChuChung"] : "";
$todayLabel   = date('d-m-Y');
$DanhSachThuoc = isset($data["DanhSachThuoc"]) ? json_decode($data["DanhSachThuoc"], true) : [];
?>

<style>
    .bl-create-container {
        background: #ffffff;
        padding: 22px 24px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        font-family: Arial, sans-serif;
    }

    .bl-create-title {
        font-size: 22px;
        font-weight: 700;
        color: #0c857d;
        margin-bottom: 8px;
    }

    .bl-create-subtitle {
        font-size: 13px;
        color: #777;
        margin-bottom: 18px;
    }

    .bl-create-row {
        margin-bottom: 14px;
    }

    .bl-create-label {
        font-size: 14px;
        font-weight: 600;
        color: #444;
        margin-bottom: 4px;
        display: block;
    }

    .bl-create-input,
    .bl-create-textarea {
        width: 100%;
        padding: 8px 10px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 14px;
        box-sizing: border-box;
    }

    .bl-create-textarea {
        min-height: 70px;
        resize: vertical;
    }

    .bl-medicine-section-title {
        font-size: 16px;
        font-weight: 600;
        color: #0c857d;
        margin: 18px 0 10px 0;
    }

    .bl-med-table-wrapper {
        border-radius: 8px;
        border: 1px solid #e1e1e1;
        overflow: hidden;
        margin-bottom: 12px;
    }

    .bl-med-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .bl-med-table thead {
        background: #0c857d;
        color: #fff;
    }

    .bl-med-table th,
    .bl-med-table td {
        padding: 8px 6px;
        border-bottom: 1px solid #e1e1e1;
        text-align: left;
    }

    .bl-med-table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 12px;
    }

    .bl-med-table tbody tr:hover {
        background: #f6fbfa;
    }

    .bl-input-small {
        width: 70px;
        padding: 6px 8px;
        border-radius: 4px;
        border: 1px solid #ccc;
        font-size: 13px;
    }

    .bl-input-text {
        width: 100%;
        padding: 6px 8px;
        border-radius: 4px;
        border: 1px solid #ccc;
        font-size: 13px;
        box-sizing: border-box;
    }

    .bl-input-readonly {
        background: #f7f7f7;
        color: #555;
    }

    .bl-btn {
        padding: 8px 18px;
        border-radius: 999px;
        border: none;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        transition: background 0.2s, box-shadow 0.2s, transform 0.1s;
    }

    .bl-btn-add {
        background: #3498db;
        color: #fff;
        margin-bottom: 10px;
    }

    .bl-btn-add:hover {
        background: #2980b9;
        box-shadow: 0 2px 6px rgba(41, 128, 185, 0.4);
        transform: translateY(-1px);
    }

    .bl-btn-remove-row {
        padding: 4px 8px;
        border-radius: 999px;
        border: none;
        background: #e74c3c;
        color: #fff;
        cursor: pointer;
        font-size: 12px;
        font-weight: 600;
    }

    .bl-btn-remove-row:hover {
        background: #c0392b;
    }

    .bl-actions {
        margin-top: 16px;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .bl-btn-cancel {
        background: #bdc3c7;
        color: #2c3e50;
    }

    .bl-btn-cancel:hover {
        background: #95a5a6;
        box-shadow: 0 2px 6px rgba(149, 165, 166, 0.4);
        transform: translateY(-1px);
    }

    .bl-btn-submit {
        background: #27ae60;
        color: #fff;
    }

    .bl-btn-submit:hover {
        background: #1e8449;
        box-shadow: 0 2px 6px rgba(39, 174, 96, 0.4);
        transform: translateY(-1px);
    }

    .bl-alert {
        padding: 10px 12px;
        border-radius: 6px;
        font-size: 13px;
        margin-bottom: 10px;
    }

    .bl-alert-error {
        background: #fbeaea;
        color: #c0392b;
        border: 1px solid #e74c3c;
    }

    .bl-alert-success {
        background: #eaf9f1;
        color: #1e8449;
        border: 1px solid #27ae60;
    }

    .bl-tag-today {
        display: inline-block;
        padding: 4px 10px;
        background: #eafaf7;
        border-radius: 999px;
        font-size: 12px;
        color: #0c857d;
        margin-left: 6px;
    }

    @media (max-width: 768px) {
        .bl-med-table-wrapper {
            overflow-x: auto;
        }

        .bl-med-table {
            min-width: 800px;
        }
    }
</style>

<div class="bl-create-container">
    <h2 class="bl-create-title">Tạo đơn bán lẻ thuốc</h2>
    <div class="bl-create-subtitle">
        Ngày kê: <strong><?php echo $todayLabel; ?></strong>
        <span class="bl-tag-today">Đơn bán lẻ - ghi nhận đã thanh toán</span>
    </div>

    <?php if ($error !== ""): ?>
        <div class="bl-alert bl-alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if ($success !== ""): ?>
        <div class="bl-alert bl-alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <form action="/KLTN_Benhvien/NVNT/BanLeTao" method="POST" id="bl-form-create">
        <div class="bl-create-row">
            <label class="bl-create-label">Ghi chú chung đơn bán lẻ (nếu có):</label>
            <textarea name="GhiChuChung" class="bl-create-textarea" placeholder="Ví dụ: Bán thuốc cảm, khách vãng lai..."><?php echo htmlspecialchars($GhiChuChung); ?></textarea>
        </div>

        <div class="bl-medicine-section-title">Danh sách thuốc</div>

        <button type="button" class="bl-btn bl-btn-add" id="bl-btn-add-row">+ Thêm thuốc</button>

        <div class="bl-med-table-wrapper">
            <table class="bl-med-table" id="bl-med-table">
                <thead>
                    <tr>
                        <th style="width: 40px;">#</th>
                        <th style="min-width: 200px;">Tên thuốc</th>
                        <th style="min-width: 120px;">Dạng</th>
                        <th style="min-width: 100px;">Hàm lượng</th>
                        <th style="min-width: 80px;">Đơn vị</th>
                        <th style="width: 80px;">Số lượng</th>
                        <th style="min-width: 150px;">Liều dùng</th>
                        <th style="width: 60px;">Xóa</th>
                    </tr>
                </thead>
                <tbody id="bl-med-tbody">
                    <!-- Các dòng thuốc sẽ được thêm bằng JavaScript -->
                </tbody>
            </table>
        </div>

        <div class="bl-actions">
            <a href="/KLTN_Benhvien/NVNT/BanLe">
                <button type="button" class="bl-btn bl-btn-cancel">Quay lại danh sách</button>
            </a>
            <button type="submit" name="luuDon" class="bl-btn bl-btn-submit">Xác nhận bán thuốc</button>
        </div>
    </form>
</div>

<script>
// Dữ liệu danh sách thuốc server gửi sang cho dropdown
window.BL_THUOC_LIST = <?php echo json_encode($DanhSachThuoc ?: []); ?>;
</script>

<script>
// JS thuần cho thêm/xóa dòng + dropdown chọn thuốc

(function() {
    const tbody = document.getElementById('bl-med-tbody');
    const btnAdd = document.getElementById('bl-btn-add-row');
    const thuocList = Array.isArray(window.BL_THUOC_LIST) ? window.BL_THUOC_LIST : [];

    function buildThuocSelect(selectElement) {
        // Option mặc định
        const defaultOpt = document.createElement('option');
        defaultOpt.value = '';
        defaultOpt.textContent = '-- Chọn thuốc --';
        selectElement.appendChild(defaultOpt);

        thuocList.forEach(function(item) {
            const opt = document.createElement('option');
            opt.value = item.MaThuoc || '';
            const ten = item.TenThuoc || '';
            const hamluong = item.HamLuong || '';
            const dang = item.DangBaoChe || '';
            const dvt = item.DonViTinh || '';

            let label = ten;
            if (hamluong !== '') {
                label += ' - ' + hamluong;
            }
            if (dang !== '') {
                label += ' (' + dang + ')';
            }
            if (dvt !== '') {
                label += ' - ' + dvt;
            }

            opt.textContent = label;
            opt.setAttribute('data-dang', dang);
            opt.setAttribute('data-hamluong', hamluong);
            opt.setAttribute('data-donvitinh', dvt);
            opt.setAttribute('data-dongia', item.DonGiaBan || '');
            selectElement.appendChild(opt);
        });
    }

    function createRow(index) {
        const tr = document.createElement('tr');

        tr.innerHTML = `
            <td class="bl-col-stt">${index + 1}</td>
            <td>
                <select name="ma_thuoc[]" class="bl-input-text bl-select-thuoc"></select>
            </td>
            <td>
                <input type="text" class="bl-input-text bl-input-readonly bl-dang" readonly />
            </td>
            <td>
                <input type="text" class="bl-input-text bl-input-readonly bl-hamluong" readonly />
            </td>
            <td>
                <input type="text" class="bl-input-text bl-input-readonly bl-donvitinh" readonly />
            </td>
            <td>
                <input type="number" name="so_luong[]" class="bl-input-small" min="1" value="1" />
            </td>
            <td>
                <input type="text" name="lieu_dung[]" class="bl-input-text" placeholder="Ví dụ: 1 ngày 2 lần..." />
            </td>
            <td style="text-align:center;">
                <button type="button" class="bl-btn-remove-row">X</button>
            </td>
        `;

        const btnRemove = tr.querySelector('.bl-btn-remove-row');
        const selectThuoc = tr.querySelector('.bl-select-thuoc');
        const dangInput   = tr.querySelector('.bl-dang');
        const hamInput    = tr.querySelector('.bl-hamluong');
        const dvtInput    = tr.querySelector('.bl-donvitinh');

        // Build dropdown options
        buildThuocSelect(selectThuoc);

        // Khi chọn thuốc -> fill dạng, hàm lượng, đơn vị
        selectThuoc.addEventListener('change', function() {
            const opt = selectThuoc.options[selectThuoc.selectedIndex];
            if (!opt || !opt.value) {
                dangInput.value = '';
                hamInput.value  = '';
                dvtInput.value  = '';
                return;
            }
            dangInput.value = opt.getAttribute('data-dang') || '';
            hamInput.value  = opt.getAttribute('data-hamluong') || '';
            dvtInput.value  = opt.getAttribute('data-donvitinh') || '';
        });

        // Gắn event xóa dòng
        btnRemove.addEventListener('click', function() {
            tbody.removeChild(tr);
            refreshIndex();
        });

        return tr;
    }

    function refreshIndex() {
        const rows = tbody.querySelectorAll('tr');
        rows.forEach((tr, idx) => {
            const sttCell = tr.querySelector('td');
            if (sttCell) {
                sttCell.textContent = idx + 1;
            }
        });
    }

    if (btnAdd) {
        btnAdd.addEventListener('click', function() {
            const index = tbody.querySelectorAll('tr').length;
            const row = createRow(index);
            tbody.appendChild(row);
            refreshIndex();
        });
    }

    // Tạo sẵn 1 dòng ban đầu
    if (btnAdd) {
        btnAdd.click();
    }
})();
</script>
