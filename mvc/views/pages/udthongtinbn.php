<?php 
    // Lấy dữ liệu đang chỉnh sửa
    $ud = isset($data["UD"]) ? json_decode($data["UD"], true) : [];

    if (!empty($ud)):
?>
<style>
    /* Wrapper căn giữa trong khu vực bệnh nhân */
    .bn-update-wrapper {
        max-width: 800px;
        margin: 0 auto;
    }

    .bn-update-card {
        background-color: #ffffff;
        padding: 24px 24px 28px;
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.12);
        border: 1px solid #e5e7eb;
        margin-bottom: 20px;
    }

    .bn-update-title {
        font-size: 1.25rem;
        font-weight: 600;
        text-align: center;
        margin-bottom: 20px;
        color: #111827;
    }

    .bn-update-form {
        width: 100%;
    }

    .bn-update-group {
        margin-bottom: 14px;
    }

    .bn-update-label {
        display: block;
        font-weight: 600;
        color: #374151;
        margin-bottom: 6px;
        font-size: 0.95rem;
    }

    .bn-update-card .form-control {
        border-radius: 8px;
        padding: 9px 11px;
        border: 1px solid #d1d5db;
        font-size: 0.95rem;
        box-shadow: none;
    }

    .bn-update-card .form-control:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.18);
        outline: none;
    }

    .bn-update-static {
        padding: 9px 11px;
        border-radius: 8px;
        background: #f3f4f6;
        border: 1px solid #e5e7eb;
        font-size: 0.95rem;
        color: #111827;
    }

    /* Nhóm giới tính */
    .bn-update-gender-wrapper {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        margin-top: 4px;
    }

    .bn-update-gender-item {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.95rem;
    }

    .bn-update-gender-item input[type="radio"] {
        margin: 0;
        cursor: pointer;
    }

    /* Vùng nút hành động */
    .bn-update-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 18px;
    }

    .bn-update-actions .btn {
        min-width: 110px;
        border-radius: 999px;
        font-weight: 500;
        padding: 8px 18px;
        font-size: 0.95rem;
    }

    .bn-update-actions .btn-secondary {
        background-color: #6b7280;
        border: none;
    }

    .bn-update-actions .btn-primary {
        background-color: #2563eb;
        border: none;
    }

    .bn-update-actions .btn:hover {
        opacity: 0.92;
    }

    .bn-update-actions .btn:focus-visible {
        outline: 2px solid #1d4ed8;
        outline-offset: 2px;
    }

    @media (max-width: 576px) {
        .bn-update-card {
            padding: 18px 16px 22px;
        }

        .bn-update-actions {
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .bn-update-actions .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="bn-update-wrapper">
    <?php foreach ($ud as $r):

        $hoTen        = isset($r["HovaTen"]) ? htmlspecialchars($r["HovaTen"]) : "";
        $soDT         = isset($r["SoDT"]) ? htmlspecialchars($r["SoDT"]) : "";
        $ngaySinhValue= !empty($r["NgaySinh"]) ? htmlspecialchars($r["NgaySinh"]) : "";
        $gioiTinh     = isset($r["GioiTinh"]) ? $r["GioiTinh"] : "";
        $bhyt         = isset($r["BHYT"]) ? htmlspecialchars($r["BHYT"]) : "";
        $email        = isset($r["Email"]) ? htmlspecialchars($r["Email"]) : "";
        $diaChi       = isset($r["DiaChi"]) ? htmlspecialchars($r["DiaChi"]) : "";
    ?>
        <div class="bn-update-card">
            <h4 class="bn-update-title">Điều chỉnh thông tin</h4>

            <form class="bn-update-form" action="/KLTN_Benhvien/BN/UDTT" method="POST">
                <div class="bn-update-group">
                    <label for="fullName" class="bn-update-label">Họ và tên *</label>
                    <input type="text"
                           name="hovaten"
                           class="form-control"
                           id="fullName"
                           value="<?php echo $hoTen; ?>"
                           required>
                </div>

                <div class="bn-update-group">
                    <label class="bn-update-label" for="phone">Số điện thoại *</label>
                    <div id="phone" class="bn-update-static">
                        <?php echo $soDT; ?>
                    </div>
                </div>

                <div class="bn-update-group">
                    <label for="dob" class="bn-update-label">Ngày sinh *</label>
                    <input type="date"
                           name="ngaysinh"
                           class="form-control"
                           id="dob"
                           value="<?php echo $ngaySinhValue; ?>"
                           required>
                </div>

                <div class="bn-update-group">
                    <span class="bn-update-label">Giới tính *</span>
                    <div class="bn-update-gender-wrapper">
                        <?php
                            $genders = ['Nam', 'Nữ'];
                            foreach ($genders as $gender):
                                $checked  = ($gender === $gioiTinh) ? 'checked' : '';
                                $genderId = 'gender_' . $gender;
                        ?>
                            <label class="bn-update-gender-item" for="<?php echo $genderId; ?>">
                                <input type="radio"
                                       name="gt"
                                       id="<?php echo $genderId; ?>"
                                       value="<?php echo $gender; ?>"
                                       <?php echo $checked; ?>
                                       required>
                                <span><?php echo $gender; ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="bn-update-group">
                    <label for="insurance" class="bn-update-label">Mã thẻ BHYT</label>
                    <input type="text"
                           name="bhyt"
                           class="form-control"
                           id="insurance"
                           value="<?php echo $bhyt; ?>">
                </div>

                <div class="bn-update-group">
                    <label for="email" class="bn-update-label">Email</label>
                    <input type="email"
                           name="email"
                           class="form-control"
                           id="email"
                           value="<?php echo $email; ?>"
                           required>
                </div>

                <div class="bn-update-group">
                    <label for="diachi" class="bn-update-label">Địa chỉ</label>
                    <input type="text"
                           name="diachi"
                           class="form-control"
                           id="diachi"
                           value="<?php echo $diaChi; ?>">
                </div>

                <div class="bn-update-actions">
                    <!-- Hủy: quay về trang hồ sơ cá nhân -->
                    <button type="button"
                            class="btn btn-secondary"
                            onclick="window.location.href='/KLTN_Benhvien/BN/TTBN';">
                        Hủy
                    </button>

                    <!-- Cập nhật: submit form như cũ -->
                    <button type="submit" name="btn-updatebn" class="btn btn-primary">
                        Cập nhật
                    </button>
                </div>
            </form>
        </div>
    <?php endforeach; ?>
</div>
<?php
    else:
        echo "<p>Không tìm thấy thông tin để cập nhật.</p>";
    endif;
?>
