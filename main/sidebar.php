<?php

    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'){
        $url = "https://";   
    }else{
        $url = "http://";
    }

    // Append the host(domain name, ip) to the URL.   
    $url.= $_SERVER['HTTP_HOST'];   

    // Append the requested resource location to the URL   
    $url.= $_SERVER['REQUEST_URI'];    
    
    $parts = parse_url($url);
    $path = trim($parts['path'], '/');
    $segments = explode('/', $path);

    if (in_array('dashboard', $segments)) {
        $check_page="dashboard";
    }else if (in_array('products', $segments)) {
        $check_page="products";
    }else  if (in_array('cart', $segments)) {
        $check_page="cart";
    }else  if (in_array('favourites', $segments)) {
        $check_page="favourites";
    }else  if (in_array('add-product', $segments)) {
        $check_page="add-product";
    }else  if (in_array('manage-products', $segments)) {
        $check_page="manage-products";
    }else  if (in_array('orders', $segments)) {
        $check_page="orders";
    }else  if (in_array('my_orders', $segments)) {
        $check_page="my_orders";
    }else  if (in_array('reports', $segments)) {
        $check_page="reports";
    }else  if (in_array('report_by_product_type', $segments)) {
        $check_page="report_by_product_type";
    }else  if (in_array('report_by_product_name', $segments)) {
        $check_page="report_by_product_name";
    }else  if (in_array('system_management', $segments)) {
        $check_page="system_management";
    }else  if (in_array('user-profile', $segments)) {
        $check_page="user-profile";
    } else  if (in_array('login', $segments)) {
        $check_page="login";
    } else  if (in_array('register', $segments)) {
        $check_page="register";
    } else  if (in_array('forgot-password', $segments)) {
        $check_page="forgot-password";
    }else  if (in_array('contact', $segments)) {
        $check_page="contact";
    }

?>

<script>

    function search_pages(){

        var input, filter, ul, li, span, i, txtValue,result=false;
        input = document.getElementById("input-search-page");
        filter = input.value.toUpperCase();
        ul=document.getElementById("sidebar-nav");
        li = ul.getElementsByTagName("li");
        for (i = 0; i < li.length; i++) {
            span = li[i].querySelector("span");
            if (span) {
                txtValue = span.textContent || span.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    li[i].style.display = "";
                    result=true;
                } else {
                    li[i].style.display = "none";
                }
            }
        }

        if(result == false){
            titles=document.querySelectorAll("#sidebar-title");
            for (i = 0; i < titles.length; i++) {
                titles[i].style.display = "none";
            }
            $("#no-records").removeClass('d-none');
        }else{
            titles=document.querySelectorAll("#sidebar-title");
            for (i = 0; i < titles.length; i++) {
                titles[i].style.display = "";
            }
            $("#no-records").addClass('d-none');
        }

    }
    
</script>

