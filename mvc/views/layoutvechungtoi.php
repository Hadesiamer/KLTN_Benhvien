<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Về Chúng Tôi - Bệnh Viện</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #0f4c81 0%, #1e3a5f 100%);
        }
    </style>
</head>
<body class="bg-gray-50">
    <?php include "blocks/header.php" ?>
        <div class="bg-amber-50 border-l-4 border-amber-500 px-6 py-3 mb-6">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <h3 class="text-amber-900 font-semibold mb-1">Lưu ý quan trọng</h3>
                    <p class="text-amber-800 text-sm leading-relaxed">
                        Đây là đồ án sinh viên, không phải website bệnh viện chính thức. Chúng tôi không chịu bất cứ trách nhiệm nào về sự nhầm lẫn của bạn.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <main class="max-w-7xl mx-auto px-6 py-8">
        <div class="gradient-bg rounded-2xl p-8 mb-10 text-white">
            <div class="max-w-4xl">
                <h1 class="text-4xl font-bold mb-3 text-balance">Giới thiệu về Bệnh viện Đa khoa Trung ương</h1>
                <p class="text-lg text-blue-100 leading-relaxed">
                    Nơi hội tụ y đức, tâm huyết và công nghệ hiện đại, mang đến dịch vụ chăm sóc sức khỏe toàn diện cho cộng đồng.
                </p>
            </div>
        </div>

        <section class="mb-10">
            <div class="grid md:grid-cols-2 gap-6">
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-3">Tầm nhìn</h2>
                    <p class="text-gray-600 leading-relaxed">
                        Trở thành một trong những bệnh viện hàng đầu tại Việt Nam, mang đến dịch vụ y tế chất lượng cao, hiện đại và nhân văn, vì sức khỏe và hạnh phúc của cộng đồng.
                    </p>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-3">Sứ mệnh</h2>
                    <p class="text-gray-600 leading-relaxed">
                        Cung cấp dịch vụ khám chữa bệnh toàn diện, an toàn và hiệu quả; lấy người bệnh làm trung tâm trong mọi hoạt động; không ngừng nghiên cứu, đổi mới và phát triển chuyên môn để phục vụ xã hội tốt hơn mỗi ngày.
                    </p>
                </div>
            </div>
        </section>

        <section class="mb-10">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Giá trị cốt lõi</h2>
                <p class="text-lg text-gray-600">Những nguyên tắc định hướng mọi hoạt động của chúng tôi</p>
            </div>

             Removed all placeholder comments and reduced card padding 
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
                <div class="bg-white rounded-xl p-5 border border-gray-100 hover:border-blue-200 transition-colors">
                    <div class="w-11 h-11 bg-red-100 rounded-lg flex items-center justify-center mb-3">
                        <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Tận tâm</h3>
                    <p class="text-gray-600 leading-relaxed text-sm">Luôn đặt lợi ích và sức khỏe người bệnh lên hàng đầu.</p>
                </div>

                <div class="bg-white rounded-xl p-5 border border-gray-100 hover:border-blue-200 transition-colors">
                    <div class="w-11 h-11 bg-blue-100 rounded-lg flex items-center justify-center mb-3">
                        <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Chuyên nghiệp</h3>
                    <p class="text-gray-600 leading-relaxed text-sm">Đội ngũ y bác sĩ, điều dưỡng và nhân viên được đào tạo bài bản, giàu kinh nghiệm.</p>
                </div>

                <div class="bg-white rounded-xl p-5 border border-gray-100 hover:border-blue-200 transition-colors">
                    <div class="w-11 h-11 bg-purple-100 rounded-lg flex items-center justify-center mb-3">
                        <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Hiện đại</h3>
                    <p class="text-gray-600 leading-relaxed text-sm">Ứng dụng công nghệ tiên tiến, trang thiết bị đạt chuẩn quốc tế.</p>
                </div>

                <div class="bg-white rounded-xl p-5 border border-gray-100 hover:border-blue-200 transition-colors">
                    <div class="w-11 h-11 bg-green-100 rounded-lg flex items-center justify-center mb-3">
                        <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Nhân văn</h3>
                    <p class="text-gray-600 leading-relaxed text-sm">Xây dựng môi trường y tế thân thiện, tôn trọng và sẻ chia.</p>
                </div>

                <div class="bg-white rounded-xl p-5 border border-gray-100 hover:border-blue-200 transition-colors">
                    <div class="w-11 h-11 bg-teal-100 rounded-lg flex items-center justify-center mb-3">
                        <svg class="w-6 h-6 text-teal-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Phát triển bền vững</h3>
                    <p class="text-gray-600 leading-relaxed text-sm">Liên tục cải tiến chất lượng dịch vụ và quy trình điều trị.</p>
                </div>
            </div>
        </section>

        <section class="mb-10">
            <div class="bg-gradient-to-br from-blue-50 to-teal-50 rounded-2xl p-8">
                <div class="max-w-4xl mx-auto text-center">
                    <div class="w-14 h-14 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Đội ngũ chuyên môn</h2>
                    <p class="text-lg text-gray-700 leading-relaxed mb-3">
                        Bệnh viện Đa khoa Trung ương quy tụ đội ngũ giáo sư, tiến sĩ, bác sĩ chuyên khoa đầu ngành trong nhiều lĩnh vực: Nội tổng quát, Ngoại khoa, Sản – Nhi, Tim mạch, Ung bướu, Cấp cứu, Chẩn đoán hình ảnh, và nhiều chuyên khoa khác.
                    </p>
                    <p class="text-base text-blue-900 font-semibold">
                        Chúng tôi tự hào là nơi hội tụ tri thức – kinh nghiệm – tâm đức của ngành y Việt Nam.
                    </p>
                </div>
            </div>
        </section>

        <section class="mb-10">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Cơ sở vật chất hiện đại</h2>
                <p class="text-lg text-gray-600">
                    Với hệ thống cơ sở vật chất khang trang, môi trường khám chữa bệnh tiện nghi, thân thiện
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-5">
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Phòng khám chuẩn quốc tế</h3>
                    <p class="text-gray-600 leading-relaxed text-sm">Phòng khám và điều trị đạt chuẩn quốc tế với đầy đủ tiện nghi.</p>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Công nghệ cao</h3>
                    <p class="text-gray-600 leading-relaxed text-sm">Hệ thống xét nghiệm, chẩn đoán hình ảnh, phẫu thuật công nghệ cao.</p>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Phòng bệnh tiện nghi</h3>
                    <p class="text-gray-600 leading-relaxed text-sm">Phòng bệnh riêng tư, tiện nghi, đảm bảo sự thoải mái cho bệnh nhân.</p>
                </div>
            </div>
        </section>

        <section class="mb-10">
            <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100">
                <h2 class="text-3xl font-bold text-gray-900 mb-6 text-center">Dịch vụ nổi bật</h2>
                
                <div class="grid md:grid-cols-2 gap-5 max-w-5xl mx-auto">
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-gray-900 mb-1">Khám bệnh tổng quát và chuyên khoa</h3>
                            <p class="text-gray-600 text-sm">Đa dạng chuyên khoa với đội ngũ bác sĩ giàu kinh nghiệm</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-gray-900 mb-1">Phẫu thuật – điều trị tiên tiến</h3>
                            <p class="text-gray-600 text-sm">Áp dụng phương pháp điều trị hiện đại nhất</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-gray-900 mb-1">Chăm sóc sức khỏe toàn diện</h3>
                            <p class="text-gray-600 text-sm">Dịch vụ chăm sóc từ phòng bệnh đến điều trị</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-gray-900 mb-1">Khám sức khỏe định kỳ</h3>
                            <p class="text-gray-600 text-sm">Gói khám sức khỏe cho cá nhân và doanh nghiệp</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-gray-900 mb-1">Cấp cứu 24/7</h3>
                            <p class="text-gray-600 text-sm">Dịch vụ cấp cứu, vận chuyển và tư vấn trực tuyến</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mb-10">
            <div class="bg-gradient-to-r from-blue-600 to-teal-600 rounded-2xl p-8 text-white">
                <div class="max-w-4xl mx-auto text-center">
                    <div class="w-14 h-14 bg-white/20 backdrop-blur rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold mb-4">Hợp tác quốc tế</h2>
                    <p class="text-lg text-blue-50 leading-relaxed">
                        Bệnh viện Đa khoa Trung ương hợp tác với nhiều tổ chức y tế, trường đại học và bệnh viện uy tín trong và ngoài nước nhằm trao đổi chuyên môn, đào tạo nhân lực và chuyển giao công nghệ y khoa hiện đại.
                    </p>
                </div>
            </div>
        </section>

        <section class="mb-8">
            <div class="bg-gray-900 rounded-2xl p-8 text-white">
                <h2 class="text-3xl font-bold mb-6 text-center">Thông tin liên hệ</h2>
                
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-5xl mx-auto">
                    <div class="flex items-start gap-3">
                        <div class="w-11 h-11 bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-base mb-1">Địa chỉ</h3>
                            <p class="text-gray-300 text-sm">123 Đường Lê Lợi, Phường Bến Nghé, Quận 1, TP. Hồ Chí Minh</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-11 h-11 bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-base mb-1">Hotline</h3>
                            <p class="text-gray-300 text-sm">1900 1234</p>
                            <p class="text-gray-300 text-sm">(028) 3822 5678</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-11 h-11 bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-base mb-1">Email</h3>
                            <p class="text-gray-300 text-sm">info@benhvien.vn</p>
                            <p class="text-gray-300 text-sm">cskh@benhvien.vn</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-11 h-11 bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-base mb-1">Website</h3>
                            <p class="text-gray-300 text-sm">www.benhvien.vn</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-11 h-11 bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-base mb-1">Giờ làm việc</h3>
                            <p class="text-gray-300 text-sm">24/7 - Cấp cứu</p>
                            <p class="text-gray-300 text-sm">7:00 - 17:00 - Khám bệnh</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
<?php include "blocks/footer.php" ?>

</body>
</html>
