<?php
class Khoa extends Controller {

    // /Khoa hoặc /Khoa/SayHi → có thể cho về danh sách khoa hoặc trang chủ
    public function SayHi() {
        header("Location: /KLTN_Benhvien");
        exit;
    }

    // Xem chi tiết 1 khoa: /Khoa/ChiTiet/{MaKhoa}
    public function ChiTiet($maKhoa = "") {
        // Map MaKhoa -> tên file page tĩnh
        // sửa lại map này cho đúng MaKhoa trong database 
        $pageMap = [
            '1' => 'than_kinh',     // Khoa Thần kinh
            '2' => 'tim_mach',         // Khoa Tim mạch
            '3' => 'Noi_Tiet',       // Khoa Nội Tiết
            '4' => 'Ngoai_khoa',         // Khoa Ngoại
            '5' => 'Sanphu_khoa',         // Khoa Sản - Phụ
            '6' => 'Nhi_khoa',     // Khoa Nhi
            '7' => 'Mat_khoa',         // Khoa Mắt
            '8' => 'Rang_Ham_Mat',         // Khoa Răng - Hàm - Mặt
            '9' => 'tai_mui_hong',       // Khoa Tai - Mũi - Họng
            '10' => 'da_lieu',         // Khoa Da Liễu
            '11' => 'dinh_duong',         // Khoa dinh dưỡng
            // ... thêm các khoa khác
        ];

        if (!isset($pageMap[$maKhoa])) {
            // Nếu MaKhoa không tồn tại trong map → cho về 404 hoặc trang chung
            $this->view("layout_khoa", [  
                "Page" => "khoa_404"
            ]);
            return;
        }

        $this->view("layout_khoa", [    
            "Page"   => $pageMap[$maKhoa],  // ./mvc/views/pages/khoa_timmach.php ...
            "MaKhoa" => $maKhoa             // nếu muốn dùng trong view thì có sẵn
        ]);
    }
}
?>
