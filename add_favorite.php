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
    $id_of_house=$_GET['house_id'];
    $id_of_user=$_GET['userid'];

    echo "$id_of_user<br>";
    echo "$id_of_house<br>";

    $sql="insert into favorite(user_id,house_id) VAlUES (?,?)";
    $pre=$conn->prepare($sql);
    $pre->bind_param("ii",$id_of_user,$id_of_house);
    //$pre->execute();
    if($pre->execute()){
        echo "update successfully<br><br>";
        echo '<meta http-equiv=REFRESH CONTENT=3;url=user_page.php?order=5>';
    }
        
    else{
        echo "this command not work<br><br>";
        echo '<meta http-equiv=REFRESH CONTENT=3;url=user_page.php?order=5>';
    }

    //if(!$pre->execute()){
    	//echo "this command not work<br><br>";
    	//echo '<meta http-equiv=REFRESH CONTENT=3;url=user_page.php>';
    //}
    //else{
    	//echo "update successfully<br><br>";
    	//echo '<meta http-equiv=REFRESH CONTENT=3;url=user_page.php>';
    //}
}   

else
{
        echo '您無權限觀看此頁面!';
        echo '<meta http-equiv=REFRESH CONTENT=3;url=login.php>';
}



?>
</body>
</html>