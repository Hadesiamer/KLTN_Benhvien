<?php
    if($_SESSION["role"] != 5){
        echo "<script>alert('Bạn không có quyền truy cập')</script>";
        header("refresh: 0; url='/KLTN_Benhvien'");
    } else if(!isset($_SESSION["idbn"])){
        echo "<script>alert('Mời bạn tạo hồ sơ để tiếp tục!')</script>";
        header("refresh: 0; url='/KLTN_Benhvien/Register/BNHS'");
    }
?>

<?php
if (!isset($_SESSION['step'])) {
    $_SESSION['step'] = 1;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['next'])) {
        $_SESSION['step']++;
    } elseif (isset($_POST['back'])) {
        $_SESSION['step']--;
    } elseif (isset($_POST['confirm'])) {
        $confirmation_message = "Xét nghiệm đã được đăng ký thành công!";
        $_SESSION['step'] = 1; 
        header("refresh: 1; url='/KLTN_Benhvien/'");
    }

    foreach ($_POST as $key => $value) {
        if ($key !== 'next' && $key !== 'back' && $key !== 'confirm') {
            $_SESSION['form_data'][$key] = $value;
        }
    }
}

function get_form_data($key) {
    return isset($_SESSION['form_data'][$key]) ? $_SESSION['form_data'][$key] : '';
}

function generateCalendar($month, $year) {
    $firstDay = mktime(0, 0, 0, $month, 1, $year);
    $daysInMonth = date('t', $firstDay);
    $dayOfWeek = date('w', $firstDay);
    
    $dayOfWeek = ($dayOfWeek == 0 ? 7 : $dayOfWeek);
    
    $calendar = [];
    $week = array_fill(0, 7, '');
    
    $dayCount = 1;
    for ($i = $dayOfWeek; $i <= 7; $i++) {
        $week[$i-1] = $dayCount++;
    }
    $calendar[] = $week;
    
    while ($dayCount <= $daysInMonth) {
        $week = array_fill(0, 7, '');
        for ($i = 0; $i < 7 && $dayCount <= $daysInMonth; $i++) {
            $week[$i] = $dayCount++;
        }
        $calendar[] = $week;
    }
    
    return $calendar;
}

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký Xét Nghiệm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./public/css/main.css">
    <link rel="stylesheet" href="../public/css/main.css">
    <link rel="stylesheet" href="./public/css/dkxn.css">
    <link rel="stylesheet" href="../public/css/dkxn.css">
