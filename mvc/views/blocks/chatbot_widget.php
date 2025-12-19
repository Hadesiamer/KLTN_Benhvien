<?php 
// Widget Chatbot AI bệnh viện - dùng trên trang chủ (và có thể tái sử dụng ở layout khác)
?>
<!-- ==== CHATBOT AI BỆNH VIỆN - WIDGET ==== -->
<style>
    /* Đặt prefix bvchat_ để tránh đụng CSS khác */
    .bvchat_button {
        position: fixed;
        right: 24px;
        bottom: 24px;
        width: 56px;
        height: 56px;
        border-radius: 50%;
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(0,0,0,0.25);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        background: #0d6efd; /* màu xanh kiểu Bootstrap */
        color: #fff;
        z-index: 9999;
    }

    .bvchat_button:hover {
        opacity: 0.9;
    }

    .bvchat_window {
        position: fixed;
        right: 24px;
        bottom: 90px;
        width: 320px;
        max-height: 450px;
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.25);
        display: none; /* ẩn mặc định, click nút chat mới hiện */
        flex-direction: column;
        overflow: hidden;
        z-index: 9999;
        font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    }

    .bvchat_header {
        padding: 10px 12px;
        background: #0d6efd;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .bvchat_header_title {
        font-size: 14px;
        font-weight: 600;
    }

    .bvchat_header_sub {
        font-size: 11px;
        opacity: 0.9;
    }

    .bvchat_close {
        cursor: pointer;
        font-size: 16px;
        padding: 0 4px;
    }

    .bvchat_messages {
        padding: 10px;
        background: #f5f5f5;
        flex: 1;
        overflow-y: auto;
        font-size: 13px;
    }

    .bvchat_msg {
        margin-bottom: 8px;
        max-width: 90%;
        line-height: 1.4;
    }

    .bvchat_msg_user {
        margin-left: auto;
        background: #0d6efd;
        color: #fff;
        padding: 6px 8px;
        border-radius: 10px 0 10px 10px;
    }

    .bvchat_msg_bot {
        margin-right: auto;
        background: #ffffff;
        color: #333;
        padding: 6px 8px;
        border-radius: 0 10px 10px 10px;
        border: 1px solid #e0e0e0;
    }

    .bvchat_footer {
        padding: 8px;
        background: #ffffff;
        border-top: 1px solid #e0e0e0;
        display: flex;
        gap: 6px;
    }

    .bvchat_input {
        flex: 1;
        border-radius: 8px;
        border: 1px solid #d0d0d0;
        padding: 6px 8px;
        font-size: 13px;
    }

    .bvchat_send {
        border: none;
        border-radius: 8px;
        background: #0d6efd;
        color: #fff;
        padding: 6px 10px;
        font-size: 13px;
        cursor: pointer;
        white-space: nowrap;
    }

    .bvchat_send:disabled {
        opacity: 0.6;
        cursor: default;
    }

    /* Chế độ phóng to: cửa sổ chiếm 1/2 màn hình laptop */
    .bvchat_window.expanded {
        width: 50vw !important;
        height: 70vh !important;
        max-height: none !important;
        right: 5vw !important;
        bottom: 5vh !important;
        border-radius: 12px;
    }
</style>

<div class="bvchat_window" id="bvchat_window">
    <div class="bvchat_header">
        <div>
            <div class="bvchat_header_title">Trợ lý ảo Bệnh viện</div>
            <div class="bvchat_header_sub">Hỏi đáp, hướng dẫn đặt khám</div>
        </div>
        <!-- Nhóm nút phóng to / đóng -->
        <div style="display:flex; gap:10px; align-items:center;">
            <div class="bvchat_expand" id="bvchat_expand" title="Phóng to" style="cursor:pointer; font-size:16px;">
                ⛶
            </div>
            <div class="bvchat_close" id="bvchat_close">&times;</div>
        </div>
    </div>
    <div class="bvchat_messages" id="bvchat_messages">
        <div class="bvchat_msg bvchat_msg_bot">
            Xin chào, tôi là hệ thống bệnh viện. Tôi có thể hỗ trợ bạn về thông tin khám bệnh, quy trình và cách đặt lịch khám trên website.
        </div>
    </div>
    <div class="bvchat_footer">
        <input type="text" id="bvchat_input" class="bvchat_input" placeholder="Nhập câu hỏi của bạn..." />
        <button id="bvchat_send" class="bvchat_send">Gửi</button>
    </div>
