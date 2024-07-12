$(document).ready(function() {

    $('input').attr('autocomplete','off');

    $("#btn_update_image_profile").change(function() {

        token_index=$("#token");
        token=token_index.val();
        if(token == undefined || token == ""){
            return;
        }

        profile_image_file = this.files[0];

        var data = new FormData();

        data.append("profile_image", profile_image_file);
        data.append('token', token);

        $.ajax({
            url: 'php_ajax/update_user_profile_image.php',
            type: 'post',
            data: data,
            contentType: false,
            processData: false,
            success: function(output) {

                var obj = JSON.parse(output);

                if (obj.result == 1) {

                    $("#alert-success-user-profile").show();

                    if(obj.image_path != ""){
                        $("#header_image_profile").attr("src",obj.image_path);
                        $("#source_image_profile").attr("src",obj.image_path);
                        $("#image_profile").attr("src",obj.image_path);
                        $("#btn-download-image-profile").attr("href",obj.image_path);
                    }

                    setTimeout(function(){
                        $("#alert-success-user-profile").hide();
                    },3000);

                }else{

                    $("#alert-danger-user-profile").show();

                    setTimeout(function(){
                        $("#alert-danger-user-profile").hide();
                    },3000);

                }

            },
            error: function(error){
                $("#alert-danger-user-profile").show();

                setTimeout(function(){
                    $("#alert-danger-user-profile").hide();
                },3000);
            }

        });

    });

    $("#btn_update_user_profile").click(function() {

        required_message="Required";
        source_text_button="<i class='bi bi-pen mr-2'></i>Save Changes";
        parent_text_button="<div class='spinner-border text-dark'></div>";
        btn_save_changes_index=$("#btn_update_user_profile");

        $(this).html(source_text_button);
        $(this).removeAttr("disabled");

        username = $("#username").val();
        country = $("#country").val();
        region = $("#region").val();
        address = $("#address").val();
        phone_number = $("#phone_number").val();

        if(username == undefined){
            username="";
            return;
        }

        if(country == undefined){
            country="";
            return;
        }

        if(region == undefined){
            region="";
            return;
        }

        if(address == undefined){
            address="";
            return;
        }

        if(phone_number == undefined){
            phone_number="";
            return;
        }

        $("#alert-success-user-profile").hide();
        $("#alert-danger-user-profile").hide();

        if (username == "") {
            $("#error_username").text(required_message);
        }else{
            $("#error_username").empty();
        }

        token_index=$("#token");
        token=token_index.val();
        if(token == undefined || token == ""){
            return;
        }

        if(username != "" && token!=""){

            btn_save_changes_index.attr("disabled","disabled");
            btn_save_changes_index.html(parent_text_button);

            data = new FormData();

            data.append('username', username);
            data.append('country', country);
            data.append('region', region);
            data.append('address', address);
            data.append('phone_number', phone_number);
            data.append('token', token);

            $.ajax({
                url: 'php_ajax/update_user_profile.php',
                type: 'post',
                data: data,
                contentType: false,
                processData: false,
                success: function(output) {

                    btn_save_changes_index.removeAttr("disabled");
                    btn_save_changes_index.html(source_text_button);
    
                    var obj = JSON.parse(output);

                    check_username=obj.error_username;
                    check_phone_number=obj.error_phone_number;

                    if(check_username != ""){
                        $("#error_username").text(check_username);
                    }

                    if(check_phone_number != ""){
                        $("#error_phone_number").text(check_phone_number);
                    }
    
                    if (check_username == "" && check_phone_number == "") {
                        
                        if (obj.result == 1) {

                            $("#alert-success-user-profile").show();

                            setTimeout(function(){
                                $("#alert-success-user-profile").hide();
                            },3000);

                            $("#div_header_username").text(username);
                            $("#div_header_menu_username").text(username);
                            $("#div_username").text(username);
                            $("#div_displayed_username").text(username);
                            $("#div_country").text(country);
                            $("#div_region").text(region);
                            $("#div_address").text(address);
                            if(phone_number != ""){
                                if (phone_number.startsWith("+961")) {
                                    phone_number = phone_number;
                                } else {
                                    phone_number = "+961"+phone_number;
                                }
                            }
                            $("#div_phone_number").text(phone_number);
                            $("#phone_number").empty();
                            $("#phone_number").val(phone_number);

                            $("#error_username").empty();
                            $("#error_phone_number").empty();

                        }else{

                            $("#error_username").empty();
                            $("#error_phone_number").empty();

                            $("#alert-danger-user-profile").show();

                            setTimeout(function(){
                                $("#alert-danger-user-profile").hide();
                            },3000);

                        }
    
                    }
                },
                error: function(error){
                    btn_save_changes_index.removeAttr("disabled");
                    btn_save_changes_index.html(source_text_button);
                    $("#error_username").empty();
                    $("#error_phone_number").empty();

                    $("#alert-danger-user-profile").show();

                    setTimeout(function(){
                        $("#alert-danger-user-profile").hide();
                    },3000);
                }
    
            });

        }

    });

});