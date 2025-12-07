<?php
// Đây là file C:\xampp\htdocs\KLTN_Benhvien\mvc\views\pages\bs_chat_benhnhan.php
$maCuocTrove = isset($data["MaCuocTrove"]) ? (int)$data["MaCuocTrove"] : 0;
$header      = isset($data["Header"]) ? $data["Header"] : null;
$messages    = isset($data["Messages"]) ? $data["Messages"] : [];

// Lấy MaTinNhan lớn nhất để phục vụ polling
$lastId = 0;
foreach ($messages as $msg) {
    if ($msg["MaTinNhan"] > $lastId) {
        $lastId = (int)$msg["MaTinNhan"];
    }
}
?>

<div class="bs-chat-wrapper">
    <style>
        /* ==== UI chat bác sĩ - bệnh nhân ==== */
        .bs-chat-wrapper {
            margin-top: 8px;
        }

        .bs-chat-card {
            background: #ffffff;
            border-radius: 18px;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.16);
            padding: 14px 14px 16px;
            border: 1px solid rgba(148, 163, 184, 0.25);
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .bs-chat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 8px;
        }

        .bs-chat-header-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .bs-chat-avatar {
            width: 40px;
            height: 40px;
            border-radius: 999px;
            background: linear-gradient(135deg, #0ea5e9, #2563eb);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 20px;
            flex-shrink: 0;
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.55);
        }

        .bs-chat-header-text h3 {
            margin: 0;
            font-size: 17px;
            font-weight: 700;
            color: #0f172a;
        }

        .bs-chat-header-text p {
            margin: 2px 0 0;
            font-size: 13px;
            color: #6b7280;
        }

        .bs-chat-header-meta {
            text-align: right;
            font-size: 12px;
            color: #6b7280;
        }

        .bs-chat-body {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        /* Khung chat cuộn */
        #chatBoxBS {
            border-radius: 14px;
            padding: 10px 10px 12px;
            height: 420px;
            overflow-y: auto;
            background: radial-gradient(circle at top left, #eff6ff 0, #ffffff 50%);
            border: 1px solid #e5e7eb;
        }

        /* Hàng tin nhắn */
        .bs-msg-row {
            display: flex;
            flex-direction: column;
            margin-bottom: 8px;
            width: 100%; /* tránh chiếm full theo kiểu kỳ lạ */
        }

        .bs-msg-row-doctor {
            align-items: flex-end;
        }

        .bs-msg-row-patient {
            align-items: flex-start;
        }

        .bs-msg-meta {
            font-size: 11px;
            color: #6b7280;
            margin-bottom: 2px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .bs-msg-meta .badge {
            font-size: 11px;
            padding: 4px 8px;
            border-radius: 999px;
        }

        .bs-msg-time {
            color: #9ca3af;
        }

        /* Bubble tin nhắn – FIX: chỉ ôm sát nội dung, không dư */
        .bs-msg-bubble {
            display: inline-block;        /* ôm vừa nội dung */
            max-width: 80%;              /* giới hạn để không tràn ngang */
            border-radius: 14px;
            padding: 8px 10px;
            font-size: 13px;
            line-height: 1.5;
            box-shadow: 0 4px 10px rgba(15, 23, 42, 0.16);
            word-break: break-word;
            white-space: normal;         /* không pre-wrap để tránh kéo dài khối */
        }

        .bs-msg-bubble-doctor {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #f9fafb;
            border-bottom-right-radius: 4px;
        }

        .bs-msg-bubble-patient {
            background: #f3f4f6;
            color: #111827;
            border-bottom-left-radius: 4px;
        }

        .bs-msg-attachment {
            margin-top: 4px;
            font-size: 12px;
        }

        .bs-msg-attachment a {
            text-decoration: none;
            font-weight: 500;
        }

        .bs-msg-attachment img {
            max-width: 200px;
            max-height: 200px;
            border-radius: 6px;
            margin-top: 3px;
            display: block;
        }

        /* Nhập tin nhắn */
        .bs-chat-input-area {
            margin-top: 8px;
            border-top: 1px solid #e5e7eb;
            padding-top: 8px;
        }

        .bs-chat-input-row {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: flex-end;
        }

        .bs-chat-input-row textarea {
            flex: 1;
            min-height: 60px;
            max-height: 120px;
            resize: vertical;
            border-radius: 12px;
            font-size: 13px;
        }

        .bs-chat-input-actions {
            display: flex;
            flex-direction: column;
            gap: 6px;
            min-width: 140px;
        }

        .bs-chat-input-file input[type="file"] {
            font-size: 12px;
            padding: 4px 6px;
            border-radius: 999px;
        }

        .bs-chat-input-file label {
            font-size: 12px;
            color: #6b7280;
        }

        .bs-chat-send-btn {
            border-radius: 999px;
            padding-inline: 18px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .bs-chat-note {
            margin-top: 5px;
            font-size: 11px;
            color: #9ca3af;
        }

        @media (max-width: 768px) {
            .bs-chat-card {
                padding: 10px 10px 12px;
                border-radius: 14px;
            }

            .bs-chat-input-row {
                flex-direction: column;
                align-items: stretch;
            }

            .bs-chat-input-actions {
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
                min-width: 0;
            }

            .bs-chat-send-btn {
                flex: 0 0 auto;
            }
        }
    </style>

    <div class="bs-chat-card">
        <div class="bs-chat-header">
            <div class="bs-chat-header-left">
                <div class="bs-chat-avatar">💬</div>
                <div class="bs-chat-header-text">
                    <h3>Chat với bệnh nhân</h3>
                    <?php if ($header): ?>
                        <p>
                            <strong><?php echo htmlspecialchars($header["TenBenhNhan"]); ?></strong>
                            <?php if (!empty($header["MaBN"])): ?>
                                · Mã BN: <?php echo htmlspecialchars($header["MaBN"]); ?>
                            <?php endif; ?>
                            <?php if (!empty($header["SoDT"])): ?>
                                · SĐT: <?php echo htmlspecialchars($header["SoDT"]); ?>
                            <?php endif; ?>
                        </p>
                    <?php else: ?>
                        <p>Trao đổi thông tin khám, dặn dò và gửi kết quả cho bệnh nhân.</p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="bs-chat-header-meta">
                <div>🩺 Bác sĩ đang trực tuyến</div>
                <div style="font-size:11px;">Tin nhắn mới sẽ hiển thị tự động.</div>
            </div>
        </div>

        <div class="bs-chat-body">
            <div id="chatBoxBS">
                <?php if (empty($messages)): ?>
                    <p class="text-muted" style="font-size:13px;">
                        Chưa có tin nhắn nào. Hãy gửi lời chào đầu tiên cho bệnh nhân.
                    </p>
                <?php else: ?>
                    <?php foreach ($messages as $msg): ?>
                        <?php
                        $isBS    = ($msg["NguoiGuiLoai"] === 'BS');
                        $rowCls  = $isBS ? "bs-msg-row bs-msg-row-doctor" : "bs-msg-row bs-msg-row-patient";
                        $badge   = $isBS ? "Bạn" : "Bệnh nhân";
                        $badgeClass = $isBS ? "primary" : "secondary";

                        $filePath  = isset($msg["FilePath"]) ? $msg["FilePath"] : null;
                        $fileName  = isset($msg["FileNameGoc"]) ? $msg["FileNameGoc"] : null;
                        $isImage   = isset($msg["IsImage"]) ? (int)$msg["IsImage"] : 0;
                        ?>
                        <div class="<?php echo $rowCls; ?>">
                            <div class="bs-msg-meta">
                                <span class="badge bg-<?php echo $badgeClass; ?>">
                                    <?php echo $badge; ?>
                                </span>
                                <span class="bs-msg-time">
                                    <?php echo htmlspecialchars($msg["ThoiGianGui"]); ?>
                                </span>
                            </div>

                            <?php if (!empty($msg["NoiDung"])): ?>
                                <div class="bs-msg-bubble <?php echo $isBS ? 'bs-msg-bubble-doctor' : 'bs-msg-bubble-patient'; ?>">
                                    <?php echo nl2br(htmlspecialchars($msg["NoiDung"])); ?>

                                    <?php if (!empty($filePath) && !empty($fileName)): ?>
                                        <div class="bs-msg-attachment">
                                            <?php if ($isImage === 1): ?>
                                                <a href="<?php echo htmlspecialchars($filePath); ?>"
                                                   target="_blank"
                                                   class="text-light text-decoration-underline">
                                                    Xem ảnh: <?php echo htmlspecialchars($fileName); ?>
                                                </a>
                                                <img src="<?php echo htmlspecialchars($filePath); ?>"
                                                     alt="<?php echo htmlspecialchars($fileName); ?>">
                                            <?php else: ?>
                                                <a href="<?php echo htmlspecialchars($filePath); ?>"
                                                   target="_blank"
                                                   class="<?php echo $isBS ? 'text-light' : 'text-primary'; ?> text-decoration-underline">
                                                    Tải file: <?php echo htmlspecialchars($fileName); ?>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php elseif (!empty($filePath) && !empty($fileName)): ?>
                                <!-- Trường hợp chỉ gửi file -->
                                <div class="bs-msg-bubble <?php echo $isBS ? 'bs-msg-bubble-doctor' : 'bs-msg-bubble-patient'; ?>">
                                    <div class="bs-msg-attachment">
                                        <?php if ($isImage === 1): ?>
                                            <a href="<?php echo htmlspecialchars($filePath); ?>"
                                               target="_blank"
                                               class="text-light text-decoration-underline">
                                                Xem ảnh: <?php echo htmlspecialchars($fileName); ?>
                                            </a>
                                            <img src="<?php echo htmlspecialchars($filePath); ?>"
                                                 alt="<?php echo htmlspecialchars($fileName); ?>">
                                        <?php else: ?>
                                            <a href="<?php echo htmlspecialchars($filePath); ?>"
                                               target="_blank"
                                               class="<?php echo $isBS ? 'text-light' : 'text-primary'; ?> text-decoration-underline">
                                                Tải file: <?php echo htmlspecialchars($fileName); ?>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="bs-chat-input-area">
                <form id="formSendBS" onsubmit="return false;" enctype="multipart/form-data">
                    <input type="hidden" id="MaCuocTroveBS" value="<?php echo $maCuocTrove; ?>">
                    <input type="hidden" id="LastIdBS" value="<?php echo $lastId; ?>">

                    <div class="bs-chat-input-row">
                        <textarea id="NoiDungBS" class="form-control"
                                  rows="2"
                                  placeholder="Nhập nội dung trả lời bệnh nhân..."></textarea>

                        <div class="bs-chat-input-actions">
                            <div class="bs-chat-input-file">
                                <input type="file" id="ChatFileBS" name="FileDinhKem" class="form-control form-control-sm">
                                <label for="ChatFileBS">Có thể đính kèm ảnh hoặc file kết quả.</label>
                            </div>
                            <button type="button"
                                    class="btn btn-primary bs-chat-send-btn"
                                    onclick="sendMessageBS();">
                                <span>📨 Gửi</span>
                            </button>
                        </div>
                    </div>

                    <div class="bs-chat-note">
                        💡 Lưu ý: Không gửi thông tin nhạy cảm (mật khẩu, OTP...) qua khung chat này.
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// NhatCuong: tự động scroll xuống cuối khi load trang
document.addEventListener('DOMContentLoaded', function () {
    var chatBox = document.getElementById('chatBoxBS');
    if (chatBox) {
        chatBox.scrollTop = chatBox.scrollHeight;
    }
});

// Gửi tin nhắn từ BS (có thể kèm file)
function sendMessageBS() {
    var maCuoc = document.getElementById('MaCuocTroveBS').value;
    var noiDung = document.getElementById('NoiDungBS').value.trim();
    var fileInput = document.getElementById('ChatFileBS');

    if (!noiDung && (!fileInput.files || fileInput.files.length === 0)) {
        alert('Vui lòng nhập nội dung hoặc chọn file.');
        return;
    }

    var formData = new FormData();
    formData.append('MaCuocTrove', maCuoc);
    formData.append('NoiDung', noiDung);
    if (fileInput.files && fileInput.files.length > 0) {
        formData.append('FileDinhKem', fileInput.files[0]);
    }

    fetch('/KLTN_Benhvien/Bacsi/AjaxGuiTinNhanBS', {
        method: 'POST',
        body: formData
    })
    .then(function(res) { return res.json(); })
    .then(function(json) {
        if (!json.success) {
            alert(json.message || 'Gửi tin nhắn thất bại.');
        } else {
            document.getElementById('NoiDungBS').value = '';
            if (fileInput) {
                fileInput.value = '';
            }
            // Sau khi gửi, chờ polling lôi tin mới về rồi scroll xuống
            var chatBox = document.getElementById('chatBoxBS');
            if (chatBox) {
                chatBox.scrollTop = chatBox.scrollHeight;
            }
        }
    })
    .catch(function(err) {
        console.error(err);
        alert('Lỗi kết nối server.');
    });
}

// Polling 1 giây để lấy tin mới
function fetchNewMessagesBS() {
    var maCuocEl = document.getElementById('MaCuocTroveBS');
    var lastIdEl = document.getElementById('LastIdBS');
    if (!maCuocEl || !lastIdEl) return;

    var maCuoc = maCuocEl.value;
    var lastId = lastIdEl.value;

    var formData = new FormData();
    formData.append('MaCuocTrove', maCuoc);
    formData.append('LastId', lastId);

    fetch('/KLTN_Benhvien/Bacsi/AjaxFetchTinNhanBS', {
        method: 'POST',
        body: formData
    })
    .then(function(res) { return res.json(); })
    .then(function(json) {
        if (!json.success) {
            return;
        }
        var msgs = json.messages || [];
        if (msgs.length === 0) return;

        var chatBox = document.getElementById('chatBoxBS');
        var currentLastId = parseInt(lastId, 10) || 0;

        msgs.forEach(function(msg) {
            var isBS  = (msg.NguoiGuiLoai === 'BS');
            var rowCls = isBS ? 'bs-msg-row bs-msg-row-doctor' : 'bs-msg-row bs-msg-row-patient';
            var badge = isBS ? 'Bạn' : 'Bệnh nhân';
            var badgeClass = isBS ? 'primary' : 'secondary';

            var row = document.createElement('div');
            row.className = rowCls;

            // Meta
            var meta = document.createElement('div');
            meta.className = 'bs-msg-meta';

            var badgeSpan = document.createElement('span');
            badgeSpan.className = 'badge bg-' + badgeClass;
            badgeSpan.textContent = badge;

            var timeSpan = document.createElement('span');
            timeSpan.className = 'bs-msg-time';
            timeSpan.textContent = msg.ThoiGianGui || '';

            meta.appendChild(badgeSpan);
            meta.appendChild(timeSpan);
            row.appendChild(meta);

            // Bubble
            var bubble = document.createElement('div');
            bubble.className = 'bs-msg-bubble ' + (isBS ? 'bs-msg-bubble-doctor' : 'bs-msg-bubble-patient');

            var hasText = msg.NoiDung && msg.NoiDung.trim().length > 0;
            if (hasText) {
                var safe = msg.NoiDung
                    ? msg.NoiDung
                        .replace(/&/g, "&amp;")
                        .replace(/</g, "&lt;")
                        .replace(/>/g, "&gt;")
                        .replace(/\n/g, "<br>")
                    : '';
                var textDiv = document.createElement('div');
                textDiv.innerHTML = safe;
                bubble.appendChild(textDiv);
            }

            // File đính kèm
            if (msg.FilePath && msg.FileNameGoc) {
                var fileWrap = document.createElement('div');
                fileWrap.className = 'bs-msg-attachment';

                var link = document.createElement('a');
                link.href = msg.FilePath;
                link.target = '_blank';

                var isImg = msg.IsImage && parseInt(msg.IsImage, 10) === 1;
                link.textContent = isImg
                    ? ('Xem ảnh: ' + msg.FileNameGoc)
                    : ('Tải file: ' + msg.FileNameGoc);

                if (isBS) {
                    link.className = 'text-light text-decoration-underline';
                } else {
                    link.className = 'text-primary text-decoration-underline';
                }

                fileWrap.appendChild(link);

                if (isImg) {
                    var img = document.createElement('img');
                    img.src = msg.FilePath;
                    img.alt = msg.FileNameGoc;
                    img.style.maxWidth = '200px';
                    img.style.maxHeight = '200px';
                    img.style.borderRadius = '6px';
                    img.style.marginTop = '3px';
                    fileWrap.appendChild(img);
                }

                bubble.appendChild(fileWrap);
            }

            row.appendChild(bubble);
            if (chatBox) {
                chatBox.appendChild(row);
            }

            if (msg.MaTinNhan > currentLastId) {
                currentLastId = msg.MaTinNhan;
            }
        });

        lastIdEl.value = currentLastId;
        if (chatBox) {
            chatBox.scrollTop = chatBox.scrollHeight;
        }
    })
    .catch(function(err) {
        console.error(err);
    });
}

// NhatCuong: giữ polling 1 giây như cũ
setInterval(fetchNewMessagesBS, 1000);
</script>
