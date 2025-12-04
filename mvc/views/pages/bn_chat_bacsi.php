<?php
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
        height: 350px;
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
        margin-bottom: 8px;
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

    .bnx_chat_btn_send_x7a9 {
        margin-top: 6px;
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
                ?>
                <div class="bnx_chat_message_x7a9 <?php echo $rowCls; ?>">
                    <span class="bnx_chat_badge_x7a9 <?php echo $rowCls; ?>">
                        <?php echo $badge; ?>
                    </span>
                    <span class="bnx_chat_time_x7a9">
                        <?php echo htmlspecialchars($msg["ThoiGianGui"]); ?>
                    </span>
                    <div class="bnx_chat_text_x7a9">
                        <?php echo nl2br(htmlspecialchars($msg["NoiDung"])); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <form id="formSendBN" class="bnx_chat_form_x7a9" onsubmit="return false;">
        <input type="hidden" id="MaCuocTrove" value="<?php echo $maCuocTrove; ?>">
        <input type="hidden" id="LastId" value="<?php echo $lastId; ?>">

        <textarea id="NoiDung"
                  class="bnx_chat_textarea_x7a9"
                  placeholder="Nhập nội dung tin nhắn..."></textarea>
        <button type="button"
                class="bnx_chat_btn_send_x7a9"
                onclick="sendMessageBN();">Gửi</button>
    </form>
</div>

<script>
// Gửi tin nhắn từ BN
function sendMessageBN() {
    var maCuoc = document.getElementById('MaCuocTrove').value;
    var noiDung = document.getElementById('NoiDung').value.trim();

    if (!noiDung) {
        alert('Vui lòng nhập nội dung.');
        return;
    }

    var formData = new FormData();
    formData.append('MaCuocTrove', maCuoc);
    formData.append('NoiDung', noiDung);

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
