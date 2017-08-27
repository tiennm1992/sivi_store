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
    1 => array('key_tab' => 'username', 'title_tab' => 'Tên đăng nhập', 'option_tab' => ''),
    2 => array('key_tab' => 'name', 'title_tab' => 'Họ và tên', 'option_tab' => 'sort'),
    3 => array('key_tab' => 'code', 'title_tab' => 'ID sale', 'option_tab' => 'sort'),
    4 => array('key_tab' => 'phone', 'title_tab' => 'Số điện thoại', 'option_tab' => 'sort'),
    6 => array('key_tab' => 'address', 'title_tab' => 'Địa chỉ', 'option_tab' => 'sort'),
);
echo $this->grid->create($users, null, $option);
?>
<?php foreach ($users as $key => $user): ?>
    <tr>
        <td><?php echo $key + 1; ?>&nbsp;</td>
        <td><?php echo h($user['User']['username']); ?>&nbsp;</td>
        <td><?php echo h($user['User']['name']); ?>&nbsp;</td>
        <td><?php echo h($user['User']['code']); ?>&nbsp;</td>
        <td><?php echo h($user['User']['phone']); ?>&nbsp;</td>
        <td><?php echo h($user['User']['address']); ?>&nbsp;</td>


    </tr>
<?php endforeach; ?>
<?php echo $this->grid->end_table($users, null, $option);
?>