<?php
//Đây là file C:\xampp\htdocs\KLTN_Benhvien\mvc\views\pages\bn_danhsach_chat.php
// $data["DanhSachBacSi"] là mảng các bác sĩ BN có thể chat
$dsBacSi = isset($data["DanhSachBacSi"]) ? $data["DanhSachBacSi"] : [];
?>

<style>
    /* [NEW] UI danh sách bác sĩ chat cho bệnh nhân */
    .bn-chatlist-wrapper {
        max-width: 1100px;
        margin: 0 auto;
    }

    .bn-chatlist-title {
        font-size: 1.6rem;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .bn-chatlist-subtitle {
        font-size: 0.95rem;
        color: #6c757d;
        margin-bottom: 18px;
    }

    .bn-chatlist-empty-card {
        max-width: 640px;
        margin: 40px auto;
        border: none;
        border-radius: 12px;
    }

    .bn-chatlist-empty-icon {
        font-size: 44px;
        margin-bottom: 10px;
    }

    .bn-chatlist-doctor-card {
        border-radius: 12px;
        border: 1px solid #e0e0e0;
        transition: all 0.2s ease-in-out;
        height: 100%;
    }

    .bn-chatlist-doctor-card:hover {
        box-shadow: 0 6px 14px rgba(0,0,0,0.06);
        transform: translateY(-2px);
        border-color: #0d6efd;
    }

    .bn-chatlist-avatar {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: linear-gradient(135deg, #2563eb, #4f46e5);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 600;
        font-size: 18px;
        margin-right: 10px;
        flex-shrink: 0;
    }

    .bn-chatlist-doctor-name {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 3px;
    }

    .bn-chatlist-khoa-badge {
        display: inline-block;
        font-size: 0.75rem;
        padding: 2px 8px;
        border-radius: 999px;
        background-color: #eef2ff;
        color: #3730a3;
        margin-bottom: 4px;
    }

    .bn-chatlist-location {
        font-size: 0.85rem;
        color: #6b7280;
    }

    .bn-chatlist-footer {
        font-size: 0.8rem;
        color: #9ca3af;
    }

    @media (max-width: 768px) {
        .bn-chatlist-doctor-card {
            margin-bottom: 10px;
        }
    }
</style>

<div class="bn-chatlist-wrapper mt-2">
    <h3 class="bn-chatlist-title">Nhắn tin với bác sĩ</h3>
    <p class="bn-chatlist-subtitle">
        Bạn chỉ có thể nhắn tin với những bác sĩ đã có <strong>lịch khám online</strong> và
        <strong>đã thanh toán</strong>. Hệ thống sẽ tự động cập nhật danh sách này.
    </p>

    <?php if (empty($dsBacSi)): ?>
        <!-- [NEW] UI khi chưa có bác sĩ nào để chat -->
        <div class="card shadow-sm text-center p-4 bn-chatlist-empty-card">
            <div class="bn-chatlist-empty-icon">💬</div>
            <h5 class="mb-2">Hiện bạn chưa thể sử dụng chức năng chat</h5>
            <p class="text-muted mb-3" style="font-size: 0.95rem;">
                Bạn cần có ít nhất một lịch khám <strong>online</strong> đã thanh toán thì mới có thể mở
                cuộc trò chuyện với bác sĩ.
            </p>
            <a href="/KLTN_Benhvien/BN/LichKham" class="btn btn-primary px-4">
                Xem các lịch khám đã thanh toán
            </a>
        </div>
    <?php else: ?>
        <!-- [NEW] Grid card danh sách bác sĩ -->
        <div class="row mt-3">
            <?php foreach ($dsBacSi as $bs): ?>
                <?php
                    $maBS    = htmlspecialchars($bs["MaBS"]);
                    $tenBS   = htmlspecialchars($bs["TenBacSi"]);
                    $tenKhoa = htmlspecialchars($bs["TenKhoa"]);
                    $moTa    = htmlspecialchars($bs["MoTaKhoa"]);
                    // Lấy 2 ký tự đầu làm avatar text
                    $avatarText = mb_strtoupper(mb_substr($tenBS, 0, 1, "UTF-8"));
                ?>
                <div class="col-md-6 mb-3">
                    <div class="card bn-chatlist-doctor-card">
                        <div class="card-body d-flex flex-column h-100">
                            <div class="d-flex align-items-start mb-2">
                                <div class="bn-chatlist-avatar">
                                    <?php echo $avatarText; ?>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="bn-chatlist-doctor-name">
                                        BS. <?php echo $tenBS; ?>
                                    </div>
                                    <?php if ($tenKhoa !== ""): ?>
                                        <div class="bn-chatlist-khoa-badge">
                                            <?php echo $tenKhoa; ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($moTa !== ""): ?>
                                        <div class="bn-chatlist-location">
                                            <?php echo $moTa; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                <div class="bn-chatlist-footer">
                                    Nhắn tin hỗ trợ sau khi khám online.
                                </div>
                                <a class="btn btn-sm btn-primary"
                                   href="/KLTN_Benhvien/BN/ChatVoiBacSi/<?php echo urlencode($maBS); ?>">
                                    Mở chat
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
