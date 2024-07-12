<?php

    if(session_status() !== PHP_SESSION_ACTIVE){
        session_start();
    }

    
    require_once '../../../config/variables.php';
    require_once "../../../config/helper.php";
    
    if (!isset($_SESSION[Session::$KEY_EC_USERID])) {
        header("location:../../../forbidden");
    }

    $obj=new stdClass();
    $image_path="";
    $result=0;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (isset($_FILES['profile_image']) && isset($_POST['token'])) {

            try{

                $token=$_POST['token'];
                    
                if(isset($_SESSION[Session::$KEY_EC_TOKEN])){
                    $token_session=$_SESSION[Session::$KEY_EC_TOKEN];
                    if($token_session == $token){
                        $file = $_FILES['profile_image'];

                        $array_allowed_files = array(
                            'image'
                        );
                
                        if(validation_file($file, $array_allowed_files)){
                            $result=1;
                        }else{
                            $result=0;
                        }
                    }else{
                        $result=0;
                    }
                }else{
                    $result=0;
                }

            }catch(PDOException $ex){

                $result=0;
                
            }

        }else{
            $result=0;
        }

    }else{
        $result=0;
    }

    $obj->result=$result;
    if(isset($_SESSION['EC_image_path'])){
        $image_path=$_SESSION['EC_image_path'];
        unset($_SESSION['EC_image_path']);
    }else{
        $image_path="";
    }
    $obj->image_path=$image_path;
    echo json_encode($obj);

    function validation_file($file, $array_allowed_files){

        if ($file['error'] == 0) {

            $source_file_name = $file['name'];
            $explode_file_name = explode('.', strtolower($source_file_name));
            $source_file_extension = end($explode_file_name);
            $source_file_tmp_path = $file['tmp_name'];
            $source_file_mime_type = mime_content_type($source_file_tmp_path);

            $is_allowed = false;
            $is_valid = false;
            $is_dangerous = false;

            $array_file_mime_type = array(
                "word" => "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
                "excel" => "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
                "pdf" => "application/pdf",
                "image" => "image/jpeg,image/x-png,image/png",
                "gif"=>"image/gif",
                "powerpoint" => "application/vnd.openxmlformats-officedocument.presentationml.presentation",
                "access" => "application/x-msaccess",
            );

            $array_file_extension = array(
                "word" => "docx,doc",
                "excel" => "xlsx,xls",
                "pdf" => "pdf",
                "image" => "jpg,jpeg,png",
                "gif"=>"gif",
                "powerpoint" => "pptx,ppt",
                "access" => "accdb",
            );

            //Check if file is allowed
            foreach ($array_allowed_files as $allowed_file) {
                $allowed_file = preg_replace("/[^A-Za-z\-]/", "", strtolower($allowed_file));
                if (array_key_exists($allowed_file, $array_file_mime_type)) {
                    foreach (explode(",", $array_file_mime_type[$allowed_file]) as $file_mime_type) {
                        if ($source_file_mime_type == $file_mime_type) {
                            $is_allowed = true;
                            $file_type = $allowed_file;
                        }
                    }
                }
            }

            if ($is_allowed) {
                //File is allowed

                //Check if file is valid
                if (array_key_exists($file_type, $array_file_extension)) {
                    foreach (explode(",", $array_file_extension[$file_type]) as $file_extension) {
                        if ($file_extension == $source_file_extension) {
                            $is_valid = true;
                        }
                    }
                }

                if ($is_valid) {
                    //File is valid

                    $file_contents = file_get_contents($source_file_tmp_path);

                    $dangerous_patterns = array(
                        'eval',
                        'system',
                        'exec',
                        'shell_exec',
                        'passthru',
                        'popen',
                        'proc_open',
                        'assert',
                        'create_function',
                        'ini_set',
                        '<script',
                        'onload',
                        'onclick',
                        'onmouseover',
                        'onmouseout',
                        'onmousedown',
                        'onmouseup',
                        'onmousemove',
                        'onerror',
                        '<?php',
                        '<?=',
                        '$2y$10$',
                        'html',
                        'echo',
                        'copy',
                        'unlink',
                        'phpinfo',
                        'file_get_contents',
                        'fwrite',
                        'preg_replace',
                        'str_replace',
                        'mysqli_query',
                        'pg_query',
                        'pg_query_params',
                        'apache_setenv',
                        'apache_child_terminate',
                        'posix_kill',
                        'kill',
                        '1#090',
                        'function',
                        'parse_ini_file',
                    );

                    //Check if file is dangerous
                    foreach ($dangerous_patterns as $pattern) {
                        if (strpos($file_contents, $pattern)) {
                            $is_dangerous = false;
                            break;
                        }
                    }

                    if ($is_dangerous) {
                        // Allowed valid danger
                        // echo "Allowed valid danger";
                        return false;
                    } else {
                        //Allowed valid not danger
                        //echo "Allowed valid not danger";
                        if(upload_file($source_file_extension, $source_file_tmp_path)){
                            return true;
                        }
                        
                    }
                } else {
                    //Allowed not valid
                    //echo "Allowed not valid";
                    return false;
                }
            } else {
                //Not allowed
                //echo "Not allowed";
                return false;
            }
        } else {
            //Error exist
            //echo "Error exist";
            return false;
        }
    }

    function upload_file($source_file_extension, $source_file_tmp_path) {

        $path_dir = "../../uploaded_images_profile/";

        $updated_file_name = "IMAGE-" . time() . "." . $source_file_extension . "";
        $is_file_exist = $path_dir . $updated_file_name;

        //Check if folder exist
        if (is_dir($path_dir)) {
            //Check if file exist
            if (!file_exists($is_file_exist)) {
                if (move_uploaded_file($source_file_tmp_path, $path_dir . $updated_file_name)) {

                    require_once '../classes/index/users.php';
                    require_once '../../../config/variables.php';
                    require_once '../../../config/conn.php';

                    $user_id=$_SESSION[Session::$KEY_EC_USERID];
                    $user_id=Helper::decrypt($user_id);

                    $class_users=new Users($db_conn->get_link());
                     
                    if($class_users->update_profile_image($user_id,$updated_file_name)){
                        $_SESSION['EC_image_path']="../uploaded_images_profile/".$updated_file_name;
                        return true;
                    }else{
                        return false;
                    }
               
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
        
    }
    

?>