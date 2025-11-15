<?php
    $dt = json_decode($data['BS'], true);
    $dt2 = json_decode($data['CK'], true);
?>


<div class="listbs">
        <div class="formdangky">
            <?php include "./mvc/views/pages/formDK_KhamBenh.php" ?>
        </div>

        <div class="booking">
                <h1>Bác sĩ tiêu biểu</h1>
                <p>Một số bác sĩ tiêu biểu của bệnh viện chúng tôi.</p>
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
                
            </div>
        
        </div>
                <!-- Đoạn này Nhàn xóa, tui mở lại -->
        <div class="listpk">
            <div class="booking_chuyenkhoa">
                <h1>Chuyên khoa chúng tôi</h1>
                <p>Đa dạng phòng khám với nhiều chuyên khoa khác nhau như Sản - Nhi, Tai Mũi họng, Da Liễu, Tiêu Hoá...</p>
                <div class="list" id="pkList">
                <?php foreach ($dt2 as $r): ?>
                    <div class="bv_card">
                        <!-- THAY href="" bằng đường dẫn tới controller Khoa -->
                        <a href="/KLTN_Benhvien/Khoa/ChiTiet/<?= htmlspecialchars($r["MaKhoa"]) ?>" style="text-decoration: none">
                            <img src="public/img/<?= htmlspecialchars($r["img"]) ?>" alt="" class="image2">
                            <div class="pk_info">
                                <h2 class="name" style="text-align:center;"><?= htmlspecialchars($r["TenKhoa"]) ?></h2>
                            </div>
                        </a>
                    </div>  
                <?php endforeach; ?>   
                </div>
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
</div>
<style>
    .listbs{
        display: block;
        flex-direction: column;
        align-items: center;
        margin-bottom: 50px;
    }
    .formdangky{
        width: 33%;
        padding: 20px;
        background-color: #f5f5f5;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        font-family: Arial, sans-serif;
        float: left;
        /* margin: 20px; */
        margin: 20px 10px 20px 20px;
    }
    .booking{
        width: 62%;
        padding: 20px;
        background-color: #f5f5f5;
        border-radius: 8px;     
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        font-family: Arial, sans-serif;
        float: right;
        /* margin: 20px; */
        margin: 20px 20px 20px 10px;
    }

    .booking_chuyenkhoa{
        width: 98%;
        padding: 20px;
        background-color: #fbfbfbff;
        border-radius: 8px;     
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        font-family: Arial, sans-serif;
        float: right;
        margin-top: 60px;
        position: center;
    }
    #doctorList{
        margin-top: 80px;
    }

    .new {
        clear: both; 
        margin-top: 20px;
        padding: 20px;
    }
</style>