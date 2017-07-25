<div class="row">
    <form method="get" >
        <div class="col-xs-12">
            <div class="form-group col-xs-12 col-sm-3">
                <!--<label for="name"></label>-->
                <div class="clearfix"></div>
                <div >
                    <input type="text" class="form-control valid" name="name" id="enddate" value="<?php echo '' ?>">
                    </span>
                </div>
            </div>
            <div class="form-group col-xs-12 col-sm-2">
                <div class="clearfix"></div>
                <input type="submit" class="btn btn-primary" name="search" value="search">
            </div>
        </div>
    </form>
</div>
<?php
$option = array();
$option['title'] = 'Products';
$option['col'] = array(
    0 => array('key_tab' => 'id', 'title_tab' => 'STT', 'option_tab' => 'sort'),
    1 => array('key_tab' => 'name', 'title_tab' => 'Tên danh mục', 'option_tab' => ''),
    2 => array('key_tab' => 'avatar', 'title_tab' => 'Avatar', 'option_tab' => ''),
    3 => array('key_tab' => 'title', 'title_tab' => 'Danh mục cha', 'option_tab' => 'sort'),
    4 => array('key_tab' => 'description', 'title_tab' => 'Thứ tự', 'option_tab' => 'sort'),
    5 => array('key_tab' => '', 'title_tab' => 'Chỉnh sửa', 'option_tab' => ''),
);
echo $this->grid->create($subcategories, null, $option);
?>
<?php foreach ($subcategories as $key => $subcategory): ?>
    <tr>
        <td><?php echo $key + 1; ?>&nbsp;</td>
        <td><?php echo h($subcategory['Subcategory']['name']); ?>&nbsp;</td>
        <td><img src="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/' . h($subcategory['Subcategory']['avatar']); ?>" style="width: 120px;height: 80px;"></td>
        <td>
            <?php echo $this->Html->link($subcategory['Category']['name'], array('controller' => 'categories', 'action' => 'index', $subcategory['Category']['id'])); ?>
            <?php // echo $this->Html->link($subcategory['Category']['name'], array('controller' => 'categories', 'action' => 'view', $subcategory['Category']['id'])); ?>
        </td>
        <td><?php echo h($subcategory['Subcategory']['sort']); ?>&nbsp;</td>
        <td class="actions">
            <?php
            echo $this->Html->link(
                    $this->Html->tag('i', '', array('class' => 'glyphicon glyphicon-edit icon-white', 'title' => 'Edit')), array('action' => 'edit', $subcategory['Subcategory']['id']), array('escape' => false, 'class' => 'btn btn-success btn-sm')
            ) . '&nbsp';
            echo $this->Form->postLink(
                    $this->Html->tag('i', '', array('class' => 'glyphicon glyphicon-remove icon-white', 'title' => 'Delete')), array('action' => 'delete', $subcategory['Subcategory']['id']), array('escape' => false, 'class' => 'btn btn-danger btn-sm btn-cat-cancel'), __('Bạn có chắc muốn xóa', $subcategory['Subcategory'])
            ) . '&nbsp';
            ?>
        </td>
    </tr>
<?php endforeach; ?>
<?php echo $this->grid->end_table($subcategories, null, $option);
?>


