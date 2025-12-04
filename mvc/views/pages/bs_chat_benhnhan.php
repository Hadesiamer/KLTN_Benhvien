<?php
$maCuocTrove = isset($data["MaCuocTrove"]) ? (int)$data["MaCuocTrove"] : 0;
$header      = isset($data["Header"]) ? $data["Header"] : null;
$messages    = isset($data["Messages"]) ? $data["Messages"] : [];

$lastId = 0;
foreach ($messages as $msg) {
    if ($msg["MaTinNhan"] > $lastId) {
        $lastId = (int)$msg["MaTinNhan"];
    }
}
?>
<div class="container mt-3">
    <h3>Chat với bệnh nhân</h3>

    <?php if ($header): ?>
        <div class="mb-3">
            <strong>Bệnh nhân: <?php echo htmlspecialchars($header["TenBenhNhan"]); ?></strong><br>
            Mã BN: <?php echo htmlspecialchars($header["MaBN"]); ?><br>
            SĐT: <?php echo htmlspecialchars($header["SoDT"]); ?>
        </div>
    <?php endif; ?>

    <div id="chatBoxBS"
         style="border:1px solid #ccc; border-radius:4px; padding:10px; height:350px; overflow-y:auto; background:#fafafa;">
        <?php if (empty($messages)): ?>
            <p class="text-muted">Chưa có tin nhắn nào.</p>
        <?php else: ?>
            <?php foreach ($messages as $msg): ?>
                <?php
                $isBS    = ($msg["NguoiGuiLoai"] === 'BS');
                $cssText = $isBS ? "text-end" : "text-start";
                $badge   = $isBS ? "Bạn" : "Bệnh nhân";
                ?>
                <div class="mb-2 <?php echo $cssText; ?>">
                    <span class="badge bg-<?php echo $isBS ? 'primary' : 'secondary'; ?>">
                        <?php echo $badge; ?>
                    </span>
                    <small class="text-muted">
                        <?php echo htmlspecialchars($msg["ThoiGianGui"]); ?>
                    </small>
                    <div>
                        <?php echo nl2br(htmlspecialchars($msg["NoiDung"])); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <form id="formSendBS" class="mt-3" onsubmit="return false;">
        <input type="hidden" id="MaCuocTroveBS" value="<?php echo $maCuocTrove; ?>">
        <input type="hidden" id="LastIdBS" value="<?php echo $lastId; ?>">

        <div class="mb-2">
            <textarea id="NoiDungBS" class="form-control" rows="2"
                      placeholder="Nhập nội dung trả lời bệnh nhân..."></textarea>
        </div>
        <button type="button" class="btn btn-primary" onclick="sendMessageBS();">Gửi</button>
    </form>
</div>

<script>
// Gửi tin nhắn từ BS
function sendMessageBS() {
    var maCuoc = document.getElementById('MaCuocTroveBS').value;
    var noiDung = document.getElementById('NoiDungBS').value.trim();

    if (!noiDung) {
        alert('Vui lòng nhập nội dung.');
        return;
    }

    var formData = new FormData();
    formData.append('MaCuocTrove', maCuoc);
    formData.append('NoiDung', noiDung);

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
        }
    })
    .catch(function(err) {
        console.error(err);
        alert('Lỗi kết nối server.');
    });
}

// Polling 1 giây để lấy tin mới
function fetchNewMessagesBS() {
    var maCuoc = document.getElementById('MaCuocTroveBS').value;
    var lastId = document.getElementById('LastIdBS').value;

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
        var currentLastId = parseInt(lastId, 10);

        msgs.forEach(function(msg) {
            var isBS  = (msg.NguoiGuiLoai === 'BS');
            var css   = isBS ? 'text-end' : 'text-start';
            var badge = isBS ? 'Bạn' : 'Bệnh nhân';
            var badgeClass = isBS ? 'primary' : 'secondary';

            var wrapper = document.createElement('div');
            wrapper.className = 'mb-2 ' + css;

            var span = document.createElement('span');
            span.className = 'badge bg-' + badgeClass;
            span.textContent = badge;
            wrapper.appendChild(span);

            var small = document.createElement('small');
            small.className = 'text-muted ms-1';
            small.textContent = ' ' + msg.ThoiGianGui;
            wrapper.appendChild(small);

            var divMsg = document.createElement('div');
            divMsg.innerHTML = msg.NoiDung
                ? msg.NoiDung.replace(/&/g, "&amp;")
                             .replace(/</g, "&lt;")
                             .replace(/>/g, "&gt;")
                             .replace(/\n/g, "<br>")
                : '';
            wrapper.appendChild(divMsg);

            chatBox.appendChild(wrapper);

            if (msg.MaTinNhan > currentLastId) {
                currentLastId = msg.MaTinNhan;
            }
        });

        document.getElementById('LastIdBS').value = currentLastId;
        chatBox.scrollTop = chatBox.scrollHeight;
    })
    .catch(function(err) {
        console.error(err);
    });
}

setInterval(fetchNewMessagesBS, 1000);
</script>
