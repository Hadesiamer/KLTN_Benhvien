<?php 
class NVNT extends Controller {

    public function SayHi() {
        $nvnt = $this->model("mNVNT");

        // Trang hiện tại
        if (isset($_POST['page'])) {
            $page = (int)$_POST['page'];
        } else {
            $page = 1;
        }

        // Trạng thái lọc: all / Chua thanh toan / Da thanh toan / Huy
        if (isset($_POST['loc'])) {
            $loc = $_POST['loc'];
        } else {
            $loc = 'all';
        }

        // Từ khóa tìm kiếm: mã BN hoặc số điện thoại
        $keyword = '';
        if (isset($_POST['keyword'])) {
            $keyword = trim($_POST['keyword']);
        }

        // Tổng số đơn sau khi áp dụng lọc + tìm kiếm
        $totalInvoices = $nvnt->GetTotalInvoicesFiltered($loc, $keyword);
        $itemsPerPage  = 5;
        $pagination    = new Pagination($totalInvoices, $itemsPerPage, $page); 

        // Luôn dùng hàm lọc, loc = 'all' thì sẽ ra tất cả KE_DON, có áp dụng search
        $invoices = $nvnt->GetDTTheoLoc(
            $pagination->getOffset(), 
            $pagination->getLimit(), 
            $loc,
            $keyword
        );

        $this->view("layoutNVNT", [
            "Page"       => "donthuoc",
            "DT"         => $invoices,
            "Pagination" => $pagination,
            "loc"        => $loc,
            "keyword"    => $keyword
        ]);
    }

    public function CTDT() {
        $nvnt = $this->model("mNVNT");

        // Xác nhận thanh toán
        if (isset($_POST["nutXN"])) {
            $MaDT = $_POST["MaDT"];
            $ok   = $nvnt->xacNhanThanhToan($MaDT);

            $this->view("layoutNVNT", [
                "Page"   => "chitietdonthuoc",
                "CTDT"   => $nvnt->getCTDT($MaDT),
                "Thuoc"  => $nvnt->getThuoc($MaDT),
                "Result" => $ok ? 1 : 0
            ]);
            return;
        }

        // Hủy đơn thuốc
        if (isset($_POST["nutHuy"])) {
            $MaDT = $_POST["MaDT"];
            $ok   = $nvnt->huyDonThuoc($MaDT);

            $this->view("layoutNVNT", [
                "Page"   => "chitietdonthuoc",
                "CTDT"   => $nvnt->getCTDT($MaDT),
                "Thuoc"  => $nvnt->getThuoc($MaDT),
                "Result" => $ok ? 3 : 0
            ]);
            return;
        }

        // Xem chi tiết đơn thuốc (từ danh sách)
        if (isset($_POST["btnCTDT"])) {
            $MaDT = $_POST["ctdt"];

            $this->view("layoutNVNT", [
                "Page"  => "chitietdonthuoc",
                "CTDT"  => $nvnt->getCTDT($MaDT),
                "Thuoc" => $nvnt->getThuoc($MaDT)
            ]);
            return;
        }

        // Nếu không có gì, quay lại danh sách
        header("Location: /KLTN_Benhvien/NVNT");
        exit;
    }
}
?>
