<div class="card mb-3">
    <div class="card-header p-2" id="headingFive">
        <h5 class="mb-0">
            <button class="btn btn-link collapsed card-header-title" data-toggle="collapse" data-target="#collapseFive"
                aria-expanded="false" aria-controls="headingFour">
                <?php echo $dictionary->get_lang($lang,$KEY_DICTIONARY); ?>
            </button>
        </h5>
    </div>
    <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion">
        <div class="card-body">
            <div class="alert alert-success alert-dismissible fade show col-12 mt-2" role="alert"
                id="alert-success-dictionary">
                Key has been updated.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="alert alert-danger alert-dismissible fade show col-12 mt-2" role="alert"
                id="alert-danger-dictionary">
                Error occurred.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="row mt-2">
                <div class="col-md-12 p-0 m-0">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="table_dictionary">
                            <thead class="bg-light" id="thead">
                                <tr>
                                    <th>#</th>
                                    <th><?php echo $dictionary->get_lang($lang,$KEY_ENGLISH); ?></th>
                                    <th><?php echo $dictionary->get_lang($lang,$KEY_FRENCH); ?></th>
                                    <th><?php echo $dictionary->get_lang($lang,$KEY_ARABIC); ?></th>
                                    <th><?php echo $dictionary->get_lang($lang,$KEY_ACTION); ?></th>
                                </tr>
                            </thead>
                            <tbody id="tbody">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>