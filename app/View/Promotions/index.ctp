<div class="row">
    <form method="get" >
        <div class="col-xs-12">

            <div class="form-group col-xs-12 col-sm-3">
                <label for="name">Tên sản phẩm</label>
                <div class="clearfix"></div>
                <div >
                    <input type="text" class="form-control valid" name="name" id="enddate" value="<?php echo $name ?>">
                    </span>
                </div>
            </div>
            <div class="form-group col-xs-12 col-sm-3">
                <label for="category">Danh mục</label>
                <div class="clearfix"></div>
                <div >
                    <select class="form-control" name="category">
                        <option value="" selected="">-- Tất cả --</option>
                        <?php foreach ($category as $key => $value): ?>
                            <option value="<?php echo $value['Category']['id'] ?>"><?php echo $value['Category']['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    </span>
                </div>
            </div>
            <div class="form-group col-xs-12 col-sm-3">
                <label for="startdate">Từ</label>
                <div class="clearfix"></div>
                <div >
                    <input type="date" class="form-control valid" name="startdate" id="startdate" value="<?php echo $start_time ?>">
                    </span>
                </div>
            </div>
            <div class="form-group col-xs-12 col-sm-3">
                <label for="enddate">Đến</label>
                <div class="clearfix"></div>
                <div >
                    <input type="date" class="form-control valid" name="enddate" id="enddate" value="<?php echo $end_time ?>">
                    </span>
                </div>
            </div>


            <div class="clearfix"></div>
            <div class="row-fluid text-center" style="margin: 15px 0">
                <input type="submit" class="btn btn-primary" name="search" value="search">
                <!--<input type="reset" class="btn btn-default" id="reset" value="reset">-->
            </div>
        </div>
    </form>
</div>

<?php
$option = array();
$option['title'] = 'Sản phẩm';
$option['col'] = array(
    0 => array('key_tab' => 'id', 'title_tab' => 'STT', 'option_tab' => 'sort'),
    1 => array('key_tab' => 'date_create', 'title_tab' => 'Ngày tạo', 'option_tab' => ''),
    2 => array('key_tab' => 'product_code', 'title_tab' => 'Mã HH', 'option_tab' => ''),
    3 => array('key_tab' => 'avatar', 'title_tab' => 'avatar', 'option_tab' => 'sort'),
    4 => array('key_tab' => 'name', 'title_tab' => 'Tên', 'option_tab' => ''),
    5 => array('key_tab' => 'price', 'title_tab' => 'Giá bán lẻ', 'option_tab' => 'sort'),
    6 => array('key_tab' => 'price origin ', 'title_tab' => 'Giá C0', 'option_tab' => 'sort'),
    7 => array('key_tab' => 'name', 'title_tab' => 'Giá  C1', 'option_tab' => ''),
    8 => array('key_tab' => 'name', 'title_tab' => 'Giá  C2', 'option_tab' => ''),
    9 => array('key_tab' => 'sale', 'title_tab' => 'sale', 'option_tab' => 'sort'),
    10 => array('key_tab' => 'title', 'title_tab' => 'Danh mục cha', 'option_tab' => 'sort'),
    11 => array('key_tab' => 'category', 'title_tab' => 'Danh mục con', 'option_tab' => 'sort'),
    12 => array('key_tab' => 'category', 'title_tab' => 'Kho hàng', 'option_tab' => 'sort'),
    13 => array('key_tab' => '', 'title_tab' => 'aption', 'option_tab' => ''),
);
echo $this->grid->create($products, null, $option);
?>
<?php foreach ($products as $key => $product): ?>
    <tr>
        <td><?php echo h($product['Product']['id']); ?>&nbsp;</td>
        <td><?php echo h($product['Product']['date_create']); ?>&nbsp;</td>
        <td><?php echo h($product['Product']['product_code']); ?>&nbsp;</td>
        <td><img src="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/' . h($product['Product']['avatar']); ?>" style="width: 120px;height: 80px;"></td>
        <td><?php echo h($product['Product']['name']); ?>&nbsp;</td>
        <td><?php echo h($product['Product']['price']); ?>&nbsp;</td>
        <td><?php echo h($product['Product']['c0']); ?>&nbsp;</td>
        <td><?php echo h($product['Product']['partner_price']); ?>&nbsp;</td>
        <td><?php echo h($product['Product']['employee_price']); ?>&nbsp;</td>
        <td><?php echo h($product['Product']['sale']); ?>&nbsp;</td>
        <td>
            <?php echo $this->Html->link($product['Category']['name'], array('controller' => 'categories', 'action' => 'index', $product['Category']['id'])); ?>
        </td>
        <td>
            <?php echo $this->Html->link($product['Subcategory']['name'], array('controller' => 'subcategories', 'action' => 'index', $product['Subcategory']['id'])); ?>
        </td>
        <td><?php echo h($product['Product']['storage']); ?>&nbsp;</td>
        <td class="actions">
            <?php
            echo $this->Html->link(
                    $this->Html->tag('i', '', array('class' => 'glyphicon glyphicon-edit icon-white', 'title' => 'Edit')), array('action' => 'edit', $product['Product']['id']), array('escape' => false, 'class' => 'btn btn-success btn-sm')
            ) . '&nbsp';
            echo $this->Form->postLink(
                    $this->Html->tag('i', '', array('class' => 'glyphicon glyphicon-remove icon-white', 'title' => 'Delete')), array('action' => 'delete', $product['Product']['id']), array('escape' => false, 'class' => 'btn btn-danger btn-sm btn-cat-cancel'), __('Bạn có chắc muốn xóa', $product['Product']['id'])
            ) . '&nbsp';
            ?>
        </td>
    </tr>
<?php endforeach; ?>
<?php echo $this->grid->end_table($products, null, $option);
?>
