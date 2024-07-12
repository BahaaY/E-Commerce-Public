<div class="card mb-3">
    <div class="card-header p-2" id="headingThree">
        <h5 class="mb-0">
            <button class="btn btn-link collapsed card-header-title" data-toggle="collapse" data-target="#collapseThree"
                aria-expanded="false" aria-controls="headingThree">
                <?php echo $dictionary->get_lang($lang,$KEY_ORDER_TYPE); ?>
            </button>
        </h5>
    </div>
    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
        <div class="card-body">
            <div class="alert alert-success alert-dismissible fade show col-12 mt-2" role="alert"
                id="alert-success-insert-order-type">
                Order type has been added.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="alert alert-success alert-dismissible fade show col-12 mt-2" role="alert"
                id="alert-success-order-type">
                Order type has been updated.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="alert alert-success alert-dismissible fade show col-12 mt-2" role="alert"
                id="alert-success-delete-order-type">
                Order type has been deleted.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="alert alert-danger alert-dismissible fade show col-12 mt-2" role="alert"
                id="alert-danger-order-type">
                Error occurred.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="table-responsive mt-2">
                <table class="table bg-white" id="table_order_typer">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th><?php echo $dictionary->get_lang($lang,$KEY_NAME); ?></th>
                            <th><?php echo $dictionary->get_lang($lang,$KEY_AMOUNT); ?></th>
                            <th><?php echo $dictionary->get_lang($lang,$KEY_AVAILABILITY); ?></th>
                            <th><?php echo $dictionary->get_lang($lang,$KEY_ACTION); ?></th>
                        </tr>
                    </thead>
                    <tbody id="tbody_order_type">
                        <?php
                                                        $row="";
                                                        $index=0;
                                                        $orders_type=$class_order_type->get_all_order_type();
                                                        foreach($orders_type as $order_type){
                                                            $index++;
                                                            $order_type_id=$order_type['order_type_id'];
                                                            $order_type_name=$order_type['order_type_name'];
                                                            $amount=$order_type['amount'];
                                                            $availability=$order_type['availability'];
                                                            $row.="
                                                                <tr id='tr_order_type_".$order_type_id."'>
                                                                    <th>".$index."</th>
                                                                    <td>
                                                                        <input class='form-control' type='text' id='order_type_name_".$order_type_id."' value='".$order_type_name."' placeholder='".$dictionary->get_lang($lang,$KEY_ENTER_ORDER_TYPE_NAME)."'>
                                                                        <span class='text-danger' id='error_order_type_name_".$order_type_id."'></span>
                                                                    </td>
                                                                    <td>
                                                                        <input class='form-control' type='text' id='order_type_amount_".$order_type_id."' value='".$amount."' placeholder='".$dictionary->get_lang($lang,$KEY_ENTER_AMOUNT)."'>
                                                                        <span class='text-danger' id='error_order_type_amount_".$order_type_id."'></span>
                                                                    </td>
                                                                    <td>
                                                                        <select class='form-control' id='order_type_availability_".$order_type_id."'>";
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
                                                                        <button class='btn btn-primary' id='btn_edit_order_type_".$order_type_id."' onclick='edit_order_type(".$order_type_id.");'><i class='bi bi-pen mr-2 ml-2'></i><span class='d-none d-sm-inline-block'>".$dictionary->get_lang($lang,$KEY_EDIT)."</span></button>
                                                                        <button class='btn btn-danger d-none' id='btn_delete_order_type_".$order_type_id."' onclick='delete_order_type(".$order_type_id.");'><i class='bi bi-trash mr-2 ml-2'></i><span class='d-none d-sm-inline-block'>".$dictionary->get_lang($lang,$KEY_DELETE)."</span></button>
                                                                    </td>
                                                                </tr>
                                                            ";
                                                        }
                                                        
                                                        echo $row;
                                                    ?>
                    </tbody>
                    <tfoot class="d-none">
                        <tr>
                            <td colspan='5'><?php echo $dictionary->get_lang($lang,$KEY_ADD_ORDER_TYPE); ?></td>
                        </tr>
                        <tr>
                            <td colspan='2'>
                                <input type='text' id='order_type_name_0' class='form-control'
                                    placeholder='<?php echo $dictionary->get_lang($lang,$KEY_ENTER_ORDER_TYPE_NAME); ?>'>
                                <span class='text-danger' id='error_order_type_name_0'></span>
                            </td>
                            <td>
                                <input type='text' id='order_type_amount_0' class='form-control'
                                    placeholder='<?php echo $dictionary->get_lang($lang,$KEY_ENTER_AMOUNT); ?>'>
                                <span class='text-danger' id='error_order_type_amount_0'></span>
                            </td>
                            <td>
                                <select class='form-control' id='order_type_availability_0'>
                                    <option value='1' selected>Show</option>
                                    <option value='0'>Hide</option>
                                </select>
                            </td>
                            <td>
                                <button class='btn btn-success' id='btn_add_order_type' onclick='add_order_type(<?php echo $index; ?>);'>
                                    <i class='bi bi-plus mr-2 ml-2'></i>
                                    <span class='d-none d-sm-inline-block'><?php echo $dictionary->get_lang($lang,$KEY_ADD); ?></span>
                                </button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>