<div class="alert alert-success alert-success alert-dismissible fade show" role="alert" id="alert-success-email">
    <span class='text-alert-success-email'>Verification code has been sent to your
        account. You will be redirected to verification page after <span id='text-alert-success-email-counter'>10</span>s</span>
</div>

<div class="alert alert-danger alert-danger alert-dismissible fade show" role="alert" id="alert-danger-email">
    <span id="text-danger-email">Error occurred</span>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="row mb-2">
    <div class="col-md-6">
        <label for="email_change_email" class="mb-0"><?php echo $dictionary->get_lang($lang,$KEY_NEW_EMAIL); ?></label> <span class="text-danger">*</span></label>
    </div>
    <div class="col-md-6 text-right" <?php echo $dir_required ?>>
        <span class="text-danger" id='error-email'></span>
    </div>
    <div class="col-md-12">
        <input name="email_change_email" type="email" class="form-control" id="email_change_email" placeholder="<?php echo $dictionary->get_lang($lang,$KEY_ENTER_EMAIL);  ?>">
    </div>
</div>

<div class="text-center">
    <button type="button" class="btn btn-primary" id='btn_change_email'><i class="bi bi-pen mr-2 ml-2"></i><?php echo $dictionary->get_lang($lang,$KEY_CHANGE_EMAIL) ?></button>
</div>