<div class="card mb-3">
    <div class="card-header p-2" id="headingOne">
        <h5 class="mb-0">
            <button class="btn btn-link card-header-title" data-toggle="collapse" data-target="#collapseOne"
                aria-expanded="true" aria-controls="collapseOne">
                <?php echo $dictionary->get_lang($lang,$KEY_PRODUCT_TYPE); ?>
            </button>
        </h5>
    </div>
    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
        <div class="card-body">
            <div class="alert alert-success alert-dismissible fade show col-12 mt-2" role="alert"
                id="alert-success-insert-product-type">
                Product type has been added.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="alert alert-success alert-dismissible fade show col-12 mt-2" role="alert"
                id="alert-success-product-type">
                Product type has been updated.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="alert alert-success alert-dismissible fade show col-12 mt-2" role="alert"
                id="alert-success-delete-product-type">
                Product type has been deleted.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="alert alert-danger alert-dismissible fade show col-12 mt-2" role="alert"
                id="alert-danger-product-type">
                Error occurred.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="table-responsive mt-2">
                <table class="table bg-white" id="table_product_type">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th><?php echo $dictionary->get_lang($lang,$KEY_NAME); ?></th>
                            <th><?php echo $dictionary->get_lang($lang,$KEY_SIZE_TYPE); ?></th>
                            <th><?php echo $dictionary->get_lang($lang,$KEY_AVAILABILITY); ?></th>
                            <th><?php echo $dictionary->get_lang($lang,$KEY_PRODUCT_AVAILABILITY); ?></th>
                            <th><?php echo $dictionary->get_lang($lang,$KEY_ACTION); ?></th>
                        </tr>
                    </thead>
                    <tbody id="tbody_product_type">
                        <?php
                            $row="";
                            $index=0;
                            $products_type=$class_product_type->get_all_product_type();
                            $products_size_type=$class_product_type->get_all_product_size_type();
                            foreach($products_type as $product_type){
                                $index++;
                                $product_type_id=$product_type['product_type_id'];
                                $product_type_name=$product_type['product_type_name'];
                                $availability=$product_type['availability'];
                                $product_size_type=$product_type['product_size_type_id_FK'];
                                $product_availability=$product_type['product_availability'];
                                                                                            
                                $row.="
                                    <tr id='tr_product_type_".$product_type_id."'>
                                        <th>".$index."</th>
                                        <td>
                                            <input class='form-control' type='text' id='product_type_name_".$product_type_id."' value='".$product_type_name."' placeholder='".$dictionary->get_lang($lang,$KEY_ENTER_PRODUCT_TYPE_NAME)."'>
                                            <span class='text-danger' id='error_product_type_name_".$product_type_id."'></span>
                                        </td>
                                        <td>
                                            <select class='form-control' id='product_size_type_".$product_type_id."'>";
                                                foreach($products_size_type as $size_type){
                                                    $size_type_id= $size_type['product_size_type_id'];
                                                    $size_type_name= $size_type['product_size_type_name'];
                                                    if($product_size_type == $size_type_id){
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
                                            <select class='form-control' id='product_type_availability_".$product_type_id."'>";
                                                $text="";
                                                for($j=1;$j>=0;$j--){
                                                    if($availability == $j){
                                                        $selected="selected";
                                                    }else{
                                                        $selected="";
                                                    }
                                                    if($j==1){
                                                        $text="Show";
                                                    }else{
                                                        $text="Hide";
                                                    }
                                                    $row.="<option value='".$j."' ".$selected.">$text</option>";
                                                }
                                                $row.="
                                            </select>
                                        </td>
                                        <td>
                                            <select class='form-control' id='product_availability_".$product_type_id."'>";
                                                $text="";
                                                for($j=1;$j>=0;$j--){
                                                    if($product_availability == $j){
                                                        $selected="selected";
                                                    }else{
                                                        $selected="";
                                                    }
                                                    if($j==1){
                                                        $text="Show";
                                                    }else{
                                                        $text="Hide";
                                                    }
                                                    $row.="<option value='".$j."' ".$selected.">$text</option>";
                                                }
                                                $row.="
                                            </select>
                                        </td>
                                        <td>
                                            <button class='btn btn-primary' id='btn_edit_product_type_".$product_type_id."' onclick='edit_product_type(".$product_type_id.");'><i class='bi bi-pen mr-2 ml-2'></i><span class='d-none d-sm-inline-block'>".$dictionary->get_lang($lang,$KEY_EDIT)."</span></button>
                                            <button class='btn btn-danger d-none' id='btn_delete_product_type_".$product_type_id."' onclick='delete_product_type(".$product_type_id.");'><i class='bi bi-trash mr-2 ml-2'></i><span class='d-none d-sm-inline-block'>".$dictionary->get_lang($lang,$KEY_DELETE)."</span></button>
                                        </td>
                                    </tr>
                                ";
                            }
                            echo $row;                                
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan='5'><?php echo $dictionary->get_lang($lang,$KEY_ADD_PRODUCT_TYPE); ?></td>
                        </tr>
                        <tr>
                            <td colspan='2'>
                                <input type='text' class='form-control' id='product_type_name_0'
                                    placeholder='<?php echo $dictionary->get_lang($lang,$KEY_ENTER_PRODUCT_TYPE_NAME); ?>'>
                                <span class='text-danger' id='error_product_type_name_0'></span>
                            </td>
                            <td>
                                <select class='form-control' id='product_size_type_0'>
                                    <?php
                                        foreach($products_size_type as $size_type){
                                            $size_type_id= $size_type['product_size_type_id'];
                                            $size_type_name= $size_type['product_size_type_name'];
                                            echo "<option value='".$size_type_id."'>$size_type_name</option>";
                                        }
                                    ?>
                                </select>
                            <td>
                                <select class='form-control' id='product_type_availability_0'>
                                    <option value='1' selected>Show</option>
                                    <option value='0'>Hide</option>
                                </select>
                            </td>
                            <td>
                                <select class='form-control' id='product_availability_0'>
                                    <option value='1' selected>Show</option>
                                    <option value='0'>Hide</option>
                                </select>
                            </td>
                            <td>
                                <button class='btn btn-success' id='btn_add_product_type' onclick='add_product_type(<?php echo $index; ?>);'>
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