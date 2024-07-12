$(document).ready(function(){

    $('input').attr('autocomplete','off');
    
    /* Select2 */
    $("#search_type").select2({
        allowClear:true,
    });

    $("#search_price").select2({
        allowClear:true,
    });

    $("#order_by_price").select2({
        allowClear:true,
    });

    $("#order_by_stock").select2({
        allowClear:true,
    });

    get_products();

    $("#order_by_price").change(function(){ 
        get_products();
    });

    $("#order_by_stock").change(function(){ 
        get_products();
    });
    
});

function show_toast(){

    $('.container-toast').show();
    $('.toast-logged-in').toast('show');
    setTimeout(function(){
        $('.container-toast').fadeOut();
    },4000);
    
}

function action_heart(product_id,index) {

    if(index == 0){
        var class_name = $("#i-favourite-" + product_id).attr('class');
        var class_original = class_name.split(' ');
        var second_part = class_original[1].split('-');
        if (second_part.length > 2) {
            remove_from_favourites(product_id)
        } else {
            add_to_favourites(product_id);
        }
    }else{
        var class_name = $("#i-favourite-view-" + product_id).attr('class');
        var class_original = class_name.split(' ');
        var second_part = class_original[1].split('-');
        if (second_part.length > 2) {
            remove_from_favourites(product_id)
        } else {
            add_to_favourites(product_id);
        }
    }
    $("#i-favourite-" + product_id).toggleClass("bi-heart bi-heart-fill text-danger");
    $("#i-favourite-view-" + product_id).toggleClass("bi-heart bi-heart-fill text-danger");

}

function add_to_favourites(product_id) {

    token_index=$("#token");
    token=token_index.val();
    if(token == undefined || token == ""){
        return;
    }

    data = new FormData();
    data.append('token', token);
    data.append('product_id', product_id);

    $.ajax({
        url: 'php_ajax/add_to_favourites.php',
        type: 'post',
        data: data,
        contentType: false,
        processData: false,
        success: function(output) {}
    });

}

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
        success: function(output) {}
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
    if(token == undefined || token == ""){
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

    order_by_price = $("#order_by_price").val();
    order_by_stock = $("#order_by_stock").val();

    permission = $("#user-permission").val();
    if(permission != undefined && permission != "" && order_by_price != undefined && order_by_stock != undefined){

        $("#container-products").html('<div class="col-md-12 text-center"><div class="spinner-border text-dark"></div></div>');

        data = new FormData();
        data.append("permission",permission);
        data.append("order_by_price",order_by_price);
        data.append("order_by_stock",order_by_stock);
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
                    $(".container-toast").html(obj.toast);
                }else{
                    $("#container-products").html('<div class="col-md-12 text-center"><h5>'+$("#key_no_product_available").val()+"</h5></div>");
                }
            }
        });
    }else{
        $("#container-products").html('<div class="col-md-12 text-center"><h5>'+$("#key_no_product_available").val()+"</h5></div>");
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

    permission=$("#user-permission").val();

    data = new FormData();
    data.append('product_id', product_id);
    data.append('permission', permission);
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

function search_text(){

    const searchQuery = document.getElementById("search_text").value;
    const productDesignDivs = document.querySelectorAll("#container-product-design");
    let count = 0;

    for (let i = 0; i < productDesignDivs.length; i++) {

        const productDesignDiv = productDesignDivs[i];

        const title = productDesignDiv.querySelector(".card-title").textContent;
        const description = productDesignDiv.querySelector(".card-description").textContent;
        if (title.toLowerCase().includes(searchQuery.toLowerCase()) || description.toLowerCase().includes(searchQuery.toLowerCase())) {
            productDesignDiv.style.display = "block";
        } else {
            productDesignDiv.style.display = "none";
        }

    }

    productDesignDivs.forEach(div => {
    const style = window.getComputedStyle(div);
    if (style.display == 'none') {
        count++;
    }
    });
    
    if(count == productDesignDivs.length){
        $("#no-products-found").show();
    }else if(count != productDesignDivs.length){
        $("#no-products-found").hide();
    }
}

function search_type(){

    const searchQuery = document.getElementById("search_type").value;
    const productDesignDivs = document.querySelectorAll("#container-product-design");
    index=0;

    for (let i = 0; i < productDesignDivs.length; i++) {

        const productDesignDiv = productDesignDivs[i];
        const type = productDesignDiv.querySelector(".card-product-type-name").textContent;
        if (type == searchQuery) {
            productDesignDiv.style.display = "block";
            index++;
        } else {
            productDesignDiv.style.display = "none";
        }

    }

    if (!searchQuery) {
        index=1;
        for (let i = 0; i < productDesignDivs.length; i++) {
            const productDesignDiv = productDesignDivs[i];
            productDesignDiv.style.display = "block";
        }
    }
    
    if(index > 0){
        $("#no-products-found").hide();
    }else{
        $("#no-products-found").show();
    }

}

function search_price(){

    const searchQuery = document.getElementById("search_price").value;
    const productDesignDivs = document.querySelectorAll("#container-product-design");
    const split_price=searchQuery.split("-");
    index=0;

    if(split_price.length == 2){
        minPrice = parseFloat(searchQuery.split("-")[0].replace(/,/g, ''));
        maxPrice = parseFloat(searchQuery.split("-")[1].replace(/,/g, ''));
    }else{
        minPrice = parseFloat(searchQuery.split("-")[0].replace(/,/g, ''));
        maxPrice = parseFloat(0);
    }

    for (let i = 0; i < productDesignDivs.length; i++) {

        const productDesignDiv = productDesignDivs[i];
        const price = parseFloat(productDesignDiv.querySelector(".card-price").textContent.replace(/,/g, ''));
        
        if(split_price.length == 2){
            if (price >= minPrice && price <= maxPrice) {
                productDesignDiv.style.display = "block";
                index++;
            }else{
                productDesignDiv.style.display = "none";
            }
        }else{
            if (price >= minPrice) {
                productDesignDiv.style.display = "block";
                index++;
            }else{
                productDesignDiv.style.display = "none";
            }
        }
    
    }

    if (!searchQuery) {
        index=1;
        for (let i = 0; i < productDesignDivs.length; i++) {
            const productDesignDiv = productDesignDivs[i];
            productDesignDiv.style.display = "block";
        }
    }

    if(index > 0){
        $("#no-products-found").hide();
    }else{
        $("#no-products-found").show();
    }

}