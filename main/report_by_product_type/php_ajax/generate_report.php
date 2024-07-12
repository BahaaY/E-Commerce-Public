<?php

    if(session_status() !== PHP_SESSION_ACTIVE){
        session_start();
    }

    require_once '../classes/reports.php';
    require_once "../../../config/conn.php";
    require_once "../../../config/variables.php";
    require_once "../../../config/helper.php";
    require_once "../../../lang/key.php";
    require_once "../../resources/classes/dictionary.php";

    if (!isset($_SESSION[Session::$KEY_EC_USERID])) {
        header("location:../../../forbidden");
    }

    if(isset($_SESSION[Session::$KEY_EC_LANG])) {
        $lang=$_SESSION[Session::$KEY_EC_LANG];
    }else {
        $lang="en";
    }

    $dictionary = new Dictionary($db_conn->get_link());

    $obj=new stdclass();
    $res=0;
    $nb_products=0;
    $sum_total=0;
    $total_qty=0;
    $format="";

    //For diagram
    $array_Yvalues=array();
    $array_Xvalues=array();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if(isset($_POST["initial_date"]) && isset($_POST["final_date"]) && isset($_POST['products']) && isset($_POST['token'])){

            try{

                $token=$_POST['token'];
                
                if(isset($_SESSION[Session::$KEY_EC_TOKEN])){
                    $token_session=$_SESSION[Session::$KEY_EC_TOKEN];
                    if($token_session == $token){
                        $start_date=$_POST["initial_date"];
                        $end_date=$_POST["final_date"];
                        $product_id=$_POST["products"];
                
                        $class_reports=new Reports($db_conn->get_link());
                        $res=1;
                        $report_data=$class_reports->generate_report($product_id,$start_date,$end_date);
                        if($report_data)
                        {
                
                            $i=0;
                                    foreach($report_data as $data)
                                    {
                                        $i++;
                                        $product_type_name=$data['product_type_name'];
                                        $price=$data['price'];
                                        $quantity=$data['quantity'];
                                        $format.="
                                            <tr>
                                                <th>".$i."</th>
                                                <td>".$product_type_name."</td>
                                                <td>".$quantity."</td>
                                                <th>$".number_format(Helper::round_price($price),2)."</th>
                                            </tr>
                                        ";
                                        $total_qty+=$quantity;
                                        $sum_total+=$price;
                
                                        array_push($array_Xvalues,$product_type_name);
                                        array_push($array_Yvalues,$quantity);
                                    }
                
                        }else{
                            $format.="<tr><td colspan='4' class='text-center'>".$dictionary->get_lang($lang,$KEY_NO_DATA_AVAILABLE_IN_TABLE)."</td></tr>";
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

    $obj->format=$format;
    $obj->nb_products=count($report_data);
    $obj->total_qty=$total_qty;
    $obj->sum_total=number_format(Helper::round_price($sum_total),2);
    $obj->Xvalues=$array_Xvalues;
    $obj->Yvalues=$array_Yvalues;
    $obj->res=$res;
    echo json_encode($obj);

?>