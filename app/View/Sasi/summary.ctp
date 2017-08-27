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
