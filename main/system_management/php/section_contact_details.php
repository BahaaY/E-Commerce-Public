<?php

    $address="";
    $phone_numbe="";
    $email="";
                            
    $contact_details_info=$class_contact_details->get_contact_details();
    if($contact_details_info){
        $address=$contact_details_info['address'];
        $phone_number=$contact_details_info['phone_number'];
        $email=$contact_details_info['email'];
    }

?>

<div class="card mb-3">
    <div class="card-header p-2" id="headingFour">
        <h5 class="mb-0">
            <button class="btn btn-link collapsed card-header-title" data-toggle="collapse" data-target="#collapseFour"
                aria-expanded="false" aria-controls="headingFour">
                <?php echo $dictionary->get_lang($lang,$KEY_CONTACT_DETAILS); ?>
            </button>
        </h5>
    </div>
    <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
        <div class="card-body">
            <div class="alert alert-success alert-dismissible fade show col-12 mt-2" role="alert"
                id="alert-success-contact-details">
                Contact details has been updated.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="alert alert-danger alert-dismissible fade show col-12 mt-2" role="alert"
                id="alert-danger-contact-details">
                Error occurred.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="row mt-2">
                <div class="col-md-4">
                    <div class="form-group mb-0">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="mb-0"><?php echo $dictionary->get_lang($lang,$KEY_ADDRESS); ?></label>
                                <span class="text-danger d-none">*</span>
                            </div>
                            <div class="col-md-6 text-right" <?php echo $dir_required; ?>>
                                <span class="text-danger" id="error_address"></span>
                            </div>
                            <div class="col-md-12">
                                <input type="text" class="form-control" id="contact_address"
                                    placeholder="<?php echo $dictionary->get_lang($lang,$KEY_ENTER_ADDRESS); ?>"
                                    value="<?php echo $address; ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-0">
                        <div class="row">
                            <div class="col-md-6">
                                <label
                                    class="mb-0"><?php echo $dictionary->get_lang($lang,$KEY_PHONE_NUMBER); ?></label>
                                <span class="text-danger d-none">*</span>
                            </div>
                            <div class="col-md-6 text-right" <?php echo $dir_required; ?>>
                                <span class="text-danger" id="error_phone_number"></span>
                            </div>
                            <div class="col-md-12">
                                <input type="text" class="form-control" id="contact_phone_number"
                                    placeholder="<?php echo $dictionary->get_lang($lang,$KEY_ENTER_PHONE_NUMBER); ?>"
                                    value="<?php echo $phone_number; ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-0">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="mb-0"><?php echo $dictionary->get_lang($lang,$KEY_EMAIL); ?></label>
                                <span class="text-danger d-none">*</span>
                            </div>
                            <div class="col-md-6 text-right" <?php echo $dir_required; ?>>
                                <span class="text-danger" id="error_email"></span>
                            </div>
                            <div class="col-md-12">
                                <input type="text" class="form-control" id="contact_email"
                                    placeholder="<?php echo $dictionary->get_lang($lang,$KEY_ENTER_EMAIL); ?>"
                                    value="<?php echo $email; ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 text-right mt-2">
                    <div class="form-group mb-0">
                        <button class="btn btn-primary" onclick='edit_contact_details();' id="btn_edit_contact_details">
                            <i class="bi bi-pen mr-2 ml-2"></i>
                            <span class='d-none d-sm-inline-block'><?php echo $dictionary->get_lang($lang,$KEY_EDIT); ?></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>