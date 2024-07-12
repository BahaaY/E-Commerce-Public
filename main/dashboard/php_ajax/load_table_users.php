<?php

    if(session_status() !== PHP_SESSION_ACTIVE){
        session_start();
    }

    require_once "../../../config/variables.php";
    require_once "../../../config/conn.php";
    require_once "../../../config/helper.php";
    require_once "../classes/users.php";
    require_once "../../../lang/key.php";
    require_once "../../resources/classes/dictionary.php";

    $data1 = array();
    $data2 = array();
    $serial_number=0;

    if (!isset($_SESSION[Session::$KEY_EC_USERID])) {
        header("location:../../../forbidden");
    }

    if(isset($_SESSION[Session::$KEY_EC_LANG])) {
        $lang=$_SESSION[Session::$KEY_EC_LANG];
    }else {
        $lang="en";
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if(isset($_POST['token'])){

            try{

                $token=$_POST['token'];
            
                if(isset($_SESSION[Session::$KEY_EC_TOKEN])){
                    $token_session=$_SESSION[Session::$KEY_EC_TOKEN];
                    if($token_session == $token){
                        $class_users=new Users($db_conn->get_link());
                        $dictionary=new Dictionary($db_conn->get_link());
                        $users=$class_users->get_all_users();
                    
                        if($users){
                            foreach($users as $user){
                                $user_id=$user['user_id'];
                                $username=$user['username'];
                                $email=$user['email'];
                                $availability=$user['availability'];
                                $login_limit=$user['login_limit'];
                                if($availability == 1){
                                    $text=$dictionary->get_lang($lang,$KEY_BLOCK_USER);
                                    $color="primary";
                                }else{
                                    $text=$dictionary->get_lang($lang,$KEY_UNBLOCK_USER);
                                    $color="danger";
                                }
                                $user_id=Helper::string_hash($user_id);
                                $action="
                                    <button type='button' class='btn btn-".$color." m-1' index='".$user_id."' id='btn_block_user' onclick=block_unblock_user('".$user_id."','".$availability."');>".$text."</button>
                                    <button type='button' class='btn btn-success m-1 d-none' index='".$user_id."' id='btn_update_user' onclick=update_user('".$user_id."');>".$dictionary->get_lang($lang,$KEY_UPDATE)."</button>
                                    <button type='button' class='btn btn-success m-1' index='".$user_id."' id='btn_reset_login_limit' onclick=reset_login_limit('".$user_id."');>".$dictionary->get_lang($lang,$KEY_RESET_LOGIN_LIMIT)."</button>
                                ";
                                $serial_number++;
                                $data1['serial_number'] = "<b>".$serial_number."</b>";
                                $data1['username'] = $username;
                                $data1['email'] = $email;
                                // $data1['login_limit'] = "
                                //     <input class='form-control' type='text' id='login_limit' index='".$user_id."' placeholder='".$dictionary->get_lang($lang,$KEY_LOGIN_LIMIT)."' value='".$login_limit."'>
                                //     <span class='text-danger' id='error_login_limit' index='".$user_id."'></span>
                                // ";
                                $data1['action'] = $action;
                                $data2[] = $data1;
                            }
                        }else{
                            $data1 = array();
                            $data2 = array();
                        }
                    }else{
                        $data1 = array();
                        $data2 = array();
                    }
                }else{
                    $data1 = array();
                    $data2 = array();
                }
                
            }catch(PDOException $ex){
        
                $data1 = array();
                $data2 = array();
            
            }

        }else{
            $data1 = array();
            $data2 = array();
        }

    }else{
        $data1 = array();
        $data2 = array();
    }
    
    echo json_encode($data2);
?>