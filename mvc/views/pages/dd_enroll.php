<?php
// Thông tin NV & template hiện tại được truyền từ controller Qlydd/DD_Enroll
$nv       = isset($data["NhanVien"]) ? $data["NhanVien"] : null;
$template = isset($data["Template"]) ? $data["Template"] : null;

if (!$nv) {
    echo '<div class="alert alert-danger">Không tìm thấy thông tin nhân viên.</div>';
    return;
}

$hasFace = !empty($template);
?>

<div class="mb-3">
    <a href="./DD_QuanLyMau" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Quay về danh sách
    </a>
</div>

<div class="row g-3">
    <!-- Thông tin nhân viên -->
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="bi bi-person-circle text-primary"></i>
                    Thông tin nhân viên
                </h5>
                <ul class="list-group list-group-flush mb-3">
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span class="text-muted">Mã NV:</span>
                        <span class="fw-semibold"><?php echo (int)$nv["MaNV"]; ?></span>
                    </li>
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span class="text-muted">Họ tên:</span>
                        <span class="fw-semibold text-end"><?php echo htmlspecialchars($nv["HovaTen"]); ?></span>
                    </li>
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span class="text-muted">Chức vụ:</span>
                        <span class="fw-semibold text-end"><?php echo htmlspecialchars($nv["ChucVu"]); ?></span>
                    </li>
                    <?php if (!empty($nv["TenKhoa"])): ?>
                        <li class="list-group-item px-0 d-flex justify-content-between">
                            <span class="text-muted">Chuyên khoa:</span>
                            <span class="fw-semibold text-end"><?php echo htmlspecialchars($nv["TenKhoa"]); ?></span>
                        </li>
                    <?php endif; ?>
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span class="text-muted">SĐT:</span>
                        <span class="fw-semibold text-end"><?php echo htmlspecialchars($nv["SoDT"]); ?></span>
                    </li>
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span class="text-muted">Email:</span>
                        <span class="fw-semibold text-end"><?php echo htmlspecialchars($nv["EmailNV"]); ?></span>
                    </li>
                    <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                        <span class="text-muted">Trạng thái mẫu:</span>
                        <?php if ($hasFace): ?>
                            <span class="badge bg-success">
                                <i class="bi bi-check2-circle"></i> Đã có mẫu
                            </span>
                        <?php else: ?>
                            <span class="badge bg-danger">
                                <i class="bi bi-x-circle"></i> Chưa có mẫu
                            </span>
                        <?php endif; ?>
                    </li>
                    <?php if ($hasFace && !empty($template["UpdatedAt"])): ?>
                        <li class="list-group-item px-0 d-flex justify-content-between">
                            <span class="text-muted">Cập nhật lần cuối:</span>
                            <span class="small text-end"><?php echo htmlspecialchars($template["UpdatedAt"]); ?></span>
                        </li>
                    <?php endif; ?>
                </ul>

                <div class="alert alert-info small mb-0">
                    <i class="bi bi-info-circle"></i>
                    <span>
                        Mỗi lần lưu sẽ <strong>ghi đè</strong> mẫu khuôn mặt cũ của nhân viên này.
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Camera + Face API -->
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="bi bi-camera-video text-primary"></i>
                    Đăng ký / cập nhật mẫu khuôn mặt
                </h5>

                <div class="row g-3">
                    <div class="col-md-8">
                        <div class="position-relative rounded overflow-hidden border" style="background:#000;">
                            <video id="videoFace" autoplay playsinline muted style="width:100%;"></video>
                            <canvas id="overlayFace" class="position-absolute top-0 start-0 w-100 h-100"></canvas>
                        </div>
                        <small class="text-muted d-block mt-1">
                            Hướng dẫn: bật camera, nhìn thẳng vào màn hình, giữ ổn định khi chụp.
                        </small>
                    </div>
                    <div class="col-md-4 d-flex flex-column">
                        <div class="mb-3">
                            <div class="fw-semibold mb-1">Trạng thái</div>
                            <div id="statusBox" class="small border rounded p-2 bg-light">
                                Đang chờ bật camera...
                            </div>
                        </div>

                        <input type="hidden" id="MaNV" value="<?php echo (int)$nv["MaNV"]; ?>">

                        <div class="d-grid gap-2 mb-3">
                            <button type="button" id="btnStartCamera" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-camera-video"></i> Bật camera
                            </button>
                            <button type="button" id="btnCapture" class="btn btn-outline-secondary btn-sm" disabled>
                                <i class="bi bi-camera"></i> Chụp khuôn mặt
                            </button>
                            <button type="button" id="btnSaveTemplate" class="btn btn-success btn-sm" disabled>
                                <i class="bi bi-save2"></i> Lưu mẫu khuôn mặt
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- face-api.js CDN -->
<script defer src="https://unpkg.com/face-api.js@0.22.2/dist/face-api.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const video    = document.getElementById("videoFace");
    const canvas   = document.getElementById("overlayFace");
    const statusEl = document.getElementById("statusBox");
    const btnStart = document.getElementById("btnStartCamera");
    const btnCap   = document.getElementById("btnCapture");
    const btnSave  = document.getElementById("btnSaveTemplate");
    const maNV     = document.getElementById("MaNV").value;

    let stream = null;
    let currentDescriptor = null;
    let modelsLoaded = false;

    // Đường dẫn tới thư mục chứa model face-api.js (anh cần tự tải về)
    const MODEL_URL = "/KLTN_Benhvien/public/face-models";

    function setStatus(text, type = "info") {
        statusEl.classList.remove("border-success", "border-danger", "border-info");
        if (type === "success") statusEl.classList.add("border-success");
        else if (type === "error") statusEl.classList.add("border-danger");
        else statusEl.classList.add("border-info");

        statusEl.innerText = text;
    }

    async function loadModels() {
        if (modelsLoaded) return;
        setStatus("Đang tải model nhận diện khuôn mặt...", "info");
        try {
            await Promise.all([
                faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
                faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
                faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL)
            ]);
            modelsLoaded = true;
            setStatus("Tải model xong. Anh có thể bật camera.", "success");
        } catch (err) {
            console.error(err);
            setStatus("Lỗi tải model. Kiểm tra lại thư mục model trong public/face-models.", "error");
            throw err;
        }
    }

    async function startCamera() {
        try {
            await loadModels();

            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                setStatus("Trình duyệt không hỗ trợ camera (getUserMedia).", "error");
                return;
            }

            stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
            video.srcObject = stream;

            video.onloadedmetadata = () => {
                video.play();
                // resize canvas cho khớp video
                canvas.width  = video.videoWidth;
                canvas.height = video.videoHeight;
            };

            btnCap.disabled  = false;
            btnSave.disabled = true;
            currentDescriptor = null;
            setStatus("Camera đã bật. Anh bấm 'Chụp khuôn mặt' khi sẵn sàng.", "success");
        } catch (err) {
            console.error(err);
            setStatus("Không bật được camera. Kiểm tra quyền truy cập camera.", "error");
        }
    }

    async function captureFace() {
        if (!modelsLoaded) {
            setStatus("Model chưa tải xong.", "error");
            return;
        }
        if (!video.srcObject) {
            setStatus("Chưa bật camera.", "error");
            return;
        }

        setStatus("Đang phân tích khuôn mặt...", "info");

        const options = new faceapi.TinyFaceDetectorOptions({
            inputSize: 320,
            scoreThreshold: 0.5
        });

        const detection = await faceapi
            .detectSingleFace(video, options)
            .withFaceLandmarks()
            .withFaceDescriptor();

        const ctx = canvas.getContext("2d");
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        if (!detection) {
            setStatus("Không tìm thấy khuôn mặt. Anh thử lại, nhìn thẳng và đủ sáng.", "error");
            return;
        }

        // Vẽ khung lên canvas cho vui
        const resized = faceapi.resizeResults(detection, {
            width: canvas.width,
            height: canvas.height
        });
        faceapi.draw.drawDetections(canvas, resized);
        faceapi.draw.drawFaceLandmarks(canvas, resized);

        currentDescriptor = Array.from(detection.descriptor); // chuyển Float32Array -> Array<number>
        btnSave.disabled  = false;

        setStatus("Đã chụp khuôn mặt. Anh bấm 'Lưu mẫu khuôn mặt' để lưu.", "success");
    }

    async function saveTemplate() {
        if (!currentDescriptor || !Array.isArray(currentDescriptor)) {
            setStatus("Chưa có descriptor. Anh chụp lại khuôn mặt.", "error");
            return;
        }

        setStatus("Đang gửi dữ liệu lên server...", "info");

        try {
            const res = await fetch("DD_SaveTemplate", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    MaNV: parseInt(maNV, 10),
                    Descriptor: currentDescriptor
                })
            });

            const data = await res.json();
            if (data.success) {
                setStatus("Lưu mẫu khuôn mặt thành công.", "success");

                // Gọi toast chung ở layout nếu muốn
                if (window.showToastFromJS) {
                    window.showToastFromJS("success", "Lưu mẫu khuôn mặt thành công.");
                }
            } else {
                setStatus(data.message || "Lưu mẫu khuôn mặt thất bại.", "error");
                if (window.showToastFromJS) {
                    window.showToastFromJS("error", "Lưu mẫu khuôn mặt thất bại.");
                }
            }
        } catch (err) {
            console.error(err);
            setStatus("Lỗi khi gửi dữ liệu lên server.", "error");
            if (window.showToastFromJS) {
                window.showToastFromJS("error", "Lỗi kết nối server khi lưu mẫu.");
            }
        }
    }

    btnStart.addEventListener("click", startCamera);
    btnCap.addEventListener("click", captureFace);
    btnSave.addEventListener("click", saveTemplate);

    // Khi rời trang có thể tắt stream cho sạch
    window.addEventListener("beforeunload", function () {
        if (stream) {
            stream.getTracks().forEach(t => t.stop());
        }
    });
});
</script>
