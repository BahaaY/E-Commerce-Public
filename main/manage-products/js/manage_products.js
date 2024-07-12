$(document).ready(function(){

    $('input').attr('autocomplete','off');
    
    /* Select2 */
    $("#type").select2({
        allowClear:true,
    });

    /* Select2 */
    $("#search_by_stock").select2({
        allowClear:true,
    });

    /* Select2 */
    $("#search_by_category").select2({
        allowClear:true,
    });

    /* Select2 */
    $("#availability").select2({
        allowClear:false,
    });

    /* color */
    row_rounder_color="";

    $("#color").spectrum({

        showPaletteOnly: true,
        showPalette:true,
        hideAfterPaletteSelect:true,
        color: 'black',

        change: function(color) {
            printColor(color);
        },

        palette:["red", "green", "blue", "yellow", "purple", "pink", "white","brown","gray","black","orange","cyan","teal","olive","gold","silver","magenta","lavender","maroon"],

    });
    
    function printColor(color) {

        array_rounder_color_value=[];
       
        var index_container_rounder_color = $("#container-rounder-color");
        var color_name = color.toName();
        //var color_hex = color.toHexString();

        $(".rounder-color").each(function() {
            array_rounder_color_value.push($(this).attr("id"));
        });
    
        if (array_rounder_color_value.includes(color_name)) {
            $("#" + color_name).remove();
        } else {
            var row_rounder_color = "<label class='rounder-color' id='" + color_name + "' title='" + color_name + "' value='"+ color_name +"' style='background-color:" + color_name + "'></label>";
            index_container_rounder_color.append(row_rounder_color);
        }

    }

    search();

});

function search(){
    product_type=$("#search_by_category").val();
    stock=$("#search_by_stock").val();
    load_table_manage_products(product_type,stock);
    load_modals_manage_products(product_type,stock);
}

function get_product_size(){

    $("#container_product_size").html("Loading...");

    index_alert_danger=$(".alert-danger");
    index_alert_danger.hide();

    index_type=$("#type");
    type_value=index_type.val();

    token_index=$("#token");
    token=token_index.val();
    if(token == undefined || token == ""){
        return;
    }

    data=new FormData();
    data.append("token",token);
    data.append("type",type_value);

    $.ajax({
        url:'php_ajax/get_product_size.php',
        type:'post',
        data:data,
        contentType:false,
        processData:false,
        success:function(output) {

            data=new FormData();
            var obj=JSON.parse(output); 

            if(obj.error == 0){

                $("#container_product_size").html(obj.res);
                $("#product_size_type").val(obj.size_type);

            }else{

                index_alert_danger.show();

                setTimeout(function(){
                    index_alert_danger.hide();
                },3000);

            }

        },
    });

}

/* Stock */
function check_stock(){

    const quantityInput = document.querySelector("#stock");

    if(quantityInput.value < 0){
        quantityInput.value=1;
    }

    if(quantityInput.value == ""){
        quantityInput.value=0;
    }

}

function increment(){

    const quantityInput = document.querySelector("#stock");

    quantityInput.value = parseInt(quantityInput.value) + 1;

}

function decrement(){

    const quantityInput = document.querySelector("#stock");

    if(quantityInput.value > 0){
        quantityInput.value = parseInt(quantityInput.value) - 1;
    }
    
}

var scrollTop;

function view_section_update_product(product_id){

    scrollTop = $(window).scrollTop();

    $("#container-table").hide();

    $("#btn-edit").val(product_id);

    index_container_table=$("#container-table");
    index_container_update_product=$("#container-update-product");
    index_loading=$("#loading");

    index_container_table.hide();
    index_loading.show();

    token_index=$("#token");
    token=token_index.val();
    if(token == undefined || token == ""){
        return;
    }

    data=new FormData();
    data.append("token",token);
    data.append("product_id",product_id);

    $.ajax({
        url:'php_ajax/get_product_data.php',
        type:'post',
        data:data,
        contentType:false,
        processData:false,
        success:function(output) {

            index_loading.hide();

            var obj=JSON.parse(output);

            if(obj.error == 0){

                title=obj.array_data.title;
                description=obj.array_data.description;
                availability=obj.array_data.availability;
                price=obj.array_data.price;
                discount_price=obj.array_data.discount_price;
                stock=obj.array_data.stock;
                color=obj.array_data.color;
                type=obj.array_data.type;
                size=obj.array_data.size;
                image=obj.array_data.image;
                product_size_type=obj.array_data.product_size_type;
                button=obj.array_data.button;

                $("#title").val(title);
                $("#description").html(description);
                $("#price").val(price);
                $("#discount_price").val(discount_price);
                $("#stock").val(stock);
                $("#container-rounder-color").html(color);
                $("#type").html(type);
                $("#container_product_size").html(size);
                $("#availability").html(availability);
                $("#container-images").html(image);
                $("#product_size_type").val(product_size_type);
                $("#container-button-update").html(button);
                
                result=price - (price*(discount_price/100));
                result = Math.round(result * 2) / 2;
                $("#price_result").html("$"+result.toFixed(2));

                index_container_update_product.show();

            }
            
        },
    });

}

