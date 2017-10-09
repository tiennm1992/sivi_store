<?php
$option = array();
$option['title'] = 'Khách hàng';
$option['col'] = array(
    0 => array('key_tab' => 'id', 'title_tab' => 'STT', 'option_tab' => 'sort'),
    1 => array('key_tab' => 'name', 'title_tab' => 'Tên khách hàng', 'option_tab' => ''),
    2 => array('key_tab' => 'name', 'title_tab' => 'Họ và tên', 'option_tab' => ''),
    3 => array('key_tab' => 'title', 'title_tab' => 'Số điện thoại', 'option_tab' => 'sort'),
    4 => array('key_tab' => 'address', 'title_tab' => 'Địa chỉ', 'option_tab' => 'sort'),
    5 => array('key_tab' => 'created_datetime', 'title_tab' => 'Ngày tham gia', 'option_tab' => 'sort'),
    6 => array('key_tab' => 'address', 'title_tab' => 'Quản lý', 'option_tab' => 'sort'),
);
echo $this->grid->create($customers, null, $option);
?>
<?php foreach ($customers as $key => $customer): ?>
    <tr>
        <td><?php echo $key + 1; ?>&nbsp;</td>
        <td><?php echo h($customer['Customer']['username']); ?>&nbsp;</td>
        <td><?php echo h($customer['Customer']['name']); ?>&nbsp;</td>
        <td><?php echo h($customer['Customer']['phone']); ?>&nbsp;</td>
        <td><?php echo h($customer['Customer']['address']); ?>&nbsp;</td>
        <td><?php echo h($customer['Customer']['created_datetime']); ?>&nbsp;</td>
        <td><a href="/userBuys/history_buy_item?user_id=<?php echo $customer['Customer']['id'] ?>">Lịch sử mua hàng</a></td>
    <!--        <td class="actions">
        <?php
//            echo $this->Html->link(
//                    $this->Html->tag('i', '', array('class' => 'glyphicon glyphicon-edit icon-white', 'title' => 'Edit')), array('action' => 'edit', $customer['Customer']['id']), array('escape' => false, 'class' => 'btn btn-success btn-sm')
//            ) . '&nbsp';
//            echo $this->Form->postLink(
//                    $this->Html->tag('i', '', array('class' => 'glyphicon glyphicon-remove icon-white', 'title' => 'Delete')), array('action' => 'delete', $customer['Customer']['id']), array('escape' => false, 'class' => 'btn btn-danger btn-sm btn-cat-cancel'), __('Bạn có chắc muốn xóa', $customer['Customer'])
//            ) . '&nbsp';
        ?>
       </td>-->
    </tr>
<?php endforeach; ?>
<?php echo $this->grid->end_table($customers, null, $option);
?>
