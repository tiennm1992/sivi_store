<div class="promotions form">
    <?php echo $this->Form->create('Promotion'); ?>
    <fieldset>
        <legend><?php echo __('Chỉnh sửa thông tin khuyến mại'); ?></legend>
        <?php
        echo $this->Form->input('id');
        echo $this->Form->input('title', array('label' => array('text' => 'Tiêu đề', 'class' => 'control-label col-xs-12 col-sm-2')));
        echo $this->Form->input('content', array('label' => array('text' => 'Nội dung khuyến mại', 'class' => 'control-label col-xs-12 col-sm-2')));
        echo $this->Form->input('sort', array('label' => array('text' => 'Sắp xếp', 'class' => 'control-label col-xs-12 col-sm-2')));
        echo $this->Form->input('description', array('label' => array('text' => 'Miêu tả', 'class' => 'control-label col-xs-12 col-sm-2')));
        echo $this->Form->input('date', array('label' => array('text' => 'Ngày tạo', 'class' => 'control-label col-xs-12 col-sm-2')));
        ?>
    </fieldset>
    <?php echo $this->Form->end(__('Lưu')); ?>
</div>
<div class="actions">
    <h3><?php echo __('Chỉnh sửa'); ?></h3>
    <ul>

        <li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Promotion.id')), array(), __('Are you sure you want to delete # %s?', $this->Form->value('Promotion.id'))); ?></li>
        <li><?php echo $this->Html->link(__('List Promotions'), array('action' => 'index')); ?></li>
    </ul>
</div>
