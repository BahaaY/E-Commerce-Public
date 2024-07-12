$(document).keydown(function (e) {
  
    if (e.which == 13) {
        e.preventDefault();
        $('#btn_add_product').click();
    }
});

$(document).ready(function(){

    $('input').attr('autocomplete','off');

    string="";

    /* Select2 */
    $("#type").select2({
        allowClear:true,
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
            var row_rounder_color = "<label class='rounder-color' id='" + color_name + "' value='"+ color_name +"' style='background-color:" + color_name + "'></label>";
            index_container_rounder_color.append(row_rounder_color);
        }

    }

    //Add commas to number
    inputs = document.querySelectorAll('.input-number');
    inputs.forEach(input => {
        input.onkeyup = function (e) {
            if(e.which!=76 && e.which!=68 && e.which!=32){
                val = this.value;
                val = val.replace(/[^\d]/g, '');
                last_val = '';
                while (val.length > 3) {
                    last_val = ',' + val.slice(-3) + last_val;
                    val = val.slice(0, -3);
                }
                last_val = val + last_val;
                this.value = last_val;
            }
        };
    });

    image_value=[];
    data=new FormData();

    $("#image").change(function(){

        $("#container-images").empty();

        if(this.files.length > 0){

            for(index_file=0;index_file<this.files.length;index_file++){
                data.append("image[]",this.files[index_file]);
                image_value.push(this.files[index_file]);

                $("#container-images").append("<img src='"+URL.createObjectURL(this.files[index_file])+"' class='p-1' width='80px' height='80px'>");

            }
    
        }

    });

    $("#btn_add_product").click(function(){

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

        btn_add_product_index=$("#btn_add_product");

        required_message=$("#key_required").val();
        source_text_button=btn_add_product_index.html();
        parent_text_button="<div class='spinner-border text-dark'></div>";

        index_alert_success=$(".alert-success");
        index_alert_danger=$(".alert-danger");

        index_title=$("#title");
        index_description=$("#description");
        index_price=$("#price");
        index_discount_price=$("#discount_price");
        index_stock=$("#stock");
        index_type=$("#type");

        index_error_title=$("#error_title");
        index_error_price=$("#error_price");
        index_error_type=$("#error_type");
        index_error_size=$("#error_size");
        index_error_image=$("#error_image");

        title_value=index_title.val();
        description_value=index_description.val();
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

        if(image_value.length == 0){
            index_error_image.text(required_message);
        }else{
            index_error_image.empty();
        }

        $(this).html(source_text_button);
        $(this).removeAttr("disabled");
        index_alert_danger.hide();
        index_alert_success.hide();

        if(discount_price_value == ""){
            discount_price_value=0;
        }

        token_index=$("#token");
        token=token_index.val();
        if(token == undefined || token == ""){
            return;
        }

        if(title_value != "" && price_value != "" && type_value != "" && image_value.length > 0){

            btn_add_product_index.attr("disabled","disabled");
            btn_add_product_index.html(parent_text_button);

            data.append("title",title_value);
            data.append("description",description_value);
            data.append("price",price_value);
            data.append("discount_price",discount_price_value);
            data.append("stock",stock_value);
            data.append("color",color_value);
            data.append("type",type_value);
            data.append("size",size_value);
            data.append("token",token);

            $.ajax({
                url:'php_ajax/add_product.php',
                type:'post',
                data:data,
                contentType:false,
                processData:false,
                success:function(output) {

                    btn_add_product_index.removeAttr("disabled");
                    btn_add_product_index.html(source_text_button);

                    data=new FormData();

                    var obj=JSON.parse(output); 

                    if(obj.error == 0){

                        index_alert_success.show();
                        reset_inputs(index_title,index_description,index_price,index_discount_price,index_stock)

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
                error:function(error){
                    btn_add_product_index.removeAttr("disabled");
                    btn_add_product_index.html(source_text_button);
                    index_alert_danger.show();

                    setTimeout(function(){
                        index_alert_danger.hide();
                    },3000);
                }
            });

        }

    });

    function reset_inputs(index_title,index_description,index_price,index_discount_price,index_stock){
        index_title.val("");
        index_description.val("");
        index_price.val("");
        index_discount_price.val(0);
        index_stock.val("1");
        
        $('#type option').prop('selected',false).trigger( "change" );

        array_color=[];
        $(".rounder-color").each(function(){
            $(this).remove();
        });

        $("#image").val("");
        $("#container-images").empty();

        $("#container_product_size").html('<div class="row ml-0 mr-0 mt-1">'+$("#key_select_a_type").val()+'</div>');

        $("#price_result").html();

    }

});

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
        error:function(error){
            index_alert_danger.show();

            setTimeout(function(){
                index_alert_danger.hide();
            },3000);
        }
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

function check_discount_percentage(){
    discount=$("#discount_price");
    if(discount.val() == ""){
        discount.val(0);
    }
}

