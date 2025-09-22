<?php
class BN extends Controller{
    public $HSModel;

    public function __construct(){
        $this->HSModel = $this->model("mBN");
    }
    
    function SayHi(){
        $mabn = $_SESSION['idbn'];
        //Models
        $bn = $this->model("mBN");
        //Views
        $this->view("layoutBN",[
            "Page"=>"thongtinbn",
            "TT"=>$bn->get1BN($mabn)
        ]);
    }

    public function DKXN(){
        $bn = $this->model("mBN");
        $mabn = $_SESSION['idbn'];

        $this->view("layoutDKXN",[
            "DKXN"=>$bn->get1BN($mabn)
        ]);
        if(isset($_POST["confirm"])){
            $mabn = $_SESSION["idbn"];
            $dayxn = $_POST["ngayxn"];
            $gioxn = $_POST["gioxn"];
            $ngayxn = $dayxn . ' ' . $gioxn;
            $ketqua = $_POST["kqxn"];
            $loaixn = $_POST["loaixn"];

            $result = $bn->getDKXN($ngayxn, $ketqua, $loaixn, $mabn);

            $this->view("layoutDKXN",[
                "DKXN"=>$bn->get1BN($mabn)
            ]);
        }
    }
    function UDTT(){
        $udbn = $this->model("mBN");
        if(isset($_POST["btnUDTT"])){
            // Call models
            $udbn = $this->model("mBN");
            $mabn = $_SESSION['idbn'];
            // Call Views
            
            $this->view("layoutBN",[
                "Page"=>"udthongtinbn",
                "UD"=>$udbn->get1BN($mabn)
            ]);
            
        }
        if (isset($_POST["btn-updatebn"])) {
            $mabn = $_SESSION["idbn"];
            $tenbn = $_POST["hovaten"];
            $gioitinh = $_POST["gt"];
            $ngaysinh = $_POST["ngaysinh"];
            $diachi = $_POST["diachi"];
            $email = $_POST["email"];
            $bhyt = $_POST["bhyt"];
        
            $result = $udbn->UpdateBN($mabn, $tenbn, $gioitinh, $ngaysinh, $diachi, $email, $bhyt);
            $resultData = json_decode($result, true); 
        
            if ($resultData['success']) {
                $_SESSION["ten"] = $tenbn;
            }
            $this->view("layoutBN", [
                "Page" => "udthongtinbn",
                "UD" => $udbn->get1BN($mabn),
                "XL" => $resultData
            ]);
        }
    }
}
?>