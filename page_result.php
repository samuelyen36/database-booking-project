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

<form action="page_result.php" method="post">
  The maximum number of datas in a page :<br>
  <input type="text" name="results">
  <br><br>
  <input type="submit" value="Submit">
</form> 

<a href="user_page.php?order=5">back to user page</a>
</body>

<?php

	if (empty($_POST["results"])) {	//you haven't input anything

  } 
  else {	//you have input something
      if(!preg_match("/^([0-9]+)$/", $_POST['results'])){	//the input contains non-number character
        $resultsErr="Results can only contain integers";
        echo "<div style ='font:50px/50px ;color:#ff0000'> <font size='18'> $resultsErr </div> </font>\n\n\n"
      }
      else{	//legal case
      	//we need a session variable to save the number
      	$_SESSION['per_page']=$_POST['results'];
      	echo "<div style ='font:50px/50px ;color:#ff0000'> <font size='18'> change successfully </div> </font>\n\n\n";
      	echo '<meta http-equiv=REFRESH CONTENT=4;url=user_page.php?order=5>';
      }
  }

?> 
</html>