<div class="users form">
    <?php echo $this->Form->create('User'); ?>
    <fieldset>
        <legend><?php echo __('Chỉnh sửa thông tin tài khoản'); ?></legend>
        <?php
        echo $this->Form->input('id');
        echo $this->Form->input('username', array('label' => array('text' => 'Tên đăng nhập', 'class' => 'control-label col-xs-12 col-sm-2')));
        echo $this->Form->input('password', array('label' => array('text' => 'Mật khẩu', 'class' => 'control-label col-xs-12 col-sm-2')));
        echo $this->Form->input('name', array('label' => array('text' => 'Tên người dùng', 'class' => 'control-label col-xs-12 col-sm-2')));
//        echo $this->Form->input('role', array('label' => array('text' => 'Tên đăng nhập', 'class' => 'control-label col-xs-12 col-sm-2')));
        echo $this->Form->input('code', array('label' => array('text' => 'Mã khách hàng', 'class' => 'control-label col-xs-12 col-sm-2'), 'readonly' => 'readonly'));
        echo $this->Form->input('phone', array('label' => array('text' => 'số điện thoại', 'class' => 'control-label col-xs-12 col-sm-2')));
        echo $this->Form->input('address', array('label' => array('text' => 'Địa chỉ', 'class' => 'control-label col-xs-12 col-sm-2')));
        ?>
    </fieldset>
    <?php echo $this->Form->end(__('Lưu thay đổi')); ?>
</div>

