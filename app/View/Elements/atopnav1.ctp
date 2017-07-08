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
                        <p>
                            <?php
                                $groups= array(
                                    ACCOUNT_TYPE_MODEL => 'モデルフループ、',
                                    ACCOUNT_TYPE_MANAGER => 'モデル管理局',
                                    ACCOUNT_TYPE_ADMIN => 'アドミングループ、'
                                );
                            ?>
                            <?php foreach($groups as $key => $account_type):?>
                                <?php if($user['account_type'] == $key) : ?>
                                    <?php echo $user['username']; ?> - <?php echo $account_type; ?>
                                    <?php break; ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </p>
                    </li>
                    <!-- Menu Footer-->
                    <li class="user-footer">
                        <div class="pull-left">
                            <a class="btn btn-default btn-flat" title="パスワード変更" href="<?php echo $this->Html->url(array( 'controller' => 'users', 'action' => 'admin_change_password', 'admin' => true ), true); ?>">パスワード変更</a>
                        </div>
                        <div class="pull-right">
                            <a class="btn btn-default btn-flat" href="<?php echo $this->Html->url(array( 'controller' => 'users', 'action' => 'admin_logout', 'admin' => true ), true); ?>">ログアウト</a>
                        </div>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>