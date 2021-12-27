<?php

require_once "PasswordHash.php";

if(isset($_POST["password"]))
{
    $password_hash = create_hash($_POST["password"]);
 
}

?>

 
<h3>Generate a password hash</h3>
<form method="post" action="index.php">
    
    <b>Password:</b>
    <input type="text" id="password" name="password"/>

    <input type="submit" value="Generate Hash"/>
</form>    

<?php if (isset($password_hash)){  ?>

<br/><br/>
<b>Password Hash: </b>
<?php echo  $password_hash; ?>

 

<?php } ?>
    