$(document).ready(function() {
    let stt = 0;
    const checkSdt = () => {
        if (!$("#txtuser").val()) {
            $(".sdt-mes").html("Không được bỏ trống");
            return false;
        }
        const regex = /[0-9]{10}$/;
        if (regex.test($("#txtuser").val())) {
            $(".sdt-mes").html("(*)");
            return true;
        } else {
            $(".sdt-mes").html("Không hợp lệ");
            return false;
        }
    };
    const checkPass = () => {
        const password = $("#password").val();
        if(!password){
            $(".pass1").html("Không được bỏ trống");
            return false;
        }
        if(password.length < 8){
            $(".pass1").html("Mật khẩu ít nhất 8 kí tự");
            return false;
        }
        const regex = /[A-Za-z0-9]$/;
        if(regex.test(password)){
            $(".pass1").html("(*)");
            return true;
        } else {
            $(".pass1").html("Mật khẩu không hợp lệ");
            return false;
        }
    };
    const checkPass12 = () => {
        const password = $("#password").val();
        const password2 = $("#password2").val();
    
        if (!password2) {
            $(".pass2").html("Không được bỏ trống");
            return false;
        }
    
        if (password !== password2) {
            $(".pass2").html("Mật khẩu không khớp");
            return false;
        }
        $(".pass2").html("(*)");
        return true;
    };
    $("#txtuser").blur(checkSdt);
    $("#password").blur(checkPass);
    $("#password2").blur(checkPass12);
    
});




