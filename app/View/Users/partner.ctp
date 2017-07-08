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
$option['title'] = 'Users';
$option['col'] = array(
    0 => array('key_tab' => 'id', 'title_tab' => 'STT', 'option_tab' => 'sort'),
    1 => array('key_tab' => 'username', 'title_tab' => 'username', 'option_tab' => ''),
    2 => array('key_tab' => 'name', 'title_tab' => 'name', 'option_tab' => 'sort'),
    4 => array('key_tab' => 'phone', 'title_tab' => 'phone', 'option_tab' => 'sort'),
    5 => array('key_tab' => 'address', 'title_tab' => 'address', 'option_tab' => 'sort'),
    6 => array('key_tab' => 'code', 'title_tab' => 'code', 'option_tab' => 'sort'),
    7 => array('key_tab' => 'option', 'title_tab' => 'Quản lý', 'option_tab' => ''),
    8 => array('key_tab' => 'option', 'title_tab' => 'option', 'option_tab' => ''),
);
echo $this->grid->create($users, null, $option);
?>
<?php foreach ($users as $key => $user): ?>
    <tr>
        <td><?php echo $key + 1; ?>&nbsp;</td>
        <td><?php echo h($user['User']['username']); ?>&nbsp;</td>
        <td><?php echo h($user['User']['name']); ?>&nbsp;</td>
        <td><?php echo h($user['User']['phone']); ?>&nbsp;</td>
        <td><?php echo h($user['User']['address']); ?>&nbsp;</td>
        <td><?php echo h($user['User']['code']); ?>&nbsp;</td>
        <td>
            <a href="/customers/index2/<?php echo h($user['User']['code']); ?>">User</a>&nbsp;
            <a href="/userBuys/index2/<?php echo h($user['User']['code']); ?>">Doanh thu</a>
        </td>
        <td class="actions">
            <?php
            echo $this->Html->link(
                    $this->Html->tag('i', '', array('class' => 'glyphicon glyphicon-edit icon-white', 'title' => 'Edit')), array('action' => 'edit', $user['User']['id'],'partner'), array('escape' => false, 'class' => 'btn btn-success btn-sm')
            ) . '&nbsp';
            echo $this->Form->postLink(
                    $this->Html->tag('i', '', array('class' => 'glyphicon glyphicon-remove icon-white', 'title' => 'Delete')), array('action' => 'delete', $user['User']['id']), array('escape' => false, 'class' => 'btn btn-danger btn-sm btn-cat-cancel'), __('Bạn có chắc muốn xóa', $user['User']['id'])
            ) . '&nbsp';
            ?>
        </td>
    </tr>
<?php endforeach; ?>
<?php echo $this->grid->end_table($users, null, $option);
?>