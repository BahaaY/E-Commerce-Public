function add_product_size(index){
    
    btn_add=$("#btn_add_product_size");

    source_text_button=btn_add.html();
    parent_text_button="<div class='spinner-border text-dark'></div>";
    required_message=$("#key_required").val();

    index_name=$("#product_size_name_0");
    index_availability=$("#product_size_availability_0");
    index_product_size_type_for_product_size_field=$("#product_size_type_for_product_size_field_0");
    index_error_name=$("#error_product_size_name_0");
    index_alert_success=$("#alert-success-insert-product-size");
    index_alert_danger=$("#alert-danger-product-size");

    index_alert_success.hide();
    index_alert_danger.hide();

    name=index_name.val();
    availability=index_availability.val();
    product_size_type_for_product_size_field=index_product_size_type_for_product_size_field.val();

    if(name == undefined){
        name="";
    }

    if(availability == undefined){
        availability="";
    }

    if(product_size_type_for_product_size_field == undefined){
        product_size_type_for_product_size_field="";
    }

    btn_add.html(source_text_button);
    btn_add.removeAttr("disabled");

    if(name == ""){
        index_error_name.text(required_message);
    }else{
        index_error_name.text("");
    }

    token_index=$("#token");
    token=token_index.val();
    if(token == undefined || token == ""){
        return;
    }

    if(name != "" && availability != "" && product_size_type_for_product_size_field != "" && index != "" && token != ""){

        btn_add.attr("disabled","disabled");
        btn_add.html(parent_text_button);

        data=new FormData();
        data.append("name",name);
        data.append("availability",availability);
        data.append("product_size_type",product_size_type_for_product_size_field);
        data.append("index",index);
        data.append("token",token);

        $.ajax({
            url:'php_ajax/add_product_size.php',
            type:'post',
            data:data,
            contentType:false,
            processData:false,
            success:function(output) {

                btn_add.removeAttr("disabled");
                btn_add.html(source_text_button);

                var obj=JSON.parse(output); 
                if(obj.res == 1){
                    $("#tbody_product_size").append(obj.row);
                    index_name.val("");
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

            }
        });

    }

}

function edit_product_size(product_size_id){
    
    btn_edit=$("#btn_edit_product_size_"+product_size_id);
    
    source_text_button=btn_edit.html();
    parent_text_button="<div class='spinner-border text-dark'></div>";
    required_message=$("#key_required").val();

    index_name=$("#product_size_name_"+product_size_id);
    index_availability=$("#product_size_availability_"+product_size_id);
    index_product_size_type_for_product_size_field=$("#product_size_type_for_product_size_field_"+product_size_id);
    index_error_name=$("#error_product_size_name_"+product_size_id);
    index_alert_success=$("#alert-success-product-size");
    index_alert_danger=$("#alert-danger-product-size");

    index_alert_success.hide();
    index_alert_danger.hide();
    index_error_name.empty();

    name=index_name.val();
    availability=index_availability.val();
    product_size_type_for_product_size_field=index_product_size_type_for_product_size_field.val();

    if(name == undefined){
        name="";
    }

    if(availability == undefined){
        availability="";
    }

    if(product_size_type_for_product_size_field == undefined){
        product_size_type_for_product_size_field="";
    }

    btn_edit.html(source_text_button);
    btn_edit.removeAttr("disabled");

    if(name == ""){
        index_error_name.text(required_message);
    }else{
        index_error_name.text("");
    }

    token_index=$("#token");
    token=token_index.val();
    if(token == undefined || token == ""){
        return;
    }

    if(name != "" && availability != "" && product_size_type_for_product_size_field != "" && product_size_id != "" && token != ""){

        btn_edit.attr("disabled","disabled");
        btn_edit.html(parent_text_button);

        data=new FormData();
        data.append("id",product_size_id);
        data.append("name",name);
        data.append("availability",availability);
        data.append("product_size_type",product_size_type_for_product_size_field);
        data.append("token",token);

        $.ajax({
            url:'php_ajax/edit_product_size.php',
            type:'post',
            data:data,
            contentType:false,
            processData:false,
            success:function(output) {

                index_error_name.empty();

                btn_edit.removeAttr("disabled");
                btn_edit.html(source_text_button);

                var obj=JSON.parse(output); 
                if(obj.check_product_size_name == 1){
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

function delete_product_size(product_size_id){
    
    btn_delete=$("#btn_delete_product_size_"+product_size_id);
    
    source_text_button=btn_delete.html();
    parent_text_button="<div class='spinner-border text-dark'></div>";
    required_message=$("#key_required").val();

    index_alert_success_delete=$("#alert-success-delete-product-size");
    index_alert_danger=$("#alert-danger-product-size");

    index_alert_success_delete.hide();
    index_alert_danger.hide();

    btn_delete.html(source_text_button);
    btn_delete.removeAttr("disabled");

    token_index=$("#token");
    token=token_index.val();
    if(token == undefined || token == ""){
        return;
    }

    if(product_size_id != "" && token != ""){

        btn_delete.attr("disabled","disabled");
        btn_delete.html(parent_text_button);

        data=new FormData();
        data.append("id",product_size_id);
        data.append("token",token);

        $.ajax({
            url:'php_ajax/delete_product_size.php',
            type:'post',
            data:data,
            contentType:false,
            processData:false,
            success:function(output) {

                btn_delete.removeAttr("disabled");
                btn_delete.html(source_text_button);

                var obj=JSON.parse(output); 
                if(obj.res == 1){

                    $("#tr_product_size_"+product_size_id).fadeOut(function(){
                        $("#tr_product_size_"+product_size_id).remove();
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