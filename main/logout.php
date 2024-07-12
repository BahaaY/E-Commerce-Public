<style>
    html, body{
        background: #f6f9ff;
    }
    .prog{
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width:200px;
    }
</style>

<?php

    if (session_status() === PHP_SESSION_NONE){
        session_start();
    }

    if (isset($_REQUEST['logout'])) {
        if (isset($_SESSION[Session::$KEY_EC_USERID]) && isset($_SESSION[Session::$KEY_EC_TOKEN])) {
            unset($_SESSION[Session::$KEY_EC_USERID]);
            unset($_SESSION[Session::$KEY_EC_TOKEN]);
            if (isset($_SESSION[Session::$KEY_EC_TIME_ZONE])) {
                unset($_SESSION[Session::$KEY_EC_TIME_ZONE]);
            }
    
            // Display progress message
            echo "<div class='prog'>";
            echo '<progress max="100" style="height:30px"><strong>Progress: 0% done.</strong></progress><br>';
            echo '<span style="font-size:17px">Logging out, please wait...</span>';
            echo "</div>";
    
            // Flush the output buffer to send the progress message to the client
            ob_flush();
            flush();
    
            // Delay for a few seconds (adjust as needed)
            sleep(2);
    
            // Redirect back to the index page using a PHP header
            echo "<script>window.location.href='../index.php';</script>";
            exit();
        }else{
            echo "<script>window.location.href='../index.php';</script>";
        }
    }

?>



