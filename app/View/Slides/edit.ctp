<div class="slides form">
<?php echo $this->Form->create('Slide'); ?>
	<fieldset>
		<legend><?php echo __('Edit Slide'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('image', array('type' => 'file','label' => array('text' => 'Ảnh đại diện', 'class' => 'control-label col-xs-12 col-sm-2')));
		echo $this->Form->input('link', array('label' => array('text' => 'Link web sản phẩm', 'class' => 'control-label col-xs-12 col-sm-2')));
		echo $this->Form->input('description', array('label' => array('text' => 'Mô tả slide', 'class' => 'control-label col-xs-12 col-sm-2')));
		echo $this->Form->input('sort',array('type' => 'number','value'=>0,'label' => array('text' => 'Sắp xếp', 'class' => 'control-label col-xs-12 col-sm-2')));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Lưu thay đổi')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Slide.id')), array(), __('Are you sure you want to delete # %s?', $this->Form->value('Slide.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Slides'), array('action' => 'index')); ?></li>
	</ul>
</div>
