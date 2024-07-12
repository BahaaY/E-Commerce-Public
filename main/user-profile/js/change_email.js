$(document).ready(function() {

    $('input').attr('autocomplete','off');

    $("#btn_change_email").click(function() {

        btn_change_email_index=$("#btn_change_email");

        source_text_button=btn_change_email_index.html();
        parent_text_button="<div class='spinner-border text-dark'></div>";
        required_message=$("#key_required").val();

        alert_success_index=$("#alert-success-email");
        text_success_counter_index=$("#text-alert-success-email-counter");

        email = $("#email_change_email").val();

        if(email == undefined){
            email="";
        }

        $(this).html(source_text_button);
        $(this).removeAttr("disabled");
        alert_success_index.hide();

        if (is_valid_email(email) == 2) {
            $("#error-email").text(required_message);
        } else if (is_valid_email(email) == 0) {
            $("#error-email").text($("#key_enter_a_valid_email").val());
        } else {
            $("#error-email").empty();
        }

        token_index=$("#token");
        token=token_index.val();
        if(token == undefined || token == ""){
            return;
        }

        if (is_valid_email(email) == 1 && token!="") {

            btn_change_email_index.attr("disabled","disabled");
            btn_change_email_index.html(parent_text_button);

            data=new FormData();
            data.append('token', token);
            data.append('email', email);

            $.ajax({
                url: 'php_ajax/change_email.php',
                type: 'post',
                data: data,
                contentType: false,
                processData: false,
                success: function(output) {

                    btn_change_email_index.removeAttr("disabled");
                    btn_change_email_index.html(source_text_button);

                    $("#error-email").empty();

                    var obj = JSON.parse(output);

                    if (obj.res == 1) {

                        btn_change_email_index.attr("disabled","disabled");
                        $("#email_change_email").val("");

                        alert_success_index.show();
                        var count = 10;
                        var interval=setInterval(function() {
                            count--;
                            text_success_counter_index.html(count);
                            if(count == 0) {
                                clearInterval(interval);
                                window.location.href="../../authentication/?id="+obj.id+"&t="+obj.type;
                            }
                        }, 1000);

                    }else if (obj.res == 2){

                        $("#error-email").text($("#key_enter_a_valid_email").val());
                        
                    }else if (obj.res == 3){

                        $("#error-email").text($("#key_email_already_used").val());
                        
                    }else if (obj.res == 4){

                        $("#error-email").text("Can not send email verification, please try again after 2 minutes");
                        
                    } else{

                        alert_danger_index.show();
                        text_danger_index.text("Error occurred");

                        setTimeout(function() {
                            alert_danger_index.hide();
                        }, 3000);

                    }
                },
                error: function(error){
                    btn_change_email_index.removeAttr("disabled");
                    btn_change_email_index.html(source_text_button);
                    alert_danger_index.show();
                    text_danger_index.text("Error occurred");

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