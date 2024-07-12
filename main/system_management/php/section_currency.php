<div class="card mb-3">
    <div class="card-header p-2" id="headingSix">
        <h5 class="mb-0">
            <button class="btn btn-link collapsed card-header-title" data-toggle="collapse" data-target="#collapseSix"
                aria-expanded="false" aria-controls="headingSix">
                <?php echo $dictionary->get_lang($lang,$KEY_CURRENCY); ?>
            </button>
        </h5>
    </div>
    <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordion">
        <div class="card-body">
            <div class="alert alert-success alert-dismissible fade show col-12 mt-2" role="alert"
                id="alert-success-currency">
                Currency has been updated.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="alert alert-danger alert-dismissible fade show col-12 mt-2" role="alert"
                id="alert-danger-currency">
                Error occurred.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="table-responsive mt-2">
                <table class="table bg-white" id="table_currency">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th><?php echo $dictionary->get_lang($lang,$KEY_CURRENCY_ABBREVIATION); ?></th>
                            <th><?php echo $dictionary->get_lang($lang,$KEY_CURRENCY_RATE); ?></th>
                            <th><?php echo $dictionary->get_lang($lang,$KEY_AVAILABILITY); ?></th>
                            <th><?php echo $dictionary->get_lang($lang,$KEY_ACTION); ?></th>
                        </tr>
                    </thead>
                    <tbody id="tbody_order_type">
                        <?php
                                                        $row="";
                                                        $index=0;
                                                        $all_currency=get_all_currency($db_conn->get_link());
                                                        foreach($all_currency as $currency_info){
                                                            $index++;
                                                            $currency_id=$currency_info['currency_id'];
                                                            $currency_abbreviation=$currency_info['currency_abbreviation'];
                                                            $currency_rate=$currency_info['currency_rate'];
                                                            $availability=$currency_info['availability'];
                                                            if($currency_id == 1){
                                                                $is_disabled="disabled";
                                                                $style="style='cursor: not-allowed'";
                                                            }else{
                                                                $is_disabled="";
                                                                $style="";
                                                            }
                                                            $row.="
                                                                <tr id='tr_currency_".$currency_id."'>
                                                                    <th>".$index."</th>
                                                                    <td>
                                                                        <input class='form-control' type='text' value='".$currency_abbreviation."' disabled>
                                                                    </td>
                                                                    <td>
                                                                        <input class='form-control' type='text' id='currency_rate_".$currency_id."' value='".$currency_rate."' ".$is_disabled." ".$style." placeholder='".$dictionary->get_lang($lang,$KEY_ENTER_CURRENCY_RATE)."'>
                                                                        <span class='text-danger' id='error_currency_rate_".$currency_id."'></span>
                                                                    </td>
                                                                    <td>
                                                                        <select class='form-control' id='currency_availability_".$currency_id."'>";
                                                                            $text="";
                                                                            for($j=1;$j>=0;$j--){
                                                                                if($availability == $j){
                                                                                    $checked="selected";
                                                                                }else{
                                                                                    $checked="";
                                                                                }
                                                                                if($j==1){
                                                                                    $text="Show";
                                                                                }else{
                                                                                    $text="Hide";
                                                                                }
                                                                                $row.="<option value='".$j."' ".$checked.">$text</option>";
                                                                            }
                                                                        $row.="
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <button class='btn btn-primary' id='btn_edit_currency_".$currency_id."' onclick='edit_currency(".$currency_id.");'><i class='bi bi-pen mr-2 ml-2'></i><span class='d-none d-sm-inline-block'>".$dictionary->get_lang($lang,$KEY_EDIT)."</span></button>
                                                                    </td>
                                                                </tr>
                                                            ";
                                                        }
                                                        
                                                        echo $row;
                                                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>