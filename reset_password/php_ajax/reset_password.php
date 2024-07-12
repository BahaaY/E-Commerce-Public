<?php

    if(session_status() !== PHP_SESSION_ACTIVE){
        session_start();
    }

    require_once '../../config/conn.php';
    require_once '../../config/variables.php';
    require_once '../../config/helper.php';

    require_once '../classes/users.php';

    $obj = new stdClass();
    
    $res=0;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (isset($_POST['new_password']) && isset($_POST['id'])) {

            try{

                $new_password = $_POST['new_password'];
                $id_encrypted = $_POST['id'];

                if(is_valid_password($new_password) == 1 && $id_encrypted != ""){
                        
                    $class_users = new Users($db_conn->get_link());

                    $user_id=Helper::decrypt($id_encrypted);

                    if($user_id){
                        if ($class_users->reset_user_password($user_id,$new_password) == 1) {

                            $res=1;
            
                        } else {
                            $res = 0;
                        }
            
                    } else {
                        $res = 0;
                    }

                }else{
                    $res = 0;
                }

            }catch(PDOException $ex){
        
                $res=0;
        
            }

        }else{
            $res = 0;
        }

    }else{
        $res = 0;
    }
    
    $obj->res = $res;

    function is_valid_password($password)
    {
        $space = preg_match('/^[^ ].* .*[^ ]$/', $password);
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number = preg_match('@[0-9]@', $password);
        $special_char = preg_match('@[\W]@', $password);

        if (strlen(trim($password)) == 0) {
            return 0;
        }

        if (strlen(trim($password)) < 6) {
            return 3;
        }

        if ($space && !$uppercase && !$lowercase && !$number && !$special_char) {
            return 2;
        }

        return 1;
    }

    echo json_encode($obj);

?>
