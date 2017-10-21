    <?php
                  $option = array();
$option['title'] = 'Products';
    $option['col'] = array(
        0 => array('key_tab' => 'STT', 'title_tab' => 'STT', 'option_tab' => 'sort'),
        1 => array('key_tab' => 'title', 'title_tab' => 'Giá trị', 'option_tab' => ''),
        2 => array('key_tab' => 'sort', 'title_tab' => 'Giá trị đối chiếu', 'option_tab' => 'sort'),
        4 => array('key_tab' => 'description', 'title_tab' => 'Mô tả', 'option_tab' => 'sort'),
        5 => array('key_tab' => 'description', 'title_tab' => 'Ngày tạo', 'option_tab' => 'sort'),
        6=> array('key_tab' => '', 'title_tab' => 'aption', 'option_tab' => ''),
);
echo $this->grid->create($ComparePosition, null, $option);
?>
	<?php  foreach ($ComparePosition as $key =>$promotion): ?>
<tr>
    <td><?php echo $key+1; ?>&nbsp;</td>
    <td><?php echo h($promotion['ComparePosition']['value1']); ?>&nbsp;</td>
    <td><?php echo h($promotion['ComparePosition']['value2']); ?>&nbsp;</td>
    <td><?php echo h($promotion['ComparePosition']['description']); ?>&nbsp;</td>
    <td><?php echo h($promotion['ComparePosition']['created_datetime']); ?>&nbsp;</td>
    <td class="actions">
                <?php
                    echo $this->Html->link(
                        $this->Html->tag('i', '', array('class' => 'glyphicon glyphicon-edit icon-white', 'title' => 'Edit')), array('action' => 'edit', $promotion['ComparePosition']['id']), array('escape' => false, 'class' => 'btn btn-success btn-sm')
                ).'&nbsp';
                echo $this->Form->postLink(
                        $this->Html->tag('i', '', array('class' => 'glyphicon glyphicon-remove icon-white', 'title' => 'Delete')), array('action' => 'delete', $promotion['ComparePosition']['id']), array('escape' => false, 'class' => 'btn btn-danger btn-sm btn-cat-cancel'), __('Bạn có chắc muốn xóa', $promotion['ComparePosition']['id'])
                ).'&nbsp';
                ?>
    </td>
</tr>
<?php endforeach; ?>
                  <?php echo $this->grid->end_table($ComparePosition,null,$option);
       ?>

<div class="actions">
   <a href="/compare/add" type="button" class="btn btn-primary" >Thêm đối chiếu</a>
</div>  

