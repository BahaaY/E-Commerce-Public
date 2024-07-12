function add_product_type(index){
    
    btn_add=$("#btn_add_product_type");

    source_text_button=btn_add.html();
    parent_text_button="<div class='spinner-border text-dark'></div>";
    required_message=$("#key_required").val();

    index_name=$("#product_type_name_0");
    index_size_type=$("#product_size_type_0");
    index_availability=$("#product_type_availability_0");
    index_product_availability=$("#product_availability_0");
    index_error_name=$("#error_product_type_name_0");
    index_alert_success=$("#alert-success-insert-product-type");
    index_alert_danger=$("#alert-danger-product-type");

    index_alert_success.hide();
    index_alert_danger.hide();

    name=index_name.val();
    size_type=index_size_type.val();
    availability=index_availability.val();
    product_availability=index_product_availability.val();

    if(name == undefined){
        name="";
    }

    if(size_type == undefined){
        size_type="";
    }

    if(availability == undefined){
        availability="";
    }

    if(product_availability == undefined){
        product_availability="";
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

    if(name != "" && size_type != "" && availability != "" && product_availability != "" && index != "" && token != ""){

        btn_add.attr("disabled","disabled");
        btn_add.html(parent_text_button);

        data=new FormData();
        data.append("name",name);
        data.append("size_type",size_type);
        data.append("availability",availability);
        data.append("product_availability",product_availability);
        data.append("index",index);
        data.append("token",token);

        $.ajax({
            url:'php_ajax/add_product_type.php',
            type:'post',
            data:data,
            contentType:false,
            processData:false,
            success:function(output) {

                btn_add.removeAttr("disabled");
                btn_add.html(source_text_button);

                var obj=JSON.parse(output); 
                if(obj.res == 1){
                    $("#tbody_product_type").append(obj.row);
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

function edit_product_type(product_type_id){
    
    btn_edit=$("#btn_edit_product_type_"+product_type_id);
    
    source_text_button=btn_edit.html();
    parent_text_button="<div class='spinner-border text-dark'></div>";
    required_message=$("#key_required").val();

    index_name=$("#product_type_name_"+product_type_id);
    index_size_type=$("#product_size_type_"+product_type_id);
    index_availability=$("#product_type_availability_"+product_type_id);
    index_product_availability=$("#product_availability_"+product_type_id);
    index_error_name=$("#error_product_type_name_"+product_type_id);
    index_alert_success=$("#alert-success-product-type");
    index_alert_danger=$("#alert-danger-product-type");

    index_alert_success.hide();
    index_alert_danger.hide();
    index_error_name.empty();

    name=index_name.val();
    size_type=index_size_type.val();
    availability=index_availability.val();
    product_availability=index_product_availability.val();

    if(name == undefined){
        name="";
    }

    if(size_type == undefined){
        size_type="";
    }

    if(availability == undefined){
        availability="";
    }

    if(product_availability == undefined){
        product_availability="";
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

    if(name != "" && size_type != "" && availability != "" && product_availability != "" && product_type_id != "" && token != ""){

        btn_edit.attr("disabled","disabled");
        btn_edit.html(parent_text_button);

        data=new FormData();
        data.append("id",product_type_id);
        data.append("name",name);
        data.append("size_type",size_type);
        data.append("availability",availability);
        data.append("product_availability",product_availability);
        data.append("token",token);

        $.ajax({
            url:'php_ajax/edit_product_type.php',
            type:'post',
            data:data,
            contentType:false,
            processData:false,
            success:function(output) {

                index_error_name.empty();

                btn_edit.removeAttr("disabled");
                btn_edit.html(source_text_button);

                var obj=JSON.parse(output); 
                if(obj.check_product_type_name == 1){
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

function delete_product_type(product_type_id){
    
    btn_delete=$("#btn_delete_product_type_"+product_type_id);
    
    source_text_button=btn_delete.html();
    parent_text_button="<div class='spinner-border text-dark'></div>";
    required_message=$("#key_required").val();

    index_alert_success_delete=$("#alert-success-delete-product-type");
    index_alert_danger=$("#alert-danger-product-type");

    index_alert_success_delete.hide();
    index_alert_danger.hide();

    btn_delete.html(source_text_button);
    btn_delete.removeAttr("disabled");

    token_index=$("#token");
    token=token_index.val();
    if(token == undefined || token == ""){
        return;
    }

    if(product_type_id != "" && token != ""){

        btn_delete.attr("disabled","disabled");
        btn_delete.html(parent_text_button);

        data=new FormData();
        data.append("id",product_type_id);
        data.append("token",token);

        $.ajax({
            url:'php_ajax/delete_product_type.php',
            type:'post',
            data:data,
            contentType:false,
            processData:false,
            success:function(output) {

                btn_delete.removeAttr("disabled");
                btn_delete.html(source_text_button);

                var obj=JSON.parse(output); 
                if(obj.res == 1){

                    $("#tr_product_type_"+product_type_id).fadeOut(function(){
                        $("#tr_product_type_"+product_type_id).remove();
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