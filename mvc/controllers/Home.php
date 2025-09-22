<?php

// http://localhost/live/Home/Show/1/2

class Home extends Controller{

    // Must have SayHi()
    function SayHi(){
        $home = $this->model("mQLBS");
        $ck = $this->model("mQuanLy");
        $this->view("layout1",[
            "Page"=>"trangchu",
            "BS"=> $home->GetAllBS(),
            "CK"=> $ck->GetKhoa()
        ]);
    }

    
}
?>

