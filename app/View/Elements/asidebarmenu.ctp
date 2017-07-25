<?php
$urls = array(
    'products' => $this->Html->url(array('controller' => 'products', 'action' => 'index', 'admin' => true), true),
    'add_product' => $this->Html->url(array('controller' => 'products', 'action' => 'add', 'admin' => true), true),
    'category' => $this->Html->url(array('controller' => 'categories', 'action' => 'index', 'admin' => true), true),
    'add_category' => $this->Html->url(array('controller' => 'categories', 'action' => 'add', 'admin' => true), true),
    'subcategory' => $this->Html->url(array('controller' => 'subcategories', 'action' => 'index', 'admin' => true), true),
    'add_subcategory' => $this->Html->url(array('controller' => 'subcategories', 'action' => 'add', 'admin' => true), true),
    'add_slides' => $this->Html->url(array('controller' => 'slides', 'action' => 'add', 'admin' => true), true),
    'slides' => $this->Html->url(array('controller' => 'slides', 'action' => 'index', 'admin' => true), true),
    //
    'employee' => $this->Html->url(array('controller' => 'users', 'action' => 'employee', 'admin' => true), true),
    'partner' => $this->Html->url(array('controller' => 'users', 'action' => 'partner', 'admin' => true), true),
    'cskh' => $this->Html->url(array('controller' => 'users', 'action' => 'admin', 'admin' => true), true),
    'super' => $this->Html->url(array('controller' => 'users', 'action' => 'super', 'admin' => true), true),
    //
    'add_employee' => $this->Html->url(array('controller' => 'employees', 'action' => 'add', 'admin' => true), true),
    'userbuy' => $this->Html->url(array('controller' => 'userBuys', 'action' => 'index', 'admin' => true), true),
    'infor' => $this->Html->url(array('controller' => 'user', 'action' => 'infor', 'admin' => true), true),
    'promotion' => $this->Html->url(array('controller' => 'promotions', 'action' => 'index', 'admin' => true), true),
    'slides' => $this->Html->url(array('controller' => 'slides', 'action' => 'index', 'admin' => true), true),
    'customer' => $this->Html->url(array('controller' => 'customers', 'action' => 'index', 'admin' => true), true),
    'revenu' => $this->Html->url(array('controller' => 'slides', 'action' => 'index', 'admin' => true), true),
    'add_slides' => $this->Html->url(array('controller' => 'slides', 'action' => 'add', 'admin' => true), true),
    'list_user' => $this->Html->url(array('controller' => 'customers', 'action' => 'list_customer', 'admin' => true), true),
    'add_user' => $this->Html->url(array('controller' => 'users', 'action' => 'add', 'admin' => true), true),
    'changepassword' => $this->Html->url(array('controller' => 'users', 'action' => 'admin_change_password', 'admin' => true), true),
    'logout' => $this->Html->url(array('controller' => 'users', 'action' => 'admin_logout', 'admin' => true), true),
    //
    'user_buy' => $this->Html->url(array('controller' => 'userBuys', 'action' => 'order', 'admin' => true), true),
    'check_buy' => $this->Html->url(array('controller' => 'userBuys', 'action' => 'check_buy', 'admin' => true), true),
    'success_buy' => $this->Html->url(array('controller' => 'userBuys', 'action' => 'success_buy', 'admin' => true), true),
);
?>

