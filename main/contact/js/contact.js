$(document).ready(function() {

    $('input').attr('autocomplete','off');

    $("#btn_send").click(function() {

        index_btn_send=$("#btn_send");

        source_text_button=index_btn_send.html();
        parent_text_button="<div class='spinner-border text-dark'></div>";

        $("#error_subject").empty();
        $("#error_message").empty();
        $("#error_name").empty();
        $("#error_email").empty();
        var name = $("#name").val();
        var email = $("#email").val();
        var subject = $("#subject").val();
        var message = $("#message").val();
        var frmData = new FormData();
        frmData.append("name", name);
        frmData.append("email", email);
        frmData.append("subject", subject);
        frmData.append("message", message);

        $(this).html(source_text_button);
        $(".alert-success").hide();
        $(".alert-danger").hide();

        index_required=$("#key_required").val();

        if (name == "") {
            $("#error_name").append(index_required);
        } else {
            $("#error_name").empty();
        }
        if (email == "") {
            $("#error_email").append(index_required);
        } else {
            $("#error_email").empty();
        }
        if (subject == "") {
            $("#error_subject").append(index_required);
        } else {
            $("#error_subject").empty();
        }
        if (message == "") {
            $("#error_message").append(index_required);
        } else {
            $("#error_message").empty();
        }
        
        if (name != "" && email != "" && subject != "" && message != "") {

            index_btn_send.attr("disabled","disabled");
            index_btn_send.html(parent_text_button);

            $.ajax({
                url: 'php_ajax/contact.php',
                type: 'post',
                data: frmData,
                contentType: false,
                processData: false,
                success: function(output) {

                    index_btn_send.removeAttr("disabled");
                    index_btn_send.html(source_text_button);

                    var obj = JSON.parse(output);
                    if (obj.res == 1) {
                        
                        $(".alert-danger").hide();
                        $(".alert-success").show();

                        setTimeout(function(){
                            $(".alert-success").hide();
                        },3000);

                        $("#name").val("");
                        $("#email").val("");
                        $("#subject").val("");
                        $("#message").val("");

                        $("#error_name").empty();
                        $("#error_email").empty();
                        $("#error_subject").empty();
                        $("#error_message").empty();

                    } else {

                        $(".alert-success").hide();
                        $(".alert-danger").show();
                        setTimeout(function(){
                            $(".alert-danger").hide();
                        },3000);

                        $("#name").val("");
                        $("#email").val("");
                        $("#subject").val("");
                        $("#message").val("");

                        $("#error_name").empty();
                        $("#error_email").empty();
                        $("#error_subject").empty();
                        $("#error_message").empty();

                    }
                },
                error: function(error){
                    index_btn_send.removeAttr("disabled");
                    index_btn_send.html(source_text_button);
                    $(".alert-success").hide();
                    $(".alert-danger").show();
                    setTimeout(function(){
                        $(".alert-danger").hide();
                    },3000);

                    $("#name").val("");
                    $("#email").val("");
                    $("#subject").val("");
                    $("#message").val("");

                    $("#error_name").empty();
                    $("#error_email").empty();
                    $("#error_subject").empty();
                    $("#error_message").empty();
                }
            });

        }
    })

})