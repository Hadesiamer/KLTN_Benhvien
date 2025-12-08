<?php
$faceTemplates = isset($data["FaceTemplates"]) ? $data["FaceTemplates"] : [];
$cauHinhCa     = isset($data["CauHinhCa"]) ? $data["CauHinhCa"] : [];

$totalTemplates = count($faceTemplates);
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-0">
            <i class="bi bi-camera-video text-primary"></i>
            Điểm danh bằng khuôn mặt
        </h4>
        <small class="text-muted">
            Hệ thống sẽ dùng <strong>mẫu khuôn mặt đã đăng ký</strong> để nhận diện nhân viên 
            và ghi nhận điểm danh gắn với <strong>lịch làm việc hôm nay</strong>.
        </small>
    </div>
    <div class="text-end">
        <div class="small text-muted">Số nhân viên đã có mẫu khuôn mặt</div>
        <div class="fs-5 fw-bold text-primary"><?php echo $totalTemplates; ?></div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="bi bi-camera-video text-primary"></i>
                    Camera nhận diện
                </h5>
                <div class="position-relative rounded overflow-hidden border" style="background:#000;">
                    <video id="videoFaceCheck" autoplay playsinline muted style="width:100%;"></video>
                    <canvas id="overlayFaceCheck" class="position-absolute top-0 start-0 w-100 h-100"></canvas>
                </div>
                <small class="text-muted d-block mt-1">
                    Hướng dẫn: bật camera, đứng trong khung hình, nhìn thẳng, ánh sáng đủ. 
                    Sau đó bấm <strong>Quét &amp; điểm danh</strong>.
                </small>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex flex-column">
                <h5 class="card-title mb-3">
                    <i class="bi bi-info-circle text-primary"></i>
                    Trạng thái & thông tin nhận diện
                </h5>

                <div class="mb-2">
                    <div class="fw-semibold mb-1">Trạng thái</div>
                    <div id="ddStatusBox" class="small border rounded p-2 bg-light">
                        <?php if ($totalTemplates > 0): ?>
                            Sẵn sàng. Bấm <strong>Bật camera</strong> để bắt đầu.
                        <?php else: ?>
                            Chưa có mẫu khuôn mặt nào trong hệ thống. 
                            Vui lòng đăng ký mẫu cho nhân viên trước.
                        <?php endif; ?>
                    </div>
                </div>

                <div class="mb-2">
                    <div class="fw-semibold mb-1">Kết quả nhận diện</div>
                    <div id="ddRecognizedInfo" class="small border rounded p-2 bg-white">
                        Chưa có.
                    </div>
                </div>

                <div class="mb-3">
                    <div class="fw-semibold mb-1">Ca làm việc (theo cấu hình)</div>
                    <?php if (!empty($cauHinhCa)): ?>
                        <ul class="list-unstyled small mb-0">
                            <?php foreach ($cauHinhCa as $ca): ?>
                                <li>
                                    <i class="bi bi-dot"></i>
                                    <strong><?php echo htmlspecialchars($ca["CaLamViec"]); ?>:</strong>
                                    <?php echo substr($ca["GioBatDau"], 0, 5); ?> - <?php echo substr($ca["GioKetThuc"], 0, 5); ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <span class="text-danger small">
                            Chưa có cấu hình ca làm việc. Vui lòng thiết lập ở mục "Cấu hình ca".
                        </span>
                    <?php endif; ?>
                </div>

                <div class="mt-auto d-grid gap-2">
                    <button type="button" id="btnDDStartCamera"
                        class="btn btn-sm btn-outline-primary"
                        <?php echo $totalTemplates <= 0 ? 'disabled' : ''; ?>>
                        <i class="bi bi-camera-video"></i> Bật camera
                    </button>
                    <button type="button" id="btnDDScan"
                        class="btn btn-sm btn-success" disabled>
                        <i class="bi bi-check2-circle"></i> Quét &amp; điểm danh
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- face-api.js -->
<script defer src="https://unpkg.com/face-api.js@0.22.2/dist/face-api.min.js"></script>

<script>
// Truyền danh sách template từ PHP sang JS
const SERVER_FACE_TEMPLATES = <?php echo json_encode($faceTemplates, JSON_UNESCAPED_UNICODE); ?>;

