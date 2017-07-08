<!--theo form moidore-->
<div class="users index">
    <h2><?php echo __('Products'); ?></h2>
            <?php
App::uses('CakeTime', 'Utility');
$option = array();
$option['title'] = 'Products';
    $option['col'] = array(
        0 => array('key_tab' => 'id', 'title_tab' => 'ID', 'option_tab' => 'sort'),
        1 => array('key_tab' => 'name', 'title_tab' => 'name', 'option_tab' => ''),
        2 => array('key_tab' => 'title', 'title_tab' => 'title', 'option_tab' => 'sort'),
        3 => array('key_tab' => 'description', 'title_tab' => 'description', 'option_tab' => 'sort'),
        4 => array('key_tab' => 'avatar', 'title_tab' => 'avatar', 'option_tab' => 'sort'),
        5 => array('key_tab' => 'sale', 'title_tab' => 'sale', 'option_tab' => 'sort'),
        6 => array('key_tab' => 'price', 'title_tab' => 'price', 'option_tab' => 'sort'),
        7 => array('key_tab' => 'status', 'title_tab' => 'status', 'option_tab' => 'sort'),
        8 => array('key_tab' => 'category', 'title_tab' => 'avatar', 'option_tab' => 'sort'),
        9 => array('key_tab' => '', 'title_tab' => 'aption', 'option_tab' => ''),
);
echo $this->grid->create($posts, null, $option);
?>
	<?php foreach ($products as $product): ?>
    <tr>
        <td><?php echo h($product['Product']['id']); ?>&nbsp;</td>
        <td><?php echo h($product['Product']['name']); ?>&nbsp;</td>
        <td><?php echo h($product['Product']['title']); ?>&nbsp;</td>
        <td><?php echo h($product['Product']['description']); ?>&nbsp;</td>
        <td><?php echo h($product['Product']['avatar']); ?>&nbsp;</td>
        <td><?php echo h($product['Product']['sale']); ?>&nbsp;</td>
        <td><?php echo h($product['Product']['price']); ?>&nbsp;</td>
        <td><?php echo h($product['Product']['status']); ?>&nbsp;</td>
        <td>
			<?php echo $this->Html->link($product['Category']['name'], array('controller' => 'categories', 'action' => 'view', $product['Category']['id'])); ?>
        </td>
        <td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $product['Product']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $product['Product']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $product['Product']['id']), array(), __('Are you sure you want to delete # %s?', $product['Product']['id'])); ?>
        </td>
    </tr>
<?php endforeach; ?>
    <p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
    <div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
    </div>
</div>
<div class="actions">
    <h3><?php echo __('Actions'); ?></h3>
    <ul>
        <li><?php echo $this->Html->link(__('New User'), array('action' => 'add')); ?></li>
    </ul>
</div>
<div class="actions">
    <h3><?php echo __('Actions'); ?></h3>
    <ul>
        <li><?php echo $this->Html->link(__('New Product'), array('action' => 'add')); ?></li>
        <li><?php echo $this->Html->link(__('List Categories'), array('controller' => 'categories', 'action' => 'index')); ?> </li>
        <li><?php echo $this->Html->link(__('New Category'), array('controller' => 'categories', 'action' => 'add')); ?> </li>
        <li><?php echo $this->Html->link(__('List Subcategories'), array('controller' => 'subcategories', 'action' => 'index')); ?> </li>
        <li><?php echo $this->Html->link(__('New Subcategory'), array('controller' => 'subcategories', 'action' => 'add')); ?> </li>
    </ul>
</div>

