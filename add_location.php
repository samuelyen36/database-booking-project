<?php
session_start();
?>
<!DOCTYPE html>
<html>
<body>
<?php 
if(!isset($_SESSION['account']))
{
        echo '<meta http-equiv=REFRESH CONTENT=0;url=no_permission.php>';

}
?>
<form action="adding_location" method="post">
  The name you want your location to be :<br>
  <input type="text" name="location_name">
  <br><br>
  <input type="submit" value="Submit">
</form> 

<a href="user_page.php">back to user page</a>
</body>
</html>