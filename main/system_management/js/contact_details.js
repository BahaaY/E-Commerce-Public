function edit_contact_details(){
    
    btn_edit=$("#btn_edit_contact_details");

    source_text_button=btn_edit.html();
    parent_text_button="<div class='spinner-border text-dark'></div>";
    required_message=$("#key_required").val();

    index_contact_address=$("#contact_address");
    index_contact_phone_number=$("#contact_phone_number");
    index_contact_email=$("#contact_email");

    index_error_address=$("#error_address");
    index_error_phone_number=$("#error_phone_number");
    index_error_email=$("#error_email");

    index_alert_success=$("#alert-success-contact-details");
    index_alert_danger=$("#alert-danger-contact-details");

    index_alert_success.hide();
    index_alert_danger.hide();

    index_error_address.empty();
    index_error_phone_number.empty();
    index_error_email.empty();

    address=index_contact_address.val();
    phone_number=index_contact_phone_number.val();
    email=index_contact_email.val();

    if(address == undefined){
        address="";
    }

    if(phone_number == undefined){
        phone_number="";
    }

    if(email == undefined){
        email="";
    }

    token_index=$("#token");
    token=token_index.val();
    if(token == undefined || token == ""){
        return;
    }

    //btn_edit.html(source_text_button);
    //btn_edit.removeAttr("disabled");

    // if(address == ""){
    //     index_error_address.text(required_message);
    // }else{
    //     index_error_address.text("");
    // }
    
    // if(phone_number == ""){
    //     index_error_phone_number.text(required_message);
    // }else{
    //     index_error_phone_number.text("");
    // }
    
    // if(email == ""){
    //     index_error_email.text(required_message);
    // }else{
    //     index_error_email.text("");
    // }

    //if(address != "" && phone_number != "" && email != ""){

        btn_edit.attr("disabled","disabled");
        btn_edit.html(parent_text_button);

        data=new FormData();
        data.append("address",address);
        data.append("phone_number",phone_number);
        data.append("email",email);
        data.append("token",token);

        $.ajax({
            url:'php_ajax/edit_contact_details.php',
            type:'post',
            data:data,
            contentType:false,
            processData:false,
            success:function(output) {

                btn_edit.removeAttr("disabled");
                btn_edit.html(source_text_button);

                var obj=JSON.parse(output); 
                if(obj.res == 1){
                    index_alert_success.show();
                    setTimeout(function(){
                        index_alert_success.hide();
                    },3000);
                }else{
                    index_alert_danger.show();
                    setTimeout(function(){
                        index_alert_danger.hide();
                    },3000);
                }
            },
            error: function(error){
                btn_edit.removeAttr("disabled");
                btn_edit.html(source_text_button);
                index_alert_danger.show();
                setTimeout(function(){
                    index_alert_danger.hide();
                },3000);
            }
        });

    //}

}