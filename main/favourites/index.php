<!DOCTYPE html>
<html lang="en">

<head>

    <title>Home / Saved</title>
    <?php 

        require_once '../main-head.php'; 
        permission_favourites_page($permission,$is_active,$is_verified);

    ?>

    <!-- CSS & JS -->
    <link rel="stylesheet" href="css/favourites.css">
    <script src="js/favourites.js"></script>

</head>

<body>

    <!-- ======= Header ======= -->
    <?php require_once '../header.php' ?>

    <!-- ======= Sidebar ======= -->
    <?php require_once '../sidebar.php'; ?>

    <main class="main" id="main">

        <input type="hidden" id="key_required" value="<?php echo $dictionary->get_lang($lang,$KEY_REQUIRED);  ?>">
        <input type="hidden" id="key_add_to_cart" value="<?php echo $dictionary->get_lang($lang,$KEY_ADD_TO_CART);  ?>">
        <input type="hidden" id="key_remove_from_cart" value="<?php echo $dictionary->get_lang($lang,$KEY_REMOVE_FROM_CART);  ?>">
        <input type="hidden" id="key_no_products_in_favourite" value="<?php echo $dictionary->get_lang($lang,$KEY_NO_PRODUCTS_IN_FAVORITE);  ?>">

        <div class="pagetitle" <?php echo $dictionary->get_dir($lang); ?>>
            <h1><?php echo $dictionary->get_lang($lang,$KEY_SAVED);  ?></h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../products"><?php echo $dictionary->get_lang($lang,$KEY_HOME);  ?></a></li>
                    <li class="breadcrumb-item active"><?php echo $dictionary->get_lang($lang,$KEY_SAVED);  ?></li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section favourites" <?php echo $dictionary->get_dir($lang); ?>>
            
            <div class="row" id="current_product">

                <div class="col-lg-12">

                    <div class="row" id="container-products">

                    </div>

                </div>
                
            </div>

            <div class="row" id="loading"  style="display:none" >

                <div class="col-md-12 p-0 m-0 d-flex justify-content-center align-items-center">
                    <div class="spinner-border" role="status">
                    
                    </div>
                </div>

            </div>

            <div class="row" id="parent_product">
                    
            </div>

        </section>

    </main><!-- End #main -->

    <!-- Modal Sign Out-->
    <?php require_once '../modal-logout.php'; ?>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

</body>

</html>
