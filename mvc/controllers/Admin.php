<?php
class Admin extends Controller{
    // public $HSModel;

    public function __construct(){
        // $this->HSModel = $this->model("mBN");
    }
    
    function SayHi(){
        // $mabn = $_SESSION['idbn'];
        //Models
        // $bn = $this->model("mBN");
        //Views
        $this->view("layoutadmin",[
            "Page"=>"admintongquan"
        ]);
    }

    function ThemNV(){
        $this->view("layoutadmin",[
            "Page"=>"adminthemnhanvien"
        ]);
    }
    function DoiMK(){
        $this->view("layoutadmin",[
            "Page"=>"admindoimatkhau"
        ]);
    }

    function ChanTruyCap(){
        $this->view("layoutadmin",[
            "Page"=>"adminchantruycap"
        ]);
    }
    
}
?>