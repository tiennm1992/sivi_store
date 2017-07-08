<div class="subcategories form">
<?php echo $this->Form->create('Subcategory', array('type' => 'file')); ?>
	<fieldset>
		<legend><?php echo __('Thêm danh mục con'); ?></legend>
	<?php
		echo $this->Form->input('category_id', array('label' => array('text' => 'Tên danh mục', 'class' => 'control-label col-xs-12 col-sm-2')));
		echo $this->Form->input('name', array('label' => array('text' => 'Tên danh mục con', 'class' => 'control-label col-xs-12 col-sm-2')));
		echo $this->Form->input('sort', array('label' => array('text' => 'sắp xếp', 'class' => 'control-label col-xs-12 col-sm-2'),'value' => '1'));
                echo $this->Form->input('avatar', array('type' => 'file','label' => array('text' => 'Ảnh đại diện', 'class' => 'control-label col-xs-12 col-sm-2')));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Lưu')); ?>
</div>
