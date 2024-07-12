<div class="card mb-3">
    <div class="card-header p-2" id="headingTwo">
        <h5 class="mb-0">
            <button class="btn btn-link collapsed card-header-title" data-toggle="collapse" data-target="#collapseTwo"
                aria-expanded="false" aria-controls="collapseTwo">
                <?php echo $dictionary->get_lang($lang,$KEY_PRODUCT_SIZE); ?>
            </button>
        </h5>
    </div>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
        <div class="card-body">
            <div class="alert alert-success alert-dismissible fade show col-12 mt-2" role="alert"
                id="alert-success-insert-product-size">
                Product size has been added.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="alert alert-success alert-dismissible fade show col-12 mt-2" role="alert"
                id="alert-success-product-size">
                Product size has been updated.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="alert alert-success alert-dismissible fade show col-12 mt-2" role="alert"
                id="alert-success-delete-product-size">
                Product size has been deleted.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="alert alert-danger alert-dismissible fade show col-12 mt-2" role="alert"
                id="alert-danger-product-size">
                Error occurred.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="table-responsive mt-2">
                <table class="table bg-white" id="table_product_size">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th><?php echo $dictionary->get_lang($lang,$KEY_NAME); ?></th>
                            <th><?php echo $dictionary->get_lang($lang,$KEY_SIZE_TYPE); ?></th>
                            <th><?php echo $dictionary->get_lang($lang,$KEY_AVAILABILITY); ?></th>
                            <th><?php echo $dictionary->get_lang($lang,$KEY_ACTION); ?></th>
                        </tr>
                    </thead>
                    <tbody id="tbody_product_size">
                        <?php
                                                        $row="";
                                                        $index=0;
                                                        $products_size=$class_product_size->get_all_product_size();
                                                        foreach($products_size as $product_size){
                                                            $index++;
                                                            $product_size_id=$product_size['product_size_id'];
                                                            $product_size_type_id_FK=$product_size['product_size_type_id_FK'];
                                                            $product_size_name=$product_size['product_size_name'];
                                                            $availability=$product_size['availability'];
                                                            $row.="
                                                                <tr id='tr_product_size_".$product_size_id."'>
                                                                    <th>".$index."</th>
                                                                    <td>
                                                                        <input class='form-control' type='text' id='product_size_name_".$product_size_id."' value='".$product_size_name."' placeholder='".$dictionary->get_lang($lang,$KEY_ENTER_PRODUCT_SIZE_NAME)."'>
                                                                        <span class='text-danger' id='error_product_size_name_".$product_size_id."'></span>
                                                                    </td>
                                                                    <td>
                                                                        <select class='form-control' id='product_size_type_for_product_size_field_".$product_size_id."'>";
                                                                        
                                                                            $product_size_type_for_product_size_field=$class_product_type->get_all_product_size_type_for_product_size_field();
                                                                            foreach($product_size_type_for_product_size_field as $size_type){
                                                                                $size_type_id= $size_type['product_size_type_id'];
                                                                                $size_type_name= $size_type['product_size_type_name'];
                                                                                if($product_size_type_id_FK == $size_type_id){
                                                                                    $selected="selected";
                                                                                }else{
                                                                                    $selected="";
                                                                                }
                                                                                $row.="<option value='".$size_type_id."' ".$selected.">$size_type_name</option>";
                                                                            }
                                                                    $row.="
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <select class='form-control' id='product_size_availability_".$product_size_id."'>";
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
                                                                        <button class='btn btn-primary' id='btn_edit_product_size_".$product_size_id."' onclick='edit_product_size(".$product_size_id.");'><i class='bi bi-pen mr-2 ml-2'></i><span class='d-none d-sm-inline-block'>".$dictionary->get_lang($lang,$KEY_EDIT)."</span></button>
                                                                        <button class='btn btn-danger d-none' id='btn_delete_product_size_".$product_size_id."' onclick='delete_product_size(".$product_size_id.");'><i class='bi bi-trash mr-2 ml-2'></i><span class='d-none d-sm-inline-block'>".$dictionary->get_lang($lang,$KEY_DELETE)."</span></button>
                                                                    </td>
                                                                </tr>
                                                            ";
                                                        }
                                                        echo $row;
                                                    ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan='4'><?php echo $dictionary->get_lang($lang,$KEY_ADD_PRODUCT_SIZE); ?></td>
                        </tr>
                        <tr>
                            <td colspan='2'>
                                <input type='text' class='form-control' id='product_size_name_0'
                                    placeholder='<?php echo $dictionary->get_lang($lang,$KEY_ENTER_PRODUCT_SIZE_NAME); ?>'>
                                <span class='text-danger' id='error_product_size_name_0'></span>
                            </td>
                            <td>
                                <select class='form-control' id='product_size_type_for_product_size_field_0'>
                                    <?php
                                        $product_size_type_for_product_size_field=$class_product_type->get_all_product_size_type_for_product_size_field();
                                        foreach($product_size_type_for_product_size_field as $size_type){
                                            $size_type_id= $size_type['product_size_type_id'];
                                            $size_type_name= $size_type['product_size_type_name'];                                      
                                            echo "<option value='".$size_type_id."'>$size_type_name</option>";
                                        }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <select class='form-control' id='product_size_availability_0'>
                                    <option value='1' selected>Show</option>
                                    <option value='0'>Hide</option>
                                </select>
                            </td>
                            <td>
                                <button class='btn btn-success' id='btn_add_product_size' onclick='add_product_size(<?php echo $index; ?>);'>
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