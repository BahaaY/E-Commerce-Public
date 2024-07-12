<?php
    
    if(session_status() !== PHP_SESSION_ACTIVE){
        session_start();
    }

    require_once '../classes/product_size.php';
    require_once '../classes/product_type.php';
    require_once '../../../config/conn.php';
    require_once "../../../config/variables.php";
    
    require_once '../../resources/libs/phpOffice/vendor/autoload.php';
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Reader\Xlsx; 

    $format = '';
    $number_of_products=0;
    $error=0;

    if($_SERVER["REQUEST_METHOD"] == "POST"){

        if (isset($_FILES['excel_file']) && isset($_POST['token'])) {

            try{

                $class_product_size=new ProductSize($db_conn->get_link());
                $class_product_type=new ProductType($db_conn->get_link());

                $file=$_FILES['excel_file'];
                $token=$_POST['token'];
                    
                if(isset($_SESSION[Session::$KEY_EC_TOKEN])){
                    $token_session=$_SESSION[Session::$KEY_EC_TOKEN];
                    if($token_session == $token){

                        $array_allowed_files = array(
                            'excel'
                        );
            
                        if(validation_file($file, $array_allowed_files)){
            
                            $tmp_file = $_FILES['excel_file']['tmp_name'];
                            $reader = new Xlsx(); 
                            $spreadsheet = $reader->load($tmp_file);
                            $data = $spreadsheet->getActiveSheet()->toArray();
                            unset($data[0]); //remove header row
                            $number_of_products = count($data);
                            $array_error_getting_size_id = array();
                            $array_error_getting_type_id = array();
                
                            for ($y = 1; $y <= count($data); $y++) {
                                $format .= '
                                    <div class="col-md-4 mb-2">
                                        <div class="card mb-2">
                                            <div class="card-body p-0">
                                ';
                                for ($j = 0; $j < count($data[$y]); $j++) {
                                    switch ($j) {
                                        case 0:
                                            $input_id = 'title_' . $y;
                                            break;
                                        case 1:
                                            $input_id = 'description_' . $y;
                                            break;
                                        case 2:
                                            $input_id = 'price_' . $y;
                                            break;
                                        case 3:
                                            $input_id = 'discount_percentage_' . $y;
                                            break;
                                        case 4:
                                            $input_id = 'stock_' . $y;
                                            break;
                                        case 5:
                                            $input_id = 'color_' . $y;
                                            break;
                                        case 6:
                                            $input_id = 'size_' . $y;
                                            break;
                                        case 7:
                                            $input_id = 'product_type_' . $y;
                                            break;
                                        default:
                                            $input_id = '';
                                            break;
                                    }
                                    $color_display='';
                                    $size_display = '';
                                    $color_value='';
                                    $size_value = '';
                                    $dash = '';
                                    if($j == 0){
                                        $text="Title";
                                    }else if($j == 1){
                                        $text="Description";
                                    }else if($j == 2){
                                        $text="Price";
                                    }else if($j == 3){
                                        $text="Discount Percentage";
                                    }else if($j == 4){
                                        $text="Stock";
                                    }else if($j == 5){
                                        $text="Color";
                                    }else if($j == 6){
                                        $text="Size";
                                    }else if($j == 7){
                                        $text="Product Type";
                                    }else{
                                        $text="";
                                    }
                                    if ($j == 5) {
                                        $split_color = explode(',', filter_var($data[$y][$j]), FILTER_SANITIZE_STRING);
                                        for ($i = 0; $i < count($split_color); $i++) {
                                            if (count($split_color) - 1 == $i) {
                                                $dash = '';
                                                $comma = '';
                                            } else {
                                                $dash = '-';
                                                $comma = ',';
                                            }
                                            $part_color_dash = explode('-', $split_color[$i]);
                                            $color_value .= $part_color_dash[0] . $dash;
                                            $color_display .= $part_color_dash[0] . $comma;
                                        }
                                        $format .='
                                            <div class="col-md-12 p-1" style="border-bottom: 1px solid #f8f9fa;" >
                                                <span id="' .$input_id .'" value="' .$color_value .'">
                                                    <b>'.$text.':</b> ' .$color_display .'
                                                </span>
                                            </div>
                                        ';
                                    }else if ($j == 6) {
                                        $split_size = explode(',', filter_var($data[$y][$j]), FILTER_SANITIZE_STRING);
                                        for ($i = 0; $i < count($split_size); $i++) {
                                            if (count($split_size) - 1 == $i) {
                                                $comma = '';
                                            } else {
                                                $comma = ',';
                                            }
                                            $part_size_dash = explode('-', $split_size[$i]);
                                            $size_display .= $part_size_dash[0] . $comma;
                                        }
                                        $array_size_id=array();
                                        foreach($split_size as $size_name){
                                            if($size_name != ""){
                                                $size_id=$class_product_size->get_product_size_id(trim($size_name));
                                                if($size_id == 0){
                                                    array_push($array_error_getting_size_id,$size_name);
                                                }
                                                array_push($array_size_id,$size_id);
                                            }
                                        }
                                        $string_size_id = implode('- ', $array_size_id);
                                        $format .='
                                            <div class="col-md-12 p-1" style="border-bottom: 1px solid #f8f9fa;" >
                                                <span id="' .$input_id .'" value="' .$string_size_id .'">
                                                    <b>'.$text.':</b> ' .$size_display .'
                                                </span>
                                            </div>
                                        ';
                                    } else if ($j == 7) {
                                        $type_name=filter_var($data[$y][$j], FILTER_SANITIZE_STRING);
                                        $array_type_id=array();
                                        $type_id=$class_product_type->get_product_type_id($type_name);
                                        if($type_id == 0){
                                            array_push($array_error_getting_type_id,$type_name);
                                        }
                                        $format .='
                                            <div class="col-md-12 p-1" style="border-bottom: 1px solid #f8f9fa;" >
                                                <span id="' .$input_id .'" value="' .$type_id .'">
                                                    <b>'.$text.':</b> ' .$type_name .'
                                                </span>  
                                            </div>
                                        ';
                                    } else {
                                        $format .='
                                            <div class="col-md-12 p-1" style="border-bottom: 1px solid #f8f9fa;" >
                                                <span id="' .$input_id .'" value="' .filter_var($data[$y][$j], FILTER_SANITIZE_STRING) .'">
                                                    <b>'.$text.':</b> ' .filter_var($data[$y][$j], FILTER_SANITIZE_STRING) .'
                                                </span>
                                            </div>
                                        ';
                                    }
                                }
                                
                                $format.='
                                    <div class="col-md-12 p-1 text-danger" style="display: none;" id="error_'.$y.'" style="font-weight: bold;">
                
                                    </div>
                                ';
                                $format .= '
                                            </div>
                                        </div>
                                    </div>
                                ';
                            }
                            if(count($array_error_getting_size_id) > 0){
                                $string_size_name = implode(', ', $array_error_getting_size_id);
                                $format='
                                    <div class="col-md-4 mb-2">
                                        Size not found ( '.$string_size_name.' )
                                    </div>
                                ';
                                $error=1;
                            }
                            if(count($array_error_getting_type_id) > 0){
                                $string_type_name = implode(', ', $array_error_getting_type_id);
                                $format='
                                    <div class="col-md-4 mb-2">
                                        Type not found ( '.$string_type_name.' )
                                    </div>
                                ';
                                $error=1;
                            }
                            if(count($array_error_getting_size_id) > 0 && count($array_error_getting_type_id) > 0){
                                $string_size_name = implode(', ', $array_error_getting_size_id);
                                $string_type_name = implode(', ', $array_error_getting_type_id);
                                $format='
                                    <div class="col-md-4 mb-2">
                                        Size not found ( '.$string_size_name.' )
                                        <br>
                                        Type not found ( '.$string_type_name.' )
                                    </div>
                                ';
                                $error=1;
                            }
                        }else{
                            $error=2;
                        }
    
                    }else{
                        $error=2;
                    }
                }else{
                    $error=2;
                }
    
            }catch(PDOException $ex){
                $error=2;
            }

        }else{
            $error=1;
        }

    }else{
        $error=2;
    }

    echo json_encode([
        "error"=>$error,
        "number_of_products"=>$number_of_products,
        "format"=>$format
    ]);

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
                //"excel" => "xlsx,xls",
                "excel" => "xlsm",
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
                            $is_dangerous = true;
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
                        return true;
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

?>
