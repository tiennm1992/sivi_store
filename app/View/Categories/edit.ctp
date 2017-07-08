<div class="categories form">
    <?php echo $this->Form->create('Category', array('type' => 'file')); ?>
    <fieldset>
        <legend><?php echo __('Chỉnh sửa danh mục'); ?></legend>
        <?php
        echo $this->Form->input('id');
        echo $this->Form->input('name', array('label' => array('text' => 'Tên danh mục', 'class' => 'control-label col-xs-12 col-sm-2')));
        ?>
        <div class="form-group">
            <label for="CategoryAvatar" class="control-label col-xs-12 col-sm-2">Ảnh đại diện</label>
            <div class="controls col-xs-12 col-sm-8">
                <input type="file" name="data[Category][avatar]"  class="form-control" id="CategoryAvatar"/>
                <img src="<?php echo '/' . $this->data['Category']['avatar'] ?>" alt="Mountain View" style="width:250px;height:250px;">
                <input type="hidden" name="data[Category][avatar_tmp]" value="<?php echo $this->data['Category']['avatar'] ?>" >
            </div>
            <div class="clearfix"></div>

        </div>
        <?php
        echo $this->Form->input('sort', array('label' => array('text' => 'sắp xếp', 'class' => 'control-label col-xs-12 col-sm-2'), 'value' => '1'));
        ?>
    </fieldset>
    <?php echo $this->Form->end(__('Lưu')); ?>
</div>
