<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khoa Ngoại khoa - Bệnh viện</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #0ea5e9;
            --primary-dark: #0284c7;
            --primary-soft: #e0f2fe;
            --cyan: #06b6d4;
            --danger: #ef4444;
            --bg: #f9fafb;
            --text-main: #111827;
            --text-sub: #4b5563;
            --border: #e5e7eb;
            --white: #ffffff;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg);
            color: var(--text-main);
        }

        a { text-decoration: none; color: inherit; }

        .container {
            width: 100%;
            max-width: 1120px;
            margin: 0 auto;
            padding: 0 16px;
        }

        .page-section { margin-bottom: 40px; }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 500;
        }

        .badge-khoa {
            background: linear-gradient(135deg, var(--primary) 0%, var(--cyan) 100%);
            color: var(--white);
            box-shadow: 0 8px 20px rgba(14, 165, 233, 0.4);
        }

        .alert {
            background-color: #fffbeb;
            border-left: 4px solid #f59e0b;
            padding: 12px 0;
        }

        .alert-inner {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: 13px;
        }

        .alert-icon {
            flex-shrink: 0;
            margin-top: 2px;
            color: #f59e0b;
        }

        .alert-title {
            font-weight: 600;
            color: #92400e;
            margin-bottom: 2px;
        }

        .alert-text { color: #b45309; }

        .hero {
            background: linear-gradient(135deg, var(--primary) 0%, var(--cyan) 100%);
            color: var(--white);
            padding: 32px 0 32px;
        }

        .hero-inner {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        @media (min-width: 768px) {
            .hero-inner {
                flex-direction: row;
                align-items: center;
            }
        }

        .hero-left, .hero-right { flex: 1; }

        .hero-pill-dot {
            width: 7px;
            height: 7px;
            border-radius: 999px;
            background-color: #bbf7d0;
            margin-right: 6px;
        }

        .hero-pill {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.5);
            background-color: rgba(255, 255, 255, 0.06);
            font-size: 11px;
            margin-bottom: 10px;
        }

        .hero-title {
            font-size: 28px;
            font-weight: 700;
            margin: 0 0 8px;
        }

        @media (min-width: 768px) { .hero-title { font-size: 34px; } }
        @media (min-width: 1024px) { .hero-title { font-size: 40px; } }

        .hero-desc {
            font-size: 14px;
            color: #e0f2fe;
            line-height: 1.7;
            margin-bottom: 14px;
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 6px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            padding: 8px 14px;
            border: 1px solid transparent;
            cursor: pointer;
            transition: background-color .15s ease, color .15s ease, border-color .15s ease;
        }

        .btn-primary {
            background-color: var(--white);
            color: var(--primary-dark);
            box-shadow: 0 10px 25px rgba(15, 118, 210, 0.35);
        }

        .btn-primary:hover { background-color: #eff6ff; }

        .btn-outline-light {
            background: transparent;
            color: var(--white);
            border-color: rgba(255, 255, 255, 0.7);
        }

        .btn-outline-light:hover { background-color: rgba(255, 255, 255, 0.12); }

        .hero-card {
            background-color: rgba(15, 23, 42, 0.18);
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.25);
            padding: 16px 18px;
            box-shadow: 0 16px 40px rgba(30, 64, 175, 0.4);
        }

        .hero-card-title {
            font-size: 16px;
            font-weight: 600;
            margin: 0 0 10px;
        }

        .hero-card-list {
            list-style: none;
            padding: 0;
            margin: 0;
            font-size: 13px;
        }

        .hero-card-row {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
        }

        .hero-card-row + .hero-card-row {
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            margin-top: 4px;
            padding-top: 8px;
        }

        .hero-card-label { color: #e0f2fe; }
        .hero-card-value { font-weight: 600; }

        .grid { display: grid; gap: 16px; }
        @media (min-width: 768px) { .grid-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
        @media (min-width: 1024px) { .grid-3-lg { grid-template-columns: repeat(3, minmax(0, 1fr)); } }

        .card {
            background-color: var(--white);
            border-radius: 14px;
            border: 1px solid var(--border);
            box-shadow: 0 4px 10px rgba(15, 23, 42, 0.04);
            padding: 16px;
        }

        .card-title {
            font-size: 14px;
            font-weight: 600;
            margin: 0 0 4px;
        }

        .card-text {
            font-size: 13px;
            color: var(--text-sub);
            line-height: 1.6;
        }

        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
            gap: 8px;
        }

        .section-title {
            font-size: 20px;
            font-weight: 700;
            margin: 0;
        }

        .section-desc {
            font-size: 13px;
            color: var(--text-sub);
            margin-bottom: 12px;
            line-height: 1.6;
        }

        .list-steps {
            font-size: 13px;
            color: var(--text-sub);
            margin: 0;
            padding-left: 16px;
        }

        .list-steps li {
            margin-bottom: 6px;
            line-height: 1.6;
        }

        .step-label {
            font-weight: 600;
            color: var(--primary-dark);
        }

        .doctor-avatar {
            width: 72px;
            height: 72px;
            border-radius: 999px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 8px;
            color: #f9fafb;
            font-weight: 700;
            font-size: 20px;
        }

        .doctor-name {
            text-align: center;
            font-size: 14px;
            font-weight: 600;
            margin: 0;
        }

        .doctor-position {
            text-align: center;
            font-size: 11px;
            color: var(--text-sub);
            margin-bottom: 6px;
        }

        .doctor-desc {
            font-size: 13px;
            color: var(--text-sub);
            line-height: 1.6;
        }

        .facilities-list {
            list-style: disc;
            padding-left: 18px;
            font-size: 13px;
            color: var(--text-sub);
        }

        .note-small {
            font-size: 11px;
            color: #6b7280;
            margin-top: 8px;
        }

        .faq-item {
            background-color: var(--white);
            border-radius: 10px;
            border: 1px solid var(--border);
            overflow: hidden;
        }

        .faq-summary {
            padding: 10px 14px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .faq-summary:hover { background-color: #f9fafb; }

        .faq-body {
            padding: 0 14px 10px;
            font-size: 13px;
            color: var(--text-sub);
            line-height: 1.6;
        }

        .faq-arrow {
            font-size: 11px;
            color: #9ca3af;
            margin-left: 8px;
        }

        .contact-section {
            background: linear-gradient(to right, #2563eb, #06b6d4);
            color: var(--white);
            border-radius: 18px;
            padding: 18px 18px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        @media (min-width: 768px) {
            .contact-section {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                padding: 22px 26px;
            }
        }

        .contact-left-title {
            font-size: 18px;
            font-weight: 700;
            margin: 0 0 6px;
        }

        .contact-left-text {
            font-size: 13px;
            color: #e0f2fe;
            margin: 0 0 4px;
        }

        .contact-left-note {
            font-size: 11px;
            color: #bfdbfe;
        }

        .contact-right {
            text-align: right;
            font-size: 13px;
        }

        .contact-label {
            font-size: 11px;
            color: #bfdbfe;
        }

        .contact-value-main {
            font-size: 20px;
            font-weight: 700;
        }

        .contact-value-sub {
            font-size: 14px;
            font-weight: 600;
            color: #facc15;
        }

        .contact-btn-wrapper { margin-top: 8px; }

        .btn-contact {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 7px 14px;
            border-radius: 8px;
            background-color: var(--white);
            color: #1d4ed8;
            font-weight: 600;
            font-size: 12px;
            border: none;
            cursor: pointer;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.35);
        }

        .btn-contact:hover { background-color: #eff6ff; }

        main { padding: 24px 0 40px; }

        @media (max-width: 640px) {
            .section-header {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>

<div class="alert">
    <div class="container">
        <div class="alert-inner">
            <div class="alert-icon">⚠️</div>
            <div>
                <div class="alert-title">Lưu ý</div>
                <div class="alert-text">
                    Đây là website trong đồ án sinh viên, nội dung chỉ mang tính minh họa, không thay thế tư vấn y khoa thực tế.
                </div>
            </div>
        </div>
    </div>
</div>

<section class="hero">
    <div class="container">
        <div class="hero-inner">
            <div class="hero-left">
                <div class="hero-pill">
                    <span class="hero-pill-dot"></span>
                    Khoa phẫu thuật – Ngoại khoa
                </div>
                <h1 class="hero-title">Khoa Ngoại khoa</h1>
                <p class="hero-desc">
                    Khoa Ngoại khoa tiếp nhận, điều trị các bệnh lý cần can thiệp phẫu thuật như bệnh lý tiêu hóa,
                    chấn thương chỉnh hình, tiết niệu, thoát vị, u bướu ngoại khoa…
                    Mục tiêu là đảm bảo an toàn phẫu thuật và hỗ trợ hồi phục sau mổ cho người bệnh.
                </p>
                <div class="hero-actions">
                    <a href="#dat-lich" class="btn btn-primary">Đặt lịch khám Ngoại khoa</a>
                    <a href="#trieuchung" class="btn btn-outline-light">Khi nào nên đi khám?</a>
                </div>
            </div>
            <div class="hero-right">
                <div class="hero-card">
                    <h2 class="hero-card-title">Thông tin nhanh</h2>
                    <ul class="hero-card-list">
                        <li class="hero-card-row">
                            <span class="hero-card-label">Địa điểm</span>
                            <span class="hero-card-value">Tầng 5 – Khu B</span>
                        </li>
                        <li class="hero-card-row">
                            <span class="hero-card-label">Giờ làm việc</span>
                            <span class="hero-card-value">Thứ 2 – Thứ 6: 7:00 – 17:00</span>
                        </li>
                        <li class="hero-card-row">
                            <span class="hero-card-label">Khám trước mổ</span>
                            <span class="hero-card-value">Theo lịch hẹn</span>
                        </li>
                        <li class="hero-card-row">
                            <span class="hero-card-label">Cấp cứu Ngoại</span>
                            <span class="hero-card-value">24/7 (tai nạn, chấn thương…)</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<main>
    <div class="container">

        <!-- 1. Khi nào nên khám Ngoại khoa -->
        <section id="trieuchung" class="page-section">
            <div class="section-header">
                <h2 class="section-title">Khi nào bạn nên khám tại Khoa Ngoại?</h2>
                <span class="badge badge-khoa">Nhận biết tình huống cần phẫu thuật</span>
            </div>
            <p class="section-desc">
                Nhiều bệnh lý ngoại khoa tiến triển âm thầm nhưng có thể gây biến chứng nặng nếu không được xử trí kịp thời.
                Người bệnh nên đi khám Ngoại khi có các biểu hiện sau:
            </p>

            <div class="grid grid-2 grid-3-lg">
                <div class="card">
                    <h3 class="card-title">Đau bụng dữ dội, kéo dài</h3>
                    <p class="card-text">
                        Đau bụng từng cơn hoặc liên tục, kèm sốt, nôn, bí trung đại tiện – gợi ý viêm ruột thừa,
                        tắc ruột hoặc bệnh lý tiêu hóa cần can thiệp.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Chấn thương, tai nạn</h3>
                    <p class="card-text">
                        Té ngã, tai nạn giao thông, tai nạn sinh hoạt gây sưng đau, biến dạng chi,
                        nghi ngờ gãy xương, trật khớp, vết thương chảy máu.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Khối u, cục lạ dưới da</h3>
                    <p class="card-text">
                        Xuất hiện khối u vùng cổ, vú, bụng, chi… lớn dần theo thời gian, đau hoặc không đau,
                        cần được đánh giá để loại trừ u ác tính.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Thoát vị vùng bẹn, rốn</h3>
                    <p class="card-text">
                        Xuất hiện khối phồng ở vùng bẹn hoặc rốn, tăng khi đứng lâu hoặc ho,
                        có thể đau tức – gợi ý thoát vị, nên khám để tư vấn phẫu thuật.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Bệnh lý hậu môn – trực tràng</h3>
                    <p class="card-text">
                        Đau rát hậu môn, chảy máu khi đi tiêu, sa búi trĩ, nứt kẽ hậu môn…
                        cần được khám và điều trị đúng cách.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Đã được chỉ định phẫu thuật</h3>
                    <p class="card-text">
                        Người bệnh được bác sĩ khuyến cáo cần mổ (cắt ruột thừa, cắt túi mật, mổ thoát vị…)
                        cần đến khám để được tư vấn và chuẩn bị trước mổ.
                    </p>
                </div>
            </div>
        </section>

        <!-- 2. Dịch vụ & kỹ thuật -->
        <section class="page-section">
            <h2 class="section-title">Dịch vụ và kỹ thuật tại Khoa Ngoại</h2>
            <p class="section-desc">
                Một số dịch vụ minh họa trong đồ án ở lĩnh vực Ngoại khoa:
            </p>

            <div class="grid grid-2 grid-3-lg">
                <div class="card">
                    <h3 class="card-title">Khám Ngoại tổng quát</h3>
                    <p class="card-text">
                        Đánh giá các triệu chứng ngoại khoa thường gặp, chỉ định xét nghiệm và chẩn đoán hình ảnh phù hợp.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Tư vấn trước mổ</h3>
                    <p class="card-text">
                        Giải thích lý do phẫu thuật, nguy cơ – lợi ích, hướng dẫn chuẩn bị trước mổ và chăm sóc sau mổ.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Phẫu thuật tiêu hóa – gan mật</h3>
                    <p class="card-text">
                        Minh họa các phẫu thuật như cắt ruột thừa, cắt túi mật, xử trí thủng dạ dày…
                        tùy thực tế từng bệnh viện.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Chấn thương chỉnh hình</h3>
                    <p class="card-text">
                        Nắn chỉnh gãy xương, cố định bột/nẹp, tư vấn phục hồi chức năng sau chấn thương (minh họa).
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Xử trí vết thương mềm</h3>
                    <p class="card-text">
                        Cắt lọc, khâu vết thương, thay băng, chăm sóc vết thương sau mổ, theo dõi nhiễm trùng.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Bệnh lý hậu môn – trực tràng</h3>
                    <p class="card-text">
                        Khám, tư vấn điều trị trĩ, nứt kẽ hậu môn, rò hậu môn… và các bệnh lý liên quan khác.
                    </p>
                </div>
            </div>
        </section>

        <!-- 3. Quy trình khám -->
        <section class="page-section">
            <h2 class="section-title">Quy trình khám tại Khoa Ngoại</h2>
            <div class="card">
                <ol class="list-steps">
                    <li>
                        <span class="step-label">Bước 1 – Đăng ký khám:</span>
                        Đến quầy tiếp nhận hoặc đặt lịch trực tuyến, chọn “Khoa Ngoại”.
                    </li>
                    <li>
                        <span class="step-label">Bước 2 – Khám ban đầu:</span>
                        Bác sĩ hỏi bệnh, khám vùng đau, đánh giá dấu hiệu toàn thân và tại chỗ.
                    </li>
                    <li>
                        <span class="step-label">Bước 3 – Cận lâm sàng:</span>
                        Chỉ định xét nghiệm máu, siêu âm, X-quang, CT… tùy trường hợp bệnh.
                    </li>
                    <li>
                        <span class="step-label">Bước 4 – Quyết định điều trị:</span>
                        Bác sĩ giải thích chẩn đoán và gợi ý điều trị nội khoa hay phẫu thuật.
                    </li>
                    <li>
                        <span class="step-label">Bước 5 – Chuẩn bị mổ & theo dõi:</span>
                        Nếu cần mổ, người bệnh được hướng dẫn làm hồ sơ phẫu thuật và theo dõi sau mổ.
                    </li>
                </ol>
            </div>
        </section>

        <!-- 4. Đội ngũ bác sĩ -->
        <section class="page-section">
            <h2 class="section-title">Đội ngũ bác sĩ (minh họa)</h2>
            <p class="section-desc">
                Đây là thông tin mô phỏng trong đồ án, không phải danh sách bác sĩ thật.
            </p>

            <div class="grid grid-2 grid-3-lg">
                <div class="card">
                    <div class="doctor-avatar" style="background: linear-gradient(to bottom right, #0ea5e9, #2563eb);">
                        NK
                    </div>
                    <h3 class="doctor-name">BSCKII. Phạm Quốc J</h3>
                    <p class="doctor-position">Trưởng khoa Ngoại</p>
                    <p class="doctor-desc">
                        Kinh nghiệm trong phẫu thuật tiêu hóa, gan mật, xử trí các cấp cứu ngoại khoa thường gặp.
                    </p>
                </div>
                <div class="card">
                    <div class="doctor-avatar" style="background: linear-gradient(to bottom right, #ec4899, #fb7185);">
                        CT
                    </div>
                    <h3 class="doctor-name">ThS. BS. Nguyễn Thị K</h3>
                    <p class="doctor-position">Bác sĩ Ngoại chấn thương</p>
                    <p class="doctor-desc">
                        Tập trung điều trị gãy xương, chấn thương khớp và phục hồi vận động sau chấn thương.
                    </p>
                </div>
                <div class="card">
                    <div class="doctor-avatar" style="background: linear-gradient(to bottom right, #10b981, #14b8a6);">
                        HM
                    </div>
                    <h3 class="doctor-name">BS. Lê Minh L</h3>
                    <p class="doctor-position">Bác sĩ Ngoại tổng quát</p>
                    <p class="doctor-desc">
                        Phụ trách khám và theo dõi các bệnh lý ngoại khoa thông thường, tư vấn điều trị và chăm sóc sau mổ.
                    </p>
                </div>
            </div>
        </section>

        <!-- 5. Cơ sở vật chất -->
        <section class="page-section">
            <h2 class="section-title">Cơ sở vật chất – thiết bị</h2>
            <div class="card">
                <p class="card-text">
                    Khoa Ngoại được trang bị các phương tiện cơ bản phục vụ phẫu thuật và chăm sóc sau mổ
                    (nội dung mô phỏng cho đồ án):
                </p>
                <ul class="facilities-list">
                    <li>Phòng mổ vô khuẩn với hệ thống đèn mổ, máy gây mê – hồi sức.</li>
                    <li>Thiết bị theo dõi mạch, huyết áp, SpO2 trong và sau mổ.</li>
                    <li>Phòng hậu phẫu với giường bệnh có monitor theo dõi.</li>
                    <li>Dụng cụ phẫu thuật cơ bản cho Ngoại tổng quát và chấn thương.</li>
                </ul>
                <p class="note-small">
                    * Thông tin trên mang tính minh họa, không phản ánh chính xác trang thiết bị của một bệnh viện cụ thể.
                </p>
            </div>
        </section>

        <!-- 6. FAQ -->
        <section class="page-section">
            <h2 class="section-title">Câu hỏi thường gặp</h2>

            <div class="grid">
                <details class="faq-item">
                    <summary class="faq-summary">
                        <span>Trước khi mổ tôi có phải nhịn ăn không?</span>
                        <span class="faq-arrow">▼</span>
                    </summary>
                    <div class="faq-body">
                        Thông thường, người bệnh được yêu cầu nhịn ăn uống trước mổ vài giờ.
                        Thời gian cụ thể sẽ được bác sĩ và điều dưỡng hướng dẫn tùy loại phẫu thuật.
                    </div>
                </details>

                <details class="faq-item">
                    <summary class="faq-summary">
                        <span>Sau mổ bao lâu thì được xuất viện?</span>
                        <span class="faq-arrow">▼</span>
                    </summary>
                    <div class="faq-body">
                        Thời gian nằm viện phụ thuộc loại phẫu thuật, tình trạng hồi phục và bệnh nền đi kèm.
                        Bác sĩ sẽ đánh giá hàng ngày và thông báo khi đủ điều kiện xuất viện.
                    </div>
                </details>

                <details class="faq-item">
                    <summary class="faq-summary">
                        <span>Vết mổ có cần kiêng tắm không?</span>
                        <span class="faq-arrow">▼</span>
                    </summary>
                    <div class="faq-body">
                        Việc tắm rửa sau mổ cần tuân theo hướng dẫn của bác sĩ/điều dưỡng.
                        Thông thường cần giữ khô vùng vết mổ cho đến khi vết thương ổn định.
                    </div>
                </details>
            </div>
        </section>

        <!-- 7. Liên hệ – Đặt lịch -->
        <section id="dat-lich" class="page-section">
            <div class="contact-section">
                <div class="contact-left">
                    <h2 class="contact-left-title">Cần tư vấn hoặc đặt lịch khám Ngoại khoa?</h2>
                    <p class="contact-left-text">
                        Liên hệ tổng đài hoặc đặt lịch trực tuyến để được tư vấn.
                        Nếu gặp chấn thương nặng, đau bụng dữ dội, hãy đến khoa Cấp cứu ngay.
                    </p>
                    <p class="contact-left-note">
                        Thông tin trên website chỉ mang tính minh họa, không dùng để tự chẩn đoán hoặc tự điều trị.
                    </p>
                </div>
                <div class="contact-right">
                    <div>
                        <span class="contact-label">Hotline tư vấn</span>
                        <div class="contact-value-main">1900 0000</div>
                    </div>
                    <div style="margin-top: 6px;">
                        <span class="contact-label">Cấp cứu Ngoại 24/7</span>
                        <div class="contact-value-sub">115 (hoặc số cấp cứu địa phương)</div>
                    </div>
                    <div class="contact-btn-wrapper">
                        <button type="button" class="btn-contact">
                            Đặt lịch khám (minh họa)
                        </button>
                    </div>
                </div>
            </div>
        </section>

    </div>
</main>

</body>
</html>
