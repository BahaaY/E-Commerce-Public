$(function(){
    reload_data();
})

function load_table_manage_products(){

    token_index=$("#token");
    token=token_index.val();
    if(token == undefined || token == ""){
        return;
    }
    
    $("#table_users").DataTable({
        ajax: {
            url: 'php_ajax/load_table_users.php',
            type: 'post',
            dataSrc: '',
            data: function(data){
                data.token=token
            }
        },
        columns: [
            { data: 'serial_number' },
            { data: 'username' },
            { data: 'email' },
            //{ data: 'login_limit' },
            { data: 'action' }
        ]
    });
}

function reload_data(){
    $('#table_users').dataTable().fnClearTable();
    $('#table_users').dataTable().fnDestroy();
    load_table_manage_products();
}

function block_unblock_user(user_id,availability){

    if(availability == 1){
        block_user(user_id);
    }else if(availability == 0){
        unblock_user(user_id);
    }

}

function block_user(user_id){

    btn=$("#btn_block_user[index='"+user_id+"']");
    
    source_text_button=$("#key_block_user").val();
    parent_text_button="<div class='spinner-border text-dark'></div>";
    parent_text_button_blocked=$("#key_unblock_user").val();

    btn.html(source_text_button);
    btn.removeAttr("disabled");

    token_index=$("#token");
    token=token_index.val();
    if(token == undefined || token == ""){
        return;
    }

    data= new FormData();

    if(user_id != "" && token != ""){

        btn.html(parent_text_button);
        btn.attr("disabled","disabled");

        data.append("id",user_id);
        data.append("token",token);

        $.ajax({
            url: 'php_ajax/block_user.php',
            type: 'post',
            data: data,
            contentType:false,
            processData:false,
            success:function(output) {

                obj=JSON.parse(output);
                if(obj.error == 0){
                    btn.html(parent_text_button_blocked);
                    btn.removeAttr("disabled");
                    btn.attr("onclick","block_unblock_user('"+user_id+"','"+0+"');");
                    btn.attr("class","btn btn-danger m-1");
                }
    
            },
        });
    }

}

function unblock_user(user_id){
    
    btn=$("#btn_block_user[index='"+user_id+"']");

    source_text_button=$("#key_unblock_user").val();
    parent_text_button="<div class='spinner-border text-dark'></div>";
    parent_text_button_unblocked=$("#key_block_user").val();

    btn.html(source_text_button);
    btn.removeAttr("disabled");

    token_index=$("#token");
    token=token_index.val();
    if(token == undefined || token == ""){
        return;
    }

    data= new FormData();

    if(user_id != "" && token != ""){

        btn.html(parent_text_button);
        btn.attr("disabled","disabled");

        data.append("id",user_id);
        data.append("token",token);

        $.ajax({
            url: 'php_ajax/unblock_user.php',
            type: 'post',
            data: data,
            contentType:false,
            processData:false,
            success:function(output) {

                obj=JSON.parse(output);
                if(obj.error == 0){
                    btn.html(parent_text_button_unblocked);
                    btn.removeAttr("disabled");
                    btn.attr("onclick","block_unblock_user('"+user_id+"','"+1+"');");
                    btn.attr("class","btn btn-primary m-1");
                }
    
            },
        });
    }

}

// function update_user(user_id){

//     btn=$("#btn_update_user[index='"+user_id+"']");
//     index_login_limit=$("#login_limit[index='"+user_id+"']");
//     index_error_login_limit=$("#error_login_limit[index='"+user_id+"']");
//     login_limit=index_login_limit.val();
//     index_alert_success=$("#alert-success");

//     index_alert_success.hide();
//     source_text_button=btn.html();
//     parent_text_button="<div class='spinner-border text-dark'></div>";

//     btn.html(source_text_button);
//     btn.removeAttr("disabled");
//     index_error_login_limit.html("");

//     if(login_limit == undefined){
//         login_limit = "";
//     }

//     if(login_limit == "" || login_limit < 0){
//         index_error_login_limit.html($("#key_required").val());
//     }

//     token_index=$("#token");
//     token=token_index.val();
//     if(token == undefined || token == ""){
//         return;
//     }

//     data= new FormData();

//     if(user_id != "" && login_limit != "" && login_limit > 0 && token != ""){

//         btn.html(parent_text_button);
//         btn.attr("disabled","disabled");

//         data.append("id",user_id);
//         data.append("login_limit",login_limit);
//         data.append("token",token);

//         $.ajax({
//             url: 'php_ajax/update_user.php',
//             type: 'post',
//             data: data,
//             contentType:false,
//             processData:false,
//             success:function(output) {

//                 obj=JSON.parse(output);
//                 if(obj.error == 0){
//                     btn.html(source_text_button);
//                     btn.removeAttr("disabled");
//                     index_alert_success.show();
//                     index_error_login_limit.html("");

//                     setTimeout(function(){
//                         index_alert_success.hide();
//                     },3000);

//                 }
    
//             },
//         });
//     }

// }

function reset_login_limit(user_id){

    btn=$("#btn_reset_login_limit[index='"+user_id+"']");
    index_alert_success=$("#alert-success");

    index_alert_success.hide();
    source_text_button=btn.html();
    parent_text_button="<div class='spinner-border text-dark'></div>";

    btn.html(source_text_button);
    btn.removeAttr("disabled");

    token_index=$("#token");
    token=token_index.val();
    if(token == undefined || token == ""){
        return;
    }

    data= new FormData();

    if(user_id != "" && token != ""){

        btn.html(parent_text_button);
        btn.attr("disabled","disabled");

        data.append("id",user_id);
        data.append("token",token);

        $.ajax({
            url: 'php_ajax/reset_login_limit.php',
            type: 'post',
            data: data,
            contentType:false,
            processData:false,
            success:function(output) {

                obj=JSON.parse(output);
                if(obj.error == 0){
                    btn.html(source_text_button);
                    btn.removeAttr("disabled");
                    index_alert_success.show();

                    setTimeout(function(){
                        index_alert_success.hide();
                    },3000);

                }
    
            },
        });
    }

}