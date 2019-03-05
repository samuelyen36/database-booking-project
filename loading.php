<?php
session_start();
?>

<!DOCTYPE HTML>
<html>  
<body>

<?php
$acc=$_POST['account'];
$pass=$_POST['password'];



$servername = "dbhome.cs.nctu.edu.tw";
$dbusername = "yenst_cs";
$dbpassword = "10271027";
$dbname = "yenst_cs_hw1";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
else{
}

$acc=mysqli_real_escape_string($conn,$acc);
$pass=mysqli_real_escape_string($conn,$pass);

$sql="SELECT * FROM `table` WHERE `account` = '$acc'";

$result=mysqli_query($conn,$sql);
$row = @mysqli_fetch_row($result);


if($acc!=null && $pass!=null && $row[0]==$_POST['account'] && $row[1]== md5($pass) &&$row[4]=="normal"){ 
	$_SESSION['account']=$_POST['account'];
	$temp=$_POST['account'];
	echo "$temp";
	echo "<br>";
	echo 'normal user登入成功!!';
	echo "<meta http-equiv=REFRESH CONTENT=3;url=user_page.php>";
}
else if($acc!=null && $pass!=null && $row[0]==$_POST['account'] && $row[1]==md5($pass) &&$row[4]=="admin"){ 
	$_SESSION['account']=$_POST['account'];	
	$temp=$_POST['account'];
	echo "$temp";
	echo "<br>";
	echo 'admin 登入成功!!';
	echo "<meta http-equiv=REFRESH CONTENT=3;url=admin.php>";
}

else{
	echo '登入失敗qqq';
	echo "<meta http-equiv=REFRESH CONTENT=3;url=login.php>";
}


?>

</body>
</html>