<ul class="sidebar-menu">
    <?php if ($user['role'] == 'super'): ?>
        <!--Quản trị đơn hàng-->
        <li class="header"><i class="fa fa-square"></i> <span>Quản trị đơn hàng</span></li>
        <!--pages-->
        <li><a href="<?php echo $urls['user_buy']; ?>" ><i class="fa fa-angle-double-right"></i> <span>Đơn hàng mới</span></a></li>
        <li><a href="<?php echo $urls['check_buy']; ?>" ><i class="fa fa-angle-double-right"></i> <span>Đơn hàng đang chờ</span></a></li>
        <li><a href="<?php echo $urls['success_buy']; ?>" ><i class="fa fa-angle-double-right"></i> <span>Đơn hàng thành công</span></a></li>
        <!--quản lý sản phẩm-->
        <li class="header"><i class="fa fa-square"></i> <span>Quản lý sản phẩm</span></li>
        <li><a href="<?php echo $urls['products']; ?>" ><i class="fa fa-angle-double-right"></i> <span>Danh sách SP</span></a></li>
        <li><a href="<?php echo $urls['add_product']; ?>"><i class="fa fa-angle-double-right"></i> <span>Thêm SP mới</span></a></li>
        <!--Quản lý tài khoản-->
        <?php if ($user['role'] == 'super'): ?>
            <li class="header"><i class="fa fa-square"></i> <span>Quản trị tài khoản</span></li>
            <li><a href="<?php echo $urls['super']; ?>"><i class="fa fa-angle-double-right"></i> <span>Admin</span></a></li>
            <li><a href="<?php echo $urls['cskh']; ?>"><i class="fa fa-angle-double-right"></i> <span>CSKH</span></a></li>
            <li><a href="<?php echo $urls['employee']; ?>"><i class="fa fa-angle-double-right"></i> <span>Sales</span></a></li>
            <li><a href="<?php echo $urls['list_user']; ?>"><i class="fa fa-angle-double-right"></i> <span>Khách hàng</span></a></li>
            <li><a href="<?php echo $urls['add_user']; ?>" ><i class="fa fa-angle-double-right"></i> <span>Thêm tài khoản</span></a></li>
        <?php endif; ?>
        <!--Quản lý category-->
        <li class="header"><i class="fa fa-square"></i> <span>Quản lý danh mục</span></li>
        <li><a href="<?php echo $urls['category']; ?>"><i class="fa fa-angle-double-right"></i> <span>Danh mục lớn</span></a></li>
        <li><a href="<?php echo $urls['add_category']; ?>" ><i class="fa fa-angle-double-right"></i> <span>Thêm DML mới</span></a></li>
        <!--Quản lý sub category-->
        <li><a href="<?php echo $urls['subcategory']; ?>"><i class="fa fa-angle-double-right"></i> <span>Danh mục con</span></a></li>
        <li><a href="<?php echo $urls['add_subcategory']; ?>" ><i class="fa fa-angle-double-right"></i> <span>Thêm DMC mới</span></a></li>


        <!--Quản lý slide-->
        <li class="header"><i class="fa fa-square"></i> <span>Quản lý khác</span></li>
        <!--pages-->
        <li><a href="<?php echo $urls['slides']; ?>" ><i class="fa fa-angle-double-right"></i> <span>Slide</span></a></li>
        <li><a href="<?php echo $urls['add_slides']; ?>" ><i class="fa fa-angle-double-right"></i> <span>Thêm Slide</span></a></li>
        <li><a href="<?php echo $urls['promotion']; ?>" ><i class="fa fa-angle-double-right"></i> <span>khuyến mại</span></a></li>
        <!--end pages-->
    <?php endif; ?>
    <?php if ($user['role'] == 'employee' || $user['role'] == 'partner'): ?>
        <!--quan ly cua nhan vien-->
        <li class="header"><i class="fa fa-square"></i> <span>Thông tin</span></li>
        <!--pages-->
        <li><a href="<?php echo $urls['customer']; ?>" ><i class="fa fa-angle-double-right"></i> <span>Khách hàng</span></a></li>
        <li><a href="<?php echo $urls['userbuy']; ?>" ><i class="fa fa-angle-double-right"></i> <span>Doanh thu</span></a></li>
        <!--<li><a href="<?php echo $urls['infor']; ?>" ><i class="fa fa-angle-double-right"></i> <span>Thông tin nhân viên</span></a></li>-->
    <?php endif; ?>
    <?php if ($user['role'] == 'admin'): ?>
        <!--quan ly cua nhan vien vien ban hang-->
        <li class="header"><i class="fa fa-square"></i> <span>Quản trị đơn hàng</span></li>
        <!--pages-->
        <li><a href="<?php echo $urls['user_buy']; ?>" ><i class="fa fa-angle-double-right"></i> <span>Đơn hàng mới</span></a></li>
        <li><a href="<?php echo $urls['check_buy']; ?>" ><i class="fa fa-angle-double-right"></i> <span>Đơn hàng đang chờ</span></a></li>
        <li><a href="<?php echo $urls['success_buy']; ?>" ><i class="fa fa-angle-double-right"></i> <span>Đơn hàng thành công</span></a></li>
        <!--quan tri khach hang-->
        <li class="header"><i class="fa fa-square"></i> <span>Quản trị khách hàng</span></li>
        <li><a href="<?php echo $urls['list_user']; ?>"><i class="fa fa-angle-double-right"></i> <span>Khách hàng</span></a></li>
        <?php endif; ?>
</ul>
