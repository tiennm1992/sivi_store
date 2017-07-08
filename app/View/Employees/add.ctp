<div class="employees form">
<?php echo $this->Form->create('Employee'); ?>
    <fieldset>
        <legend><?php echo __('Add Employee'); ?></legend>
	<?php
		echo $this->Form->input('username');
		echo $this->Form->input('password');
                echo $this->Form->input('full_name');
		echo $this->Form->input('phone');
//		echo $this->Form->input('code');?>
        <div class="form-group required">
            <label for="EmployeeCode" class="control-label col-xs-12 col-sm-2">Code</label>
            <div class="controls col-xs-12 col-sm-8">
                <input name="data[Employee][code]" class="form-control" maxlength="20" type="text" id="EmployeeCode" required="required"/>
                <br>
                <a id="button" class="btn btn-success">Sinh code tự động</a>
            </div>
            <div class="clearfix"></div>
        </div>
                    <?php
		echo $this->Form->input('address');
		
	?>
    </fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<script>
    $(document).ready(function () {
        $("#button").click(function () {
            $.ajax({
                url: "/employees/ajaxcode",
                success: function (result) {
                    document.getElementById("EmployeeCode").value = result;
                }});
        });
    });

</script>
