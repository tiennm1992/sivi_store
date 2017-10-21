<div class="promotions view">
<h2><?php echo __('Promotion'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($promotion['Promotion']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Title'); ?></dt>
		<dd>
			<?php echo h($promotion['Promotion']['title']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Content'); ?></dt>
		<dd>
			<?php echo h($promotion['Promotion']['content']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Sort'); ?></dt>
		<dd>
			<?php echo h($promotion['Promotion']['sort']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Description'); ?></dt>
		<dd>
			<?php echo h($promotion['Promotion']['description']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Date'); ?></dt>
		<dd>
			<?php echo h($promotion['Promotion']['date']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Promotion'), array('action' => 'edit', $promotion['Promotion']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Promotion'), array('action' => 'delete', $promotion['Promotion']['id']), array(), __('Are you sure you want to delete # %s?', $promotion['Promotion']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Promotions'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Promotion'), array('action' => 'add')); ?> </li>
	</ul>
</div>
