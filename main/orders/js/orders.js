$(document).ready(function(){
    $('input').attr('autocomplete','off');
})

function clear_alert(){
    $(".alert").empty();
    $(".alert").hide();
}

function searchTable(columnIndex, keyword, tableId){

    var table = document.getElementById(tableId);
    var rows = table.getElementsByTagName("tr");

    keyword = keyword.value.toUpperCase();
    keyword = keyword.replace(/,/g,"");

    for (var i = 0; i < rows.length; i++) {
        var cell = rows[i].getElementsByTagName("td")[columnIndex];
        if (cell) {
            var value = cell.textContent || cell.innerText;
            value = value.toUpperCase();
            value = value.replace(/,/g,"");

            if (value.indexOf(keyword) > -1) {
                rows[i].style.display = "";
            } else {
                rows[i].style.display = "none";
            }
        }
    }

    var tbody = table.getElementsByTagName("tbody")[0];
    var tbody_rows = tbody.querySelectorAll('tr');
    var visibleRowCount = 0;

    tbody_rows.forEach(function(row) {
        var display = row.style.display;
        if (display != 'none') {
            visibleRowCount++;
        }
    });

    if(visibleRowCount == 0){
        $("#tfoot-"+tableId).show();
    }else{
        $("#tfoot-"+tableId).hide();
    }

}

function update_order(order_id){
    
        btn_update_order_index=$("#btn_update_order_"+order_id);

        source_text_button=btn_update_order_index.html();
        parent_text_button="<div class='spinner-border text-dark'></div>";

        btn_update_order_index.html(parent_text_button);
        btn_update_order_index.attr("disabled","disabled");

        var status=$("#status_"+order_id).val();

        token_index=$("#token");
        token=token_index.val();
        if(token == undefined || token == ""){
            return;
        }
    
        data=new FormData();
        data.append("token",token);
        data.append("status",status);
        data.append("order_id",order_id);

        $("#alert-success-update-order").hide();
        $("#alert-danger-update-order").hide();

        $.ajax({
            url:'php_ajax/orders.php',
            type:'post',
            data:data,
            contentType:false,
            processData:false,
            success:function(output) {

                btn_update_order_index.html(source_text_button);
                btn_update_order_index.removeAttr("disabled");
              
                var obj=JSON.parse(output); 
                if(obj.res==1){

                    $("#status_order_"+order_id).empty();
                    if(obj.status=="1"){
                        status_new="Under Process";
                        class_text="text-primary";
                    }else if(obj.status==3){
                        status_new="Completed";
                        class_text="text-success";
                    }else{
                        status_new="Canceled";
                        class_text="text-danger";
                    }

                    $("#status_order_"+order_id).append(status_new);
                    $("#status_order_"+order_id).attr("class",class_text);
                    if(obj.status=="1"){
                        $("#add_to_sales_"+order_id).attr("disabled","disabled");
                    }else if(obj.status==3){
                        $("#add_to_sales_"+order_id).removeAttr("disabled");
                    }else{
                        $("#add_to_sales_"+order_id).attr("disabled","disabled");
                    }
                    
                    $("#modal-details-order-"+order_id).slideUp(function(){
                        $(".modal-backdrop").hide();
                        $("body").removeClass("modal-open");
                    });

                    $("#alert-success-update-order").show();

                    setTimeout(function(){
                        $("#alert-success-update-order").hide();
                    },3000);

                    $("body").removeAttr("class");
                    $("body").removeAttr("style");

                }else{
                    $("#alert-danger-update-order").show();
                    setTimeout(function(){
                        $("#alert-danger-update-order").hide();
                    },3000);
                }
              
            },
            error: function(error){
                btn_update_order_index.html(source_text_button);
                btn_update_order_index.removeAttr("disabled");
                $("#alert-danger-update-order").show();
                setTimeout(function(){
                    $("#alert-danger-update-order").hide();
                },3000);
            }
        });

}

