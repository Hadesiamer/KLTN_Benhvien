<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khoa Tim Mạch - Bệnh viện</title>
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

        /* ========== ICON TẠM BẰNG SVG ========== */
        .icon-circle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 999px;
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
                <!-- icon tam -->
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
                    Khoa chuyên sâu – Tim Mạch
                </div>
                <h1 class="hero-title">Khoa Tim Mạch</h1>
                <p class="hero-desc">
                    Khoa Tim Mạch tiếp nhận, chẩn đoán và điều trị các bệnh lý về tim và hệ tuần hoàn
                    như tăng huyết áp, bệnh mạch vành, suy tim, rối loạn nhịp tim…
                    Mục tiêu là giúp người bệnh sống khỏe, phòng ngừa biến chứng tim mạch nguy hiểm.
                </p>
                <div class="hero-actions">
                    <a href="#dat-lich" class="btn btn-primary">Đặt lịch khám Tim Mạch</a>
                    <a href="#trieuchung" class="btn btn-outline-light">Khi nào nên đi khám?</a>
                </div>
            </div>
            <div class="hero-right">
                <div class="hero-card">
                    <h2 class="hero-card-title">Thông tin nhanh</h2>
                    <ul class="hero-card-list">
                        <li class="hero-card-row">
                            <span class="hero-card-label">Địa điểm</span>
                            <span class="hero-card-value">Tầng 3 – Khu B</span>
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
                            <span class="hero-card-label">Cấp cứu Tim Mạch</span>
                            <span class="hero-card-value">Hoạt động 24/7</span>
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
                <h2 class="section-title">Khi nào bạn nên khám tại Khoa Tim Mạch?</h2>
                <span class="badge badge-khoa">Nhận biết sớm triệu chứng</span>
            </div>
            <p class="section-desc">
                Người bệnh nên sớm đến khám tại Khoa Tim Mạch nếu có một trong các dấu hiệu sau.
                Việc phát hiện và điều trị sớm giúp hạn chế biến chứng nhồi máu cơ tim, đột quỵ, suy tim…
            </p>

            <div class="grid grid-2 grid-3-lg">
                <div class="card">
                    <h3 class="card-title">Đau tức ngực, nặng ngực</h3>
                    <p class="card-text">
                        Cảm giác bóp nghẹt, đè nặng vùng ngực, đặc biệt khi gắng sức, leo cầu thang hoặc xúc động.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Khó thở, hụt hơi</h3>
                    <p class="card-text">
                        Khó thở khi vận động nhẹ, khó thở về đêm, phải kê cao gối khi ngủ, mệt mỏi kéo dài.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Tim đập nhanh, hồi hộp</h3>
                    <p class="card-text">
                        Cảm giác đánh trống ngực, tim đập nhanh, loạn nhịp hoặc bỏ nhịp, kèm chóng mặt, choáng váng.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Huyết áp bất thường</h3>
                    <p class="card-text">
                        Huyết áp cao kéo dài, khó kiểm soát hoặc tụt huyết áp gây choáng váng, xây xẩm.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Phù chân, tăng cân nhanh</h3>
                    <p class="card-text">
                        Phù mắt cá chân, cẳng chân, cảm giác nặng chân, khó thở, tăng cân nhanh do ứ dịch – gợi ý suy tim.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Yếu tố nguy cơ cao</h3>
                    <p class="card-text">
                        Người có tiểu đường, mỡ máu cao, hút thuốc lá, thừa cân, tiền sử gia đình bệnh tim mạch nên khám định kỳ.
                    </p>
                </div>
            </div>
        </section>

        <!-- 2. DỊCH VỤ & KỸ THUẬT -->
        <section class="page-section">
            <h2 class="section-title">Dịch vụ và kỹ thuật tại Khoa Tim Mạch</h2>
            <p class="section-desc">
                Khoa Tim Mạch triển khai nhiều kỹ thuật chẩn đoán và điều trị tim mạch phổ biến.
                Một số dịch vụ minh họa trong đồ án:
            </p>

            <div class="grid grid-2 grid-3-lg">
                <div class="card">
                    <h3 class="card-title">Khám tim mạch tổng quát</h3>
                    <p class="card-text">
                        Khám lâm sàng, đo huyết áp, đánh giá nguy cơ tim mạch và tư vấn chế độ sinh hoạt.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Điện tâm đồ (ECG)</h3>
                    <p class="card-text">
                        Ghi lại hoạt động điện của tim, phát hiện rối loạn nhịp, thiếu máu cơ tim, dày thất…
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Siêu âm tim Doppler</h3>
                    <p class="card-text">
                        Đánh giá cấu trúc van tim, chức năng co bóp, dòng chảy qua các buồng tim và mạch máu lớn.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Holter điện tâm đồ 24 giờ</h3>
                    <p class="card-text">
                        Theo dõi nhịp tim liên tục trong ngày, phát hiện các rối loạn nhịp thoáng qua khó nhận biết.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Tư vấn điều trị tăng huyết áp</h3>
                    <p class="card-text">
                        Lập kế hoạch điều trị lâu dài, hướng dẫn dùng thuốc, chế độ ăn giảm muối, theo dõi tại nhà.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Quản lý bệnh mạch vành, suy tim</h3>
                    <p class="card-text">
                        Theo dõi định kỳ, điều chỉnh thuốc, hướng dẫn tập luyện và nhận biết dấu hiệu báo động.
                    </p>
                </div>
            </div>
        </section>

        <!-- 3. QUY TRÌNH KHÁM -->
        <section class="page-section">
            <h2 class="section-title">Quy trình khám tại Khoa Tim Mạch</h2>
            <div class="card">
                <ol class="list-steps">
                    <li>
                        <span class="step-label">Bước 1 – Đăng ký khám:</span>
                        Đến quầy tiếp nhận hoặc đặt lịch trực tuyến, chọn “Khoa Tim Mạch”.
                    </li>
                    <li>
                        <span class="step-label">Bước 2 – Khám ban đầu:</span>
                        Bác sĩ hỏi bệnh, đo huyết áp, nghe tim, đánh giá triệu chứng và yếu tố nguy cơ.
                    </li>
                    <li>
                        <span class="step-label">Bước 3 – Cận lâm sàng (nếu cần):</span>
                        Làm điện tâm đồ, siêu âm tim, xét nghiệm máu, X-quang… theo chỉ định.
                    </li>
                    <li>
                        <span class="step-label">Bước 4 – Chẩn đoán và tư vấn:</span>
                        Bác sĩ giải thích kết quả, đưa ra chẩn đoán và kế hoạch điều trị.
                    </li>
                    <li>
                        <span class="step-label">Bước 5 – Kê đơn – hẹn tái khám:</span>
                        Nhận đơn thuốc, hướng dẫn theo dõi tại nhà và lịch tái khám phù hợp.
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
                        TM
                    </div>
                    <h3 class="doctor-name">BSCKII. Nguyễn Văn A</h3>
                    <p class="doctor-position">Trưởng khoa Tim Mạch</p>
                    <p class="doctor-desc">
                        Chuyên điều trị bệnh mạch vành, suy tim mạn, trên 15 năm kinh nghiệm trong lĩnh vực tim mạch.
                    </p>
                </div>
                <div class="card">
                    <div class="doctor-avatar" style="background: linear-gradient(to bottom right, #ec4899, #fb7185);">
                        TT
                    </div>
                    <h3 class="doctor-name">ThS. BS. Trần Thị B</h3>
                    <p class="doctor-position">Bác sĩ siêu âm tim</p>
                    <p class="doctor-desc">
                        Thế mạnh siêu âm tim Doppler, đánh giá bệnh van tim, bệnh tim bẩm sinh người lớn.
                    </p>
                </div>
                <div class="card">
                    <div class="doctor-avatar" style="background: linear-gradient(to bottom right, #10b981, #14b8a6);">
                        HD
                    </div>
                    <h3 class="doctor-name">BS. Lê Hoàng C</h3>
                    <p class="doctor-position">Bác sĩ tim mạch</p>
                    <p class="doctor-desc">
                        Tập trung quản lý tăng huyết áp, rối loạn mỡ máu, dự phòng biến cố tim mạch cho người nguy cơ cao.
                    </p>
                </div>
            </div>
        </section>

        <!-- 5. CƠ SỞ VẬT CHẤT -->
        <section class="page-section">
            <h2 class="section-title">Cơ sở vật chất – thiết bị</h2>
            <div class="card">
                <p class="card-text">
                    Khoa Tim Mạch được trang bị các thiết bị cơ bản phục vụ cho công tác khám và theo dõi bệnh nhân tim mạch
                    (nội dung minh họa cho đồ án):
                </p>
                <ul class="facilities-list">
                    <li>Máy điện tâm đồ 12 chuyển đạo.</li>
                    <li>Máy siêu âm tim Doppler.</li>
                    <li>Hệ thống đo huyết áp tự động.</li>
                    <li>Giường bệnh có monitor theo dõi mạch, huyết áp, SpO2.</li>
                </ul>
                <p class="note-small">
                    * Thông tin trên mang tính mô phỏng, không phản ánh chính xác trang thiết bị của một bệnh viện cụ thể.
                </p>
            </div>
        </section>

        <!-- 6. FAQ -->
        <section class="page-section">
            <h2 class="section-title">Câu hỏi thường gặp</h2>

            <div class="grid">
                <details class="faq-item">
                    <summary class="faq-summary">
                        <span>Tôi có cần nhịn ăn trước khi siêu âm tim không?</span>
                        <span class="faq-arrow">▼</span>
                    </summary>
                    <div class="faq-body">
                        Thông thường, siêu âm tim không yêu cầu nhịn ăn.
                        Tuy nhiên một số xét nghiệm hoặc thủ thuật tim mạch khác có thể cần chuẩn bị riêng,
                        người bệnh nên nghe theo hướng dẫn cụ thể của bác sĩ.
                    </div>
                </details>

                <details class="faq-item">
                    <summary class="faq-summary">
                        <span>Tăng huyết áp có cần khám định kỳ không?</span>
                        <span class="faq-arrow">▼</span>
                    </summary>
                    <div class="faq-body">
                        Người bị tăng huyết áp nên tái khám định kỳ để kiểm soát huyết áp, theo dõi chức năng tim, thận
                        và điều chỉnh thuốc nếu cần.
                    </div>
                </details>

                <details class="faq-item">
                    <summary class="faq-summary">
                        <span>Tim đập nhanh, hồi hộp thoáng qua có nguy hiểm không?</span>
                        <span class="faq-arrow">▼</span>
                    </summary>
                    <div class="faq-body">
                        Một số trường hợp có thể chỉ là phản ứng nhất thời do căng thẳng,
                        nhưng cũng có thể là biểu hiện của rối loạn nhịp tim.
                        Nếu triệu chứng lặp lại nhiều lần, nên đến Khoa Tim Mạch để kiểm tra.
                    </div>
                </details>
            </div>
        </section>

        <!-- 7. LIÊN HỆ – ĐẶT LỊCH -->
        <section id="dat-lich" class="page-section">
            <div class="contact-section">
                <div class="contact-left">
                    <h2 class="contact-left-title">Cần tư vấn hoặc đặt lịch khám Tim Mạch?</h2>
                    <p class="contact-left-text">
                        Liên hệ tổng đài hoặc đặt lịch trực tuyến để được hỗ trợ.
                        Trong trường hợp đau ngực dữ dội, khó thở cấp, cần gọi cấp cứu ngay.
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
                        <span class="contact-label">Cấp cứu Tim Mạch 24/7</span>
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
