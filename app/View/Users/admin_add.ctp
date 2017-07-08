<ul>
    <li>Login as : <?php echo $current_user['username'];?></li>
    <li><a href='<?php echo $this->webroot."admin/users";?>'>View Users</a></li>
    <li><a href='<?php echo $this->webroot."admin/users/add";?>'>Add new User</a></li>
    <li><a href='<?php echo $this->webroot."users/logout";?>'>Logout</a></li>
</ul>
<?php
    echo $this->Form->create('User'); 
    echo "<fieldset>";  
    echo "<legend>Add new User</legend>";
    
    echo $this->Form->input('name');
    
    echo $this->Form->input('username');
    
    echo $this->Form->input('pass',array("type"=>"password"));
    
    echo $this->Form->input('re_pass',array("type"=>"password"));
    
    echo $this->Form->input('email');
    
    $options = array(""=>"Level","1"=>"Admin","2"=>"User");   
    echo $this->Form->input("level",array("type"=>"select","options"=>$options));
    
    echo $this->Form->end('Add new');
    echo "</fieldset>";
    
?>