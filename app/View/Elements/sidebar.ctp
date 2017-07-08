<h1>Main Menu</h1>
<div class="collapse navbar-collapse" id="mainmenu">
    <ul class="nav nav-stacked">
        <li><a href="<?php echo $this->Html->url('/', true); ?>" class="active">Home</a></li>
        <li><a href="<?php echo $this->Html->url(array( 'controller' => 'modelposts', 'action' => 'get_media' ), true); ?>">add post</a></li>
        <li><a href="<?php echo $this->Html->url(array( 'controller' => 'usermodels', 'action' => 'listmodel' ), true); ?>">List model</a></li>
        <li><a href="<?php echo $this->Html->url(array( 'controller' => 'users', 'action' => 'mylike' ), true); ?>">My like</a></li>
        <li><a href="<?php echo $this->Html->url(array( 'controller' => 'usernews', 'action' => 'timeline' ), true); ?>">Timeline</a></li>
        <li><a href="<?php echo $this->Html->url(array( 'controller' => 'users', 'action' => 'editinfo' ), true); ?>">Edit Info</a></li>
        <li><a href="<?php echo $this->Html->url(array( 'controller' => 'authenticates', 'action' => 'logout' ), true); ?>">Logout</a></li>
    </ul>
</div>