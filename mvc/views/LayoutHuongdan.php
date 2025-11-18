<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hướng Dẫn - Bệnh Viện</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6; /* nền xám nhẹ */
            color: #111827;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* --------- Khung chung ---------- */
        .guid_main_container_x1 {
            max-width: 1200px;
            margin: 0 auto;
            padding: 24px 16px 48px;
        }

        .guid_section_x1 {
            margin-bottom: 40px;
        }

        .guid_section_title_x1 {
            font-size: 26px;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 16px;
        }

        .guid_text_center_x1 {
            text-align: center;
        }

        /* --------- Alert “Lưu ý quan trọng” ---------- */
        .guid_notice_wrap_x1 {
            background-color: #fffbeb; /* amber-50 */
            border-left: 4px solid #f59e0b; /* amber-500 */
            padding: 12px 16px;
        }

        .guid_notice_inner_x1 {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            gap: 10px;
            align-items: flex-start;
        }

        .guid_notice_icon_x1 svg {
            width: 20px;
            height: 20px;
            color: #d97706;
            margin-top: 2px;
            flex-shrink: 0;
        }

        .guid_notice_title_x1 {
            font-size: 15px;
            font-weight: 600;
            color: #78350f;
            margin: 0 0 4px;
        }

        .guid_notice_text_x1 {
            font-size: 13px;
            color: #854d0e;
            line-height: 1.5;
            margin: 0;
        }

        /* --------- Hero đầu trang ---------- */
        .guid_hero_x1 {
            background: radial-gradient(circle at top left, #38bdf8 0%, #0ea5e9 35%, #0f766e 100%);
            color: #ffffff;
            padding: 40px 16px;
            margin-top: 16px;
        }

        .guid_hero_inner_x1 {
            max-width: 1200px;
            margin: 0 auto;
        }

        .guid_hero_title_x1 {
            font-size: 32px;
            font-weight: 700;
            margin: 0 0 8px;
        }

        .guid_hero_text_x1 {
            font-size: 18px;
            color: #e0f2fe;
            margin: 0;
        }

        /* --------- Grid chung ---------- */
        .guid_grid_x1 {
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .guid_grid_2col_md_x1 {
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .guid_grid_3col_md_x1 {
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .guid_grid_4col_md_x1 {
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
        }

        @media (min-width: 768px) {
            .guid_grid_2col_md_x1 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
            .guid_grid_3col_md_x1 {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
            .guid_grid_4col_md_x1 {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
        }

        /* --------- Card chung ---------- */
        .guid_card_x1 {
            background-color: #ffffff;
            border-radius: 14px;
            padding: 20px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
        }

        .guid_card_x1:hover {
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.12);
            transform: translateY(-1px);
            transition: all 0.18s ease-out;
        }

        .guid_card_title_x1 {
            font-size: 17px;
            font-weight: 600;
            color: #111827;
            margin: 0 0 6px;
        }

        .guid_card_text_x1 {
            font-size: 13px;
            color: #4b5563;
            line-height: 1.6;
            margin: 0;
        }

        /* --------- Quy trình khám bệnh ---------- */
        .guid_steps_card_x1 {
            background-color: #ffffff;
            border-radius: 16px;
            padding: 20px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
        }

        .guid_step_number_x1 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 8px;
            background: linear-gradient(135deg, #0ea5e9 0%, #06b6d4 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .guid_step_title_x1 {
            font-size: 16px;
            font-weight: 600;
            color: #111827;
            margin: 0 0 4px;
        }

        .guid_step_text_x1 {
            font-size: 13px;
            color: #4b5563;
            line-height: 1.6;
            margin: 0;
        }

        /* --------- Icon box ---------- */
        .guid_icon_box_blue_x1,
        .guid_icon_box_green_x1,
        .guid_icon_box_purple_x1,
        .guid_icon_box_blue2_x1,
        .guid_icon_box_green2_x1,
        .guid_icon_circle_orange_x1,
        .guid_icon_circle_red_x1,
        .guid_icon_circle_teal_x1,
        .guid_icon_circle_indigo_x1 {
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
        }

        .guid_icon_box_blue_x1 {
            background-color: #dbeafe;
            padding: 10px;
            flex-shrink: 0;
        }

        .guid_icon_box_green_x1 {
            background-color: #dcfce7;
            padding: 10px;
            flex-shrink: 0;
        }

        .guid_icon_box_purple_x1 {
            background-color: #f3e8ff;
            padding: 10px;
            width: 48px;
            height: 48px;
            flex-shrink: 0;
        }

        .guid_icon_box_blue2_x1 {
            background-color: #dbeafe;
            padding: 10px;
            width: 48px;
            height: 48px;
            flex-shrink: 0;
        }

        .guid_icon_box_green2_x1 {
            background-color: #dcfce7;
            padding: 10px;
            width: 48px;
            height: 48px;
            flex-shrink: 0;
        }

        .guid_icon_circle_orange_x1,
        .guid_icon_circle_red_x1,
        .guid_icon_circle_teal_x1,
        .guid_icon_circle_indigo_x1 {
            width: 56px;
            height: 56px;
            border-radius: 999px;
            margin: 0 auto 10px;
        }

        .guid_icon_circle_orange_x1 {
            background-color: #ffedd5;
        }

        .guid_icon_circle_red_x1 {
            background-color: #fee2e2;
        }

        .guid_icon_circle_teal_x1 {
            background-color: #ccfbf1;
        }

        .guid_icon_circle_indigo_x1 {
            background-color: #e0e7ff;
        }

        .guid_icon_box_blue_x1 svg,
        .guid_icon_box_green_x1 svg,
        .guid_icon_box_purple_x1 svg,
        .guid_icon_box_green2_x1 svg,
        .guid_icon_box_blue2_x1 svg,
        .guid_icon_circle_orange_x1 svg,
        .guid_icon_circle_red_x1 svg,
        .guid_icon_circle_teal_x1 svg,
        .guid_icon_circle_indigo_x1 svg {
            width: 22px;
            height: 22px;
        }

        .guid_icon_box_blue_x1 svg,
        .guid_icon_box_blue2_x1 svg {
            color: #2563eb;
        }

        .guid_icon_box_green_x1 svg,
        .guid_icon_box_green2_x1 svg {
            color: #16a34a;
        }

        .guid_icon_box_purple_x1 svg {
            color: #7c3aed;
        }

        .guid_icon_circle_orange_x1 svg {
            color: #ea580c;
        }

        .guid_icon_circle_red_x1 svg {
            color: #dc2626;
        }

        .guid_icon_circle_teal_x1 svg {
            color: #0d9488;
        }

        .guid_icon_circle_indigo_x1 svg {
            color: #4f46e5;
        }

        /* --------- Flex hàng ---------- */
        .guid_flex_row_x1 {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .guid_flex_between_x1 {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .guid_time_label_x1 {
            color: #4b5563;
            font-size: 13px;
        }

        .guid_time_value_x1 {
            font-weight: 600;
            font-size: 13px;
            color: #111827;
        }

        .guid_time_value_red_x1 {
            color: #dc2626;
        }

        .guid_divider_top_x1 {
            border-top: 1px solid #e5e7eb;
            padding-top: 8px;
            margin-top: 8px;
        }

        .guid_badge_note_x1 {
            background-color: #eff6ff;
            border-radius: 10px;
            padding: 8px 10px;
            font-size: 12px;
            color: #1d4ed8;
        }

        /* --------- List tick / cross ---------- */
        .guid_list_x1 {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .guid_list_item_x1 {
            display: flex;
            align-items: flex-start;
            gap: 6px;
            font-size: 13px;
            color: #4b5563;
        }

        .guid_list_icon_green_x1 {
            color: #16a34a;
            font-weight: 700;
            margin-top: 1px;
        }

        .guid_list_icon_red_x1 {
            color: #dc2626;
            font-weight: 700;
            margin-top: 1px;
        }

        /* --------- Thanh toán & bảo hiểm ---------- */
        .guid_list_dotted_x1 {
            list-style: none;
            padding: 0;
            margin: 0;
            font-size: 13px;
            color: #4b5563;
        }

        .guid_list_dotted_x1 li::before {
            content: "• ";
        }

        /* --------- Bãi xe & đi lại ---------- */
        .guid_label_x1 {
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            width: 90px;
            flex-shrink: 0;
        }

        .guid_value_x1 {
            font-size: 13px;
            color: #4b5563;
        }

        .guid_tip_box_blue_x1 {
            background-color: #eff6ff;
            border-radius: 10px;
            padding: 8px 10px;
            font-size: 12px;
            color: #1d4ed8;
            margin-top: 6px;
        }

        .guid_tip_box_green_x1 {
            background-color: #ecfdf3;
            border-radius: 10px;
            padding: 8px 10px;
            font-size: 12px;
            color: #166534;
            margin-top: 6px;
        }

        /* --------- Liên hệ khẩn cấp ---------- */
        .guid_emergency_wrap_x1 {
            background: linear-gradient(90deg, #fef2f2 0%, #fffbeb 100%);
            border: 1px solid #fecaca;
            border-radius: 16px;
            padding: 20px;
        }

        .guid_emergency_item_x1 {
            text-align: center;
        }

        .guid_emergency_circle_red_x1,
        .guid_emergency_circle_blue_x1,
        .guid_emergency_circle_green_x1 {
            width: 56px;
            height: 56px;
            border-radius: 999px;
            margin: 0 auto 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
        }

        .guid_emergency_circle_red_x1 {
            background-color: #dc2626;
        }

        .guid_emergency_circle_blue_x1 {
            background-color: #2563eb;
        }

        .guid_emergency_circle_green_x1 {
            background-color: #16a34a;
        }

        .guid_emergency_circle_red_x1 svg,
        .guid_emergency_circle_blue_x1 svg,
        .guid_emergency_circle_green_x1 svg {
            width: 26px;
            height: 26px;
        }

        .guid_emergency_title_x1 {
            font-size: 16px;
            font-weight: 700;
            color: #111827;
            margin: 0 0 4px;
        }

        .guid_emergency_main_x1 {
            font-size: 22px;
            font-weight: 700;
            margin: 0;
        }

        .guid_emergency_main_red_x1 {
            color: #b91c1c;
        }

        .guid_emergency_main_blue_x1 {
            color: #1d4ed8;
        }

        .guid_emergency_main_green_x1 {
            color: #15803d;
        }

        .guid_emergency_sub_x1 {
            font-size: 12px;
            color: #4b5563;
            margin: 4px 0 0;
        }

        /* --------- FAQ ---------- */
        .guid_faq_item_x1 {
            background-color: #ffffff;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            overflow: hidden;
        }

        .guid_faq_item_x1 summary {
            list-style: none;
            cursor: pointer;
            padding: 12px 16px;
            font-weight: 600;
            font-size: 14px;
            color: #111827;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .guid_faq_item_x1[open] summary {
            background-color: #f3f4f6;
        }

        .guid_faq_item_x1 summary::-webkit-details-marker {
            display: none;
        }

        .guid_faq_item_x1 svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
            margin-left: 8px;
            color: #6b7280;
        }

        .guid_faq_body_x1 {
            padding: 0 16px 12px 16px;
            font-size: 13px;
            color: #4b5563;
            line-height: 1.6;
        }

        /* --------- CTA cuối trang ---------- */
        .guid_cta_footer_x1 {
            background: linear-gradient(90deg, #2563eb 0%, #06b6d4 100%);
            color: #ffffff;
            padding: 32px 16px;
            margin-top: 32px;
        }

        .guid_cta_inner_x1 {
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
        }

        .guid_cta_title_x1 {
            font-size: 22px;
            font-weight: 700;
            margin: 0 0 8px;
        }

        .guid_cta_text_x1 {
            font-size: 14px;
            color: #dbeafe;
            margin: 0 0 16px;
        }

        .guid_cta_actions_x1 {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 12px;
        }

        .guid_btn_primary_x1,
        .guid_btn_outline_x1 {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 9px 18px;
            border-radius: 999px;
            font-size: 14px;
            font-weight: 600;
            border: 1px solid transparent;
            cursor: pointer;
            transition: all 0.15s ease-out;
        }

        .guid_btn_primary_x1 {
            background-color: #ffffff;
            color: #2563eb;
        }

        .guid_btn_primary_x1:hover {
            background-color: #eff6ff;
        }

        .guid_btn_outline_x1 {
            border-color: rgba(255,255,255,0.7);
            color: #ffffff;
            background-color: rgba(15,23,42,0.1);
        }

        .guid_btn_outline_x1:hover {
            background-color: rgba(15,23,42,0.18);
        }

        /* --------- Responsive nhỏ ---------- */
        @media (max-width: 640px) {
            .guid_main_container_x1 {
                padding: 20px 14px 40px;
            }

            .guid_hero_title_x1 {
                font-size: 26px;
            }

            .guid_section_title_x1 {
                font-size: 22px;
            }
        }
    </style>
</head>
<body>

<?php include "blocks/header.php" ?>

<!-- Lưu ý quan trọng -->
<div class="guid_notice_wrap_x1">
    <div class="guid_notice_inner_x1">
        <div class="guid_notice_icon_x1">
            <svg fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
        </div>
        <div>
            <h3 class="guid_notice_title_x1">Lưu ý quan trọng</h3>
            <p class="guid_notice_text_x1">
                Đây là đồ án sinh viên, không phải website bệnh viện chính thức. Chúng tôi không chịu bất cứ trách nhiệm nào về sự nhầm lẫn của bạn.
            </p>
        </div>
    </div>
</div>

<!-- Hero -->
<div class="guid_hero_x1">
    <div class="guid_hero_inner_x1">
        <h1 class="guid_hero_title_x1">Hướng Dẫn Bệnh Nhân</h1>
        <p class="guid_hero_text_x1">
            Thông tin hữu ích để bạn có trải nghiệm tốt nhất tại bệnh viện
        </p>
    </div>
</div>

<main class="guid_main_container_x1">

    <!-- Quy trình khám bệnh -->
    <section class="guid_section_x1">
        <h2 class="guid_section_title_x1">Quy Trình Khám Bệnh</h2>
        <div class="guid_grid_4col_md_x1">
            <div class="guid_steps_card_x1">
                <div class="guid_step_number_x1">01</div>
                <h3 class="guid_step_title_x1">Đăng Ký Khám</h3>
                <p class="guid_step_text_x1">
                    Đến quầy tiếp nhận hoặc đăng ký trực tuyến. Mang theo CMND/CCCD và thẻ BHYT (nếu có).
                </p>
            </div>
            <div class="guid_steps_card_x1">
                <div class="guid_step_number_x1">02</div>
                <h3 class="guid_step_title_x1">Thanh Toán Phí</h3>
                <p class="guid_step_text_x1">
                    Sau khi đăng ký có thể thanh toán trực tuyến hoặc đến quầy tại bệnh viện.
                </p>
            </div>
            <div class="guid_steps_card_x1">
                <div class="guid_step_number_x1">03</div>
                <h3 class="guid_step_title_x1">Theo dõi lịch khám</h3>
                <p class="guid_step_text_x1">
                    Xem thông tin chi tiết của lịch đã đăng ký.
                </p>
            </div>
            <div class="guid_steps_card_x1">
                <div class="guid_step_number_x1">04</div>
                <h3 class="guid_step_title_x1">Khám & Điều Trị</h3>
                <p class="guid_step_text_x1">
                    Gặp bác sĩ, thực hiện xét nghiệm nếu cần và nhận đơn thuốc.
                </p>
            </div>
        </div>
    </section>

    <!-- Giờ làm việc & thăm bệnh -->
    <section class="guid_section_x1">
        <h2 class="guid_section_title_x1">Giờ Làm Việc & Thăm Bệnh</h2>
        <div class="guid_grid_2col_md_x1">
            <!-- Giờ làm việc -->
            <div class="guid_card_x1">
                <div class="guid_flex_row_x1">
                    <div class="guid_icon_box_blue_x1">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div style="flex:1;">
                        <h3 class="guid_card_title_x1">Giờ Làm Việc</h3>
                        <div style="font-size:13px;">
                            <div class="guid_flex_between_x1">
                                <span class="guid_time_label_x1">Thứ 2 - Thứ 6:</span>
                                <span class="guid_time_value_x1">7:00 - 17:00</span>
                            </div>
                            <div class="guid_flex_between_x1">
                                <span class="guid_time_label_x1">Thứ 7:</span>
                                <span class="guid_time_value_x1">7:00 - 12:00</span>
                            </div>
                            <div class="guid_flex_between_x1">
                                <span class="guid_time_label_x1">Chủ nhật:</span>
                                <span class="guid_time_value_x1">Nghỉ</span>
                            </div>
                            <div class="guid_divider_top_x1 guid_flex_between_x1">
                                <span class="guid_time_label_x1">Cấp cứu:</span>
                                <span class="guid_time_value_red_x1 guid_time_value_x1">24/7</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Giờ thăm bệnh -->
            <div class="guid_card_x1">
                <div class="guid_flex_row_x1">
                    <div class="guid_icon_box_green_x1">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div style="flex:1;">
                        <h3 class="guid_card_title_x1">Giờ Thăm Bệnh</h3>
                        <div style="font-size:13px;">
                            <div class="guid_flex_between_x1">
                                <span class="guid_time_label_x1">Buổi sáng:</span>
                                <span class="guid_time_value_x1">8:00 - 11:00</span>
                            </div>
                            <div class="guid_flex_between_x1">
                                <span class="guid_time_label_x1">Buổi chiều:</span>
                                <span class="guid_time_value_x1">14:00 - 17:00</span>
                            </div>
                            <div class="guid_flex_between_x1">
                                <span class="guid_time_label_x1">Số người thăm:</span>
                                <span class="guid_time_value_x1">Tối đa 2 người</span>
                            </div>
                            <div class="guid_divider_top_x1">
                                <p style="font-size:12px;color:#4b5563;margin:0;">
                                    * Không thăm bệnh vào giờ nghỉ trưa và sau 17:00
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Quy định thăm bệnh -->
    <section class="guid_section_x1">
        <h2 class="guid_section_title_x1">Quy Định Thăm Bệnh</h2>
        <div class="guid_card_x1">
            <div class="guid_grid_2col_md_x1">
                <div>
                    <h3 class="guid_card_title_x1" style="color:#16a34a;display:flex;align-items:center;gap:6px;">
                        <svg fill="currentColor" viewBox="0 0 20 20" style="width:18px;height:18px;">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Được phép
                    </h3>
                    <ul class="guid_list_x1">
                        <li class="guid_list_item_x1">
                            <span class="guid_list_icon_green_x1">✓</span>
                            <span>Mang theo hoa tươi, trái cây sạch.</span>
                        </li>
                        <li class="guid_list_item_x1">
                            <span class="guid_list_icon_green_x1">✓</span>
                            <span>Giữ im lặng, nói chuyện nhỏ nhẹ.</span>
                        </li>
                        <li class="guid_list_item_x1">
                            <span class="guid_list_icon_green_x1">✓</span>
                            <span>Rửa tay sát khuẩn trước khi vào phòng.</span>
                        </li>
                        <li class="guid_list_item_x1">
                            <span class="guid_list_icon_green_x1">✓</span>
                            <span>Đeo khẩu trang khi cần thiết.</span>
                        </li>
                        <li class="guid_list_item_x1">
                            <span class="guid_list_icon_green_x1">✓</span>
                            <span>Tuân thủ hướng dẫn của nhân viên y tế.</span>
                        </li>
                    </ul>
                </div>
                <div>
                    <h3 class="guid_card_title_x1" style="color:#dc2626;display:flex;align-items:center;gap:6px;">
                        <svg fill="currentColor" viewBox="0 0 20 20" style="width:18px;height:18px;">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        Không được phép
                    </h3>
                    <ul class="guid_list_x1">
                        <li class="guid_list_item_x1">
                            <span class="guid_list_icon_red_x1">✗</span>
                            <span>Mang trẻ em dưới 12 tuổi vào thăm.</span>
                        </li>
                        <li class="guid_list_item_x1">
                            <span class="guid_list_icon_red_x1">✗</span>
                            <span>Hút thuốc trong khuôn viên bệnh viện.</span>
                        </li>
                        <li class="guid_list_item_x1">
                            <span class="guid_list_icon_red_x1">✗</span>
                            <span>Sử dụng điện thoại ồn ào.</span>
                        </li>
                        <li class="guid_list_item_x1">
                            <span class="guid_list_icon_red_x1">✗</span>
                            <span>Mang thức ăn nặng mùi, đồ uống có cồn.</span>
                        </li>
                        <li class="guid_list_item_x1">
                            <span class="guid_list_icon_red_x1">✗</span>
                            <span>Chụp ảnh, quay phim khi chưa được phép.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Thanh toán & Bảo hiểm -->
    <section class="guid_section_x1">
        <h2 class="guid_section_title_x1">Thanh Toán & Bảo Hiểm</h2>
        <div class="guid_grid_3col_md_x1">
            <div class="guid_card_x1">
                <div class="guid_icon_box_purple_x1">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
                <h3 class="guid_card_title_x1">Hình Thức Thanh Toán</h3>
                <ul class="guid_list_dotted_x1">
                    <li>Tiền mặt (VNĐ).</li>
                    <li>Chuyển khoản ngân hàng.</li>
                    <li>Thanh toán thông qua QR Code.</li>
                </ul>
            </div>

            <div class="guid_card_x1">
                <div class="guid_icon_box_blue2_x1">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="guid_card_title_x1">Bảo Hiểm Y Tế</h3>
                <ul class="guid_list_dotted_x1">
                    <li>Chấp nhận BHYT quốc gia.</li>
                    <li>Bảo hiểm tư nhân.</li>
                    <li>Mang thẻ BHYT khi đến.</li>
                    <li>Kiểm tra hạn sử dụng.</li>
                    <li>Đăng ký đúng tuyến.</li>
                </ul>
            </div>

            <div class="guid_card_x1">
                <div class="guid_icon_box_green2_x1">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <h3 class="guid_card_title_x1">Giấy Tờ Cần Thiết</h3>
                <ul class="guid_list_dotted_x1">
                    <li>CMND/CCCD/Hộ chiếu.</li>
                    <li>Thẻ BHYT (nếu có).</li>
                    <li>Sổ khám bệnh (nếu có).</li>
                    <li>Kết quả xét nghiệm cũ.</li>
                    <li>Giấy chuyển viện (nếu có).</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Dịch vụ tiện ích -->
    <section class="guid_section_x1">
        <h2 class="guid_section_title_x1">Dịch Vụ Tiện Ích</h2>
        <div class="guid_grid_4col_md_x1">
            <div class="guid_card_x1 guid_text_center_x1">
                <div class="guid_icon_circle_orange_x1">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h3 class="guid_card_title_x1">Cửa Hàng Tiện Lợi</h3>
                <p class="guid_card_text_x1" style="font-size:12px;">Tầng 1, mở cửa 6:00-22:00.</p>
            </div>

            <div class="guid_card_x1 guid_text_center_x1">
                <div class="guid_icon_circle_red_x1">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <h3 class="guid_card_title_x1">Thư Viện Y Học</h3>
                <p class="guid_card_text_x1" style="font-size:12px;">Tầng 3, mở cửa 8:00-17:00.</p>
            </div>

            <div class="guid_card_x1 guid_text_center_x1">
                <div class="guid_icon_circle_teal_x1">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="guid_card_title_x1">Đặt Lịch Online</h3>
                <p class="guid_card_text_x1" style="font-size:12px;">Qua website hoặc hotline.</p>
            </div>

            <div class="guid_card_x1 guid_text_center_x1">
                <div class="guid_icon_circle_indigo_x1">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                    </svg>
                </div>
                <h3 class="guid_card_title_x1">ATM & Ngân Hàng</h3>
                <p class="guid_card_text_x1" style="font-size:12px;">Tầng 1, phục vụ 24/7.</p>
            </div>
        </div>
    </section>

    <!-- Bãi đỗ xe & đi lại -->
    <section class="guid_section_x1">
        <h2 class="guid_section_title_x1">Bãi Đỗ Xe & Đi Lại</h2>
        <div class="guid_grid_2col_md_x1">
            <!-- Bãi đỗ xe -->
            <div class="guid_card_x1">
                <h3 class="guid_card_title_x1" style="display:flex;align-items:center;gap:6px;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px;height:18px;color:#2563eb;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                    Bãi đỗ xe
                </h3>
                <div class="guid_list_x1" style="margin-top:4px;">
                    <div class="guid_flex_row_x1">
                        <span class="guid_label_x1">Xe máy:</span>
                        <span class="guid_value_x1">5.000đ/lượt - Tầng hầm B1.</span>
                    </div>
                    <div class="guid_flex_row_x1">
                        <span class="guid_label_x1">Ô tô:</span>
                        <span class="guid_value_x1">20.000đ/giờ - Tầng hầm B1, B2.</span>
                    </div>
                    <div class="guid_flex_row_x1">
                        <span class="guid_label_x1">Miễn phí:</span>
                        <span class="guid_value_x1">Xe cấp cứu, xe người khuyết tật.</span>
                    </div>
                    <div class="guid_tip_box_blue_x1">
                        💡 Bãi xe có camera an ninh 24/7 và nhân viên trông giữ.
                    </div>
                </div>
            </div>

            <!-- Đi lại -->
            <div class="guid_card_x1">
                <h3 class="guid_card_title_x1" style="display:flex;align-items:center;gap:6px;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px;height:18px;color:#16a34a;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Đi lại
                </h3>
                <div class="guid_list_x1" style="margin-top:4px;">
                    <div class="guid_flex_row_x1">
                        <span class="guid_label_x1">Xe buýt:</span>
                        <span class="guid_value_x1">Tuyến 08, 14, 28, 45 - Trạm BV Đa Khoa.</span>
                    </div>
                    <div class="guid_flex_row_x1">
                        <span class="guid_label_x1">Taxi:</span>
                        <span class="guid_value_x1">Điểm đón tại cổng chính.</span>
                    </div>
                    <div class="guid_flex_row_x1">
                        <span class="guid_label_x1">Grab/Gojek:</span>
                        <span class="guid_value_x1">Điểm đón tại cổng phụ.</span>
                    </div>
                    <div class="guid_tip_box_green_x1">
                        🚌 Bệnh viện cách bến xe 2km, sân bay 15km.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Liên hệ khẩn cấp -->
    <section class="guid_section_x1">
        <h2 class="guid_section_title_x1">Liên Hệ Khẩn Cấp</h2>
        <div class="guid_emergency_wrap_x1">
            <div class="guid_grid_3col_md_x1">
                <div class="guid_emergency_item_x1">
                    <div class="guid_emergency_circle_red_x1">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <h3 class="guid_emergency_title_x1">Cấp Cứu 24/7</h3>
                    <p class="guid_emergency_main_x1 guid_emergency_main_red_x1">115</p>
                    <p class="guid_emergency_sub_x1">Hoặc: (024) 3826 xxxx</p>
                </div>

                <div class="guid_emergency_item_x1">
                    <div class="guid_emergency_circle_blue_x1">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="guid_emergency_title_x1">Tổng Đài Tư Vấn</h3>
                    <p class="guid_emergency_main_x1 guid_emergency_main_blue_x1">1900 0000</p>
                    <p class="guid_emergency_sub_x1">7:00 - 21:00 hàng ngày</p>
                </div>

                <div class="guid_emergency_item_x1">
                    <div class="guid_emergency_circle_green_x1">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="guid_emergency_title_x1">Email Hỗ Trợ</h3>
                    <p class="guid_emergency_main_x1 guid_emergency_main_green_x1">support@hospital.vn</p>
                    <p class="guid_emergency_sub_x1">Phản hồi trong 24h.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section class="guid_section_x1">
        <h2 class="guid_section_title_x1">Câu Hỏi Thường Gặp</h2>
        <div class="guid_grid_x1">
            <details class="guid_faq_item_x1">
                <summary>
                    <span>Tôi có cần đặt lịch trước khi đến khám không?</span>
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </summary>
                <div class="guid_faq_body_x1">
                    Bạn có thể đến trực tiếp hoặc đặt lịch trước qua hotline/website để tiết kiệm thời gian chờ. Đặt lịch trước được ưu tiên và giảm thời gian chờ đợi.
                </div>
            </details>

            <details class="guid_faq_item_x1">
                <summary>
                    <span>Bệnh viện có nhận bảo hiểm y tế không?</span>
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </summary>
                <div class="guid_faq_body_x1">
                    Có, chúng tôi chấp nhận BHYT quốc gia và hầu hết các loại bảo hiểm tư nhân. Vui lòng mang theo thẻ BHYT còn hạn và đăng ký đúng tuyến để được hưởng quyền lợi tối đa.
                </div>
            </details>

            <details class="guid_faq_item_x1">
                <summary>
                    <span>Tôi có thể lấy kết quả xét nghiệm khi nào?</span>
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </summary>
                <div class="guid_faq_body_x1">
                    Thời gian trả kết quả tùy loại xét nghiệm: xét nghiệm thường (2–4 giờ), xét nghiệm đặc biệt (1–3 ngày), sinh thiết (5–7 ngày). Bạn có thể nhận kết quả trực tiếp hoặc xem online qua hệ thống.
                </div>
            </details>

            <details class="guid_faq_item_x1">
                <summary>
                    <span>Người nhà có được ở lại chăm sóc bệnh nhân không?</span>
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </summary>
                <div class="guid_faq_body_x1">
                    Có, mỗi bệnh nhân được phép có 1 người nhà ở lại chăm sóc. Người nhà cần đăng ký tại quầy điều dưỡng và tuân thủ nội quy của bệnh viện. Phòng VIP cho phép 2 người nhà.
                </div>
            </details>

            <details class="guid_faq_item_x1">
                <summary>
                    <span>Bệnh viện có dịch vụ xe đưa đón không?</span>
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </summary>
                <div class="guid_faq_body_x1">
                    Chúng tôi có dịch vụ xe cấp cứu 24/7 và xe đưa đón cho bệnh nhân nội trú xuất viện (có phí). Vui lòng liên hệ tổng đài để đặt lịch trước ít nhất 2 giờ.
                </div>
            </details>
        </div>
    </section>

</main>

<!-- CTA cuối trang -->
<div class="guid_cta_footer_x1">
    <div class="guid_cta_inner_x1">
        <h2 class="guid_cta_title_x1">Cần Hỗ Trợ Thêm?</h2>
        <p class="guid_cta_text_x1">
            Đội ngũ chăm sóc khách hàng của chúng tôi luôn sẵn sàng hỗ trợ bạn
        </p>
        <div class="guid_cta_actions_x1">
            <a href="tel:19000000" class="guid_btn_primary_x1">
                Gọi Hotline: 1900 0000
            </a>
            <a href="#" class="guid_btn_outline_x1">
                Đặt Lịch Khám Online
            </a>
        </div>
    </div>
</div>

<?php include "blocks/footer.php" ?>

</body>
</html>
