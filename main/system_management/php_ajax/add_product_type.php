<?php

    if(session_status() !== PHP_SESSION_ACTIVE){
        session_start();
    }
    require_once "../../../config/variables.php";
    require_once '../../../config/conn.php';
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

        if(isset($_POST['name']) && isset($_POST['size_type']) && isset($_POST['availability']) && isset($_POST['product_availability']) && isset($_POST['index']) && isset($_POST['token'])){
            
            try{

                $token=$_POST['token'];
                
                if(isset($_SESSION[Session::$KEY_EC_TOKEN])){
                    $token_session=$_SESSION[Session::$KEY_EC_TOKEN];
                    if($token_session == $token){
                        $product_type_name=$_POST['name'];
                        $product_size_type=$_POST['size_type'];
                        $availability=$_POST['availability'];
                        $product_availability=$_POST['product_availability'];
                        $index=$_POST['index'];
                
                        if($product_type_name != "" && $product_size_type !="" && $availability !="" && $product_availability !="" && $index !=""){
                
                            $class_product_type=new ProductType($db_conn->get_link());
                
                            if($class_product_type->insert_product_type($product_type_name,$product_size_type,$availability,$product_availability)){
                                $product_type_id=$class_product_type->get_last_insert_product_type_id();
                                if($product_type_id != 0){
                                    $index++;
                                    $row="
                                        <tr id='tr_product_type_".$product_type_id."'>
                                            <th>".$index."</th>
                                            <td>
                                                <input class='form-control' type='text' id='product_type_name_".$product_type_id."' value='".$product_type_name."' placeholder='".$dictionary->get_lang($lang,$KEY_ENTER_PRODUCT_TYPE_NAME)."'>
                                                <span class='text-danger' id='error_product_type_name_".$product_type_id."'></span>
                                            </td>
                                            <td>
                                                <select class='form-control' id='product_size_type_".$product_type_id."'>";
                                                    $products_size_type=$class_product_type->get_all_product_size_type();
                                                    foreach($products_size_type as $size_type){
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
                                                <select class='form-control' id='product_type_availability_".$product_type_id."'>";
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
                                                <select class='form-control' id='product_availability_".$product_type_id."'>";
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
                                                <button class='btn btn-primary' id='btn_edit_product_type_".$product_type_id."' onclick='edit_product_type(".$product_type_id.");'><i class='bi bi-pen mr-2 ml-2'></i>".$dictionary->get_lang($lang,$KEY_EDIT)."</button>
                                                <button class='btn btn-danger d-none' id='btn_delete_product_type_".$product_type_id."' onclick='delete_product_type(".$product_type_id.");'><i class='bi bi-trash mr-2 ml-2'></i>".$dictionary->get_lang($lang,$KEY_DELETE)."</button>
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