<div class="modal fade" id="modal-sign-out" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo $dictionary->get_lang($lang,$KEY_SIGN_OUT);  ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want sign out?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class='fa fa-close mr-2'></i><?php echo $dictionary->get_lang($lang,$KEY_CLOSE);  ?></button>
                <form method="post">
                    <button type="submit" class="btn btn-danger" name='logout'><i class='bi bi-box-arrow-right mr-2'></i><?php echo $dictionary->get_lang($lang,$KEY_SIGN_OUT);  ?></button>
                </form>
            </div>
        </div>
    </div>
</div>
