<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khoa Thần Kinh - Bệnh viện</title>
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

        /* ========== LAYOUT CHUNG ========== */
        .container {
            width: 100%;
            max-width: 1120px;
            margin: 0 auto;
            padding: 0 16px;
        }

        .page-section {
            margin-bottom: 40px;
        }

        .page-title {
            font-size: 24px;
            font-weight: 700;
            margin: 0 0 12px;
        }

        .page-subtitle {
            font-size: 14px;
            color: var(--text-sub);
            margin-bottom: 16px;
            line-height: 1.6;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 500;
        }

        .badge-outline {
            border: 1px solid rgba(255, 255, 255, 0.7);
            color: #f9fafb;
            background-color: rgba(255, 255, 255, 0.06);
        }

        .badge-khoa {
            background: linear-gradient(135deg, var(--primary) 0%, var(--cyan) 100%);
            color: var(--white);
            box-shadow: 0 8px 20px rgba(14, 165, 233, 0.4);
        }

        /* ========== ALERT ĐỒ ÁN ========== */
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

        /* ========== HERO ========== */
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

        .hero-left {
            flex: 1;
        }

        .hero-right {
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
            .hero-title {
                font-size: 34px;
            }
        }

        @media (min-width: 1024px) {
            .hero-title {
                font-size: 40px;
            }
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

        .btn-primary:hover {
            background-color: #eff6ff;
        }

        .btn-outline-light {
            background: transparent;
            color: var(--white);
            border-color: rgba(255, 255, 255, 0.7);
        }

        .btn-outline-light:hover {
            background-color: rgba(255, 255, 255, 0.12);
        }

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

        .hero-card-label {
            color: #e0f2fe;
        }

        .hero-card-value {
            font-weight: 600;
        }

        /* ========== LƯỚI & CARD ========== */
        .grid {
            display: grid;
            gap: 16px;
        }

        @media (min-width: 768px) {
            .grid-2 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (min-width: 1024px) {
            .grid-3-lg {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
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

        /* ========== TITLES ========== */
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

        /* ========== DANH SÁCH CÓ THỨ TỰ ========== */
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

        /* ========== BÁC SĨ (MINH HỌA) ========== */
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

        /* ========== CƠ SỞ VẬT CHẤT ========== */
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

        /* ========== FAQ ========== */
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

        .faq-summary:hover {
            background-color: #f9fafb;
        }

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

        /* ========== KHỐI LIÊN HỆ ========== */
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

        .btn-contact:hover {
            background-color: #eff6ff;
        }

        /* Responsive spacing chung */
        main {
            padding: 24px 0 40px;
        }

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
                    Khoa chuyên sâu – Thần Kinh
                </div>
                <h1 class="hero-title">Khoa Thần Kinh</h1>
                <p class="hero-desc">
                    Khoa Thần Kinh tiếp nhận, chẩn đoán và theo dõi các bệnh lý liên quan đến não, tủy sống,
                    dây thần kinh và cơ, như đột quỵ, động kinh, đau đầu kéo dài, rối loạn vận động…
                    Mục tiêu là giúp người bệnh phát hiện sớm và hạn chế di chứng thần kinh lâu dài.
                </p>
                <div class="hero-actions">
                    <a href="#dat-lich" class="btn btn-primary">Đặt lịch khám Thần Kinh</a>
                    <a href="#trieuchung" class="btn btn-outline-light">Khi nào nên đi khám?</a>
                </div>
            </div>
            <div class="hero-right">
                <div class="hero-card">
                    <h2 class="hero-card-title">Thông tin nhanh</h2>
                    <ul class="hero-card-list">
                        <li class="hero-card-row">
                            <span class="hero-card-label">Địa điểm</span>
                            <span class="hero-card-value">Tầng 4 – Khu B</span>
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
                            <span class="hero-card-label">Cấp cứu Thần Kinh</span>
                            <span class="hero-card-value">Hoạt động 24/7 (đột quỵ, hôn mê…)</span>
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
                <h2 class="section-title">Khi nào bạn nên khám tại Khoa Thần Kinh?</h2>
                <span class="badge badge-khoa">Nhận biết sớm triệu chứng</span>
            </div>
            <p class="section-desc">
                Hệ thần kinh điều khiển hầu hết hoạt động của cơ thể. Một số dấu hiệu bất thường cần được
                khám sớm để hạn chế nguy cơ đột quỵ, yếu liệt hoặc suy giảm trí nhớ kéo dài.
            </p>

            <div class="grid grid-2 grid-3-lg">
                <div class="card">
                    <h3 class="card-title">Đau đầu kéo dài, dữ dội</h3>
                    <p class="card-text">
                        Đau đầu nhiều ngày, đau kèm buồn nôn, sợ ánh sáng, hoặc đau đầu dữ dội đột ngột khác thường.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Yếu liệt tay chân</h3>
                    <p class="card-text">
                        Yếu hoặc tê nửa người, liệt tay/chân, khó cầm nắm, đi lại loạng choạng, đột ngột xuất hiện.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Rối loạn ngôn ngữ</h3>
                    <p class="card-text">
                        Nói khó, nói đớ, nói không rõ lời, khó tìm từ, hoặc không hiểu lời người khác nói.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Chóng mặt, mất thăng bằng</h3>
                    <p class="card-text">
                        Cảm giác quay cuồng, xây xẩm, khó đứng vững, dễ té ngã, đặc biệt khi kèm yếu tay chân hoặc nói khó.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Co giật, động kinh</h3>
                    <p class="card-text">
                        Cơn co giật toàn thân hoặc giật một vùng, kèm mất ý thức thoáng qua, sùi bọt mép, cắn lưỡi…
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Suy giảm trí nhớ, thay đổi hành vi</h3>
                    <p class="card-text">
                        Hay quên, lặp lại câu hỏi, khó tập trung, thay đổi cảm xúc hoặc hành vi khác thường kéo dài.
                    </p>
                </div>
            </div>
        </section>

        <!-- 2. DỊCH VỤ & KỸ THUẬT -->
        <section class="page-section">
            <h2 class="section-title">Dịch vụ và kỹ thuật tại Khoa Thần Kinh</h2>
            <p class="section-desc">
                Một số dịch vụ minh họa trong đồ án cho lĩnh vực Thần Kinh:
            </p>

            <div class="grid grid-2 grid-3-lg">
                <div class="card">
                    <h3 class="card-title">Khám thần kinh tổng quát</h3>
                    <p class="card-text">
                        Đánh giá vận động, cảm giác, phản xạ, thăng bằng, trí nhớ và các chức năng thần kinh khác.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Tầm soát đột quỵ</h3>
                    <p class="card-text">
                        Khai thác yếu tố nguy cơ, hướng dẫn theo dõi huyết áp, đường huyết, mỡ máu,
                        tư vấn phát hiện sớm dấu hiệu đột quỵ.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Điện não đồ (EEG)</h3>
                    <p class="card-text">
                        Ghi lại hoạt động điện của não, hỗ trợ chẩn đoán động kinh và một số rối loạn thần kinh khác.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Đo dẫn truyền thần kinh – cơ</h3>
                    <p class="card-text">
                        Hỗ trợ đánh giá bệnh lý thần kinh ngoại biên, hội chứng ống cổ tay, bệnh lý cơ…
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Theo dõi đau đầu mạn tính</h3>
                    <p class="card-text">
                        Khám định kỳ, hướng dẫn ghi nhận nhật ký đau đầu, tư vấn lối sống và dùng thuốc theo chỉ định.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Quản lý bệnh Parkinson & rối loạn vận động</h3>
                    <p class="card-text">
                        Theo dõi triệu chứng run, cứng cơ, chậm vận động; điều chỉnh thuốc và hướng dẫn tập luyện hỗ trợ.
                    </p>
                </div>
            </div>
        </section>

        <!-- 3. QUY TRÌNH KHÁM -->
        <section class="page-section">
            <h2 class="section-title">Quy trình khám tại Khoa Thần Kinh</h2>
            <div class="card">
                <ol class="list-steps">
                    <li>
                        <span class="step-label">Bước 1 – Đăng ký khám:</span>
                        Đến quầy tiếp nhận hoặc đặt lịch trực tuyến, chọn “Khoa Thần Kinh”.
                    </li>
                    <li>
                        <span class="step-label">Bước 2 – Khám ban đầu:</span>
                        Bác sĩ hỏi bệnh, khám các dấu hiệu thần kinh, kiểm tra thăng bằng, vận động, cảm giác.
                    </li>
                    <li>
                        <span class="step-label">Bước 3 – Cận lâm sàng (nếu cần):</span>
                        Thực hiện điện não đồ, chụp CT/MRI, xét nghiệm máu hoặc các test chuyên biệt khác theo chỉ định.
                    </li>
                    <li>
                        <span class="step-label">Bước 4 – Chẩn đoán và tư vấn:</span>
                        Giải thích kết quả, đánh giá nguy cơ và đề xuất hướng điều trị, phục hồi chức năng.
                    </li>
                    <li>
                        <span class="step-label">Bước 5 – Kê đơn – hẹn tái khám:</span>
                        Hướng dẫn dùng thuốc, theo dõi tại nhà và lịch tái khám, đặc biệt với bệnh mạn tính hoặc có nguy cơ tái phát.
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
                        TK
                    </div>
                    <h3 class="doctor-name">BSCKII. Nguyễn Minh D</h3>
                    <p class="doctor-position">Trưởng khoa Thần Kinh</p>
                    <p class="doctor-desc">
                        Kinh nghiệm trong chẩn đoán và điều trị đột quỵ não, bệnh mạch máu não, rối loạn vận động.
                    </p>
                </div>
                <div class="card">
                    <div class="doctor-avatar" style="background: linear-gradient(to bottom right, #ec4899, #fb7185);">
                        HV
                    </div>
                    <h3 class="doctor-name">ThS. BS. Hoàng Vân E</h3>
                    <p class="doctor-position">Bác sĩ thần kinh</p>
                    <p class="doctor-desc">
                        Quan tâm đến các bệnh lý đau đầu mạn tính, động kinh, rối loạn giấc ngủ và suy giảm trí nhớ.
                    </p>
                </div>
                <div class="card">
                    <div class="doctor-avatar" style="background: linear-gradient(to bottom right, #10b981, #14b8a6);">
                        QA
                    </div>
                    <h3 class="doctor-name">BS. Trịnh Quang F</h3>
                    <p class="doctor-position">Bác sĩ thần kinh – cơ</p>
                    <p class="doctor-desc">
                        Phụ trách chẩn đoán các bệnh lý thần kinh ngoại biên, bệnh lý cơ và rối loạn dẫn truyền thần kinh.
                    </p>
                </div>
            </div>
        </section>

        <!-- 5. CƠ SỞ VẬT CHẤT -->
        <section class="page-section">
            <h2 class="section-title">Cơ sở vật chất – thiết bị</h2>
            <div class="card">
                <p class="card-text">
                    Khoa Thần Kinh được trang bị các phương tiện cơ bản phục vụ khám và theo dõi người bệnh
                    (nội dung mô phỏng cho đồ án):
                </p>
                <ul class="facilities-list">
                    <li>Máy điện não đồ (EEG).</li>
                    <li>Hệ thống chụp CT/MRI (liên kết khoa Chẩn đoán hình ảnh).</li>
                    <li>Thiết bị đo dẫn truyền thần kinh – cơ (minh họa).</li>
                    <li>Giường bệnh có monitor theo dõi các chỉ số sinh tồn.</li>
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
                        <span>Đau đầu kéo dài có cần chụp MRI/CT ngay không?</span>
                        <span class="faq-arrow">▼</span>
                    </summary>
                    <div class="faq-body">
                        Không phải mọi trường hợp đau đầu đều cần chụp MRI/CT.
                        Bác sĩ sẽ căn cứ vào triệu chứng, thời gian đau, yếu tố nguy cơ đi kèm
                        để chỉ định cận lâm sàng phù hợp.
                    </div>
                </details>

                <details class="faq-item">
                    <summary class="faq-summary">
                        <span>Triệu chứng nào có thể là dấu hiệu đột quỵ?</span>
                        <span class="faq-arrow">▼</span>
                    </summary>
                    <div class="faq-body">
                        Đột ngột méo miệng, yếu/nặng nửa người, nói khó, nhìn mờ, đau đầu dữ dội khác thường…
                        Đây là những dấu hiệu cảnh báo, cần đi cấp cứu ngay.
                    </div>
                </details>

                <details class="faq-item">
                    <summary class="faq-summary">
                        <span>Người lớn tuổi hay quên có phải bị sa sút trí tuệ không?</span>
                        <span class="faq-arrow">▼</span>
                    </summary>
                    <div class="faq-body">
                        Hay quên có thể do nhiều nguyên nhân: căng thẳng, thiếu ngủ, trầm cảm, hoặc bệnh lý thần kinh.
                        Nếu tình trạng kéo dài, ảnh hưởng sinh hoạt, nên đến Khoa Thần Kinh để được đánh giá chi tiết.
                    </div>
                </details>
            </div>
        </section>

        <!-- 7. LIÊN HỆ – ĐẶT LỊCH -->
        <section id="dat-lich" class="page-section">
            <div class="contact-section">
                <div class="contact-left">
                    <h2 class="contact-left-title">Cần tư vấn hoặc đặt lịch khám Thần Kinh?</h2>
                    <p class="contact-left-text">
                        Liên hệ tổng đài hoặc đặt lịch trực tuyến để được hỗ trợ.
                        Nếu xuất hiện yếu liệt đột ngột, méo miệng, nói khó, hãy gọi cấp cứu ngay.
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
                        <span class="contact-label">Cấp cứu Thần Kinh 24/7</span>
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
