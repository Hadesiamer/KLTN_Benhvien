<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Về Chúng Tôi - Bệnh Viện</title>

    <!-- Font -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        /* ============================
           RESET CƠ BẢN
        ============================ */
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background-color: #f9fafb; /* giống bg-gray-50 */
            color: #111827;
        }

        a {
            text-decoration: none;
        }

        /* ============================
           LAYOUT CHUNG
        ============================ */

        .pgabout_container_a9 {
            max-width: 1120px; /* tương đương max-w-7xl */
            margin: 0 auto;
            padding: 32px 24px;
        }

        .pgabout_section_a9 {
            margin-bottom: 40px;
        }

        .pgabout_text_center_a9 {
            text-align: center;
        }

        .pgabout_section_title_a9 {
            font-size: 28px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 8px;
        }

        .pgabout_section_subtitle_a9 {
            font-size: 18px;
            color: #4b5563;
        }

        /* ============================
           THANH THÔNG BÁO (LƯU Ý QUAN TRỌNG)
        ============================ */

        .pgabout_notice_wrap_a9 {
            background-color: #fffbeb; /* amber-50 */
            border-left: 4px solid #f59e0b; /* amber-500 */
            padding: 12px 24px;
            margin-bottom: 24px;
        }

        .pgabout_notice_inner_a9 {
            max-width: 1120px;
            margin: 0 auto;
        }

        .pgabout_notice_content_a9 {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .pgabout_notice_icon_a9 svg {
            width: 20px;
            height: 20px;
            color: #b45309; /* amber-700/800 */
            flex-shrink: 0;
            margin-top: 2px;
        }

        .pgabout_notice_title_a9 {
            font-size: 15px;
            font-weight: 600;
            color: #78350f; /* amber-900 */
            margin: 0 0 4px;
        }

        .pgabout_notice_text_a9 {
            font-size: 13px;
            line-height: 1.5;
            color: #854d0e; /* amber-800 */
            margin: 0;
        }

        /* ============================
           HERO GIỚI THIỆU (GRADIENT)
        ============================ */

        .pgabout_hero_a9 {
            background: linear-gradient(135deg, #0f4c81 0%, #1e3a5f 100%);
            border-radius: 16px;
            padding: 32px 24px;
            color: #ffffff;
            margin-bottom: 32px;
        }

        .pgabout_hero_inner_a9 {
            max-width: 640px;
        }

        .pgabout_hero_title_a9 {
            font-size: 32px;
            font-weight: 700;
            margin: 0 0 8px;
        }

        .pgabout_hero_desc_a9 {
            font-size: 18px;
            color: #bfdbfe; /* text-blue-100 */
            line-height: 1.6;
            margin: 0;
        }

        /* ============================
           GRID CHUNG (CARD)
        ============================ */

        .pgabout_grid_a9 {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .pgabout_grid_2col_md_a9 {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .pgabout_grid_3col_md_a9 {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
        }

        @media (min-width: 768px) {
            .pgabout_grid_2col_md_a9 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .pgabout_grid_3col_md_a9 {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        .pgabout_card_a9 {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 24px;
            border: 1px solid #f3f4f6;
            box-shadow: 0 1px 2px rgba(0,0,0,0.03);
        }

        .pgabout_card_a9:hover {
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.08);
        }

        .pgabout_card_title_a9 {
            font-size: 20px;
            font-weight: 700;
            color: #111827;
            margin: 0 0 8px;
        }

        .pgabout_card_text_a9 {
            font-size: 14px;
            color: #4b5563;
            line-height: 1.6;
            margin: 0;
        }

        /* ============================
           ICON WRAPPER
        ============================ */

        .pgabout_icon_box_lg_blue_a9,
        .pgabout_icon_box_lg_teal_a9,
        .pgabout_icon_box_md_red_a9,
        .pgabout_icon_box_md_blue_a9,
        .pgabout_icon_box_md_purple_a9,
        .pgabout_icon_box_md_green_a9,
        .pgabout_icon_box_md_teal_a9,
        .pgabout_icon_box_lg_blue2_a9,
        .pgabout_icon_box_lg_purple2_a9,
        .pgabout_icon_box_lg_green2_a9,
        .pgabout_icon_box_md_blue2_a9,
        .pgabout_icon_box_md_blue3_a9,
        .pgabout_icon_box_md_blue4_a9,
        .pgabout_icon_box_md_blue5_a9 {
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            margin-bottom: 12px;
        }

        .pgabout_icon_box_lg_blue_a9 {
            width: 48px;
            height: 48px;
            background-color: #dbeafe; /* blue-100 */
        }

        .pgabout_icon_box_lg_teal_a9 {
            width: 48px;
            height: 48px;
            background-color: #ccfbf1; /* teal-100 */
        }

        .pgabout_icon_box_md_red_a9 {
            width: 44px;
            height: 44px;
            background-color: #fee2e2; /* red-100 */
        }

        .pgabout_icon_box_md_blue_a9 {
            width: 44px;
            height: 44px;
            background-color: #dbeafe;
        }

        .pgabout_icon_box_md_purple_a9 {
            width: 44px;
            height: 44px;
            background-color: #efe9ff; /* gần purple-100 */
        }

        .pgabout_icon_box_md_green_a9 {
            width: 44px;
            height: 44px;
            background-color: #dcfce7; /* green-100 */
        }

        .pgabout_icon_box_md_teal_a9 {
            width: 44px;
            height: 44px;
            background-color: #ccfbf1;
        }

        .pgabout_icon_box_lg_blue2_a9 {
            width: 48px;
            height: 48px;
            background-color: #dbeafe;
        }

        .pgabout_icon_box_lg_purple2_a9 {
            width: 48px;
            height: 48px;
            background-color: #f3e8ff;
        }

        .pgabout_icon_box_lg_green2_a9 {
            width: 48px;
            height: 48px;
            background-color: #dcfce7;
        }

        .pgabout_icon_box_md_blue2_a9,
        .pgabout_icon_box_md_blue3_a9,
        .pgabout_icon_box_md_blue4_a9,
        .pgabout_icon_box_md_blue5_a9 {
            width: 36px;
            height: 36px;
            background-color: #dbeafe;
            margin-right: 12px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 4px;
        }

        .pgabout_icon_round_big_blue_a9 {
            width: 56px;
            height: 56px;
            border-radius: 999px;
            background-color: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
        }

        .pgabout_icon_round_big_blue_a9 svg,
        .pgabout_icon_box_lg_blue_a9 svg,
        .pgabout_icon_box_lg_teal_a9 svg,
        .pgabout_icon_box_md_red_a9 svg,
        .pgabout_icon_box_md_blue_a9 svg,
        .pgabout_icon_box_md_purple_a9 svg,
        .pgabout_icon_box_md_green_a9 svg,
        .pgabout_icon_box_md_teal_a9 svg,
        .pgabout_icon_box_lg_blue2_a9 svg,
        .pgabout_icon_box_lg_purple2_a9 svg,
        .pgabout_icon_box_lg_green2_a9 svg,
        .pgabout_icon_box_md_blue2_a9 svg,
        .pgabout_icon_box_md_blue3_a9 svg,
        .pgabout_icon_box_md_blue4_a9 svg,
        .pgabout_icon_box_md_blue5_a9 svg {
            width: 20px;
            height: 20px;
        }

        .pgabout_icon_box_lg_blue_a9 svg,
        .pgabout_icon_box_md_blue_a9 svg,
        .pgabout_icon_box_lg_blue2_a9 svg,
        .pgabout_icon_box_md_blue2_a9 svg,
        .pgabout_icon_box_md_blue3_a9 svg,
        .pgabout_icon_box_md_blue4_a9 svg,
        .pgabout_icon_box_md_blue5_a9 svg {
            color: #2563eb;
        }

        .pgabout_icon_box_lg_teal_a9 svg,
        .pgabout_icon_box_md_teal_a9 svg {
            color: #0f766e;
        }

        .pgabout_icon_box_md_red_a9 svg {
            color: #dc2626;
        }

        .pgabout_icon_box_md_purple_a9 svg,
        .pgabout_icon_box_lg_purple2_a9 svg {
            color: #7c3aed;
        }

        .pgabout_icon_box_md_green_a9 svg,
        .pgabout_icon_box_lg_green2_a9 svg {
            color: #16a34a;
        }

        .pgabout_icon_round_big_blue_a9 svg {
            color: #ffffff;
            width: 28px;
            height: 28px;
        }

        /* ============================
           CARD NHỎ (VALUE / SERVICES / CONTACT)
        ============================ */

        .pgabout_value_card_a9 {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #f3f4f6;
        }

        .pgabout_value_title_a9 {
            font-size: 16px;
            font-weight: 600;
            color: #111827;
            margin: 0 0 6px;
        }

        .pgabout_value_text_a9 {
            font-size: 13px;
            color: #4b5563;
            line-height: 1.6;
            margin: 0;
        }

        .pgabout_flex_row_a9 {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .pgabout_contact_card_a9 {
            background-color: #111827;
            color: #ffffff;
            border-radius: 16px;
            padding: 32px 24px;
        }

        .pgabout_contact_text_small_a9 {
            font-size: 13px;
            color: #d1d5db;
            margin: 0;
        }

        .pgabout_contact_label_a9 {
            font-size: 15px;
            font-weight: 600;
            margin: 0 0 4px;
        }

        .pgabout_contact_grid_a9 {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
            max-width: 960px;
            margin: 0 auto;
        }

        @media (min-width: 768px) {
            .pgabout_contact_grid_a9 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (min-width: 1024px) {
            .pgabout_contact_grid_a9 {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        .pgabout_contact_icon_box_a9 {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background-color: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .pgabout_contact_icon_box_a9 svg {
            width: 20px;
            height: 20px;
            color: #ffffff;
        }

        .pgabout_contact_subtext_a9 {
            font-size: 13px;
            color: #e5e7eb;
            margin: 0;
        }

        /* ============================
           BLOCK BACKGROUND GRADIENT NHỎ
        ============================ */

        .pgabout_gradient_block_a9 {
            background: linear-gradient(135deg, #eff6ff 0%, #e0f2fe 50%, #ecfeff 100%);
            border-radius: 16px;
            padding: 32px 24px;
        }

        .pgabout_gradient_block_inner_a9 {
            max-width: 640px;
            margin: 0 auto;
            text-align: center;
        }

        .pgabout_gradient_block_title_a9 {
            font-size: 28px;
            font-weight: 700;
            color: #111827;
            margin: 0 0 12px;
        }

        .pgabout_gradient_block_p_a9 {
            font-size: 17px;
            color: #374151;
            line-height: 1.6;
            margin: 0 0 8px;
        }

        .pgabout_gradient_block_p_bold_a9 {
            font-size: 15px;
            color: #1d4ed8;
            font-weight: 600;
            margin: 0;
        }

        .pgabout_gradient_block2_a9 {
            background: linear-gradient(90deg, #2563eb 0%, #0d9488 100%);
            border-radius: 16px;
            padding: 32px 24px;
            color: #ffffff;
        }

        .pgabout_gradient_block2_inner_a9 {
            max-width: 640px;
            margin: 0 auto;
            text-align: center;
        }

        .pgabout_gradient_block2_title_a9 {
            font-size: 28px;
            font-weight: 700;
            margin: 0 0 12px;
        }

        .pgabout_gradient_block2_text_a9 {
            font-size: 17px;
            color: #e0f2fe;
            line-height: 1.6;
            margin: 0;
        }

        /* Dịch vụ nổi bật */
        .pgabout_services_card_a9 {
            background-color: #ffffff;
            border-radius: 16px;
            padding: 32px 24px;
            border: 1px solid #f3f4f6;
            box-shadow: 0 1px 2px rgba(0,0,0,0.03);
        }

        .pgabout_services_grid_a9 {
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
            max-width: 960px;
            margin: 0 auto;
        }

        @media (min-width: 768px) {
            .pgabout_services_grid_a9 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        .pgabout_service_title_a9 {
            font-size: 15px;
            font-weight: 600;
            color: #111827;
            margin: 0 0 4px;
        }

        .pgabout_service_text_a9 {
            font-size: 13px;
            color: #4b5563;
            margin: 0;
        }

        .pgabout_services_section_title_a9 {
            font-size: 28px;
            font-weight: 700;
            color: #111827;
            text-align: center;
            margin: 0 0 24px;
        }

        /* RESPONSIVE TINH CHỈNH NHỎ */
        @media (max-width: 640px) {
            .pgabout_container_a9 {
                padding: 24px 16px;
            }

            .pgabout_hero_title_a9,
            .pgabout_section_title_a9,
            .pgabout_gradient_block_title_a9,
            .pgabout_gradient_block2_title_a9,
            .pgabout_services_section_title_a9 {
                font-size: 24px;
            }
        }

    </style>
</head>
<body>

<?php include "blocks/header.php" ?>

<!-- Lưu ý quan trọng -->
<div class="pgabout_notice_wrap_a9">
    <div class="pgabout_notice_inner_a9">
        <div class="pgabout_notice_content_a9">
            <div class="pgabout_notice_icon_a9">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div>
                <h3 class="pgabout_notice_title_a9">Lưu ý quan trọng</h3>
                <p class="pgabout_notice_text_a9">
                    Đây là đồ án sinh viên, không phải website bệnh viện chính thức. Chúng tôi không chịu bất cứ trách nhiệm nào về sự nhầm lẫn của bạn.
                </p>
            </div>
        </div>
    </div>
</div>

<main class="pgabout_container_a9">

    <!-- Hero giới thiệu -->
    <div class="pgabout_hero_a9">
        <div class="pgabout_hero_inner_a9">
            <h1 class="pgabout_hero_title_a9">Giới thiệu về Bệnh viện Đa khoa Trung ương</h1>
            <p class="pgabout_hero_desc_a9">
                Nơi hội tụ y đức, tâm huyết và công nghệ hiện đại, mang đến dịch vụ chăm sóc sức khỏe toàn diện cho cộng đồng.
            </p>
        </div>
    </div>

    <!-- Tầm nhìn & Sứ mệnh -->
    <section class="pgabout_section_a9">
        <div class="pgabout_grid_2col_md_a9">
            <div class="pgabout_card_a9">
                <div class="pgabout_icon_box_lg_blue_a9">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </div>
                <h2 class="pgabout_card_title_a9">Tầm nhìn</h2>
                <p class="pgabout_card_text_a9">
                    Trở thành một trong những bệnh viện hàng đầu tại Việt Nam, mang đến dịch vụ y tế chất lượng cao, hiện đại và nhân văn, vì sức khỏe và hạnh phúc của cộng đồng.
                </p>
            </div>

            <div class="pgabout_card_a9">
                <div class="pgabout_icon_box_lg_teal_a9">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h2 class="pgabout_card_title_a9">Sứ mệnh</h2>
                <p class="pgabout_card_text_a9">
                    Cung cấp dịch vụ khám chữa bệnh toàn diện, an toàn và hiệu quả; lấy người bệnh làm trung tâm trong mọi hoạt động; không ngừng nghiên cứu, đổi mới và phát triển chuyên môn để phục vụ xã hội tốt hơn mỗi ngày.
                </p>
            </div>
        </div>
    </section>

    <!-- Giá trị cốt lõi -->
    <section class="pgabout_section_a9">
        <div class="pgabout_text_center_a9" style="margin-bottom: 24px;">
            <h2 class="pgabout_section_title_a9">Giá trị cốt lõi</h2>
            <p class="pgabout_section_subtitle_a9">Những nguyên tắc định hướng mọi hoạt động của chúng tôi</p>
        </div>

        <div class="pgabout_grid_a9 pgabout_grid_3col_md_a9">
            <div class="pgabout_value_card_a9">
                <div class="pgabout_icon_box_md_red_a9">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h3 class="pgabout_value_title_a9">Tận tâm</h3>
                <p class="pgabout_value_text_a9">Luôn đặt lợi ích và sức khỏe người bệnh lên hàng đầu.</p>
            </div>

            <div class="pgabout_value_card_a9">
                <div class="pgabout_icon_box_md_blue_a9">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                    </svg>
                </div>
                <h3 class="pgabout_value_title_a9">Chuyên nghiệp</h3>
                <p class="pgabout_value_text_a9">Đội ngũ y bác sĩ, điều dưỡng và nhân viên được đào tạo bài bản, giàu kinh nghiệm.</p>
            </div>

            <div class="pgabout_value_card_a9">
                <div class="pgabout_icon_box_md_purple_a9">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h3 class="pgabout_value_title_a9">Hiện đại</h3>
                <p class="pgabout_value_text_a9">Ứng dụng công nghệ tiên tiến, trang thiết bị đạt chuẩn quốc tế.</p>
            </div>

            <div class="pgabout_value_card_a9">
                <div class="pgabout_icon_box_md_green_a9">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                    </svg>
                </div>
                <h3 class="pgabout_value_title_a9">Nhân văn</h3>
                <p class="pgabout_value_text_a9">Xây dựng môi trường y tế thân thiện, tôn trọng và sẻ chia.</p>
            </div>

            <div class="pgabout_value_card_a9">
                <div class="pgabout_icon_box_md_teal_a9">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"/>
                        </svg>
                </div>
                <h3 class="pgabout_value_title_a9">Phát triển bền vững</h3>
                <p class="pgabout_value_text_a9">Liên tục cải tiến chất lượng dịch vụ và quy trình điều trị.</p>
            </div>
        </div>
    </section>

    <!-- Đội ngũ chuyên môn -->
    <section class="pgabout_section_a9">
        <div class="pgabout_gradient_block_a9">
            <div class="pgabout_gradient_block_inner_a9">
                <div class="pgabout_icon_round_big_blue_a9">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                    </svg>
                </div>
                <h2 class="pgabout_gradient_block_title_a9">Đội ngũ chuyên môn</h2>
                <p class="pgabout_gradient_block_p_a9">
                    Bệnh viện Đa khoa Trung ương quy tụ đội ngũ giáo sư, tiến sĩ, bác sĩ chuyên khoa đầu ngành trong nhiều lĩnh vực: Nội tổng quát, Ngoại khoa, Sản – Nhi, Tim mạch, Ung bướu, Cấp cứu, Chẩn đoán hình ảnh, và nhiều chuyên khoa khác.
                </p>
                <p class="pgabout_gradient_block_p_bold_a9">
                    Chúng tôi tự hào là nơi hội tụ tri thức – kinh nghiệm – tâm đức của ngành y Việt Nam.
                </p>
            </div>
        </div>
    </section>

    <!-- Cơ sở vật chất hiện đại -->
    <section class="pgabout_section_a9">
        <div class="pgabout_text_center_a9" style="margin-bottom: 24px;">
            <h2 class="pgabout_section_title_a9">Cơ sở vật chất hiện đại</h2>
            <p class="pgabout_section_subtitle_a9">
                Với hệ thống cơ sở vật chất khang trang, môi trường khám chữa bệnh tiện nghi, thân thiện
            </p>
        </div>

        <div class="pgabout_grid_3col_md_a9">
            <div class="pgabout_card_a9">
                <div class="pgabout_icon_box_lg_blue2_a9">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <h3 class="pgabout_value_title_a9">Phòng khám chuẩn quốc tế</h3>
                <p class="pgabout_value_text_a9">Phòng khám và điều trị đạt chuẩn quốc tế với đầy đủ tiện nghi.</p>
            </div>

            <div class="pgabout_card_a9">
                <div class="pgabout_icon_box_lg_purple2_a9">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="pgabout_value_title_a9">Công nghệ cao</h3>
                <p class="pgabout_value_text_a9">Hệ thống xét nghiệm, chẩn đoán hình ảnh, phẫu thuật công nghệ cao.</p>
            </div>

            <div class="pgabout_card_a9">
                <div class="pgabout_icon_box_lg_green2_a9">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </div>
                <h3 class="pgabout_value_title_a9">Phòng bệnh tiện nghi</h3>
                <p class="pgabout_value_text_a9">Phòng bệnh riêng tư, tiện nghi, đảm bảo sự thoải mái cho bệnh nhân.</p>
            </div>
        </div>
    </section>

    <!-- Dịch vụ nổi bật -->
    <section class="pgabout_section_a9">
        <div class="pgabout_services_card_a9">
            <h2 class="pgabout_services_section_title_a9">Dịch vụ nổi bật</h2>

            <div class="pgabout_services_grid_a9">
                <div class="pgabout_flex_row_a9">
                    <div class="pgabout_icon_box_md_blue2_a9">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="pgabout_service_title_a9">Khám bệnh tổng quát và chuyên khoa</h3>
                        <p class="pgabout_service_text_a9">Đa dạng chuyên khoa với đội ngũ bác sĩ giàu kinh nghiệm</p>
                    </div>
                </div>

                <div class="pgabout_flex_row_a9">
                    <div class="pgabout_icon_box_md_blue3_a9">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="pgabout_service_title_a9">Phẫu thuật – điều trị tiên tiến</h3>
                        <p class="pgabout_service_text_a9">Áp dụng phương pháp điều trị hiện đại nhất</p>
                    </div>
                </div>

                <div class="pgabout_flex_row_a9">
                    <div class="pgabout_icon_box_md_blue4_a9">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="pgabout_service_title_a9">Chăm sóc sức khỏe toàn diện</h3>
                        <p class="pgabout_service_text_a9">Dịch vụ chăm sóc từ phòng bệnh đến điều trị</p>
                    </div>
                </div>

                <div class="pgabout_flex_row_a9">
                    <div class="pgabout_icon_box_md_blue5_a9">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="pgabout_service_title_a9">Khám sức khỏe định kỳ</h3>
                        <p class="pgabout_service_text_a9">Gói khám sức khỏe cho cá nhân và doanh nghiệp</p>
                    </div>
                </div>

                <div class="pgabout_flex_row_a9">
                    <div class="pgabout_icon_box_md_blue2_a9">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="pgabout_service_title_a9">Cấp cứu 24/7</h3>
                        <p class="pgabout_service_text_a9">Dịch vụ cấp cứu, vận chuyển và tư vấn trực tuyến</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Hợp tác quốc tế -->
    <section class="pgabout_section_a9">
        <div class="pgabout_gradient_block2_a9">
            <div class="pgabout_gradient_block2_inner_a9">
                <div class="pgabout_icon_round_big_blue_a9" style="background-color: rgba(255,255,255,0.2); backdrop-filter: blur(2px);">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h2 class="pgabout_gradient_block2_title_a9">Hợp tác quốc tế</h2>
                <p class="pgabout_gradient_block2_text_a9">
                    Bệnh viện Đa khoa Trung ương hợp tác với nhiều tổ chức y tế, trường đại học và bệnh viện uy tín trong và ngoài nước nhằm trao đổi chuyên môn, đào tạo nhân lực và chuyển giao công nghệ y khoa hiện đại.
                </p>
            </div>
        </div>
    </section>

    <!-- Thông tin liên hệ -->
    <section class="pgabout_section_a9">
        <div class="pgabout_contact_card_a9">
            <h2 class="pgabout_section_title_a9 pgabout_text_center_a9">Thông tin liên hệ</h2>

            <div class="pgabout_contact_grid_a9">
                <div class="pgabout_flex_row_a9">
                    <div class="pgabout_contact_icon_box_a9">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="pgabout_contact_label_a9">Địa chỉ</h3>
                        <p class="pgabout_contact_text_small_a9">123 Đường Lê Lợi, Phường Bến Nghé, Quận 1, TP. Hồ Chí Minh</p>
                    </div>
                </div>

                <div class="pgabout_flex_row_a9">
                    <div class="pgabout_contact_icon_box_a9">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="pgabout_contact_label_a9">Hotline</h3>
                        <p class="pgabout_contact_text_small_a9">1900 1234</p>
                        <p class="pgabout_contact_text_small_a9">(028) 3822 5678</p>
                    </div>
                </div>

                <div class="pgabout_flex_row_a9">
                    <div class="pgabout_contact_icon_box_a9">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="pgabout_contact_label_a9">Email</h3>
                        <p class="pgabout_contact_text_small_a9">info@benhvien.vn</p>
                        <p class="pgabout_contact_text_small_a9">cskh@benhvien.vn</p>
                    </div>
                </div>

                <div class="pgabout_flex_row_a9">
                    <div class="pgabout_contact_icon_box_a9">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="pgabout_contact_label_a9">Website</h3>
                        <p class="pgabout_contact_text_small_a9">www.benhvien.vn</p>
                    </div>
                </div>

                <div class="pgabout_flex_row_a9">
                    <div class="pgabout_contact_icon_box_a9">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="pgabout_contact_label_a9">Giờ làm việc</h3>
                        <p class="pgabout_contact_text_small_a9">24/7 - Cấp cứu</p>
                        <p class="pgabout_contact_text_small_a9">7:00 - 17:00 - Khám bệnh</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

</main>

<?php include "blocks/footer.php" ?>

</body>
</html>
