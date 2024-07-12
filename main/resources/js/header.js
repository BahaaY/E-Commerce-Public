$(document).ready(function(){

    $('#dropdown_menu_notification').click(function(event) {
        event.stopPropagation();
    });

    var counter_dropdown_opened=0;

    $("#icon_open_notification").click(function(){

        token_index=$("#token");
        token=token_index.val();
        if(token == undefined || token == ""){
            return;
        }

        $.ajax({
            url:'../resources/php_ajax/read_notification.php',
            type:'post',
            data:"token="+token,
            success:function(output) {
    
                var obj=JSON.parse(output);
                
                if(obj.error == 0){ 
                    
                    $("#notification_number").empty();
                    $("#notification_number").fadeOut();

                    counter_dropdown_opened++;

                    if(counter_dropdown_opened == 2){
                        $("#text_notification_number").text(0);
                        document.querySelectorAll("#li_container_notification_text").forEach(function(element){
                            element.classList.remove('bg-light');
                        });
                    }

                }
    
            },
        });

    });

});

function copy_reference_number(order_reference_number){
    
    if(order_reference_number != ""){
        navigator.clipboard.writeText(order_reference_number);
        $("#title_toast_copy_reference_number").text("Reference number");
        $("#body_toast_copy_reference_number").html(order_reference_number+"<br>Reference number copied.");
    }else{
        navigator.clipboard.writeText("");
        $("#title_toast_copy_reference_number").text("Reference number");
        $("#body_toast_copy_reference_number").text("Reference number not assigned.");
    }
    
    $('.container_toast_copy_reference_number').show();
    $('.toast_copy_reference_number').toast('show');
    setTimeout(function(){
        $('.container_toast_copy_reference_number').fadeOut();
    },4000);
}

function open_modal_ask_clear_all_notifications(){
    $("#modal-ask-clear-all-notifications").modal("show");
}

function close_modal_ask_clear_all_notifications(){
    $("#modal-ask-clear-all-notifications").modal("hide");
}

function clear_all_notifications(){

    source_text_button="<i class='bi bi-trash mr-2'></i>Clear";
    parent_text_button="<div class='spinner-border text-dark'></div>";
    btn_clear_all_notifications_index=$("#btn_clear_all_notifications");

    btn_clear_all_notifications_index.html(parent_text_button);
    btn_clear_all_notifications_index.attr("disabled","disabled");

    token_index=$("#token");
    token=token_index.val();
    if(token == undefined || token == ""){
        return;
    }

    $.ajax({
        url:'../resources/php_ajax/clear_all_notification.php',
        type:'post',
        data:"token="+token,
        success:function(output) {

            btn_clear_all_notifications_index.html(source_text_button);
            btn_clear_all_notifications_index.removeAttr("disabled");
            close_modal_ask_clear_all_notifications();

            var obj=JSON.parse(output);
            
            if(obj.error == 0){ 

                $("#dropdown_menu_notification").empty();
                $("#dropdown_menu_notification").append("<li class='dropdown-header'>You have <span id='text_notification_number'>0</span> new notifications</li>");

            }

        },
    });

}