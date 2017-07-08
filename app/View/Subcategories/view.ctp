<div class="subcategories view">
<h2><?php echo __('Subcategory'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($subcategory['Subcategory']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Category'); ?></dt>
		<dd>
			<?php echo $this->Html->link($subcategory['Category']['name'], array('controller' => 'categories', 'action' => 'view', $subcategory['Category']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($subcategory['Subcategory']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Sort'); ?></dt>
		<dd>
			<?php echo h($subcategory['Subcategory']['sort']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Subcategory'), array('action' => 'edit', $subcategory['Subcategory']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Subcategory'), array('action' => 'delete', $subcategory['Subcategory']['id']), array(), __('Are you sure you want to delete # %s?', $subcategory['Subcategory']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Subcategories'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Subcategory'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Categories'), array('controller' => 'categories', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Category'), array('controller' => 'categories', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Products'), array('controller' => 'products', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Product'), array('controller' => 'products', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Products'); ?></h3>
	<?php if (!empty($subcategory['Product'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Name'); ?></th>
		<th><?php echo __('Title'); ?></th>
		<th><?php echo __('Description'); ?></th>
		<th><?php echo __('Avatar'); ?></th>
		<th><?php echo __('Price'); ?></th>
		<th><?php echo __('Sale'); ?></th>
		<th><?php echo __('Date Create'); ?></th>
		<th><?php echo __('Img'); ?></th>
		<th><?php echo __('Category Id'); ?></th>
		<th><?php echo __('Subcategory Id'); ?></th>
		<th><?php echo __('Status'); ?></th>
		<th><?php echo __('Storage'); ?></th>
		<th><?php echo __('Product From'); ?></th>
		<th><?php echo __('Sort'); ?></th>
		<th><?php echo __('User View'); ?></th>
		<th><?php echo __('User Like'); ?></th>
		<th><?php echo __('User Share'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($subcategory['Product'] as $product): ?>
		<tr>
			<td><?php echo $product['id']; ?></td>
			<td><?php echo $product['name']; ?></td>
			<td><?php echo $product['title']; ?></td>
			<td><?php echo $product['description']; ?></td>
			<td><?php echo $product['avatar']; ?></td>
			<td><?php echo $product['price']; ?></td>
			<td><?php echo $product['sale']; ?></td>
			<td><?php echo $product['date_create']; ?></td>
			<td><?php echo $product['img']; ?></td>
			<td><?php echo $product['category_id']; ?></td>
			<td><?php echo $product['subcategory_id']; ?></td>
			<td><?php echo $product['status']; ?></td>
			<td><?php echo $product['storage']; ?></td>
			<td><?php echo $product['product_from']; ?></td>
			<td><?php echo $product['sort']; ?></td>
			<td><?php echo $product['user_view']; ?></td>
			<td><?php echo $product['user_like']; ?></td>
			<td><?php echo $product['user_share']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'products', 'action' => 'view', $product['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'products', 'action' => 'edit', $product['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'products', 'action' => 'delete', $product['id']), array(), __('Are you sure you want to delete # %s?', $product['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Product'), array('controller' => 'products', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
