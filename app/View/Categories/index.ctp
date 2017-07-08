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
    1 => array('key_tab' => 'name', 'title_tab' => 'name', 'option_tab' => ''),
    2 => array('key_tab' => 'title', 'title_tab' => 'avatar', 'option_tab' => 'sort'),
    3 => array('key_tab' => 'description', 'title_tab' => 'sort', 'option_tab' => 'sort'),
    4 => array('key_tab' => '', 'title_tab' => 'option', 'option_tab' => ''),
);
echo $this->grid->create($categories, null, $option);
?>
<?php foreach ($categories as $key => $category): ?>
    <tr>
        <td><?php echo $key + 1; ?>&nbsp;</td>
        <td><a href="/subcategories/index?category_id=<?php echo $category['Category']['id'] ?>"><?php echo h($category['Category']['name']); ?>&nbsp;</a></td>
        <td><img src="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/' . h($category['Category']['avatar']); ?>" style="width: 120px;height: 80px;"></td>
        <td><?php echo h($category['Category']['sort']); ?>&nbsp;</td>
        <td class="actions">
            <?php
            echo $this->Html->link(
                    $this->Html->tag('i', '', array('class' => 'glyphicon glyphicon-edit icon-white', 'title' => 'Edit')), array('action' => 'edit', $category['Category']['id']), array('escape' => false, 'class' => 'btn btn-success btn-sm')
            ) . '&nbsp';
            echo $this->Form->postLink(
                    $this->Html->tag('i', '', array('class' => 'glyphicon glyphicon-remove icon-white', 'title' => 'Delete')), array('action' => 'delete', $category['Category']['id']), array('escape' => false, 'class' => 'btn btn-danger btn-sm btn-cat-cancel'), __('Bạn có chắc muốn xóa', $category['Category']['name'])
            ) . '&nbsp';
            ?>
        </td>
    </tr>
<?php endforeach; ?>
<?php echo $this->grid->end_table($categories, null, $option);
?>

