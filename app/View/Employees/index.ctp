  <?php
                  $option = array();
$option['title'] = 'Employees';
    $option['col'] = array(
        0 => array('key_tab' => 'id', 'title_tab' => 'STT', 'option_tab' => 'sort'),
        1 => array('key_tab' => 'username', 'title_tab' => 'username', 'option_tab' => ''),
        2 => array('key_tab' => 'full_name', 'title_tab' => 'full_name', 'option_tab' => 'sort'),
        4 => array('key_tab' => 'code', 'title_tab' => 'code', 'option_tab' => 'sort'),
        5 => array('key_tab' => 'phone', 'title_tab' => 'phone', 'option_tab' => 'sort'),
        6 => array('key_tab' => 'address', 'title_tab' => 'address', 'option_tab' => 'sort'),
        7 => array('key_tab' => '', 'title_tab' => 'aption', 'option_tab' => ''),
);
echo $this->grid->create($employees, null, $option);
?>
	<?php foreach ($employees as $key =>$employee): ?>
<tr>
    <td><?php echo $key+1; ?>&nbsp;</td>
    <td><?php echo h($employee['Employee']['username']); ?>&nbsp;</td>
    <td><?php echo h($employee['Employee']['full_name']); ?>&nbsp;</td>
    <td><?php echo h($employee['Employee']['code']); ?>&nbsp;</td>
    <td><?php echo h($employee['Employee']['phone']); ?>&nbsp;</td>
    <td><?php echo h($employee['Employee']['address']); ?>&nbsp;</td>
    <td class="actions">
                <?php
                    echo $this->Html->link(
                        $this->Html->tag('i', '', array('class' => 'glyphicon glyphicon-edit icon-white', 'title' => 'Edit')), array('action' => 'edit', $employee['Employee']['id']), array('escape' => false, 'class' => 'btn btn-success btn-sm')
                ).'&nbsp';
                echo $this->Form->postLink(
                        $this->Html->tag('i', '', array('class' => 'glyphicon glyphicon-remove icon-white', 'title' => 'Delete')), array('action' => 'delete', $employee['Employee']['id']), array('escape' => false, 'class' => 'btn btn-danger btn-sm btn-cat-cancel'), __('Bạn có chắc muốn xóa', $employee['Employee']['id'])
                ).'&nbsp';
                ?>
    </td>
</tr>
<?php endforeach; ?>
                  <?php echo $this->grid->end_table($employees,null,$option);
                  
        ?>