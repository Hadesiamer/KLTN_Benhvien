<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khoa Mắt - Bệnh viện</title>
    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* ========= THEME CHO KHOA MẮT ========= */
        :root {
            --primary: #0ea5e9;          /* xanh dương cho chuyên khoa mắt */
            --primary-dark: #0369a1;
            --cyan: #22c55e;
            --bg: #f1f5f9;
            --text-main: #0f172a;
            --text-sub: #475569;
            --border: #e2e8f0;
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

        /* ========= ALERT THÔNG BÁO ĐỒ ÁN ========= */
        .alert {
            background-color: #e0f2fe;
            border-left: 4px solid #0284c7;
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
            color: #0284c7;
        }

        .alert-title {
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 2px;
        }

        .alert-text {
            color: #1e293b;
        }

        /* ========= HERO KHOA MẮT ========= */
        .hero {
            background: radial-gradient(circle at top left, #38bdf8, #0f172a);
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

        .hero-left,
        .hero-right {
            flex: 1;
        }

        .hero-pill-dot {
            width: 7px;
            height: 7px;
            border-radius: 999px;
            background-color: #bef264;
            margin-right: 6px;
        }

        .hero-pill {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.5);
            background-color: rgba(15, 23, 42, 0.3);
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
            transition: background-color .15s ease, color .15s ease, border-color .15s ease, transform .1s ease;
        }

        .btn-primary {
            background-color: var(--white);
            color: var(--primary-dark);
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.4);
        }

        .btn-primary:hover {
            background-color: #e0f2fe;
            transform: translateY(-1px);
        }

        .btn-outline-light {
            background: transparent;
            color: var(--white);
            border-color: rgba(255, 255, 255, 0.7);
        }

        .btn-outline-light:hover {
            background-color: rgba(15, 23, 42, 0.3);
            transform: translateY(-1px);
        }

        .hero-card {
            background-color: rgba(15, 23, 42, 0.35);
            border-radius: 16px;
            border: 1px solid rgba(148, 163, 184, 0.6);
            padding: 16px 18px;
            box-shadow: 0 16px 40px rgba(37, 99, 235, 0.5);
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
            border-top: 1px solid rgba(148, 163, 184, 0.7);
            margin-top: 4px;
            padding-top: 8px;
        }

        .hero-card-label {
            color: #e2e8f0;
        }

        .hero-card-value {
            font-weight: 600;
        }

        /* ========= GRID & CARD CHUNG ========= */
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

        /* ========= BÁC SĨ ========= */
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

        /* ========= CƠ SỞ VẬT CHẤT ========= */
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

        /* ========= FAQ ========= */
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
            background-color: #f1f5f9;
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

        /* ========= LIÊN HỆ ========= */
        .contact-section {
            background: linear-gradient(to right, #0ea5e9, #22c55e);
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
            color: #dbeafe;
        }

        .contact-right {
            text-align: right;
            font-size: 13px;
        }

        .contact-label {
            font-size: 11px;
            color: #e0f2fe;
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
            color: #0f172a;
            font-weight: 600;
            font-size: 12px;
            border: none;
            cursor: pointer;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.35);
        }

        .btn-contact:hover {
            background-color: #e0f2fe;
        }

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

<!-- THÔNG BÁO ĐỒ ÁN (MINH HỌA) -->
<div class="alert">
    <div class="container">
        <div class="alert-inner">
            <div class="alert-icon">👁️</div>
            <div>
                <div class="alert-title">Lưu ý</div>
                <div class="alert-text">
                    Đây là trang giới thiệu Khoa Mắt trong đồ án sinh viên, nội dung chỉ mang tính minh họa,
                    không thay thế tư vấn y khoa hoặc chỉ định điều trị của bác sĩ chuyên khoa.
                </div>
            </div>
        </div>
    </div>
</div>

<!-- HERO KHOA MẮT -->
<section class="hero">
    <div class="container">
        <div class="hero-inner">
            <div class="hero-left">
                <div class="hero-pill">
                    <span class="hero-pill-dot"></span>
                    Khoa Mắt – Chăm sóc & bảo vệ thị lực
                </div>
                <h1 class="hero-title">Khoa Mắt</h1>
                <p class="hero-desc">
                    Khoa Mắt cung cấp các dịch vụ khám, tầm soát và điều trị các bệnh lý về mắt như cận thị,
                    loạn thị, đục thủy tinh thể, tăng nhãn áp (glôcôm), bệnh võng mạc tiểu đường… 
                    Mục tiêu là giúp người bệnh duy trì thị lực tốt, phát hiện sớm tổn thương và hạn chế biến chứng lâu dài.
                </p>
                <div class="hero-actions">
                    <a href="#dat-lich" class="btn btn-primary">Đặt lịch khám Mắt</a>
                    <a href="#trieuchung" class="btn btn-outline-light">Khi nào cần đi khám mắt?</a>
                </div>
            </div>
            <div class="hero-right">
                <div class="hero-card">
                    <h2 class="hero-card-title">Thông tin nhanh</h2>
                    <ul class="hero-card-list">
                        <li class="hero-card-row">
                            <span class="hero-card-label">Địa điểm</span>
                            <span class="hero-card-value">Tầng 4 – Khu A</span>
                        </li>
                        <li class="hero-card-row">
                            <span class="hero-card-label">Giờ làm việc</span>
                            <span class="hero-card-value">Thứ 2 – Thứ 7: 7:00 – 17:00</span>
                        </li>
                        <li class="hero-card-row">
                            <span class="hero-card-label">Khám cấp cứu mắt</span>
                            <span class="hero-card-value">Liên kết Khoa Cấp cứu 24/7</span>
                        </li>
                        <li class="hero-card-row">
                            <span class="hero-card-label">Khu điều trị</span>
                            <span class="hero-card-value">Phòng khám mắt – Phòng thủ thuật – Phòng phẫu thuật (minh họa)</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<main>
    <div class="container">

        <!-- 1. Khi nào nên khám tại Khoa Mắt -->
        <section id="trieuchung" class="page-section">
            <div class="section-header">
                <h2 class="section-title">Khi nào bạn nên khám tại Khoa Mắt?</h2>
                <span class="badge badge-khoa">Chăm sóc sức khỏe thị giác</span>
            </div>
            <p class="section-desc">
                Người bệnh nên chủ động đi khám mắt định kỳ hoặc khi xuất hiện các triệu chứng bất thường dưới đây
                để được phát hiện sớm bệnh lý và điều trị kịp thời.
            </p>

            <div class="grid grid-2 grid-3-lg">
                <div class="card">
                    <h3 class="card-title">Nhìn mờ, khó tập trung</h3>
                    <p class="card-text">
                        Thị lực giảm dần, phải nheo mắt khi nhìn xa hoặc gần, đau đầu khi làm việc với máy tính,
                        học tập lâu… có thể là dấu hiệu của tật khúc xạ (cận, viễn, loạn thị) hoặc mỏi mắt.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Mắt đỏ, đau, chói sáng</h3>
                    <p class="card-text">
                        Đỏ mắt kéo dài, cảm giác cộm như có bụi, đau nhức, sợ ánh sáng, chảy nước mắt nhiều
                        là biểu hiện thường gặp của viêm kết mạc, viêm giác mạc hoặc khô mắt.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Chớp sáng, ruồi bay, che khuất tầm nhìn</h3>
                    <p class="card-text">
                        Thấy chớp sáng, vệt đen lơ lửng, như màn che một phần tầm nhìn có thể liên quan đến
                        bệnh lý võng mạc, bong võng mạc – cần đi khám sớm để tránh mất thị lực không hồi phục.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Đau nhức mắt dữ dội, nhìn mờ đột ngột</h3>
                    <p class="card-text">
                        Cơn đau mắt dữ dội, nhìn quầng sáng quanh đèn, buồn nôn, thị lực giảm đột ngột
                        có thể là dấu hiệu tăng nhãn áp cấp – đây là tình trạng cấp cứu nhãn khoa.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Người bệnh tiểu đường, tăng huyết áp</h3>
                    <p class="card-text">
                        Người bệnh tiểu đường, tăng huyết áp kéo dài cần khám mắt định kỳ
                        để tầm soát bệnh võng mạc tiểu đường và các biến chứng mạch máu võng mạc.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Khám mắt định kỳ cho trẻ em và người lớn</h3>
                    <p class="card-text">
                        Trẻ em trong độ tuổi đi học và người lớn trên 40 tuổi nên khám mắt định kỳ,
                        đặc biệt để tầm soát tật khúc xạ, lác, nhược thị, đục thủy tinh thể và tăng nhãn áp.
                    </p>
                </div>
            </div>
        </section>

        <!-- 2. Dịch vụ & kỹ thuật tại Khoa Mắt -->
        <section class="page-section">
            <h2 class="section-title">Dịch vụ và kỹ thuật tại Khoa Mắt</h2>
            <p class="section-desc">
                Một số dịch vụ minh họa trong đồ án ở lĩnh vực Nhãn khoa (không phải danh sách đầy đủ):
            </p>

            <div class="grid grid-2 grid-3-lg">
                <div class="card">
                    <h3 class="card-title">Khám mắt tổng quát</h3>
                    <p class="card-text">
                        Đo thị lực, đo khúc xạ, soi đáy mắt, đánh giá bề mặt nhãn cầu, kiểm tra khô mắt
                        và tư vấn chăm sóc mắt theo từng độ tuổi, tính chất công việc.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Theo dõi & điều trị tật khúc xạ</h3>
                    <p class="card-text">
                        Chẩn đoán cận thị, viễn thị, loạn thị; tư vấn lựa chọn kính gọng, kính áp tròng phù hợp,
                        kết hợp hướng dẫn thói quen học tập – làm việc tốt cho mắt.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Khám và điều trị bệnh lý bề mặt nhãn cầu</h3>
                    <p class="card-text">
                        Viêm kết mạc, viêm giác mạc, dị vật giác mạc, viêm bờ mi, khô mắt…
                        được khám, xử trí và theo dõi theo hướng dẫn của bác sĩ (minh họa).
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Đục thủy tinh thể (cườm khô)</h3>
                    <p class="card-text">
                        Tư vấn tầm soát và điều trị đục thủy tinh thể ở người lớn tuổi,
                        giải thích các phương án phẫu thuật (minh họa) và kế hoạch theo dõi sau mổ.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Tăng nhãn áp (glôcôm)</h3>
                    <p class="card-text">
                        Đo nhãn áp, đánh giá thị trường (thị lực ngoại vi), tư vấn điều trị nội khoa
                        và theo dõi lâu dài nhằm hạn chế tổn thương thần kinh thị giác.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Bệnh võng mạc & tiểu đường</h3>
                    <p class="card-text">
                        Khám đáy mắt cho người bệnh tiểu đường, tăng huyết áp, tư vấn phát hiện sớm
                        bệnh võng mạc và hướng dẫn lịch theo dõi định kỳ (minh họa).
                    </p>
                </div>
            </div>
        </section>

        <!-- 3. Quy trình khám tại Khoa Mắt -->
        <section class="page-section">
            <h2 class="section-title">Quy trình khám tại Khoa Mắt</h2>
            <div class="card">
                <ol class="list-steps">
                    <li>
                        <span class="step-label">Bước 1 – Đăng ký khám:</span>
                        Đến quầy tiếp nhận hoặc đặt lịch trực tuyến, chọn “Khoa Mắt”, cung cấp thông tin triệu chứng hiện tại.
                    </li>
                    <li>
                        <span class="step-label">Bước 2 – Khai thác bệnh sử:</span>
                        Bác sĩ hỏi về thời gian mờ mắt, tiền sử chấn thương mắt, tiền sử bệnh toàn thân
                        (tiểu đường, tăng huyết áp…), tiền sử sử dụng kính hoặc thuốc nhỏ mắt.
                    </li>
                    <li>
                        <span class="step-label">Bước 3 – Khám lâm sàng và cận lâm sàng:</span>
                        Thực hiện đo thị lực, đo khúc xạ, soi đáy mắt, đo nhãn áp, khám bằng sinh hiển vi
                        hoặc các xét nghiệm hình ảnh khác tùy theo tình trạng (minh họa).
                    </li>
                    <li>
                        <span class="step-label">Bước 4 – Chẩn đoán & tư vấn điều trị:</span>
                        Bác sĩ giải thích nguyên nhân triệu chứng, đưa ra chẩn đoán,
                        kê đơn điều trị, hướng dẫn cách sử dụng thuốc và các lưu ý khi chăm sóc mắt.
                    </li>
                    <li>
                        <span class="step-label">Bước 5 – Hẹn tái khám & theo dõi:</span>
                        Sắp xếp lịch tái khám để đánh giá đáp ứng điều trị, điều chỉnh kính hoặc kế hoạch can thiệp sau đó.
                    </li>
                </ol>
            </div>
        </section>

        <!-- 4. Đội ngũ bác sĩ (minh họa) -->
        <section class="page-section">
            <h2 class="section-title">Đội ngũ bác sĩ (minh họa)</h2>
            <p class="section-desc">
                Thông tin dưới đây chỉ phục vụ mục đích mô phỏng trong đồ án, không phải danh sách bác sĩ thật.
            </p>

            <div class="grid grid-2 grid-3-lg">
                <div class="card">
                    <div class="doctor-avatar" style="background: radial-gradient(circle at top, #38bdf8, #1d4ed8);">
                        MT
                    </div>
                    <h3 class="doctor-name">BSCKII. Nguyễn Minh T</h3>
                    <p class="doctor-position">Trưởng khoa Mắt</p>
                    <p class="doctor-desc">
                        Nhiều năm kinh nghiệm trong chẩn đoán và điều trị các bệnh lý phức tạp về mắt,
                        bao gồm tăng nhãn áp và bệnh võng mạc ở người bệnh tiểu đường (minh họa).
                    </p>
                </div>
                <div class="card">
                    <div class="doctor-avatar" style="background: radial-gradient(circle at top, #22c55e, #0f766e);">
                        AC
                    </div>
                    <h3 class="doctor-name">ThS. BS. Lê Anh C</h3>
                    <p class="doctor-position">Bác sĩ Nhãn khoa</p>
                    <p class="doctor-desc">
                        Tập trung khám và theo dõi tật khúc xạ, chăm sóc mắt cho người làm việc máy tính nhiều,
                        tư vấn chế độ sinh hoạt bảo vệ thị lực cho học sinh – sinh viên.
                    </p>
                </div>
                <div class="card">
                    <div class="doctor-avatar" style="background: radial-gradient(circle at top, #f97316, #ea580c);">
                        HD
                    </div>
                    <h3 class="doctor-name">BS. Phạm Hồng D</h3>
                    <p class="doctor-position">Bác sĩ Mắt trẻ em (minh họa)</p>
                    <p class="doctor-desc">
                        Quan tâm đến tầm soát lác, nhược thị, tật khúc xạ ở trẻ em,
                        hướng dẫn phụ huynh theo dõi thói quen sinh hoạt và thời gian sử dụng màn hình cho trẻ.
                    </p>
                </div>
            </div>
        </section>

        <!-- 5. Cơ sở vật chất – thiết bị (minh họa) -->
        <section class="page-section">
            <h2 class="section-title">Cơ sở vật chất – Thiết bị (minh họa)</h2>
            <div class="card">
                <p class="card-text">
                    Khoa Mắt trong đồ án được thiết kế với các khu vực chức năng và thiết bị nhãn khoa cơ bản,
                    phục vụ cho việc xây dựng luồng nghiệp vụ và giao diện website:
                </p>
                <ul class="facilities-list">
                    <li>Phòng khám mắt với ghế khám chuyên dụng, bảng đo thị lực (minh họa).</li>
                    <li>Khu vực đo khúc xạ, đo nhãn áp, soi đáy mắt (mô phỏng trong mô tả).</li>
                    <li>Phòng thủ thuật nhỏ như lấy dị vật kết – giác mạc, xử trí chấn thương phần mềm quanh mắt.</li>
                    <li>Khu vực tư vấn cho người bệnh về cách dùng thuốc, vệ sinh mắt và sử dụng kính đúng cách.</li>
                </ul>
                <p class="note-small">
                    * Tất cả thông tin trên chỉ mang tính minh họa cho đồ án, không phản ánh chính xác trang thiết bị của bất kỳ cơ sở y tế nào.
                </p>
            </div>
        </section>

        <!-- 6. Câu hỏi thường gặp -->
        <section class="page-section">
            <h2 class="section-title">Câu hỏi thường gặp</h2>

            <div class="grid">
                <details class="faq-item">
                    <summary class="faq-summary">
                        <span>Tôi nên khám mắt định kỳ bao lâu một lần?</span>
                        <span class="faq-arrow">▼</span>
                    </summary>
                    <div class="faq-body">
                        Tùy độ tuổi và bệnh nền, đa số người trưởng thành có thể khám mắt định kỳ 1–2 năm/lần.
                        Người bị tiểu đường, tăng huyết áp hoặc đang điều trị bệnh mắt nên khám theo lịch hẹn
                        cụ thể của bác sĩ chuyên khoa (thông tin mang tính minh họa).
                    </div>
                </details>

                <details class="faq-item">
                    <summary class="faq-summary">
                        <span>Có nên tự mua thuốc nhỏ mắt khi bị đỏ mắt, cộm rát?</span>
                        <span class="faq-arrow">▼</span>
                    </summary>
                    <div class="faq-body">
                        Khi đỏ mắt, cộm rát kéo dài, tốt nhất nên đi khám để được chẩn đoán nguyên nhân.
                        Việc tự ý dùng thuốc nhỏ mắt chứa corticoid trong thời gian dài có thể gây tăng nhãn áp,
                        đục thủy tinh thể và các biến chứng khác (nội dung minh họa, không thay thế tư vấn y khoa).
                    </div>
                </details>

                <details class="faq-item">
                    <summary class="faq-summary">
                        <span>Khi nào là tình trạng cấp cứu mắt cần đi bệnh viện ngay?</span>
                        <span class="faq-arrow">▼</span>
                    </summary>
                    <div class="faq-body">
                        Một số tình huống nghi ngờ cấp cứu mắt: chấn thương mắt do vật nhọn, hóa chất văng vào mắt,
                        mất thị lực đột ngột, đau nhức dữ dội kèm nôn ói, nhìn thấy như màn đen che ngang tầm nhìn…
                        Khi đó, người bệnh nên đến khoa Cấp cứu hoặc Khoa Mắt gần nhất để được xử trí sớm.
                    </div>
                </details>
            </div>
        </section>

        <!-- 7. Liên hệ – Đặt lịch khám Mắt -->
        <section id="dat-lich" class="page-section">
            <div class="contact-section">
                <div class="contact-left">
                    <h2 class="contact-left-title">Cần tư vấn hoặc đặt lịch khám Mắt?</h2>
                    <p class="contact-left-text">
                        Người bệnh có thể liên hệ tổng đài hoặc đặt lịch khám trực tuyến để được hỗ trợ sắp xếp thời gian phù hợp.
                        Nếu xuất hiện đau mắt dữ dội, giảm thị lực nhanh, chấn thương mắt hoặc hóa chất bắn vào mắt,
                        hãy đến khoa Cấp cứu ngay lập tức.
                    </p>
                    <p class="contact-left-note">
                        Thông tin trên website chỉ mang tính minh họa cho đồ án, không dùng để tự chẩn đoán
                        hoặc tự điều trị bệnh lý về mắt. Luôn tuân theo chỉ định của bác sĩ chuyên khoa khi đi khám thực tế.
                    </p>
                </div>
                <div class="contact-right">
                    <div>
                        <span class="contact-label">Hotline tư vấn (minh họa)</span>
                        <div class="contact-value-main">1900 0123</div>
                    </div>
                    <div style="margin-top: 6px;">
                        <span class="contact-label">Cấp cứu mắt 24/7 (liên hệ)</span>
                        <div class="contact-value-sub">115 (hoặc số cấp cứu địa phương)</div>
                    </div>
                    <div class="contact-btn-wrapper">
                        <button type="button" class="btn-contact">
                            Đặt lịch khám Mắt (minh họa)
                        </button>
                    </div>
                </div>
            </div>
        </section>

    </div>
</main>

</body>
</html>
