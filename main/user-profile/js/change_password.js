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

    $("#btn_change_password").click(function() {
        
        btn_change_password_index=$("#btn_change_password");

        required_message = $("#key_required").val();
        source_text_button=btn_change_password_index.html();
        parent_text_button="<div class='spinner-border text-dark'></div>";

        current_password = $("#current-password").val();
        new_password = $("#new-password").val();
        confirm_new_password = $("#confirm-new-password").val();

        $(this).html(source_text_button);
        $(this).removeAttr("disabled");
        $(".alert-success-password").hide();
        $(".alert-danger-password").hide();

        if (is_valid_current_password(current_password) == 0) {
            $("#error-current-password").text(required_message);
        }else{
            $("#error-current-password").empty();
        }

        if (is_valid_new_password(new_password) == 3) {
            $("#error-new-password").text(required_message);
        } else if (is_valid_new_password(new_password) == 2) {
            $("#error-new-password").text($("#key_password_length").val());
        } else if (is_valid_new_password(new_password) == 0) {
            $("#error-new-password").text($("#key_password_validation").val());
        } else {
            $("#error-new-password").empty();
        }

        if (is_valid_confirm_new_password(new_password,confirm_new_password) == 0) {
            $("#error-confirm-new-password").text(required_message);
        }else if (is_valid_confirm_new_password(new_password,confirm_new_password) == 2) {
            $("#error-confirm-new-password").text($("#key_confirm_password_not_the_same").val());
        }else{
            $("#error-confirm-new-password").empty();
        }

        token_index=$("#token");
        token=token_index.val();
        if(token == undefined || token == ""){
            return;
        }

        if (is_valid_current_password(current_password) == 1 && is_valid_new_password(new_password) == 1 && is_valid_confirm_new_password(new_password,confirm_new_password) == 1 && token!="") {
            
            btn_change_password_index.attr("disabled","disabled");
            btn_change_password_index.html(parent_text_button);
            
            data = new FormData();

            data.append('current_password', current_password);
            data.append('new_password', new_password);
            data.append('token', token);

            $.ajax({
                url: 'php_ajax/change_password.php',
                type: 'post',
                data: data,
                contentType: false,
                processData: false,
                success: function(output) {

                    btn_change_password_index.removeAttr("disabled");
                    btn_change_password_index.html(source_text_button);

                    $("#error-current-password").empty();
                    $("#error-new-password").empty();
                    $("#error-confirm-new-password").empty();

                    var obj = JSON.parse(output);
                    if (obj.res == 1) {
                        $(".alert-success-password").show();
                        reset_inputs();
                        setTimeout(function() {
                            $(".alert-success-password").hide();
                        }, 3000);
                    }else if (obj.res == 2) {
                        $("#error-current-password").text($("#key_enter_your_current_password").val());
                    } else {
                        $(".alert-danger-password").show();
                        reset_inputs();
                        setTimeout(function() {
                            $(".alert-danger-password").hide();
                        }, 3000);
                    }
                },
                error: function(error){
                    btn_change_password_index.removeAttr("disabled");
                    btn_change_password_index.html(source_text_button);
                    $(".alert-danger-password").show();
                    reset_inputs();
                    setTimeout(function() {
                        $(".alert-danger-password").hide();
                    }, 3000);
                }
            });
        }
    });

    function is_valid_current_password(current_password){

        // Check if is empty
        if (current_password.length == 0) {
            return 0;
        }

        return 1;

    }

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

    function reset_inputs(){
        $("#current-password").val("");
        $("#new-password").val("");
        $("#confirm-new-password").val("");
    }

});