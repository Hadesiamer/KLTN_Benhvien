<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khoa Răng hàm mặt - Bệnh viện</title>
    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* ========= THEME CHO KHOA RĂNG HÀM MẶT ========= */
        :root {
            --primary: #fb923c;          /* cam cho răng hàm mặt */
            --primary-dark: #c2410c;
            --cyan: #22c55e;
            --bg: #fff7ed;
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
            box-shadow: 0 8px 20px rgba(248, 113, 22, 0.4);
        }

        /* ========= ALERT THÔNG BÁO ĐỒ ÁN ========= */
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

        .alert-text {
            color: #92400e;
        }

        /* ========= HERO KHOA RĂNG HÀM MẶT ========= */
        .hero {
            background: radial-gradient(circle at top left, #fb923c, #0f172a);
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
            color: #fed7aa;
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

        .hero-card-label {
            color: #e5e7eb;
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
            background-color: #fff7ed;
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
            background: linear-gradient(to right, #fb923c, #22c55e);
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
            color: #7c2d12;
            font-weight: 600;
            font-size: 12px;
            border: none;
            cursor: pointer;
            box-shadow: 0 10px 25px rgba(124, 45, 18, 0.35);
        }

        .btn-contact:hover {
            background-color: #fffbeb;
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
            <div class="alert-icon">🦷</div>
            <div>
                <div class="alert-title">Lưu ý</div>
                <div class="alert-text">
                    Đây là trang giới thiệu Khoa Răng hàm mặt trong đồ án sinh viên, nội dung chỉ mang tính minh họa,
                    không thay thế tư vấn y khoa hoặc chỉ định điều trị của bác sĩ chuyên khoa.
                </div>
            </div>
        </div>
    </div>
</div>

<!-- HERO KHOA RĂNG HÀM MẶT -->
<section class="hero">
    <div class="container">
        <div class="hero-inner">
            <div class="hero-left">
                <div class="hero-pill">
                    <span class="hero-pill-dot"></span>
                    Khoa Răng hàm mặt – Chăm sóc nụ cười & sức khỏe khoang miệng
                </div>
                <h1 class="hero-title">Khoa Răng hàm mặt</h1>
                <p class="hero-desc">
                    Khoa Răng hàm mặt cung cấp dịch vụ khám, tư vấn và điều trị các bệnh lý răng miệng, nha chu,
                    chỉnh nha, phục hình răng và phẫu thuật hàm mặt (minh họa). Mục tiêu là giúp người bệnh
                    duy trì chức năng ăn nhai, phát âm cũng như thẩm mỹ nụ cười.
                </p>
                <div class="hero-actions">
                    <a href="#dat-lich" class="btn btn-primary">Đặt lịch khám Răng hàm mặt</a>
                    <a href="#trieuchung" class="btn btn-outline-light">Khi nào cần đi khám răng?</a>
                </div>
            </div>
            <div class="hero-right">
                <div class="hero-card">
                    <h2 class="hero-card-title">Thông tin nhanh</h2>
                    <ul class="hero-card-list">
                        <li class="hero-card-row">
                            <span class="hero-card-label">Địa điểm</span>
                            <span class="hero-card-value">Tầng 3 – Khu A</span>
                        </li>
                        <li class="hero-card-row">
                            <span class="hero-card-label">Giờ làm việc</span>
                            <span class="hero-card-value">Thứ 2 – Thứ 7: 7:00 – 17:00</span>
                        </li>
                        <li class="hero-card-row">
                            <span class="hero-card-label">Cấp cứu Răng hàm mặt</span>
                            <span class="hero-card-value">Liên kết Khoa Cấp cứu 24/7</span>
                        </li>
                        <li class="hero-card-row">
                            <span class="hero-card-label">Khu điều trị</span>
                            <span class="hero-card-value">Phòng khám – Phòng thủ thuật – Phòng phẫu thuật (minh họa)</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<main>
    <div class="container">

        <!-- 1. Khi nào nên khám tại Khoa Răng hàm mặt -->
        <section id="trieuchung" class="page-section">
            <div class="section-header">
                <h2 class="section-title">Khi nào bạn nên khám tại Khoa Răng hàm mặt?</h2>
                <span class="badge badge-khoa">Chăm sóc sức khỏe răng miệng</span>
            </div>
            <p class="section-desc">
                Người bệnh nên khám Răng hàm mặt định kỳ hoặc khi có những triệu chứng sau
                để được phát hiện sớm bệnh lý và điều trị đúng hướng (thông tin minh họa).
            </p>

            <div class="grid grid-2 grid-3-lg">
                <div class="card">
                    <h3 class="card-title">Đau răng, ê buốt khi ăn uống</h3>
                    <p class="card-text">
                        Đau răng, ê buốt khi ăn đồ nóng/lạnh/ngọt, đau âm ỉ hoặc nhói từng cơn
                        có thể liên quan đến sâu răng, viêm tủy hoặc mòn cổ răng.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Chảy máu lợi, hôi miệng</h3>
                    <p class="card-text">
                        Lợi dễ chảy máu khi chải răng, hôi miệng kéo dài, lợi sưng đỏ
                        là dấu hiệu thường gặp của viêm lợi, viêm nha chu cần được điều trị sớm.
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Răng lệch, chen chúc, khớp cắn sai</h3>
                    <p class="card-text">
                        Răng mọc lệch, khớp cắn không đều, khó vệ sinh có thể gây sâu răng, viêm nha chu
                        và ảnh hưởng thẩm mỹ – nên được khám để tư vấn chỉnh nha (niềng răng).
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Chấn thương vùng hàm mặt</h3>
                    <p class="card-text">
                        Té ngã, va đập khiến răng gãy, lung lay, môi hoặc má rách, đau vùng khớp thái dương hàm…
                        là tình huống cần được khám sớm để hạn chế biến chứng (minh họa).
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Mất răng, khó ăn nhai</h3>
                    <p class="card-text">
                        Thiếu răng làm ảnh hưởng khả năng ăn nhai, tiêu hóa và thẩm mỹ gương mặt,
                        người bệnh nên được tư vấn phục hình răng (cầu răng, hàm giả, implant – mô phỏng).
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Khám răng miệng định kỳ</h3>
                    <p class="card-text">
                        Mọi người, đặc biệt là trẻ em và người có nhiều mảng bám, hút thuốc lá
                        nên khám và cạo vôi răng định kỳ để phòng sâu răng, viêm nha chu.
                    </p>
                </div>
            </div>
        </section>

        <!-- 2. Dịch vụ & kỹ thuật tại Khoa Răng hàm mặt -->
        <section class="page-section">
            <h2 class="section-title">Dịch vụ và kỹ thuật tại Khoa Răng hàm mặt</h2>
            <p class="section-desc">
                Một số dịch vụ minh họa trong đồ án ở lĩnh vực Răng hàm mặt (không phải danh sách đầy đủ):
            </p>

            <div class="grid grid-2 grid-3-lg">
                <div class="card">
                    <h3 class="card-title">Khám và điều trị sâu răng</h3>
                    <p class="card-text">
                        Khám phát hiện sớm sâu răng, điều trị trám răng, xử trí viêm tủy răng,
                        tư vấn vệ sinh răng miệng đúng cách (minh họa).
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Điều trị nha chu, cạo vôi răng</h3>
                    <p class="card-text">
                        Cạo vôi, xử lý túi nha chu, hướng dẫn chăm sóc lợi và răng cho người bệnh
                        có bệnh lý nha chu mạn tính hoặc hút thuốc lá, đái tháo đường…
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Nhổ răng khôn & tiểu phẫu răng miệng</h3>
                    <p class="card-text">
                        Nhổ răng khôn mọc lệch, chôn ngầm, các thủ thuật răng miệng đơn giản,
                        tư vấn trước và sau phẫu thuật (nội dung minh họa).
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Chỉnh nha (niềng răng)</h3>
                    <p class="card-text">
                        Khám và lập kế hoạch chỉnh nha cho răng lệch lạc, chen chúc hoặc khớp cắn sai;
                        theo dõi lâu dài trong suốt quá trình điều trị (mô phỏng).
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Phục hình răng</h3>
                    <p class="card-text">
                        Tư vấn các phương án phục hình như mão răng, cầu răng, hàm tháo lắp
                        hoặc cấy ghép implant (minh họa cho đồ án).
                    </p>
                </div>
                <div class="card">
                    <h3 class="card-title">Nha khoa trẻ em</h3>
                    <p class="card-text">
                        Khám răng sữa, răng hỗn hợp, hướng dẫn chải răng cho trẻ,
                        phòng ngừa sâu răng sữa và phát hiện sớm bất thường mọc răng (minh họa).
                    </p>
                </div>
            </div>
        </section>

        <!-- 3. Quy trình khám tại Khoa Răng hàm mặt -->
        <section class="page-section">
            <h2 class="section-title">Quy trình khám tại Khoa Răng hàm mặt</h2>
            <div class="card">
                <ol class="list-steps">
                    <li>
                        <span class="step-label">Bước 1 – Đăng ký khám:</span>
                        Đến quầy tiếp nhận hoặc đặt lịch trực tuyến, chọn “Khoa Răng hàm mặt”
                        và ghi rõ lý do khám (đau răng, cạo vôi răng, chỉnh nha…).
                    </li>
                    <li>
                        <span class="step-label">Bước 2 – Khai thác bệnh sử:</span>
                        Bác sĩ hỏi về thời gian xuất hiện triệu chứng, thói quen chải răng,
                        chế độ ăn uống, hút thuốc lá, bệnh nền liên quan…
                    </li>
                    <li>
                        <span class="step-label">Bước 3 – Khám lâm sàng & chụp phim (nếu cần):</span>
                        Kiểm tra răng, lợi, xương ổ răng; chụp X-quang răng hoặc phim toàn cảnh (minh họa)
                        để đánh giá tổn thương ẩn và lập kế hoạch điều trị.
                    </li>
                    <li>
                        <span class="step-label">Bước 4 – Tư vấn & thực hiện điều trị:</span>
                        Bác sĩ giải thích tình trạng răng miệng, đưa ra các lựa chọn điều trị,
                        tiến hành thủ thuật (nếu phù hợp) và hướng dẫn chăm sóc sau điều trị.
                    </li>
                    <li>
                        <span class="step-label">Bước 5 – Hẹn tái khám & chăm sóc định kỳ:</span>
                        Lên lịch tái khám, cạo vôi răng định kỳ, theo dõi chỉnh nha hoặc phục hình
                        để duy trì kết quả lâu dài.
                    </li>
                </ol>
            </div>
        </section>

        <!-- 4. Đội ngũ bác sĩ (minh họa) -->
        <section class="page-section">
            <h2 class="section-title">Đội ngũ bác sĩ (minh họa)</h2>
            <p class="section-desc">
                Thông tin bên dưới chỉ phục vụ mục đích mô phỏng cho đồ án, không phải danh sách bác sĩ thật.
            </p>

            <div class="grid grid-2 grid-3-lg">
                <div class="card">
                    <div class="doctor-avatar" style="background: radial-gradient(circle at top, #fb923c, #b45309);">
                        RM
                    </div>
                    <h3 class="doctor-name">BSCKII. Trần Quốc M</h3>
                    <p class="doctor-position">Trưởng khoa Răng hàm mặt</p>
                    <p class="doctor-desc">
                        Kinh nghiệm trong điều trị các bệnh lý răng miệng phức tạp,
                        phục hình và phẫu thuật Răng hàm mặt (nội dung minh họa cho đồ án).
                    </p>
                </div>
                <div class="card">
                    <div class="doctor-avatar" style="background: radial-gradient(circle at top, #22c55e, #15803d);">
                        CN
                    </div>
                    <h3 class="doctor-name">ThS. BS. Lê Bảo N</h3>
                    <p class="doctor-position">Bác sĩ Chỉnh nha</p>
                    <p class="doctor-desc">
                        Tập trung vào các ca chỉnh nha cho thanh thiếu niên và người lớn,
                        quan tâm đến thẩm mỹ nụ cười và cân đối khuôn mặt (minh họa).
                    </p>
                </div>
                <div class="card">
                    <div class="doctor-avatar" style="background: radial-gradient(circle at top, #60a5fa, #1d4ed8);">
                        TE
                    </div>
                    <h3 class="doctor-name">BS. Nguyễn Hồng E</h3>
                    <p class="doctor-position">Bác sĩ Nha khoa trẻ em (minh họa)</p>
                    <p class="doctor-desc">
                        Khám và điều trị sâu răng sữa, tư vấn dinh dưỡng và vệ sinh răng miệng cho trẻ,
                        tạo môi trường khám thân thiện giúp trẻ hợp tác tốt hơn.
                    </p>
                </div>
            </div>
        </section>

        <!-- 5. Cơ sở vật chất – Thiết bị (minh họa) -->
        <section class="page-section">
            <h2 class="section-title">Cơ sở vật chất – Thiết bị (minh họa)</h2>
            <div class="card">
                <p class="card-text">
                    Trong đồ án, Khoa Răng hàm mặt được mô phỏng với các khu vực chức năng và thiết bị cơ bản
                    để xây dựng luồng nghiệp vụ và giao diện website:
                </p>
                <ul class="facilities-list">
                    <li>Ghế khám răng có đèn chiếu và hệ thống hút – xịt nước (minh họa).</li>
                    <li>Khu vực chụp X-quang răng, phim toàn cảnh hàm mặt (mô tả trong website).</li>
                    <li>Phòng tiểu phẫu răng khôn, nhổ răng và xử trí chấn thương răng miệng.</li>
                    <li>Khu vực vô trùng dụng cụ, chuẩn bị vật liệu trám, phục hình (minh họa).</li>
                </ul>
                <p class="note-small">
                    * Tất cả thông tin trên chỉ phục vụ cho mục đích minh họa trong đồ án,
                    không phản ánh chính xác điều kiện cơ sở vật chất của một bệnh viện cụ thể.
                </p>
            </div>
        </section>

        <!-- 6. Câu hỏi thường gặp -->
        <section class="page-section">
            <h2 class="section-title">Câu hỏi thường gặp</h2>

            <div class="grid">
                <details class="faq-item">
                    <summary class="faq-summary">
                        <span>Tôi nên cạo vôi răng định kỳ bao lâu một lần?</span>
                        <span class="faq-arrow">▼</span>
                    </summary>
                    <div class="faq-body">
                        Thông thường, có thể cân nhắc cạo vôi răng 6–12 tháng/lần tùy theo tình trạng mảng bám,
                        thói quen vệ sinh răng miệng và hướng dẫn của bác sĩ (nội dung minh họa).
                    </div>
                </details>

                <details class="faq-item">
                    <summary class="faq-summary">
                        <span>Nhổ răng khôn có nguy hiểm không?</span>
                        <span class="faq-arrow">▼</span>
                    </summary>
                    <div class="faq-body">
                        Nhổ răng khôn cần được khám và đánh giá phim X-quang trước. Mức độ khó dễ tùy vị trí răng
                        và cấu trúc xung quanh. Người bệnh nên trao đổi trực tiếp với bác sĩ để được tư vấn cụ thể
                        cho từng trường hợp (minh họa, không thay thế tư vấn y khoa).
                    </div>
                </details>

                <details class="faq-item">
                    <summary class="faq-summary">
                        <span>Có cần khám răng khi chưa thấy đau không?</span>
                        <span class="faq-arrow">▼</span>
                    </summary>
                    <div class="faq-body">
                        Nhiều tổn thương răng miệng giai đoạn đầu có thể không gây đau. Khám răng định kỳ giúp
                        phát hiện sớm sâu răng, viêm lợi và các vấn đề khác, tránh phải điều trị phức tạp về sau.
                    </div>
                </details>
            </div>
        </section>

        <!-- 7. Liên hệ – Đặt lịch khám Răng hàm mặt -->
        <section id="dat-lich" class="page-section">
            <div class="contact-section">
                <div class="contact-left">
                    <h2 class="contact-left-title">Cần tư vấn hoặc đặt lịch khám Răng hàm mặt?</h2>
                    <p class="contact-left-text">
                        Người bệnh có thể liên hệ tổng đài hoặc đặt lịch khám trực tuyến để được tư vấn thời gian phù hợp.
                        Nếu có chấn thương vùng hàm mặt, đau răng dữ dội, sưng mặt lan rộng hoặc sốt cao,
                        hãy đến khoa Cấp cứu hoặc Khoa Răng hàm mặt gần nhất.
                    </p>
                    <p class="contact-left-note">
                        Thông tin trên website chỉ mang tính minh họa cho đồ án, không dùng để tự chẩn đoán
                        hoặc tự điều trị. Luôn tuân thủ hướng dẫn của bác sĩ chuyên khoa khi thăm khám thực tế.
                    </p>
                </div>
                <div class="contact-right">
                    <div>
                        <span class="contact-label">Hotline tư vấn (minh họa)</span>
                        <div class="contact-value-main">1900 0456</div>
                    </div>
                    <div style="margin-top: 6px;">
                        <span class="contact-label">Cấp cứu Răng hàm mặt</span>
                        <div class="contact-value-sub">115 (hoặc số cấp cứu địa phương)</div>
                    </div>
                    <div class="contact-btn-wrapper">
                        <button type="button" class="btn-contact">
                            Đặt lịch khám Răng hàm mặt (minh họa)
                        </button>
                    </div>
                </div>
            </div>
        </section>

    </div>
</main>

</body>
</html>
