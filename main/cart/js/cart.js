$(document).ready(function(){
    $('input').attr('autocomplete','off');
})

function remove_cart_item(cart_id){
    
    btn_remove_cart_item=$("#btn_remove_cart_item_"+cart_id);

    source_text_button=btn_remove_cart_item.html();
    parent_text_button="<div class='spinner-border text-dark'></div>";

    btn_remove_cart_item.html(parent_text_button);
    btn_remove_cart_item.attr("disabled","disabled");

    token_index=$("#token");
    token=token_index.val();
    if(token == undefined || token == ""){
        return;
    }

    data=new FormData();
    data.append("token",token);
    data.append("cart_id",cart_id);

    $.ajax({
        url:'php_ajax/remove_cart_item.php',
        type:'post',
        data:data,
        contentType:false,
        processData:false,
        success:function(output) {

            btn_remove_cart_item.html(source_text_button);
            btn_remove_cart_item.removeAttr("disabled");

            var obj=JSON.parse(output);

            if(obj.error == 0){
                total_price=$("#total_price_"+cart_id).text();
                $("#section-cart").load(location.href + " #section-cart ");
                $('#modal-remove-cart-'+cart_id).attr("style","display:none");
                $(".modal-backdrop").hide();

                $("body").removeAttr("class");
                $("body").removeAttr("style");
                
            }

        },
        error:function(error){
            btn_remove_cart_item.html(source_text_button);
            btn_remove_cart_item.removeAttr("disabled");
        }
    });
}

function decrement_price(cart_id){

    var decrement_value = $("#qty_"+cart_id).val();
    var value = parseInt(decrement_value, 10);
    value = isNaN(value) ? 0 : value;
    if (value > 1) {
        value--;
        $("#qty_"+cart_id).val(value);
        $("#qty_order_details_"+cart_id).text(value);

        price=$("#price_"+cart_id).text();
        price = price.replace(/,/g,"");
        total=price * value;
        total = total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        $("#total_price_"+cart_id).text(total); 
        //$("#subtotal").text(total+10); 
        //$("#total-shipping").text(total+10); 
    }
    update_subtotal();
}

function increment_price(cart_id){

    max_value=$("#qty_"+cart_id).attr("max");

    var increment_value = $("#qty_"+cart_id).val();
    var value = parseInt(increment_value, 10);
    value = isNaN(value) ? 0 : value;
       
    value++;
    if(value <= max_value){
        $("#qty_"+cart_id).val(value);
        $("#qty_order_details_"+cart_id).text(value);
        price=$("#price_"+cart_id).text();
        price = price.replace(/,/g,"");
        total=price * value;
        total = total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        $("#total_price_"+cart_id).text(total); 
        update_subtotal();
    }

}

function get_color(cart_id){

    clr = $('input[name="color__'+cart_id+'"]:checked').val();
    $("#color_order_details_"+cart_id).html(clr);

}

function get_size(cart_id){

    s = $('input[name="size__'+cart_id+'"]:checked').val();
    $("#size_order_details_"+cart_id).html(s);

}

function add_comma_to_number(number){
    var last_val = '';
    while (number.length > 3) {
        last_val = ',' + number.slice(-3) + last_val;
        number = number.slice(0, -3);
    }
    last_val = number + last_val;
    return last_val;
}
function get_checked_input(){
    var selectedOption = $('input[name="shipping_type"]:checked').val();
    // if(selectedOption == "Free"){
    //     $("#shipping").html("Free");
    // }else{
    //     $("#shipping").html("$<span id='shipping_value'>"+selectedOption+"</span>");
    // }
    $("#shipping").html("<span id='shipping_value'>"+selectedOption+"</span>");
    update_subtotal();
}

function update_subtotal(){
    sum_of_price=0;
    $('.total_price').each(function() {
        price=$(this).text().replace(/,/g,"");
        sum_of_price+=parseFloat(price);
    });
    if($("#shipping_value").val() != undefined){
        shipping_value=$("#shipping_value").text().replace(/,/g,"");
        total=sum_of_price+parseFloat(shipping_value);
    }else{
        total=parseFloat(sum_of_price);
    }
    sum_of_price = sum_of_price.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    $("#subtotal").text(sum_of_price);
    total = total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    $("#total-shipping").text(total);
    $("#total_price_order_details").text(total);
}

