<div class="products view">
<h2><?php echo __('Product'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($product['Product']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($product['Product']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Title'); ?></dt>
		<dd>
			<?php echo h($product['Product']['title']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Description'); ?></dt>
		<dd>
			<?php echo h($product['Product']['description']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Avatar'); ?></dt>
		<dd>
			<?php echo h($product['Product']['avatar']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Price'); ?></dt>
		<dd>
			<?php echo h($product['Product']['price']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Sale'); ?></dt>
		<dd>
			<?php echo h($product['Product']['sale']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Date Create'); ?></dt>
		<dd>
			<?php echo h($product['Product']['date_create']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Img'); ?></dt>
		<dd>
			<?php echo h($product['Product']['img']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Category'); ?></dt>
		<dd>
			<?php echo $this->Html->link($product['Category']['name'], array('controller' => 'categories', 'action' => 'view', $product['Category']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Subcategory'); ?></dt>
		<dd>
			<?php echo $this->Html->link($product['Subcategory']['name'], array('controller' => 'subcategories', 'action' => 'view', $product['Subcategory']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Status'); ?></dt>
		<dd>
			<?php echo h($product['Product']['status']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Storage'); ?></dt>
		<dd>
			<?php echo h($product['Product']['storage']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Product From'); ?></dt>
		<dd>
			<?php echo h($product['Product']['product_from']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Sort'); ?></dt>
		<dd>
			<?php echo h($product['Product']['sort']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('User View'); ?></dt>
		<dd>
			<?php echo h($product['Product']['user_view']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('User Like'); ?></dt>
		<dd>
			<?php echo h($product['Product']['user_like']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('User Share'); ?></dt>
		<dd>
			<?php echo h($product['Product']['user_share']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Product'), array('action' => 'edit', $product['Product']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Product'), array('action' => 'delete', $product['Product']['id']), array(), __('Are you sure you want to delete # %s?', $product['Product']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Products'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Product'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Categories'), array('controller' => 'categories', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Category'), array('controller' => 'categories', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Subcategories'), array('controller' => 'subcategories', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Subcategory'), array('controller' => 'subcategories', 'action' => 'add')); ?> </li>
	</ul>
</div>
