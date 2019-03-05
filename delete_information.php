<?php
session_start();
?>
<!DOCTYPE html>
<html>
<body>


<?php

$servername = "dbhome.cs.nctu.edu.tw";
$dbusername = "yenst_cs";
$dbpassword = "10271027";
$dbname = "yenst_cs_hw3";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if(!isset($_SESSION['account'])){
echo '<meta http-equiv=REFRESH CONTENT=0;url=no_permission.php>';
}

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql="select * from information_name where 1";
$result = mysqli_query($conn,$sql);

while($row = @mysqli_fetch_row($result)){

echo "$row[1]";
?>	<a href="deleting_information.php?information_id=<?php echo$row[0] ?>"> delete </a> <?php 
echo "<br><br>";
}



?>
<a href="user_page.php">back to homepage</a> 


</body>
</html>