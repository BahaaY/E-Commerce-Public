<!DOCTYPE html>
<html lang="en">

<head>

    <title>Home / Dashboard</title>
    <?php 

        require_once '../main-head.php';
        permission_dashboard($permission,$is_active,$is_verified);

    ?>

    <!-- CSS & JS -->
    <link rel="stylesheet" href="css/dashboard.css">
    <script src="js/dashboard.js"></script>

</head>

<body>

    <!-- ======= Header ======= -->
    <?php require_once '../header.php' ?>

    <!-- ======= Sidebar ======= -->
    <?php require_once '../sidebar.php'; ?>

    <main id="main" class="main"> 

        <input type="hidden" id="key_block_user" value="<?php echo $dictionary->get_lang($lang,$KEY_BLOCK_USER); ?>">
        <input type="hidden" id="key_unblock_user" value="<?php echo $dictionary->get_lang($lang,$KEY_UNBLOCK_USER); ?>">
        <input type="hidden" id="key_required" value="<?php echo $dictionary->get_lang($lang,$KEY_REQUIRED); ?>">

        <div class="pagetitle m-0" <?php echo $dictionary->get_dir($lang); ?>>
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-3">
                    <div class="form-group mb-2">
                        <h1><?php echo $dictionary->get_lang($lang,$KEY_DASHBOARD); ?></h1>
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="../dashboard"><?php echo $dictionary->get_lang($lang,$KEY_HOME); ?></a></li>
                                <li class="breadcrumb-item active"><?php echo $dictionary->get_lang($lang,$KEY_DASHBOARD); ?></li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
            
        </div><!-- End Page Title -->

        <section class="section dashboard d-flex flex-column align-items-center justify-content-center" style="width:100% !important">

            <div class="container-fluid p-0" <?php echo $dictionary->get_dir($lang); ?>>
                <div class="row justify-content-center m-0 p-0">
                    <div class="col-md-12 p-0">
                        <div class="row">
                            <div class="col-md-3 pt-2">
                                <div class="cards p-0">
                                    <a class="framework-card" href="../add-product/">
                                        <i class="bi bi-plus" id="icon"></i>
                                        <p><?php echo $dictionary->get_lang($lang,$KEY_ADD_PRODUCT); ?></p>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3 pt-2">
                                <div class="cards p-0">
                                    <a class="framework-card" href="../manage-products/">
                                        <i class="bi bi-shop" id="icon"></i>
                                        <p><?php echo $dictionary->get_lang($lang,$KEY_MANAGE_PRODUCTS); ?></p>
                                    </a>   
                                </div>
                            </div>
                            <div class="col-md-3 pt-2">
                                <div class="cards p-0">
                                    <a class="framework-card" href="../orders/">
                                        <i class="bi bi-truck" id="icon"></i>
                                        <p><?php echo $dictionary->get_lang($lang,$KEY_ORDERS); ?></p>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3 pt-2">
                                <div class="cards p-0">
                                    <a class="framework-card" href="../reports/">
                                        <i class="bi bi-book" id="icon"></i>
                                        <p><?php echo $dictionary->get_lang($lang,$KEY_REPORTS); ?></p>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3 pt-2">
                                <div class="cards p-0">
                                    <a class="framework-card" href="../system_management/">
                                        <i class="bi bi-wrench" id="icon"></i>
                                        <p><?php echo $dictionary->get_lang($lang,$KEY_SYSTEM_MANAGEMENT); ?></p>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3 pt-2">
                                <div class="cards p-0">
                                    <a class="framework-card" href="../user-profile/">
                                        <i class="bi bi-gear" id="icon"></i>
                                        <p><?php echo $dictionary->get_lang($lang,$KEY_SETTINGS); ?></p>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3 pt-2">
                                <div class="cards p-0">
                                    <a class="framework-card d-none" href="../contact/">
                                        <i class="bi bi-envelope" id="icon"></i>
                                        <p><?php echo $dictionary->get_lang($lang,$KEY_CONTACT); ?></p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-5 mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-success alert-dismissible fade show" role="alert" id="alert-success">
                            User has been updated
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table" id="table_users">
                                <thead class="thead-light">
                                    <th>#</th>
                                    <th><?php echo $dictionary->get_lang($lang,$KEY_USERNAME);  ?></th>
                                    <th><?php echo $dictionary->get_lang($lang,$KEY_EMAIL);  ?></th>
                                    <th class="d-none"><?php echo $dictionary->get_lang($lang,$KEY_LOGIN_LIMIT);  ?></th>
                                    <th><?php echo $dictionary->get_lang($lang,$KEY_ACTION);  ?></th>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
        </section>

    </main><!-- End #main -->

    <!-- Modal Sign Out-->
    <?php require_once '../modal-logout.php'; ?>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

</body>

</html>