function delete_order(order_id){

    btn_delete_order_index=$("#btn_delete_order_"+order_id);

    source_text_button=btn_delete_order_index.html();
    parent_text_button="<div class='spinner-border text-dark'></div>";

    btn_delete_order_index.html(parent_text_button);
    btn_delete_order_index.attr("disabled","disabled");

    token_index=$("#token");
    token=token_index.val();
    if(token == undefined || token == ""){
        return;
    }
  
            data=new FormData()
            data.append("token",token);
            data.append("order_id",order_id);
    
            $.ajax({
                url:'php_ajax/delete_order.php',
                type:'post',
                data:data,
                contentType:false,
                processData:false,
                success:function(output) {

                    btn_delete_order_index.html(source_text_button);
                    btn_delete_order_index.removeAttr("disabled");

                    var obj=JSON.parse(output); 
                    if(obj.res==1){
                        $("#modal-delete-order-"+order_id).hide();
                        $("body").removeClass("modal-open");
                        $(".modal-backdrop").remove();
                        
                        $("body").removeAttr("class");
                        $("body").removeAttr("style");

                        $("#row_order_"+order_id).fadeOut(function(){
                            $("#orders").load(location.href + " #orders ");
                        });
                        
                        $("#alert-success-delete-order").show();
                        $("#alert-danger-update-order").hide();

                        $("body").removeAttr("class");
                        $("body").removeAttr("style");
      
                        setTimeout(function(){
                            $("#alert-success-delete-order").hide();
                        },3000);

                    }else{
                        $("#alert-success-delete-order").hide();
                        $("#alert-danger-update-order").show();
    
                        setTimeout(function(){
                            $("#alert-danger-update-order").hide();
                        },3000);
                    }
                  
                },
                error: function(error){
                    btn_delete_order_index.html(source_text_button);
                    btn_delete_order_index.removeAttr("disabled");
                    $("#alert-success-delete-order").hide();
                    $("#alert-danger-update-order").show();
    
                    setTimeout(function(){
                        $("#alert-danger-update-order").hide();
                    },3000);
                }
            });
         
}

function add_to_sales(order_id){

    chk_add_to_sales_index=$("#container_chk_add_to_sales_"+order_id);

    source_text_button=chk_add_to_sales_index.html();
    parent_text_button="<div class='spinner-border text-dark'></div>";

    if($("#add_to_sales_"+order_id).is(":checked")){
        is_checked=1;
    }else{
        is_checked=0;
    }

    chk_add_to_sales_index.html(parent_text_button);
    chk_add_to_sales_index.attr("disabled","disabled");

    $("#alert-danger-update-order").hide();

    token_index=$("#token");
    token=token_index.val();
    if(token == undefined || token == ""){
        return;
    }
    
    data=new FormData();
    data.append("token",token);
    data.append("is_checked",is_checked);
    data.append("order_id",order_id);

    $.ajax({
        url:'php_ajax/add_remove_sales.php',
        type:'post',
        data:data,
        contentType:false,
        processData:false,
        success:function(output) {

            chk_add_to_sales_index.html(source_text_button);
            chk_add_to_sales_index.removeAttr("disabled");

            if(is_checked == 1){
                $("#add_to_sales_"+order_id).prop("checked", true);
            }else{
                $("#add_to_sales_"+order_id).prop("checked", false);
            }
          
            var obj=JSON.parse(output); 
            if(obj.res==0){
                $("#alert-danger-update-order").show();
                setTimeout(function(){
                    $("#alert-danger-update-order").hide();
                },3000);
            }
          
        },
        error: function(error){
            chk_add_to_sales_index.html(source_text_button);
            chk_add_to_sales_index.removeAttr("disabled");
            $("#alert-danger-update-order").show();
            setTimeout(function(){
                $("#alert-danger-update-order").hide();
            },3000);
        }
    });

}

function reload(){
    $("#section_orders").attr("style","display:block !important;");
    $("#section_container_print").hide();
    
    $(window).scrollTop(scrollTop);
}

function print(){
    // $("#container_print_invoice").printThis({
    //     debug: false,               // show the iframe for debugging
    //     importCSS: true,            // import page CSS
    //     importStyle: false,         // import style tags
    //     printContainer: true,       // grab outer container as well as the contents of the selector
    //     loadCSS: "",  // path to additional css file - us an array [] for multiple
    //     pageTitle: "",              // add title to print page
    //     removeInline: false,        // remove all inline styles from print elements
    //     printDelay: 500,            // variable print delay
    //     header: null,               // prefix to html
    //     footer: null,               // postfix to html
    //     base: false,                // preserve the BASE tag, or accept a string for the URL
    //     formValues: true,           // preserve input/form values
    //     canvas: false,              // copy canvas elements (experimental)
    //     doctypeString: '',       // enter a different doctype for older markup
    //     removeScripts: false,       // remove script tags from print content
    //     copyTagClasses: false,       // copy classes from the html & body tag

    // });
    
    btn_index = $(".btn-print");
    source_text_button = btn_index.html();
    parent_text_button = "<div class='spinner-border text-dark'></div>";

    btn_index.html(parent_text_button);
    btn_index.attr("disabled", "disabled");

    invoice_type = btn_index.attr("id");
    order_id = btn_index.attr("order_id");

    let url = "";
    if (invoice_type == "1") {
        url = "invoice/print_small_invoice.php?order_id=" + order_id;
    } else if (invoice_type == "2") {
        url = "invoice/print_large_invoice.php?order_id=" + order_id;
    }

    if (url) {
        window.open(url, "_blank");
    }

    btn_index.html(source_text_button);
    btn_index.removeAttr("disabled");

}

