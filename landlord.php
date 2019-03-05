<?php
session_start();
?>

<a href="user_page.php?order=5">homepage</a> <a href="logout.php">logout</a><br><br>

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

$user_id=$_SESSION['id'];

$sql = "select * from book inner join house on book.house_id = house.id where house.owner_id= ? ";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);


$stmt->execute();
$result=$stmt->get_result();

if(mysqli_num_rows($result)==0){
    echo "<font size='8' color='red'> your house haven't been book yet </font>";

  }


while( $row = @mysqli_fetch_row($result)){
  $check_in_user=$row[4];

  $select_order_name = "select name from user_table where user_table.id = ? ";
  $prep = $conn->prepare($select_order_name);
  $prep->bind_param("i",$check_in_user);
  $prep->execute();
  $booker_id_result = $prep->get_result();
  $booker_row = @mysqli_fetch_row($booker_id_result);



  echo "house name : $row[6]<br>";
  echo "house id : $row[5]<br>";
  echo "check in : $row[1] <br>";
  echo "check out: $row[2] <br>";
  echo "booker: $booker_row[0]<br>";

  echo "<br><br><br>";
}


?>
</body>
</html>