document.addEventListener("DOMContentLoaded", function () {
    const video    = document.getElementById("videoFaceCheck");
    const canvas   = document.getElementById("overlayFaceCheck");
    const statusEl = document.getElementById("ddStatusBox");
    const infoEl   = document.getElementById("ddRecognizedInfo");
    const btnStart = document.getElementById("btnDDStartCamera");
    const btnScan  = document.getElementById("btnDDScan");

    let stream          = null;
    let modelsLoaded    = false;
    let modelLoadError  = false;
    let faceMatcher     = null;
    let preparedTemplates = [];

    const MODEL_URL = "/KLTN_Benhvien/public/face-models";

    function setStatus(text, type = "info") {
        statusEl.classList.remove("border-success", "border-danger", "border-info");
        if (type === "success") statusEl.classList.add("border-success");
        else if (type === "error") statusEl.classList.add("border-danger");
        else statusEl.classList.add("border-info");
        statusEl.innerText = text;
    }

    function setInfo(html) {
        infoEl.innerHTML = html;
    }

    // Chuẩn hóa template: parse Descriptor JSON -> Float32Array
    function prepareTemplates(rawList) {
        const result = [];
        rawList.forEach(item => {
            if (!item || !item.Descriptor) return;
            try {
                const arr = JSON.parse(item.Descriptor);
                if (!Array.isArray(arr) || arr.length === 0) return;

                const floatArr = new Float32Array(arr.map(Number));
                result.push({
                    maNV: parseInt(item.MaNV, 10),
                    hoTen: item.HovaTen,
                    chucVu: item.ChucVu,
                    descriptor: floatArr
                });
            } catch (e) {
                console.error("Lỗi parse Descriptor:", e, item);
            }
        });
        return result;
    }

    async function loadModels() {
        if (modelsLoaded || modelLoadError) return;
        setStatus("Đang tải model nhận diện khuôn mặt...", "info");
        try {
            await Promise.all([
                faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
                faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
                faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL)
            ]);
            modelsLoaded = true;
            setStatus("Tải model xong. Có thể bật camera.", "success");
        } catch (err) {
            console.error("loadModels error:", err);
            modelLoadError = true;
            setStatus("Lỗi tải model. Kiểm tra lại thư mục public/face-models.", "error");
            throw err;
        }
    }

    function buildFaceMatcher() {
        preparedTemplates = prepareTemplates(SERVER_FACE_TEMPLATES || []);
        if (!preparedTemplates.length) {
            setStatus("Chưa có mẫu khuôn mặt hợp lệ. Vui lòng đăng ký mẫu trước.", "error");
            if (btnStart) btnStart.disabled = true;
            return;
        }

        const labeled = preparedTemplates.map(t =>
            new faceapi.LabeledFaceDescriptors(
                t.maNV.toString(),
                [t.descriptor]
            )
        );
        faceMatcher = new faceapi.FaceMatcher(labeled, 0.6);
        console.log("FaceMatcher ready. Templates:", preparedTemplates.length);
    }

    async function startCamera() {
        try {
            await loadModels();
        } catch {
            return;
        }

        if (!faceMatcher) {
            buildFaceMatcher();
        }
        if (!faceMatcher) {
            return; // đã báo lỗi ở trên
        }

        try {
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                setStatus("Trình duyệt không hỗ trợ camera (getUserMedia).", "error");
                return;
            }

            stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
            video.srcObject = stream;

            video.onloadedmetadata = () => {
                video.play();
                canvas.width  = video.videoWidth;
                canvas.height = video.videoHeight;
            };

            if (btnScan) btnScan.disabled = false;
            setStatus("Camera đã bật. Bấm 'Quét & điểm danh' khi sẵn sàng.", "success");
        } catch (err) {
            console.error("startCamera error:", err);
            if (err.name === "NotAllowedError" || err.name === "PermissionDeniedError") {
                setStatus("Trình duyệt đã chặn quyền camera. Kiểm tra lại quyền truy cập.", "error");
            } else if (err.name === "NotFoundError" || err.name === "DevicesNotFoundError") {
                setStatus("Không tìm thấy thiết bị camera.", "error");
            } else {
                setStatus("Lỗi khi bật camera: " + (err.message || err.toString()), "error");
            }
        }
    }

    async function scanAndCheckIn() {
        if (!modelsLoaded || !faceMatcher) {
            setStatus("Model hoặc FaceMatcher chưa sẵn sàng.", "error");
            return;
        }
        if (!video.srcObject) {
            setStatus("Chưa bật camera.", "error");
            return;
        }

        setStatus("Đang quét và nhận diện khuôn mặt...", "info");

        const options = new faceapi.TinyFaceDetectorOptions({
            inputSize: 320,
            scoreThreshold: 0.5
        });

        try {
            const detection = await faceapi
                .detectSingleFace(video, options)
                .withFaceLandmarks()
                .withFaceDescriptor();

            const ctx = canvas.getContext("2d");
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            if (!detection) {
                setStatus("Không tìm thấy khuôn mặt. Thử lại, đứng gần và đủ sáng.", "error");
                setInfo("Chưa nhận diện được khuôn mặt hợp lệ.");
                return;
            }

            const resized = faceapi.resizeResults(detection, {
                width: canvas.width,
                height: canvas.height
            });
            faceapi.draw.drawDetections(canvas, resized);
            faceapi.draw.drawFaceLandmarks(canvas, resized);

            const bestMatch = faceMatcher.findBestMatch(detection.descriptor);
            console.log("Best match:", bestMatch.toString());

            if (!bestMatch || bestMatch.label === "unknown") {
                setStatus("Không nhận diện được nhân viên phù hợp trong hệ thống.", "error");
                setInfo("Khuôn mặt không khớp với bất kỳ mẫu nào đã đăng ký.");
                return;
            }

            const maNV = parseInt(bestMatch.label, 10);
            const distance = bestMatch.distance;
            const nv = preparedTemplates.find(t => t.maNV === maNV);

            if (!nv) {
                setStatus("Nhận diện được mã nhân viên nhưng không tìm thấy thông tin NV.", "error");
                setInfo("Mã NV: " + maNV + " (thiếu thông tin).");
                return;
            }

            setInfo(
                "<strong>" + nv.hoTen + "</strong><br>" +
                "Mã NV: " + maNV + "<br>" +
                "Chức vụ: " + (nv.chucVu || "") + "<br>" +
                "Khoảng cách (distance): " + distance.toFixed(3)
            );

            setStatus("Đã nhận diện. Đang gửi dữ liệu điểm danh...", "info");

            const res = await fetch("DD_CheckIn", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    MaNV: maNV,
                    Distance: distance
                })
            });

            const data = await res.json();
            if (!res.ok || !data.success) {
                const msg = data && data.message ? data.message : "Điểm danh thất bại.";
                setStatus(msg, "error");
                if (window.showToastFromJS) {
                    window.showToastFromJS("error", msg);
                }
                return;
            }

            let msg = data.message;
            if (data.KetQua) {
                msg += " (" + data.KetQua + ")";
            }
            setStatus(msg, "success");

            // Dùng toast chung nếu có
            if (window.showToastFromJS) {
                window.showToastFromJS("success", msg);
            }

            // Sau khi điểm danh thành công: tắt camera + disable nút quét
            if (stream) {
                stream.getTracks().forEach(t => t.stop());
                stream = null;
            }
            video.srcObject = null;
            if (btnScan) btnScan.disabled = true;

        } catch (err) {
            console.error("scanAndCheckIn error:", err);
            setStatus("Lỗi trong quá trình nhận diện / điểm danh.", "error");
            if (window.showToastFromJS) {
                window.showToastFromJS("error", "Lỗi nhận diện hoặc ghi nhận điểm danh.");
            }
        }
    }

    if (btnStart) {
        btnStart.addEventListener("click", startCamera);
    }
    if (btnScan) {
        btnScan.addEventListener("click", scanAndCheckIn);
    }

    // Tắt camera khi rời trang
    window.addEventListener("beforeunload", function () {
        if (stream) {
            stream.getTracks().forEach(t => t.stop());
        }
    });
});
</script>
