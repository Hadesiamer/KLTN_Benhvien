<?php
//Đây là file C:\xampp\htdocs\KLTN_Benhvien\mvc\views\pages\bn_chat_bacsi.php
$maCuocTrove = isset($data["MaCuocTrove"]) ? (int)$data["MaCuocTrove"] : 0;
$maBS        = isset($data["MaBS"]) ? (int)$data["MaBS"] : 0;
$messages    = isset($data["Messages"]) ? $data["Messages"] : [];
$bacSi       = isset($data["BacSi"]) ? $data["BacSi"] : null;

$lastId = 0;
foreach ($messages as $msg) {
    if ($msg["MaTinNhan"] > $lastId) {
        $lastId = (int)$msg["MaTinNhan"];
    }
}
?>

<style>
    /* ====== KHUNG CHAT BỆNH NHÂN – CSS THUẦN, PREFIX RIÊNG X7A9 ====== */
    .bnx_chat_wrapper_x7a9 {
        width: 100%;
        box-sizing: border-box;
        padding: 10px 5px 20px 5px;
        font-family: Arial, sans-serif;
    }

    .bnx_chat_header_x7a9 {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 8px;
        color: #333333;
    }

    .bnx_chat_doctor_info_x7a9 {
        margin-bottom: 10px;
        padding: 8px 10px;
        border-left: 4px solid #0077b6;
        background-color: #f5f9ff;
        font-size: 14px;
        color: #222222;
    }

    .bnx_chat_box_x7a9 {
        border: 1px solid #dddddd;
        border-radius: 6px;
        padding: 10px;
        height: 420px; /* cao hơn 1 chút cho dễ kéo */
        overflow-y: auto;
        background-color: #fafafa;
        box-sizing: border-box;
        font-size: 14px;
    }

    .bnx_chat_empty_x7a9 {
        font-style: italic;
        color: #777777;
        font-size: 14px;
    }

    .bnx_chat_message_x7a9 {
        margin-bottom: 10px;
        clear: both;
    }

    .bnx_chat_message_x7a9.bnx_me_x7a9 {
        text-align: right;
    }

    .bnx_chat_message_x7a9.bnx_other_x7a9 {
        text-align: left;
    }

    .bnx_chat_badge_x7a9 {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 600;
        color: #ffffff;
        margin-bottom: 2px;
    }

    .bnx_chat_badge_x7a9.bnx_me_x7a9 {
        background-color: #007bff; /* bệnh nhân */
    }

    .bnx_chat_badge_x7a9.bnx_other_x7a9 {
        background-color: #6c757d; /* bác sĩ */
    }

    .bnx_chat_time_x7a9 {
        display: inline-block;
        font-size: 11px;
        color: #888888;
        margin-left: 4px;
    }

    .bnx_chat_text_x7a9 {
        margin-top: 2px;
        padding: 6px 8px;
        border-radius: 6px;
        background-color: #ffffff;
        display: inline-block;
        max-width: 80%;
        word-wrap: break-word;
        text-align: left;
    }

    .bnx_chat_message_x7a9.bnx_me_x7a9 .bnx_chat_text_x7a9 {
        background-color: #e3f2fd;
    }

    .bnx_chat_file_x7a9 {
        margin-top: 4px;
        display: inline-block;
        max-width: 80%;
        word-wrap: break-word;
    }

    .bnx_chat_file_link_x7a9 {
        font-size: 13px;
        color: #0d6efd;
        text-decoration: none;
    }

    .bnx_chat_file_link_x7a9:hover {
        text-decoration: underline;
    }

    .bnx_chat_img_preview_x7a9 {
        max-width: 200px;
        max-height: 200px;
        margin-top: 4px;
        border-radius: 4px;
        display: block;
    }

    .bnx_chat_form_x7a9 {
        margin-top: 12px;
    }

    .bnx_chat_textarea_x7a9 {
        width: 100%;
        box-sizing: border-box;
        padding: 8px;
        border-radius: 4px;
        border: 1px solid #cccccc;
        resize: vertical;
        min-height: 60px;
        font-size: 14px;
        font-family: inherit;
    }

    .bnx_chat_file_input_x7a9 {
        margin-top: 6px;
        font-size: 13px;
    }

    .bnx_chat_btn_send_x7a9 {
        margin-top: 8px;
        padding: 8px 18px;
        border: none;
        border-radius: 4px;
        background-color: #007bff;
        color: #ffffff;
        font-size: 14px;
        cursor: pointer;
    }

    .bnx_chat_btn_send_x7a9:hover {
        background-color: #0056b3;
    }
