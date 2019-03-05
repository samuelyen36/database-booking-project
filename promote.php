<?php
session_start();
?>
<!DOCTYPE HTML>  
<html>
<head>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>  
<?php
if(!isset($_SESSION['account'])){
echo '<meta http-equiv=REFRESH CONTENT=0;url=no_permission.php>';
}

$servername = "dbhome.cs.nctu.edu.tw";
$dbusername = "yenst_cs";
$dbpassword = "10271027";
$dbname = "yenst_cs_hw3";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
else{
}

if($_GET['acc'] != null){
	
	$auth=$_GET['acc'];
	$temp=$_GET['acc'];
	//$auth=addslashes($auth);

	//$sql = "UPDATE `user_table` SET `auth`='admin' WHERE `account`='$auth' ";
	$sql = "UPDATE `user_table` SET `auth`='admin' WHERE `account`=? ";
	$pre=$conn->prepare($sql);
	$pre->bind_param("s",$auth);
	//$result=mysqli_query($conn,$sql)
	//$result=$pre->get_result()
	if($pre->execute()){
	echo "The  $temp has been promoted";
	echo '<meta http-equiv=REFRESH CONTENT=1;url=member_management.php>';
	}

	else{
		echo "command failed.";
		echo '<meta http-equiv=REFRESH CONTENT=1;url=member_management.php>';

	}

}





?>
</body>
</html>