image_value=[];

function upload_image(){

    //$("#container-images").empty();
    input_image=$("#image")[0];

    if(input_image.files.length > 0){

        for(index_file=0;index_file<input_image.files.length;index_file++){
            image_value.push(input_image.files[index_file]);
            if(image_value.length > 0){
                id_index_file=image_value.length - 1;
            }else{
                id_index_file=index_file;
            }
            $("#container-images").append("<div class='col-md-1' id='col-"+id_index_file+"'><div class='form-group row text-center'><img src='"+URL.createObjectURL(input_image.files[index_file])+"' class='p-1' style='width:80px;height:80px;'><i class='fa fa-close' id='icon-remove-image' onclick='remove_uploaded_image("+id_index_file+");'></i></div></div>");
        }
    
    }

}

function remove_uploaded_image(id_index_file){
    $("#col-"+id_index_file).remove();
    if (id_index_file > -1) {
        image_value.splice(id_index_file, 1);
    }
}

function edit_product(product_id){

    array_color=[];
    $(".rounder-color").each(function(){
        array_color.push($(this).attr("value"));
    });
   
    var array_size = [];

    product_size_type=$("#product_size_type").val();
    if(product_size_type == 1 || product_size_type == 2){
        $.each($("input[type='checkbox']:checked"), function(){
            array_size.push($(this).val());
        });
    }else if(product_size_type == 3){
        array_size.push("");
    }else{
        array_size.push("");
    }

    btn_edit_product_index=$("#btn_update_product_"+product_id);
    
    required_message=$("#key_required").val();
    source_text_button=btn_edit_product_index.html();
    parent_text_button="<div class='spinner-border text-dark'></div>";

    btn_edit_product_index.html(source_text_button);
    btn_edit_product_index.removeAttr("disabled");

    index_alert_success=$("#alert-success-update-product");
    index_alert_danger=$("#alert-danger-product");

    index_title=$("#title");
    index_description=$("#description");
    index_price=$("#price");
    index_discount_price=$("#discount_price");
    index_stock=$("#stock");
    index_color=$("#color");
    index_type=$("#type");
    index_availability=$("#availability");

    index_error_title=$("#error_title");
    index_error_price=$("#error_price");
    index_error_color=$("#error_color");
    index_error_type=$("#error_type");
    index_error_size=$("#error_size");
    index_error_image=$("#error_image");

    title_value=index_title.val();
    description_value=index_description.val();
    availability_value=index_availability.val();
    price_value=index_price.val();
    discount_price_value=index_discount_price.val();
    stock_value=index_stock.val();
    type_value=index_type.val();

    if(array_color.length > 0){
        color_value= array_color.join(", ");
    }else{
        color_value="";
    }

    if(array_size.length > 1){
        size_value=array_size.join(", ");
    }else{
        size_value=array_size;
    }

    /* Check if undefined */
    if(title_value == undefined){
        return;
    }
    if(description_value == undefined){
        return;
    }
    if(availability_value == undefined){
        return;
    }
    if(price_value == undefined){
        return;
    }
    if(discount_price_value == undefined){
        return;
    }
    if(stock_value == undefined){
        return;
    }
    if(type_value == undefined){
        return;
    }

    /* Check if empty */
    if(title_value == ""){
        index_error_title.text(required_message);
    }else{
        index_error_title.empty();
    }

    if(price_value == ""){
        index_error_price.text(required_message);
    }else{
        index_error_price.empty();
    }

    if(type_value == ""){
        index_error_type.text(required_message);
    }else{
        index_error_type.empty();
    }

    numOfImages = document.querySelectorAll('#container-images img').length;

    if(numOfImages == 0){
        index_error_image.text(required_message);
    }else{
        index_error_image.empty();
    }

    index_alert_danger.hide();
    index_alert_success.hide();

    token_index=$("#token");
    token=token_index.val();
    if(token == undefined || token == ""){
        return;
    }

    if(product_id != "" && title_value != "" && price_value != "" && type_value != "" && numOfImages > 0){

        btn_edit_product_index.attr("disabled","disabled");
        btn_edit_product_index.html(parent_text_button);
        
        data=new FormData();

        data.append("product_id",product_id);
        data.append("title",title_value);
        data.append("description",description_value);
        data.append("price",price_value);
        data.append("discount_price",discount_price_value);
        data.append("stock",stock_value);
        data.append("color",color_value);
        data.append("type",type_value);
        data.append("size",size_value);
        data.append("availability",availability_value);
        if(image_value.length > 0 ){
            for(var i=0;i<image_value.length;i++){
                data.append("image[]",image_value[i]);
            }
        }
        data.append("token",token);

        $.ajax({
            url:'php_ajax/edit_product.php',
            type:'post',
            data:data,
            contentType:false,
            processData:false,
            success:function(output) {

                btn_edit_product_index.removeAttr("disabled");
                btn_edit_product_index.html(source_text_button);

                data=new FormData();

                var obj=JSON.parse(output); 

                if(obj.error == 0){

                    index_alert_success.show();

                    product_type=$("#search_by_category").val();
                    stock=$("#search_by_stock").val();
                    load_table_manage_products(product_type,stock);
                    load_modals_manage_products(product_type,stock);

                    image_value=[];
                    $("#image").val("");

                    back();

                    setTimeout(function(){
                        index_alert_success.hide();
                    },3000);

                }else{

                    index_alert_danger.show();

                    back();

                    setTimeout(function(){
                        index_alert_danger.hide();
                    },3000);

                }

            },
            error: function(error){
                btn_edit_product_index.removeAttr("disabled");
                btn_edit_product_index.html(source_text_button);
                index_alert_danger.show();
                back();
                setTimeout(function(){
                    index_alert_danger.hide();
                },3000);
            }
        });

    }

}

