<h3>Tổng lợi nhuận của tháng <?php echo date('m-Y') ?></h3> 
<style>
    table {
        border-collapse: collapse;
        margin-bottom: 10px;
    }

    table, td, th {
        border: 1px solid black;
        padding: 5px;
    }

</style>
<table style="width: 100%; " >
    <tr>
        <td>Số sản phẩm bán được:<?php echo $total_product; ?> </td>
        <td> Cấp bậc: <?php echo $level; ?> </td>
        <td>Điểm tích thưởng: <?php echo $point; ?> </td>
    </tr>
    <tr>
        <td>Doanh thu bán hàng: <?php echo number_format(h($total_price), 0, ',', '.'); ?> </td>
        <td>Hệ số tính: <?php echo ''; ?> </td>
        <td>Tiền thưởng:<?php echo number_format(h($bonus), 0, ',', '.');  ?>  đ</th>
    </tr>
    <tr>
        <td>Lợi nhuận: <?php echo number_format($sum, 0, ',', '.'); ?> </td>
        <td> </td>
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
    4 => array('key_tab' => 'partner_price', 'title_tab' => 'Giá cấp 1', 'option_tab' => 'sort'),
    5 => array('key_tab' => 'employee_price', 'title_tab' => 'Giá cấp 2', 'option_tab' => 'sort'),
//    4 => array('key_tab' => 'price_origin', 'title_tab' => 'Giá gốc', 'option_tab' => 'sort'),
    6 => array('key_tab' => 'revenue', 'title_tab' => 'Lợi nhuận', 'option_tab' => 'sort'),
    7 => array('key_tab' => 'date', 'title_tab' => 'Ngày mua', 'option_tab' => 'sort'),
    8 => array('key_tab' => 'number_product', 'title_tab' => 'Số lượng mua', 'option_tab' => 'sort'),
    9 => array('key_tab' => 'status', 'title_tab' => 'Đơn hàng', 'option_tab' => 'sort'),
);
echo $this->grid->create($userBuys, null, $option);
?>
<?php foreach ($userBuys as $key => $userbuy): ?>
    <tr>
        <td><?php echo $key + 1; ?>&nbsp;</td>
        <td><?php echo h($userbuy['Customer']['username']); ?>&nbsp;</td>
        <td><?php echo h($userbuy['Product']['name']); ?>&nbsp;</td>
        <td><?php echo number_format(h($userbuy['Buy']['price_sale']), 0, ',', '.'); ?>&nbsp đ &nbsp;</td>
        <!--<td><?php // echo number_format(h($userbuy['Buy']['price_origin']), 0, ',', '.');  ?>&nbsp đ &nbsp;</td>-->
        <td><?php echo number_format(h($userbuy['Buy']['partner_price']), 0, ',', '.'); ?>&nbsp đ &nbsp;</td>
        <td><?php echo number_format(h($userbuy['Buy']['employee_price']), 0, ',', '.'); ?>&nbsp đ &nbsp;</td>
        <td><?php echo number_format(h($userbuy['Buy']['revenue']), 0, ',', '.'); ?>&nbsp đ &nbsp;</td>
        <td><?php echo h($userbuy['Buy']['date']); ?>&nbsp;</td>
        <td><?php echo h($userbuy['Buy']['number_product']); ?>&nbsp;</td>
        <?php $status = array('Đơn hàng mới', 'Đang chờ', 'Thành công') ?>
        <td><?php echo $status[$userbuy['Buy']['status']]; ?>&nbsp;</td>
    <!--    <td class="actions">
        <?php
        echo $this->Html->link(
                $this->Html->tag('i', '', array('class' => 'glyphicon glyphicon-edit icon-white', 'title' => 'Edit')), array('action' => 'edit', $userbuy['Buy']['id']), array('escape' => false, 'class' => 'btn btn-success btn-sm')
        ) . '&nbsp';
        echo $this->Form->postLink(
                $this->Html->tag('i', '', array('class' => 'glyphicon glyphicon-remove icon-white', 'title' => 'Delete')), array('action' => 'delete', $userbuy['Buy']['id']), array('escape' => false, 'class' => 'btn btn-danger btn-sm btn-cat-cancel'), __('Bạn có chắc muốn xóa', $userbuy['Buy']['id'])
        ) . '&nbsp';
        ?>
        </td>-->
    </tr>
<?php endforeach; ?>
<?php echo $this->grid->end_table($userBuys, null, $option);
?>
