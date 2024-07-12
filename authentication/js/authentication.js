$(document).keydown(function (e) {
  
    if (e.which == 13) {
        e.preventDefault();
        $('#btn_verify').click();
    }
    
});

$(document).ready(function() {

    $('input').attr('autocomplete','off');

    $("#btn_verify").click(function(){

        hide_all_alert_text();

        parent_text_button="<div class='spinner-border text-dark'></div>";
        btn_verify_index=$("#btn_verify");
        btn_resend_email_index=$("#btn_resend_email");
        
        source_text_button=btn_verify_index.text();

        btn_verify_index.html(source_text_button);
        btn_verify_index.removeAttr("disabled");
        btn_resend_email_index.removeAttr("disabled");

        index_alert_danger=$("#alert-danger");
        index_alert_danger.hide();

        index_digit1=$("#digit1");
        index_digit2=$("#digit2");
        index_digit3=$("#digit3");
        index_digit4=$("#digit4");
        index_digit5=$("#digit5");
        index_digit6=$("#digit6");

        digit1=index_digit1.val();
        digit2=index_digit2.val();
        digit3=index_digit3.val();
        digit4=index_digit4.val();
        digit5=index_digit5.val();
        digit6=index_digit6.val();

        if(digit1 == undefined){
            digit1 == "";
        }

        if(digit2 == undefined){
            digit2 == "";
        }

        if(digit3 == undefined){
            digit3 == "";
        }

        if(digit4 == undefined){
            digit4 == "";
        }

        if(digit5 == undefined){
            digit5 == "";
        }

        if(digit6 == undefined){
            digit6 == "";
        }

        verification_code=digit1+digit2+digit3+digit4+digit5+digit6;
        id=$("#id").val();
        type=$("#type").val();
        if(id == undefined){
            id="";
        }
        if(type == undefined){
            type="";
        }

        if(verification_code.length == 6 && id != "" && type != ""){

            btn_verify_index.attr("disabled","disabled");
            btn_verify_index.html(parent_text_button);

            data=new FormData();
            data.append("verification_code",verification_code);
            data.append("id",id);
            data.append("type",type);

            $.ajax({
                url:'php_ajax/authentication.php',
                type:'post',
                data:data,
                contentType:false,
                processData:false,
                success:function(output) {

                    btn_verify_index.removeAttr("disabled");
                    btn_resend_email_index.removeAttr("disabled");
                    btn_verify_index.html(source_text_button);

                    reset_inputs(index_digit1,index_digit2,index_digit3,index_digit4,index_digit5,index_digit6);

                    var obj=JSON.parse(output);

                    if(obj.error == 0){

                        if(obj.status == 0){ //Email verification Register & Login

                            btn_verify_index.attr("disabled","disabled");
                            btn_resend_email_index.attr("disabled","disabled");
                            reset_inputs(index_digit1,index_digit2,index_digit3,index_digit4,index_digit5,index_digit6);
                            disable_inputs(index_digit1,index_digit2,index_digit3,index_digit4,index_digit5,index_digit6);

                            $(".alert-success").show();
                            $(".text-alert-success-account-verification").show();
                            var count = 10;
                            var interval=setInterval(function() {
                                count--;
                                $("#text-alert-success-counter-account-verification").html(count);
                                if(count == 0) {
                                    clearInterval(interval);
                                    window.location.href="../main";
                                }
                            }, 1000);
                        }else if(obj.status == 1){ //Change Email

                            btn_verify_index.attr("disabled","disabled");
                            btn_resend_email_index.attr("disabled","disabled");
                            reset_inputs(index_digit1,index_digit2,index_digit3,index_digit4,index_digit5,index_digit6);
                            disable_inputs(index_digit1,index_digit2,index_digit3,index_digit4,index_digit5,index_digit6);

                            $(".alert-success").show();
                            $(".text-alert-success-update-email").show();
                            var count = 10;
                            var interval=setInterval(function() {
                                count--;
                                $("#text-alert-success-counter-update-email").html(count);
                                if(count == 0) {
                                    clearInterval(interval);
                                    window.location.href="../main/user-profile/";
                                }
                            }, 1000);
                        }else if(obj.status == 2){ //Reset password email

                            btn_verify_index.attr("disabled","disabled");
                            btn_resend_email_index.attr("disabled","disabled");
                            reset_inputs(index_digit1,index_digit2,index_digit3,index_digit4,index_digit5,index_digit6);
                            disable_inputs(index_digit1,index_digit2,index_digit3,index_digit4,index_digit5,index_digit6);

                            window.location.href="../reset_password/?id="+obj.id;
                        }

                    }else{

                        index_alert_danger.show();
                        index_alert_danger.text("Error occurred");

                        setTimeout(function() {
                            index_alert_danger.hide();
                        }, 3000);

                    }

                    if(obj.msg_error != ""){
                        index_alert_danger.show();
                        index_alert_danger.text(obj.msg_error);

                        setTimeout(function() {
                            index_alert_danger.hide();
                        }, 3000);
                    }

                },
                error:function(error) {

                    btn_verify_index.removeAttr("disabled");
                    btn_resend_email_index.removeAttr("disabled");
                    btn_verify_index.html(source_text_button);

                    index_alert_danger.show();
                    index_alert_danger.text("Error occurred");

                    reset_inputs(index_digit1,index_digit2,index_digit3,index_digit4,index_digit5,index_digit6);

                    setTimeout(function() {
                        index_alert_danger.hide();
                    }, 3000);
                    
                }
            });

        }else{

            index_alert_danger.show();
            index_alert_danger.text("Enter a valid code");
            
            setTimeout(function() {
                index_alert_danger.hide();
            }, 3000);
            
        }

    });

    $("#btn_resend_email").click(function(){

        source_text_button="Send code again";
        parent_text_button="<div class='spinner-border text-dark'></div>";
        btn_resend_email_index=$("#btn_resend_email");

        btn_resend_email_index.html(source_text_button);
        btn_resend_email_index.removeAttr("disabled");

        index_alert_danger=$("#alert-danger");
        index_alert_danger.hide();

        id=$("#id").val();
        type=$("#type").val();
        if(type == undefined){
            type="";
        }
        if(id == undefined){
            id="";
        }

        if(id != "" && type != ""){

            btn_resend_email_index.attr("disabled","disabled");
            btn_resend_email_index.html(parent_text_button);

            data=new FormData();
            data.append("id",id);
            data.append("type",type);

            $.ajax({
                url:'php_ajax/resend_email.php',
                type:'post',
                data:data,
                contentType:false,
                processData:false,
                success:function(output) {

                    btn_resend_email_index.removeAttr("disabled");
                    btn_resend_email_index.html(source_text_button);

                    var obj=JSON.parse(output);

                    if(obj.error == 0){

                        if(obj.msg == ""){

                            $(".alert-success").show();
                            $(".text-alert-success-resend-email").show();
    
                            setTimeout(function() {
                                $(".alert-success").hide();
                            }, 3000);

                        }else{

                            index_alert_danger.show();
                            index_alert_danger.text(obj.msg);

                            setTimeout(function() {
                                index_alert_danger.hide();
                            }, 3000);

                        }

                    }else{

                        index_alert_danger.show();
                        index_alert_danger.text("Error occurred");

                        setTimeout(function() {
                            index_alert_danger.hide();
                        }, 3000);

                    }

                },
                error:function(error) {

                    btn_resend_email_index.removeAttr("disabled");
                    btn_resend_email_index.html(source_text_button);

                    index_alert_danger.show();
                    index_alert_danger.text("Error occurred");

                    setTimeout(function() {
                        index_alert_danger.hide();
                    }, 3000);
                    
                }
            });

        }else{

            index_alert_danger.show();
            index_alert_danger.text("Error occurred");

            setTimeout(function() {
                index_alert_danger.hide();
            }, 3000);
            
        }

    });

    function reset_inputs(index_digit1,index_digit2,index_digit3,index_digit4,index_digit5,index_digit6){
        index_digit1.val("");
        index_digit2.val("");
        index_digit3.val("");
        index_digit4.val("");
        index_digit5.val("");
        index_digit6.val("");
        index_digit1.select().focus();
    }

    function disable_inputs(index_digit1,index_digit2,index_digit3,index_digit4,index_digit5,index_digit6){
        index_digit1.attr("disabled","disabled");
        index_digit2.attr("disabled","disabled");
        index_digit3.attr("disabled","disabled");
        index_digit4.attr("disabled","disabled");
        index_digit5.attr("disabled","disabled");
        index_digit6.attr("disabled","disabled");
    }

    $(function() {
        
        'use strict';
    
        var body = $('body');
    
        function goToNextInput(e) {
            var key = e.which,
            t = $(e.target),
            sib = t.next('input'),
            prevSib = t.prev('input');
            
            if (key !== 9 && key !== 8 && key !== 13 && key !== 37 && key !== 39 && (key < 48 || (key > 57 && key < 96) || key > 105)) {
                e.preventDefault();
                return false;
            }
    
            if (key === 9) {
                return true;
            }

            if (key === 13) {
                return false;
            }
    
            if ((key === 8 || key === 37)) {
                if(t.val() === ''){
                    if (prevSib && prevSib.length) {
                        prevSib.select().focus();
                        return false;
                    }else{
                        return true;
                    }
                }else{
                    prevSib.select().focus();
                    return false;
                }
            }
    
            if (!sib || !sib.length) {
                sib = body.find('input').eq(0);
            }
    
            sib.select().focus();
        }
    
        function onKeyDown(e) {
            var key = e.which;
    
            if (key === 9 || key === 8 || key === 13 || key === 37 && key === 39 || (key >= 48 && key <= 57) || (key >= 96 && key <= 105)) {
                return true;
            }
    
            e.preventDefault();
            return false;
        }
    
        function onFocus(e) {
            $(e.target).select();
        }
    
        body.on('keyup', 'input', goToNextInput);
        body.on('keydown', 'input', onKeyDown);
        body.on('click', 'input', onFocus);
    });    
    
    
});

function hide_all_alert_text(){
    $(".text-alert-success-account-verification").hide();
    $(".text-alert-success-update-email").hide();
    $(".text-alert-success-resend-email").hide();
}