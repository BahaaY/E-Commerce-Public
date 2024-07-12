$(document).ready(function() {

    var error;
    $("#excel_file").change(function() {

        token_index=$("#token");
        token=token_index.val();
        if(token == undefined || token == ""){
            return;
        }

        spinner="<div class='col-md-12'><div class='spinner-border text-dark'></div></div>";
        
        var file_name = document.getElementById("excel_file").files[0].name;
        $("#file_text").text(file_name);
        
        $("#btn_import").removeAttr("disabled");
        $("#excel_file").removeAttr("disabled");
        $("#error_excel_file").html("");
        $("#container_products").empty();
        $("#number_of_products").empty();

        if($("#excel_file").val() != ""){

            $("#excel_file").attr("disabled","disabled");
            $("#btn_import").attr("disabled","disabled");
            $("#container_products").show();
            $("#container_products").html(spinner);

            var form_data = new FormData();
            var file = document.getElementById("excel_file").files[0];
            form_data.append("excel_file", file);
            form_data.append("token", token);

            $.ajax({
                url: 'php_ajax/display_excel_data.php',
                type: 'post',
                data: form_data,
                contentType: false,
                processData: false,
                success: function(output) {
                    
                    var obj = JSON.parse(output);
                    $("#excel_file").removeAttr("disabled");
                    $("#btn_import").removeAttr("disabled");

                    if(obj.error != 2){
                        $("#number_of_products").show();
                        if(obj.number_of_products > 0){
                            $("#container_products").empty();
                            $("#container_products").html(obj.format);
                            $("#number_of_products").html("Number of products: " + obj.number_of_products);
                            $("#number_of_products").attr("nb_products", obj.number_of_products);
                        }else{
                            $("#container_products").html("<div class='col-md-12 text-center'>"+$("#key_no_products_found").val()+"</div>");
                        }
                    }else{
                        $("#container_products").hide();
                        $("#number_of_products").hide();
                        alert_danger=$("#alert-danger");
                        alert_danger.show();
                        setTimeout(() => {
                            alert_danger.hide();
                        }, 3000);
                    }
                    error=obj.error;
                },
                error:function(error){
                    $("#excel_file").removeAttr("disabled");
                    $("#btn_import").removeAttr("disabled");
                    $("#container_products").hide();
                    $("#number_of_products").hide();
                    alert_danger=$("#alert-danger");
                    alert_danger.show();
                    setTimeout(() => {
                        alert_danger.hide();
                    }, 3000);
                }
            });
        }

    });

    $("#btn_import").click(function() {
        
        btn_index=$("#btn_import");
        alert_success=$("#alert-success");
        alert_danger=$("#alert-danger");

        source_text_button=btn_index.html();
        parent_text_button="<div class='spinner-border text-dark'></div>";
        
        btn_index.html(source_text_button);
        btn_index.removeAttr("disabled");
        alert_success.hide();
        alert_danger.hide();

        index_error_excel_file=$("#error_excel_file");
        index_error_excel_file.html("");

        token_index=$("#token");
        token=token_index.val();
        if(token == undefined || token == ""){
            return;
        }

        if($("#excel_file").val() != ""){

            if(error == 0){

                var number_of_products = $("#number_of_products").attr("nb_products");
                
                if (number_of_products != "" && number_of_products > 0) {

                    btn_index.html(parent_text_button);
                    btn_index.attr("disabled","disabled");

                    var array_title=[];
                    var array_description=[];
                    var array_price=[];
                    var array_discount_percentage=[];
                    var array_stock=[];
                    var array_color=[];
                    var array_size=[];
                    var array_product_type=[];
                    var has_error=0;

                    for (var i = 1; i <= number_of_products; i++) {

                        var array_error=[];

                        $("#error_" + i).empty();
                        var title = document.getElementById("title_" + i).getAttribute("value");
                        var description = document.getElementById("description_" + i).getAttribute("value");
                        var price = document.getElementById("price_" + i).getAttribute("value");
                        var discount_percentage = document.getElementById("discount_percentage_" + i).getAttribute("value");
                        var stock = document.getElementById("stock_" + i).getAttribute("value");
                        var color = document.getElementById("color_" + i).getAttribute("value");
                        var size = document.getElementById("size_" + i).getAttribute("value");
                        var product_type = document.getElementById("product_type_" + i).getAttribute("value");
                        //var product_image = document.getElementById("result_" + i).getAttribute("value");

                        if(discount_percentage == ""){
                            discount_percentage=0;
                        }

                        if(title != "" && description != "" && price != "" && stock != "" && product_type != ""){

                            array_title.push(title);
                            array_description.push(description);
                            array_price.push(price);
                            array_discount_percentage.push(discount_percentage);
                            array_stock.push(stock);
                            array_color.push(color);
                            array_size.push(size);
                            array_product_type.push(product_type);

                        }else{
                            if(title == ""){
                                array_error.push("Title");
                            }
                            if(description == ""){
                                array_error.push("Description");
                            }
                            if(price == ""){
                                array_error.push("Price");
                            }
                            if(stock == ""){
                                array_error.push("Stock");
                            }
                            if(product_type == ""){
                                array_error.push("Product Type");
                            }
                            if(array_error.length == 1){
                                text="field";
                            }else{
                                text="fields";
                            }
                            
                            has_error=1;
                            $("#error_" + i).show();
                            $("#error_" + i).html(array_error + " "+text+" are required");
                        
                        }
                        
                    }

                    if(has_error == 0){

                        data = new FormData();
                        data.append("number_of_products", number_of_products);
                        data.append("array_title", array_title);
                        data.append("array_description", array_description);
                        data.append("array_price", array_price);
                        data.append("array_discount_percentage", array_discount_percentage);
                        data.append("array_stock", array_stock);
                        data.append("array_color", array_color);
                        data.append("array_size", array_size);
                        data.append("array_product_type", array_product_type);
                        data.append("token", token);
                        $.ajax({
                            url: 'php_ajax/import_excel_data.php',
                            type: 'post',
                            data: data,
                            contentType: false,
                            processData: false,
                            success: function(output) {

                                btn_index.html(source_text_button);
                                btn_index.removeAttr("disabled");

                                var obj = JSON.parse(output);
                                if(obj.error == 0){
                                    alert_success.show();
                                    $("#container_products").html("");
                                    $("#number_of_products").html("");
                                    $("#container_products").hide();
                                    $("#number_of_products").hide();
                                    $("#excel_file").val("");
                                    $("#file_text").text($("#file_text").html());
                                }else{
                                    alert_danger.show();
                                }

                                setTimeout(() => {
                                    alert_success.hide();
                                    alert_danger.hide();
                                }, 3000);

                            },
                            error:function(error){
                                btn_index.html(source_text_button);
                                btn_index.removeAttr("disabled");
                            }

                        });

                    }else{
                        btn_index.html(source_text_button);
                        btn_index.removeAttr("disabled");
                        alert_danger.show();
                        setTimeout(() => {
                            alert_danger.hide();
                        }, 3000);
                    }

                }else{
                    index_error_excel_file.html($("#key_file_is_empty").val());
                }

            }else{

                alert_danger=$("#alert-danger");
                alert_danger.show();
                setTimeout(() => {
                    alert_danger.hide();
                }, 3000);

            }

        }else{
            index_error_excel_file.html($("#key_required").val());
        }

    });

});