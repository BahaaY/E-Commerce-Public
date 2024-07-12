<div class="row">
    <div class="col-md-12">
        <div class="alert alert-success alert-dismissible fade show" role="alert" id="alert-success-user-profile">
            <span id="text-success-user-profile">Profile has been updated</span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
    <div class="col-md-12">
        <div class="alert alert-danger alert-dismissible fade show" role="alert" id="alert-danger-user-profile">
            <span id="text-danger-user-profile">Error occurred</span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
</div>

<div class="row mb-2">
    <label for="profileImage" class="col-md-4 col-lg-3 col-form-label"><?php echo $dictionary->get_lang($lang,$KEY_PROFILE_IMAGE); ?></label>
    <div class="col-md-8 col-lg-9">
        <img src="<?php echo $profile_image; ?>" alt="Profile" id="image_profile">
        <div class="pt-2">
            <a href="<?php echo $profile_image; ?>" class="btn btn-success btn-sm" title="Download my profile image" download
                id="btn-download-image-profile"><i class="bi bi-download"></i></a>
            <a role="button" class="btn btn-primary btn-sm btn-file-new-profile-image"
                title="Upload new profile image"><i class="bi bi-upload"></i>
                <input type="file" id="btn_update_image_profile"></a>
            <a role="button" class="btn btn-danger btn-sm" title="Remove my profile image"><i class="bi bi-trash"
                    data-toggle="modal" data-target="#remove-profile-image"></i></a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-2">
        <div class="row">
            <div class="col-md-6">
                <label for="username" class="mb-0"><?php echo $dictionary->get_lang($lang,$KEY_USERNAME); ?></label> <span class="text-danger">*</span></label>
            </div>
            <div class="col-md-6 text-right" <?php echo $dir_required ?>>
                <span class='text-danger' id="error_username"></span>
            </div>
            <div class="col-md-12">
                <input name="username" type="text" class="form-control" id="username" value="<?php echo $username; ?>"
                    placeholder="<?php echo $dictionary->get_lang($lang,$KEY_ENTER_USERNAME);  ?>">
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-2">
        <div class="row">
            <div class="col-md-6">
                <label for="country" class="mb-0"><?php echo $dictionary->get_lang($lang,$KEY_COUNTRY); ?></label></label>
            </div>
            <div class="col-md-6 text-right" <?php echo $dir_required ?>>
                <span class='text-danger' id="error_country"></span>
            </div>
            <div class="col-md-12">
                <input name="country" type="text" class="form-control" id="country" value="<?php echo $country; ?>"
                    placeholder="<?php echo $dictionary->get_lang($lang,$KEY_ENTER_COUNTRY);  ?>">
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-2">
        <div class="row">
            <div class="col-md-6">
                <label for="region" class="mb-0"><?php echo $dictionary->get_lang($lang,$KEY_REGION); ?></label></label>
            </div>
            <div class="col-md-6 text-right" <?php echo $dir_required ?>>
                <span class='text-danger' id="error_region"></span>
            </div>
            <div class="col-md-12">
                <input name="region" type="text" class="form-control" id="region" value="<?php echo $region; ?>"
                    placeholder="<?php echo $dictionary->get_lang($lang,$KEY_ENTER_REGION);  ?>">
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-2">
        <div class="row">
            <div class="col-md-6">
                <label for="address" class="mb-0"><?php echo $dictionary->get_lang($lang,$KEY_ADDRESS); ?></label></label>
            </div>
            <div class="col-md-6 text-right" <?php echo $dir_required ?>>
                <span class='text-danger' id="error_address"></span>
            </div>
            <div class="col-md-12">
                <input name="address" type="text" class="form-control" id="address" value="<?php echo $address; ?>"
                    placeholder="<?php echo $dictionary->get_lang($lang,$KEY_ENTER_ADDRESS);  ?>">
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-2">
        <div class="row">
            <div class="col-md-6">
                <label for="phone_number" class="mb-0"><?php echo $dictionary->get_lang($lang,$KEY_PHONE); ?></label></label>
            </div>
            <div class="col-md-6 text-right" <?php echo $dir_required ?>>
                <span class='text-danger' id="error_phone_number"></span>
            </div>
            <div class="col-md-12">
                <input name="phone_number" type="text" class="form-control" id="phone_number" value="<?php echo $phone_number; ?>"
                    placeholder="<?php echo $dictionary->get_lang($lang,$KEY_ENTER_PHONE_NUMBER);  ?>">
            </div>
        </div>
    </div>
    
</div>

<div class="text-center">
    <button type="button" id='btn_update_user_profile' class="btn btn-primary"><i class="bi bi-pen mr-2 ml-2"></i><?php echo $dictionary->get_lang($lang,$KEY_SAVE_CHANGES) ?></button>
</div>
