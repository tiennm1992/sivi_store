<script src="<?php echo $this->webroot ?>ckeditor/ckeditor.js"></script>
<div class="products form">
    <?php echo $this->Form->create('Product', array('type' => 'file')); ?>
    <fieldset>
        <legend><?php echo __('Thêm sản phẩm'); ?></legend>
        <?php
        echo $this->Form->input('name', array('label' => array('text' => 'Tên sản phẩm', 'class' => 'control-label col-xs-12 col-sm-2')));
        echo $this->Form->input('title', array('label' => array('text' => 'Tiêu đề', 'class' => 'control-label col-xs-12 col-sm-2'), 'type' => 'text'));
        echo $this->Form->input('description', array('label' => array('text' => 'Mô tả', 'class' => 'control-label col-xs-12 col-sm-2'), 'type' => 'textarea', 'class' => 'ckeditor'));
        echo $this->Form->input('detail', array('label' => array('text' => 'Chi tiết sản phẩm', 'class' => 'control-label col-xs-12 col-sm-2'), 'type' => 'textarea', 'class' => 'ckeditor'));
        echo $this->Form->input('avatar', array('label' => array('text' => 'Ảnh đại diện', 'class' => 'control-label col-xs-12 col-sm-2'), 'type' => 'file'));
        
        echo $this->Form->input('price', array('label' => array('text' => 'Giá bán lẻ', 'class' => 'control-label col-xs-12 col-sm-2'),));
        echo $this->Form->input('price_origin', array('label' => array('text' => 'Giá nhập', 'class' => 'control-label col-xs-12 col-sm-2'),));
        echo $this->Form->input('partner_price', array('label' => array('text' => 'Giá C1', 'class' => 'control-label col-xs-12 col-sm-2'),));
        echo $this->Form->input('employee_price', array('label' => array('text' => 'Giá C2', 'class' => 'control-label col-xs-12 col-sm-2'),));
        echo $this->Form->input('sale', array('label' => array('text' => 'Giảm sale', 'class' => 'control-label col-xs-12 col-sm-2'),));
        echo $this->Form->input('date_create', array('label' => array('text' => 'Ngày tạo', 'class' => 'control-label col-xs-12 col-sm-2'), 'type' => 'date'));
        ?>
        <div class="form-group">
            <label for="ProductImg" class="control-label col-xs-12 col-sm-2">Danh sách ảnh sản phẩm</label>
            <div class="controls col-xs-12 col-sm-8">
                <input type="file" name="data[Product][img][]"  class="form-control" multiple="multiple" id="ProductImg"/>
            </div>
            <div class="clearfix"></div>
        </div>
        <?php
//		echo $this->Form->input('img.', array('type' => 'file'));
        echo $this->Form->input('category_id', array('label' => array('text' => 'Thể loại', 'class' => 'control-label col-xs-12 col-sm-2'),));
//        echo $this->Form->input('subcategory_id', array('label' => array('text' => 'Thể loại con', 'class' => 'control-label col-xs-12 col-sm-2'),));
        ?>
        <div class="form-group">
            <label for="ProductSubcategoryId" class="control-label col-xs-12 col-sm-2">Thể loại con</label>
            <div class="controls col-xs-12 col-sm-8">
                <select name="data[Product][subcategory_id]" class="form-control" id="ProductSubcategoryId">
                </select></div><div class="clearfix"></div>
        </div>
        <?php
        echo $this->Form->input('status', array('label' => array('text' => 'Trạng thái sản phẩm', 'class' => 'control-label col-xs-12 col-sm-2'),));
        echo $this->Form->input('storage', array('label' => array('text' => 'Kho chứa sản phẩm', 'class' => 'control-label col-xs-12 col-sm-2'),));
        echo $this->Form->input('product_from', array('label' => array('text' => 'Nơi sản xuất', 'class' => 'control-label col-xs-12 col-sm-2'),));
        echo $this->Form->input('sort', array('label' => array('text' => 'sắp xếp', 'class' => 'control-label col-xs-12 col-sm-2'), 'type' => 'number', 'value' => 1));
        echo $this->Form->input('user_view', array('label' => array('text' => 'Sô người view', 'class' => 'control-label col-xs-12 col-sm-2'), 'type' => 'number', 'value' => 0));
        echo $this->Form->input('user_like', array('label' => array('text' => 'Số người like', 'class' => 'control-label col-xs-12 col-sm-2'), 'type' => 'number', 'value' => 0));
        echo $this->Form->input('user_share', array('label' => array('text' => 'Số người chia sẻ', 'class' => 'control-label col-xs-12 col-sm-2'), 'type' => 'number', 'value' => 0));
        ?>
    </fieldset>

    <?php echo $this->Form->end(__('Lưu')); ?>
</div>
<script>
    $(document).ready(function () {
//        var category_id = $("select#ProductCategoryId").val($("#ProductCategoryId option:first").val());
        var category_id = $('select#ProductCategoryId option:first').val();
        $.ajax({
            type: "POST",
            url: "/subcategories/ajaxsub",
            data: {category: category_id},
            cache: false,
            success: function (data) {
                $("select#ProductSubcategoryId").html(data);
            }
        });
        $('select#ProductCategoryId').change(function () {
            search();
        });
    });

    function search() {
        var query_value = $('select#ProductCategoryId').val();
        if (query_value !== '') {
            $.ajax({
                type: "POST",
                url: "/subcategories/ajaxsub",
                data: {category: query_value},
                cache: false,
                success: function (data) {
                    if (data != '') {
                        $("select#ProductSubcategoryId").html(data);
                    } else {
                        $('#ProductSubcategoryId').html('<option value="0"></option>');
                    }
                }
            });
        }
        return false;
    }
</script>
