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

if(isset($_SESSION['account']))
{
$ac=$_SESSION['account'];
//$ac=mysqli_real_escape_string($conn,$ac);

$sql = "SELECT * FROM `user_table` where account <> ?";
$pre=$conn->prepare($sql);
$pre->bind_param("s",$ac);
$pre->execute(); //在test資料表中選擇所有欄位
//$result=mysqli_query($conn,$sql)
if($result=$pre->get_result()){
  //echo"the command is worked";
}
$pre->close();
$sql1= "SELECT * FROM `user_table` where account =  ?";
$pre1=$conn->prepare($sql1);
$pre1->bind_param("s",$ac);
$pre1->execute();
//$res1=mysqli_query($conn,$sql1);
$res1=$pre1->get_result();
$row1=mysqli_fetch_row($res1);
echo "帳號：$row1[0]" ;
echo "\t";
echo "姓名：$row1[2]";
echo "\t";
echo "郵件：$row1[3]" ;
echo "\t";
echo "身分：$row1[4]";
echo "<br><br><br><br>";





		echo 'if you want to add a admin or user , click <a href="admin_register.php">HERE</a>  <br><br><br><br>';	

        while($row = @mysqli_fetch_row($result)){
               echo "帳號：$row[0]   \t   姓名：$row[2]	 \t     郵件：$row[3]  \t    身分：$row[4]	 \t  ID：$row[5]<br>";
               $acco=$row[0];
               //$acco=mysqli_real_escape_string($conn,$acco);
               if($row[4]=="normal"){
               	//echo "if you want to promote this user click here";
               ?>	<a href="promote.php?acc=<?php echo$acco?>"> promote </a> or 
               	<a href="delete.php?acc=<?php echo$acco?>"> delete </a>  <?php
               }
               else{
               ?>	<a href="delete.php?acc=<?php echo$acco?>"> delete </a>	<?php
               }

               echo "<br> <br> <br>";
        }

        echo '<a href="logout.php">登出</a>  <br><br>';
        echo '<a href="user_page.php?order=5">BackToHomepage</a>  <br><br>';
}

else
{
        echo '您無權限觀看此頁面!';
		echo '<meta http-equiv=REFRESH CONTENT=3;url=login.php>';

}



?>
</body>
</html>