$(document).ready(function(){
    $('input').attr('autocomplete','off');
    get_products();
})

function remove_from_favourites(product_id) {

    token_index=$("#token");
    token=token_index.val();
    if(token == undefined || token == ""){
        return;
    }

    data = new FormData();
    data.append('token', token);
    data.append('product_id', product_id);

    $.ajax({
        url: 'php_ajax/remove_from_favourites.php',
        type: 'post',
        data: data,
        contentType: false,
        processData: false,
        success: function(output) {
            obj=JSON.parse(output);
            if(obj.res == 1){
                $("#row_" + product_id).fadeOut('slow', function() {
                    $(this).remove();
                    if(obj.remaining == 0){
                        $("#container-products").html('<div class="col-md-12 text-center"><h5>'+$("#key_no_products_in_favourite").val()+'</h5></div>');
                    }
                })
            }
        }
    });

}

function add_to_cart(product_id, index){

    if(index == 0){
        btn_cart=$("#btn_cart_"+product_id);
    }else{
        btn_cart=$("#btn_cart_info_"+product_id);
    }

    parent_text_button="<div class='spinner-border text-dark'></div>";
    
    btn_cart.attr("disabled","disabled");
    btn_cart.html(parent_text_button);

    token_index=$("#token");
    token=token_index.val();
    if(token == undefined || token == ""){
        return;
    }

    data = new FormData();
    data.append('token', token);
    data.append('product_id', product_id);

    $.ajax({
        url: 'php_ajax/add_to_cart.php',
        type: 'post',
        data: data,
        contentType: false,
        processData: false,
        success: function(output) {

            btn_cart.removeAttr("disabled");

            obj=JSON.parse(output);

            if(obj.res == 1){
                if(index == 0){
                    button_text="<button type='button' class='btn btn-primary' id='btn_cart_"+product_id+"' onclick='remove_from_cart("+product_id+",0)'><i class='bi bi-cart mr-2 ml-2'></i>"+$("#key_remove_from_cart").val()+"</button>";
                }else{
                    button_text="<button type='button' class='btn btn-primary' id='btn_cart_info_"+product_id+"' onclick='remove_from_cart("+product_id+",1)'><i class='bi bi-cart mr-2 ml-2'></i>"+$("#key_remove_from_cart").val()+"</button>";
                }
                $("#container-button-"+product_id).html(button_text);
                $("#container-button-view-"+product_id).html(button_text);
            }

        }
    });

}

function remove_from_cart(product_id, index){

    if(index == 0){
        btn_cart=$("#btn_cart_"+product_id);
    }else{
        btn_cart=$("#btn_cart_info_"+product_id);
    }

    parent_text_button="<div class='spinner-border text-dark'></div>";
    
    btn_cart.attr("disabled","disabled");
    btn_cart.html(parent_text_button);

    token_index=$("#token");
    token=token_index.val();
    if(token != undefined && token == ""){
        return;
    }

    data = new FormData();
    data.append('token', token);
    data.append('product_id', product_id);

    $.ajax({
        url: 'php_ajax/remove_from_cart.php',
        type: 'post',
        data: data,
        contentType: false,
        processData: false,
        success: function(output) {

            btn_cart.removeAttr("disabled");

            obj=JSON.parse(output);

            if(obj.res == 1){
                if(index == 0){
                    button_text="<button type='button' class='btn btn-primary' id='btn_cart_"+product_id+"' onclick='add_to_cart("+product_id+",0)'><i class='bi bi-cart mr-2 ml-2'></i>"+$("#key_add_to_cart").val()+"</button>";
                }else{
                    button_text="<button type='button' class='btn btn-primary' id='btn_cart_info_"+product_id+"' onclick='add_to_cart("+product_id+",1)'><i class='bi bi-cart mr-2 ml-2'></i>"+$("#key_add_to_cart").val()+"</button>";
                }
                $("#container-button-"+product_id).html(button_text);
                $("#container-button-view-"+product_id).html(button_text);
            }

        }
    });

}

function get_products(){

    token_index=$("#token");
    token=token_index.val();
    if(token != undefined && token != ""){

        $("#container-products").html('<div class="col-md-12 text-center"><div class="spinner-border text-dark"></div></div>');

        data = new FormData();
        data.append("token",token);
        $.ajax({
            url: "php_ajax/get_products.php",
            type: "POST",
            data: data,
            contentType: false,
            processData: false,
            success: function(output){
                obj = JSON.parse(output);
                if(obj.error == 0){
                    $("#container-products").html(obj.products);
                }else{
                    $("#container-products").html('<div class="col-md-12 text-center"><h5>'+$("#key_no_products_in_favourite").val()+'</h5></div>');
                }
            }
        });
    }else{
        $("#container-products").html('<div class="col-md-12 text-center"><h5>'+$("#key_no_products_in_favourite").val()+'</h5></div>');
    }
}

var scrollTop;

function view_product(product_id) {

    scrollTop = $(window).scrollTop();

    index_current_product=$("#current_product");
    index_parent_product=$("#parent_product");
    index_loading=$("#loading");

    index_current_product.hide();
    index_loading.show();

    token_index=$("#token");
    token=token_index.val();
    if(token == undefined || token == ""){
        return;
    }
    
    data = new FormData();
    data.append('token', token);
    data.append('product_id', product_id);
    $.ajax({
        url: 'php_ajax/view_product.php',
        type: 'post',
        data: data,
        contentType: false,
        processData: false,
        success: function(output) {

            index_loading.hide();

            var obj = JSON.parse(output);

            if(obj.res == 1){

                index_parent_product.empty();
                index_parent_product.append(obj.format);
                index_parent_product.show();

            }
            

        }
    });

}

function back(){

    $("#parent_product").hide();
    $("#current_product").show();

    $(window).scrollTop(scrollTop);

}