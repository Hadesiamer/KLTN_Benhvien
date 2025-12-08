<?php
$cauHinhCa = isset($data["CauHinhCa"]) ? $data["CauHinhCa"] : [];
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-0">
            <i class="bi bi-gear text-primary"></i>
            Cấu hình ca làm việc
        </h4>
        <small class="text-muted">
            Gồm 2 ca cố định: <strong>Sáng</strong> và <strong>Chiều</strong>. 
            Bạn chỉ cần chỉnh khung giờ và <strong>giới hạn đi sớm / đi trễ</strong>,
            các chức năng điểm danh / thống kê sẽ tự đọc theo cấu hình này.
        </small>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <?php if (!empty($cauHinhCa)): ?>
            <form method="POST" action="./DD_CauHinhCa">
                <input type="hidden" name="action" value="save_all">
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width:40px;">#</th>
                                <th style="min-width:150px;">Ca làm việc</th>
                                <th style="min-width:130px;">Giờ bắt đầu</th>
                                <th style="min-width:130px;">Giờ kết thúc</th>
                                <th class="text-center" style="min-width:140px;">
                                    Giới hạn sớm<br>
                                    <small class="text-muted">(phút, cho phép đến sớm)</small>
                                </th>
                                <th class="text-center" style="min-width:140px;">
                                    Giới hạn trễ<br>
                                    <small class="text-muted">(phút, vẫn coi là đúng giờ)</small>
                                </th>
                                <th>Ghi chú</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cauHinhCa as $index => $ca): ?>
                                <?php
                                    $maCa      = (int)$ca["MaCa"];
                                    $caLamViec = $ca["CaLamViec"];
                                    $timeStart = substr($ca["GioBatDau"], 0, 5);
                                    $timeEnd   = substr($ca["GioKetThuc"], 0, 5);
                                    $ghiChu    = isset($ca["GhiChu"]) ? $ca["GhiChu"] : "";

                                    // Giá trị giới hạn sớm/trễ, default nếu null
                                    $gioiHanSom = isset($ca["GioiHanSomPhut"]) ? (int)$ca["GioiHanSomPhut"] : 15;
                                    $gioiHanTre = isset($ca["GioiHanTrePhut"]) ? (int)$ca["GioiHanTrePhut"] : 5;
                                ?>
                                <tr>
                                    <td><?php echo $index + 1; ?></td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($caLamViec); ?></strong>
                                        <input type="hidden"
                                               name="rows[<?php echo $maCa; ?>][CaLamViec]"
                                               value="<?php echo htmlspecialchars($caLamViec); ?>">
                                    </td>
                                    <td>
                                        <input type="time"
                                               name="rows[<?php echo $maCa; ?>][GioBatDau]"
                                               class="form-control form-control-sm"
                                               value="<?php echo htmlspecialchars($timeStart); ?>"
                                               required>
                                    </td>
                                    <td>
                                        <input type="time"
                                               name="rows[<?php echo $maCa; ?>][GioKetThuc]"
                                               class="form-control form-control-sm"
                                               value="<?php echo htmlspecialchars($timeEnd); ?>"
                                               required>
                                    </td>
                                    <td class="text-center">
                                        <input type="number"
                                               name="rows[<?php echo $maCa; ?>][GioiHanSomPhut]"
                                               class="form-control form-control-sm text-end d-inline-block"
                                               style="max-width: 90px;"
                                               min="0"
                                               step="1"
                                               value="<?php echo htmlspecialchars($gioiHanSom); ?>">
                                    </td>
                                    <td class="text-center">
                                        <input type="number"
                                               name="rows[<?php echo $maCa; ?>][GioiHanTrePhut]"
                                               class="form-control form-control-sm text-end d-inline-block"
                                               style="max-width: 90px;"
                                               min="0"
                                               step="1"
                                               value="<?php echo htmlspecialchars($gioiHanTre); ?>">
                                    </td>
                                    <td>
                                        <input type="text"
                                               name="rows[<?php echo $maCa; ?>][GhiChu]"
                                               class="form-control form-control-sm"
                                               value="<?php echo htmlspecialchars($ghiChu); ?>"
                                               placeholder="Ghi chú thêm (không bắt buộc)">
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Lưu cấu hình
                    </button>
                </div>
            </form>
        <?php else: ?>
            <div class="alert alert-warning mb-0">
                Chưa có cấu hình ca làm việc nào. 
                Vui lòng kiểm tra bảng <code>cauhinh_ca</code> (phải có 2 dòng CaLamViec = 'Sáng' và 'Chiều').
            </div>
        <?php endif; ?>
    </div>
</div>
