<nav class="navbar navbar-static-top" role="navigation">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </a>
    <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
            <!-- Messages: style can be found in dropdown.less-->
            <!-- User Account: style can be found in dropdown.less -->
            <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="glyphicon glyphicon-user"></i>
                    <span><?php // echo $user['username']; ?> <i class="caret"></i></span>
                </a>
                <ul class="dropdown-menu">
                    <!-- User image -->
                    <li class="user-header">
                        <?php echo $user['username']; ?>
                    </li>
                    <!-- Menu Footer-->
                    <li class="user-footer">
                        <div class="pull-left">
                            <a class="btn btn-default btn-flat"  href="<?php echo $this->Html->url(array( 'controller' => 'users', 'action' => 'admin_change_password', 'admin' => true ), true); ?>">Đổi password</a>
                        </div>
                        <div class="pull-right">
                            <a class="btn btn-default btn-flat" href="<?php echo $this->Html->url(array( 'controller' => 'users', 'action' => 'logout', 'admin' => true ), true); ?>">Đăng xuất</a>
                        </div>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>