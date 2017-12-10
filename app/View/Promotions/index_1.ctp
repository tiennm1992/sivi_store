    <?php
                  $option = array();
$option['title'] = 'Products';
    $option['col'] = array(
        0 => array('key_tab' => 'STT', 'title_tab' => 'STT', 'option_tab' => 'sort'),
        1 => array('key_tab' => 'title', 'title_tab' => 'Tiêu đề', 'option_tab' => ''),
        2 => array('key_tab' => 'sort', 'title_tab' => 'Sắp xếp', 'option_tab' => 'sort'),
        4 => array('key_tab' => 'description', 'title_tab' => 'Mô tả', 'option_tab' => 'sort'),
        5 => array('key_tab' => '', 'title_tab' => 'aption', 'option_tab' => ''),
);
echo $this->grid->create($promotions, null, $option);
?>
	<?php foreach ($promotions as $key =>$promotion): ?>
<tr>
    <td><?php echo $key+1; ?>&nbsp;</td>
    <td><?php echo h($promotion['Promotion']['title']); ?>&nbsp;</td>
    <td><?php echo h($promotion['Promotion']['sort']); ?>&nbsp;</td>
    <td><?php echo h($promotion['Promotion']['description']); ?>&nbsp;</td>
    <td class="actions">
                <?php
                    echo $this->Html->link(
                        $this->Html->tag('i', '', array('class' => 'glyphicon glyphicon-edit icon-white', 'title' => 'Edit')), array('action' => 'edit', $promotion['Promotion']['id']), array('escape' => false, 'class' => 'btn btn-success btn-sm')
                ).'&nbsp';
                echo $this->Form->postLink(
                        $this->Html->tag('i', '', array('class' => 'glyphicon glyphicon-remove icon-white', 'title' => 'Delete')), array('action' => 'delete', $promotion['Promotion']['id']), array('escape' => false, 'class' => 'btn btn-danger btn-sm btn-cat-cancel'), __('Bạn có chắc muốn xóa', $promotion['Promotion']['id'])
                ).'&nbsp';
                ?>
    </td>
</tr>
<?php endforeach; ?>
                  <?php echo $this->grid->end_table($promotions,null,$option);
                  
        ?>

<div class="actions">
    <h3><?php echo __('Actions'); ?></h3>
    <ul>
        <li><?php echo $this->Html->link(__('Thêm khuyến mại mới'), array('action' => 'add')); ?></li>
    </ul>
</div>  

