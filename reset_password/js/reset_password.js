$(document).keydown(function (e) {
  
    if (e.which == 13) {
        e.preventDefault();
        $('#btn_reset').click();
    }
});

$(document).ready(function() {

    $('input').attr('autocomplete','off');

    $(".input-group-addon-new-password").on('click', function(event) {
        event.preventDefault();
        if($('#show_hide_new_password input').attr("type") == "text"){
            $('#show_hide_new_password input').attr('type', 'password');
            $('#show_hide_new_password i').addClass( "fa-eye-slash" );
            $('#show_hide_new_password i').removeClass( "fa-eye" );
        }else if($('#show_hide_new_password input').attr("type") == "password"){
            $('#show_hide_new_password input').attr('type', 'text');
            $('#show_hide_new_password i').removeClass( "fa-eye-slash" );
            $('#show_hide_new_password i').addClass( "fa-eye" );
        }
    });

    $(".input-group-addon-confirm-new-password").on('click', function(event) {
        event.preventDefault();
        if($('#show_hide_confirm_new_password input').attr("type") == "text"){
            $('#show_hide_confirm_new_password input').attr('type', 'password');
            $('#show_hide_confirm_new_password i').addClass( "fa-eye-slash" );
            $('#show_hide_confirm_new_password i').removeClass( "fa-eye" );
        }else if($('#show_hide_confirm_new_password input').attr("type") == "password"){
            $('#show_hide_confirm_new_password input').attr('type', 'text');
            $('#show_hide_confirm_new_password i').removeClass( "fa-eye-slash" );
            $('#show_hide_confirm_new_password i').addClass( "fa-eye" );
        }
    });

    $("#btn_reset").click(function(){
        
        btn_reset_password_index=$("#btn_reset");

        source_text_button=btn_reset_password_index.html();
        parent_text_button="<div class='spinner-border text-dark'></div>";
        required_message="Required";
        
        id=$("#id").val();
        new_password = $("#new-password").val();
        confirm_new_password = $("#confirm-new-password").val();

        if(id == undefined){
            id="";
        }

        if(new_password == undefined){
            new_password="";
        }

        if(confirm_new_password == undefined){
            confirm_new_password="";
        }

        $(this).html(source_text_button);
        $(this).removeAttr("disabled");
        $(".alert-danger").hide();

        if (is_valid_new_password(new_password) == 3) {
            $("#error-new-password").text(required_message);
        } else if (is_valid_new_password(new_password) == 2) {
            $("#error-new-password").text("Password must be at least 6 characters");
        } else if (is_valid_new_password(new_password) == 0) {
            $("#error-new-password").text("Password must be a combination of numbers, letters (lowercase, uppercase) and punctuation marks (such as ! and &)");
        } else {
            $("#error-new-password").empty();
        }

        if (is_valid_confirm_new_password(new_password,confirm_new_password) == 0) {
            $("#error-confirm-new-password").text(required_message);
        }else if (is_valid_confirm_new_password(new_password,confirm_new_password) == 2) {
            $("#error-confirm-new-password").text("New password and confirm new password does not match");
        }else{
            $("#error-confirm-new-password").empty();
        }

        if(is_valid_new_password(new_password) == 1 && is_valid_confirm_new_password(new_password,confirm_new_password) == 1 && id != ""){

            btn_reset_password_index.attr("disabled","disabled");
            btn_reset_password_index.html(parent_text_button);
            
            data = new FormData();
            data.append('id', id);
            data.append('new_password', new_password);

            $.ajax({
                url: 'php_ajax/reset_password.php',
                type: 'post',
                data: data,
                contentType: false,
                processData: false,
                success: function(output) {
                    
                    btn_reset_password_index.removeAttr("disabled");
                    btn_reset_password_index.html(source_text_button);

                    var obj = JSON.parse(output);

                    if (obj.res == 1) {

                        $("#new-password").val("");
                        $("#confirm-new-password").val("");
                        
                        btn_reset_password_index.attr("disabled","disabled");
                        btn_reset_password_index.html(source_text_button);

                        $(".alert-success").show();
                        var count = 10;
                        var interval=setInterval(function() {
                            count--;
                            $("#text-alert-success-counter").html(count);
                            if(count == 0) {
                                clearInterval(interval);
                                window.location.href = "../main/login";
                            }
                        }, 1000);

                    } else {

                        $(".alert-danger").show();
                        $("#text-alert-danger").text("Error occurred");

                        setTimeout(function() {
                            $(".alert-danger").fadeOut('fast');
                        }, 3000);

                    }

                },
                error: function(error){

                    btn_reset_password_index.removeAttr("disabled");
                    btn_reset_password_index.html(source_text_button);
                    $(".alert-danger").show();
                    $("#text-alert-danger").text("Error occurred");

                    setTimeout(function() {
                        $(".alert-danger").fadeOut('fast');
                    }, 3000);
                    
                }
            });

        }

    });

    function is_valid_new_password(new_password) {
    
        // Check if is empty
        if (new_password.length == 0) {
            return 3;
        }
    
        // Check for number of characters
        if (new_password.length < 6) {
            return 2;
        }
    
        // Check for at least one uppercase letter
        if (!/[A-Z]/.test(new_password)) {
            return 0;
        }
    
        // Check for at least one lowercase letter
        if (!/[a-z]/.test(new_password)) {
            return 0;
        }
    
        // Check for at least one special character
        if (!/[\W]/.test(new_password)) {
            return 0;
        }
    
        // Check for at least one digit (number)
        if (!/[0-9]/.test(new_password)) {
            return 0;
        }
    
        // Check that the password doesn't contain spaces
        if (/\s/.test(new_password)) {
            return 0;
        }
    
        // If all the checks passed, the password is strong
        return 1;
    
    }

    function is_valid_confirm_new_password(new_password,confirm_new_password){

        // Check if is empty
        if (confirm_new_password.length == 0) {
            return 0;
        }

        if(new_password!=confirm_new_password && confirm_new_password.length != 0){
            return 2;
        }

        return 1;

    }

});