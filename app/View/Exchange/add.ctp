<script src="<?php echo $this->webroot ?>ckeditor/ckeditor.js"></script>
<div class="promotions form">
    <?php echo $this->Form->create('ExchangePositions'); ?>
    <fieldset>
        <legend><?php echo __('Thêm mới quy đổi'); ?></legend>
        <?php
        echo $this->Form->input('id');
        echo $this->Form->input('value1', array('label' => array('text' => 'Giá trị', 'class' => 'control-label col-xs-12 col-sm-2')));
        echo $this->Form->input('value2', array('label' => array('text' => 'Cách quy đổi', 'class' => 'control-label col-xs-12 col-sm-2')));
        echo $this->Form->input('description', array('label' => array('text' => 'Miêu tả', 'class' => 'control-label col-xs-12 col-sm-2')));
        echo $this->Form->input('created_datetime', array('label' => array('text' => 'Ngày tạo', 'class' => 'control-label col-xs-12 col-sm-2'), 'type' => 'date'));
        ?>
    </fieldset>
    <?php echo $this->Form->end(__('Thêm')); ?>
</div>