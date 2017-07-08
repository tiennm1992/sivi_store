<?php $account = $this->Session->read('Account_Info');?>
<a id="main-menu" href="#sidr" class="box-shadow-menu" ></a>
<div id="sidr">
    <div id="account-info">
        <img src="<?php echo $account->user->profile_picture;?>"/>
        <span><?php echo $account->user->full_name; ?></span>
        <span>|</span>
        <span><a href="<?php echo $this->Html->url(array( 'controller' => 'authenticates', 'action' => 'logout' ), true); ?>">Logout</a></span>
    </div>
    <!-- Your content -->
    <p>Front End Module</p>
    <ul>
        <li><a href="<?php echo $this->Html->url('/', true); ?>" class="active" >Home</a></li>
        <li><a href="<?php echo $this->Html->url(array( 'controller' => 'usermodels', 'action' => 'listmodel' ), true); ?>">List model</a></li>
        <li><a href="<?php echo $this->Html->url(array( 'controller' => 'users', 'action' => 'mylike' ), true); ?>">My like</a></li>
        <li><a href="<?php echo $this->Html->url(array( 'controller' => 'usernews', 'action' => 'timeline' ), true); ?>">Timeline</a></li>
        <li><a href="<?php echo $this->Html->url(array( 'controller' => 'usernews', 'action' => 'newstimeline' ), true); ?>">News Timeline</a></li>
        <li><a href="<?php echo $this->Html->url(array( 'controller' => 'users', 'action' => 'editinfo' ), true); ?>">Edit Info</a></li>
    </ul>
</div>