$(document).keydown(function (e) {
  
    if (e.which == 13) {
        e.preventDefault();
        $('#btn_login').click();
    }
});

$(document).ready(function(){

    $('input').attr('autocomplete','off');

    $(".input-group-addon-password").on('click', function(event) {
        event.preventDefault();
        if($('#show_hide_password input').attr("type") == "text"){
            $('#show_hide_password input').attr('type', 'password');
            $('#show_hide_password i').addClass( "fa-eye-slash" );
            $('#show_hide_password i').removeClass( "fa-eye" );
        }else if($('#show_hide_password input').attr("type") == "password"){
            $('#show_hide_password input').attr('type', 'text');
            $('#show_hide_password i').removeClass( "fa-eye-slash" );
            $('#show_hide_password i').addClass( "fa-eye" );
        }
    });

    $("#btn_google").click(function(){
        window.location.href="../../google_auth";
    });

    $("#btn_facebook").click(function(){
        window.location.href="../../facebook_auth";
    });

    $("#btn_login").click(function(){

        btn_login_index=$("#btn_login");

        source_text_button=btn_login_index.html();
        parent_text_button="<div class='spinner-border text-dark'></div>";
        required_message=$("#key_required").val();

        email_index=$("#email");
        password_index=$("#password");
        remember_me_index=$("#chk_remember_me");
        alert_success_index=$("#alert-success");
        alert_danger_index=$("#alert-danger");
        text_danger_index=$("#text-danger");

        email_error_index=$("#error-email");
        password_error_index=$("#error-password");

        email=email_index.val();
        password=password_index.val();

        if(email == undefined){
            email="";
        }

        if(password == undefined){
            password="";
        }

        $(this).html(source_text_button);
        $(this).removeAttr("disabled");
        alert_danger_index.hide();
        alert_success_index.hide();

        check_is_valid_email=is_valid_email(email);
        check_is_valid_password=is_valid_password(password);

        if(check_is_valid_email == 0){
            email_error_index.text($("#key_enter_a_valid_email").val());
        }else if(check_is_valid_email == 2){
            email_error_index.text(required_message);
        }else{
            email_error_index.empty();
        }

        if(check_is_valid_password == 0){
            password_error_index.text(required_message);
        }else{
            password_error_index.empty();
        }

        remember=0;
        if(remember_me_index.is(":checked")){
            remember=1;
        }else{
            remember=0;
        }

        if(check_is_valid_email == 1 && check_is_valid_password == 1){

            btn_login_index.attr("disabled","disabled");
            btn_login_index.html(parent_text_button);

            data=new FormData();
            data.append("email",email);
            data.append("password",password);
            data.append("remember",remember);

            $.ajax({
                url:'php_ajax/login.php',
                type:'post',
                data:data,
                contentType:false,
                processData:false,
                success:function(output) {
                    
                    btn_login_index.removeAttr("disabled");
                    btn_login_index.html(source_text_button);

                    var obj=JSON.parse(output); 

                    if(obj.error_email!=""){
                        email_error_index.append(obj.error_email);
                    }

                    if(obj.error_password!=""){
                        password_error_index.append(obj.error_password);
                    }

                    if(obj.error_email=="" && obj.error_password==""){

                        if(obj.error!=1){

                            if(obj.success == 1){
                                btn_login_index.attr("disabled","disabled");
                                window.location.href="../";
                            }

                            if(obj.success == 2){

                                btn_login_index.attr("disabled","disabled");
                                email_index.val("");
                                password_index.val("");

                                $(".alert-success").show();
                                var count = 10;
                                var interval=setInterval(function() {
                                    count--;
                                    $("#text-alert-success-counter").html(count);
                                    if(count == 0) {
                                        clearInterval(interval);
                                        window.location.href="../../authentication/?id="+obj.id+"&t="+obj.type;
                                    }
                                }, 1000);

                            }

                            if(obj.msg!=""){
                                alert_danger_index.show();
                                text_danger_index.text(obj.msg);
                                btn_login_index.removeAttr("disabled");
                                btn_login_index.html(source_text_button);
                                reset_inputs(password_index);
                                setTimeout(function() {
                                    alert_danger_index.hide();
                                }, 3000);
                            }

                        }else{

                            alert_danger_index.show();
                            text_danger_index.text("Error occurred");
                            btn_login_index.removeAttr("disabled");
                            btn_login_index.html(source_text_button);
                            reset_inputs(password_index);
                            setTimeout(function() {
                                alert_danger_index.hide();
                            }, 3000);

                        }
                        
                    }
                },
                error: function(error){
                    btn_login_index.removeAttr("disabled");
                    btn_login_index.html(source_text_button);
                    alert_danger_index.show();
                    text_danger_index.text("Error occurred");
                    reset_inputs(password_index);
                    setTimeout(function() {
                        alert_danger_index.hide();
                    }, 3000);
                }
            });

        }

    });

    function is_valid_email(email){

        // Check if is empty
        if(email.length == 0){
            return 2;
        }
    
        //Check if contain @ and . ( @ before . )
        if (!/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(email)) {
            return 0;
        }
    
        // If all the checks passed, the email is strong
        return 1;
            
    }

    function is_valid_password(password){

        // Check if is empty
        if(password.length == 0){
            return 0;
        }
          
        // If all the checks passed, the password is strong
        return 1;
            
    }

    function reset_inputs(password_index){

        password_index.val("");
    
    }

});
