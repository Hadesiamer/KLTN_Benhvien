<?php
// Page "Xem danh sách khám" dành cho Bác sĩ
// Không kiểm tra role ở đây vì layoutBacsi.php đã kiểm tra
?>

<h2 class="mb-4">Danh sách khám bệnh</h2>

<div class="filters">
    <div class="date-picker">
        <span>Ngày hiện tại: </span>
        <input type="date" value="<?php echo date('Y-m-d'); ?>" readonly>
    </div>
</div>

<!-- Khung chứa bảng + scroll -->
<div id="appointment-list-container" class="table-container">
    <?php require "./mvc/views/pages/Danhsachkham.php"; ?>
</div>

<?php
// Thông báo kết quả lập phiếu khám (nếu có)
if (isset($data['result'])) {
    if ($data["result"] == 'true') {
        echo '<script language="javascript">
            alert("Lập phiếu khám thành công");
        </script>';
    } else {
        echo '<script language="javascript">
            alert("Lập phiếu khám thất bại");
        </script>';
    }
}
?>

<style>
    /* CSS cho phần "Xem danh sách khám" (không đụng layoutBacsi) */

    .filters {
        display: flex;
        justify-content: flex-start;
        align-items: center;
        margin-bottom: 20px;
        gap: 20px;
    }

    .date-picker {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .date-picker input {
        padding: 5px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    /* 🔥 KHUNG BẢNG CÓ SCROLL */
    .table-container {
        max-height: 420px;      /* chỉnh chiều cao tùy ý */
        overflow-y: auto;
        border: 1px solid #ddd;
        border-radius: 6px;
        padding: 5px;
        background: #fff;
    }

    .patient-list {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .patient-list th,
    .patient-list td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    .patient-list th {
        background-color: #f2f2f2;
    }

    .patient-list tr:hover {
        background-color: #f1f1f1;
    }

    .patient-list .highlight {
        color: blue;
        text-decoration: underline;
        cursor: pointer;
    }

    .patient-list .selected {
        background-color: #cce5ff;
    }

    .btn-submit {
        display: inline-block;
        padding: 6px 12px;
        color: #fff;
        background-color: #007bff;
        border: none;
        border-radius: 4px;
        text-decoration: none;
        text-align: center;
        cursor: pointer;
        font-size: 14px;
    }

    .btn-submit:hover {
        background-color: #0056b3;
    }
</style>

<script>
    // Chọn 1 dòng trong danh sách
    function selectRow(row) {
        var rows = document.querySelectorAll('.patient-list tr');
        rows.forEach(function (r) {
            r.classList.remove('selected');
        });
        row.classList.add('selected');
    }

    // Lọc danh sách khám theo ca làm việc (nếu có radio shift)
    function filterAppointments() {
        var shiftInput = document.querySelector('input[name="shift"]:checked');
        if (!shiftInput) return;

        var shift = shiftInput.value;
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/KLTN_Benhvien/Bacsi/GetDanhSach', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (xhr.status === 200) {
                document.getElementById('appointment-list-container').innerHTML = xhr.responseText;

                // Gán lại sự kiện click cho mỗi dòng sau khi AJAX load
                var rows = document.querySelectorAll('.patient-list tr[data-malk]');
                rows.forEach(function (row) {
                    row.addEventListener('click', function () {
                        selectRow(this);
                    });
                });
            }
        };
        xhr.send('shift=' + encodeURIComponent(shift));
    }

    // Gán sự kiện click ban đầu khi trang load
    document.addEventListener('DOMContentLoaded', function () {
        var rows = document.querySelectorAll('.patient-list tr[data-malk]');
        rows.forEach(function (row) {
            row.addEventListener('click', function () {
                selectRow(this);
            });
        });
    });
</script>
