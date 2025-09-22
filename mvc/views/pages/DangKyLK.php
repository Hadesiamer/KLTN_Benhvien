
<link rel="stylesheet" href="./public/css/dangkylk.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<div class="col-4">
<div class="search-bar">
<form id="searchForm" method="POST" action="/KLTN_Benhvien/DangKyLK">
    <div class="search-container">
        <input type="text" name="searchTerm" id="searchSpecialty" 
               placeholder="Tìm kiếm khoa khám nhanh..." 
               value="<?= isset($_POST['searchTerm']) ? $_POST['searchTerm'] : ''; ?>" 
               onkeydown="if(event.key === 'Enter'){this.form.submit();}">
    </div>
</form>


</div>

<div class="specialties">
        <h3>Chuyên khoa:</h3>
        <div id="specialtyList" style="max-height: 400px; overflow-y: auto;">
            <form method="POST" action="/KLTN_Benhvien/DangKyLK">
            <?php
            if (is_array($data["CK"])) {
                $data["CK"] = json_encode($data["CK"]);  
            }
                $chuyenKhoaList = json_decode($data["CK"], true); 
            ?>
                <?php if (isset($chuyenKhoaList) && !empty($chuyenKhoaList)): ?>
                    <?php foreach ($chuyenKhoaList as $ck): ?>
                        <div>
                            <input type="radio" name="MaKhoa" value="<?= $ck['MaKhoa']; ?>" 
                                   onchange="this.form.submit();" 
                                   <?= (isset($_POST['MaKhoa']) && $_POST['MaKhoa'] === $ck['MaKhoa']) ? 'checked' : ''; ?> >
                            <label><?= $ck['TenKhoa']; ?></label>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Không tìm thấy chuyên khoa nào</p>
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>


<div class="col-8">
<h3>Danh sách bác sĩ</h3>
    <div class="specialties">
        <?php
        if (is_array($data["BS"])) {
            $data["BS"] = json_encode($data["BS"]);  
        }
        $bacsiList = json_decode($data["BS"], true); 
        ?>
        <?php if (isset($bacsiList) && !empty($bacsiList)): ?>
            
            <div id="specialtyList" style="max-height: 470px; overflow-y: auto;">
            <div class="danh-sach-bac-si">
                <?php foreach ($bacsiList as $bs): ?>
                    <div class="row align-items-center mb-3">
                        <div class="col-3">
                            <img src="./public/img/<?= $bs['HinhAnh'];?>" alt="Hình bác sĩ" class="img-fluid rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
                        </div>
                        <div class="col-5">
                            <p class="mb-0"><strong>BS.<?= $bs['HovaTen']; ?></strong></p>
                        </div>
                        <div class="col-4 text-end">
                            <form method="POST" action="/KLTN_Benhvien/DangKyLK">
                                <button type="submit" name="MaBS" value="<?= $bs['MaNV']; ?>" class="btn btn-primary">Đặt khám</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            </div>
            
        <?php else: ?>
            <p>Vui lòng chọn chuyên khoa để xem danh sách bác sĩ!</p>
        <?php endif; ?>
    </div>
</div>
<script>
   

</script>