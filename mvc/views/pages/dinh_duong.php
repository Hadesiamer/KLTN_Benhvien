<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khoa Dinh dưỡng - Bệnh viện</title>
    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* ========= THEME KHOA DINH DƯỠNG ========= */
        :root {
            --primary: #22c55e;          /* xanh lá tươi cho dinh dưỡng */
            --primary-dark: #15803d;
            --accent: #0ea5e9;
            --bg: #ecfdf5;
            --text-main: #022c22;
            --text-sub: #4b5563;
            --border: #d1fae5;
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
            box-shadow: 0 8px 20px rgba(34, 197, 94, 0.4);
        }

        /* ALERT */
        .alert {
            background-color: #dcfce7;
            border-left: 4px solid #22c55e;
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
            color: #16a34a;
        }

        .alert-title {
            font-weight: 600;
            color: #14532d;
            margin-bottom: 2px;
        }

        .alert-text { color: #166534; }

        /* HERO */
        .hero {
            background: radial-gradient(circle at top left, #22c55e, #0f172a);
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
            color: #dcfce7;
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
            background-color: #dcfce7;
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
            box-shadow: 0 16px 40px rgba(34, 197, 94, 0.5);
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

        .hero-card-label { color: #e2e8f0; }
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

        .faq-summary:hover { background-color: #dcfce7; }

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
            background: linear-gradient(to right, #22c55e, #0ea5e9);
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
            color: #dcfce7;
            margin: 0 0 4px;
        }

        .contact-left-note {
            font-size: 11px;
            color: #bbf7d0;
        }

        .contact-right {
            text-align: right;
            font-size: 13px;
        }

        .contact-label {
            font-size: 11px;
            color: #dcfce7;
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
            color: #166534;
            font-weight: 600;
            font-size: 12px;
            border: none;
            cursor: pointer;
            box-shadow: 0 10px 25px rgba(22, 101, 52, 0.35);
        }

        .btn-contact:hover { background-color: #dcfce7; }

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
            <div class="alert-icon">🥦</div>
            <div>
                <div class="alert-title">Lưu ý</div>
                <div class="alert-text">
                    Đây là trang giới thiệu Khoa Dinh dưỡng trong đồ án sinh viên, nội dung chỉ mang tính minh họa,
                    không thay thế tư vấn dinh dưỡng hoặc chỉ định điều trị của chuyên gia dinh dưỡng.
                </div>
            </div>
        </div>
    </div>
</div>

<!-- HERO KHOA DINH DƯỠNG -->
<section class="hero">
    <div class="container">
        <div class="hero-inner">
            <div class="hero-left">
                <div class="hero-pill">
                    <span class="hero-pill-dot"></span>
                    Khoa Dinh dưỡng – Xây dựng chế độ ăn hợp lý
                </div>
                <h1 class="hero-title">Khoa Dinh dưỡng</h1>
                <p class="hero-desc">
                    Khoa Dinh dưỡng hỗ trợ xây dựng chế độ ăn phù hợp cho từng nhóm người bệnh
                    như suy dinh dưỡng, thừa cân – béo phì, đái tháo đường, tăng huyết áp, bệnh lý tiêu hóa…
                    Nội dung trên trang chỉ mang tính minh họa cho đồ án.
                </p>
                <div class="hero-actions">
                    <a href="#dat-lich" class="btn btn-primary">Đặt lịch tư vấn Dinh dưỡng</a>
                    <a href="#trieuchung" class="btn btn-outline-light">Khi nào nên khám Dinh dưỡng?</a>
                </div>
            </div>
            <div class="hero-right">
                <div class="hero-card">
                    <h2 class="hero-card-title">Thông tin nhanh</h2>
                    <ul class="hero-card-list">
                        <li class="hero-card-row">
                            <span class="hero-card-label">Địa điểm</span>
                            <span class="hero-card-value">Tầng 1 – Khu A</span>
                        </li>
                        <li class="hero-card-row">
                            <span class="hero-card-label">Giờ làm việc</span>
                            <span class="hero-card-value">Thứ 2 – Thứ 6: 7:00 – 16:30</span>
                        </li>
                        <li class="hero-card-row">
                            <span class="hero-card-label">Đối tượng tư vấn</span>
                            <span class="hero-card-value">Trẻ em, người lớn, bệnh mạn tính (minh họa)</span>
                        </li>
                        <li class="hero-card-row">
                            <span class="hero-card-label">Hình thức</span>
                            <span class="hero-card-value">Tư vấn trực tiếp tại bệnh viện</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<main>
    <div class="container">

        <!-- 1. Khi nào nên khám Dinh dưỡng -->
        <section id="trieuchung" class="page-section">
            <div class="section-header">
                <h2 class="section-title">Khi nào bạn nên đến Khoa Dinh dưỡng?</h2>
                <span class="badge badge-khoa">Chăm sóc dinh dưỡng hợp lý</span>
            </div>
            <p class="section-desc">
                Dinh dưỡng hợp lý giúp hỗ trợ điều trị, phục hồi và phòng ngừa nhiều bệnh mạn tính.
                Người bệnh nên tham khảo ý kiến chuyên gia dinh dưỡng khi có các vấn đề sau (minh họa):
            </p>

            <div class="grid grid-2 grid-3-lg">
                <div class="card">
                    <h3 class="card-title">Thừa cân – béo phì</h3>
                    <p class="card-text">
                        Cân nặng tăng nhanh, BMI cao, vòng bụng lớn, khó kiểm soát ăn uống,
                        muốn xây dựng chế độ giảm cân an toàn và bền vững (mô phỏng).
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Suy dinh dưỡng, thiếu cân</h3>
                    <p class="card-text">
                        Trẻ em hoặc người lớn gầy, thiếu cân, ăn uống kém, mệt mỏi kéo dài,
                        cần được đánh giá khẩu phần và hỗ trợ tăng cân lành mạnh.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Bệnh lý đái tháo đường, tim mạch</h3>
                    <p class="card-text">
                        Người bệnh đái tháo đường, tăng huyết áp, rối loạn mỡ máu
                        cần chế độ ăn phù hợp để kiểm soát đường huyết, huyết áp và mỡ máu (minh họa).
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Bệnh lý tiêu hóa, gan, thận</h3>
                    <p class="card-text">
                        Người bệnh có viêm loét dạ dày, viêm gan, suy thận, bệnh đường ruột…
                        cần được tư vấn hạn chế một số thực phẩm và cách phân bổ bữa ăn trong ngày.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Dinh dưỡng cho trẻ em & tuổi học đường</h3>
                    <p class="card-text">
                        Trẻ biếng ăn, chậm tăng cân, thừa cân, hoặc gia đình muốn xây dựng khẩu phần
                        phù hợp lứa tuổi học đường (nội dung minh họa).
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Dinh dưỡng cho phụ nữ mang thai & cho con bú</h3>
                    <p class="card-text">
                        Cần tư vấn xây dựng khẩu phần giúp mẹ khỏe, thai phát triển tốt
                        và hỗ trợ nuôi con bằng sữa mẹ (mô tả trên website).
                    </p>
                </div>
            </div>
        </section>

        <!-- 2. Dịch vụ & kỹ thuật -->
        <section class="page-section">
            <h2 class="section-title">Dịch vụ tư vấn tại Khoa Dinh dưỡng</h2>
            <p class="section-desc">
                Một số dịch vụ minh họa trong đồ án tại Khoa Dinh dưỡng:
            </p>

            <div class="grid grid-2 grid-3-lg">
                <div class="card">
                    <h3 class="card-title">Đánh giá tình trạng dinh dưỡng</h3>
                    <p class="card-text">
                        Đo chiều cao, cân nặng, BMI, vòng bụng; đánh giá sơ bộ khẩu phần ăn hằng ngày
                        và mức độ hoạt động thể lực (minh họa).
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Xây dựng khẩu phần cá nhân hóa</h3>
                    <p class="card-text">
                        Mô phỏng xây dựng khẩu phần theo nhóm bệnh, tuổi, giới, mức độ hoạt động;
                        gợi ý cách phân bố bữa ăn trong ngày phù hợp.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Tư vấn dinh dưỡng cho bệnh mạn tính</h3>
                    <p class="card-text">
                        Tư vấn chế độ ăn phù hợp với đái tháo đường, tăng huyết áp,
                        rối loạn mỡ máu, bệnh thận, bệnh gan… (nội dung minh họa).
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Hỗ trợ giảm cân lành mạnh</h3>
                    <p class="card-text">
                        Hướng dẫn thay đổi thói quen ăn uống, lựa chọn thực phẩm,
                        kiểm soát khẩu phần và kết hợp vận động (mô phỏng).
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Dinh dưỡng cho trẻ em</h3>
                    <p class="card-text">
                        Tư vấn khẩu phần cho trẻ theo từng giai đoạn: ăn dặm, tuổi mẫu giáo, tuổi học đường;
                        nhấn mạnh vai trò của bữa sáng và đa dạng thực phẩm (minh họa).
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Giáo dục sức khỏe về dinh dưỡng</h3>
                    <p class="card-text">
                        Cung cấp tài liệu, giải thích nhãn dinh dưỡng, hướng dẫn cách đọc thành phần thực phẩm
                        để người bệnh tự lựa chọn thực phẩm tốt hơn.
                    </p>
                </div>
            </div>
        </section>

        <!-- 3. Quy trình tư vấn -->
        <section class="page-section">
            <h2 class="section-title">Quy trình tư vấn tại Khoa Dinh dưỡng</h2>
            <div class="card">
                <ol class="list-steps">
                    <li>
                        <span class="step-label">Bước 1 – Đăng ký tư vấn:</span>
                        Đến quầy tiếp nhận hoặc đặt lịch trực tuyến, chọn “Khoa Dinh dưỡng”
                        và ghi rõ mục đích tư vấn (giảm cân, tăng cân, bệnh mạn tính…).
                    </li>
                    <li>
                        <span class="step-label">Bước 2 – Khai thác thói quen ăn uống:</span>
                        Chuyên viên dinh dưỡng hỏi về số bữa ăn trong ngày, loại thức ăn thường dùng,
                        khẩu vị, thời gian ăn, mức độ vận động.
                    </li>
                    <li>
                        <span class="step-label">Bước 3 – Đánh giá tình trạng dinh dưỡng:</span>
                        Đo các chỉ số cơ thể (chiều cao, cân nặng, BMI…), đánh giá sơ bộ tình trạng hiện tại
                        và bệnh lý liên quan (nếu có – minh họa).
                    </li>
                    <li>
                        <span class="step-label">Bước 4 – Đề xuất chế độ ăn & tư vấn:</span>
                        Đưa ra khuyến nghị về khẩu phần, nhóm thực phẩm nên ưu tiên/hạn chế,
                        cách phân bổ bữa ăn trong ngày và lưu ý khi áp dụng.
                    </li>
                    <li>
                        <span class="step-label">Bước 5 – Hẹn tái tư vấn:</span>
                        Hẹn thời gian tái đánh giá sau một khoảng thời gian để điều chỉnh chế độ ăn
                        dựa trên mức độ đáp ứng của người bệnh.
                    </li>
                </ol>
            </div>
        </section>

        <!-- 4. Đội ngũ (minh họa) -->
        <section class="page-section">
            <h2 class="section-title">Đội ngũ tư vấn dinh dưỡng (minh họa)</h2>
            <p class="section-desc">
                Danh sách bên dưới chỉ dùng cho mục đích mô phỏng trong đồ án.
            </p>

            <div class="grid grid-2 grid-3-lg">
                <div class="card">
                    <div class="doctor-avatar" style="background: radial-gradient(circle at top, #22c55e, #16a34a);">
                        DD
                    </div>
                    <h3 class="doctor-name">ThS. BS. Trần Bảo D</h3>
                    <p class="doctor-position">Phụ trách Khoa Dinh dưỡng</p>
                    <p class="doctor-desc">
                        Tập trung vào hoạch định chế độ ăn cho người bệnh mạn tính
                        và hỗ trợ dinh dưỡng lâm sàng (nội dung minh họa).
                    </p>
                </div>
                <div class="card">
                    <div class="doctor-avatar" style="background: radial-gradient(circle at top, #0ea5e9, #2563eb);">
                        NK
                    </div>
                    <h3 class="doctor-name">CN. Đinh Ngọc K</h3>
                    <p class="doctor-position">Chuyên viên Dinh dưỡng</p>
                    <p class="doctor-desc">
                        Tham gia tư vấn khẩu phần cho người thừa cân – béo phì,
                        xây dựng kế hoạch thay đổi lối sống (mô phỏng).
                    </p>
                </div>
                <div class="card">
                    <div class="doctor-avatar" style="background: radial-gradient(circle at top, #f97316, #ea580c);">
                        TE
                    </div>
                    <h3 class="doctor-name">CN. Lê Anh E</h3>
                    <p class="doctor-position">Chuyên viên Dinh dưỡng nhi (minh họa)</p>
                    <p class="doctor-desc">
                        Quan tâm đến dinh dưỡng cho trẻ em, hướng dẫn phụ huynh xây dựng khẩu phần ăn phù hợp
                        theo từng độ tuổi.
                    </p>
                </div>
            </div>
        </section>

        <!-- 5. Cơ sở vật chất -->
        <section class="page-section">
            <h2 class="section-title">Cơ sở vật chất – Hoạt động (minh họa)</h2>
            <div class="card">
                <p class="card-text">
                    Trong đồ án, Khoa Dinh dưỡng được thiết kế với một số khu vực mô phỏng:
                </p>
                <ul class="facilities-list">
                    <li>Phòng tư vấn dinh dưỡng cá nhân.</li>
                    <li>Khu vực cân đo và đánh giá tình trạng dinh dưỡng.</li>
                    <li>Không gian cung cấp tài liệu, mô hình minh họa khẩu phần ăn.</li>
                    <li>Phối hợp với khoa điều trị nội trú để tư vấn suất ăn cho người bệnh (mô tả trên website).</li>
                </ul>
                <p class="note-small">
                    * Thông tin mang tính minh họa, không phản ánh chính xác mô hình một khoa Dinh dưỡng cụ thể.
                </p>
            </div>
        </section>

        <!-- 6. FAQ -->
        <section class="page-section">
            <h2 class="section-title">Câu hỏi thường gặp</h2>

            <div class="grid">
                <details class="faq-item">
                    <summary class="faq-summary">
                        <span>Giảm cân có cần kiêng hoàn toàn tinh bột không?</span>
                        <span class="faq-arrow">▼</span>
                    </summary>
                    <div class="faq-body">
                        Việc giảm cân an toàn thường không khuyến khích bỏ hẳn một nhóm chất.
                        Thay vào đó, cần phân bố lượng tinh bột hợp lý, ưu tiên tinh bột nguyên hạt
                        và kết hợp vận động (nội dung minh họa, không thay thế tư vấn cá nhân).
                    </div>
                </details>

                <details class="faq-item">
                    <summary class="faq-summary">
                        <span>Người bệnh đái tháo đường có phải kiêng hết đồ ngọt?</span>
                        <span class="faq-arrow">▼</span>
                    </summary>
                    <div class="faq-body">
                        Người bệnh đái tháo đường cần kiểm soát lượng đường và tinh bột,
                        nhưng chế độ ăn cụ thể cần được điều chỉnh theo từng cá nhân.
                        Người bệnh nên được tư vấn trực tiếp bởi chuyên gia dinh dưỡng và bác sĩ điều trị.
                    </div>
                </details>

                <details class="faq-item">
                    <summary class="faq-summary">
                        <span>Có nên dùng thực phẩm chức năng để thay thế bữa ăn?</span>
                        <span class="faq-arrow">▼</span>
                    </summary>
                    <div class="faq-body">
                        Thực phẩm chức năng không thay thế được bữa ăn đa dạng và cân bằng.
                        Việc sử dụng cần được cân nhắc theo chỉ định, tránh lạm dụng.
                        Thông tin này chỉ mang tính tham khảo, không phải hướng dẫn cá nhân hóa.
                    </div>
                </details>
            </div>
        </section>

        <!-- 7. Liên hệ – Đặt lịch -->
        <section id="dat-lich" class="page-section">
            <div class="contact-section">
                <div class="contact-left">
                    <h2 class="contact-left-title">Cần tư vấn hoặc đặt lịch tại Khoa Dinh dưỡng?</h2>
                    <p class="contact-left-text">
                        Người bệnh có thể liên hệ tổng đài hoặc đặt lịch trực tuyến để được sắp xếp buổi tư vấn.
                        Khi có dấu hiệu mệt nhiều, sụt cân nhanh, khó ăn uống kèm bệnh lý nặng,
                        người bệnh nên đến khám tại các khoa lâm sàng phù hợp trước.
                    </p>
                    <p class="contact-left-note">
                        Thông tin trên website chỉ mang tính minh họa trong đồ án,
                        không dùng để tự xây dựng chế độ ăn điều trị cho bản thân.
                    </p>
                </div>
                <div class="contact-right">
                    <div>
                        <span class="contact-label">Hotline tư vấn Dinh dưỡng (minh họa)</span>
                        <div class="contact-value-main">1900 0888</div>
                    </div>
                    <div style="margin-top: 6px;">
                        <span class="contact-label">Thông tin hỗ trợ</span>
                        <div class="contact-value-sub">Liên hệ quầy hướng dẫn tại bệnh viện</div>
                    </div>
                    <div class="contact-btn-wrapper">
                        <button type="button" class="btn-contact">
                            Đặt lịch tư vấn Dinh dưỡng (minh họa)
                        </button>
                    </div>
                </div>
            </div>
        </section>

    </div>
</main>

</body>
</html>
