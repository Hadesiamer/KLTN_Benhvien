<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khoa Nhi - Bệnh viện</title>
    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* ========= THEME KHOA NHI ========= */
        :root {
            --primary: #f97316;          /* cam tươi, thân thiện cho nhi */
            --primary-dark: #c2410c;
            --accent: #22c55e;
            --bg: #fff7ed;
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
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            color: var(--white);
            box-shadow: 0 8px 20px rgba(249, 115, 22, 0.4);
        }

        /* ALERT */
        .alert {
            background-color: #fffbeb;
            border-left: 4px solid #f97316;
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
            color: #f97316;
        }

        .alert-title {
            font-weight: 600;
            color: #7c2d12;
            margin-bottom: 2px;
        }

        .alert-text { color: #92400e; }

        /* HERO */
        .hero {
            background: radial-gradient(circle at top left, #f97316, #0f172a);
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

        @media (min-width: 768px) { .hero-title { font-size: 34px; } }
        @media (min-width: 1024px) { .hero-title { font-size: 40px; } }

        .hero-desc {
            font-size: 14px;
            color: #ffedd5;
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
            background-color: #fffbeb;
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
            border: 1px solid rgba(248, 250, 252, 0.6);
            padding: 16px 18px;
            box-shadow: 0 16px 40px rgba(248, 113, 22, 0.5);
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
            border-top: 1px solid rgba(248, 250, 252, 0.3);
            margin-top: 4px;
            padding-top: 8px;
        }

        .hero-card-label { color: #e5e7eb; }
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

        .faq-summary:hover { background-color: #fffbeb; }

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
            background: linear-gradient(to right, #f97316, #22c55e);
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
            color: #ffedd5;
            margin: 0 0 4px;
        }

        .contact-left-note {
            font-size: 11px;
            color: #fed7aa;
        }

        .contact-right {
            text-align: right;
            font-size: 13px;
        }

        .contact-label {
            font-size: 11px;
            color: #ffedd5;
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
            color: #7c2d12;
            font-weight: 600;
            font-size: 12px;
            border: none;
            cursor: pointer;
            box-shadow: 0 10px 25px rgba(124, 45, 18, 0.35);
        }

        .btn-contact:hover { background-color: #fffbeb; }

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
            <div class="alert-icon">🧸</div>
            <div>
                <div class="alert-title">Lưu ý</div>
                <div class="alert-text">
                    Đây là trang giới thiệu Khoa Nhi trong đồ án sinh viên, nội dung chỉ mang tính minh họa,
                    không thay thế tư vấn y khoa hoặc chỉ định điều trị của bác sĩ nhi khoa.
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
                    Khoa Nhi – Chăm sóc sức khỏe trẻ em
                </div>
                <h1 class="hero-title">Khoa Nhi</h1>
                <p class="hero-desc">
                    Khoa Nhi tiếp nhận khám và điều trị các bệnh lý thường gặp ở trẻ em như sốt, ho, tiêu chảy,
                    nhiễm khuẩn hô hấp, bệnh lý dinh dưỡng, dị ứng… (minh họa). Mục tiêu là giúp trẻ hồi phục tốt,
                    phát triển thể chất và tinh thần khỏe mạnh.
                </p>
                <div class="hero-actions">
                    <a href="#dat-lich" class="btn btn-primary">Đặt lịch khám Nhi</a>
                    <a href="#trieuchung" class="btn btn-outline-light">Khi nào cần đưa trẻ đi khám?</a>
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
                            <span class="hero-card-value">Thứ 2 – Chủ nhật: 7:00 – 17:00</span>
                        </li>
                        <li class="hero-card-row">
                            <span class="hero-card-label">Khu nội trú</span>
                            <span class="hero-card-value">Khu điều trị nội trú Nhi (minh họa)</span>
                        </li>
                        <li class="hero-card-row">
                            <span class="hero-card-label">Đối tượng</span>
                            <span class="hero-card-value">Trẻ từ sơ sinh đến dưới 16 tuổi (mô phỏng)</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<main>
    <div class="container">

        <!-- 1. Khi nào nên khám Nhi -->
        <section id="trieuchung" class="page-section">
            <div class="section-header">
                <h2 class="section-title">Khi nào nên đưa trẻ đến Khoa Nhi?</h2>
                <span class="badge badge-khoa">Chăm sóc sức khỏe trẻ em</span>
            </div>
            <p class="section-desc">
                Trẻ em có hệ miễn dịch đang phát triển, dễ mắc bệnh lý nhiễm trùng và rối loạn dinh dưỡng.
                Phụ huynh nên đưa trẻ đi khám khi có các dấu hiệu sau (thông tin minh họa):
            </p>

            <div class="grid grid-2 grid-3-lg">
                <div class="card">
                    <h3 class="card-title">Sốt cao kéo dài</h3>
                    <p class="card-text">
                        Trẻ sốt cao liên tục, uống thuốc hạ sốt đáp ứng kém, sốt kèm li bì,
                        khó đánh thức, co giật hoặc khó thở… cần được đưa đi khám ngay.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Ho, khò khè, thở nhanh</h3>
                    <p class="card-text">
                        Ho kéo dài, khò khè, thở nhanh, rút lõm lồng ngực hoặc trẻ bỏ bú,
                        bú kém… có thể là dấu hiệu bệnh lý đường hô hấp ở trẻ nhỏ.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Tiêu chảy, nôn ói</h3>
                    <p class="card-text">
                        Trẻ đi ngoài nhiều lần, phân lỏng, nôn ói nhiều, uống vào lại nôn,
                        mắt trũng, khát nước nhiều là dấu hiệu nghi ngờ mất nước (minh họa).
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Phát ban, nổi mẩn toàn thân</h3>
                    <p class="card-text">
                        Trẻ sốt kèm phát ban, nổi mẩn đỏ hoặc tím, ngứa nhiều,
                        hoặc có dấu hiệu sưng môi, sưng mí mắt… cần được bác sĩ đánh giá.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Chậm tăng cân, biếng ăn</h3>
                    <p class="card-text">
                        Trẻ ăn uống kém, sụt cân hoặc không tăng cân kéo dài,
                        chậm phát triển so với lứa tuổi nên được khám để tìm nguyên nhân về dinh dưỡng, tiêu hóa.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Khám sức khỏe định kỳ</h3>
                    <p class="card-text">
                        Nên cho trẻ khám sức khỏe định kỳ, kiểm tra mốc phát triển,
                        tiêm chủng và tầm soát một số bệnh lý bẩm sinh (minh họa).
                    </p>
                </div>
            </div>
        </section>

        <!-- 2. Dịch vụ & kỹ thuật -->
        <section class="page-section">
            <h2 class="section-title">Dịch vụ và kỹ thuật tại Khoa Nhi</h2>
            <p class="section-desc">
                Một số dịch vụ minh họa trong đồ án ở lĩnh vực Nhi khoa:
            </p>

            <div class="grid grid-2 grid-3-lg">
                <div class="card">
                    <h3 class="card-title">Khám Nhi tổng quát</h3>
                    <p class="card-text">
                        Khám các bệnh lý thường gặp: sốt, ho, viêm đường hô hấp, tiêu chảy, nôn ói,
                        đánh giá tình trạng toàn thân và hướng dẫn theo dõi tại nhà (minh họa).
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Theo dõi bệnh hô hấp</h3>
                    <p class="card-text">
                        Quản lý các trường hợp viêm phế quản, hen phế quản, viêm phổi…
                        theo phác đồ được mô phỏng trong đồ án, đồng thời tư vấn chăm sóc hô hấp cho trẻ.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Bệnh lý tiêu hóa – dinh dưỡng</h3>
                    <p class="card-text">
                        Khám các vấn đề về tiêu hóa, táo bón, tiêu chảy kéo dài,
                        phối hợp với chuyên khoa Dinh dưỡng để xây dựng khẩu phần phù hợp (minh họa).
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Khám phát triển – sàng lọc</h3>
                    <p class="card-text">
                        Đánh giá sơ bộ mốc phát triển vận động – ngôn ngữ,
                        tư vấn phụ huynh về việc theo dõi phát triển tâm – vận động của trẻ (mô phỏng).
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Tư vấn tiêm chủng</h3>
                    <p class="card-text">
                        Tư vấn lịch tiêm chủng mở rộng, một số vắc-xin dịch vụ (minh họa),
                        hướng dẫn theo dõi phản ứng sau tiêm.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Giáo dục sức khỏe cho phụ huynh</h3>
                    <p class="card-text">
                        Hướng dẫn chăm sóc trẻ khi sốt, ho, tiêu chảy, cách dùng thuốc an toàn,
                        cách nhận biết dấu hiệu cần đưa trẻ đến bệnh viện.
                    </p>
                </div>
            </div>
        </section>

        <!-- 3. Quy trình khám -->
        <section class="page-section">
            <h2 class="section-title">Quy trình khám tại Khoa Nhi</h2>
            <div class="card">
                <ol class="list-steps">
                    <li>
                        <span class="step-label">Bước 1 – Đăng ký khám:</span>
                        Phụ huynh đến quầy tiếp nhận hoặc đặt lịch trực tuyến, chọn “Khoa Nhi”
                        và cung cấp thông tin triệu chứng của trẻ.
                    </li>
                    <li>
                        <span class="step-label">Bước 2 – Khai thác bệnh sử:</span>
                        Bác sĩ hỏi về thời gian trẻ bị sốt, ho, tiêu chảy, ăn uống, giấc ngủ,
                        tiền sử bệnh, tiêm chủng và các thuốc đã dùng ở nhà.
                    </li>
                    <li>
                        <span class="step-label">Bước 3 – Khám lâm sàng & cận lâm sàng:</span>
                        Khám toàn thân, đo sinh hiệu, nghe tim phổi, khám bụng…
                        kết hợp xét nghiệm, chụp X-quang hoặc các kiểm tra khác theo chỉ định (minh họa).
                    </li>
                    <li>
                        <span class="step-label">Bước 4 – Tư vấn & điều trị:</span>
                        Bác sĩ giải thích tình trạng sức khỏe của trẻ, kê đơn thuốc,
                        hướng dẫn cách theo dõi và chăm sóc tại nhà.
                    </li>
                    <li>
                        <span class="step-label">Bước 5 – Hẹn tái khám:</span>
                        Đặt lịch tái khám khi cần kiểm tra lại, đánh giá đáp ứng điều trị
                        hoặc theo dõi mốc phát triển.
                    </li>
                </ol>
            </div>
        </section>

        <!-- 4. Đội ngũ bác sĩ (minh họa) -->
        <section class="page-section">
            <h2 class="section-title">Đội ngũ bác sĩ (minh họa)</h2>
            <p class="section-desc">
                Thông tin sau chỉ phục vụ mục đích mô phỏng trong đồ án, không phải danh sách bác sĩ thật.
            </p>

            <div class="grid grid-2 grid-3-lg">
                <div class="card">
                    <div class="doctor-avatar" style="background: radial-gradient(circle at top, #f97316, #ea580c);">
                        NP
                    </div>
                    <h3 class="doctor-name">BSCKII. Phạm Minh P</h3>
                    <p class="doctor-position">Trưởng khoa Nhi</p>
                    <p class="doctor-desc">
                        Có kinh nghiệm trong quản lý bệnh lý nhi khoa nặng, điều trị các ca hô hấp –
                        tiêu hóa và tư vấn chăm sóc trẻ sau xuất viện (minh họa).
                    </p>
                </div>
                <div class="card">
                    <div class="doctor-avatar" style="background: radial-gradient(circle at top, #22c55e, #16a34a);">
                        NH
                    </div>
                    <h3 class="doctor-name">ThS. BS. Lê Hồng H</h3>
                    <p class="doctor-position">Bác sĩ Nhi tổng quát</p>
                    <p class="doctor-desc">
                        Tập trung vào khám ngoại trú cho trẻ, tư vấn dùng thuốc an toàn,
                        phối hợp giáo dục sức khỏe cho phụ huynh (mô phỏng).
                    </p>
                </div>
                <div class="card">
                    <div class="doctor-avatar" style="background: radial-gradient(circle at top, #38bdf8, #0ea5e9);">
                        ST
                    </div>
                    <h3 class="doctor-name">BS. Nguyễn Mai T</h3>
                    <p class="doctor-position">Bác sĩ Sơ sinh (minh họa)</p>
                    <p class="doctor-desc">
                        Quan tâm đến chăm sóc trẻ sơ sinh, trẻ sinh non, hướng dẫn cho mẹ về nuôi con bằng sữa mẹ
                        và theo dõi sau sinh.
                    </p>
                </div>
            </div>
        </section>

        <!-- 5. Cơ sở vật chất -->
        <section class="page-section">
            <h2 class="section-title">Cơ sở vật chất – Thiết bị (minh họa)</h2>
            <div class="card">
                <p class="card-text">
                    Trong đồ án, Khoa Nhi được mô phỏng với các khu vực chức năng cơ bản để xây dựng luồng nghiệp vụ:
                </p>
                <ul class="facilities-list">
                    <li>Phòng khám nhi ngoại trú thân thiện với trẻ.</li>
                    <li>Khu theo dõi, truyền dịch, khí dung cho trẻ (mô tả trong website).</li>
                    <li>Phòng chơi – khu vực chờ có đồ chơi đơn giản để trẻ bớt lo lắng (minh họa).</li>
                    <li>Khu tư vấn cho phụ huynh về chăm sóc trẻ, dinh dưỡng, tiêm chủng.</li>
                </ul>
                <p class="note-small">
                    * Thông tin trên mang tính minh họa, không phản ánh chính xác cơ sở vật chất của một bệnh viện cụ thể.
                </p>
            </div>
        </section>

        <!-- 6. FAQ -->
        <section class="page-section">
            <h2 class="section-title">Câu hỏi thường gặp</h2>

            <div class="grid">
                <details class="faq-item">
                    <summary class="faq-summary">
                        <span>Khi trẻ sốt bao lâu thì nên đưa đi khám?</span>
                        <span class="faq-arrow">▼</span>
                    </summary>
                    <div class="faq-body">
                        Tùy độ tuổi và biểu hiện đi kèm, nếu trẻ sốt cao kéo dài, khó hạ sốt,
                        kèm li bì, khó thở, co giật hoặc bỏ bú… cần đưa đi khám ngay.
                        Nội dung chỉ mang tính minh họa, không thay thế tư vấn y khoa.
                    </div>
                </details>

                <details class="faq-item">
                    <summary class="faq-summary">
                        <span>Có nên tự ý dùng kháng sinh cho trẻ tại nhà?</span>
                        <span class="faq-arrow">▼</span>
                    </summary>
                    <div class="faq-body">
                        Kháng sinh cần được dùng theo chỉ định và liều lượng phù hợp.
                        Việc tự ý dùng kháng sinh có thể gây kháng thuốc và che lấp triệu chứng.
                        Phụ huynh nên liên hệ bác sĩ khi nghi ngờ trẻ cần dùng kháng sinh (minh họa).
                    </div>
                </details>

                <details class="faq-item">
                    <summary class="faq-summary">
                        <span>Có cần khám Nhi định kỳ cho trẻ khỏe mạnh không?</span>
                        <span class="faq-arrow">▼</span>
                    </summary>
                    <div class="faq-body">
                        Khám định kỳ giúp theo dõi chiều cao, cân nặng, mốc phát triển và tiêm chủng đúng lịch.
                        Lịch khám cụ thể phụ thuộc độ tuổi và tình trạng của từng trẻ.
                    </div>
                </details>
            </div>
        </section>

        <!-- 7. Liên hệ – Đặt lịch -->
        <section id="dat-lich" class="page-section">
            <div class="contact-section">
                <div class="contact-left">
                    <h2 class="contact-left-title">Cần tư vấn hoặc đặt lịch khám Nhi?</h2>
                    <p class="contact-left-text">
                        Phụ huynh có thể liên hệ tổng đài hoặc đặt lịch khám trực tuyến để được hỗ trợ.
                        Nếu trẻ có dấu hiệu khó thở, tím tái, co giật, lơ mơ hoặc uống kém nhiều,
                        hãy đưa trẻ đến khoa Cấp cứu ngay lập tức.
                    </p>
                    <p class="contact-left-note">
                        Thông tin trên website chỉ mang tính minh họa cho đồ án,
                        không dùng để tự chẩn đoán hoặc tự điều trị cho trẻ.
                    </p>
                </div>
                <div class="contact-right">
                    <div>
                        <span class="contact-label">Hotline tư vấn Nhi (minh họa)</span>
                        <div class="contact-value-main">1900 0303</div>
                    </div>
                    <div style="margin-top: 6px;">
                        <span class="contact-label">Cấp cứu Nhi 24/7</span>
                        <div class="contact-value-sub">115 (hoặc số cấp cứu địa phương)</div>
                    </div>
                    <div class="contact-btn-wrapper">
                        <button type="button" class="btn-contact">
                            Đặt lịch khám Nhi (minh họa)
                        </button>
                    </div>
                </div>
            </div>
        </section>

    </div>
</main>

</body>
</html>
