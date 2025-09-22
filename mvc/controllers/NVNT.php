<?php
    class NVNT extends Controller{
        public function SayHi() {
            $nvnt = $this->model("mNVNT");
            if(isset($_POST['btnPage']))
            {
                
                $page = $_POST['page'];
            }
            else
            {
                $page = 1;
            }
            
            $totalInvoices = $nvnt->GetTotalInvoices();
            $itemsPerPage = 5;
            $pagination = new Pagination($totalInvoices, $itemsPerPage, $page); 
            if(isset($_POST['btnLoc']))
            {
                $loc = $_POST['loc'];
                $invoices = $nvnt->GetDTTheoLoc($pagination->getOffset(), $pagination->getLimit(), $loc);
                
            }
            else if (isset($_POST['btnPage']))
            {
                $loc = $_POST['loc'];
                $invoices = $nvnt->GetDTTheoLoc($pagination->getOffset(), $pagination->getLimit(), $loc);
            }
            else
            {
                $loc = "d.TrangThai";
                $invoices = $nvnt->GetDT($pagination->getOffset(), $pagination->getLimit());
            }
            $this->view("layoutNVNT", [
                "Page" => "donthuoc",
                "DT" => $invoices,
                "Pagination" => $pagination,
                "loc" => $loc
            ]);
        }
        public function CTDT(){
            if(isset($_POST["btnCTDT"])){
                $MaDT = $_POST["ctdt"];
                $nvnt = $this->model("mNVNT");

                $this->view("layoutNVNT",[
                    "Page"=>"chitietdonthuoc",
                    "CTDT" => $nvnt->getCTDT($MaDT),
                    "Thuoc" => $nvnt->getThuoc($MaDT)
                ]);
            }
            if(isset($_POST["nutXN"]))
            {
                $TT = "'Completed'";
                $MaDT = $_POST["MaDT"];
                $nvnt = $this->model("mNVNT");
                
                $rs = $nvnt->setPTTT($MaDT,$TT);
                $this->view("layoutNVNT",[
                    "Page"=>"chitietdonthuoc",
                    "CTDT" => $nvnt->getCTDT($MaDT),
                    "Thuoc" => $nvnt->getThuoc($MaDT),
                    "Result"=> $rs
                ]);
            }
            if(isset($_POST["nutHuy"]))
            {
                $TT = "'Cancelled'";
                $MaDT = $_POST["MaDT"];
                $nvnt = $this->model("mNVNT");
                if($nvnt->setPTTT($MaDT,$TT))
                {
                    $rs = 3;
                }
                $this->view("layoutNVNT",[
                    "Page"=>"chitietdonthuoc",
                    "CTDT" => $nvnt->getCTDT($MaDT),
                    "Thuoc" => $nvnt->getThuoc($MaDT),
                    "Result"=> $rs
                ]);
            }
        }
    }
?>