$(document).ready(function(){

    load_table_dictionary();

});

function load_table_dictionary(){

    token_index=$("#token");
    token=token_index.val();
    if(token == undefined || token == ""){
        return;
    }

    $('#table_dictionary').dataTable().fnClearTable();
    $('#table_dictionary').dataTable().fnDestroy();
    table = $('#table_dictionary').DataTable({
        searching: true,
        ajax: {
            url: 'php_ajax/load_table_dictionary.php',
            type:"post",
            dataSrc: '',
            data: function(data){
                data.token=token
            }
        },
        columns: [
            { data: 'serial_number' },
            { data: 'en' },
            { data: 'fr' },
            { data: 'ar' },
            { data: 'action' }
        ],
  
    });

}

function edit_dictionary(dictionary_id){

    btn_edit=$("#btn_edit_dictionary_"+dictionary_id);

    source_text_button=btn_edit.html();
    parent_text_button="<div class='spinner-border text-dark'></div>";
    required_message=$("#key_required").val();

    index_english=$("#english_"+dictionary_id);
    index_french=$("#french_"+dictionary_id);
    index_arabic=$("#arabic_"+dictionary_id);
    index_alert_success=$("#alert-success-dictionary");
    index_alert_danger=$("#alert-danger-dictionary");

    index_error_english=$("#error_english_"+dictionary_id);
    index_error_french=$("#error_french_"+dictionary_id);
    index_error_arabic=$("#error_arabic_"+dictionary_id);

    index_alert_success.hide();
    index_alert_danger.hide();
    index_error_english.empty();
    index_error_french.empty();
    index_error_arabic.empty();

    english=index_english.val();
    french=index_french.val();
    arabic=index_arabic.val();

    if(english == undefined){
        english="";
    }

    if(french == undefined){
        french="";
    }

    if(arabic == undefined){
        arabic="";
    }

    btn_edit.html(source_text_button);
    btn_edit.removeAttr("disabled");

    if(english == ""){
        index_error_english.text(required_message);
    }else{
        index_error_english.text("");
    }

    if(french == ""){
        index_error_french.text(required_message);
    }else{
        index_error_french.text("");
    }

    if(arabic == ""){
        index_error_arabic.text(required_message);
    }else{
        index_error_arabic.text("");
    }

    token_index=$("#token");
    token=token_index.val();
    if(token == undefined || token == ""){
        return;
    }

    if(english != "" && french != "" && index_error_arabic != "" && dictionary_id != "" && token != ""){

        btn_edit.attr("disabled","disabled");
        btn_edit.html(parent_text_button);

        data=new FormData();
        data.append("id",dictionary_id);
        data.append("english",english);
        data.append("french",french);
        data.append("arabic",arabic);
        data.append("token",token);

        $.ajax({
            url:'php_ajax/edit_dictionary.php',
            type:'post',
            data:data,
            contentType:false,
            processData:false,
            success:function(output) {

                btn_edit.removeAttr("disabled");
                btn_edit.html(source_text_button);

                var obj=JSON.parse(output); 
                if(obj.res == 1){
                    index_alert_success.show();
                    setTimeout(function(){
                        index_alert_success.hide();
                        location.reload();
                    },3000);
                }else{
                    index_alert_danger.show();
                    setTimeout(function(){
                        index_alert_danger.hide();
                        location.reload()
                    },3000);
                }
            },
            error: function(error){
                btn_edit.removeAttr("disabled");
                btn_edit.html(source_text_button);
                index_alert_danger.show();
                setTimeout(function(){
                    index_alert_danger.hide();
                },3000);
            }
        });

    }

}