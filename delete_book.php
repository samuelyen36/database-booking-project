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
    $id_of_book=$_GET['id'];

    $sql="delete from book where id=? ";
    $pre=$conn->prepare($sql);
    $pre->bind_param("i",$id_of_book);
    if($pre->execute()){
        echo "delete successfully<br><br>";
        echo '<meta http-equiv=REFRESH CONTENT=3;url=tenant.php>';
    }
    else {
    	echo "this command not work<br><br>";
    	echo '<meta http-equiv=REFRESH CONTENT=3;url=tenant.php>';
    }
    
}   

else
{
        echo '您無權限觀看此頁面!';
        echo '<meta http-equiv=REFRESH CONTENT=3;url=login.php>';
}



?>
</body>
</html>