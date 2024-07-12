<div class="row">
    <div class="col-md-12">
        <div class="alert alert-danger alert-danger-delete-account alert-dismissible fade show" role="alert" id="alert-danger-delete-account-error">
            <span id="text-danger-delete-account">Error occurred</span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-danger alert-danger-delete-account alert-dismissible fade show" role="alert" id="alert-danger-delete-account">
            <span id="text-danger-delete-account">Email or password incorrect.</span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-danger alert-danger-delete-account alert-dismissible fade show" role="alert" id="alert-danger-delete-account-cancel-orders">
            <span id="text-danger-delete-account">Please cancel your orders.</span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
</div>

<div class="row mb-2">
    <div class="col-md-6">
        <label for="email_delete_account" class="mb-0"><?php echo $dictionary->get_lang($lang,$KEY_EMAIL);  ?></label> <span class="text-danger">*</span></label>
    </div>
    <div class="col-md-6 text-right" <?php echo $dir_required ?>>
        <span class="text-danger" id="error-email-delete-account"></span>
    </div>
    <div class="col-md-12">
        <input name="email_delete_account" type="email" class="form-control" id="email_delete_account"
            placeholder="<?php echo $dictionary->get_lang($lang,$KEY_ENTER_EMAIL);  ?>">
    </div>
</div>

<div class="row mb-2">
    <div class="col-md-6">
        <label for="password" class="mb-0"><?php echo $dictionary->get_lang($lang,$KEY_PASSWORD);  ?></label> <span class="text-danger">*</span></label>
    </div>
    <div class="col-md-6 text-right" <?php echo $dir_required ?>>
        <span class="text-danger" id="error-password-delete-account"></span>
    </div>
    <div class="col-md-12" style="direction: ltr !important">
        <div class="input-group" id="show_hide_password">
            <input class="form-control" type="password" id="password" placeholder="<?php echo $dictionary->get_lang($lang,$KEY_ENTER_PASSWORD);  ?>" <?php echo $dictionary->get_dir($lang); ?>>
            <div class="input-group-addon input-group-addon-password d-flex align-items-center p-2">
                <i class="fa fa-eye-slash" aria-hidden="true"></i>
            </div>
        </div>
    </div>
</div>

<div class="text-center">
    <button type="button" class="btn btn-primary" id="btn_delete_account"><i class="bi bi-trash mr-2 ml-2"></i><?php echo $dictionary->get_lang($lang,$KEY_DELETE_ACCOUNT) ?></button>
</div>

<div class="modal fade" id="modal-ask-delete-account" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="direction:ltr !important">
                <h5 class="modal-title"><?php echo $dictionary->get_lang($lang,$KEY_DELETE_ACCOUNT);  ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want delete this account?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="close_modal_ask_delete_account"  data-dismiss="modal"><i class="fa fa-close mr-2 ml-2"></i><?php echo $dictionary->get_lang($lang,$KEY_CLOSE);  ?></button>
                <form method="post">
                    <button type="button" class="btn btn-danger" name='delete-account' id="btn_ask_delete_account"><i class="bi bi-trash mr-2 ml-2"></i><?php echo $dictionary->get_lang($lang,$KEY_DELETE);  ?></button>
                </form>
            </div>
        </div>
    </div>
</div>
