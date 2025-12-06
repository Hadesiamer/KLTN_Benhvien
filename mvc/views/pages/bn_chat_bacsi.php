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

    /* [NEW] Bọc card đẹp hơn */
    .bnx_chat_outer_x7a9 {
        max-width: 1100px;
        margin: 0 auto;
    }

    .bnx_chat_wrapper_x7a9 {
        width: 100%;
        box-sizing: border-box;
        padding: 10px 0 20px 0;
        font-family: Arial, sans-serif;
    }

    .bnx_chat_card_x7a9 {
        border-radius: 16px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.06);
        background-color: #ffffff;
        overflow: hidden;
    }

    .bnx_chat_headerbar_x7a9 {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 16px;
        background: linear-gradient(135deg, #2563eb, #4f46e5);
        color: #ffffff;
    }

    .bnx_chat_header_left_x7a9 {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .bnx_chat_header_title_x7a9 {
        font-size: 1rem;
        font-weight: 600;
        margin: 0;
    }

    .bnx_chat_header_sub_x7a9 {
        font-size: 0.8rem;
        opacity: 0.9;
    }

    .bnx_chat_doctor_avatar_x7a9 {
        width: 40px;
        height: 40px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 18px;
    }

    .bnx_chat_header_status_x7a9 {
        font-size: 0.75rem;
        background: rgba(15, 23, 42, 0.25);
        padding: 3px 8px;
        border-radius: 999px;
    }

    .bnx_chat_body_x7a9 {
        padding: 10px 16px 14px 16px;
        background: radial-gradient(circle at top left, #eef2ff, #f9fafb);
    }

    .bnx_chat_hint_x7a9 {
        font-size: 0.8rem;
        color: #6b7280;
        margin-bottom: 6px;
    }

    .bnx_chat_box_x7a9 {
        border-radius: 10px;
        padding: 10px 12px;
        height: 380px;
        overflow-y: auto;
        background-color: #f9fafb;
        box-sizing: border-box;
        font-size: 14px;
    }

    .bnx_chat_empty_x7a9 {
        font-style: italic;
        color: #777777;
        font-size: 14px;
        margin-top: 6px;
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
        font-size: 11px;
        font-weight: 600;
        color: #ffffff;
        margin-bottom: 2px;
    }

    .bnx_chat_badge_x7a9.bnx_me_x7a9 {
        background-color: #2563eb; /* bệnh nhân */
    }

    .bnx_chat_badge_x7a9.bnx_other_x7a9 {
        background-color: #6b7280; /* bác sĩ */
    }

    .bnx_chat_time_x7a9 {
        display: inline-block;
        font-size: 11px;
        color: #9ca3af;
        margin-left: 4px;
    }

    .bnx_chat_text_x7a9 {
        margin-top: 2px;
        padding: 7px 9px;
        border-radius: 10px;
        background-color: #ffffff;
        display: inline-block;
        max-width: 80%;
        word-wrap: break-word;
        text-align: left;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.08);
    }

    .bnx_chat_message_x7a9.bnx_me_x7a9 .bnx_chat_text_x7a9 {
        background-color: #dbeafe;
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
        max-width: 220px;
        max-height: 220px;
        margin-top: 4px;
        border-radius: 6px;
        display: block;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.25);
    }

    .bnx_chat_formwrap_x7a9 {
        border-top: 1px solid #e5e7eb;
        padding: 10px 16px 12px 16px;
        background-color: #ffffff;
    }

    .bnx_chat_form_x7a9 {
        margin-top: 4px;
    }

    .bnx_chat_textarea_x7a9 {
        width: 100%;
        box-sizing: border-box;
        padding: 8px 10px;
        border-radius: 8px;
        border: 1px solid #d1d5db;
        resize: vertical;
        min-height: 60px;
        font-size: 14px;
        font-family: inherit;
    }

    .bnx_chat_textarea_x7a9:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 1px rgba(37, 99, 235, 0.25);
    }

    .bnx_chat_file_input_x7a9 {
        margin-top: 6px;
        font-size: 13px;
        color: #6b7280;
    }

    .bnx_chat_btn_row_x7a9 {
        margin-top: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .bnx_chat_btn_hint_x7a9 {
        font-size: 0.8rem;
        color: #94a3b8;
    }

    .bnx_chat_btn_send_x7a9 {
        padding: 8px 18px;
        border: none;
        border-radius: 999px;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: #ffffff;
        font-size: 14px;
        cursor: pointer;
        white-space: nowrap;
    }

    .bnx_chat_btn_send_x7a9:hover {
        background: linear-gradient(135deg, #1d4ed8, #1e3a8a);
    }

    @media (max-width: 768px) {
        .bnx_chat_card_x7a9 {
            border-radius: 12px;
        }

        .bnx_chat_body_x7a9 {
            padding: 8px 10px 10px 10px;
        }

        .bnx_chat_box_x7a9 {
            height: 340px;
        }
    }
</style>

<div class="bnx_chat_outer_x7a9">
    <div class="bnx_chat_wrapper_x7a9">
        <div class="bnx_chat_card_x7a9">
            <!-- [NEW] Header bar -->
            <div class="bnx_chat_headerbar_x7a9">
                <div class="bnx_chat_header_left_x7a9">
                    <?php
                        $doctorName = $bacSi ? htmlspecialchars($bacSi["HovaTen"]) : "Bác sĩ";
                        $doctorKhoa = $bacSi ? htmlspecialchars($bacSi["TenKhoa"]) : "";
                        $avatarChar = mb_strtoupper(mb_substr($doctorName, 0, 1, "UTF-8"));
                    ?>
                    <div class="bnx_chat_doctor_avatar_x7a9">
                        <?php echo $avatarChar; ?>
                    </div>
                    <div>
                        <p class="bnx_chat_header_title_x7a9 mb-0">
                            Chat với BS. <?php echo $doctorName; ?>
                        </p>
                        <?php if ($doctorKhoa !== ""): ?>
                            <div class="bnx_chat_header_sub_x7a9">
                                Khoa <?php echo $doctorKhoa; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="bnx_chat_header_status_x7a9">
                    Hỗ trợ sau khám online
                </div>
            </div>

            <!-- Nội dung chat -->
            <div class="bnx_chat_body_x7a9">
                <div class="bnx_chat_hint_x7a9">
                    Tin nhắn sẽ được lưu lại để bác sĩ và bạn có thể xem lại trong lần khám sau.
                </div>

                <div id="chatBox" class="bnx_chat_box_x7a9">
                    <?php if (empty($messages)): ?>
                        <p class="bnx_chat_empty_x7a9">
                            Chưa có tin nhắn nào. Hãy gửi lời chào bác sĩ trước nhé.
                        </p>
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
            </div>

            <!-- Form gửi tin -->
            <div class="bnx_chat_formwrap_x7a9">
                <form id="formSendBN" class="bnx_chat_form_x7a9" onsubmit="return false;" enctype="multipart/form-data">
                    <input type="hidden" id="MaCuocTrove" value="<?php echo $maCuocTrove; ?>">
                    <input type="hidden" id="LastId" value="<?php echo $lastId; ?>">

                    <textarea id="NoiDung"
                              class="bnx_chat_textarea_x7a9"
                              placeholder="Nhập nội dung tin nhắn cho bác sĩ..."></textarea>

                    <div class="bnx_chat_file_input_x7a9">
                        <input type="file" id="ChatFileBN" name="FileDinhKem">
                    </div>

                    <div class="bnx_chat_btn_row_x7a9">
                        <div class="bnx_chat_btn_hint_x7a9">
                            Bạn có thể gửi kèm hình ảnh hoặc tài liệu tối đa 5MB.
                        </div>
                        <button type="button"
                                class="bnx_chat_btn_send_x7a9"
                                onclick="sendMessageBN();">Gửi tin nhắn</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
