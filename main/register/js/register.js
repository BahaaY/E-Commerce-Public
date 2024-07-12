$(document).keydown(function (e) {
  
    if (e.which == 13) {
        e.preventDefault();
        $('#btn_register').click();
    }
});

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

    $(".input-group-addon-confirm-password").on('click', function(event) {
        event.preventDefault();
        if($('#show_hide_confirm_password input').attr("type") == "text"){
            $('#show_hide_confirm_password input').attr('type', 'password');
            $('#show_hide_confirm_password i').addClass( "fa-eye-slash" );
            $('#show_hide_confirm_password i').removeClass( "fa-eye" );
        }else if($('#show_hide_confirm_password input').attr("type") == "password"){
            $('#show_hide_confirm_password input').attr('type', 'text');
            $('#show_hide_confirm_password i').removeClass( "fa-eye-slash" );
            $('#show_hide_confirm_password i').addClass( "fa-eye" );
        }
    });

    $("#country").select2({
        allowClear:true,
        templateResult: function(item) {
            return format(item, false);
        }
    });

    $("#btn_google").click(function(){
        window.location.href="../../google_auth";
    });

    $("#btn_facebook").click(function(){
        window.location.href="../../facebook_auth";
    });

    $("#btn_register").click(function() {

        btn_register_index=$("#btn_register");

        source_text_button=btn_register_index.html();
        parent_text_button="<div class='spinner-border text-dark'></div>";
        required_message=$("#key_required").val();

        $(this).html(source_text_button);
        $(this).removeAttr("disabled");
        
        country = $("#country option:selected").text();

        email = $("#email").val();
        password = $("#password").val();
        confirm_password = $("#confirm-password").val();

        if(country == undefined){
            country="";
        }

        if(email == undefined){
            email="";
        }

        if(password == undefined){
            password="";
        }

        if(confirm_password == undefined){
            confirm_password="";
        }

        if (is_valid_country(country) == 0) {
            $("#error-country").text(required_message);
        }else {
            $("#error-country").empty();
        }

        if (is_valid_email(email) == 2) {
            $("#error-email").text(required_message);
        } else if (is_valid_email(email) == 0) {
            $("#error-email").text($("#key_enter_a_valid_email").val());
        } else {
            $("#error-email").empty();
        }

        if (is_valid_password(password) == 3) {
            $("#error-password").text(required_message);
        } else if (is_valid_password(password) == 2) {
            $("#error-password").text($("#key_password_length").val());
        } else if (is_valid_password(password) == 0) {
            $("#error-password").text($("#key_password_validation").val());
        } else {
            $("#error-password").empty();
        }

        if (is_valid_confirm_password(password,confirm_password) == 0) {
            $("#error-confirm-password").text(required_message);
        }else if (is_valid_confirm_password(password,confirm_password) == 2) {
            $("#error-confirm-password").text($("#key_confirm_password_not_the_same").val());
        }else{
            $("#error-confirm-password").empty();
        }

        if (is_valid_email(email) == 1 && is_valid_password(password) == 1 && is_valid_confirm_password(password,confirm_password) == 1 && is_valid_country(country) == 1) {

            btn_register_index.attr("disabled","disabled");
            btn_register_index.html(parent_text_button);
            
            data = new FormData();
            data.append('country', country);
            data.append('email', email);
            data.append('password', password);

            $.ajax({
                url: 'php_ajax/register.php',
                type: 'post',
                data: data,
                contentType: false,
                processData: false,
                success: function(output) {
                    
                    btn_register_index.removeAttr("disabled");
                    btn_register_index.html(source_text_button);

                    var obj = JSON.parse(output);

                    if (obj.res == 1) {
                        
                        btn_register_index.attr("disabled","disabled");
                        
                        reset_inputs();

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
                        
                    } else if (obj.res == 2) {

                        $("#error-email").text($("#key_email_already_used").val());

                    } else {

                        $(".alert-danger").show();
                        $("#text-alert-danger").text("Error occurred");
                        setTimeout(() => {
                            $(".alert-danger").hide();
                        }, 3000);

                    }

                },
                error: function(error){
                    btn_register_index.removeAttr("disabled");
                    btn_register_index.html(source_text_button);
                    $(".alert-danger").show();
                    $("#text-alert-danger").text("Error occurred");
                    setTimeout(() => {
                        $(".alert-danger").hide();
                    }, 3000);
                }
            });

        }

    });

    function reset_inputs(){

        $("#email").val("");
        $("#password").val("");
        $("#confirm-password").val("");
        $('#country option').prop('selected',false).trigger( "change" );
    
    }

    function is_valid_country(country) {

        // Check if is empty
        if (country.length == 0) {
            return 0;
        }
    
        // If all the checks passed, the email is strong
        return 1;
    
    }

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
    
    function is_valid_password(password) {
    
        // Check if is empty
        if (password.length == 0) {
            return 3;
        }
    
        // Check for number of characters
        if (password.length < 6) {
            return 2;
        }
    
        // Check for at least one uppercase letter
        if (!/[A-Z]/.test(password)) {
            return 0;
        }
    
        // Check for at least one lowercase letter
        if (!/[a-z]/.test(password)) {
            return 0;
        }
    
        // Check for at least one special character
        if (!/[\W]/.test(password)) {
            return 0;
        }
    
        // Check for at least one digit (number)
        if (!/[0-9]/.test(password)) {
            return 0;
        }
    
        // Check that the password doesn't contain spaces
        if (/\s/.test(password)) {
            return 0;
        }
    
        // If all the checks passed, the password is strong
        return 1;
    
    }

    function is_valid_confirm_password(password,confirm_password){

        // Check if is empty
        if (confirm_password.length == 0) {
            return 0;
        }

        if(password!=confirm_password && confirm_password.length != 0){
            return 2;
        }

        return 1;

    }

    function format(item, state) {
        if (!item.id) {
            return item.text;
        }
        var countryUrl = "https://hatscripts.github.io/circle-flags/flags/";
        var stateUrl = "https://oxguy3.github.io/flags/svg/us/";
        var url = state ? stateUrl : countryUrl;
        var img = $("<img>", {
            class: "img-flag",
            width: 26,
            src: url + item.element.value.toLowerCase() + ".svg"
        });
        var span = $("<span>", {
            text: " " + item.text
        });
        span.prepend(img);
        return span;
    }

});