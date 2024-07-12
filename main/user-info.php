<?php

    if (session_status() === PHP_SESSION_NONE){
        session_start();
    }

    require_once '../../config/variables.php';
    require_once '../../config/conn.php';
    require_once '../../config/helper.php';
    require_once 'user-profile/classes/index/users.php';

    $folder_path="../uploaded_images_profile/";
    $parent_folder_path="../../images/";
    $parent_profile_image="avatar.jpg";

    if (isset($_SESSION[Session::$KEY_EC_USERID])) {

        $user_id=$_SESSION[Session::$KEY_EC_USERID];
        $user_id=Helper::decrypt($user_id);
    
        $class_users=new Users($db_conn->get_link());
    
        $user_info=$class_users->get_user_data($user_id);
        $username=$user_info['username'];
        $email=$user_info['email'];
        $country=$user_info['country'];
        $region=$user_info['region'];
        $address=$user_info['address'];
        $phone_number=$user_info['phone_number'];
        
        $profile_image=$user_info['profile_image'];

        if($profile_image != "" || $profile_image != NULL){
            if(is_dir($folder_path)){
                if(file_exists($folder_path.$profile_image)){
                    $profile_image=$folder_path.$profile_image;
                }else{
                    $profile_image= $parent_folder_path.$parent_profile_image;
                }
            }else{
                $profile_image= $parent_folder_path.$parent_profile_image;
            }
        }else{
            $profile_image= $parent_folder_path.$parent_profile_image;
        }    

    }else{
        $username="";
        $email="";
        $country="";
        $region="";
        $address="";
        $phone_number="";
        $profile_image=$parent_folder_path.$parent_profile_image;
    }
 
?>