</head>
<body>
<?php require "./mvc/views/blocks/header.php";?>
<div class="containerdk">
    <form method="POST">
        <div class="headerdk">
            <h2>Đăng Ký Xét Nghiệm</h2>
            <p>Bước <?php echo $_SESSION['step']; ?> / 4</p>
        </div>

        <?php if ($_SESSION['step'] === 1): ?>
            <div class="mb-4">
                <h4>Chọn loại xét nghiệm</h4>
                <div class="test-type">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="test_type" id="amh" value="Xét nghiệm AMH" <?php echo get_form_data('test_type') === 'Xét nghiệm AMH' ? 'checked' : ''; ?> required>
                        <label class="form-check-label" for="amh">Xét nghiệm AMH</label>
                    </div>
                </div>
                <div class="test-type">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="test_type" id="blood" value="Xét nghiệm Máu" <?php echo get_form_data('test_type') === 'Xét nghiệm Máu' ? 'checked' : ''; ?> required>
                        <label class="form-check-label" for="blood">Xét nghiệm Máu</label>
                    </div>
                </div>
            </div>
        <?php elseif ($_SESSION['step'] === 2): ?>
            <div class="mb-4">
                <h4>Chọn ngày xét nghiệm</h4>
                <?php
                $current_month = isset($_POST['month']) ? intval($_POST['month']) : date('n');
                $current_year = isset($_POST['year']) ? intval($_POST['year']) : date('Y');
                $selected_date = get_form_data('test_date');
                ?>
                <div class="calendar-container">
                    <?php if ($selected_date): ?>
                        <div class="alert alert-info mb-3">
                            Ngày đã chọn: <?php echo date('d/m/Y', strtotime($selected_date)); ?>
                        </div>
                    <?php endif; ?>
                    <div class="calendar-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <button type="button" class="btn btn-sm btn-light" onclick="changeMonth(-1)">&lt;</button>
                            <span>THÁNG <?php echo $current_month ?> NĂM <?php echo $current_year ?></span>
                            <button type="button" class="btn btn-sm btn-light" onclick="changeMonth(1)">&gt;</button>
                        </div>
                    </div>
                    <div class="calendar-grid">
                        <div class="calendar-day-header">CN</div>
                        <div class="calendar-day-header">T2</div>
                        <div class="calendar-day-header">T3</div>
                        <div class="calendar-day-header">T4</div>
                        <div class="calendar-day-header">T5</div>
                        <div class="calendar-day-header">T6</div>
                        <div class="calendar-day-header">T7</div>

                        <?php
                        $calendar = generateCalendar($current_month, $current_year);
                        $today = new DateTime();
                        foreach ($calendar as $week) {
                            foreach ($week as $day) {
                                if ($day === '') {
                                    echo '<div class="calendar-date empty"></div>';
                                } else {
                                    $date = new DateTime("$current_year-$current_month-$day");
                                    $date_string = $date->format('Y-m-d');
                                    $is_selected = $selected_date === $date_string;
                                    $is_disabled = $date < $today;
                                    $class = 'calendar-date';
                                    if ($is_selected) $class .= ' selected';
                                    if ($is_disabled) $class .= ' disabled';
                                    echo sprintf(
                                        '<div class="%s" onclick="selectDate(\'%s\', %s)">%d</div>',
                                        $class,
                                        $date_string,
                                        $is_disabled ? 'false' : 'true',
                                        $day
                                    );
                                }
                            }
                        }
                        ?>
                    </div>
                    <input type="hidden" name="test_date" id="selected_date" value="<?php echo $selected_date; ?>" required>
                    <input type="hidden" name="month" id="current_month" value="<?php echo $current_month; ?>">
                    <input type="hidden" name="year" id="current_year" value="<?php echo $current_year; ?>">
                </div>
            </div>
        <?php elseif ($_SESSION['step'] === 3): ?>
            <div class="mb-4">
                <h4>Chọn giờ xét nghiệm</h4>
                <div class="time-section">
                    <h6>Buổi sáng</h6>
                    <div class="time-slots">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="time_slot" id="morning1" value="08:00:00" <?php echo get_form_data('time_slot') === '08:00-10:00' ? 'checked' : ''; ?> required>
                            <label class="form-check-label" for="morning1">08:00:00</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="time_slot" id="morning2" value="10:00:00" <?php echo get_form_data('time_slot') === '10:00-12:00' ? 'checked' : ''; ?> required>
                            <label class="form-check-label" for="morning2">10:00:00</label>
                        </div>
                    </div>
                </div>
                <div class="time-section mt-3">
                    <h6>Buổi chiều</h6>
                    <div class="time-slots">
                        <div claspúhs="form-check">
                            <input class="form-check-input" type="radio" name="time_slot" id="afternoon1" value="14:00:00" <?php echo get_form_data('time_slot') === '14:00-16:00' ? 'checked' : ''; ?> required>
                            <label class="form-check-label" for="afternoon1">14:00:00</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="time_slot" id="afternoon2" value="16:00:00" <?php echo get_form_data('time_slot') === '16:00-18:00' ? 'checked' : ''; ?> required>
                            <label class="form-check-label" for="afternoon2">16:00:00</label>
                        </div>
                    </div>
                </div>
            </div>
        <?php elseif ($_SESSION['step'] === 4): ?>
            <div class="mb-4">
                <h4>Xác nhận thông tin</h4>
                <p><strong>Loại xét nghiệm:</strong> <?php echo get_form_data('test_type'); ?></p>
                <p><strong>Ngày xét nghiệm:</strong> <?php echo get_form_data('test_date'); ?></p>
                <p><strong>Giờ xét nghiệm:</strong> <?php echo get_form_data('time_slot'); ?></p>
                <input type="hidden" name="loaixn" id="loaixn" value="<?php echo get_form_data('test_type');?>">
                <input type="hidden" name="ngayxn" id="ngayxn" value="<?php echo get_form_data('test_date'); ?>">
                <input type="hidden" name="gioxn" id="gioxn" value="<?php echo get_form_data('time_slot'); ?>">
                <input type="hidden" name="kqxn" value=" ">

                <div class="mt-3">
                    <?php 
                        $b = json_decode($data["DKXN"], true);
                        foreach($b as $r): ?>
                            <h5>Thông tin bệnh nhân</h5>
                            <div class="te">
                                <label for="full_name" class="form-label"><strong>Họ và tên:</strong></label>
                                <p id="full_name" class="form-control-static"><?php echo $r["HovaTen"]; ?></p>
                            </div>
                            <div class="te">
                                <label for="gender" class="form-label"><strong>Giới tính:</strong></label>
                                <p id="gender" class="form-control-static"><?php echo $r["GioiTinh"]; ?></p>
                            </div>
                            <div class="te">
                                <label for="email" class="form-label"><strong>Email:</strong></label>
                                <p id="email" class="form-control-static"><?php echo $r["Email"]; ?></p>
                            </div>
                            <div class="te">
                                <label for="insurance" class="form-label"><strong>Bảo hiểm y tế:</strong></label>
                                <p id="insurance" class="form-control-static"><?php echo $r["BHYT"]; ?></p>
                            </div>
                            <div class="te">
                                <label for="medical_code" class="form-label"><strong>Mã phiếu khám:</strong></label>
                                <p id="medical_code" class="form-control-static"><?php echo $r["MaPK"]; ?></p>
                            </div>
                        <?php endforeach;
                    ?>
                </div>
                
                <div class="mt-3">
                    <h6>Phương thức thanh toán</h6>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="payment_method" id="cash" value="Tiền mặt" <?php echo get_form_data('payment_method') === 'Tiền mặt' ? 'checked' : ''; ?> required>
                        <label class="form-check-label" for="cash">Tiền mặt</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="payment_method" id="card" value="Thẻ tín dụng" <?php echo get_form_data('payment_method') === 'Thẻ tín dụng' ? 'checked' : ''; ?> required>
                        <label class="form-check-label" for="card">Thẻ tín dụng</label>
                    </div>
                </div>
                <div class="mt-3">
                    <p class="text-end"><strong>Tổng tiền: 
                        <?php 
                        echo get_form_data('test_type') === 'Xét nghiệm AMH' ? '500,000đ' : '300,000đ'; 
                        ?>
                    </strong></p>
                </div>
            </div>
        <?php endif; ?>

        <div class="d-flex justify-content-between mt-4">
            <?php if ($_SESSION['step'] > 1): ?>
                <button type="submit" name="back" class="btn btn-secondary">Quay lại</button>
            <?php else: ?>
                <div></div>
            <?php endif; ?>
            
            <?php if ($_SESSION['step'] < 4): ?>
                <button type="submit" name="next" class="btn btn-primary">Tiếp tục</button>
            <?php else: ?>
                <button type="submit" name="confirm" class="btn btn-success">Xác nhận đăng ký</button>
            <?php endif; ?>
        </div>
    </form>

    <?php if (isset($confirmation_message)): ?>
        <div class="alert alert-success mt-4" role="alert">
            <?php echo $confirmation_message; ?>
        </div>
    <?php endif; ?>
