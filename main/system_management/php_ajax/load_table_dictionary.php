<?php

    if(session_status() !== PHP_SESSION_ACTIVE){
        session_start();
    }
    require_once "../../../config/variables.php";
    require_once '../../../config/conn.php';
    require_once '../classes/dictionary_key.php';
    require_once '../../../lang/key.php';
    require_once '../../resources/classes/dictionary.php';

    if (!isset($_SESSION[Session::$KEY_EC_USERID])) {
        header("location:../../../forbidden");
    }

    $dictionary = new Dictionary($db_conn->get_link());

    if(isset($_SESSION[Session::$KEY_EC_LANG])) {
        $lang=$_SESSION[Session::$KEY_EC_LANG];
    }else {
        $lang="en";
    }
    
    $data1 = array();
    $data2 = array();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
        if(isset($_POST['token'])){

            try{

                $token=$_POST['token'];
            
                if(isset($_SESSION[Session::$KEY_EC_TOKEN])){
                    $token_session=$_SESSION[Session::$KEY_EC_TOKEN];
                    if($token_session == $token){
        
                        $class_dictionary = new DictionaryKey($db_conn->get_link());
                        $all_keys = $class_dictionary->get_all_key();
        
                        if ($all_keys) {
        
                            $serial_number=0;
                    
                            foreach ($all_keys as $key) {
                    
                                $serial_number++;
                    
                                $dictionary_id = $key['dictionary_id'];
                                $en = $key['en'];
                                $fr = $key['fr'];
                                $ar = $key['ar'];
                                
                                $action="
                                    <button type='button' class='btn btn-primary m-1' id='btn_edit_dictionary_".$dictionary_id."' title='Edit' onclick='edit_dictionary(".$dictionary_id.")';><i class='bi bi-pen mr-2 ml-2'></i><span class='d-none d-sm-inline-block'>".$dictionary->get_lang($lang,$KEY_EDIT)."</span></button>
                                ";
                                
                                $data1['serial_number'] = $serial_number;
                                $data1['en'] = "
                                    <input type='text' class='form-control' id='english_".$dictionary_id."' placeholder='".$dictionary->get_lang($lang,$KEY_ENGLISH)."' value='".$en."'>
                                    <span class='text-danger' id='error_english_".$dictionary_id."'></span>
                                    <span style='display:none;'>".$en."</span>
                                ";
                                $data1['fr'] = "
                                    <input type='text' class='form-control' id='french_".$dictionary_id."' placeholder='".$dictionary->get_lang($lang,$KEY_FRENCH)."' value='".$fr."'>
                                    <span class='text-danger' id='error_french_".$dictionary_id."'></span>
                                    <span style='display:none;'>".$fr."</span>
                                ";
                                $data1['ar'] = "
                                    <input type='text' class='form-control' id='arabic_".$dictionary_id."' placeholder='".$dictionary->get_lang($lang,$KEY_ARABIC)."' value='".$ar."'>
                                    <span class='text-danger' id='error_arabic_".$dictionary_id."'></span>
                                    <span style='display:none;'>".$ar."</span>
                                ";
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
