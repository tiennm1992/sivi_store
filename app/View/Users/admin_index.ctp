<html>
<body>
<ul>
    <li>Login as : <?php echo $current_user['username'];?></li>
    <li><a href='<?php echo $this->webroot."admin/users";?>'>View Users</a></li>
    <li><a href='<?php echo $this->webroot."admin/users/add";?>'>Add new User</a></li>
    <li><a href='<?php echo $this->webroot."users/logout";?>'>Logout</a></li>
</ul>
<?php
if($data==NULL){
    echo "<h2>Dada Empty</h2>";
}
else{
    echo "<table>
          <tr>
            <td>User ID</td>
            <td>Name</td>
            <td>Username</td>
            <td>Email</td>
            <td>Level</td>
            <td>Options</td>
          </tr>";
    foreach($data as $item){
        echo "<tr>";
        echo "<td>".$item['User']['id']."</td>";
        echo "<td>".$item['User']['name']."</td>";
        echo "<td>".$item['User']['username']."</td>";
        echo "<td>".$item['User']['email']."</td>";
        if($item['User']['level']==1){
            $level = "Admin";
        }else{
            $level = "User";
        }
        echo "<td>".$level."</td>";
        echo "<td><a href='".$this->webroot."admin/users/edit/".$item['User']['id']."' >Edit</a><br>
        <a href='".$this->webroot."admin/users/delete/".$item['User']['id']."' >Del</a></td>";
        echo "</tr>";
    } 
}
?>
</body>
</html>