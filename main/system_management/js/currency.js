function edit_currency(currency_id){
    
    btn_edit=$("#btn_edit_currency_"+currency_id);
    
    source_text_button=btn_edit.html();
    parent_text_button="<div class='spinner-border text-dark'></div>";
    required_message=$("#key_required").val();

    index_rate=$("#currency_rate_"+currency_id);
    index_availability=$("#currency_availability_"+currency_id);
    index_alert_success=$("#alert-success-currency");
    index_alert_danger=$("#alert-danger-currency");

    index_error_currency_rate=$("#error_currency_rate_"+currency_id);

    index_alert_success.hide();
    index_alert_danger.hide();
    index_error_currency_rate.empty();

    rate=index_rate.val();
    availability=index_availability.val();

    if(rate == undefined){
        rate="";
    }

    if(availability == undefined){
        availability="";
    }

    btn_edit.html(source_text_button);
    btn_edit.removeAttr("disabled");

    if(rate == ""){
        index_error_currency_rate.text(required_message);
    }else{
        index_error_currency_rate.text("");
    }

    token_index=$("#token");
    token=token_index.val();
    if(token == undefined || token == ""){
        return;
    }

    if(rate != "" && availability != "" && currency_id != "" && token != ""){

        btn_edit.attr("disabled","disabled");
        btn_edit.html(parent_text_button);

        data=new FormData();
        data.append("id",currency_id);
        data.append("rate",rate);
        data.append("availability",availability);
        data.append("token",token);

        $.ajax({
            url:'php_ajax/edit_currency.php',
            type:'post',
            data:data,
            contentType:false,
            processData:false,
            success:function(output) {

                index_error_currency_rate.empty();  

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

    }

}