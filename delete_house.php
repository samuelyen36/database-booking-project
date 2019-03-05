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
$house_id= $_GET['house_id'];

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

echo "$house_id<br>";
//$sql = "delete from house where id = '$house_id' ";
$sql = "delete from house where id = ? ";
$pre=$conn->prepare($sql);
$pre->bind_param("i",$house_id);


//mysqli_query($conn,$sql)

if($pre->execute()){
    echo "delete $house_id from you table successfully<br>";
 }
else{
	echo("Error description: " . mysqli_error($conn));
}

echo '<meta http-equiv=REFRESH CONTENT=3;url=house_management.php>';

?>
</body>
</html>