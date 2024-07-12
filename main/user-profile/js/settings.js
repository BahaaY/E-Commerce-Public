$(document).ready(function() {

    $("#btn_save_settings").click(function() {
        
        btn_save_settings_index=$("#btn_save_settings");

        source_text_button=btn_save_settings_index.html();
        parent_text_button="<div class='spinner-border text-dark'></div>";

        alert_success_index=$("#alert-success-settings");
        alert_danger_index=$("#alert-danger-settings");

        btn_save_settings_index.html(source_text_button);
        btn_save_settings_index.removeAttr("disabled");
        alert_success_index.hide();
        alert_danger_index.hide();

        var states = [];
        var ids = [];

        $('.checkbox_notification').each(function() {
            var state = $(this).is(':checked') ? 1 : 0;
            var id=$(this).attr("value");
            states.push(state);
            ids.push(id);
        });

        var is_checked_two_step_verification=0;
        if($("#two_step_verification").is(":checked")){
            is_checked_two_step_verification=1;
        }else{
            is_checked_two_step_verification=0;
        }

        token_index=$("#token");
        token=token_index.val();
        if(token == undefined || token == ""){
            return;
        }

        if(states.length == ids.length && token!=""){

            btn_save_settings_index.html(parent_text_button);
            btn_save_settings_index.attr("disabled","disabled");
            
            param="states="+states+"&ids="+ids+"&two_step_verification="+is_checked_two_step_verification+"&token="+token;
            $.ajax({
                type: "POST",
                url:"php_ajax/update_settings.php",
                data:param,
                success: function(output)
                {       

                    btn_save_settings_index.html(source_text_button);
                    btn_save_settings_index.removeAttr("disabled");

                    obj=JSON.parse(output);
                    if(obj.error == 0){
                        alert_success_index.show();
                        setTimeout(function(){
                            alert_success_index.hide();
                        },3000);
                    }else{
                        alert_danger_index.show();
                        setTimeout(function(){
                            alert_danger_index.hide();
                        },3000);
                    }

                },
                error: function(error){
                    btn_save_settings_index.html(source_text_button);
                    btn_save_settings_index.removeAttr("disabled");
                    alert_danger_index.show();
                    setTimeout(function(){
                        alert_danger_index.hide();
                    },3000);
                }
            });

        }else{
            alert_danger_index.show();
            setTimeout(function(){
                alert_danger_index.hide();
            },3000);
        }
       
    });

});

// $(document).ready(function() {

//     $("#btn_save_settings").click(function() {
        
//         btn_save_settings_index=$("#btn_save_settings");

//         source_text_button=btn_save_settings_index.html();
//         parent_text_button="<div class='spinner-border text-dark'></div>";

//         alert_success_index=$("#alert-success-settings");
//         alert_danger_index=$("#alert-danger-settings");

//         btn_save_settings_index.html(source_text_button);
//         btn_save_settings_index.removeAttr("disabled");
//         alert_success_index.hide();
//         alert_danger_index.hide();

//         var is_checked_two_step_verification=0;

//         if($("#two_step_verification").is(":checked")){
//             is_checked_two_step_verification=1;
//         }else{
//             is_checked_two_step_verification=0;
//         }

//         token_index=$("#token");
//         token=token_index.val();
//         if(token == undefined || token == ""){
//             return;
//         }

//         if(token!=""){

//             btn_save_settings_index.html(parent_text_button);
//             btn_save_settings_index.attr("disabled","disabled");
            
//             param="two_step_verification="+is_checked_two_step_verification+"&token="+token;
//             $.ajax({
//                 type: "POST",
//                 url:"php_ajax/update_settings.php",
//                 data:param,
//                 success: function(output)
//                 {       

//                     btn_save_settings_index.html(source_text_button);
//                     btn_save_settings_index.removeAttr("disabled");

//                     obj=JSON.parse(output);
//                     if(obj.error == 0){
//                         alert_success_index.show();
//                         setTimeout(function(){
//                             alert_success_index.hide();
//                         },3000);
//                     }else{
//                         alert_danger_index.show();
//                         setTimeout(function(){
//                             alert_danger_index.hide();
//                         },3000);
//                     }

//                 },
//                 error: function(error){
//                     btn_save_settings_index.html(source_text_button);
//                     btn_save_settings_index.removeAttr("disabled");
//                     alert_danger_index.show();
//                     setTimeout(function(){
//                         alert_danger_index.hide();
//                     },3000);
//                 }
//             });

//         }else{
//             alert_danger_index.show();
//             setTimeout(function(){
//                 alert_danger_index.hide();
//             },3000);
//         }
       
//     });

// });