<?php

$option = array();
$option['title'] = 'Users';
    $option['col'] = array(
        0 => array('key_tab' => 'id', 'title_tab' => 'STT', 'option_tab' => 'sort'),
        1 => array('key_tab' => 'customer', 'title_tab' => 'Tên khách hàng', 'option_tab' => ''),
        2 => array('key_tab' => 'product', 'title_tab' => 'Tên sản phẩm', 'option_tab' => 'sort'),
        3 => array('key_tab' => 'price', 'title_tab' => 'Số điện thoại', 'option_tab' => 'sort'),
        4 => array('key_tab' => 'price', 'title_tab' => 'Địa chỉ', 'option_tab' => 'sort'),
        5 => array('key_tab' => 'price_origin', 'title_tab' => 'Thời gian mua', 'option_tab' => 'sort'),
        6 => array('key_tab' => 'option', 'title_tab' => 'Xóa', 'option_tab' => ''),
);
echo $this->grid->create($userBuys, null, $option);
?>
	<?php foreach ($userBuys as $key =>$userbuy): ?>
<tr>
    <td><?php echo h($userbuy['UserBuy']['id']); ?>&nbsp;</td>
    <td><?php echo h($userbuy['Customer']['username']); ?>&nbsp;</td>
    <td><?php echo h($userbuy['Product']['name']); ?>&nbsp;</td>
    <td><?php echo h($userbuy['Customer']['phone']); ?>&nbsp;</td>
    <td><?php echo h($userbuy['Customer']['address']); ?>&nbsp;</td>
    <td><?php echo h($userbuy['UserBuy']['date']); ?>&nbsp;</td>
    <td class="actions">
                <?php
//                    echo $this->Html->link(
//                        $this->Html->tag('i', '', array('class' => 'glyphicon glyphicon-edit icon-white', 'title' => 'Edit')), array('action' => 'edit', $userbuy['UserBuy']['id']), array('escape' => false, 'class' => 'btn btn-success btn-sm')
//                ).'&nbsp';
                echo $this->Form->postLink(
                        $this->Html->tag('i', '', array('class' => 'glyphicon glyphicon-remove icon-white', 'title' => 'Delete')), array('action' => 'delete_order_success', $userbuy['UserBuy']['id']), array('escape' => false, 'class' => 'btn btn-danger btn-sm btn-cat-cancel'), __('Bạn có chắc muốn xóa đơn hàng này', $userbuy['UserBuy']['id'])
                ).'&nbsp';
                ?>
    </td>
</tr>
<?php endforeach; ?>
                  <?php echo $this->grid->end_table($userBuys,null,$option);
                  
        ?>