</div>

<script>
function selectDate(date, isSelectable) {
    if (!isSelectable) return;
    document.querySelectorAll('.calendar-date').forEach(el => el.classList.remove('selected'));
    event.target.classList.add('selected');
    document.getElementById('selected_date').value = date;
    
    // Update the displayed selected date
    var formattedDate = new Date(date).toLocaleDateString('vi-VN', { day: '2-digit', month: '2-digit', year: 'numeric' });
    var alertDiv = document.querySelector('.alert-info');
    if (alertDiv) {
        alertDiv.textContent = 'Ngày đã chọn: ' + formattedDate;
    } else {
        alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-info mb-3';
        alertDiv.textContent = 'Ngày đã chọn: ' + formattedDate;
        document.querySelector('.calendar-container').insertBefore(alertDiv, document.querySelector('.calendar-header'));
    }
}

function changeMonth(increment) {
    var currentMonth = parseInt(document.getElementById('current_month').value);
    var currentYear = parseInt(document.getElementById('current_year').value);
    
    currentMonth += increment;
    
    if (currentMonth > 12) {
        currentMonth = 1;
        currentYear++;
    } else if (currentMonth < 1) {
        currentMonth = 12;
        currentYear--;
    }
    
    document.getElementById('current_month').value = currentMonth;
    document.getElementById('current_year').value = currentYear;
    
    document.querySelector('form').submit();
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>