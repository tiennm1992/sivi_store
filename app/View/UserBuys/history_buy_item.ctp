<?php
$option = array();
$option['title'] = 'Users';
$option['col'] = array(
    0 => array('key_tab' => 'id', 'title_tab' => 'STT', 'option_tab' => 'sort'),
    1 => array('key_tab' => 'customer', 'title_tab' => 'Tên khách hàng', 'option_tab' => ''),
    2 => array('key_tab' => 'product', 'title_tab' => 'Tên sản phẩm', 'option_tab' => 'sort'),
    3 => array('key_tab' => 'price', 'title_tab' => 'Giá bán', 'option_tab' => 'sort'),
    5 => array('key_tab' => 'partner_price', 'title_tab' => 'Giá cấp 1', 'option_tab' => 'sort'),
    6 => array('key_tab' => 'employee_price', 'title_tab' => 'Giá cấp 2', 'option_tab' => 'sort'),
    7 => array('key_tab' => 'number_product', 'title_tab' => 'Số Sp', 'option_tab' => 'sort'),
    8 => array('key_tab' => 'revenue', 'title_tab' => 'Lợi nhuận', 'option_tab' => 'sort'),
    9 => array('key_tab' => 'revenue', 'title_tab' => 'Ngày mua', 'option_tab' => 'sort'),
);
echo $this->grid->create($userBuys, null, $option);
?>
<?php foreach ($userBuys as $key => $userbuy): ?>
    <tr>
        <td><?php echo $key + 1; ?>&nbsp;</td>
        <td><?php echo h($userbuy['Customer']['username']); ?>&nbsp;</td>
        <td><?php echo h($userbuy['Product']['name']); ?>&nbsp;</td>
        <td><?php echo number_format(h($userbuy['Buy']['price_sale']), 0, ',', '.'); ?>&nbsp đ &nbsp;</td>
        <td><?php echo number_format(h($userbuy['Buy']['partner_price']), 0, ',', '.'); ?>&nbsp đ &nbsp;</td>
        <td><?php echo number_format(h($userbuy['Buy']['employee_price']), 0, ',', '.'); ?>&nbsp đ &nbsp;</td>
        <td><?php echo number_format(h($userbuy['Buy']['number_product']), 0, ',', '.'); ?>&nbsp &nbsp;</td>
        <td><?php echo number_format(h($userbuy['Buy']['revenue']), 0, ',', '.'); ?>&nbsp đ &nbsp;</td>
        <td><?php echo h($userbuy['Buy']['date']); ?>&nbsp;</td>
    </tr>
<?php endforeach; ?>
<?php echo $this->grid->end_table($userBuys, null, $option);
?>
