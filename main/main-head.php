<?php

    if (session_status() === PHP_SESSION_NONE){
        session_start();
    }

    require_once '../resources/classes/dictionary.php';
    require_once '../../lang/key.php';
    require_once '../../config/conn.php';
    require_once '../../config/variables.php';
    require_once '../permission.php';
    require_once '../notification.php';
    require_once '../currency.php';

    require_once '../user-info.php';

    require_once 'logout.php';
    
    $dictionary=new Dictionary($db_conn->get_link());

    if(isset($_SESSION[Session::$KEY_EC_TIME_ZONE])){
        $time_zone=Helper::decrypt($_SESSION[Session::$KEY_EC_TIME_ZONE]);
        if($time_zone != ""){
            date_default_timezone_set($time_zone);
        }else{
            date_default_timezone_set(WebsiteInfo::$KEY_DEFAULT_TIME_ZONE);
        }
    }else{
        date_default_timezone_set(WebsiteInfo::$KEY_DEFAULT_TIME_ZONE);
    }

    if(isset($_SESSION[Session::$KEY_EC_LANG])) {
        $lang=$_SESSION[Session::$KEY_EC_LANG];
    }else {
        $lang="en";
    }

    if (isset($_GET['lang'])) {
        $lang=filter_var($_GET['lang'], FILTER_SANITIZE_STRING);
        if($lang == "en" || $lang == "fr" || $lang == "ar"){
            $_SESSION[Session::$KEY_EC_LANG] = $lang;
        }else{
            $lang="en";
            $_SESSION[Session::$KEY_EC_LANG] = $lang;
        }
    }

    if(isset($_SESSION[Session::$KEY_EC_CURRENCY])) {
        $currency=$_SESSION[Session::$KEY_EC_CURRENCY];
    }else {
        $currency=Currency::$KEY_EC_DEFAULT_ACTIVE_CURRENCY;
    }

    if (isset($_GET['currency'])) {
        $currency=strtoupper(filter_var($_GET['currency'], FILTER_SANITIZE_STRING));
        if($currency == "LBP" || $currency == "USD" || $currency == "EUR"){
            $_SESSION[Session::$KEY_EC_CURRENCY] = $currency;
        }else{
            $_SESSION[Session::$KEY_EC_CURRENCY] = Currency::$KEY_EC_DEFAULT_ACTIVE_CURRENCY;
        }
    }

    $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $path = substr($link, 0, strpos($link, WebsiteInfo::$KEY_PATH_TO_WEBSITE));
    $path=$path. WebsiteInfo::$KEY_PATH_TO_WEBSITE."/main/";
?>

<!-- POWERED BY SPACE SOFTWARE SOLUTIONS -->

<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">

<link href="<?php echo WebsiteInfo::$KEY_WEBSITE_LOGO ?>" rel="icon">
<link href="<?php echo WebsiteInfo::$KEY_WEBSITE_LOGO ?>" rel="apple-touch-icon">

<meta content="" name="description">
<meta content="" name="keywords">

<!-- Jquery -->
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
<script src="<?php echo $path; ?>resources/libs/jquery-3.6.1/jquery.js"></script>

<!-- Bootstrap -->
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->
<link rel="stylesheet" href="<?php echo $path; ?>resources/libs/bootstrap/bootstrap.min.css">
<script src="<?php echo $path; ?>resources/libs/bootstrap/poper.min.js"></script>
<script src="<?php echo $path; ?>resources/libs/bootstrap/bootstrap.min.js"></script>

<!-- Select2 -->
<!-- <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script> -->
<link rel="stylesheet" type="text/css" href="<?php echo $path; ?>resources/libs/select2/select2.css">
<script src="<?php echo $path; ?>resources/libs/select2/select2.min.js"></script>

