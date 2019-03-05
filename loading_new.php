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
$dbname = "yenst_cs_hw3";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
else{
}

$sql="select account,password,auth,id,email,name from user_table where account=? ";

$pre=$conn->prepare($sql);

$pre->bind_param("s",$acc);

$pre-> execute();
$result = $pre->get_result();
//$row=$result->fetch_assoc();
$row = @mysqli_fetch_row($result);


if($acc!=null && $pass!=null && $row[0]==$_POST['account'] && $row[1]== md5($pass) &&$row[2]=="normal"){ 
	$_SESSION['account']=$_POST['account'];
	$_SESSION['id']=$row[3];
	$_SESSION['_email']=$row[4];
	$_SESSION['auth']=$row[2];
	$_SESSION['_name']=$row[5];
	$temp=$_POST['account'];
	echo "$temp";
	echo "<br>";
	echo 'normal user登入成功!!';
	echo "<meta http-equiv=REFRESH CONTENT=3;url=user_page.php?order=5>";
}
else if($acc!=null && $pass!=null && $row[0]==$_POST['account'] && $row[1]==md5($pass) &&$row[2]=="admin"){ 
	$_SESSION['account']=$_POST['account'];	
	$_SESSION['id']=$row[3];
	$_SESSION['_email']=$row[4];
	$_SESSION['auth']=$row[2];
	$_SESSION['_name']=$row[5];
	$temp=$_POST['account'];
	echo "$temp";
	echo "<br>";
	echo 'admin 登入成功!!';
	echo "<meta http-equiv=REFRESH CONTENT=3;url=user_page.php?order=5>";
}

else{
	
	
	echo '登入失敗qqq';
	echo "<meta http-equiv=REFRESH CONTENT=3;url=login.php?order=5>";
}


?>

</body>
</html>
