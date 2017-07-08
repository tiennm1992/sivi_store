<script src="<?php echo $this->webroot ?>ckeditor/ckeditor.js"></script>
<div class="products form">
<?php echo $this->Form->create('Product', array('type' => 'file')); ?>
    <fieldset>
        <legend><?php echo __('Add Product'); ?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('title',array('type' => 'text'));
		echo $this->Form->input('description',array('type' => 'textarea','class'=>'ckeditor'));
		echo $this->Form->input('avatar', array('type' => 'file'));
		echo $this->Form->input('price');
		echo $this->Form->input('sale');
		echo $this->Form->input('date_create', array('type' => 'date'));
                 ?>
<!--        <div class="form-group">
            <label for="ProductImg" class="control-label col-xs-12 col-sm-2">Img Slide</label>
            <div class="controls col-xs-12 col-sm-8">
                <input type="file" name="data[Product][img][]"  class="form-control" multiple="multiple" id="ProductImg"/>
            </div>
            <div class="clearfix"></div>
        </div>-->
                    <?php
//		echo $this->Form->input('img.', array('type' => 'file'));
		echo $this->Form->input('category_id');
		echo $this->Form->input('subcategory_id');
		echo $this->Form->input('status');
		echo $this->Form->input('storage');
		echo $this->Form->input('product_from');
		echo $this->Form->input('sort',array('type' => 'number','value'=>1));
		echo $this->Form->input('user_view',array('type' => 'number','value'=>0));
		echo $this->Form->input('user_like',array('type' => 'number','value'=>0));
		echo $this->Form->input('user_share',array('type' => 'number','value'=>0));
	?>
    </fieldset>

<?php
echo $this->Form->end(__('Submit')); ?>
</div>
<!--<div class="actions">
        <h3><?php echo __('Actions'); ?></h3>
        <ul>

                <li><?php echo $this->Html->link(__('List Products'), array('action' => 'index')); ?></li>
                <li><?php echo $this->Html->link(__('List Categories'), array('controller' => 'categories', 'action' => 'index')); ?> </li>
                <li><?php echo $this->Html->link(__('New Category'), array('controller' => 'categories', 'action' => 'add')); ?> </li>
                <li><?php echo $this->Html->link(__('List Subcategories'), array('controller' => 'subcategories', 'action' => 'index')); ?> </li>
                <li><?php echo $this->Html->link(__('New Subcategory'), array('controller' => 'subcategories', 'action' => 'add')); ?> </li>
        </ul>
</div>-->
