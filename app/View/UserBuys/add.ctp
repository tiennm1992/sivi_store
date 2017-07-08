<div class="userBuys form">
<?php echo $this->Form->create('UserBuy'); ?>
	<fieldset>
		<legend><?php echo __('Add User Buy'); ?></legend>
	<?php
		echo $this->Form->input('customer_id');
		echo $this->Form->input('product_id');
		echo $this->Form->input('price_origin');
		echo $this->Form->input('price_sale');
		echo $this->Form->input('revenue');
		echo $this->Form->input('date');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List User Buys'), array('action' => 'index')); ?></li>
	</ul>
</div>
