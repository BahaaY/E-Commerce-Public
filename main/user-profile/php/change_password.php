<div class="row">
    <div class="col-md-12">
        <div class="alert alert-success alert-success-password alert-dismissible fade show" role="alert" id="alert-success-password">
            <span id="text-success-password">Password has been updated</span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
    <div class="col-md-12">
        <div class="alert alert-danger alert-danger-password alert-dismissible fade show" role="alert" id="alert-danger-password">
            <span id="text-danger-password">Error occurred</span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
</div>

<div class="row mb-2">
    <div class="col-md-6">
        <label for="current-password" class="mb-0"><?php echo $dictionary->get_lang($lang,$KEY_CURRENT_PASSWORD); ?></label> <span class="text-danger">*</span></label>
    </div>
    <div class="col-md-6 text-right" <?php echo $dir_required ?>>
        <span class="text-danger" id="error-current-password"></span>
    </div>
    <div class="col-md-12">
        <input name="password" type="password" class="form-control" id="current-password"
            placeholder="<?php echo $dictionary->get_lang($lang,$KEY_ENTER_CURRENT_PASSWORD);  ?>">
    </div>
</div>

<div class="row mb-2">
    <div class="col-md-6">
        <label for="new-password" class="mb-0"><?php echo $dictionary->get_lang($lang,$KEY_NEW_PASSWORD); ?></label> <span class="text-danger">*</span></label>
    </div>
    <div class="col-md-6 text-right" <?php echo $dir_required ?>>
        <span class="text-danger" id="error-new-password"></span>
    </div>
    <div class="col-md-12" style="direction: ltr !important">
        <div class="input-group" id="show_hide_new_password">
            <input class="form-control" type="password" id="new-password" placeholder="<?php echo $dictionary->get_lang($lang,$KEY_ENTER_NEW_PASSWORD);  ?>" <?php echo $dictionary->get_dir($lang); ?>>
            <div class="input-group-addon input-group-addon-new-password d-flex align-items-center p-2">
                <i class="fa fa-eye-slash" aria-hidden="true"></i>
            </div>
        </div>
    </div>
</div>

<div class="row mb-2">
    <div class="col-md-6">
        <label for="confirm-new-password" class="mb-0"><?php echo $dictionary->get_lang($lang,$KEY_CONFIRM_PASSWORD); ?></label> <span class="text-danger">*</span></label>
    </div>
    <div class="col-md-6 text-right" <?php echo $dir_required ?>>
        <span class="text-danger" id="error-confirm-new-password"></span>
    </div>
    <div class="col-md-12" style="direction: ltr !important">
        <div class="input-group" id="show_hide_confirm_new_password">
            <input class="form-control" type="password" id="confirm-new-password" placeholder="<?php echo $dictionary->get_lang($lang,$KEY_ENTER_CONFIRM_PASSWORD);  ?>" <?php echo $dictionary->get_dir($lang); ?>>
            <div class="input-group-addon input-group-addon-confirm-new-password d-flex align-items-center p-2">
                <i class="fa fa-eye-slash" aria-hidden="true"></i>
            </div>
        </div>
    </div>
</div>

<div class="text-center">
    <button type="button" class="btn btn-primary" id="btn_change_password"><i class="bi bi-pen mr-2 ml-2"></i><?php echo $dictionary->get_lang($lang,$KEY_CHANGE_PASSWORD) ?></button>
</div>
