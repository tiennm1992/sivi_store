<div class="users form">
    <?php echo $this->Form->create('User'); ?>
    <fieldset>
        <legend><?php echo __('Thêm tài khoản mới'); ?></legend>
        <?php
        echo $this->Form->input('username', array('label' => array('text' => 'Tên đăng nhập', 'class' => 'control-label col-xs-12 col-sm-2')));
        echo $this->Form->input('password', array('label' => array('text' => 'Mật khẩu đăng nhập', 'class' => 'control-label col-xs-12 col-sm-2')));
        echo $this->Form->input('name', array('label' => array('text' => 'Tên người dùng', 'class' => 'control-label col-xs-12 col-sm-2')));
        echo $this->Form->input('phone', array('label' => array('text' => 'Số điện thoại người dùng', 'class' => 'control-label col-xs-12 col-sm-2')));
//		echo $this->Form->input('code');
        ?>
        <div class="form-group required">
            <label for="EmployeeCode" class="control-label col-xs-12 col-sm-2">Mã của tài khoản</label>
            <div class="controls col-xs-12 col-sm-8">
                <input name="data[User][code]" class="form-control" maxlength="20" type="text" id="EmployeeCode" required="required"/>
                <br>
                <a id="button" class="btn btn-success">Sinh code tự động</a>
            </div>
            <div class="clearfix"></div>
        </div>
        <?php
        echo $this->Form->input('address', array('label' => array('text' => 'Địa chỉ', 'class' => 'control-label col-xs-12 col-sm-2')));
        echo $this->Form->input('role', array('label' => array('text' => 'Chức danh tài khoản', 'class' => 'control-label col-xs-12 col-sm-2'),
            'options' => array('admin' => 'Chăm sóc khách hàng', 'employee' => 'Nhân Viên kinh doanh', 'partner' => 'Cộng tác viên', 'super' => 'Quản trị')
        ));
        ?>
    </fieldset>
    <?php echo $this->Form->end(__('Tạo tài khoản')); ?>
</div>
<script>
    $(document).ready(function () {
        $("#button").click(function () {
            $.ajax({
                url: "/users/ajaxcode",
                success: function (result) {
                    document.getElementById("EmployeeCode").value = result;
                }});
        });
    });
    $("#UserAddForm").submit(function (e) {
        var url = "/users/addajax"; // the script where you handle the form input.
        $.ajax({
            type: "POST",
            url: url,
            data: $("#UserAddForm").serialize(), // serializes the form's elements.
            success: function (data)
            {
                if (data == 'done') {
                    document.location.href = '/users/index'
                } else {
                    alert(data); //
                }

            }
        });

        e.preventDefault(); // avoid to execute the actual submit of the form.
    });

</script>