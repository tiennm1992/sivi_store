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
$option['title'] = 'Khách hàng';
$option['col'] = array(
    0 => array('key_tab' => 'id', 'title_tab' => 'STT', 'option_tab' => 'sort'),
    1 => array('key_tab' => 'name', 'title_tab' => 'Tên đăng nhập', 'option_tab' => ''),
    2 => array('key_tab' => 'name', 'title_tab' => 'Họ và tên', 'option_tab' => ''),
    3 => array('key_tab' => 'title', 'title_tab' => 'Số điện thoại', 'option_tab' => 'sort'),
    4 => array('key_tab' => 'address', 'title_tab' => 'Địa chỉ', 'option_tab' => 'sort'),
    5 => array('key_tab' => 'address', 'title_tab' => 'ID Sasi', 'option_tab' => 'sort'),
    6 => array('key_tab' => 'address', 'title_tab' => 'Quản lý', 'option_tab' => 'sort'),
    7 => array('key_tab' => 'created_datetime', 'title_tab' => 'Ngày tham gia', 'option_tab' => 'sort'),
    8 => array('key_tab' => '', 'title_tab' => 'Sửa tài khoản', 'option_tab' => ''),
    9 => array('key_tab' => '', 'title_tab' => 'Xóa tài khoản', 'option_tab' => ''),
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
        <td><?php echo h($customer['Customer']['employee_code']); ?>&nbsp;</td>
        <td><a>Lịch sử mua hàng</a></td>
        <td><?php echo h($customer['Customer']['created_datetime']); ?>&nbsp;</td>
        
        <td class="actions">
            <?php
            echo $this->Html->link(
                    $this->Html->tag('i', '', array('class' => 'glyphicon glyphicon-edit icon-white', 'title' => 'Edit')), array('action' => 'edit', $customer['Customer']['id']), array('escape' => false, 'class' => 'btn btn-success btn-sm')
            ) . '&nbsp';
//            echo $this->Form->postLink(
//                    $this->Html->tag('i', '', array('class' => 'glyphicon glyphicon-remove icon-white', 'title' => 'Delete')), array('action' => 'delete', $customer['Customer']['id']), array('escape' => false, 'class' => 'btn btn-danger btn-sm btn-cat-cancel'), __('Bạn có chắc muốn xóa', $customer['Customer'])
//            ) . '&nbsp';
            ?>
        </td>
        <td class="actions">
            <?php
            echo $this->Form->postLink(
                    $this->Html->tag('i', '', array('class' => 'glyphicon glyphicon-remove icon-white', 'title' => 'Delete')), array('action' => 'delete', $customer['Customer']['id']), array('escape' => false, 'class' => 'btn btn-danger btn-sm btn-cat-cancel'), __('Bạn có chắc muốn xóa', $customer['Customer'])
            ) . '&nbsp';
            ?>
        </td>
    </tr>
<?php endforeach; ?>
<?php echo $this->grid->end_table($customers, null, $option);
?>