function delete_product(product_id){
    
    btn_delete_product_index=$("#delete_product_"+product_id);

    index_alert_success=$("#alert-success-delete-product");
    index_alert_danger=$("#alert-danger-product");

    source_text_button=btn_delete_product_index.html();
    parent_text_button="<div class='spinner-border text-dark'></div>";

    btn_delete_product_index.html(parent_text_button);
    btn_delete_product_index.attr("disabled","disabled");

    token_index=$("#token");
    token=token_index.val();
    if(token == undefined || token == ""){
        return;
    }

    data=new FormData();
    data.append('token', token);
    data.append('product_id', product_id);

    $.ajax({
        url: 'php_ajax/remove_product.php',
        type: 'post',
        data: data,
        contentType: false,
        processData: false,
        success: function(output) {

            btn_delete_product_index.html(source_text_button);
            btn_delete_product_index.removeAttr("disabled");

            var obj = JSON.parse(output);

            if (obj.error == 0) {

                index_alert_success.show();

                product_type=$("#search_by_category").val();
                stock=$("#search_by_stock").val();
                load_table_manage_products(product_type,stock);

                $("#modal-delete-product-"+product_id).slideUp(function(){
                    $(".modal-backdrop").hide();
                    $("body").removeClass("modal-open");
                });

                setTimeout(function(){
                    index_alert_success.hide();
                },3000);

                $("body").removeAttr("class");
                $("body").removeAttr("style");

            }else{

                $("#modal-delete-product-"+product_id).slideUp(function(){
                    $(".modal-backdrop").hide();
                });
                
                index_alert_danger.show();

                setTimeout(function(){
                    index_alert_danger.hide();
                },3000);

                $("body").removeAttr("class");
                $("body").removeAttr("style");

            }
        },
        error: function(error){
            btn_delete_product_index.html(source_text_button);
            btn_delete_product_index.removeAttr("disabled");
            $("#modal-delete-product-"+product_id).slideUp(function(){
                $(".modal-backdrop").hide();
            });
            index_alert_danger.show();
            setTimeout(function(){
                index_alert_danger.hide();
            },3000);
            $("body").removeAttr("class");
            $("body").removeAttr("style");
        }
    });

}

