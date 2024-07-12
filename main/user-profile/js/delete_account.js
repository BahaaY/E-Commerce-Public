$(document).ready(function() {

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

    $("#btn_delete_account").click(function() {

        btn_delete_account_index=$("#btn_delete_account");

        required_message = $("#key_required").val();
        source_text_button=btn_delete_account_index.html();
        parent_text_button="<div class='spinner-border text-dark'></div>";
    
        email = $("#email_delete_account").val();
        password = $("#password").val();
    
        $(this).html(source_text_button);
        $(this).removeAttr("disabled");
        $("#error-email-delete-account").empty();
        $("#error-password-delete-account").empty();
        $("#alert-danger-delete-account-error").hide();
        $("#alert-danger-delete-account").hide();
        $("#alert-danger-delete-account-cancel-orders").hide();
    
        if (is_valid_email(email) == 2) {
            $("#error-email-delete-account").text(required_message);
        } else if (is_valid_email(email) == 0) {
            $("#error-email-delete-account").text($("#key_enter_a_valid_email").val());
        } else {
            $("#error-email-delete-account").empty();
        }
    
        if (password == "") {
            $("#error-password-delete-account").text(required_message);
        }else{
            $("#error-password-delete-account").empty();
        }

        token_index=$("#token");
        token=token_index.val();
        if(token == undefined || token == ""){
            return;
        }
    
        if (is_valid_email(email) == 1 && password != "" && token!="") {
            
            btn_delete_account_index.attr("disabled","disabled");
            btn_delete_account_index.html(parent_text_button);
            
            data = new FormData();
    
            data.append('email', email);
            data.append('password', password);
            data.append('token', token);
    
            $.ajax({
                url: 'php_ajax/check_delete_account.php',
                type: 'post',
                data: data,
                contentType: false,
                processData: false,
                success: function(output) {
    
                    btn_delete_account_index.removeAttr("disabled");
                    btn_delete_account_index.html(source_text_button);
    
                    $("#error-email-delete-account").empty();
                    $("#error-password-delete-account").empty();
                    $("#alert-danger-delete-account-error").hide();
                    $("#alert-danger-delete-account").hide();
                    $("#alert-danger-delete-account-cancel-orders").hide();
    
                    var obj = JSON.parse(output);
                    if (obj.res == 2) {
                        $("#alert-danger-delete-account-cancel-orders").show();
                    }else if (obj.res == 1) {
                        $("#modal-ask-delete-account").modal('show');
                    }else if (obj.res == 3) {
                        $("#alert-danger-delete-account").show();
                    }else if (obj.res == 0) {
                        $("#alert-danger-delete-account-error").show();
                    }
                    setTimeout(function(){
                        $(".alert-danger-delete-account").hide();
                    },3000);
                },
                error: function(error){
                    btn_delete_account_index.removeAttr("disabled");
                    btn_delete_account_index.html(source_text_button);
                    $("#alert-danger-delete-account-error").show();
                    setTimeout(function(){
                        $("#alert-danger-delete-account-error").hide();
                    },3000);
                }
            });
        }
    });
    
    $("#btn_ask_delete_account").click(function() {
        
        btn_ask_delete_account_index=$("#btn_ask_delete_account");

        required_message = $("#key_required").val();
        source_text_button=btn_ask_delete_account_index.html();
        parent_text_button="<div class='spinner-border text-dark'></div>";
    
        $(this).html(source_text_button);
        $(this).removeAttr("disabled");
    
        $.ajax({
            url: 'php_ajax/delete_account.php',
            type: 'post',
            data: data,
            contentType: false,
            processData: false,
            success: function(output) {
                btn_ask_delete_account_index.attr("disabled","disabled");
                btn_ask_delete_account_index.html(parent_text_button);
                var obj = JSON.parse(output);
                if(obj.res == 1){
                    setTimeout(function(){
                        window.location.href="../products";
                    },3000);
                }else{
                    $("#modal-ask-delete-account").modal('hide');
                    ("#alert-danger-delete-account-error").show();
                    setTimeout(function(){
                        $(".alert-danger-delete-account").hide();
                    },3000);
                }
                
            }
        });
    });

    $("#close_modal_ask_delete_account").click(function() {
        $("#modal-ask-delete-account").modal('hide');
    });

});

function is_valid_email(email) {

    // Check if is empty
    if (email.length == 0) {
        return 2;
    }

    //Check if contain @ and . ( @ before . )
    if (!/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(email)) {
        return 0;
    }

    // If all the checks passed, the email is strong
    return 1;

}