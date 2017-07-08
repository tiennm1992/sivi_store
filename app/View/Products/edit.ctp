<script src="<?php echo $this->webroot ?>ckeditor/ckeditor.js"></script>
<div class="products form">
    <?php echo $this->Form->create('Product', array('type' => 'file')); ?>
    <fieldset>
        <legend><?php echo __('Chỉnh sửa sản phẩm'); ?></legend>
        <?php
        echo $this->Form->input('id');
        echo $this->Form->input('name', array('label' => array('text' => 'Tên sản phẩm', 'class' => 'control-label col-xs-12 col-sm-2')));
        echo $this->Form->input('title', array('label' => array('text' => 'Tiêu đề', 'class' => 'control-label col-xs-12 col-sm-2'), 'type' => 'text'));
        echo $this->Form->input('description', array('label' => array('text' => 'Mô tả', 'class' => 'control-label col-xs-12 col-sm-2'), 'type' => 'textarea', 'class' => 'ckeditor'));
        echo $this->Form->input('detail', array('label' => array('text' => 'Chi tiết sản phẩm', 'class' => 'control-label col-xs-12 col-sm-2'), 'type' => 'textarea', 'class' => 'ckeditor'));
        ?>
        <div class="form-group">
            <label for="ProductAvatar" class="control-label col-xs-12 col-sm-2">Ảnh đại diện</label>
            <div class="controls col-xs-12 col-sm-8">
                <input type="file" name="data[Product][avatar]"  class="form-control" id="ProductAvatar"/>
                <img src="<?php echo '/' . $this->data['Product']['avatar'] ?>" alt="Mountain View" style="width:250px;height:250px;">
                <input type="hidden" name="data[Product][avatar_tmp]" value="<?php echo $this->data['Product']['avatar'] ?>" >
            </div>
            <div class="clearfix"></div>

        </div>
        <?php
//		echo $this->Form->input('avatar');
        echo $this->Form->input('price', array('label' => array('text' => 'Giá bán sản phẩm', 'class' => 'control-label col-xs-12 col-sm-2'),));
        echo $this->Form->input('price_origin', array('label' => array('text' => 'Giá gốc sản phẩm', 'class' => 'control-label col-xs-12 col-sm-2'),));
        echo $this->Form->input('sale', array('label' => array('text' => 'Giảm giá', 'class' => 'control-label col-xs-12 col-sm-2'),));
        echo $this->Form->input('date_create', array('label' => array('text' => 'Ngày tạo', 'class' => 'control-label col-xs-12 col-sm-2'), 'type' => 'date'));
        ?>
        <div class="form-group">
            <label for="ProductImg" class="control-label col-xs-12 col-sm-2">Danh sách ảnh sản phẩm</label>
            <div class="controls col-xs-12 col-sm-8">
                <input type="file" name="data[Product][img][]"  class="form-control" multiple="multiple" id="ProductImg"/>
                <?php
                $slide_img = explode('|', $this->data['Product']['img']);
                $slide_img = array_filter($slide_img);
                foreach ($slide_img as $key => $value):
                    ?>
                    <div id="<?php echo $key ?>">
                        <img src="<?php echo '/' . $value ?>" alt="Mountain View" style="width:250px;height:250px;">
                        <a class="btn btn-warning" onclick="_delete(<?php echo $key ?>)">delete</a>
                        <input type="hidden" name="data[Product][img_tmp][]" value="<?php echo $value ?>">
                    </div>
                    <?php
                endforeach;
                ?>
            </div>
            <div class="clearfix"></div>
        </div>
        <?php
//		echo $this->Form->input('img');
        echo $this->Form->input('category_id', array('label' => array('text' => 'Thể loại', 'class' => 'control-label col-xs-12 col-sm-2'),));
        echo $this->Form->input('subcategory_id', array('label' => array('text' => 'Thể loại con', 'class' => 'control-label col-xs-12 col-sm-2'),));
        echo $this->Form->input('status', array('label' => array('text' => 'Trạng thái sản phẩm', 'class' => 'control-label col-xs-12 col-sm-2'),));
        echo $this->Form->input('storage', array('label' => array('text' => 'Kho chứa sản phẩm', 'class' => 'control-label col-xs-12 col-sm-2'),));
        echo $this->Form->input('product_from', array('label' => array('text' => 'Nơi sản xuất', 'class' => 'control-label col-xs-12 col-sm-2'),));
        echo $this->Form->input('sort', array('label' => array('text' => 'sắp xếp', 'class' => 'control-label col-xs-12 col-sm-2'), 'type' => 'number', 'value' => 1));
        echo $this->Form->input('user_view', array('label' => array('text' => 'Sô người view', 'class' => 'control-label col-xs-12 col-sm-2'), 'type' => 'number', 'value' => 0));
        echo $this->Form->input('user_like', array('label' => array('text' => 'Số người like', 'class' => 'control-label col-xs-12 col-sm-2'), 'type' => 'number', 'value' => 0));
        echo $this->Form->input('user_share', array('label' => array('text' => 'Số người chia sẻ', 'class' => 'control-label col-xs-12 col-sm-2'), 'type' => 'number', 'value' => 0));
        ?>
    </fieldset>
    <?php echo $this->Form->end(__('Thay đổi')); ?>
</div>
<script>
    function _delete(id) {
        var elem = document.getElementById(id);
        elem.parentElement.removeChild(elem);
    }
    $(document).ready(function () {
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
