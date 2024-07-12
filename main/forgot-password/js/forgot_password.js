$(document).keydown(function (e) {
  
    if (e.which == 13) {
        e.preventDefault();
        $('#btn_send').click();
    }
});

$(document).ready(function() {

    $('input').attr('autocomplete','off');

    $("#btn_send").click(function() {
        
        btn_send_index=$("#btn_send");

        source_text_button=btn_send_index.html();
        parent_text_button="<div class='spinner-border text-dark'></div>";
        required_message=$("#key_required").val();

        alert_danger_index=$("#alert-danger");
        text_danger_index=$("#text-danger");

        email = $("#email").val();

        if(email == undefined){
            email="";
        }

        $(this).html(source_text_button);
        $(this).removeAttr("disabled");
        alert_danger_index.hide();

        if (is_valid_email(email) == 2) {
            $("#error-email").text(required_message);
        } else if (is_valid_email(email) == 0) {
            $("#error-email").text($("#key_enter_a_valid_email").val());
        } else {
            $("#error-email").empty();
        }

        if (is_valid_email(email) == 1) {

            btn_send_index.attr("disabled","disabled");
            btn_send_index.html(parent_text_button);

            data=new FormData();
            data.append('email', email);

            $.ajax({
                url: 'php_ajax/forgot_password.php',
                type: 'post',
                data: data,
                contentType: false,
                processData: false,
                success: function(output) {
                    
                    btn_send_index.removeAttr("disabled");
                    btn_send_index.html(source_text_button);

                    var obj = JSON.parse(output);

                    if (obj.res == 1) {

                        btn_send_index.attr("disabled","disabled");
                        $("#email").val("");

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

                    } else if (obj.res == 2){

                        $("#error-email").text($("#key_email_not_exist").val());
                        
                    } else if (obj.res == 3){

                        $("#error-email").text($("#key_enter_a_valid_email").val());
                        
                    } else if (obj.res == 4){

                        alert_danger_index.show();
                        text_danger_index.text($("#key_account_blocked").val());

                        setTimeout(function() {
                            alert_danger_index.hide();
                        }, 3000);
                        
                    } else if (obj.res == 5){

                        alert_danger_index.show();
                        text_danger_index.text($("#key_error_login_limit").val());

                        setTimeout(function() {
                            alert_danger_index.hide();
                        }, 3000);
                        
                    } else if (obj.res == 6){

                        alert_danger_index.show();
                        text_danger_index.text("Can not send email verification, please try again after 2 minutes");

                        setTimeout(function() {
                            alert_danger_index.hide();
                        }, 3000);
                        
                    }  else if (obj.res == 0){

                        alert_danger_index.show();
                        text_danger_index.text($("#key_error_occurred").val());

                        setTimeout(function() {
                            alert_danger_index.hide();
                        }, 3000);

                    }
                },
                error: function(error){
                    btn_send_index.removeAttr("disabled");
                    btn_send_index.html(source_text_button);
                    alert_danger_index.show();
                    text_danger_index.text($("#key_error_occurred").val());
                    setTimeout(function() {
                        alert_danger_index.hide();
                    }, 3000);
                }
            });
        }
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

});