<div class="row">
    <div class="col-md-12">
        <div class="alert alert-success alert-dismissible fade show" role="alert" id="alert-success-settings">
            <span id="text-success-settings">Settings has been updated</span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
    <div class="col-md-12">
        <div class="alert alert-danger alert-dismissible fade show" role="alert" id="alert-danger-settings">
            <span id="text-danger-settings">Error occurred</span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
</div>

<div class="row mb-3">

    <div class="col-md-12">

        <?php
            require_once 'classes/index/notification.php';
            require_once 'classes/index/users.php';
            require_once '../../config/conn.php';
            $class_notification=new Notification($db_conn->get_link());
            $class_users=new Users($db_conn->get_link());
            $all_notification=$class_notification->get_admin_notification();
            $row="";
            
            if(isset($_SESSION[Session::$KEY_EC_USERID])){
                $user_id = $_SESSION[Session::$KEY_EC_USERID];
                $user_id=Helper::decrypt($user_id);
            }
            if($permission == 1){
                if($all_notification){
                    $row.='
                        <label class="font-weight-bold">'.$dictionary->get_lang($lang,$KEY_NOTIFICATIONS).'</label>
                    ';
                    foreach($all_notification as $notification){
                        $notification_id=$notification['notification_id'];
                        $notification_name=$notification['notification_name'];
                        $notification_description=$notification['notification_description'];
                        $is_active=$notification['is_active'];
                        if($is_active == 1){
                            $checked="checked";
                        }else{
                            $checked="";
                        }
                        $row.='
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input checkbox checkbox_notification" type="checkbox" name="notification" value="'.$notification_id.'" id="ntf_request_order_'.$notification_id.'" '.$checked.'>
                                    <label class="form-check-label mr-4" for="ntf_request_order_'.$notification_id.'">
                                        '.$notification_name.'
                                    </label>
                                    <br>
                                    <label class="form-check-label mr-4" style="color: rgba(1, 41, 112, 0.6);">
                                        '.$notification_description.'
                                    </label>
                                </div>
                            </div>
                        ';
                        
                    }
                }
            }
           
            $user_data=$class_users->get_user_data($user_id);
            $two_step_verification = $user_data['two_step_verification'];
            if($two_step_verification == 1){
                $is_checked_two_step_verification="checked";
            }else{
                $is_checked_two_step_verification="";   
            }
            $row.='
                <div class="form-group">
                    <label class="font-weight-bold">'.$dictionary->get_lang($lang,$KEY_TWO_STEP_VERIFICATION).'</label>
                    <div class="form-check">
                        <input class="form-check-input checkbox" type="checkbox" id="two_step_verification" name="two_step_verification" '.$is_checked_two_step_verification.'>
                        <label class="form-check-label mr-4" for="two_step_verification">
                            Send email verification.
                        </label>
                        <label class="form-check-label mr-4" style="color: rgba(1, 41, 112, 0.6);">
                            Upon initiating the login process, a confirmation email will be dispatched to your registered account as part of our robust two-step verification procedure.
                        </label>
                    </div>
                </div>
            ';
            echo $row;
        ?>
        
    </div>
</div>

<div class="text-center">
    <button type="button" class="btn btn-primary" id="btn_save_settings"><i class='bi bi-pen mr-2 ml-2'></i><?php echo $dictionary->get_lang($lang,$KEY_SAVE_CHANGES) ?></button>
</div>
