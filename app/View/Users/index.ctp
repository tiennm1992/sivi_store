  <?php
                  $option = array();
$option['title'] = 'Users';
    $option['col'] = array(
        0 => array('key_tab' => 'id', 'title_tab' => 'STT', 'option_tab' => 'sort'),
        1 => array('key_tab' => 'username', 'title_tab' => 'username', 'option_tab' => ''),
        2 => array('key_tab' => 'name', 'title_tab' => 'name', 'option_tab' => 'sort'),
        3 => array('key_tab' => 'role', 'title_tab' => 'role', 'option_tab' => 'sort'),
        4 => array('key_tab' => 'phone', 'title_tab' => 'phone', 'option_tab' => 'sort'),
        5 => array('key_tab' => 'address', 'title_tab' => 'code', 'option_tab' => 'sort'),
        6 => array('key_tab' => 'email', 'title_tab' => 'email', 'option_tab' => 'sort'),
        7 => array('key_tab' => 'option', 'title_tab' => 'option', 'option_tab' => ''),
);
echo $this->grid->create($users, null, $option);
?>
	<?php foreach ($users as $key =>$user): ?>
<tr>
    <td><?php echo $key+1; ?>&nbsp;</td>
    <td><?php echo h($user['User']['username']); ?>&nbsp;</td>
    <td><?php echo h($user['User']['name']); ?>&nbsp;</td>
    <td><?php echo h($user['User']['role']); ?>&nbsp;</td>
    <td><?php echo h($user['User']['phone']); ?>&nbsp;</td>
    <td><?php echo h($user['User']['code']); ?>&nbsp;</td>
    <td><?php echo h($user['User']['email']); ?>&nbsp;</td>
    <td class="actions">
                <?php
                    echo $this->Html->link(
                        $this->Html->tag('i', '', array('class' => 'glyphicon glyphicon-edit icon-white', 'title' => 'Edit')), array('action' => 'edit', $user['User']['id']), array('escape' => false, 'class' => 'btn btn-success btn-sm')
                ).'&nbsp';
                echo $this->Form->postLink(
                        $this->Html->tag('i', '', array('class' => 'glyphicon glyphicon-remove icon-white', 'title' => 'Delete')), array('action' => 'delete', $user['User']['id']), array('escape' => false, 'class' => 'btn btn-danger btn-sm btn-cat-cancel'), __('Bạn có chắc muốn xóa', $user['User']['id'])
                ).'&nbsp';
                ?>
    </td>
</tr>
<?php endforeach; ?>
                  <?php echo $this->grid->end_table($users,null,$option);
                  
        ?>