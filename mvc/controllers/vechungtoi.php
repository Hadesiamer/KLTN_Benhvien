<?php
class vechungtoi extends Controller {

    // Hàm khởi tạo (nếu bạn cần load model, có thể để trống)
    public function __construct() {
        // $this->vechungtoiModel = $this->model("mVechungtoi");
    }

    // Hàm mặc định (bắt buộc phải có)
    public function SayHi() {
        // Gọi view “layoutvechungtoi”
        $this->view("layoutvechungtoi", []);
    }
}
?>
