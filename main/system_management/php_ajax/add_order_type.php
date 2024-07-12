<?php

    if(session_status() !== PHP_SESSION_ACTIVE){
        session_start();
    }
    
    require_once "../../../config/variables.php";
    require_once '../../../config/conn.php';
    require_once '../classes/order_type.php';
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

    $res=0;
    $row="";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if(isset($_POST['name']) && isset($_POST['amount']) && isset($_POST['availability']) && isset($_POST['index']) && isset($_POST['token'])){

            try{

                $token=$_POST['token'];
                
                if(isset($_SESSION[Session::$KEY_EC_TOKEN])){
                    $token_session=$_SESSION[Session::$KEY_EC_TOKEN];
                    if($token_session == $token){
                        $order_type_name=$_POST['name'];
                        $amount=$_POST['amount'];
                        $availability=$_POST['availability'];
                        $index=$_POST['index'];
                
                        if($order_type_name != "" && $amount !="" && $availability !="" && $index !=""){
                
                            $class_order_type=new OrderType($db_conn->get_link());
                
                            if($class_order_type->insert_order_type($order_type_name,$amount,$availability)){
                                $order_type_id=$class_order_type->get_last_insert_order_type_id();
                                if($order_type_id != 0){
                                    $index++;
                                    $row.="
                                        <tr id='tr_order_type_".$order_type_id."'>
                                            <th>".$index."</th>
                                            <td>
                                                <input class='form-control' type='text' id='order_type_name_".$order_type_id."' value='".$order_type_name."' placeholder='".$dictionary->get_lang($lang,$KEY_ENTER_ORDER_TYPE_NAME)."'>
                                                <span class='text-danger' id='error_order_type_name_".$order_type_id."'></span>
                                            </td>
                                            <td>
                                                <input class='form-control' type='text' id='order_type_amount_".$order_type_id."' value='".$amount."' placeholder='".$dictionary->get_lang($lang,$KEY_ENTER_AMOUNT)."'>
                                                <span class='text-danger' id='error_order_type_amount_".$order_type_id."'></span>
                                            </td>
                                            <td>
                                                <select class='form-control' id='order_type_availability_".$order_type_id."'>";
                                                    $text="";
                                                    for($j=1;$j>=0;$j--){
                                                        if($availability == $j){
                                                            $checked="selected";
                                                        }else{
                                                            $checked="";
                                                        }
                                                        if($j==1){
                                                            $text="Show";
                                                        }else{
                                                            $text="Hide";
                                                        }
                                                        $row.="<option value='".$j."' ".$checked.">$text</option>";
                                                    }
                                                $row.="
                                                </select>
                                            </td>
                                            <td>
                                                <button class='btn btn-primary' id='btn_edit_order_type_".$order_type_id."' onclick='edit_order_type(".$order_type_id.");'><i class='bi bi-pen mr-2 ml-2'></i>".$dictionary->get_lang($lang,$KEY_EDIT)."</button>
                                                <button class='btn btn-danger d-none' id='btn_delete_order_type_".$order_type_id."' onclick='delete_order_type(".$order_type_id.");'><i class='bi bi-trash mr-2 ml-2'></i>".$dictionary->get_lang($lang,$KEY_DELETE)."</button>
                                            </td>
                                        </tr>
                                    ";
                                    $res=1;
                                }else{
                                    $res=0;
                                }
                                
                            }else{
                                $res=0;
                            }
                
                        }else{
                            $res=0;
                        }
                    }else{
                        $res=0;
                    }
                }else{
                    $res=0;
                }

            }catch(PDOException $ex){

                $res=0;
                
            }

        }else{
            $res=0;
        }

    }else{
        $res=0;
    }

    echo json_encode([
        "res"=>$res,
        "row"=>$row
    ]);

?>