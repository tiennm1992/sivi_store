<a id="main-menu" href="#sidr" class="box-shadow-menu" ></a>
<div id="sidr">
    <div id="account-info">
        <span>hi <?php echo $user['username']; ?> !</span>
        <span><a href="<?php echo $this->Html->url(array( 'controller' => 'accounts', 'action' => 'admin_logout', 'admin' => true ), true); ?>">logout</a></span>
    </div>
    <ul>
        <li><a href="<?php echo $this->Html->url(array( 'controller' => 'accounts', 'action' => 'admin_index', 'admin' => true ), true); ?>">User Management</a></li>
        <li><a href="<?php echo $this->Html->url(array( 'controller' => 'models', 'action' => 'admin_index', 'admin' => true ), true); ?>">Model Management</a></li>
        <li><a href="<?php echo $this->Html->url(array( 'controller' => 'categories', 'action' => 'admin_index', 'admin' => true ), true); ?>">Category Management</a></li>
        <li><a href="<?php echo $this->Html->url(array( 'controller' => 'ecsites', 'action' => 'admin_index', 'admin' => true ), true); ?>">EC Sites Management</a></li>
        <li><a href="<?php echo $this->Html->url(array( 'controller' => 'brands', 'action' => 'admin_index', 'admin' => true ), true); ?>">Brand Management</a></li>
        <li><a href="<?php echo $this->Html->url(array( 'controller' => 'products', 'action' => 'admin_index', 'admin' => true ), true); ?>">Product Management</a></li>
    </ul>
    <p>Promotion Management</p>
    <p>Post Management</p>
    <p>Transaction History</p>
    <p>Instagram Sync</p>
</div>