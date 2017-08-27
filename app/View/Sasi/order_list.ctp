<?php
$option = array();
$option['title'] = 'Users';
$option['col'] = array(
    0 => array('key_tab' => 'id', 'title_tab' => 'STT', 'option_tab' => 'sort'),
    1 => array('key_tab' => 'customer', 'title_tab' => 'Thời gian mua', 'option_tab' => ''),
    2 => array('key_tab' => 'customer', 'title_tab' => 'Mã HH', 'option_tab' => ''),
    3 => array('key_tab' => 'customer', 'title_tab' => 'Avatar', 'option_tab' => ''),
    4 => array('key_tab' => 'customer', 'title_tab' => 'Tên sản phẩm', 'option_tab' => ''),
    5 => array('key_tab' => 'customer', 'title_tab' => 'Màu sắc', 'option_tab' => ''),
    6 => array('key_tab' => 'customer', 'title_tab' => 'SL', 'option_tab' => ''),
    7 => array('key_tab' => 'customer', 'title_tab' => 'Giá bán lẻ', 'option_tab' => ''),
    8 => array('key_tab' => 'customer', 'title_tab' => 'Giá sale', 'option_tab' => ''),
    9 => array('key_tab' => 'customer', 'title_tab' => 'Vận chuyển', 'option_tab' => ''),
    10 => array('key_tab' => 'customer', 'title_tab' => 'Tài khoản KH', 'option_tab' => ''),
    11=> array('key_tab' => 'customer', 'title_tab' => 'Tên khách hàng', 'option_tab' => ''),
    12=> array('key_tab' => 'customer', 'title_tab' => 'Số điên thoại', 'option_tab' => ''),
    13 => array('key_tab' => 'customer', 'title_tab' => 'Địa chỉ', 'option_tab' => ''),
);
echo $this->grid->create($userBuys, null, $option);
?>
<?php foreach ($userBuys as $key => $userbuy): ?>
    <tr>
        <td><?php echo h($userbuy['UserBuy']['id']); ?>&nbsp;</td>
        <td><?php echo h($userbuy['UserBuy']['date']); ?>&nbsp;</td>
        <td><?php echo h($userbuy['Product']['product_code']); ?>&nbsp;</td>
         <td><img src="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/' . h($userbuy['Product']['avatar']); ?>" style="width: 120px;height: 80px;"></td>
        <td><?php echo h($userbuy['Product']['name']); ?>&nbsp;</td>
        <td><?php echo h($userbuy['Product']['color']); ?>&nbsp;</td>
        <td><?php echo h($userbuy['UserBuy']['number_product']); ?>&nbsp;</td>
        <td><?php echo h($userbuy['Product']['price']); ?>&nbsp;</td>
        <td><?php echo h($userbuy['Product']['sale']); ?>&nbsp;</td>
        <td><?php echo h($userbuy['Product']['transfer_by']); ?>&nbsp;</td>
        <td><?php echo h($userbuy['Customer']['username']); ?>&nbsp;</td>
        <td><?php echo h($userbuy['Customer']['name']); ?>&nbsp;</td>
        <td><?php echo h($userbuy['Customer']['phone']); ?>&nbsp;</td>
        <td><?php echo h($userbuy['Customer']['address']); ?>&nbsp;</td>
    </tr>
<?php endforeach; ?>
<?php echo $this->grid->end_table($userBuys, null, $option);
?>