var scrollTop;

function print_small_invoice(order_id){

    scrollTop = $(window).scrollTop();

    $(".btn-print").attr("id","1");

    btn_index=$("#btn_small_invoice_"+order_id);

    source_text_button=btn_index.html();
    parent_text_button="<div class='spinner-border text-dark'></div>";

    btn_index.html(parent_text_button);
    btn_index.attr("disabled","disabled");

    token_index=$("#token");
    token=token_index.val();
    if(token == undefined || token == ""){
        return;
    }

    data=new FormData();
    data.append("token",token);
    data.append("order_id",order_id);

    $.ajax({
        url:'php_ajax/print_small_invoice.php',
        type:'post',
        data:data,
        contentType:false,
        processData:false,
        success:function(output) {

            btn_index.html(source_text_button);
            btn_index.removeAttr("disabled");
          
            var obj=JSON.parse(output); 
            if(obj.res!=0){
                $("#modal-ask-print-"+order_id).hide();
                $(".modal-backdrop").remove();
                $("#section_orders").attr("style","display:none !important;");
                $("#container_print_invoice").html(obj.row);
                $("#section_container_print").show();
                $("body").removeAttr("class");
                $("body").removeAttr("style");
                $(".btn-print").attr("order_id",obj.order_id);
            }
          
        },
    });

}

function print_large_invoice(order_id){

    scrollTop = $(window).scrollTop();

    $(".btn-print").attr("id","2");

    btn_index=$("#btn_large_invoice_"+order_id);

    source_text_button=btn_index.html();
    parent_text_button="<div class='spinner-border text-dark'></div>";

    btn_index.html(parent_text_button);
    btn_index.attr("disabled","disabled");

    token_index=$("#token");
    token=token_index.val();
    if(token == undefined || token == ""){
        return;
    }

    data=new FormData();
    data.append("token",token);
    data.append("order_id",order_id);

    $.ajax({
        url:'php_ajax/print_large_invoice.php',
        type:'post',
        data:data,
        contentType:false,
        processData:false,
        success:function(output) {

            btn_index.html(source_text_button);
            btn_index.removeAttr("disabled");
          
            var obj=JSON.parse(output); 
            if(obj.res!=0){
                $("#modal-ask-print-"+order_id).hide();
                $(".modal-backdrop").remove();
                $("#section_orders").attr("style","display:none !important;");
                $("#container_print_invoice").html(obj.row);
                $("#section_container_print").show();
                $("body").removeAttr("class");
                $("body").removeAttr("style");
                $(".btn-print").attr("order_id",obj.order_id);
            }
          
        },
    });
    
}

function print_small_large_invoice(order_id){

    btn_index=$("#btn_small_large_invoice_"+order_id);

    source_text_button=btn_index.html();
    parent_text_button="<div class='spinner-border text-dark'></div>";

    btn_index.html(parent_text_button);
    btn_index.attr("disabled","disabled");

    token_index=$("#token");
    token=token_index.val();
    if(token == undefined || token == ""){
        return;
    }
    
    data=new FormData();
    data.append("token",token);
    data.append("order_id",order_id);

    $.ajax({
        url:'php_ajax/print_small_large_invoice.php',
        type:'post',
        data:data,
        contentType:false,
        processData:false,
        success:function(output) {
            
            btn_index.html(source_text_button);
            btn_index.removeAttr("disabled");
          
            var obj=JSON.parse(output); 
            if(obj.res!=0){
                

                $("#modal-ask-print-"+order_id).hide();
                $(".modal-backdrop").remove();
                $("#section_orders").attr("style","display:none !important;");
                $("#container_print_invoice").html(obj.row);
                $("#section_container_print").show();
                $("body").removeAttr("class");
                $("body").removeAttr("style");
            }
          
        },
    });

}
