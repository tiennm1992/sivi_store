     <?php
                  $option = array();
$option['title'] = 'Products';
    $option['col'] = array(
        0 => array('key_tab' => 'id', 'title_tab' => 'ID', 'option_tab' => 'sort'),
        1 => array('key_tab' => 'image', 'title_tab' => 'Ảnh slide', 'option_tab' => ''),
        2 => array('key_tab' => 'link', 'title_tab' => 'link sản phẩm', 'option_tab' => 'sort'),
        4 => array('key_tab' => 'description', 'title_tab' => 'Mô tả', 'option_tab' => 'sort'),
        5 => array('key_tab' => '', 'title_tab' => 'Chỉnh sửa', 'option_tab' => ''),
);
echo $this->grid->create($slides, null, $option);
?>
	<?php foreach ($slides as $key =>$slide): ?>
    <tr>
        <td><?php echo $key+1; ?>&nbsp;</td>
        <td><img src="<?php echo 'http://'. $_SERVER['HTTP_HOST'] .'/'.h($slide['Slide']['image']); ?>" style="width: 120px;height: 80px;"></td>
        <td><?php echo h($slide['Slide']['link']); ?>&nbsp;</td>
        <td><?php echo h($slide['Slide']['description']); ?>&nbsp;</td>
        <td class="actions">
                <?php
                    echo $this->Html->link(
                        $this->Html->tag('i', '', array('class' => 'glyphicon glyphicon-edit icon-white', 'title' => 'Edit')), array('action' => 'edit', $slide['Slide']['id']), array('escape' => false, 'class' => 'btn btn-success btn-sm')
                ).'&nbsp';
                echo $this->Form->postLink(
                        $this->Html->tag('i', '', array('class' => 'glyphicon glyphicon-remove icon-white', 'title' => 'Delete')), array('action' => 'delete', $slide['Slide']['id']), array('escape' => false, 'class' => 'btn btn-danger btn-sm btn-cat-cancel'), __('Bạn có chắc muốn xóa', $slide['Slide']['id'])
                ).'&nbsp';
                ?>
        </td>
    </tr>
<?php endforeach; ?>
                  <?php echo $this->grid->end_table($slides,null,$option);
                  
        ?>
