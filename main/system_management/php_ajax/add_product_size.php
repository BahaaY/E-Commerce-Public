<?php

    if(session_status() !== PHP_SESSION_ACTIVE){
        session_start();
    }
    require_once "../../../config/variables.php";
    require_once '../../../config/conn.php';
    require_once '../classes/product_size.php';
    require_once '../classes/product_type.php';
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

        if(isset($_POST['name']) && isset($_POST['availability']) && isset($_POST['product_size_type']) && isset($_POST['index']) && isset($_POST['token'])){

            try{

                $token=$_POST['token'];
                    
                if(isset($_SESSION[Session::$KEY_EC_TOKEN])){
                    $token_session=$_SESSION[Session::$KEY_EC_TOKEN];
                    if($token_session == $token){
                        $product_size_name=$_POST['name'];
                        $availability=$_POST['availability'];
                        $product_size_type=$_POST['product_size_type'];
                        $index=$_POST['index'];
                
                        if($product_size_name != "" && $availability !="" && $product_size_type !="" && $index !=""){
                
                            $class_product_size=new ProductSize($db_conn->get_link());
                            $class_product_type=new ProductType($db_conn->get_link());
                
                            if($class_product_size->insert_product_size($product_size_name,$availability,$product_size_type)){
                                $product_size_id=$class_product_size->get_last_insert_product_size_id();
                                if($product_size_id != 0){
                                    $index++;
                                    $row.="
                                        <tr id='tr_product_size_".$product_size_id."'>
                                            <th>".$index."</th>
                                            <td>
                                                <input class='form-control' type='text' id='product_size_name_".$product_size_id."' value='".$product_size_name."' placeholder='".$dictionary->get_lang($lang,$KEY_ENTER_PRODUCT_SIZE_NAME)."'>
                                                <span class='text-danger' id='error_product_size_name_".$product_size_id."'></span>
                                            </td>
                                            <td>
                                                <select class='form-control' id='product_size_type_for_product_size_field_".$product_size_id."'>";
                                                
                                                    $product_size_type_for_product_size_field=$class_product_type->get_all_product_size_type_for_product_size_field();
                                                    foreach($product_size_type_for_product_size_field as $size_type){
                                                        $size_type_id= $size_type['product_size_type_id'];
                                                        $size_type_name= $size_type['product_size_type_name'];
                                                        if($product_size_type == $size_type_id){
                                                            $selected="selected";
                                                        }else{
                                                            $selected="";
                                                        }
                                                        $row.="<option value='".$size_type_id."' ".$selected.">$size_type_name</option>";
                                                    }
                                            $row.="
                                                </select>
                                            </td>
                                            <td>
                                                <select class='form-control' id='product_size_availability_".$product_size_id."'>";
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
                                                <button class='btn btn-primary' id='btn_edit_product_size_".$product_size_id."' onclick='edit_product_size(".$product_size_id.");'><i class='bi bi-pen mr-2 ml-2'></i>".$dictionary->get_lang($lang,$KEY_EDIT)."</button>
                                                <button class='btn btn-danger d-none' id='btn_delete_product_size_".$product_size_id."' onclick='delete_product_size(".$product_size_id.");'><i class='bi bi-trash mr-2 ml-2'></i>".$dictionary->get_lang($lang,$KEY_DELETE)."</button>
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