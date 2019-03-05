<?php
session_start();

if(!isset($_SESSION['account']))
{
        echo '<meta http-equiv=REFRESH CONTENT=0;url=no_permission.php>';

}

?>
<!DOCTYPE html>
<html>
<body>

<form action="adding_information" method="post">
  The name you want your information to be :<br>
  <input type="text" name="information_name">
  <br><br>
  <input type="submit" value="Submit">
</form> 

<a href="user_page.php">back to user page</a>
</body>
</html>