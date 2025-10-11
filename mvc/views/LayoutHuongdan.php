<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hướng Dẫn - Bệnh Viện</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #0ea5e9 0%, #06b6d4 100%);
        }
        
        .step-number {
            background: linear-gradient(135deg, #0ea5e9 0%, #06b6d4 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="bg-gray-50">    
<?php include "blocks/header.php" ?>

    <div class="bg-amber-50 border-l-4 border-amber-500 p-4">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-amber-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>  
                </div>
            
                <div>
                    <h3 class="text-amber-900 font-semibold mb-1">Lưu ý quan trọng</h3>
                    <p class="text-amber-800 text-sm leading-relaxed">
                        Đây là đồ án sinh viên, không phải website bệnh viện chính thức. Chúng tôi không chịu bất cứ trách nhiệm nào về sự nhầm lẫn của bạn.
                    </p>
                </div>
            </div>
        </div>
    </div>

 
    <div class="gradient-bg text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold mb-3">Hướng Dẫn Bệnh Nhân</h1>
            <p class="text-xl text-blue-50">Thông tin hữu ích để bạn có trải nghiệm tốt nhất tại bệnh viện</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <section class="mb-10">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">Quy Trình Khám Bệnh</h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-100">
                    <div class="text-5xl font-bold step-number mb-3">01</div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Đăng Ký Khám</h3>
                    <p class="text-gray-600 text-sm">Đến quầy tiếp nhận hoặc đăng ký trực tuyến. Mang theo CMND/CCCD và thẻ BHYT (nếu có).</p>
                </div>
                <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-100">
                    <div class="text-5xl font-bold step-number mb-3">02</div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Thanh Toán Phí</h3>
                    <p class="text-gray-600 text-sm">Sau khi đăng ký có thể thanh toán trực tuyến hoặc đến quầy tại bệnh viện</p>
                </div>
                <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-100">
                    <div class="text-5xl font-bold step-number mb-3">03</div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Theo dõi lịch khám</h3>
                    <p class="text-gray-600 text-sm">Xem thông tin chi tiết của lịch đã đăng ký</p>
                </div>
                <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-100">
                    <div class="text-5xl font-bold step-number mb-3">04</div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Khám & Điều Trị</h3>
                    <p class="text-gray-600 text-sm">Gặp bác sĩ, thực hiện xét nghiệm nếu cần và nhận đơn thuốc.</p>
                </div>
            </div>
        </section>

      
        <section class="mb-10">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">Giờ Làm Việc & Thăm Bệnh</h2>
            <div class="grid md:grid-cols-2 gap-4">
                <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-100">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 bg-blue-100 rounded-lg p-3">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4 flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Giờ Làm Việc</h3>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Thứ 2 - Thứ 6:</span>
                                    <span class="font-medium text-gray-900">7:00 - 17:00</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Thứ 7:</span>
                                    <span class="font-medium text-gray-900">7:00 - 12:00</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Chủ nhật:</span>
                                    <span class="font-medium text-gray-900">Nghỉ</span>
                                </div>
                                <div class="flex justify-between pt-2 border-t">
                                    <span class="text-gray-600">Cấp cứu:</span>
                                    <span class="font-medium text-red-600">24/7</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-100">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 bg-green-100 rounded-lg p-3">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4 flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Giờ Thăm Bệnh</h3>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Buổi sáng:</span>
                                    <span class="font-medium text-gray-900">8:00 - 11:00</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Buổi chiều:</span>
                                    <span class="font-medium text-gray-900">14:00 - 17:00</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Số người thăm:</span>
                                    <span class="font-medium text-gray-900">Tối đa 2 người</span>
                                </div>
                                <div class="pt-2 border-t">
                                    <p class="text-gray-600 text-xs">* Không thăm bệnh vào giờ nghỉ trưa và sau 17:00</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
  
      
        <section class="mb-10">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">Quy Định Thăm Bệnh</h2>
            <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-100">
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold text-green-600 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                
                        </h3>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li class="flex items-start">
                                <span class="text-green-500 mr-2">✓</span>
                                <span>Mang theo hoa tươi, trái cây sạch</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-green-500 mr-2">✓</span>
                                <span>Giữ im lặng, nói chuyện nhỏ nhẹ</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-green-500 mr-2">✓</span>
                                <span>Rửa tay sát khuẩn trước khi vào phòng</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-green-500 mr-2">✓</span>
                                <span>Đeo khẩu trang khi cần thiết</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-green-500 mr-2">✓</span>
                                <span>Tuân thủ hướng dẫn của nhân viên y tế</span>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-red-600 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                     
                        </h3>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li class="flex items-start">
                                <span class="text-red-500 mr-2">✗</span>
                                <span>Mang trẻ em dưới 12 tuổi vào thăm</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-red-500 mr-2">✗</span>
                                <span>Hút thuốc trong khuôn viên bệnh viện</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-red-500 mr-2">✗</span>
                                <span>Sử dụng điện thoại ồn ào</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-red-500 mr-2">✗</span>
                                <span>Mang thức ăn nặng mùi, đồ uống có cồn</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-red-500 mr-2">✗</span>
                                <span>Chụp ảnh, quay phim khi chưa được phép</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

    
        <section class="mb-10">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">Thanh Toán & Bảo Hiểm</h2>
            <div class="grid md:grid-cols-3 gap-4">
                <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-100">
                    <div class="bg-purple-100 rounded-lg p-3 w-12 h-12 flex items-center justify-center mb-3">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Hình Thức Thanh Toán</h3>
                    <ul class="space-y-1 text-sm text-gray-600">
                        <li>• Tiền mặt (VNĐ)</li>
                        <li>• Chuyển khoản ngân hàng</li>
                        <li>• Thanh toán thông qua Qrcode</li>

                    </ul>
                </div>
                <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-100">
                    <div class="bg-blue-100 rounded-lg p-3 w-12 h-12 flex items-center justify-center mb-3">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Bảo Hiểm Y Tế</h3>
                    <ul class="space-y-1 text-sm text-gray-600">
                        <li>• Chấp nhận BHYT quốc gia</li>
                        <li>• Bảo hiểm tư nhân</li>
                        <li>• Mang thẻ BHYT khi đến</li>
                        <li>• Kiểm tra hạn sử dụng</li>
                        <li>• Đăng ký đúng tuyến</li>
                    </ul>
                </div>
                <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-100">
                    <div class="bg-green-100 rounded-lg p-3 w-12 h-12 flex items-center justify-center mb-3">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Giấy Tờ Cần Thiết</h3>
                    <ul class="space-y-1 text-sm text-gray-600">
                        <li>• CMND/CCCD/Hộ chiếu</li>
                        <li>• Thẻ BHYT (nếu có)</li>
                        <li>• Sổ khám bệnh (nếu có)</li>
                        <li>• Kết quả xét nghiệm cũ</li>
                        <li>• Giấy chuyển viện (nếu có)</li>
                    </ul>
                </div>
            </div>
        </section>

       
        <section class="mb-10">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">Dịch Vụ Tiện Ích</h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-lg p-5 shadow-sm border border-gray-100 text-center">
                    <div class="bg-orange-100 rounded-full p-3 w-14 h-14 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">Cửa Hàng Tiện Lợi</h3>
                    <p class="text-xs text-gray-600">Tầng 1, mở cửa 6:00-22:00</p>
                </div>
                <div class="bg-white rounded-lg p-5 shadow-sm border border-gray-100 text-center">
                    <div class="bg-red-100 rounded-full p-3 w-14 h-14 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">Thư Viện Y Học</h3>
                    <p class="text-xs text-gray-600">Tầng 3, mở cửa 8:00-17:00</p>
                </div>
                <div class="bg-white rounded-lg p-5 shadow-sm border border-gray-100 text-center">
                    <div class="bg-teal-100 rounded-full p-3 w-14 h-14 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">Đặt Lịch Online</h3>
                    <p class="text-xs text-gray-600">Website hoặc hotline</p>
                </div>
                <div class="bg-white rounded-lg p-5 shadow-sm border border-gray-100 text-center">
                    <div class="bg-indigo-100 rounded-full p-3 w-14 h-14 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">ATM & Ngân Hàng</h3>
                    <p class="text-xs text-gray-600">Tầng 1, phục vụ 24/7</p>
                </div>
            </div>
        </section>


        <section class="mb-10">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">Bãi Đỗ Xe & Đi Lại</h2>
            <div class="grid md:grid-cols-2 gap-4">
                <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                      
                    </h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex items-start">
                            <span class="font-medium text-gray-700 w-24">Xe máy:</span>
                            <span class="text-gray-600">5.000đ/lượt - Tầng hầm B1</span>
                        </div>
                        <div class="flex items-start">
                            <span class="font-medium text-gray-700 w-24">Ô tô:</span>
                            <span class="text-gray-600">20.000đ/giờ - Tầng hầm B1, B2</span>
                        </div>
                        <div class="flex items-start">
                            <span class="font-medium text-gray-700 w-24">Miễn phí:</span>
                            <span class="text-gray-600">Xe cấp cứu, xe người khuyết tật</span>
                        </div>
                        <div class="bg-blue-50 rounded p-3 mt-3">
                            <p class="text-xs text-blue-800">💡 Bãi xe có camera an ninh 24/7 và nhân viên trông giữ</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>

                    </h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex items-start">
                            <span class="font-medium text-gray-700 w-24">Xe buýt:</span>
                            <span class="text-gray-600">Tuyến 08, 14, 28, 45 - Trạm BV Đa Khoa</span>
                        </div>
                        <div class="flex items-start">
                            <span class="font-medium text-gray-700 w-24">Taxi:</span>
                            <span class="text-gray-600">Điểm đón tại cổng chính</span>
                        </div>
                        <div class="flex items-start">
                            <span class="font-medium text-gray-700 w-24">Grab/Gojek:</span>
                            <span class="text-gray-600">Điểm đón tại cổng phụ</span>
                        </div>
                        <div class="bg-green-50 rounded p-3 mt-3">
                            <p class="text-xs text-green-800">🚌 Bệnh viện cách bến xe 2km, sân bay 15km</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <section class="mb-10">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">Liên Hệ Khẩn Cấp</h2>
            <div class="bg-gradient-to-r from-red-50 to-orange-50 rounded-lg p-6 border border-red-200">
                <div class="grid md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="bg-red-600 text-white rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <h3 class="font-bold text-gray-900 text-lg mb-1">Cấp Cứu 24/7</h3>
                        <p class="text-2xl font-bold text-red-600">115</p>
                        <p class="text-sm text-gray-600 mt-1">Hoặc: (024) 3826 xxxx</p>
                    </div>
                    <div class="text-center">
                        <div class="bg-blue-600 text-white rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="font-bold text-gray-900 text-lg mb-1">Tổng Đài Tư Vấn</h3>
                        <p class="text-2xl font-bold text-blue-600">1900 0000</p>
                        <p class="text-sm text-gray-600 mt-1">7:00 - 21:00 hàng ngày</p>
                    </div>
                    <div class="text-center">
                        <div class="bg-green-600 text-white rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="font-bold text-gray-900 text-lg mb-1">Email Hỗ Trợ</h3>
                        <p class="text-lg font-bold text-green-600">support@hospital.vn</p>
                        <p class="text-sm text-gray-600 mt-1">Phản hồi trong 24h</p>
                    </div>
                </div>
            </div>
        </section>

         Câu Hỏi Thường Gặp 
        <section>
            <h2 class="text-3xl font-bold text-gray-900 mb-6">Câu Hỏi Thường Gặp</h2>
            <div class="space-y-3">
                <details class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                    <summary class="px-6 py-4 cursor-pointer font-semibold text-gray-900 hover:bg-gray-50 flex items-center justify-between">
                        <span>Tôi có cần đặt lịch trước khi đến khám không?</span>
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </summary>
                    <div class="px-6 pb-4 text-gray-600 text-sm">
                        Bạn có thể đến trực tiếp hoặc đặt lịch trước qua hotline/website để tiết kiệm thời gian chờ. Đặt lịch trước được ưu tiên và giảm thời gian chờ đợi.
                    </div>
                </details>
                <details class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                    <summary class="px-6 py-4 cursor-pointer font-semibold text-gray-900 hover:bg-gray-50 flex items-center justify-between">
                        <span>Bệnh viện có nhận bảo hiểm y tế không?</span>
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </summary>
                    <div class="px-6 pb-4 text-gray-600 text-sm">
                        Có, chúng tôi chấp nhận BHYT quốc gia và hầu hết các loại bảo hiểm tư nhân. Vui lòng mang theo thẻ BHYT còn hạn và đăng ký đúng tuyến để được hưởng quyền lợi tối đa.
                    </div>
                </details>
                <details class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                    <summary class="px-6 py-4 cursor-pointer font-semibold text-gray-900 hover:bg-gray-50 flex items-center justify-between">
                        <span>Tôi có thể lấy kết quả xét nghiệm khi nào?</span>
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </summary>
                    <div class="px-6 pb-4 text-gray-600 text-sm">
                        Thời gian trả kết quả tùy loại xét nghiệm: xét nghiệm thường (2-4 giờ), xét nghiệm đặc biệt (1-3 ngày), sinh thiết (5-7 ngày). Bạn có thể nhận kết quả trực tiếp hoặc xem online qua hệ thống.
                    </div>
                </details>
                <details class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                    <summary class="px-6 py-4 cursor-pointer font-semibold text-gray-900 hover:bg-gray-50 flex items-center justify-between">
                        <span>Người nhà có được ở lại chăm sóc bệnh nhân không?</span>
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </summary>
                    <div class="px-6 pb-4 text-gray-600 text-sm">
                        Có, mỗi bệnh nhân được phép có 1 người nhà ở lại chăm sóc. Người nhà cần đăng ký tại quầy điều dưỡng và tuân thủ nội quy của bệnh viện. Phòng VIP cho phép 2 người nhà.
                    </div>
                </details>
                <details class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                    <summary class="px-6 py-4 cursor-pointer font-semibold text-gray-900 hover:bg-gray-50 flex items-center justify-between">
                        <span>Bệnh viện có dịch vụ xe đưa đón không?</span>
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </summary>
                    <div class="px-6 pb-4 text-gray-600 text-sm">
                        Chúng tôi có dịch vụ xe cấp cứu 24/7 và xe đưa đón cho bệnh nhân nội trú xuất viện (có phí). Vui lòng liên hệ tổng đài để đặt lịch trước ít nhất 2 giờ.
                    </div>
                </details>
            </div>
        </section>

    </div>


    <div class="bg-gradient-to-r from-blue-600 to-cyan-600 text-white py-10 mt-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-2xl font-bold mb-3">Cần Hỗ Trợ Thêm?</h2>
            <p class="text-blue-100 mb-6">Đội ngũ chăm sóc khách hàng của chúng tôi luôn sẵn sàng hỗ trợ bạn</p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="tel:1900xxxx" class="bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-blue-50 transition-colors">
                    Gọi Hotline: 1900 0000
                </a>
                <a href="#" class="bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-800 transition-colors border border-blue-500">
                    Đặt Lịch Khám Online
                </a>
            </div>
        </div>
    </div>
<?php include "blocks/footer.php" ?>
</body>
</html>
