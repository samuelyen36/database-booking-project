<?php
session_start();
session_unset();
session_destroy();   
$_SESSION['Authenticated']=false; 
?>


<!DOCTYPE HTML>
<html>  
<body>

login system:<br>



<form action="loading_new.php" method="post">
Account: <input type="text" name="account"><br>
Passoword: <input type="password" name="password"><br>
<input type="submit">
</form>

<br><br>
doesn't have an account? click <a href="register_test.php">here</a> to regist



</body>
</html>
