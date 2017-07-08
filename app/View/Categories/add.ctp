<div class="categories form">
<?php echo $this->Form->create('Category', array('type' => 'file')); ?>
	<fieldset>
		<legend><?php echo __('Thêm danh mục'); ?></legend>
	<?php
		echo $this->Form->input('name', array('label' => array('text' => 'Tên danh mục', 'class' => 'control-label col-xs-12 col-sm-2')));
                echo $this->Form->input('avatar', array('label' => array('text' => 'Ảnh đại diện', 'class' => 'control-label col-xs-12 col-sm-2'),'type' => 'file'));
		echo $this->Form->input('sort', array('label' => array('text' =>'sắp xếp', 'class' => 'control-label col-xs-12 col-sm-2'),'value' => '1'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Lưu')); ?>
</div>