<!-- Spectrum: For colors -->
<!-- <script src="https://bgrins.github.io/spectrum/spectrum.js"></script>
<link rel="stylesheet" type="text/css" href="https://bgrins.github.io/spectrum/spectrum.css">
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.9/semantic.css'> -->
<script src="<?php echo $path; ?>resources/libs/spectrum/spectrum.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $path; ?>resources/libs/spectrum/spectrum.css">
<link rel='stylesheet' href='<?php echo $path; ?>resources/libs/spectrum/semantic.css'>

<!-- DataTable -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script> -->
<link rel="stylesheet" href="<?php echo $path; ?>resources/libs/datatable/datatables.min.css">
<link rel="stylesheet" href="<?php echo $path; ?>resources/libs/datatable/datatables.css">
<script src="<?php echo $path; ?>resources/libs/datatable/datatables.min.js"></script>
<script src="<?php echo $path; ?>resources/libs/datatable/datatables.js"></script>

<!-- Sort table -->
<!-- <script src="https://www.kryogenix.org/code/browser/sorttable/sorttable.js"></script> -->
<script src="<?php echo $path; ?>resources/libs/sortTable/sortable.js"></script>

<!-- Font aweson -->
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->
<link rel="stylesheet" href="<?php echo $path; ?>resources/libs/font-awesome/css/font-awesome.min.css">

<!-- Diagram -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script> -->
<script src="<?php echo $path; ?>resources/libs/chart/chart.js"></script>

<!-- PrintThis -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/printThis/1.15.0/printThis.min.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/printThis/1.15.0/printThis.js"></script> -->
<script src="<?php echo $path; ?>resources/libs/printThis/printThis.js" ></script>

<!-- Table To Excel -->
<!-- <script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script> -->
<script src="<?php echo $path; ?>resources/libs/table2excel/jquery.table2excel.js"></script>

<!-- Google Fonts -->
<!-- <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet"> -->
<link href="<?php echo $path; ?>resources/libs/google-font/google-font.css" rel="stylesheet">

<!-- Vendor CSS Files -->
<link href="<?php echo $path; ?>resources/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="<?php echo $path; ?>resources/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
<link href="<?php echo $path; ?>resources/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
<link href="<?php echo $path; ?>resources/vendor/quill/quill.snow.css" rel="stylesheet">
<link href="<?php echo $path; ?>resources/vendor/quill/quill.bubble.css" rel="stylesheet">
<link href="<?php echo $path; ?>resources/vendor/remixicon/remixicon.css" rel="stylesheet">
<link href="<?php echo $path; ?>resources/vendor/simple-datatables/style.css" rel="stylesheet">

<!-- Template Main CSS File -->
<link href="<?php echo $path; ?>resources/css/style.css" rel="stylesheet">

<!-- Vendor JS Files -->
<script src="<?php echo $path; ?>resources/vendor/apexcharts/apexcharts.min.js"></script>
<script src="<?php echo $path; ?>resources/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo $path; ?>resources/vendor/chart.js/chart.umd.js"></script>
<script src="<?php echo $path; ?>resources/vendor/echarts/echarts.min.js"></script>
<script src="<?php echo $path; ?>resources/vendor/quill/quill.min.js"></script>
<script src="<?php echo $path; ?>resources/vendor/simple-datatables/simple-datatables.js"></script>
<script src="<?php echo $path; ?>resources/vendor/tinymce/tinymce.min.js"></script>
<script src="<?php echo $path; ?>resources/vendor/php-email-form/validate.js"></script>

<!-- Template Main JS File -->
<script src="<?php echo $path; ?>resources/js/main.js"></script>

<!-- CSS & JS-->
<link rel="stylesheet" href="<?php echo $path; ?>resources/css/main.css">
<script src="<?php echo $path; ?>resources/js/header.js"></script>

<?php
    require_once '../../config/helper.php';
    if(isset($_SESSION[Session::$KEY_EC_TOKEN])){
        $token=$_SESSION[Session::$KEY_EC_TOKEN];
    }else{
        $token="";
    }
?>
<input type="hidden" id="token" value="<?php echo $token; ?>">

<!-- <div id="loader">
    <div class="loader-icon"></div>
</div> -->