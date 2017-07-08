<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="metro metro">
        <meta name="author" content="">
        <title><?php echo $this->fetch('title'); ?></title>
    <?php echo $this->Html->meta('icon'); ?>
    <?php echo $this->Html->css('bootstrap.min'); ?>
        <!--[if lt IE 9]>
    <?php echo $this->Html->script('html5shiv'); ?>
        <![endif]-->
    <?php echo $this->Html->css('main'); ?>
    <?php echo $this->Html->css('AdminLTE.min'); ?>
    <?php echo $this->Html->css('blue'); ?>
    <?php echo $this->fetch('css'); ?>
        <style>
            body {
                /*background-color: lightblue;*/
                background-image: url("http://hinhanhdep.pro/content/uploads/2016/01/phong-canh-4-mua-15.jpg");
            }
        </style>
    </head><!--/head-->

    <body class="login-page">
    <?php echo $this->fetch('content'); ?>
    <?php echo $this->Html->script('jquery'); ?>
    <?php echo $this->Html->script('bootstrap.min'); ?>
    <?php echo $this->Html->script('icheck.min'); ?>
    <?php echo $this->Html->script('alogin'); ?>
    <?php echo $this->fetch('script'); ?>
    </body>
</html>