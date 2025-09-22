<?php
$dt = json_decode($data["CTPK"], true);
foreach ($dt as $data):
echo'<div class="container" id="container">
        <div class="card" id="card">
            <form action="/KLTN_Benhvien/QuanLy/TTBN" method="POST">
                <input type="hidden" name="back" value="' . $data['MaBN'] . '">
                <input type="submit" name="nutBack" value="Back" class="btn-back">
            </form>
            <div class="card-header text-center py-3" id="card-header" style=" border-radius: 5px;">
                <h4 class="mb-0">PHIẾU KHÁM BỆNH</h4>
            </div>
            <div class="card-body" id="card-body">
                <div class="row mb-4" id="row">
                    <div class="col-md-6" id="col-left">
                        <h5 class="section-title" id="patient-info-title">THÔNG TIN BỆNH NHÂN</h5>
                        <div class="info-row row" id="info-row">
                            <div class="col-sm-4 info-label">Họ và Tên:</div>
                            <div class="col-sm-8">' . ($data['HovaTen']) . '</div>
                        </div>
                        <div class="info-row row" id="info-row">
                            <div class="col-sm-4 info-label">Ngày sinh:</div>
                            <div class="col-sm-8">' . ($data['NgaySinh']) . '</div>
                        </div>
                        <div class="info-row row" id="info-row">
                            <div class="col-sm-4 info-label">Giới tính:</div>
                            <div class="col-sm-8">' . ($data['GioiTinh']) . '</div>
                        </div>
                        <div class="info-row row" id="info-row">
                            <div class="col-sm-4 info-label">Địa chỉ:</div>
                            <div class="col-sm-8">' . ($data['DiaChi']) . '</div>
                        </div>
                        <div class="info-row row" id="info-row">
                            <div class="col-sm-4 info-label">Số điện thoại:</div>
                            <div class="col-sm-8">' . ($data['SoDT']) . '</div>
                        </div>
                    </div>
                    <div class="col-md-6" id="col-right">
                        <h5 class="section-title" id="exam-info-title">THÔNG TIN KHÁM BỆNH</h5>
                        <div class="info-row row" id="info-row">
                            <div class="col-sm-4 info-label">Mã phiếu khám:</div>
                            <div class="col-sm-8">' . ($data['MaPK']) . '</div>
                        </div>
                        <div class="info-row row" id="info-row">
                            <div class="col-sm-4 info-label">ID bệnh nhân:</div>
                            <div class="col-sm-8">' . ($data['MaBN']) . '</div>
                        </div>
                        <div class="info-row row" id="info-row">
                            <div class="col-sm-4 info-label">BHYT:</div>
                            <div class="col-sm-8">' . ($data['BHYT']) . '</div>
                        </div>
                        <div class="info-row row" id="info-row">
                            <div class="col-sm-4 info-label">Ngày khám:</div>
                            <div class="col-sm-8">' . ($data['NgayTaoPhieuKham']) . '</div>
                        </div>
                        <div class="info-row row" id="info-row">
                            <div class="col-sm-4 info-label">Bác sĩ:</div>
                            <div class="col-sm-8">' . ($data['BacSiPhuTrach']) . '</div>
                        </div>
                    </div>
                </div>

                <h5 class="section-title" id="exam-results-title">KẾT QUẢ KHÁM VÀ CHUẨN ĐOÁN</h5>
                <div class="info-row row" id="info-row">
                    <div class="col-sm-3 info-label">Chỉ định xét nghiệm:</div>';
                    if($data['MaXN'] == null){
                        echo '<div class="col-sm-9">Không</div>
                    </div>';
                    }
                    else if($data['MaXN'] != null){
                        echo '<div class="col-sm-9">' .$data['LoaiXN']. '</div>
                    </div>
                    <div class="info-row row" id="info-row">
                        <div class="col-sm-3 info-label">Kết quả xét nghiệm:</div>
                        <div class="col-sm-9">' .$data['KetQuaXN']. '</div>
                    </div>';
                    }
                echo'
                <div class="info-row row" id="info-row">
                    <div class="col-sm-3 info-label">Chuẩn đoán:</div>
                    <div class="col-sm-9">' . ($data['KetQua']) . '</div>
                </div>

                <h5 class="section-title mt-4" id="prescription-title">Đơn thuốc và Lời dặn</h5>
                <div class="info-row row" id="info-row">
                    <div class="col-sm-3 info-label">Đơn thuốc:</div>
                    <div class="col-sm-9">' . ($data['DonThuoc']) . '</div>
                </div>
                <div class="info-row row" id="info-row">
                    <div class="col-sm-3 info-label">Lời dặn:</div>
                    <div class="col-sm-9">' . ($data['LoiDan']) . '</div>
                </div>
                <div class="text-center mt-5" id="doctor-signature">
                    <div class="row">
                        <div class="col-8"></div>
                        <div class="col-4 text-right">
                            <p><strong>Ngày: </strong>' . date("d/m/Y") . '</p>
                            <p><strong>Bác sĩ phụ trách</strong></p>
                            <div style="height: 100px; width: 300px; margin: 0 auto;"></div>
                            <p>(Ký và ghi rõ họ tên)</p>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-4">
                    <button onclick="window.print();" class="btn btn-primary">In phiếu khám</button>
                </div>
            </div>
        </div>
    </div>';
endforeach;
?>