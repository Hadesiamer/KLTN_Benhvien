<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khoa Sản phụ khoa - Bệnh viện</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #ec4899;          /* hồng nhẹ cho sản phụ khoa */
            --primary-dark: #db2777;
            --cyan: #06b6d4;
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
            box-shadow: 0 8px 20px rgba(236, 72, 153, 0.4);
        }

        /* ALERT */
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

        /* HERO */
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
            background-color: #fde68a;
            margin-right: 6px;
        }

        .hero-pill {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.5);
            background-color: rgba(255, 255, 255, 0.08);
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
            color: #fee2e2;
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
            box-shadow: 0 10px 25px rgba(190, 24, 93, 0.35);
        }

        .btn-primary:hover { background-color: #fff7ed; }

        .btn-outline-light {
            background: transparent;
            color: var(--white);
            border-color: rgba(255, 255, 255, 0.7);
        }

        .btn-outline-light:hover { background-color: rgba(255, 255, 255, 0.12); }

        .hero-card {
            background-color: rgba(15, 23, 42, 0.16);
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.25);
            padding: 16px 18px;
            box-shadow: 0 16px 40px rgba(236, 72, 153, 0.4);
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
            border-top: 1px solid rgba(255, 255, 255, 0.25);
            margin-top: 4px;
            padding-top: 8px;
        }

        .hero-card-label { color: #ffe4e6; }
        .hero-card-value { font-weight: 600; }

        /* GRID & CARD */
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

        /* BÁC SĨ */
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

        /* CƠ SỞ VẬT CHẤT */
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

        /* FAQ */
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

        /* LIÊN HỆ */
        .contact-section {
            background: linear-gradient(to right, #ec4899, #06b6d4);
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
            color: #ffe4e6;
            margin: 0 0 4px;
        }

        .contact-left-note {
            font-size: 11px;
            color: #fecdd3;
        }

        .contact-right {
            text-align: right;
            font-size: 13px;
        }

        .contact-label {
            font-size: 11px;
            color: #fee2e2;
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
            color: #be185d;
            font-weight: 600;
            font-size: 12px;
            border: none;
            cursor: pointer;
            box-shadow: 0 10px 25px rgba(131, 24, 67, 0.35);
        }

        .btn-contact:hover { background-color: #fff7ed; }

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

<!-- HERO -->
<section class="hero">
    <div class="container">
        <div class="hero-inner">
            <div class="hero-left">
                <div class="hero-pill">
                    <span class="hero-pill-dot"></span>
                    Khoa chăm sóc mẹ & bé – Sản phụ khoa
                </div>
                <h1 class="hero-title">Khoa Sản phụ khoa</h1>
                <p class="hero-desc">
                    Khoa Sản phụ khoa cung cấp dịch vụ chăm sóc sức khỏe sinh sản cho phụ nữ,
                    theo dõi thai kỳ, sinh nở và điều trị các bệnh lý phụ khoa thường gặp.
                    Mục tiêu là đảm bảo an toàn cho mẹ và bé, đồng thời bảo vệ sức khỏe phụ nữ lâu dài.
                </p>
                <div class="hero-actions">
                    <a href="#dat-lich" class="btn btn-primary">Đặt lịch khám Sản phụ khoa</a>
                    <a href="#trieuchung" class="btn btn-outline-light">Khi nào nên đi khám?</a>
                </div>
            </div>
            <div class="hero-right">
                <div class="hero-card">
                    <h2 class="hero-card-title">Thông tin nhanh</h2>
                    <ul class="hero-card-list">
                        <li class="hero-card-row">
                            <span class="hero-card-label">Địa điểm</span>
                            <span class="hero-card-value">Tầng 6 – Khu B</span>
                        </li>
                        <li class="hero-card-row">
                            <span class="hero-card-label">Giờ làm việc</span>
                            <span class="hero-card-value">Thứ 2 – Thứ 6: 7:00 – 17:00</span>
                        </li>
                        <li class="hero-card-row">
                            <span class="hero-card-label">Sinh thường / mổ lấy thai</span>
                            <span class="hero-card-value">Hoạt động 24/7</span>
                        </li>
                        <li class="hero-card-row">
                            <span class="hero-card-label">Khu điều trị</span>
                            <span class="hero-card-value">Khu khám thai – Phòng sinh – Hậu sản</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<main>
    <div class="container">

        <!-- 1. Khi nào nên khám Sản phụ khoa -->
        <section id="trieuchung" class="page-section">
            <div class="section-header">
                <h2 class="section-title">Khi nào bạn nên khám tại Khoa Sản phụ khoa?</h2>
                <span class="badge badge-khoa">Chăm sóc sức khỏe phụ nữ</span>
            </div>
            <p class="section-desc">
                Phụ nữ nên chủ động đi khám Sản phụ khoa định kỳ hoặc khi có những dấu hiệu bất thường sau
                để được tư vấn và điều trị kịp thời.
            </p>

            <div class="grid grid-2 grid-3-lg">
                <div class="card">
                    <h3 class="card-title">Trễ kinh, nghi ngờ mang thai</h3>
                    <p class="card-text">
                        Khi trễ kinh, thử que lên hai vạch hoặc có dấu hiệu nghén, nên đến khám
                        để xác định thai và được tư vấn chăm sóc thai kỳ an toàn.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Ra huyết âm đạo bất thường</h3>
                    <p class="card-text">
                        Rong kinh, ra huyết giữa kỳ, ra huyết sau mãn kinh hoặc sau quan hệ
                        đều là dấu hiệu cần được bác sĩ kiểm tra sớm.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Khí hư bất thường, ngứa rát</h3>
                    <p class="card-text">
                        Khí hư có mùi hôi, đổi màu, ngứa rát vùng kín hoặc đau khi quan hệ
                        có thể liên quan đến viêm nhiễm phụ khoa.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Đau bụng dưới, đau vùng chậu</h3>
                    <p class="card-text">
                        Đau kéo dài vùng bụng dưới, đau nhiều khi hành kinh hoặc khi quan hệ
                        có thể liên quan đến u nang, u xơ, lạc nội mạc tử cung…
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Khám tiền hôn nhân, trước khi mang thai</h3>
                    <p class="card-text">
                        Các cặp đôi nên khám trước hôn nhân hoặc trước khi có kế hoạch mang thai
                        để tầm soát bệnh lý và được tư vấn dinh dưỡng, tiêm phòng cần thiết.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Khám phụ khoa định kỳ</h3>
                    <p class="card-text">
                        Phụ nữ nên khám phụ khoa và tầm soát ung thư cổ tử cung, ung thư vú
                        theo lịch hẹn của bác sĩ, kể cả khi chưa có triệu chứng.
                    </p>
                </div>
            </div>
        </section>

        <!-- 2. Dịch vụ & kỹ thuật -->
        <section class="page-section">
            <h2 class="section-title">Dịch vụ và kỹ thuật tại Khoa Sản phụ khoa</h2>
            <p class="section-desc">
                Một số dịch vụ minh họa trong đồ án ở lĩnh vực Sản phụ khoa:
            </p>

            <div class="grid grid-2 grid-3-lg">
                <div class="card">
                    <h3 class="card-title">Khám và theo dõi thai kỳ</h3>
                    <p class="card-text">
                        Khám thai định kỳ, siêu âm thai, sàng lọc trước sinh
                        và tư vấn chế độ dinh dưỡng, nghỉ ngơi cho mẹ bầu.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Tư vấn sinh thường – sinh mổ</h3>
                    <p class="card-text">
                        Giải thích ưu – nhược điểm của từng phương pháp sinh,
                        giúp gia đình lựa chọn phù hợp theo tình trạng mẹ và thai nhi.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Khám và điều trị bệnh phụ khoa</h3>
                    <p class="card-text">
                        Khám viêm nhiễm phụ khoa, rối loạn kinh nguyệt, u xơ tử cung, u nang buồng trứng…
                        và theo dõi lâu dài theo hướng dẫn của bác sĩ.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Tư vấn kế hoạch hóa gia đình</h3>
                    <p class="card-text">
                        Tư vấn các biện pháp tránh thai, đặt vòng, cấy que tránh thai,
                        hỗ trợ lập kế hoạch sinh con an toàn, phù hợp hoàn cảnh gia đình.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Tầm soát ung thư phụ khoa</h3>
                    <p class="card-text">
                        Thực hiện Pap smear, HPV, siêu âm vú, siêu âm tử cung –
                        buồng trứng và tư vấn tầm soát ung thư cổ tử cung, ung thư vú.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Khám sức khỏe sinh sản</h3>
                    <p class="card-text">
                        Khám sức khỏe tổng quát cho phụ nữ, cặp vợ chồng hiếm muộn
                        hoặc trước khi mang thai lần đầu.
                    </p>
                </div>
            </div>
        </section>

        <!-- 3. Quy trình khám -->
        <section class="page-section">
            <h2 class="section-title">Quy trình khám tại Khoa Sản phụ khoa</h2>
            <div class="card">
                <ol class="list-steps">
                    <li>
                        <span class="step-label">Bước 1 – Đăng ký khám:</span>
                        Đến quầy tiếp nhận hoặc đặt lịch trực tuyến, chọn “Khoa Sản phụ khoa”.
                    </li>
                    <li>
                        <span class="step-label">Bước 2 – Khai thác bệnh sử:</span>
                        Bác sĩ hỏi về chu kỳ kinh, tiền sử mang thai, phương pháp tránh thai đang dùng
                        và các triệu chứng hiện tại.
                    </li>
                    <li>
                        <span class="step-label">Bước 3 – Khám và cận lâm sàng:</span>
                        Khám phụ khoa, siêu âm, xét nghiệm máu, xét nghiệm tầm soát
                        hoặc các xét nghiệm khác tùy tình trạng.
                    </li>
                    <li>
                        <span class="step-label">Bước 4 – Tư vấn và điều trị:</span>
                        Bác sĩ giải thích kết quả, đưa ra chẩn đoán và kế hoạch điều trị,
                        theo dõi thai kỳ hoặc theo dõi bệnh phụ khoa.
                    </li>
                    <li>
                        <span class="step-label">Bước 5 – Hẹn tái khám:</span>
                        Lên lịch khám thai định kỳ hoặc tái khám phụ khoa theo hướng dẫn cụ thể.
                    </li>
                </ol>
            </div>
        </section>

        <!-- 4. Đội ngũ bác sĩ (minh họa) -->
        <section class="page-section">
            <h2 class="section-title">Đội ngũ bác sĩ (minh họa)</h2>
            <p class="section-desc">
                Đây là thông tin mô phỏng trong đồ án, không phải danh sách bác sĩ thật.
            </p>

            <div class="grid grid-2 grid-3-lg">
                <div class="card">
                    <div class="doctor-avatar" style="background: linear-gradient(to bottom right,#ec4899,#f97316);">
                        SP
                    </div>
                    <h3 class="doctor-name">BSCKII. Trần Mai M</h3>
                    <p class="doctor-position">Trưởng khoa Sản phụ khoa</p>
                    <p class="doctor-desc">
                        Nhiều năm kinh nghiệm trong quản lý thai kỳ nguy cơ cao,
                        xử trí các tình huống sản khoa cấp cứu và tư vấn sinh an toàn.
                    </p>
                </div>
                <div class="card">
                    <div class="doctor-avatar" style="background: linear-gradient(to bottom right,#a855f7,#ec4899);">
                        TN
                    </div>
                    <h3 class="doctor-name">ThS. BS. Nguyễn Hồng N</h3>
                    <p class="doctor-position">Bác sĩ Sản khoa</p>
                    <p class="doctor-desc">
                        Tập trung chăm sóc thai kỳ bình thường, tư vấn sinh thường – sinh mổ
                        và chăm sóc sau sinh cho mẹ và bé.
                    </p>
                </div>
                <div class="card">
                    <div class="doctor-avatar" style="background: linear-gradient(to bottom right,#10b981,#14b8a6);">
                        PK
                    </div>
                    <h3 class="doctor-name">BS. Lý Thu O</h3>
                    <p class="doctor-position">Bác sĩ Phụ khoa</p>
                    <p class="doctor-desc">
                        Khám và điều trị các bệnh lý phụ khoa, tư vấn kế hoạch hóa gia đình
                        và tầm soát ung thư phụ khoa cho phụ nữ các lứa tuổi.
                    </p>
                </div>
            </div>
        </section>

        <!-- 5. Cơ sở vật chất -->
        <section class="page-section">
            <h2 class="section-title">Cơ sở vật chất – thiết bị</h2>
            <div class="card">
                <p class="card-text">
                    Khoa Sản phụ khoa được trang bị các phương tiện cần thiết cho chăm sóc sản phụ và phụ khoa
                    (nội dung mô phỏng cho đồ án):
                </p>
                <ul class="facilities-list">
                    <li>Phòng khám thai, phòng khám phụ khoa riêng tư.</li>
                    <li>Phòng sinh thường và phòng mổ lấy thai (liên kết khoa Gây mê – Hồi sức).</li>
                    <li>Hệ thống siêu âm sản khoa, monitor theo dõi tim thai (minh họa).</li>
                    <li>Khu hậu sản dành cho mẹ và bé nghỉ ngơi, theo dõi sau sinh.</li>
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
                        <span>Thai bao nhiêu tuần thì nên khám lần đầu?</span>
                        <span class="faq-arrow">▼</span>
                    </summary>
                    <div class="faq-body">
                        Khi trễ kinh hoặc thử que 2 vạch, sản phụ nên đi khám sớm để xác định vị trí thai
                        và được tư vấn lịch khám thai phù hợp từng giai đoạn.
                    </div>
                </details>

                <details class="faq-item">
                    <summary class="faq-summary">
                        <span>Có cần khám phụ khoa khi chưa có triệu chứng gì không?</span>
                        <span class="faq-arrow">▼</span>
                    </summary>
                    <div class="faq-body">
                        Phụ nữ vẫn nên khám phụ khoa định kỳ để tầm soát sớm các bệnh lý,
                        kể cả khi chưa có biểu hiện rõ ràng. Lịch khám cụ thể tùy độ tuổi và yếu tố nguy cơ.
                    </div>
                </details>

                <details class="faq-item">
                    <summary class="faq-summary">
                        <span>Sau sinh bao lâu thì nên tái khám?</span>
                        <span class="faq-arrow">▼</span>
                    </summary>
                    <div class="faq-body">
                        Thông thường sản phụ được khuyến khích tái khám khoảng 4–6 tuần sau sinh,
                        hoặc sớm hơn nếu có sốt, ra dịch bất thường hay đau nhiều.
                    </div>
                </details>
            </div>
        </section>

        <!-- 7. Liên hệ – Đặt lịch -->
        <section id="dat-lich" class="page-section">
            <div class="contact-section">
                <div class="contact-left">
                    <h2 class="contact-left-title">Cần tư vấn hoặc đặt lịch khám Sản phụ khoa?</h2>
                    <p class="contact-left-text">
                        Liên hệ tổng đài hoặc đặt lịch trực tuyến để được hỗ trợ.
                        Nếu có đau bụng dữ dội, ra huyết nhiều khi đang mang thai,
                        hãy đến khoa Cấp cứu ngay lập tức.
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
                        <span class="contact-label">Cấp cứu Sản khoa 24/7</span>
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
