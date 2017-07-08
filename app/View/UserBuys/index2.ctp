<h3>Tổng lợi nhuận của tháng <?php echo date('m-Y')?> là: <?php echo number_format($sum, 0, ',', '.');?> đ</h3> 
<style>
    table {
        border-collapse: collapse;
    }

    table, td, th {
        border: 1px solid black;
    }
</style>
<table style="width: 100%; " >
    <tr>
        <td>Số sản phẩm bán được: </td>
        <td> Cấp bậc: </td>
        <td>Điểm tích thưởng: </td>
    </tr>
    <tr>
        <td>Doanh thu bán hàng:</td>
        <td>Hệ số tính: </td>
        <td>Tiền thưởng:</th>
    </tr>
    <tr>
        <td>Lợi nhuận: </td>
        <td>: </td>
        <td></td>
    </tr>
</table>


<?php
                  $option = array();
$option['title'] = 'Users';
    $option['col'] = array(
        0 => array('key_tab' => 'id', 'title_tab' => 'STT', 'option_tab' => 'sort'),
        1 => array('key_tab' => 'customer', 'title_tab' => 'Tên khách hàng', 'option_tab' => ''),
        2 => array('key_tab' => 'product', 'title_tab' => 'Tên sản phẩm', 'option_tab' => 'sort'),
        3 => array('key_tab' => 'price', 'title_tab' => 'Giá bán', 'option_tab' => 'sort'),
        4 => array('key_tab' => 'price_origin', 'title_tab' => 'Giá gốc', 'option_tab' => 'sort'),
        5 => array('key_tab' => 'revenue', 'title_tab' => 'Lợi nhuận', 'option_tab' => 'sort'),
        6 => array('key_tab' => 'date', 'title_tab' => 'Ngày mua', 'option_tab' => 'sort'),
//        7 => array('key_tab' => 'option', 'title_tab' => 'option', 'option_tab' => ''),
);
echo $this->grid->create($userBuys, null, $option);
   ?>
	<?php foreach ($userBuys as $key =>$userbuy): ?>
<tr>
    <td><?php echo $key+1; ?>&nbsp;</td>
    <td><?php echo h($userbuy['Customer']['username']); ?>&nbsp;</td>
    <td><?php echo h($userbuy['Product']['name']); ?>&nbsp;</td>
    <td><?php echo number_format(h($userbuy['UserBuy']['price_sale']), 0, ',', '.');?>&nbsp đ &nbsp;</td>
    <td><?php echo number_format(h($userbuy['UserBuy']['price_origin']), 0, ',', '.'); ?>&nbsp đ &nbsp;</td>
    <td><?php echo number_format(h($userbuy['UserBuy']['revenue']), 0, ',', '.'); ?>&nbsp đ &nbsp;</td>
    <td><?php echo h($userbuy['UserBuy']['date']); ?>&nbsp;</td>
<!--    <td class="actions">
                <?php
                    echo $this->Html->link(
                        $this->Html->tag('i', '', array('class' => 'glyphicon glyphicon-edit icon-white', 'title' => 'Edit')), array('action' => 'edit', $userbuy['UserBuy']['id']), array('escape' => false, 'class' => 'btn btn-success btn-sm')
                ).'&nbsp';
                echo $this->Form->postLink(
                        $this->Html->tag('i', '', array('class' => 'glyphicon glyphicon-remove icon-white', 'title' => 'Delete')), array('action' => 'delete', $userbuy['UserBuy']['id']), array('escape' => false, 'class' => 'btn btn-danger btn-sm btn-cat-cancel'), __('Bạn có chắc muốn xóa', $userbuy['UserBuy']['id'])
                ).'&nbsp';
                ?>
    </td>-->
</tr>
<?php endforeach; ?>
                  <?php echo $this->grid->end_table($userBuys,null,$option);
                  
        ?>
