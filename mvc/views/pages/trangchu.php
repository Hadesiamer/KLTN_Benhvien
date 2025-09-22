<?php
    $dt = json_decode($data['BS'], true);
    $dt2 = json_decode($data['CK'], true);
?>


<div class="listbs">
    
        <div class="booking">
                <h1>Đặt khám bác sĩ</h1>
                <p>Phiếu khám kèm số thứ tự và thời gian của bạn được xác nhận.</p>
                <div class="list" id="doctorList">
                <?php foreach ($dt as $r): ?>
                    <div class="bs_card">
                        <a href="" style="text-decoration: none">
                        <img src="public/img/<?=$r["HinhAnh"]?>" alt="" class="image">
                        <div class="bs_info">
                            <h2 class="name"><?=$r["HovaTen"]?></h2>
                            <p class="department"><?=$r["TenKhoa"]?></p>
                        </div>
                        </a>
                    </div>
                <?php endforeach; ?>
                </div>
                <button class="see-more-button">Xem thêm ></button>
                <button id="scrollLeftDoctor" class="scroll-button" aria-label="Scroll left">&lt;</button>
                <button id="scrollRightDoctor" class="scroll-button" aria-label="Scroll right">&gt;</button>
            </div>
        
        </div>
        <div class="listpk">
            <div class="booking">
                    <h1>Đặt khám chuyên khoa</h1>
                    <p>Đa dạng phòng khám với nhiều chuyên khoa khác nhau như Sản - Nhi, Tai Mũi họng, Da Liễu, Tiêu Hoá...</p>
                    <div class="list" id="pkList">
                    <?php foreach ($dt2 as $r): ?>
                        <div class="bv_card">
                            <a href="" style="text-decoration: none">
                                <img src="public/img/<?=$r["img"]?>" alt="" class="image2"> <!-- Chỗ này để ảnh 300x150 dùm nhé-->
                                <div class="pk_info">
                                    <h2 class="name" style="text-align:center;"><?=$r["TenKhoa"]?></h2>
                                </div>
                            </a>
                        </div>  
                    <?php endforeach; ?>   
                    </div>
                    <button class="see-more-button">Xem thêm ></button>
                    <button id="scrollLeftPK" class="scroll-button" aria-label="Scroll left">&lt;</button>
                    <button id="scrollRightPK" class="scroll-button" aria-label="Scroll right">&gt;</button>
            </div>
        </div>
        <div class="new" >
        <h1>Tin tức y tế</h1>
        <div class="news-grid container" style="background: #E6E6FA;">
            <article class="news-card">
                <img src="public/img/loangxuong.png" alt="Thuốc NextG Cal" class="news-image">
                <div class="news-content">
                    <h2 class="news-title">Thuốc NextG Cal: Bổ sung canxi và điều trị loãng xương</h2>
                    <p class="news-description">Thuốc PM NextG Cal là thuốc được sản xuất bởi công ty Probiotec Pharma. Thuốc NextG cal có công dụng chính là bổ sung canxi trong trường hợp thiếu canxi, giúp điều trị loãng xương,...</p>
                    <a href="#" class="read-more">Read More</a>
                </div>
            </article>
            <article class="news-card">
                <img src="public/img/velaxin.png" alt="Thuốc Velaxin" class="news-image">
                <div class="news-content">
                    <h2 class="news-title">Thuốc Velaxin là thuốc gì? Công dụng, cách dùng và lưu ý khi sử dụng</h2>
                    <p class="news-description">Velaxin được sử dụng trong trường hợp điều trị các cơn trầm cảm chủ yếu (Major depressive disorder) và sử dụng duy trì để phòng ngừa tái phát các cơn trầm cảm nặng. Ngoài ra...</p>
                    <a href="#" class="read-more">Read More</a>
                </div>
            </article>
            <article class="news-card">
                <img src="public/img/nhiemkhuan.png" alt="Klamentin" class="news-image">
                <div class="news-content">
                    <h2 class="news-title">Klamentin là thuốc gì? Công dụng, cách dùng và lưu ý khi sử dụng</h2>
                    <p class="news-description">Klamentin là thuốc kháng sinh được dùng trong điều trị nhiễm khuẩn. Vậy thuốc Klamentin được sử dụng cụ thể trong trường hợp nào? Cách sử dụng thuốc hợp lý ra sao?</p>
                    <a href="#" class="read-more">Read More</a>
                </div>
            </article>
            <article class="news-card">
                <img src="public/img/nhiemkhuan2.png" alt="LevoDHG" class="news-image">
                <div class="news-content">
                    <h2 class="news-title">LevoDHG 750 là thuốc gì? Công dụng, cách dùng và lưu ý khi sử dụng</h2>
                    <p class="news-description">LevoDHG 750 là thuốc kháng sinh được dùng trong điều trị nhiễm khuẩn. Vậy thuốc LevoDHG được sử dụng cụ thể trong trường hợp nào? Cách sử dụng thuốc hợp lý ra sao?</p>
                    <a href="#" class="read-more">Read More</a>
                </div>
            </article>
            <article class="news-card">
                <img src="public/img/clabact.png" alt="Clabact" class="news-image">
                <div class="news-content">
                    <h2 class="news-title">Clabact là thuốc gì? Công dụng, cách dùng và lưu ý khi sử dụng</h2>
                    <p class="news-description">Clabact là thuốc kháng sinh được dùng trong điều trị nhiễm khuẩn. Vậy thuốc Clabact được sử dụng cụ thể trong trường hợp nào? Cách sử dụng thuốc hợp lý ra sao? Cùng Dược...</p>
                    <a href="#" class="read-more">Read More</a>
                </div>
            </article>
            <article class="news-card">
                <img src="public/img/thuoc1.png" alt="Thuốc Klamentin" class="news-image">
                <div class="news-content">
                    <h2 class="news-title">Thuốc Klamentin gói là thuốc gì? Công dụng, cách dùng và lưu ý sử dụng</h2>
                    <p class="news-description">Cốm pha hỗn dịch uống Klamentin, hay thường được gọi là thuốc Klamentin gói, là thuốc kháng sinh được dùng trong điều trị nhiễm khuẩn. Vậy thuốc Klamentin được sử dụng...</p>
                    <a href="#" class="read-more">Read More</a>
                </div>
            </article>
            <article class="news-card">
                <img src="public/img/thuocla.png" alt="Thuốc lá dẫn đến" class="news-image">
                <div class="news-content">
                    <h2 class="news-title">Thuốc lá dẫn đến hơn 100.000 ca tử vong mỗi năm tại Việt Nam</h2>
                    <p class="news-description">Thuốc lá là nguyên nhân dẫn đến hơn 100.000 ca tử vong mỗi năm tại Việt Nam, chiếm 15% tổng số ca tử vong...</p>
                    <a href="#" class="read-more">Read More</a>
                </div>
            </article>
            <article class="news-card">
                <img src="public/img/baohiem.png" alt="Quy định BHYT" class="news-image">
                <div class="news-content">
                    <h2 class="news-title">Quy định BHYT mới tạo nhiều thuận lợi cho người bệnh ung thư</h2>
                    <p class="news-description">Bộ Y tế đã ban hành Thông tư số 39 sửa đổi, bổ sung một số điều của Thông tư số 35 ngày 28/9/2016 của Bộ trưởng Bộ Y tế ...</p>
                    <a href="#" class="read-more">Read More</a>
                </div>
            </article>
        </div>
        </div>