</style>

<div class="bnx_chat_wrapper_x7a9">
    <div class="bnx_chat_header_x7a9">Chat với bác sĩ</div>

    <?php if ($bacSi): ?>
        <div class="bnx_chat_doctor_info_x7a9">
            <div><strong>BS. <?php echo htmlspecialchars($bacSi["HovaTen"]); ?></strong></div>
            <div>Khoa: <?php echo htmlspecialchars($bacSi["TenKhoa"]); ?></div>
        </div>
    <?php endif; ?>

    <div id="chatBox" class="bnx_chat_box_x7a9">
        <?php if (empty($messages)): ?>
            <p class="bnx_chat_empty_x7a9">Chưa có tin nhắn nào. Hãy gửi lời chào bác sĩ nhé.</p>
        <?php else: ?>
            <?php foreach ($messages as $msg): ?>
                <?php
                $isBN   = ($msg["NguoiGuiLoai"] === 'BN');
                $rowCls = $isBN ? "bnx_me_x7a9" : "bnx_other_x7a9";
                $badge  = $isBN ? "Bạn" : "Bác sĩ";

                $filePath  = isset($msg["FilePath"]) ? $msg["FilePath"] : null;
                $fileName  = isset($msg["FileNameGoc"]) ? $msg["FileNameGoc"] : null;
                $isImage   = isset($msg["IsImage"]) ? (int)$msg["IsImage"] : 0;
                ?>
                <div class="bnx_chat_message_x7a9 <?php echo $rowCls; ?>">
                    <span class="bnx_chat_badge_x7a9 <?php echo $rowCls; ?>">
                        <?php echo $badge; ?>
                    </span>
                    <span class="bnx_chat_time_x7a9">
                        <?php echo htmlspecialchars($msg["ThoiGianGui"]); ?>
                    </span>
                    <?php if (!empty($msg["NoiDung"])): ?>
                        <div class="bnx_chat_text_x7a9">
                            <?php echo nl2br(htmlspecialchars($msg["NoiDung"])); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($filePath) && !empty($fileName)): ?>
                        <div class="bnx_chat_file_x7a9">
                            <?php if ($isImage === 1): ?>
                                <a href="<?php echo htmlspecialchars($filePath); ?>" target="_blank" class="bnx_chat_file_link_x7a9">
                                    Xem ảnh: <?php echo htmlspecialchars($fileName); ?>
                                </a>
                                <img src="<?php echo htmlspecialchars($filePath); ?>"
                                     alt="<?php echo htmlspecialchars($fileName); ?>"
                                     class="bnx_chat_img_preview_x7a9">
                            <?php else: ?>
                                <a href="<?php echo htmlspecialchars($filePath); ?>" target="_blank" class="bnx_chat_file_link_x7a9">
                                    Tải file: <?php echo htmlspecialchars($fileName); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <form id="formSendBN" class="bnx_chat_form_x7a9" onsubmit="return false;" enctype="multipart/form-data">
        <input type="hidden" id="MaCuocTrove" value="<?php echo $maCuocTrove; ?>">
        <input type="hidden" id="LastId" value="<?php echo $lastId; ?>">

        <textarea id="NoiDung"
                  class="bnx_chat_textarea_x7a9"
                  placeholder="Nhập nội dung tin nhắn..."></textarea>

        <div class="bnx_chat_file_input_x7a9">
            <input type="file" id="ChatFileBN" name="FileDinhKem">
        </div>

        <button type="button"
                class="bnx_chat_btn_send_x7a9"
                onclick="sendMessageBN();">Gửi</button>
    </form>
</div>

