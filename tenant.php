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

$user_id = $_SESSION['id'];
$sql = "select house.name ,user_table.name,check_in,check_out,book.id,house.id from book inner join house on book.house_id = house.id inner join user_table on house.owner_id = user_table.id where book.user_id = ? ";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);


$stmt->execute();
$result=$stmt->get_result();
if(mysqli_num_rows($result)==0){
    echo "<font size='8' color='red'>you haven't booked a house yet</font>";
  }


while($row = @mysqli_fetch_row($result)){
  $book_id = $row[4];
  $house_id = $row[5];
   ?> <a href="delete_book.php?id=<?php echo$book_id?>"> delete </a> <?php
   ?> <a href="change_book.php?id=<?php echo$book_id?>&&house_id=<?php echo$house_id?>"> update </a><br> <?php
  echo "house name : $row[0]<br>";
  echo "house id : $row[5]<br>";
  echo "owner_name : $row[1] <br>";
  echo "check in date : $row[2]<br>";
  echo "check out date : $row[3]<br>";
  echo "<br><br>";

}



?>
</body>
</html>