<aside id="sidebar" class="sidebar" <?php echo $dictionary->get_dir($lang); ?>>

    <ul class="sidebar-nav" id="sidebar-nav">

        <div class="input-group rounded mb-2">
            <input type="search" class="form-control" id="input-search-page" onkeyup="search_pages();" placeholder="<?php echo $dictionary->get_lang($lang,$KEY_FIND_A_PAGE); ?>" aria-label="Search for page" aria-describedby="search-addon" />
        </div><!-- End Search Bar -->

        <li class="nav-heading p-0 m-0" id="sidebar-title"><?php echo $dictionary->get_lang($lang,$KEY_HOME); ?></li>

        <?php
            // if($permission == 1){
            //     $dashboard_collapsed="collapsed";
            //     $products_collapsed="";
            // }else{
            //     $dashboard_collapsed="";
            //     $products_collapsed="collapsed";
            // }
        ?>

        <?php
            if($permission == 1){
        ?>
            <li class="nav-item <?php echo $access_dashboard ?> sidebar-pages" id="dashboard-page" >
                <a class="nav-link <?php if($check_page!="dashboard") echo "collapsed"; ?>" href="../dashboard">
                    <i class="bi bi-speedometer2 mr-2 ml-2"></i>
                    <span class="sidebar-text"><?php echo $dictionary->get_lang($lang,$KEY_DASHBOARD); ?></span>
                </a>
            </li><!-- End Dashboard Nav -->
        <?php 
            } 
        ?>

        <?php
            if($permission != 1){
        ?>
            <li class="nav-item <?php echo $access_products ?> sidebar-pages" id="product-page" >
                <a class="nav-link <?php if($check_page!="products") echo "collapsed"; ?>" href="../products">
                    <i class="bi bi-shop mr-2 ml-2"></i>
                    <span class="sidebar-text"><?php echo $dictionary->get_lang($lang,$KEY_PRODUCTS); ?></span>
                </a>
            </li><!-- End Products Nav -->
        <?php 
            } 
        ?>

        <li class="nav-item <?php echo $access_saved ?> sidebar-pages" id="favourites-page" >
            <a class="nav-link <?php if($check_page!="favourites") echo "collapsed"; ?>" href="../favourites">
                <i class="bi bi-heart mr-2 ml-2"></i>
                <span class="sidebar-text"><?php echo $dictionary->get_lang($lang,$KEY_SAVED); ?></span>
            </a>
        </li><!-- End Favourites Nav -->

        <li class="nav-item <?php echo $access_cart ?> sidebar-pages" id="cart-page" >
            <a class="nav-link <?php if($check_page!="cart") echo "collapsed"; ?>" href="../cart">
                <i class="bi bi-cart mr-2 ml-2"></i>
                <span class="sidebar-text"><?php echo $dictionary->get_lang($lang,$KEY_CART); ?></span>
            </a>
        </li><!-- End Cart Nav -->

        <li class="nav-item <?php echo $access_saved ?> sidebar-pages" id="my-orders-page" >
            <a class="nav-link <?php if($check_page!="my_orders") echo "collapsed"; ?>" href="../my_orders">
                <i class="bi bi-truck mr-2 ml-2"></i>
                <span class="sidebar-text"><?php echo $dictionary->get_lang($lang,$KEY_MY_ORDERS); ?></span>
            </a>
        </li><!-- End My orders Nav -->

        <li class="nav-heading" id="sidebar-title"><?php echo $dictionary->get_lang($lang,$KEY_PAGES); ?></li>

        <li class="nav-item <?php echo $access_profile ?> sidebar-pages" id="profile-page" >
            <a class="nav-link <?php if($check_page!="user-profile") echo "collapsed"; ?>" href="../user-profile/">
                <?php
                    if($permission == 1){
                        $text=$dictionary->get_lang($lang,$KEY_SETTINGS);
                        $icon="bi bi-gear mr-2 ml-2";
                    }else{
                        $text=$dictionary->get_lang($lang,$KEY_PROFILE);
                        $icon="bi bi-person mr-2 ml-2";
                    }
                ?>
                <i class="<?php echo $icon ?>"></i>
                <span class="sidebar-text"><?php echo $text; ?></span>
            </a>
        </li><!-- End Profile Page Nav -->

        <li class="nav-item <?php echo $access_login ?> sidebar-pages" id="login-page" >
            <a class="nav-link <?php if($check_page!="login") echo "collapsed"; ?>" href="../login/">
                <i class="bi bi-box-arrow-in-right mr-2 ml-2"></i>
                <span class="sidebar-text"><?php echo $dictionary->get_lang($lang,$KEY_LOGIN); ?></span>
            </a>
        </li><!-- End Login Page Nav -->

        <li class="nav-item <?php echo $access_register ?> sidebar-pages" id="register-page" >
            <a class="nav-link <?php if($check_page!="register") echo "collapsed"; ?>" href="../register/">
                <i class="bi bi-door-open mr-2 ml-2"></i>
                <span class="sidebar-text"><?php echo $dictionary->get_lang($lang,$KEY_CREATE_ACCOUNT); ?></span>
            </a>
        </li><!-- End Register Page Nav -->

        <li class="nav-item <?php echo $access_forgot_password ?> sidebar-pages" id="forgot-password-page" >
            <a class="nav-link <?php if($check_page!="forgot-password") echo "collapsed"; ?>" href="../forgot-password/">
                <i class="bi bi-lock mr-2 ml-2"></i>
                <span class="sidebar-text"><?php echo $dictionary->get_lang($lang,$KEY_FORGOT_PASSWORD); ?></span>
            </a>
        </li><!-- End Forgot password Page Nav -->

        <li class="nav-item <?php echo $access_contact ?> sidebar-pages" id="contact-page" >
            <a class="nav-link <?php if($check_page!="contact") echo "collapsed"; ?>" href="../contact/">
                <i class="bi bi-envelope mr-2 ml-2"></i>
                <span class="sidebar-text"><?php echo $dictionary->get_lang($lang,$KEY_CONTACT); ?></span>
            </a>
        </li><!-- End Contact Page Nav -->

        <li class="nav-heading <?php echo $access_add_product ?>" id="sidebar-title"><?php echo $dictionary->get_lang($lang,$KEY_ADMIN); ?></li>

        <li class="nav-item <?php echo $access_add_product ?> sidebar-pages" id="add-product-page" >
            <a class="nav-link <?php if($check_page!="add-product") echo "collapsed"; ?>" href="../add-product">
                <i class="bi bi-plus mr-2 ml-2" style="font-size:18px"></i>
                <span class="sidebar-text"><?php echo $dictionary->get_lang($lang,$KEY_ADD_PRODUCT); ?></span>
            </a>
        </li><!-- End Add Product Nav -->

        <li class="nav-item <?php echo $access_add_product ?> sidebar-pages" id="manage-products-page" >
            <a class="nav-link <?php if($check_page!="manage-products") echo "collapsed"; ?>" href="../manage-products">
                <i class="bi bi-shop mr-2 ml-2" style="font-size:18px"></i>
                <span class="sidebar-text"><?php echo $dictionary->get_lang($lang,$KEY_MANAGE_PRODUCTS); ?></span>
            </a>
        </li><!-- End Manage Products Nav -->

        <li class="nav-item <?php echo $access_orders ?> sidebar-pages" id="orders-page" >
            <a class="nav-link <?php if($check_page!="orders") echo "collapsed"; ?>" href="../orders">
                <i class="bi bi-truck mr-2 ml-2" style="font-size:18px"></i>
                <span class="sidebar-text"><?php echo $dictionary->get_lang($lang,$KEY_ORDERS); ?></span>
            </a>
        </li><!-- End Orders Nav -->

        <li class="nav-item <?php echo $access_reports ?> sidebar-pages" id="reports-page" >
            <a class="nav-link <?php if($check_page!="reports" && $check_page!="report_by_product_name" && $check_page!="report_by_product_type") echo "collapsed"; ?>" href="../reports">
                <i class="bi bi-book mr-2 ml-2" style="font-size:18px"></i>
                <span class="sidebar-text"><?php echo $dictionary->get_lang($lang,$KEY_REPORTS); ?></span>
            </a>
        </li><!-- End Report Nav -->

        <li class="nav-item <?php echo $access_reports ?> sidebar-pages" id="system-management-page" >
            <a class="nav-link <?php if($check_page!="system_management") echo "collapsed"; ?>" href="../system_management">
                <i class="bi bi-wrench mr-2 ml-2" style="font-size:18px"></i>
                <span class="sidebar-text"><?php echo $dictionary->get_lang($lang,$KEY_SYSTEM_MANAGEMENT); ?></span>
            </a>
        </li><!-- End System Management Nav -->

        <!-- <li class="nav-item <?php echo $access_add_product ?>">
            <a class="nav-link <?php if($check_page!="add-product") echo "collapsed"; ?>" data-bs-target="#admin-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-menu-button-wide mr-2 ml-2"></i><span>Admin</span><i class="bi bi-chevron-down ms-auto mr-2 ml-2"></i>
            </a>
            <ul id="admin-nav" class="nav-content <?php if($check_page!="add-product") echo "collapse"; ?>" data-bs-parent="#sidebar-nav">
                <li class="nav-item <?php echo $access_add_product ?>">
                    <a class="nav-link <?php if($check_page!="add-product") echo "collapsed"; ?>" href="../add-product">
                        <i class="bi bi-plus mr-2 ml-2" style="font-size:18px"></i>
                        <span>Add Product</span>
                    </a>
                </li>
            </ul>
        </li> -->

        <li class="nav-heading d-none" id="no-records"><?php echo $dictionary->get_lang($lang,$KEY_NO_MATCHING_RECORDS_FOUND); ?></li>
        
        <?php require_once '../footer.php' ?>

    </ul>

</aside><!-- End Sidebar-->



