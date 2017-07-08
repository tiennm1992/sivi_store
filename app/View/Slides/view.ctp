<div class="slides view">
<h2><?php echo __('Slide'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($slide['Slide']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Image'); ?></dt>
		<dd>
			<?php echo h($slide['Slide']['image']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Link'); ?></dt>
		<dd>
			<?php echo h($slide['Slide']['link']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Description'); ?></dt>
		<dd>
			<?php echo h($slide['Slide']['description']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Slide'), array('action' => 'edit', $slide['Slide']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Slide'), array('action' => 'delete', $slide['Slide']['id']), array(), __('Are you sure you want to delete # %s?', $slide['Slide']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Slides'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Slide'), array('action' => 'add')); ?> </li>
	</ul>
</div>
