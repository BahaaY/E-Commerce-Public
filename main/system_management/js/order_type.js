function add_order_type(index){
    
    btn_add=$("#btn_add_order_type");

    source_text_button=btn_add.html();
    parent_text_button="<div class='spinner-border text-dark'></div>";
    required_message=$("#key_required").val();

    index_name=$("#order_type_name_0");
    index_amount=$("#order_type_amount_0");
    index_availability=$("#order_type_availability_0");
    index_error_name=$("#error_order_type_name_0");
    index_error_amount=$("#error_order_type_amount_0");
    index_alert_success=$("#alert-success-insert-order-type");
    index_alert_danger=$("#alert-danger-order-type");

    index_alert_success.hide();
    index_alert_danger.hide();

    name=index_name.val();
    amount=index_amount.val();
    availability=index_availability.val();

    if(name == undefined){
        name="";
    }

    if(amount == undefined){
        amount="";
    }

    if(availability == undefined){
        availability="";
    }

    btn_add.html(source_text_button);
    btn_add.removeAttr("disabled");

    if(name == ""){
        index_error_name.text(required_message);
    }else{
        index_error_name.text("");
    }

    if(amount == ""){
        index_error_amount.text(required_message);
    }else{
        index_error_amount.text("");
    }

    token_index=$("#token");
    token=token_index.val();
    if(token == undefined || token == ""){
        return;
    }

    if(name != "" && amount != "" && availability != "" && index != "" && token != ""){

        btn_add.attr("disabled","disabled");
        btn_add.html(parent_text_button);

        data=new FormData();
        data.append("name",name);
        data.append("amount",amount);
        data.append("availability",availability);
        data.append("index",index);
        data.append("token",token);

        $.ajax({
            url:'php_ajax/add_order_type.php',
            type:'post',
            data:data,
            contentType:false,
            processData:false,
            success:function(output) {

                btn_add.removeAttr("disabled");
                btn_add.html(source_text_button);

                var obj=JSON.parse(output); 
                if(obj.res == 1){
                    $("#tbody_order_type").append(obj.row);
                    index_name.val("");
                    index_amount.val("");
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
                btn_add.removeAttr("disabled");
                btn_add.html(source_text_button);
                index_alert_danger.show();
                setTimeout(function(){
                    index_alert_danger.hide();
                },3000);
            }
        });

    }

}

function edit_order_type(order_type_id){
    
    btn_edit=$("#btn_edit_order_type_"+order_type_id);
    
    source_text_button=btn_edit.html();
    parent_text_button="<div class='spinner-border text-dark'></div>";
    required_message=$("#key_required").val();

    index_name=$("#order_type_name_"+order_type_id);
    index_amount=$("#order_type_amount_"+order_type_id);
    index_availability=$("#order_type_availability_"+order_type_id);
    index_error_name=$("#error_order_type_name_"+order_type_id);
    index_error_amount=$("#error_order_type_amount_"+order_type_id);
    index_alert_success=$("#alert-success-order-type");
    index_alert_danger=$("#alert-danger-order-type");

    index_alert_success.hide();
    index_alert_danger.hide();
    index_error_name.empty();
    index_error_amount.empty();

    name=index_name.val();
    amount=index_amount.val();
    availability=index_availability.val();

    if(name == undefined){
        name="";
    }

    if(amount == undefined){
        amount="";
    }

    if(availability == undefined){
        availability="";
    }

    btn_edit.html(source_text_button);
    btn_edit.removeAttr("disabled");

    if(name == ""){
        index_error_name.text(required_message);
    }else{
        index_error_name.text("");
    }

    if(amount == ""){
        index_error_amount.text(required_message);
    }else{
        index_error_amount.text("");
    }

    token_index=$("#token");
    token=token_index.val();
    if(token == undefined || token == ""){
        return;
    }

    if(name != "" && amount != "" && availability != "" && order_type_id != "" && token != ""){

        btn_edit.attr("disabled","disabled");
        btn_edit.html(parent_text_button);

        data=new FormData();
        data.append("id",order_type_id);
        data.append("name",name);
        data.append("amount",amount);
        data.append("availability",availability);
        data.append("token",token);

        $.ajax({
            url:'php_ajax/edit_order_type.php',
            type:'post',
            data:data,
            contentType:false,
            processData:false,
            success:function(output) {

                index_error_name.empty();
                index_error_amount.empty();            

                btn_edit.removeAttr("disabled");
                btn_edit.html(source_text_button);

                var obj=JSON.parse(output); 
                if(obj.check_order_type_name == 1){
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
                }else{
                    index_error_name.text("Used");
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

function delete_order_type(order_type_id){

    btn_delete=$("#btn_delete_order_type_"+order_type_id);
    
    source_text_button=btn_delete.html();
    parent_text_button="<div class='spinner-border text-dark'></div>";
    required_message=$("#key_required").val();

    index_alert_success_delete=$("#alert-success-delete-order-type");
    index_alert_danger=$("#alert-danger-order-type");

    index_alert_success_delete.hide();
    index_alert_danger.hide();

    btn_delete.html(source_text_button);
    btn_delete.removeAttr("disabled");

    token_index=$("#token");
    token=token_index.val();
    if(token == undefined || token == ""){
        return;
    }

    if(order_type_id != "" && token != ""){

        btn_delete.attr("disabled","disabled");
        btn_delete.html(parent_text_button);

        data=new FormData();
        data.append("id",order_type_id);
        data.append("token",token);

        $.ajax({
            url:'php_ajax/delete_order_type.php',
            type:'post',
            data:data,
            contentType:false,
            processData:false,
            success:function(output) {

                btn_delete.removeAttr("disabled");
                btn_delete.html(source_text_button);

                var obj=JSON.parse(output); 
                if(obj.res == 1){

                    $("#tr_order_type_"+order_type_id).fadeOut(function(){
                        $("#tr_order_type_"+order_type_id).remove();
                    });

                    index_alert_success_delete.show();
                    setTimeout(function(){
                        index_alert_success_delete.hide();
                    },3000);
                }else{
                    index_alert_danger.show();
                    setTimeout(function(){
                        index_alert_danger.hide();
                    },3000);
                }
            },
            error: function(error){
                btn_delete.removeAttr("disabled");
                btn_delete.html(source_text_button);
                index_alert_danger.show();
                setTimeout(function(){
                    index_alert_danger.hide();
                },3000);
            }
        });

    }

}