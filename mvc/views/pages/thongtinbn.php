<?php
    // mvc/views/pages/thongtinbn.php
    // Giao diện riêng cho trang hồ sơ bệnh nhân
?>

<?php
    // Đọc flash message từ session (nếu có)
    $bnFlash = null;
    if (isset($_SESSION["bn_flash"])) {
        $bnFlash = $_SESSION["bn_flash"];
        unset($_SESSION["bn_flash"]); // dùng 1 lần rồi xóa
    }
?>

<style>
    /* Toast nổi góc phải màn hình */
    .bn-toast {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1050;
        min-width: 260px;
        max-width: 360px;
        padding: 10px 14px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.95rem;
        font-weight: 500;
        color: #fff;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.35);
        animation: bn-toast-slide-in 0.25s ease-out;
    }

    .bn-toast-success {
        background: linear-gradient(135deg, #16a34a, #22c55e);
    }

    .bn-toast-danger {
        background: linear-gradient(135deg, #dc2626, #ef4444);
    }

    .bn-toast-icon {
        font-size: 1.1rem;
    }

    .bn-toast-message {
        flex: 1;
    }

    @keyframes bn-toast-slide-in {
        from {
            opacity: 0;
            transform: translateX(16px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    /* Giao diện hồ sơ bệnh nhân */
    .bn-profile-wrapper {
        max-width: 800px;
        margin: 0 auto;
    }

    .bn-profile-container {
        background: #ffffff;
        border-radius: 16px;
        padding: 24px 20px;
        margin-bottom: 20px;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.12);
        border: 1px solid #e5e7eb;
    }

    .bn-profile-header {
        display: flex;
        align-items: center;
        gap: 16px;
        padding-bottom: 16px;
        border-bottom: 1px solid #e5e7eb;
        margin-bottom: 16px;
    }

    .bn-profile-avatar {
        width: 56px;
        height: 56px;
        border-radius: 999px;
        background: linear-gradient(135deg, #2563eb, #3b82f6);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        color: #ffffff;
        flex-shrink: 0;
    }

    .bn-profile-header h5 {
        font-size: 1.1rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 4px;
    }

    .bn-profile-header small {
        font-size: 0.85rem;
        color: #6b7280;
    }

    .bn-profile-section {
        background: #f9fafb;
        border-radius: 12px;
        padding: 16px 14px;
        margin-bottom: 16px;
    }

    .bn-profile-section-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 10px;
    }

    .bn-profile-row {
        display: grid;
        grid-template-columns: minmax(0, 1.2fr) minmax(0, 2fr);
        padding: 6px 0;
        font-size: 0.92rem;
    }

    .bn-profile-label {
        color: #6b7280;
    }

    .bn-profile-value {
        text-align: right;
        color: #111827;
        font-weight: 500;
        word-break: break-word;
    }

    .bn-profile-edit {
        display: flex;
        justify-content: flex-end;
        margin-top: 8px;
    }

    .bn-profile-edit-btn {
        border-radius: 999px;
        padding-left: 18px;
        padding-right: 18px;
    }

    @media (max-width: 576px) {
        .bn-profile-container {
            padding: 18px 14px;
        }

        .bn-profile-header {
            align-items: flex-start;
        }

        .bn-profile-row {
            grid-template-columns: 1.3fr 1.7fr;
            column-gap: 8px;
        }

        .bn-profile-value {
            text-align: left;
        }

        .bn-toast {
            left: 10px;
            right: 10px;
        }
    }
</style>

<?php if (!empty($bnFlash)): ?>
    <?php
        $msg  = isset($bnFlash["message"]) ? htmlspecialchars($bnFlash["message"]) : "";
        $ok   = !empty($bnFlash["success"]);
        $type = $ok ? "bn-toast-success" : "bn-toast-danger";
        $icon = $ok ? "✔️" : "⚠️";
    ?>
    <div id="bn-toast-flash" class="bn-toast <?php echo $type; ?>">
        <span class="bn-toast-icon"><?php echo $icon; ?></span>
        <span class="bn-toast-message"><?php echo $msg; ?></span>
    </div>
    <script>
        setTimeout(() => {
            const t = document.getElementById("bn-toast-flash");
            if (t) {
                t.style.transition = "0.4s";
                t.style.opacity = "0";
                t.style.transform = "translateX(10px)";
                setTimeout(() => t.remove(), 400);
            }
        }, 3000);
    </script>
<?php endif; ?>

<?php
    $tt = json_decode($data["TT"], true);

    if (!empty($tt)):
?>
<div class="bn-profile-wrapper">
    <?php foreach ($tt as $r):

        // --- Ép kiểu ngày sinh dd-mm-yyyy ---
        $ngaySinhFormatted = "";
        if (!empty($r["NgaySinh"])) {
            $ngaySinhFormatted = date("d-m-Y", strtotime($r["NgaySinh"]));
        }
    ?>
        <div class="bn-profile-container">
            <div class="bn-profile-header">
                <div class="bn-profile-avatar">👤</div>
                <div>
                    <h5><?php echo htmlspecialchars($r["HovaTen"]); ?></h5>
                    <small>Mã BN: <?php echo htmlspecialchars($r["MaBN"]); ?></small>
                </div>
            </div>

            <div class="bn-profile-section">
                <div class="bn-profile-section-title">Thông tin cơ bản</div>

                <div class="bn-profile-row">
                    <span class="bn-profile-label">Họ và tên</span>
                    <span class="bn-profile-value">
                        <?php echo htmlspecialchars($r["HovaTen"]); ?>
                    </span>
                </div>

                <div class="bn-profile-row">
                    <span class="bn-profile-label">Điện thoại</span>
                    <span class="bn-profile-value">
                        <?php echo htmlspecialchars($r["SoDT"]); ?>
                    </span>
                </div>

                <div class="bn-profile-row">
                    <span class="bn-profile-label">Giới tính</span>
                    <span class="bn-profile-value">
                        <?php echo htmlspecialchars($r["GioiTinh"]); ?>
                    </span>
                </div>

                <div class="bn-profile-row">
                    <span class="bn-profile-label">Ngày sinh</span>
                    <span class="bn-profile-value">
                        <?php echo htmlspecialchars($ngaySinhFormatted); ?>
                    </span>
                </div>

                <div class="bn-profile-row">
                    <span class="bn-profile-label">Địa chỉ</span>
                    <span class="bn-profile-value">
                        <?php echo htmlspecialchars($r["DiaChi"]); ?>
                    </span>
                </div>
            </div>

            <div class="bn-profile-section">
                <div class="bn-profile-section-title">Thông tin bổ sung</div>

                <div class="bn-profile-row">
                    <span class="bn-profile-label">Mã BHYT</span>
                    <span class="bn-profile-value">
                        <?php echo htmlspecialchars($r["BHYT"]); ?>
                    </span>
                </div>

                <div class="bn-profile-row">
                    <span class="bn-profile-label">Email</span>
                    <span class="bn-profile-value">
                        <?php echo htmlspecialchars($r["Email"]); ?>
                    </span>
                </div>
            </div>

            <div class="bn-profile-edit">
                <form action="/KLTN_Benhvien/BN/UDTT" method="post">
                    <button type="submit"
                            class="btn btn-primary bn-profile-edit-btn"
                            name="btnUDTT">
                        Thay đổi thông tin
                    </button>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php
    else:
        echo "<p>Không tìm thấy thông tin bệnh nhân.</p>";
    endif;
?>
