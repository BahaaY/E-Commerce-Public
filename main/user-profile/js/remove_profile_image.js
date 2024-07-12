$(document).ready(function(){
    $('input').attr('autocomplete','off');
})

function remove_profile_image(){

    token_index=$("#token");
    token=token_index.val();
    if(token == undefined || token == ""){
        return;
    }

    var data = new FormData();
    data.append('token', token);

    $.ajax({
        url: 'php_ajax/remove_user_profile.php',
        type: 'post',
        data: data,
        contentType: false,
        processData: false,
        success: function(output) {

            var obj = JSON.parse(output);

            if (obj.error == 0) {

                $("#alert-success-user-profile").show();

                image_path="../../images/avatar.jpg";

                $("#header_image_profile").attr("src",image_path);
                $("#source_image_profile").attr("src",image_path);
                $("#image_profile").attr("src",image_path);
                $("#btn-download-image-profile").attr("href",image_path);
                
                $("#remove-profile-image").slideUp(function(){
                    $(".modal-backdrop").hide();
                });

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
}