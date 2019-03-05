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

$id=$_GET['information_id'];
$sql="delete from information_name where id = ?";
$pre = $conn->prepare($sql);
$pre->bind_param("i",$id);
if($pre->execute()){
	if($pre->execute()){		//information is not be inserted yet
    echo "<div style ='font:50px/50px ;color:#ff0000'> <font size='18'> delete $id successfully </div> </font>\n\n\n";
    //echo "<meta http-equiv=REFRESH CONTENT=3;url=user_page.php>";
 }
 else{
	echo "<div style ='font:50px/50px ;color:#ff0000'> <font size='18'> delete $id failed </div> </font>\n\n\n"; 
}
echo '<meta http-equiv=REFRESH CONTENT=4;url=delete_information.php>';
}


?>

</body>
</html>