</div>

<button class="bvchat_button" id="bvchat_button" title="Chat với bệnh viện">
    💬
</button>

<script>
    (function () {
        // Endpoint tới controller Chatbot của bạn
        // Nếu project nằm ở http://localvhost/KLTN_Benhvien/
        // thì giữ nguyên. Nếu thư mục gốc khác, bạn sửa lại cho đúng.
        const BVCHAT_ENDPOINT = "/KLTN_Benhvien/Chatbot/Ask";

        const btnToggle = document.getElementById("bvchat_button");
        const chatWindow = document.getElementById("bvchat_window");
        const btnClose = document.getElementById("bvchat_close");
        const input = document.getElementById("bvchat_input");
        const btnSend = document.getElementById("bvchat_send");
        const messagesBox = document.getElementById("bvchat_messages");
        const btnExpand = document.getElementById("bvchat_expand");

        let isExpanded = false; // trạng thái phóng to / thu nhỏ

        function appendMessage(text, type) {
            const div = document.createElement("div");
            div.classList.add("bvchat_msg");
            if (type === "user") {
                div.classList.add("bvchat_msg_user");
            } else {
                div.classList.add("bvchat_msg_bot");
            }
            div.textContent = text;
            messagesBox.appendChild(div);
            messagesBox.scrollTop = messagesBox.scrollHeight;
        }

        function toggleWindow(show) {
            if (show) {
                chatWindow.style.display = "flex";
                input.focus();
            } else {
                chatWindow.style.display = "none";
            }
        }

        // Nút mở / đóng widget
        btnToggle.addEventListener("click", function () {
            const isVisible = chatWindow.style.display === "flex";
            toggleWindow(!isVisible);
        });

        // Nút đóng (x)
        btnClose.addEventListener("click", function () {
            toggleWindow(false);
        });

        // Nút phóng to / thu nhỏ
        btnExpand.addEventListener("click", function () {
            isExpanded = !isExpanded;

            if (isExpanded) {
                chatWindow.classList.add("expanded");
                btnExpand.textContent = "🗗"; // biểu tượng thu nhỏ
                btnExpand.title = "Thu nhỏ";
            } else {
                chatWindow.classList.remove("expanded");
                btnExpand.textContent = "⛶"; // biểu tượng phóng to
                btnExpand.title = "Phóng to";
            }
        });

        function sendMessage() {
            const text = input.value.trim();
            if (!text) return;

            appendMessage(text, "user");
            input.value = "";
            input.focus();

            btnSend.disabled = true;
            appendMessage("Đang xử lý, vui lòng đợi...", "bot");
            const loadingMsg = messagesBox.lastChild;

            fetch(BVCHAT_ENDPOINT, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ message: text })
            })
                .then(function (res) { return res.json(); })
                .then(function (data) {
                    messagesBox.removeChild(loadingMsg);
                    if (data && data.success) {
                        appendMessage(data.answer, "bot");
                    } else {
                        appendMessage(
                            data && data.error
                                ? data.error
                                : "Đã có lỗi xảy ra, vui lòng thử lại sau.",
                            "bot"
                        );
                    }
                })
                .catch(function () {
                    messagesBox.removeChild(loadingMsg);
                    appendMessage("Không kết nối được tới máy chủ, vui lòng thử lại sau.", "bot");
                })
                .finally(function () {
                    btnSend.disabled = false;
                });
        }

        btnSend.addEventListener("click", sendMessage);

        input.addEventListener("keydown", function (e) {
            if (e.key === "Enter") {
                e.preventDefault();
                sendMessage();
            }
        });
    })();
</script>
<!-- ==== HẾT CHATBOT WIDGET ==== -->
