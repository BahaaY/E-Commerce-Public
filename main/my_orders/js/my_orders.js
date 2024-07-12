$(document).ready(function(){
    $('input').attr('autocomplete','off');
})

function clear_alert(){
    $(".alert").empty();
    $(".alert").hide();
}

function cancel_order(order_id){
    
    btn_cancel_order_index=$("#btn_cancel_order_"+order_id);

    source_text_button=btn_cancel_order_index.html();
    parent_text_button="<div class='spinner-border text-dark'></div>";

    btn_cancel_order_index.html(parent_text_button);
    btn_cancel_order_index.attr("disabled","disabled");
    
    token_index=$("#token");
    token=token_index.val();
    if(token == undefined || token == ""){
        return;
    }
  
    data=new FormData()
    data.append("token",token);
    data.append("order_id",order_id);
    
    $.ajax({
        url:'php_ajax/cancel_order.php',
        type:'post',
        data:data,
        contentType:false,
        processData:false,
        success:function(output) {

            btn_cancel_order_index.html(source_text_button);
            btn_cancel_order_index.removeAttr("disabled");

            var obj=JSON.parse(output); 
            if(obj.res==1){
                //$("#modal-details-order-"+order_id).empty();
                $("#modal-details-order-"+order_id).hide();
                $("#modal-cancel-order-"+order_id).hide();
                $("body").removeClass("modal-open");
                $(".modal-backdrop").remove();

                $("#row_order_"+order_id).fadeOut(function(){
                    $("#my_orders").load(location.href + " #my_orders ");
                });
                        
                $("#alert-success-cancel-order").show();
                $("#alert-danger-cancel-order").hide();

                $("body").removeAttr("class");
                $("body").removeAttr("style");
      
                setTimeout(function(){
                    $("#alert-success-cancel-order").hide();
                },3000);

            }else{
                $("#alert-success-cancel-order").hide();
                $("#alert-danger-cancel-order").show();
    
                setTimeout(function(){
                    $("#alert-danger-cancel-order").hide();
                },3000);
            }
                  
        },
        error: function(error){
            btn_cancel_order_index.html(source_text_button);
            btn_cancel_order_index.removeAttr("disabled");
            $("#alert-success-cancel-order").hide();
            $("#alert-danger-cancel-order").show();
            setTimeout(function(){
                $("#alert-danger-cancel-order").hide();
            },3000);
        }
    });
         
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

