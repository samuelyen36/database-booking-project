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
$info_name=$_POST["location_name"];

if(!isset($_SESSION['account']))
{
        echo '<meta http-equiv=REFRESH CONTENT=0;url=no_permission.php>';

}

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql_check = "select id from location_name where name = ?";
$pre_check=$conn->prepare($sql_check);
$pre_check->bind_param("s",$info_name);
$pre_check->execute();
$result = $pre_check->get_result();
$pre_check->close();
if(mysqli_num_rows($result)!=0){
	echo "<div style ='font:50px/50px ;color:#ff0000'> <font size='18'> this location has the same name with another location ，母湯喔 </div> </font>\n\n\n";
	echo '<meta http-equiv=REFRESH CONTENT=4;url=add_location.php>';
}
else{
$sql="INSERT INTO location_name (name)VALUES(?)";
$pre=$conn->prepare($sql);
$pre->bind_param("s",$info_name);
if($pre->execute()){		//location is not be inserted yet
    echo "<div style ='font:50px/50px ;color:#ff0000'> <font size='18'> add $info_name successfully </div> </font>\n\n\n";
    //echo "<meta http-equiv=REFRESH CONTENT=3;url=user_page.php>";
 }
 else{
	echo "<div style ='font:50px/50px ;color:#ff0000'> <font size='18'> add $info_name failed </div> </font>\n\n\n"; 
}
$pre->close();
echo '<meta http-equiv=REFRESH CONTENT=4;url=user_page.php>';
}
?>


</body>
</html>