<script>
// Gửi tin nhắn từ BN (có thể kèm file)
function sendMessageBN() {
    var maCuoc = document.getElementById('MaCuocTrove').value;
    var noiDung = document.getElementById('NoiDung').value.trim();
    var fileInput = document.getElementById('ChatFileBN');

    // Cho phép gửi TRỐNG nội dung nếu có file
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

    fetch('/KLTN_Benhvien/BN/AjaxGuiTinNhanBN', {
        method: 'POST',
        body: formData
    })
    .then(function(res) { return res.json(); })
    .then(function(json) {
        if (!json.success) {
            alert(json.message || 'Gửi tin nhắn thất bại.');
        } else {
            document.getElementById('NoiDung').value = '';
            if (fileInput) {
                fileInput.value = '';
            }
            // Không cần tự append, để polling 1s tự tải tin mới
        }
    })
    .catch(function(err) {
        console.error(err);
        alert('Lỗi kết nối server.');
    });
}

// Polling 1 giây để lấy tin mới
function fetchNewMessagesBN() {
    var maCuoc = document.getElementById('MaCuocTrove').value;
    var lastId = document.getElementById('LastId').value;

    var formData = new FormData();
    formData.append('MaCuocTrove', maCuoc);
    formData.append('LastId', lastId);

    fetch('/KLTN_Benhvien/BN/AjaxFetchTinNhanBN', {
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

        var chatBox = document.getElementById('chatBox');
        var currentLastId = parseInt(lastId, 10);

        msgs.forEach(function(msg) {
            var isBN   = (msg.NguoiGuiLoai === 'BN');
            var rowCls = isBN ? 'bnx_me_x7a9' : 'bnx_other_x7a9';
            var badge  = isBN ? 'Bạn' : 'Bác sĩ';

            var wrapper = document.createElement('div');
            wrapper.className = 'bnx_chat_message_x7a9 ' + rowCls;

            var spanBadge = document.createElement('span');
            spanBadge.className = 'bnx_chat_badge_x7a9 ' + rowCls;
            spanBadge.textContent = badge;
            wrapper.appendChild(spanBadge);

            var spanTime = document.createElement('span');
            spanTime.className = 'bnx_chat_time_x7a9';
            spanTime.textContent = ' ' + (msg.ThoiGianGui || '');
            wrapper.appendChild(spanTime);

            if (msg.NoiDung) {
                var divMsg = document.createElement('div');
                divMsg.className = 'bnx_chat_text_x7a9';
                var safeContent = msg.NoiDung
                    ? msg.NoiDung
                        .replace(/&/g, "&amp;")
                        .replace(/</g, "&lt;")
                        .replace(/>/g, "&gt;")
                        .replace(/\n/g, "<br>")
                    : '';
                divMsg.innerHTML = safeContent;
                wrapper.appendChild(divMsg);
            }

            // Xử lý file đính kèm (nếu có)
            if (msg.FilePath && msg.FileNameGoc) {
                var fileWrap = document.createElement('div');
                fileWrap.className = 'bnx_chat_file_x7a9';

                var link = document.createElement('a');
                link.href = msg.FilePath;
                link.target = '_blank';
                link.className = 'bnx_chat_file_link_x7a9';
                var fileText = (msg.IsImage && parseInt(msg.IsImage, 10) === 1)
                    ? ('Xem ảnh: ' + msg.FileNameGoc)
                    : ('Tải file: ' + msg.FileNameGoc);
                link.textContent = fileText;
                fileWrap.appendChild(link);

                if (msg.IsImage && parseInt(msg.IsImage, 10) === 1) {
                    var img = document.createElement('img');
                    img.src = msg.FilePath;
                    img.alt = msg.FileNameGoc;
                    img.className = 'bnx_chat_img_preview_x7a9';
                    fileWrap.appendChild(img);
                }

                wrapper.appendChild(fileWrap);
            }

            chatBox.appendChild(wrapper);

            if (msg.MaTinNhan > currentLastId) {
                currentLastId = msg.MaTinNhan;
            }
        });

        document.getElementById('LastId').value = currentLastId;
        chatBox.scrollTop = chatBox.scrollHeight;
    })
    .catch(function(err) {
        console.error(err);
    });
}

// Gọi polling mỗi 1 giây
setInterval(fetchNewMessagesBN, 1000);
</script>
