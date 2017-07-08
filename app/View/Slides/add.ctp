<div class="slides form">
<?php echo $this->Form->create('Slide', array('type' => 'file')); ?>
    <fieldset>
        <legend><?php echo __('Thêm slide mới'); ?></legend>
	<?php
                echo $this->Form->input('image', array('type' => 'file','label' => array('text' => 'Ảnh đại diện', 'class' => 'control-label col-xs-12 col-sm-2')));
		echo $this->Form->input('link', array('label' => array('text' => 'Link web sản phẩm', 'class' => 'control-label col-xs-12 col-sm-2')));
		echo $this->Form->input('description', array('label' => array('text' => 'Mô tả slide', 'class' => 'control-label col-xs-12 col-sm-2')));
		echo $this->Form->input('sort',array('type' => 'number','value'=>0,'label' => array('text' => 'Sắp xếp', 'class' => 'control-label col-xs-12 col-sm-2')));
	?>
    </fieldset>
<?php echo $this->Form->end(__('Lưu')); ?>
</div>
