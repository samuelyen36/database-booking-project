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
    $check_in=$_GET['checkin'];
    $check_out=$_GET['checkout'];
    $house_id=$_GET['house'];
    $user=$_SESSION['id'];


    $sql="INSERT INTO `book`( `check_in`, `check_out`, `house_id`, `user_id`) VALUES ('$check_in','$check_out','$house_id','$user')";

    //$pre->execute();
    if(mysqli_query($conn,$sql)){
        echo "book successfully<br><br>";
        echo '<meta http-equiv=REFRESH CONTENT=3;url=user_page.php?order=5>';
    }
        
    else{
        echo "this command not work<br><br>";
        echo '<meta http-equiv=REFRESH CONTENT=3;url=user_page.php?order=5>';
    }

}   



?>
</body>
</html>