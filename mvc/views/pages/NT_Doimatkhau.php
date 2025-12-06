<div class="nt-change-password-container">
    <!-- CSS riêng cho màn Đổi mật khẩu NVNT -->
    <style>
        .nt-change-password-container {
            padding: 16px 20px;
        }

        .nt-change-card {
            border-radius: 20px;
            border: none;
            box-shadow: 0 6px 18px rgba(0, 132, 116, 0.18);
            overflow: hidden;
        }

        .nt-change-header {
            background: linear-gradient(135deg, #0c857d, #12b3a5);
            color: #ffffff;
            padding: 18px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .nt-change-header-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .nt-change-icon {
            width: 40px;
            height: 40px;
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.16);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .nt-change-header-text h3 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
        }

        .nt-change-header-text p {
            margin: 2px 0 0 0;
            font-size: 13px;
            opacity: 0.9;
        }

        .nt-change-header-right {
            text-align: right;
            font-size: 12px;
            opacity: 0.9;
        }

        .nt-change-body {
            padding: 20px 24px 22px 24px;
            background: #f5fbfa;
        }

        .nt-label {
            font-size: 12px;
            font-weight: 600;
            color: #555;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .nt-input-control {
            border-radius: 10px;
            border: 1px solid #d3e9e6;
            font-size: 14px;
        }

        .nt-input-control:focus {
            border-color: #0c857d;
            box-shadow: 0 0 0 0.15rem rgba(12, 133, 125, 0.2);
        }

        .nt-password-group {
            position: relative;
        }

        .nt-toggle-password {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            cursor: pointer;
            font-size: 13px;
            color: #0c857d;
            padding: 2px 4px;
        }

        .nt-toggle-password:hover {
            text-decoration: underline;
        }

        .nt-hint {
            font-size: 12px;
            color: #777;
            margin-top: 4px;
        }

        .nt-error-text {
            font-size: 12px;
        }

        .nt-change-actions {
            margin-top: 12px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .nt-change-actions .btn-primary {
            border-radius: 999px;
            padding-inline: 18px;
            background: linear-gradient(135deg, #0c857d, #12b3a5);
            border: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .nt-change-actions .btn-primary:hover {
            background: linear-gradient(135deg, #0a6d67, #0fa293);
        }

        .nt-alert-info {
            font-size: 12px;
            color: #555;
            margin-top: 6px;
        }

        .nt-badge-tip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 999px;
            background: #e1fff6;
            border: 1px solid #0c857d;
            font-size: 11px;
            color: #055a54;
            margin-top: 6px;
        }

        @media (max-width: 576px) {
            .nt-change-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .nt-change-header-right {
                text-align: left;
            }
        }
    </style>

    <?php if (!empty($data["error"])): ?>
        <div class="alert alert-danger mb-3">
            <?php echo htmlspecialchars($data["error"]); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($data["success"])): ?>
        <div class="alert alert-success mb-3">
            <?php echo htmlspecialchars($data["success"]); ?>
        </div>
    <?php endif; ?>

    <div class="card nt-change-card">
        <!-- HEADER -->
        <div class="nt-change-header">
            <div class="nt-change-header-left">
                <div class="nt-change-icon">🔐</div>
                <div class="nt-change-header-text">
                    <h3>Đổi mật khẩu</h3>
                    <p>Bảo vệ tài khoản nhân viên nhà thuốc, tránh lộ thông tin ca trực & đơn thuốc.</p>
                </div>
            </div>
            <div class="nt-change-header-right">
                <div>💊 Tài khoản: Nhân viên nhà thuốc</div>
                <div>🔁 Nên đổi mật khẩu định kỳ 3 tháng/lần</div>
            </div>
        </div>

        <!-- BODY -->
        <div class="nt-change-body">
            <form method="POST" id="formDoiMatKhau" novalidate>
                <!-- MẬT KHẨU HIỆN TẠI -->
                <div class="mb-3">
                    <label for="old_password" class="nt-label">Mật khẩu hiện tại</label>
                    <div class="nt-password-group">
                        <input type="password"
                               class="form-control nt-input-control"
                               name="old_password"
                               id="old_password"
                               placeholder="Nhập mật khẩu hiện tại">
                        <button type="button"
                                class="nt-toggle-password"
                                data-target="old_password">
                            Hiện
                        </button>
                    </div>
                    <small id="old_password_error"
                           class="text-danger nt-error-text"></small>
                </div>

                <!-- MẬT KHẨU MỚI -->
                <div class="mb-3">
                    <label for="new_password" class="nt-label">Mật khẩu mới</label>
                    <div class="nt-password-group">
                        <input type="password"
                               class="form-control nt-input-control"
                               name="new_password"
                               id="new_password"
                               placeholder="Mật khẩu mới phải dài hơn 8 ký tự">
                        <button type="button"
                                class="nt-toggle-password"
                                data-target="new_password">
                            Hiện
                        </button>
                    </div>
                    <small id="new_password_error"
                           class="text-danger nt-error-text"></small>
                    <div class="nt-hint">
                        ✅ Gợi ý: kết hợp chữ hoa, chữ thường, số & ký tự đặc biệt để tăng độ mạnh.
                    </div>
                </div>

                <!-- NHẬP LẠI MẬT KHẨU MỚI -->
                <div class="mb-3">
                    <label for="confirm_password" class="nt-label">Nhập lại mật khẩu mới</label>
                    <div class="nt-password-group">
                        <input type="password"
                               class="form-control nt-input-control"
                               name="confirm_password"
                               id="confirm_password"
                               placeholder="Nhập lại mật khẩu mới">
                        <button type="button"
                                class="nt-toggle-password"
                                data-target="confirm_password">
                            Hiện
                        </button>
                    </div>
                    <small id="confirm_password_error"
                           class="text-danger nt-error-text"></small>
                </div>

                <div class="nt-change-actions">
                    <button type="submit" class="btn btn-primary" name="btnDoiMK">
                        💾 Đổi mật khẩu
                    </button>
                </div>

                <div class="nt-alert-info">
                    <div class="nt-badge-tip">
                        <span>🛡️</span>
                        <span>Không chia sẻ mật khẩu cho bất kỳ ai, kể cả nội bộ bệnh viện.</span>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- JS VALIDATE CŨ (giữ nguyên logic, chỉ đặt vào cuối file) -->
    <script>
        // --- MỚI THÊM: toggle hiện/ẩn mật khẩu ---
        (function () {
            const toggleButtons = document.querySelectorAll('.nt-toggle-password');

            toggleButtons.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const targetId = btn.getAttribute('data-target');
                    const input = document.getElementById(targetId);

                    if (!input) return;

                    if (input.type === 'password') {
                        input.type = 'text';
                        btn.textContent = 'Ẩn';
                    } else {
                        input.type = 'password';
                        btn.textContent = 'Hiện';
                    }
                });
            });
        })();

        // --- RÀNG BUỘC PHÍA CLIENT, CẢNH BÁO ĐỎ NGAY KHI NHẬP (LOGIC GIỮ NGUYÊN) ---

        const oldPasswordInput = document.getElementById('old_password');
        const newPasswordInput = document.getElementById('new_password');
        const confirmPasswordInput = document.getElementById('confirm_password');

        const oldPasswordError = document.getElementById('old_password_error');
        const newPasswordError = document.getElementById('new_password_error');
        const confirmPasswordError = document.getElementById('confirm_password_error');

        function validateOldPassword() {
            // Theo yêu cầu: không ràng buộc độ dài, chỉ để trống cũng cho submit,
            // nhưng có thể hiển thị gợi ý nhẹ nếu muốn (ở đây để trống thì không báo lỗi).
            oldPasswordError.textContent = "";
            return true;
        }

        function validateNewPassword() {
            const value = newPasswordInput.value.trim();
            // Trên 8 ký tự => > 8
            if (value.length === 0) {
                newPasswordError.textContent = "Vui lòng nhập mật khẩu mới.";
                return false;
            }
            if (value.length <= 8) {
                newPasswordError.textContent = "Mật khẩu mới phải dài hơn 8 ký tự.";
                return false;
            }
            newPasswordError.textContent = "";
            return true;
        }

        function validateConfirmPassword() {
            const newValue = newPasswordInput.value.trim();
            const confirmValue = confirmPasswordInput.value.trim();

            if (confirmValue.length === 0) {
                confirmPasswordError.textContent = "Vui lòng nhập lại mật khẩu mới.";
                return false;
            }
            if (newValue !== confirmValue) {
                confirmPasswordError.textContent = "Mật khẩu nhập lại không khớp.";
                return false;
            }
            confirmPasswordError.textContent = "";
            return true;
        }

        // Lắng nghe sự kiện nhập để hiển thị lỗi ngay lập tức
        oldPasswordInput.addEventListener('input', validateOldPassword);
        newPasswordInput.addEventListener('input', function() {
            validateNewPassword();
            validateConfirmPassword(); // cập nhật luôn xác nhận nếu đang nhập lại
        });
        confirmPasswordInput.addEventListener('input', validateConfirmPassword);

        // Chặn submit nếu không hợp lệ
        document.getElementById('formDoiMatKhau').addEventListener('submit', function(e) {
            const v1 = validateOldPassword();
            const v2 = validateNewPassword();
            const v3 = validateConfirmPassword();

            if (!v1 || !v2 || !v3) {
                e.preventDefault();
            }
        });
    </script>
</div>
