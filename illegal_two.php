<?php 
session_start();
?> 
<!DOCTYPE HTML>
<html>  
<body>

<?php

if(!isset($_SESSION['account']))
{
        echo '<meta http-equiv=REFRESH CONTENT=0;url=no_permission.php>';

}

echo "<div style ='font:50px/50px ;color:#ff0000'> <font size='18'> the checkout date must be later than checkin date </div> </font>\n\n\n";
echo '<meta http-equiv=REFRESH CONTENT=3;url=user_page.php?order=5>';





?>

</body>
</html>
