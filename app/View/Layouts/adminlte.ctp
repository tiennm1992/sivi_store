<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="description" content="Serendip">
        <meta name="author" content="cakiem.vn">
        <!--<title><?php echo $this->fetch('title'); ?></title>-->
    <?php echo $this->Html->meta('icon'); ?>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- Bootstrap 3.3.2 -->
    <?php echo $this->Html->css('bootstrap.min'); ?>
        <!-- Font Awesome Icons -->
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Ionicons -->
        <link href="http://code.ionicframework.com/ionicons/2.0.0/css/ionicons.min.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
    <?php echo $this->Html->css('AdminLTE'); ?>
    <?php echo $this->Html->css('_all-skins.min'); ?>
    <?php echo $this->Html->css('amain'); ?>
    <?php echo $this->fetch('css'); ?>

        <!-- jQuery 2.1.3 -->
    <?php echo $this->Html->script('jQuery-2.1.3.min'); ?>
    </head>
    <body class="skin-blue">
        <!-- Site wrapper -->
        <div class="wrapper">
            <header class="main-header">
                <a href="<?php echo $this->Html->url(array( 'controller' => 'products', 'action' => 'index', 'admin' => true ), true); ?>" class="logo"><b>SiviStore</b></a>
                <!-- Header Navbar: style can be found in header.less -->
        <?php echo $this->element('atopnav'); ?>
            </header>
            <!-- Left side column. contains the sidebar -->
            <aside class="main-sidebar">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <!-- sidebar menu: : style can be found in sidebar.less -->
            <?php echo $this->element('asidebarmenu'); ?>
                </section>
                <!-- /.sidebar -->
            </aside>
            <!-- =============================================== -->
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">

                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="row">
                        <div class="col-xs-12">
                            <legend>
                        <?php echo $this->fetch('title'); ?>
                        <?php echo $this->fetch('action_bar'); ?>
                            </legend>
                            <div class="clearfix"></div>
                    <?php
                    echo $this->Session->flash('success');
                    echo $this->Session->flash('error');
                    echo $this->Session->flash('warning');
                    echo $this->Session->flash('notice');
                    ?>
                        </div>
                    </div>
                </section>

                <!-- Main content -->
                <section class="content">
            <?php echo $this->fetch('content'); ?>
                </section><!-- /.content -->
            </div><!-- /.content-wrapper -->

            <footer class="main-footer">
                <strong>Copyright &copy; 2016 <a href="#">SiviStore</a>.</strong> All rights reserved.
            </footer>
        </div><!-- ./wrapper -->

        <!-- Bootstrap 3.3.2 JS -->
<?php echo $this->Html->script('bootstrap.min'); ?>
        <!-- Underscore -->
<?php echo $this->Html->script('underscore-min'); ?>
        <!-- SlimScroll -->
<?php echo $this->Html->script('jquery.slimscroll.min'); ?>
        <!-- FastClick -->
<?php echo $this->Html->script('fastclick.min'); ?>
        <!-- BlockUI -->
<?php echo $this->Html->script('jquery.blockUI'); ?>
        <!-- AdminLTE App -->
<?php echo $this->Html->script('app.min'); ?>
<?php echo $this->Html->script('amain'); ?>
<?php echo $this->fetch('script'); ?>
<?php echo $this->fetch('modal'); ?>
    </body>
</html>