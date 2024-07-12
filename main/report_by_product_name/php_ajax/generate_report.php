<?php

    if(session_status() !== PHP_SESSION_ACTIVE){
        session_start();
    }

    require_once '../classes/reports.php';
    require_once "../classes/product_size.php";
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
    $format="";
    $nb_products=array();
    $total_price=0;
    $total_quantity=0;
    $sum_total=0;

    //For diagram
    $array_Yvalues=array();
    $array_Xvalues=array();
    $productSums = array();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if(isset($_POST["initial_date"]) && isset($_POST["final_date"]) && isset($_POST['products']) && isset($_POST['token'])){

            try{

                $token=$_POST['token'];
                    
                if(isset($_SESSION[Session::$KEY_EC_TOKEN])){
                    $token_session=$_SESSION[Session::$KEY_EC_TOKEN];
                    if($token_session == $token){
                        $reports=new Reports($db_conn->get_link());
                        $class_product_size=new ProductSize($db_conn->get_link());
                        $res=1;
                    if($reports->generate_report($_POST['products'],$_POST["initial_date"],$_POST["final_date"]))
                    {
                    
                    $i=0;
                                    foreach($reports->generate_report($_POST['products'],$_POST["initial_date"],$_POST["final_date"]) as $report)
                                    {
                                        $i++;
                                        $quantity=$report["quantity"];
                                        $color=$report["color"];
                                        if($color == ""){
                                            $color="<i style='opacity:0.7'>".$dictionary->get_lang($lang,$KEY_NO_COLOR_AVAILABLE)."</i>";
                                        }
                                        $size_id=$report["product_size_id_FK"];
                                        if($size_id == ""){
                                            $size_name="<i style='opacity:0.7'>".$dictionary->get_lang($lang,$KEY_NO_SIZE_AVAILABLE)."</i>";
                                        }else{
                                            $size_name=$class_product_size->get_product_size_name($size_id);
                                        }
                                        $total_price=$report["Fprice"]*$quantity;
                                        $total_quantity =$total_quantity+$quantity;
                                        $sum_total+=$total_price;
                                        $format.="<tr id='sale_".$report["sales_id"]."'>
                                                <th>".$i."</th>
                                                <td>".$report["title"]."</td>
                                                <td>".$quantity."</td>
                                                <td>$".number_format(Helper::round_price($report["Fprice"]),2)."</td>
                                                <td>".$size_name."</td>
                                                <td>".$color."</td>
                                                <td>".$report["date"]."</td>";

                                                if(isset($_SESSION[Session::$KEY_EC_TIME_ZONE])){
                                                    $time_zone=Helper::decrypt($_SESSION[Session::$KEY_EC_TIME_ZONE]);
                                                    date_default_timezone_set($time_zone);
                                                }else{
                                                    date_default_timezone_set(WebsiteInfo::$KEY_DEFAULT_TIME_ZONE);
                                                }

                                                $time=$report["time"];
                                                $datetime = new DateTime($time);
                                                $source_time = $datetime->format('h:i');
                                                $time = DateTime::createFromFormat('H:i:s', $time);
                                                $am_pm = $time->format('A');
                                                $format.="<td>".$source_time." ".$am_pm."</td>
                                                <th>$".number_format(Helper::round_price($total_price),2)."</th>
                                                </tr>";
                                                if(!in_array($report['title'],$nb_products)){
                                                    array_push($nb_products,$report['title']);
                                                }
                    
                                                if (isset($productSums[$report['title']])) {
                                                    $productSums[$report['title']] += $quantity;
                                                } else {
                                                    $productSums[$report['title']] = $quantity;
                                                }
                                    }
                    
                                    // Print total sum per product
                                    foreach ($productSums as $product => $sum) {
                                        array_push($array_Xvalues,$product);
                                        array_push($array_Yvalues,$sum);
                                    }
                    
                        }else{
                            $format.="<tr><td colspan='9' class='text-center'>".$dictionary->get_lang($lang,$KEY_NO_DATA_AVAILABLE_IN_TABLE)."</td></tr>";
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
    
    $obj->sum_total=number_format(Helper::round_price($sum_total),2);
    $obj->nb_products=count($nb_products);
    $obj->total_qty=$total_quantity;
    $obj->res=$res;
    $obj->Xvalues=$array_Xvalues;
    $obj->Yvalues=$array_Yvalues;
    $obj->format=$format;
    echo json_encode($obj);


?>