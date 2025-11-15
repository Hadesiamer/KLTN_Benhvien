<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khoa Nội Tiết - Bệnh viện</title>
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

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg);
            color: var(--text-main);
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        .container {
            width: 100%;
            max-width: 1120px;
            margin: 0 auto;
            padding: 0 16px;
        }

        .page-section {
            margin-bottom: 40px;
        }

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

        .alert-text {
            color: #b45309;
        }

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

        .hero-left, .hero-right {
            flex: 1;
        }

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

        @media (min-width: 768px) {
            .hero-title { font-size: 34px; }
        }

        @media (min-width: 1024px) {
            .hero-title { font-size: 40px; }
        }

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
            transition: background-color 0.15s ease, color 0.15s ease, border-color 0.15s ease;
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

        .grid {
            display: grid;
            gap: 16px;
        }

        @media (min-width: 768px) {
            .grid-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }

        @media (min-width: 1024px) {
            .grid-3-lg { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        }

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

        .section-title {
            font-size: 20px;
            font-weight: 700;
            margin: 0;
        }

        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
            gap: 8px;
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

        .contact-btn-wrapper {
            margin-top: 8px;
        }

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

<!-- THÔNG BÁO ĐỒ ÁN -->
<div class="alert">
    <div class="container">
        <div class="alert-inner">
            <div class="alert-icon">
                ⚠️
            </div>
            <div>
                <div class="alert-title">Lưu ý</div>
                <div class="alert-text">
                    Đây là website trong đồ án sinh viên, nội dung chỉ mang tính minh họa, không thay thế tư vấn y khoa thực tế.
                </div>
            </div>
        </div>
    </div>
</div>

<!-- HERO -->
<section class="hero">
    <div class="container">
        <div class="hero-inner">
            <div class="hero-left">
                <div class="hero-pill">
                    <span class="hero-pill-dot"></span>
                    Khoa chuyên sâu – Nội Tiết
                </div>
                <h1 class="hero-title">Khoa Nội Tiết</h1>
                <p class="hero-desc">
                    Khoa Nội Tiết tập trung chẩn đoán và theo dõi các rối loạn hormone trong cơ thể như
                    đái tháo đường, bệnh tuyến giáp, tuyến thượng thận, rối loạn mỡ máu, béo phì…
                    Mục tiêu là kiểm soát bệnh lâu dài, giảm biến chứng và nâng cao chất lượng cuộc sống.
                </p>
                <div class="hero-actions">
                    <a href="#dat-lich" class="btn btn-primary">Đặt lịch khám Nội Tiết</a>
                    <a href="#trieuchung" class="btn btn-outline-light">Khi nào nên đi khám?</a>
                </div>
            </div>
            <div class="hero-right">
                <div class="hero-card">
                    <h2 class="hero-card-title">Thông tin nhanh</h2>
                    <ul class="hero-card-list">
                        <li class="hero-card-row">
                            <span class="hero-card-label">Địa điểm</span>
                            <span class="hero-card-value">Tầng 2 – Khu B</span>
                        </li>
                        <li class="hero-card-row">
                            <span class="hero-card-label">Giờ làm việc</span>
                            <span class="hero-card-value">Thứ 2 – Thứ 6: 7:00 – 17:00</span>
                        </li>
                        <li class="hero-card-row">
                            <span class="hero-card-label">Khám thứ 7</span>
                            <span class="hero-card-value">7:00 – 12:00</span>
                        </li>
                        <li class="hero-card-row">
                            <span class="hero-card-label">Theo dõi bệnh mạn</span>
                            <span class="hero-card-value">Đái tháo đường, tuyến giáp, rối loạn mỡ máu…</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<main>
    <div class="container">

        <!-- 1. KHI NÀO NÊN KHÁM -->
        <section id="trieuchung" class="page-section">
            <div class="section-header">
                <h2 class="section-title">Khi nào bạn nên khám tại Khoa Nội Tiết?</h2>
                <span class="badge badge-khoa">Nhận biết sớm bất thường</span>
            </div>
            <p class="section-desc">
                Các rối loạn nội tiết thường diễn tiến âm thầm nhưng gây nhiều biến chứng trên tim mạch, thận,
                mắt và thần kinh. Người bệnh nên đi khám sớm khi có những biểu hiện sau.
            </p>

            <div class="grid grid-2 grid-3-lg">
                <div class="card">
                    <h3 class="card-title">Khát nhiều, tiểu nhiều</h3>
                    <p class="card-text">
                        Uống nước liên tục, tiểu nhiều lần trong ngày, sụt cân không rõ lý do là những dấu hiệu gợi ý đái tháo đường.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Tăng hoặc sụt cân bất thường</h3>
                    <p class="card-text">
                        Tăng cân nhanh dù ăn không nhiều, hoặc sụt cân dù ăn uống bình thường, kèm mệt mỏi kéo dài.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Tim đập nhanh, run tay, nóng bức</h3>
                    <p class="card-text">
                        Cảm giác hồi hộp, run tay, khó chịu với nóng, dễ đổ mồ hôi, sụt cân – có thể liên quan đến cường giáp.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Lạnh nhiều, mệt mỏi, khô da</h3>
                    <p class="card-text">
                        Hay lạnh, táo bón, tăng cân nhẹ, da khô, buồn ngủ nhiều – có thể là biểu hiện của suy giáp.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Huyết áp, mỡ máu cao</h3>
                    <p class="card-text">
                        Huyết áp cao, cholesterol hoặc triglycerid tăng kéo dài, cần được tư vấn phối hợp giữa tim mạch và nội tiết.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Rối loạn kinh nguyệt, khó mang thai</h3>
                    <p class="card-text">
                        Chu kỳ kinh không đều, kéo dài hoặc vô kinh, có thể liên quan đến rối loạn nội tiết, hội chứng buồng trứng đa nang.
                    </p>
                </div>
            </div>
        </section>

        <!-- 2. DỊCH VỤ & KỸ THUẬT -->
        <section class="page-section">
            <h2 class="section-title">Dịch vụ và kỹ thuật tại Khoa Nội Tiết</h2>
            <p class="section-desc">
                Một số dịch vụ minh họa trong đồ án cho lĩnh vực Nội Tiết:
            </p>

            <div class="grid grid-2 grid-3-lg">
                <div class="card">
                    <h3 class="card-title">Khám và tư vấn đái tháo đường</h3>
                    <p class="card-text">
                        Đánh giá đường huyết, tư vấn chế độ ăn uống, vận động và dùng thuốc theo hướng dẫn của bác sĩ.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Theo dõi biến chứng đái tháo đường</h3>
                    <p class="card-text">
                        Hướng dẫn tầm soát biến chứng trên mắt, thận, thần kinh ngoại biên và tim mạch.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Khám và điều trị bệnh tuyến giáp</h3>
                    <p class="card-text">
                        Đánh giá bướu giáp, cường giáp, suy giáp, tư vấn các phương án điều trị và theo dõi lâu dài.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Quản lý rối loạn mỡ máu</h3>
                    <p class="card-text">
                        Kết hợp thay đổi lối sống và dùng thuốc theo chỉ định, nhằm giảm nguy cơ bệnh tim mạch và đột quỵ.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Tư vấn dinh dưỡng – béo phì</h3>
                    <p class="card-text">
                        Hướng dẫn chế độ ăn hợp lý, xây dựng kế hoạch giảm cân an toàn và theo dõi chỉ số cơ thể.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Theo dõi các rối loạn hormone khác</h3>
                    <p class="card-text">
                        Các bệnh lý tuyến thượng thận, tuyến yên, rối loạn nội tiết sinh sản… được tư vấn và điều phối khám chuyên khoa khi cần.
                    </p>
                </div>
            </div>
        </section>

        <!-- 3. QUY TRÌNH KHÁM -->
        <section class="page-section">
            <h2 class="section-title">Quy trình khám tại Khoa Nội Tiết</h2>
            <div class="card">
                <ol class="list-steps">
                    <li>
                        <span class="step-label">Bước 1 – Đăng ký khám:</span>
                        Đến quầy tiếp nhận hoặc đặt lịch trực tuyến, chọn “Khoa Nội Tiết”.
                    </li>
                    <li>
                        <span class="step-label">Bước 2 – Khai thác bệnh sử:</span>
                        Bác sĩ hỏi về triệu chứng, thói quen sinh hoạt, tiền sử bệnh và thuốc đang sử dụng.
                    </li>
                    <li>
                        <span class="step-label">Bước 3 – Xét nghiệm cận lâm sàng:</span>
                        Xét nghiệm đường huyết, HbA1c, mỡ máu, hormone tuyến giáp, hoặc các xét nghiệm khác tùy trường hợp.
                    </li>
                    <li>
                        <span class="step-label">Bước 4 – Đánh giá và tư vấn:</span>
                        Giải thích kết quả, chẩn đoán và đưa ra kế hoạch điều trị, chế độ ăn uống – vận động phù hợp.
                    </li>
                    <li>
                        <span class="step-label">Bước 5 – Theo dõi định kỳ:</span>
                        Hẹn lịch tái khám, kiểm tra lại xét nghiệm và điều chỉnh điều trị khi cần.
                    </li>
                </ol>
            </div>
        </section>

        <!-- 4. ĐỘI NGŨ BÁC SĨ (MINH HỌA) -->
        <section class="page-section">
            <h2 class="section-title">Đội ngũ bác sĩ (minh họa)</h2>
            <p class="section-desc">
                Đây là thông tin mô phỏng trong đồ án, không phải danh sách bác sĩ thật.
            </p>

            <div class="grid grid-2 grid-3-lg">
                <div class="card">
                    <div class="doctor-avatar" style="background: linear-gradient(to bottom right, #0ea5e9, #2563eb);">
                        NT
                    </div>
                    <h3 class="doctor-name">BSCKII. Lê Anh G</h3>
                    <p class="doctor-position">Trưởng khoa Nội Tiết</p>
                    <p class="doctor-desc">
                        Kinh nghiệm trong quản lý đái tháo đường type 1, type 2 và các biến chứng mạn tính liên quan.
                    </p>
                </div>
                <div class="card">
                    <div class="doctor-avatar" style="background: linear-gradient(to bottom right, #ec4899, #fb7185);">
                        TH
                    </div>
                    <h3 class="doctor-name">ThS. BS. Trần Hải H</h3>
                    <p class="doctor-position">Bác sĩ Nội Tiết – Tuyến giáp</p>
                    <p class="doctor-desc">
                        Tập trung chẩn đoán và điều trị các bệnh lý tuyến giáp, hướng dẫn theo dõi lâu dài.
                    </p>
                </div>
                <div class="card">
                    <div class="doctor-avatar" style="background: linear-gradient(to bottom right, #10b981, #14b8a6);">
                        DP
                    </div>
                    <h3 class="doctor-name">BS. Đỗ Phương I</h3>
                    <p class="doctor-position">Bác sĩ Nội Tiết – Dinh dưỡng</p>
                    <p class="doctor-desc">
                        Quan tâm đến dinh dưỡng lâm sàng, hỗ trợ người bệnh xây dựng lối sống lành mạnh và bền vững.
                    </p>
                </div>
            </div>
        </section>

        <!-- 5. CƠ SỞ VẬT CHẤT -->
        <section class="page-section">
            <h2 class="section-title">Cơ sở vật chất – thiết bị</h2>
            <div class="card">
                <p class="card-text">
                    Khoa Nội Tiết được hỗ trợ bởi hệ thống xét nghiệm và chẩn đoán hình ảnh của bệnh viện
                    (nội dung mô phỏng cho đồ án):
                </p>
                <ul class="facilities-list">
                    <li>Hệ thống xét nghiệm sinh hóa – miễn dịch để đo đường huyết, HbA1c, hormone, mỡ máu…</li>
                    <li>Thiết bị đo đường huyết tại chỗ (POC) phục vụ khám ngoại trú.</li>
                    <li>Liên kết với khoa Chẩn đoán hình ảnh trong đánh giá tuyến giáp, tuyến thượng thận…</li>
                    <li>Khu tư vấn dinh dưỡng – giáo dục sức khỏe cho người bệnh nội tiết.</li>
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
                        <span>Đái tháo đường có phải kiêng hoàn toàn đồ ngọt không?</span>
                        <span class="faq-arrow">▼</span>
                    </summary>
                    <div class="faq-body">
                        Người bệnh thường được khuyên hạn chế đồ ngọt, nhưng chế độ ăn cụ thể cần được tư vấn bởi bác sĩ
                        hoặc chuyên gia dinh dưỡng, tùy mức độ bệnh và điều trị.
                    </div>
                </details>

                <details class="faq-item">
                    <summary class="faq-summary">
                        <span>Uống thuốc tuyến giáp có phải dùng suốt đời không?</span>
                        <span class="faq-arrow">▼</span>
                    </summary>
                    <div class="faq-body">
                        Một số bệnh lý tuyến giáp cần dùng thuốc lâu dài, nhưng cũng có trường hợp chỉ cần điều trị trong một giai đoạn.
                        Quyết định này phải dựa trên đánh giá của bác sĩ.
                    </div>
                </details>

                <details class="faq-item">
                    <summary class="faq-summary">
                        <span>Béo phì có phải lúc nào cũng do ăn nhiều không?</span>
                        <span class="faq-arrow">▼</span>
                    </summary>
                    <div class="faq-body">
                        Béo phì có thể liên quan đến chế độ ăn, lối sống, nhưng cũng có thể bị ảnh hưởng bởi yếu tố nội tiết,
                        di truyền hoặc thuốc. Cần được đánh giá đầy đủ trước khi xây dựng kế hoạch giảm cân.
                    </div>
                </details>
            </div>
        </section>

        <!-- 7. LIÊN HỆ – ĐẶT LỊCH -->
        <section id="dat-lich" class="page-section">
            <div class="contact-section">
                <div class="contact-left">
                    <h2 class="contact-left-title">Cần tư vấn hoặc đặt lịch khám Nội Tiết?</h2>
                    <p class="contact-left-text">
                        Liên hệ tổng đài hoặc đặt lịch trực tuyến để được hỗ trợ.
                        Việc theo dõi định kỳ giúp kiểm soát tốt bệnh và hạn chế biến chứng lâu dài.
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
                        <span class="contact-label">Hỗ trợ bệnh nội tiết</span>
                        <div class="contact-value-sub">Theo giờ hành chính</div>
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
