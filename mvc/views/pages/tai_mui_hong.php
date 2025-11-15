<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khoa Tai mũi họng - Bệnh viện</title>
    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* ========= THEME CHO KHOA TAI MŨI HỌNG ========= */
        :root {
            --primary: #14b8a6;          /* xanh ngọc cho tai mũi họng */
            --primary-dark: #0f766e;
            --accent: #38bdf8;
            --bg: #ecfeff;
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
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            color: var(--white);
            box-shadow: 0 8px 20px rgba(20, 184, 166, 0.4);
        }

        /* ========= ALERT THÔNG BÁO ĐỒ ÁN ========= */
        .alert {
            background-color: #ecfeff;
            border-left: 4px solid #0f766e;
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
            color: #0f766e;
        }

        .alert-title {
            font-weight: 600;
            color: #022c22;
            margin-bottom: 2px;
        }

        .alert-text {
            color: #0f172a;
        }

        /* ========= HERO KHOA TAI MŨI HỌNG ========= */
        .hero {
            background: radial-gradient(circle at top left, #14b8a6, #0f172a);
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
            background-color: #facc15;
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
            box-shadow: 0 16px 40px rgba(20, 184, 166, 0.5);
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
            background-color: #f0f9ff;
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
            background: linear-gradient(to right, #14b8a6, #38bdf8);
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
            <div class="alert-icon">👂</div>
            <div>
                <div class="alert-title">Lưu ý</div>
                <div class="alert-text">
                    Đây là trang giới thiệu Khoa Tai mũi họng trong đồ án sinh viên, nội dung chỉ mang tính minh họa,
                    không thay thế tư vấn y khoa hoặc chỉ định điều trị của bác sĩ chuyên khoa.
                </div>
            </div>
        </div>
    </div>
</div>

<!-- HERO KHOA TAI MŨI HỌNG -->
<section class="hero">
    <div class="container">
        <div class="hero-inner">
            <div class="hero-left">
                <div class="hero-pill">
                    <span class="hero-pill-dot"></span>
                    Khoa Tai mũi họng – Hô hấp trên & thính giác
                </div>
                <h1 class="hero-title">Khoa Tai mũi họng</h1>
                <p class="hero-desc">
                    Khoa Tai mũi họng tiếp nhận khám và điều trị các bệnh lý vùng tai, mũi, họng, thanh quản
                    và vùng đầu – cổ liên quan (minh họa). Mục tiêu là giúp người bệnh cải thiện hô hấp,
                    giọng nói và khả năng nghe, giảm tái phát và biến chứng lâu dài.
                </p>
                <div class="hero-actions">
                    <a href="#dat-lich" class="btn btn-primary">Đặt lịch khám Tai mũi họng</a>
                    <a href="#trieuchung" class="btn btn-outline-light">Khi nào cần đi khám?</a>
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
                            <span class="hero-card-value">Thứ 2 – Thứ 7: 7:00 – 17:00</span>
                        </li>
                        <li class="hero-card-row">
                            <span class="hero-card-label">Cấp cứu Tai mũi họng</span>
                            <span class="hero-card-value">Liên kết Khoa Cấp cứu 24/7</span>
                        </li>
                        <li class="hero-card-row">
                            <span class="hero-card-label">Khu điều trị</span>
                            <span class="hero-card-value">Phòng khám – Phòng nội soi – Phòng thủ thuật (minh họa)</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<main>
    <div class="container">

        <!-- 1. Khi nào nên khám tại Khoa Tai mũi họng -->
        <section id="trieuchung" class="page-section">
            <div class="section-header">
                <h2 class="section-title">Khi nào bạn nên khám tại Khoa Tai mũi họng?</h2>
                <span class="badge badge-khoa">Chăm sóc sức khỏe tai – mũi – họng</span>
            </div>
            <p class="section-desc">
                Các triệu chứng tai mũi họng thường xuyên xuất hiện trong sinh hoạt hàng ngày.
                Người bệnh nên khám khi triệu chứng kéo dài, tái phát nhiều lần hoặc ảnh hưởng nhiều đến giấc ngủ,
                công việc và học tập (thông tin minh họa).
            </p>

            <div class="grid grid-2 grid-3-lg">
                <div class="card">
                    <h3 class="card-title">Nghẹt mũi, chảy mũi kéo dài</h3>
                    <p class="card-text">
                        Nghẹt mũi, chảy mũi, hắt hơi nhiều, giảm ngửi… kéo dài hoặc tái phát
                        có thể liên quan đến viêm mũi dị ứng, viêm xoang hoặc polyp mũi.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Đau họng, khàn tiếng</h3>
                    <p class="card-text">
                        Đau rát họng, nuốt vướng, khàn tiếng kéo dài trên vài tuần cần được kiểm tra,
                        nhất là ở người hút thuốc, uống rượu nhiều (minh họa).
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Ù tai, nghe kém</h3>
                    <p class="card-text">
                        Ù tai, nghe không rõ, phải tăng âm lượng khi xem TV hoặc phải nhờ người lặp lại nhiều lần
                        có thể là dấu hiệu của giảm thính lực, viêm tai giữa hoặc tổn thương tai trong.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Đau tai, chảy dịch tai</h3>
                    <p class="card-text">
                        Đau tai, chảy dịch tai, nghe kém sau viêm tai giữa hoặc chấn thương tai
                        cần được khám sớm để tránh biến chứng kéo dài (minh họa).
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Ngáy to, ngưng thở khi ngủ (ghi nhận)</h3>
                    <p class="card-text">
                        Ngáy to, có cơn ngưng thở hoặc thở hổn hển trong giấc ngủ được người nhà ghi nhận,
                        có thể liên quan đến bệnh lý tai mũi họng và đường hô hấp trên (mô phỏng).
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Khám định kỳ cho trẻ em</h3>
                    <p class="card-text">
                        Trẻ hay viêm họng, viêm tai giữa, nghẹt mũi, nói khó nghe hoặc chậm nói
                        nên được khám định kỳ để đánh giá thính lực và đường hô hấp trên.
                    </p>
                </div>
            </div>
        </section>

        <!-- 2. Dịch vụ & kỹ thuật tại Khoa Tai mũi họng -->
        <section class="page-section">
            <h2 class="section-title">Dịch vụ và kỹ thuật tại Khoa Tai mũi họng</h2>
            <p class="section-desc">
                Một số dịch vụ minh họa trong đồ án ở lĩnh vực Tai mũi họng (không phải danh sách đầy đủ):
            </p>

            <div class="grid grid-2 grid-3-lg">
                <div class="card">
                    <h3 class="card-title">Khám tai mũi họng tổng quát</h3>
                    <p class="card-text">
                        Khám tai, mũi, họng, thanh quản; đánh giá tình trạng viêm nhiễm, phù nề, polyp;
                        tư vấn chăm sóc vùng tai – mũi – họng trong sinh hoạt hàng ngày (minh họa).
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Nội soi tai mũi họng</h3>
                    <p class="card-text">
                        Nội soi mũi xoang, họng và thanh quản để phát hiện polyp, u, tổn thương niêm mạc
                        và theo dõi diễn tiến bệnh (mô tả trong website).
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Điều trị viêm mũi – viêm xoang</h3>
                    <p class="card-text">
                        Khám và tư vấn điều trị viêm mũi dị ứng, viêm xoang cấp/mạn,
                        hướng dẫn xịt rửa mũi đúng cách và kiểm soát yếu tố kích thích (minh họa).
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Đánh giá thính lực (nghe)</h3>
                    <p class="card-text">
                        Thực hiện đo thính lực đơn giản (minh họa), tầm soát nghe kém ở trẻ em và người cao tuổi,
                        tư vấn chuyển tuyến khi cần đánh giá chuyên sâu hơn.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Xử trí dị vật tai – mũi – họng</h3>
                    <p class="card-text">
                        Lấy dị vật ở tai, mũi, họng trong những trường hợp phù hợp, sau khi được bác sĩ đánh giá,
                        đồng thời tư vấn phòng ngừa tái diễn (nội dung minh họa).
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Theo dõi bệnh lý ngáy và rối loạn hô hấp khi ngủ</h3>
                    <p class="card-text">
                        Khai thác triệu chứng, tư vấn thói quen sinh hoạt và hướng dẫn người bệnh
                        đi đánh giá chuyên sâu về giấc ngủ khi cần (mô phỏng).
                    </p>
                </div>
            </div>
        </section>

        <!-- 3. Quy trình khám tại Khoa Tai mũi họng -->
        <section class="page-section">
            <h2 class="section-title">Quy trình khám tại Khoa Tai mũi họng</h2>
            <div class="card">
                <ol class="list-steps">
                    <li>
                        <span class="step-label">Bước 1 – Đăng ký khám:</span>
                        Đến quầy tiếp nhận hoặc đặt lịch trực tuyến, chọn “Khoa Tai mũi họng”
                        và ghi rõ triệu chứng chính (nghẹt mũi, đau họng, ù tai…).
                    </li>
                    <li>
                        <span class="step-label">Bước 2 – Khai thác bệnh sử:</span>
                        Bác sĩ hỏi về thời gian và mức độ triệu chứng, tiền sử dị ứng, thói quen sinh hoạt,
                        nghề nghiệp, môi trường làm việc và các bệnh nền liên quan.
                    </li>
                    <li>
                        <span class="step-label">Bước 3 – Khám lâm sàng & cận lâm sàng:</span>
                        Khám tai, mũi, họng, có thể kết hợp nội soi tai mũi họng, chụp phim X-quang
                        hoặc các xét nghiệm khác tùy trường hợp (nội dung minh họa).
                    </li>
                    <li>
                        <span class="step-label">Bước 4 – Tư vấn & điều trị:</span>
                        Bác sĩ giải thích nguyên nhân, đưa ra chẩn đoán, kê đơn điều trị – 
                        hướng dẫn sử dụng thuốc xịt mũi, thuốc nhỏ tai, thuốc uống và cách chăm sóc tại nhà.
                    </li>
                    <li>
                        <span class="step-label">Bước 5 – Hẹn tái khám & theo dõi:</span>
                        Lên lịch tái khám để đánh giá đáp ứng điều trị, điều chỉnh thuốc hoặc kế hoạch can thiệp khác
                        nếu cần thiết.
                    </li>
                </ol>
            </div>
        </section>

        <!-- 4. Đội ngũ bác sĩ (minh họa) -->
        <section class="page-section">
            <h2 class="section-title">Đội ngũ bác sĩ (minh họa)</h2>
            <p class="section-desc">
                Thông tin dưới đây chỉ dùng cho mục đích mô phỏng trong đồ án, không phải danh sách bác sĩ thật.
            </p>

            <div class="grid grid-2 grid-3-lg">
                <div class="card">
                    <div class="doctor-avatar" style="background: radial-gradient(circle at top, #14b8a6, #0f766e);">
                        TM
                    </div>
                    <h3 class="doctor-name">BSCKII. Lê Minh P</h3>
                    <p class="doctor-position">Trưởng khoa Tai mũi họng</p>
                    <p class="doctor-desc">
                        Quan tâm đến quản lý các bệnh lý tai mũi họng mạn tính và phối hợp đa chuyên khoa
                        trong điều trị bệnh lý đường hô hấp trên (minh họa).
                    </p>
                </div>
                <div class="card">
                    <div class="doctor-avatar" style="background: radial-gradient(circle at top, #38bdf8, #0ea5e9);">
                        NX
                    </div>
                    <h3 class="doctor-name">ThS. BS. Nguyễn Xuân K</h3>
                    <p class="doctor-position">Bác sĩ Tai mũi họng</p>
                    <p class="doctor-desc">
                        Tập trung khám và điều trị các bệnh lý viêm mũi xoang, viêm họng, rối loạn giọng nói
                        và theo dõi lâu dài cho người bệnh (mô phỏng).
                    </p>
                </div>
                <div class="card">
                    <div class="doctor-avatar" style="background: radial-gradient(circle at top, #a855f7, #7c3aed);">
                        TE
                    </div>
                    <h3 class="doctor-name">BS. Trần Gia H</h3>
                    <p class="doctor-position">Bác sĩ Tai mũi họng trẻ em (minh họa)</p>
                    <p class="doctor-desc">
                        Khám và theo dõi viêm tai giữa, viêm amidan, viêm VA ở trẻ,
                        đồng thời tư vấn cho phụ huynh cách chăm sóc tai mũi họng cho trẻ nhỏ.
                    </p>
                </div>
            </div>
        </section>

        <!-- 5. Cơ sở vật chất – Thiết bị (minh họa) -->
        <section class="page-section">
            <h2 class="section-title">Cơ sở vật chất – Thiết bị (minh họa)</h2>
            <div class="card">
                <p class="card-text">
                    Trong đồ án, Khoa Tai mũi họng được mô tả với các khu vực chức năng
                    và thiết bị cơ bản để xây dựng luồng nghiệp vụ và giao diện hệ thống:
                </p>
                <ul class="facilities-list">
                    <li>Phòng khám Tai mũi họng với ghế khám chuyên dụng và hệ thống chiếu sáng (minh họa).</li>
                    <li>Phòng nội soi tai mũi họng dùng để quan sát mũi xoang, họng, thanh quản (mô tả trên website).</li>
                    <li>Khu vực xử trí dị vật tai – mũi – họng và các thủ thuật nhỏ phù hợp.</li>
                    <li>Khu tư vấn giáo dục sức khỏe cho người bệnh về chăm sóc tai mũi họng tại nhà.</li>
                </ul>
                <p class="note-small">
                    * Thông tin trên chỉ phục vụ mục đích minh họa trong đồ án, không phản ánh chính xác cơ sở vật chất
                    của bất kỳ bệnh viện cụ thể nào.
                </p>
            </div>
        </section>

        <!-- 6. Câu hỏi thường gặp -->
        <section class="page-section">
            <h2 class="section-title">Câu hỏi thường gặp</h2>

            <div class="grid">
                <details class="faq-item">
                    <summary class="faq-summary">
                        <span>Viêm mũi dị ứng có cần khám chuyên khoa không?</span>
                        <span class="faq-arrow">▼</span>
                    </summary>
                    <div class="faq-body">
                        Khi triệu chứng hắt hơi, chảy mũi trong, nghẹt mũi, ngứa mũi kéo dài hoặc ảnh hưởng nhiều
                        đến sinh hoạt, người bệnh nên khám Tai mũi họng để được tư vấn điều trị và kiểm soát yếu tố dị ứng.
                        (Thông tin minh họa, không thay thế tư vấn y khoa).
                    </div>
                </details>

                <details class="faq-item">
                    <summary class="faq-summary">
                        <span>Ù tai kéo dài có nguy hiểm không?</span>
                        <span class="faq-arrow">▼</span>
                    </summary>
                    <div class="faq-body">
                        Ù tai có nhiều nguyên nhân khác nhau. Nếu ù tai kèm nghe kém, chóng mặt hoặc kéo dài nhiều tuần,
                        người bệnh nên đi khám để được đánh giá, tránh bỏ sót các bệnh lý cần theo dõi lâu dài (mô phỏng).
                    </div>
                </details>

                <details class="faq-item">
                    <summary class="faq-summary">
                        <span>Trẻ hay viêm họng, viêm amidan có cần cắt amidan ngay không?</span>
                        <span class="faq-arrow">▼</span>
                    </summary>
                    <div class="faq-body">
                        Quyết định cắt amidan phụ thuộc vào số lần tái phát, mức độ nặng, ảnh hưởng đến sinh hoạt và phát triển của trẻ.
                        Cần được bác sĩ Tai mũi họng thăm khám trực tiếp và tư vấn cụ thể cho từng trường hợp (nội dung minh họa).
                    </div>
                </details>
            </div>
        </section>

        <!-- 7. Liên hệ – Đặt lịch khám Tai mũi họng -->
        <section id="dat-lich" class="page-section">
            <div class="contact-section">
                <div class="contact-left">
                    <h2 class="contact-left-title">Cần tư vấn hoặc đặt lịch khám Tai mũi họng?</h2>
                    <p class="contact-left-text">
                        Người bệnh có thể liên hệ tổng đài hoặc đặt lịch khám trực tuyến để được hỗ trợ sớm.
                        Nếu có khó thở, chảy máu mũi nhiều, dị vật nghi ngờ mắc ở đường thở,
                        đau tai dữ dội hoặc nuốt nghẹn tiến triển nhanh, hãy đến khoa Cấp cứu ngay lập tức.
                    </p>
                    <p class="contact-left-note">
                        Thông tin trên website chỉ mang tính minh họa cho đồ án, không dùng để tự chẩn đoán
                        hoặc tự điều trị. Luôn tham khảo ý kiến bác sĩ chuyên khoa khi đi khám thực tế.
                    </p>
                </div>
                <div class="contact-right">
                    <div>
                        <span class="contact-label">Hotline tư vấn (minh họa)</span>
                        <div class="contact-value-main">1900 0789</div>
                    </div>
                    <div style="margin-top: 6px;">
                        <span class="contact-label">Cấp cứu Tai mũi họng</span>
                        <div class="contact-value-sub">115 (hoặc số cấp cứu địa phương)</div>
                    </div>
                    <div class="contact-btn-wrapper">
                        <button type="button" class="btn-contact">
                            Đặt lịch khám Tai mũi họng (minh họa)
                        </button>
                    </div>
                </div>
            </div>
        </section>

    </div>
</main>

</body>
</html>
