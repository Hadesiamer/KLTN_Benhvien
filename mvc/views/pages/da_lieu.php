<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khoa Da liễu - Bệnh viện</title>
    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* ========= THEME KHOA DA LIỄU ========= */
        :root {
            --primary: #ec4899;          /* hồng cho da liễu / thẩm mỹ */
            --primary-dark: #be185d;
            --accent: #6366f1;
            --bg: #fdf2f8;
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
            box-shadow: 0 8px 20px rgba(236, 72, 153, 0.4);
        }

        /* ALERT */
        .alert {
            background-color: #fef2f2;
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
            color: #7f1d1d;
            margin-bottom: 2px;
        }

        .alert-text { color: #92400e; }

        /* HERO */
        .hero {
            background: radial-gradient(circle at top left, #ec4899, #0f172a);
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
            background-color: #fee2e2;
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
            transition: background-color .15s ease, color .15s ease, border-color .15s ease, transform .1s ease;
        }

        .btn-primary {
            background-color: var(--white);
            color: var(--primary-dark);
            box-shadow: 0 10px 25px rgba(190, 24, 93, 0.4);
        }

        .btn-primary:hover {
            background-color: #fef2f2;
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
            box-shadow: 0 16px 40px rgba(236, 72, 153, 0.5);
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

        .faq-summary:hover { background-color: #fdf2f8; }

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
            background: linear-gradient(to right, #ec4899, #6366f1);
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
            color: #fee2e2;
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

        .btn-contact:hover { background-color: #fef2f2; }

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
            <div class="alert-icon">🌸</div>
            <div>
                <div class="alert-title">Lưu ý</div>
                <div class="alert-text">
                    Đây là trang giới thiệu Khoa Da liễu trong đồ án sinh viên, nội dung chỉ mang tính minh họa,
                    không thay thế tư vấn y khoa hoặc chỉ định điều trị của bác sĩ chuyên khoa Da liễu.
                </div>
            </div>
        </div>
    </div>
</div>

<!-- HERO KHOA DA LIỄU -->
<section class="hero">
    <div class="container">
        <div class="hero-inner">
            <div class="hero-left">
                <div class="hero-pill">
                    <span class="hero-pill-dot"></span>
                    Khoa Da liễu – Chăm sóc da & các bệnh lý về da
                </div>
                <h1 class="hero-title">Khoa Da liễu</h1>
                <p class="hero-desc">
                    Khoa Da liễu tiếp nhận khám và điều trị các bệnh lý về da, tóc, móng
                    như viêm da cơ địa, mụn trứng cá, vảy nến, nấm da, dị ứng, bệnh lý tự miễn… (minh họa).
                    Mục tiêu là giúp người bệnh cải thiện triệu chứng, hạn chế tái phát và nâng cao chất lượng cuộc sống.
                </p>
                <div class="hero-actions">
                    <a href="#dat-lich" class="btn btn-primary">Đặt lịch khám Da liễu</a>
                    <a href="#trieuchung" class="btn btn-outline-light">Khi nào nên khám Da liễu?</a>
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
                            <span class="hero-card-value">Thứ 2 – Thứ 7: 7:00 – 17:00</span>
                        </li>
                        <li class="hero-card-row">
                            <span class="hero-card-label">Dịch vụ thẩm mỹ (minh họa)</span>
                            <span class="hero-card-value">Một số thủ thuật đơn giản</span>
                        </li>
                        <li class="hero-card-row">
                            <span class="hero-card-label">Khu điều trị</span>
                            <span class="hero-card-value">Phòng khám – Phòng thủ thuật da liễu</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<main>
    <div class="container">

        <!-- 1. Khi nào nên khám Da liễu -->
        <section id="trieuchung" class="page-section">
            <div class="section-header">
                <h2 class="section-title">Khi nào bạn nên khám tại Khoa Da liễu?</h2>
                <span class="badge badge-khoa">Chăm sóc sức khỏe làn da</span>
            </div>
            <p class="section-desc">
                Da là cơ quan lớn nhất của cơ thể, dễ bị tác động bởi môi trường, nội tiết và cơ địa.
                Người bệnh nên khám khi có các biểu hiện sau (thông tin minh họa):
            </p>

            <div class="grid grid-2 grid-3-lg">
                <div class="card">
                    <h3 class="card-title">Mẩn đỏ, ngứa kéo dài</h3>
                    <p class="card-text">
                        Vùng da đỏ, ngứa nhiều, nổi mề đay, chàm, viêm da cơ địa hoặc tái phát nhiều lần
                        cần được khám để tìm nguyên nhân và cách kiểm soát lâu dài.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Mụn trứng cá, sẹo mụn</h3>
                    <p class="card-text">
                        Mụn xuất hiện nhiều, viêm, để lại thâm sẹo, ảnh hưởng thẩm mỹ và tâm lý
                        nên được tư vấn phác đồ điều trị phù hợp, tránh tự nặn mụn (minh họa).
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Da khô bong vảy, nứt nẻ</h3>
                    <p class="card-text">
                        Da khô nhiều, bong vảy, nứt nẻ, đặc biệt ở tay – chân,
                        có thể liên quan đến vảy nến, viêm da cơ địa hoặc các bệnh da mạn tính khác.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Rụng tóc, thay đổi móng</h3>
                    <p class="card-text">
                        Tóc rụng nhiều, từng mảng hoặc rụng kéo dài,
                        móng tay chân đổi màu, dày lên, giòn… cần được đánh giá da – tóc – móng (minh họa).
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Nghi ngờ nấm da, nấm móng</h3>
                    <p class="card-text">
                        Ngứa, tróc vảy, loang lổ màu ở da, da ở kẽ chân bong vảy, hôi,
                        móng đục, dày, dễ gãy… là dấu hiệu thường gặp của nấm da, nấm móng.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Khám da định kỳ & tư vấn chăm sóc</h3>
                    <p class="card-text">
                        Khám da định kỳ, tư vấn dùng kem chống nắng, dưỡng ẩm, lựa chọn sản phẩm phù hợp
                        với loại da và cơ địa (nội dung minh họa).
                    </p>
                </div>
            </div>
        </section>

        <!-- 2. Dịch vụ & kỹ thuật -->
        <section class="page-section">
            <h2 class="section-title">Dịch vụ và kỹ thuật tại Khoa Da liễu</h2>
            <p class="section-desc">
                Một số dịch vụ minh họa trong đồ án về Da liễu (không phải danh sách đầy đủ):
            </p>

            <div class="grid grid-2 grid-3-lg">
                <div class="card">
                    <h3 class="card-title">Khám và điều trị bệnh da</h3>
                    <p class="card-text">
                        Khám các bệnh da thường gặp như viêm da cơ địa, vảy nến, mề đay, dị ứng thuốc, mụn trứng cá…
                        và tư vấn phác đồ điều trị phù hợp (minh họa).
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Điều trị nấm da – nấm móng</h3>
                    <p class="card-text">
                        Khám và xử trí các bệnh nấm da, nấm móng, tư vấn phối hợp vệ sinh – điều trị tại chỗ
                        và toàn thân khi cần (mô phỏng).
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Chăm sóc da mụn & sẹo</h3>
                    <p class="card-text">
                        Tư vấn điều trị mụn trứng cá, hạn chế thâm – sẹo, hướng dẫn chăm sóc da hằng ngày;
                        mô tả một số thủ thuật trong phạm vi đồ án (không phải hướng dẫn điều trị thật).
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Tư vấn dị ứng – mề đay</h3>
                    <p class="card-text">
                        Khai thác yếu tố dị ứng, tư vấn cách hạn chế tác nhân khởi phát,
                        hướng dẫn sử dụng thuốc theo đơn và theo dõi lâu dài (minh họa).
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Tư vấn chăm sóc da & chống nắng</h3>
                    <p class="card-text">
                        Hướng dẫn thói quen chăm sóc da, lựa chọn kem chống nắng – dưỡng ẩm phù hợp,
                        xây dựng thói quen sinh hoạt tốt cho da (mô tả trên website).
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Theo dõi bệnh da mạn tính</h3>
                    <p class="card-text">
                        Quản lý lâu dài các bệnh da mạn tính như vảy nến, lupus, bệnh da tự miễn (mô phỏng),
                        nhấn mạnh tầm quan trọng của tái khám theo hẹn.
                    </p>
                </div>
            </div>
        </section>

        <!-- 3. Quy trình khám -->
        <section class="page-section">
            <h2 class="section-title">Quy trình khám tại Khoa Da liễu</h2>
            <div class="card">
                <ol class="list-steps">
                    <li>
                        <span class="step-label">Bước 1 – Đăng ký khám:</span>
                        Đến quầy tiếp nhận hoặc đặt lịch trực tuyến, chọn “Khoa Da liễu”
                        và ghi rõ triệu chứng chính (mụn, ngứa, phát ban…).
                    </li>
                    <li>
                        <span class="step-label">Bước 2 – Khai thác bệnh sử:</span>
                        Bác sĩ hỏi về thời gian tổn thương xuất hiện, vị trí, yếu tố làm nặng,
                        các thuốc hoặc mỹ phẩm đã sử dụng trước đó.
                    </li>
                    <li>
                        <span class="step-label">Bước 3 – Khám da & cận lâm sàng (nếu cần):</span>
                        Khám da, tóc, móng; có thể lấy mẫu xét nghiệm nấm, xét nghiệm máu
                        hoặc các thăm dò khác tùy trường hợp (minh họa).
                    </li>
                    <li>
                        <span class="step-label">Bước 4 – Tư vấn & điều trị:</span>
                        Bác sĩ giải thích chẩn đoán, kê đơn thuốc bôi, thuốc uống
                        và hướng dẫn chi tiết cách sử dụng, thời gian theo dõi.
                    </li>
                    <li>
                        <span class="step-label">Bước 5 – Hẹn tái khám & theo dõi:</span>
                        Hẹn ngày tái khám để đánh giá đáp ứng điều trị, điều chỉnh phác đồ
                        hoặc kết hợp các phương pháp hỗ trợ khác (trong phạm vi mô phỏng).
                    </li>
                </ol>
            </div>
        </section>

        <!-- 4. Đội ngũ bác sĩ (minh họa) -->
        <section class="page-section">
            <h2 class="section-title">Đội ngũ bác sĩ (minh họa)</h2>
            <p class="section-desc">
                Thông tin bác sĩ dưới đây chỉ phục vụ cho mục đích minh họa trong đồ án.
            </p>

            <div class="grid grid-2 grid-3-lg">
                <div class="card">
                    <div class="doctor-avatar" style="background: radial-gradient(circle at top, #ec4899, #f97316);">
                        DL
                    </div>
                    <h3 class="doctor-name">BSCKII. Trần Thu L</h3>
                    <p class="doctor-position">Trưởng khoa Da liễu</p>
                    <p class="doctor-desc">
                        Có kinh nghiệm trong điều trị các bệnh da mạn tính, dị ứng, bệnh da tự miễn,
                        và tư vấn chăm sóc da lâu dài (minh họa).
                    </p>
                </div>
                <div class="card">
                    <div class="doctor-avatar" style="background: radial-gradient(circle at top, #6366f1, #4f46e5);">
                        MN
                    </div>
                    <h3 class="doctor-name">ThS. BS. Nguyễn Hải N</h3>
                    <p class="doctor-position">Bác sĩ Da liễu</p>
                    <p class="doctor-desc">
                        Tập trung điều trị mụn, sẹo mụn, viêm da cơ địa, vảy nến và một số bệnh da thường gặp
                        ở người trẻ (mô phỏng).
                    </p>
                </div>
                <div class="card">
                    <div class="doctor-avatar" style="background: radial-gradient(circle at top, #22c55e, #16a34a);">
                        TE
                    </div>
                    <h3 class="doctor-name">BS. Phạm Gia E</h3>
                    <p class="doctor-position">Bác sĩ Da liễu trẻ em (minh họa)</p>
                    <p class="doctor-desc">
                        Quan tâm đến bệnh da ở trẻ em như chàm, rôm sảy, dị ứng, nấm da,
                        hướng dẫn phụ huynh cách chăm sóc da cho trẻ.
                    </p>
                </div>
            </div>
        </section>

        <!-- 5. Cơ sở vật chất -->
        <section class="page-section">
            <h2 class="section-title">Cơ sở vật chất – Thiết bị (minh họa)</h2>
            <div class="card">
                <p class="card-text">
                    Trong đồ án, Khoa Da liễu được mô tả với một số khu vực chức năng cơ bản:
                </p>
                <ul class="facilities-list">
                    <li>Phòng khám Da liễu ngoại trú.</li>
                    <li>Phòng thủ thuật da liễu đơn giản (nặn mụn, xử lý sẹo, lấy mẫu da – mô phỏng).</li>
                    <li>Khu tư vấn chăm sóc da, hướng dẫn sử dụng thuốc và mỹ phẩm.</li>
                    <li>Khu vực chờ thân thiện, cung cấp tài liệu giáo dục sức khỏe về chăm sóc da.</li>
                </ul>
                <p class="note-small">
                    * Thông tin trên chỉ phục vụ cho mục đích minh họa trong đồ án, 
                    không phản ánh chính xác cơ sở vật chất của bất kỳ bệnh viện cụ thể nào.
                </p>
            </div>
        </section>

        <!-- 6. FAQ -->
        <section class="page-section">
            <h2 class="section-title">Câu hỏi thường gặp</h2>

            <div class="grid">
                <details class="faq-item">
                    <summary class="faq-summary">
                        <span>Mụn trứng cá có cần đi khám Da liễu không?</span>
                        <span class="faq-arrow">▼</span>
                    </summary>
                    <div class="faq-body">
                        Mụn nhẹ có thể cải thiện với chăm sóc da phù hợp, nhưng khi mụn viêm nhiều,
                        để lại thâm sẹo hoặc kéo dài, người bệnh nên đi khám để được tư vấn điều trị đúng.
                        Nội dung chỉ mang tính minh họa, không thay thế tư vấn y khoa.
                    </div>
                </details>

                <details class="faq-item">
                    <summary class="faq-summary">
                        <span>Có nên tự mua thuốc bôi khi bị ngứa da?</span>
                        <span class="faq-arrow">▼</span>
                    </summary>
                    <div class="faq-body">
                        Tự ý sử dụng thuốc bôi chứa corticoid kéo dài có thể làm mỏng da,
                        giãn mạch, thay đổi sắc tố và làm nặng thêm một số bệnh da.
                        Người bệnh nên khám để được kê đơn phù hợp (minh họa).
                    </div>
                </details>

                <details class="faq-item">
                    <summary class="faq-summary">
                        <span>Dùng kem chống nắng có cần thiết không?</span>
                        <span class="faq-arrow">▼</span>
                    </summary>
                    <div class="faq-body">
                        Tia UV có thể gây sạm da, lão hóa sớm và tăng nguy cơ ung thư da.
                        Dùng kem chống nắng phù hợp da, kết hợp che chắn và tránh nắng gắt là rất quan trọng
                        trong chăm sóc da dài lâu.
                    </div>
                </details>
            </div>
        </section>

        <!-- 7. Liên hệ – Đặt lịch -->
        <section id="dat-lich" class="page-section">
            <div class="contact-section">
                <div class="contact-left">
                    <h2 class="contact-left-title">Cần tư vấn hoặc đặt lịch khám Da liễu?</h2>
                    <p class="contact-left-text">
                        Người bệnh có thể liên hệ tổng đài hoặc đặt lịch khám trực tuyến để được tư vấn.
                        Nếu có dấu hiệu toàn thân nặng như khó thở, phù mặt, sốt cao kèm phát ban nhanh,
                        hãy đến khoa Cấp cứu ngay lập tức.
                    </p>
                    <p class="contact-left-note">
                        Thông tin trên website chỉ mang tính minh họa cho đồ án,
                        không dùng để tự chẩn đoán hoặc tự điều trị các bệnh lý da.
                    </p>
                </div>
                <div class="contact-right">
                    <div>
                        <span class="contact-label">Hotline tư vấn Da liễu (minh họa)</span>
                        <div class="contact-value-main">1900 0666</div>
                    </div>
                    <div style="margin-top: 6px;">
                        <span class="contact-label">Cấp cứu da – dị ứng nặng</span>
                        <div class="contact-value-sub">115 (hoặc số cấp cứu địa phương)</div>
                    </div>
                    <div class="contact-btn-wrapper">
                        <button type="button" class="btn-contact">
                            Đặt lịch khám Da liễu (minh họa)
                        </button>
                    </div>
                </div>
            </div>
        </section>

    </div>
</main>

</body>
</html>
