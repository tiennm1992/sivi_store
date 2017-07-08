<script src="<?php echo $this->webroot ?>ckeditor/ckeditor.js"></script>
<div class="promotions form">
    <?php echo $this->Form->create('Promotion'); ?>
    <fieldset>
        <legend><?php echo __('Thêm khuyến mại mới'); ?></legend>
        <?php
        echo $this->Form->input('title', array('label' => array('text' => 'Tiêu đề', 'class' => 'control-label col-xs-12 col-sm-2')));
        echo $this->Form->input('sort', array('value' => 1, 'label' => array('text' => 'sắp xếp', 'class' => 'control-label col-xs-12 col-sm-2')));
        echo $this->Form->input('description', array('type' => 'text', 'label' => array('text' => 'Mô tả khuyến mại', 'class' => 'control-label col-xs-12 col-sm-2')));
        echo $this->Form->input('content', array('type' => 'textarea', 'class' => 'ckeditor', 'label' => array('text' => 'Nội dung khuyến mại', 'class' => 'control-label col-xs-12 col-sm-2')));
        echo $this->Form->input('date', array('label' => array('text' => 'Ngày tạo', 'class' => 'control-label col-xs-12 col-sm-2')));
        ?>
    </fieldset>
    <?php echo $this->Form->end(__('Thêm')); ?>
</div>