function request_order(products_id,carts_id){
    
    btn_request_order_index=$("#btn_request_order");

    source_text_button=btn_request_order_index.html();
    parent_text_button="<div class='spinner-border text-dark'></div>";

    btn_request_order_index.html(source_text_button);
    btn_request_order_index.removeAttr("disabled");

    quantity="";
    color="";
    index_alert_success_checkout=$("#alert-success-checkout");
    index_alert_danger_checkout=$("#alert-danger-checkout");

    index_fullname=$("#fullname");
    index_username=$("#username");
    index_email=$("#email");
    index_address=$("#address");
    index_region=$("#region");
    index_country=$("#country");
    index_phone_number=$("#phone_number");

    index_error_fullname=$("#error_fullname");
    index_error_username=$("#error_username");
    index_error_email=$("#error_email");
    index_error_address=$("#error_address");
    index_error_region=$("#error_region");
    index_error_country=$("#error_country");
    index_error_phone_number=$("#error_phone_number");

    required_text="Required";

    fullname=index_fullname.val();
    username=index_username.val();
    email=index_email.val();
    address=index_address.val();
    region=index_region.val();
    country=index_country.val();
    phone_number=index_phone_number.val();

    if(fullname == undefined){
        fullname="";
    }
    if(username == undefined){
        username="";
    }
    if(email == undefined){
        email="";
    }
    if(address == undefined){
        address="";
    }
    if(region == undefined){
        region="";
    }
    if(country == undefined){
        country="";
    }
    if(phone_number == undefined){
        phone_number="";
    }

    if(fullname == ""){
        index_error_fullname.text(required_text);
    }
    if(username == ""){
        index_error_username.text(required_text);
    }
    if(email == ""){
        index_error_email.text(required_text);
    }
    if(address == ""){
        index_error_address.text(required_text);
    }
    if(region == ""){
        index_error_region.text(required_text);
    }
    if(country == ""){
        index_errorcountry.text(required_text);
    }
    if(phone_number == ""){
        index_error_phone_number.text(required_text);
    }

    size="";
    if(products_id.split(",").length == carts_id.split(",").length){
        array_cart_id=carts_id.split(",");
        array_cart_id.forEach(cart_id => {
            qty=$("#qty_order_details_"+cart_id).text();
            clr = $('input[name="color__'+cart_id+'"]:checked').val();
            s = $('input[name="size__'+cart_id+'"]:checked').attr("product_size_id");
            quantity+=qty+",";
            if(clr){
                color+=clr+",";
            }else{
                color+=",";
            }
            if(s){
                size+=s+",";
            }else{
                size+=",";
            }
        });
    }
    quantity=quantity.slice(0, -1);
    color=color.slice(0, -1);
    size=size.slice(0, -1);

    order_type = $('input[name="shipping_type"]:checked').attr("id");

    token_index=$("#token");
    token=token_index.val();
    if(token == undefined || token == ""){
        return;
    }

    if(token != "" && fullname != "" && username != "" && email != "" && address != "" && region != "" && country != "" && phone_number != ""
        && products_id != "" && carts_id != "" && quantity != "" && order_type !="" && products_id.split(",").length == quantity.split(",").length && products_id.split(",").length == color.split(",").length && products_id.split(",").length == size.split(",").length){

        btn_request_order_index.html(parent_text_button);
        btn_request_order_index.attr("disabled","disabled");

        data=new FormData();
        data.append("fullname",fullname);
        data.append("username",username);
        data.append("email",email);
        data.append("address",address);
        data.append("region",region);
        data.append("country",country);
        data.append("phone_number",phone_number);
        data.append("products_id",products_id);
        data.append("carts_id",carts_id);
        data.append("quantity",quantity);
        data.append("color",color);
        data.append("size",size);
        data.append("order_type",order_type);
        data.append("token",token);

        $.ajax({
            url:'php_ajax/request_order.php',
            type:'post',
            data:data,
            contentType:false,
            processData:false,
            success:function(output) {

                btn_request_order_index.html(source_text_button);
                btn_request_order_index.removeAttr("disabled");

                var obj=JSON.parse(output);

                if(obj.error == 0){

                    index_alert_success_checkout.show();
                    setTimeout(function(){
                        index_alert_success_checkout.hide();
                    },3000);

                    $("#section-cart").load(location.href + " #section-cart ");
                    $('#modal-checkout').attr("style","display:none");
                    $(".modal-backdrop").hide();

                    $("body").removeAttr("class");
                    $("body").removeAttr("style");

                }else{

                    $("#section-cart").load(location.href + " #section-cart ");
                    $('#modal-checkout').attr("style","display:none");
                    $(".modal-backdrop").hide();

                    index_alert_danger_checkout.show();
                    setTimeout(function(){
                        index_alert_danger_checkout.hide();
                    },3000);

                    $("body").removeAttr("class");
                    $("body").removeAttr("style");

                }

            },
            error:function(error){
                btn_request_order_index.html(source_text_button);
                btn_request_order_index.removeAttr("disabled");
                $("body").removeAttr("class");
                $("body").removeAttr("style");
                index_alert_danger_checkout.show();
                setTimeout(function(){
                    index_alert_danger_checkout.hide();
                },3000);
            }
        });

    }

}

function get_order_type_info(){
    
    $("#order_type_name").html($('input[name="shipping_type"]:checked').attr("order_type_name"));
    $("#order_type_amount").html($('input[name="shipping_type"]:checked').attr("value"));
    
}