<?php

class NVYT extends Controller{

    // Must have SayHi()
    function SayHi(){
        $nvyt = $this->model("mNVYT");
        if(isset($_POST['btnPage']))
            {
                
                $page = $_POST['page'];
            }
            else
            {
                $page = 1;
            }
            $totalInvoices = $nvyt->GetTotalInvoices();
            $itemsPerPage = 5;
            $pagination = new Pagination($totalInvoices, $itemsPerPage, $page);
        if(isset($_POST['btnLoc']))
        {
            $loc = $_POST['loc'];
            $invoices = $nvyt->GetHDTheoLoc($pagination->getOffset(), $pagination->getLimit(), $loc);
            
        }
        else if (isset($_POST['btnPage']))
        {
            $loc = $_POST['loc'];
            $invoices = $nvyt->GetHDTheoLoc($pagination->getOffset(), $pagination->getLimit(), $loc);
        }
        else
        {
            $loc = "h.TrangThai";
            $invoices = $nvyt->GetHD($pagination->getOffset(), $pagination->getLimit());
        }    
        // Call Views
        $this->view("layoutNVYT",[
            "Page"=>"hoadon",
            "HD" => $invoices,
            "Pagination" => $pagination,
            "loc" => $loc
        ]);

    }
    function CTHD(){
        if(isset($_POST["btnCTHD"])){
            $MaHD = $_POST["cthd"];
            // Call models
            $nvyt = $this->model("mNVYT");
            // Call Views
            $this->view("layoutNVYT",[
                "Page"=>"chitiethoadon",
                "CTHD" => $nvyt->getCTHD($MaHD),
                "TT" => $nvyt->getPTTT(),
            ]);
        }   
        if(isset($_POST["nutXN"]))
        {
            $TT = "'Completed'";
            $id = $_POST["paymentOption"];
            $MaHD = $_POST["MaHD"];
            $nvyt = $this->model("mNVYT");
            
            $rs = $nvyt->setPTTT($MaHD,$id);
            $rs = $nvyt->setTrangThai($MaHD,$TT);
            $this->view("layoutNVYT",[
                "Page"=>"chitiethoadon",
                "CTHD" => $nvyt->getCTHD($MaHD),
                "TT" => $nvyt->getPTTT(),
                "Result"=> $rs
            ]);
        }
        if(isset($_POST["nutHuy"]))
        {
            $TT = "'Cancelled'";
            $MaHD = $_POST["MaHD"];
            $nvyt = $this->model("mNVYT");
            if($nvyt->setTrangThai($MaHD,$TT))
            {
                $rs = 3;
            }
            $this->view("layoutNVYT",[
                "Page"=>"chitiethoadon",
                "CTHD" => $nvyt->getCTHD($MaHD),
                "TT" => $nvyt->getPTTT(),
                "Result"=> $rs
            ]);
        }
            
        
    }
    function LichKham(){
        $nvyt = $this->model("mNVYT");
        if(isset($_POST['btnPage']))
            {
                
                $page = $_POST['page'];
            }
            else
            {
                $page = 1;
            }
            $totalInvoices = $nvyt->GetTotalInvoicesLK();
            $itemsPerPage = 5;
            $pagination = new Pagination($totalInvoices, $itemsPerPage, $page);
        if(isset($_POST['btnLoc']))
        {
            $loc = $_POST['loc'];
            $invoices = $nvyt->GetLKTheoLoc($pagination->getOffset(), $pagination->getLimit(), $loc);
            
        }
        else if (isset($_POST['btnPage']))
        {
            $loc = $_POST['loc'];
            $invoices = $nvyt->GetLKTheoLoc($pagination->getOffset(), $pagination->getLimit(), $loc);
        }
        else
        {
            $loc = date('Y-m-d');
            $invoices = $nvyt->GetLKTheoLoc($pagination->getOffset(), $pagination->getLimit(), $loc);
        }    
        $this->view("layoutNVYT", [
            "Page" => "DSKL",
            "Pagination" => $pagination,
            "DanhSachKham" => $invoices,
            "loc" => $loc
        ]);
    }
    public function CTLK()
    {
        if(isset($_POST["btnCTLK"]))
        {
            $MaLK = $_POST["ctlk"];
            $nvyt = $this->model("mNVYT");
            $this->view("layoutNVYT",[
                "Page"=>"chitietlichkham",
                "CTLK" => $nvyt->getCTLK($MaLK),
            ]);
        }
        if(isset($_POST["btnTDLK"]))
        {
            $MaLK = $_POST["MaLK"];
            $NGAYKHAM = $_POST["NgayKham"];
            $GIOKHAM = $_POST["GioKham"];
            $nvyt = $this->model("mNVYT");
            $rs = $nvyt->ThayDoiLK($MaLK, $NGAYKHAM, $GIOKHAM);
            $this->view("layoutNVYT",[
                "Page"=>"chitietlichkham",
                "CTLK" => $nvyt->getCTLK($MaLK),
                "Result" => $rs
            ]);
        }
    }
}
?>