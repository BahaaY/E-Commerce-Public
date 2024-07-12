<?php

    if (session_status() === PHP_SESSION_NONE){
        session_start();
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <title>Admin / Reports</title>
    <?php 

        require_once '../main-head.php'; 
        permission_reports_page($permission,$is_active,$is_verified);

    ?>

</head>

<body>

    <!-- ======= Header ======= -->
    <?php require_once '../header.php'; ?>

    <!-- ======= Sidebar ======= -->
    <?php require_once '../sidebar.php'; ?>

    <main class="main-title" id="main">

        <div class="pagetitle" <?php echo $dictionary->get_dir($lang); ?>>
           <h1><?php echo $dictionary->get_lang($lang,$KEY_REPORTS); ?></h1>
           <nav>
               <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../dashboard"><?php echo $dictionary->get_lang($lang,$KEY_HOME); ?></a></li>
                    <li class="breadcrumb-item"><?php echo $dictionary->get_lang($lang,$KEY_ADMIN); ?></li>
                    <li class="breadcrumb-item active"><?php echo $dictionary->get_lang($lang,$KEY_REPORTS); ?></li>
               </ol>
           </nav>
        </div><!-- End Page Title -->

        <div class="row" <?php echo $dictionary->get_dir($lang); ?>>
            <div class="col-md-12 mb-2">
                <a href="../report_by_product_type/" class="link h5" role="button"><?php echo $dictionary->get_lang($lang,$KEY_REPORT_BY_PRODUCT_TYPE); ?></a>
            </div>
            <div class="col-md-12 mb-2">
                <a href="../report_by_product_name/" class="link h5" role="button"><?php echo $dictionary->get_lang($lang,$KEY_REPORT_BY_PRODUCT_NAME); ?></a>
            </div>
        </div>

    </main>

    <!-- Modal Sign Out-->
    <?php require_once '../modal-logout.php'; ?>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

</body>

</html>