function delete_product_image(id_image_product){

    btn_delete_product_image_index=$("#delete_product_image_"+id_image_product);

    index_alert_success=$("#alert-success-delete-image");
    index_alert_danger=$("#alert-danger-product");

    source_text_button=btn_delete_product_image_index.html();
    parent_text_button="<div class='spinner-border text-dark'></div>";

    btn_delete_product_image_index.html(source_text_button);
    btn_delete_product_image_index.removeAttr("disabled");

    if(id_image_product != ""){

        btn_delete_product_image_index.html(parent_text_button);
        btn_delete_product_image_index.attr("disabled","disabled");

        token_index=$("#token");
        token=token_index.val();
        if(token == undefined || token == ""){
            return;
        }

        data=new FormData();
        data.append('token', token);
        data.append('image_id', id_image_product);
    
        $.ajax({
            url: 'php_ajax/delete_product_image.php',
            type: 'post',
            data: data,
            contentType: false,
            processData: false,
            success: function(output) {

                btn_delete_product_image_index.html(source_text_button);
                btn_delete_product_image_index.removeAttr("disabled");
    
                var obj = JSON.parse(output);

                $("#modal-delete-image-"+id_image_product).slideUp(function(){
                    $(".modal-backdrop").hide();
                    $("body").removeClass("modal-open");
                });
    
                if (obj.error == 0) {
    
                    // $("#col-"+id_image_product).hide(function(){
                    //     $("#col-"+id_image_product).remove();
                    // });
                    
                    product_type=$("#search_by_category").val();
                    stock=$("#search_by_stock").val();
                    load_table_manage_products(product_type,stock);

                    back();

                    index_alert_success.show();

                    setTimeout(function(){
                        index_alert_success.hide();
                    },3000); 

                    $("body").removeAttr("class");
                    $("body").removeAttr("style");

                }else{

                    back();
    
                    index_alert_danger.show();

                    setTimeout(function(){
                        index_alert_danger.hide();
                    },3000);    

                    $("body").removeAttr("class");
                    $("body").removeAttr("style");
                    
                }
            },
            error: function(error){
                btn_delete_product_image_index.html(source_text_button);
                btn_delete_product_image_index.removeAttr("disabled");
                back();
                index_alert_danger.show();
                setTimeout(function(){
                    index_alert_danger.hide();
                },3000);    
                $("body").removeAttr("class");
                $("body").removeAttr("style");
            }
        });

    }

}

function back(){

    $("#container-update-product").hide();
    $("#container-table").show();
    
    $(window).scrollTop(scrollTop);

}

function load_table_manage_products(product_type=null,stock=null){
    
    $('#table_manage_products').dataTable().fnClearTable();
    $('#table_manage_products').dataTable().fnDestroy();

    token_index=$("#token");
    token=token_index.val();
    if(token == undefined || token == ""){
        return;
    }

    $("#table_manage_products").DataTable({
        ajax: {
            url: 'php_ajax/load_table_manage_products.php',
            type: 'post',
            dataSrc: '',
            data: function(data){
                data.token=token;
                data.product_type=product_type;
                data.stock=stock;
            }
        },
        columns: [
            { data: 'serial_number' },
            { data: 'title' },
            { data: 'image' },
            { data: 'action' },
            { data: 'stock' }
        ]
    });
}

function load_modals_manage_products(product_type=null,stock=null){

    token_index=$("#token");
    token=token_index.val();
    if(token == undefined || token == ""){
        return;
    }

    $.ajax({
        url:'php_ajax/load_modals_manage_products.php',
        type:'post',
        data:'token='+token+'&product_type='+product_type+'&stock='+stock,
        success:function(output) {

            var obj=JSON.parse(output);

            $("#container-modals").html(obj.modals);
            
        },
    });

}

function get_discount_result() {
    var price = parseFloat($("#price").val());
    var discount_price = parseFloat($("#discount_price").val());

    if (isNaN(price)) {
        price = 0;
    }

    if (isNaN(discount_price)) {
        discount_price = 0;
    }

    if (discount_price != "") {
        var result = price - (price * (discount_price / 100));
        result = parseFloat(Math.round(result * 2) / 2);
        $("#price_result").html("$" + result.toFixed(2));
    } else {
        $("#price_result").html("$" + price.toFixed(2));
    }

    if(price == "" && discount_price == ""){
        $("#price_result").html("");
    }

}
