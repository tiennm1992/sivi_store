<div class="userBuys view">
<h2><?php echo __('User Buy'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($userBuy['UserBuy']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Customer Id'); ?></dt>
		<dd>
			<?php echo h($userBuy['UserBuy']['customer_id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Product Id'); ?></dt>
		<dd>
			<?php echo h($userBuy['UserBuy']['product_id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Price Origin'); ?></dt>
		<dd>
			<?php echo h($userBuy['UserBuy']['price_origin']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Price Sale'); ?></dt>
		<dd>
			<?php echo h($userBuy['UserBuy']['price_sale']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Revenue'); ?></dt>
		<dd>
			<?php echo h($userBuy['UserBuy']['revenue']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Date'); ?></dt>
		<dd>
			<?php echo h($userBuy['UserBuy']['date']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit User Buy'), array('action' => 'edit', $userBuy['UserBuy']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete User Buy'), array('action' => 'delete', $userBuy['UserBuy']['id']), array(), __('Are you sure you want to delete # %s?', $userBuy['UserBuy']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List User Buys'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User Buy'), array('action' => 'add')); ?> </li>
	</ul>
</div>
