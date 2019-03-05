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
// define variables and set to empty values
if(!isset($_SESSION['account'])){
echo '<meta http-equiv=REFRESH CONTENT=0;url=no_permission.php>';
}

$servername = "dbhome.cs.nctu.edu.tw";
$dbusername = "yenst_cs";
$dbpassword = "10271027";
$dbname = "yenst_cs_hw3";
$auth= "normal";

// Create connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$success=0;
function test_input($data) {
  //$data = trim($data);    //清除前後空白
  //$data = stripslashes($data);  //deleting 
  //$data = htmlspecialchars($data);
  return $data;
}
$nameErr = $priceErr = $dateErr=   "";
$name = $price= $date ="";
$ori_acc="";
$Errmsg=0;
$flag=0;


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["name"])) {
    $nameErr = "Name is required";
    $Errmsg=1;
  } else {
    $name = test_input($_POST["name"]);
    $ori_acc=$_POST['name'];
    // check if name only contains letters
     /* if (preg_match("/\s/",$name)) {    //no space is allowed
      $nameErr = "no space is allowed"; 
      $Errmsg=1;
    }*/
    $success=$success+1;
  }
  
  if (empty($_POST["price"])) {
    $priceErr = "Price is required";
    $Errmsg=1;
  } 
  else {
      if(!preg_match("/^([0-9]+)$/", $_POST['price'])){
        $priceErr="Price can only contain integers";
        $Errmsg=1;
      }
      else{
      $price = test_input($_POST["price"]);
	    $success=$success+1;
      }
  }

  if (empty($_POST["date"])) {
    $dateErr = "date is required";
    $Errmsg=1;
  }
  else {
    $date= test_input($_POST["date"]);
    $success=$success+1;
    }

}

?>


<?php 




if ($success==3){
$Errtext=" ";
$sql_success=0;		//testing if this insert  work


$owner_id=$_SESSION['id'];




  $sql = "INSERT INTO `house`(name, price,time,`owner_id`) VALUES (?,?,'$date',$owner_id)";
  $pre=$conn->prepare($sql);
  $pre->bind_param("si",$name,$price);
  $sql_temp="SELECT id from `house` where name =? and price = ? ";		//get the id of 
  //the house
  $pre_temp=$conn->prepare($sql_temp);
  $pre_temp->bind_param("si",$name,$price);


  if($pre->execute()){		//information is not be inserted yet
    echo "<div style ='font:50px/50px ;color:#ff0000'> <font size='18'> add successfully </div> </font>\n\n\n";
    //echo "<meta http-equiv=REFRESH CONTENT=3;url=user_page.php>";
  }
  else{
    echo "add failed\n";  
  }

  $pre_temp->execute();
  $result_temp=$pre_temp->get_result();
  $row=@mysqli_fetch_row($result_temp);
  $id_of_house=$row[0];

  echo "id of the house is $id_of_house\n\n";

  if(!empty($_POST['location'])){
  $location_id=$_POST['location'];
  $sql_inset_location="INSERT INTO `location`(`location_id`, `house_id`) VALUES ('$location_id','$id_of_house')";
  if(mysqli_query($conn,$sql_inset_location)){
    echo "add location successfully<br>";
  }
  else{
    echo("Error description: " . mysqli_error($conn));
    echo "query failed<br>";
  }
}

  $sql_select_information="select * from information_name where 1";
  $all_information=mysqli_query($conn,$sql_select_information);
  while($row=@mysqli_fetch_row($all_information)){
    if(!empty($_POST[$row[0]])){    //name of post
        $id_of_information=$row[0];
        $sql_insert_information="INSERT INTO information(house_id,information_id) values('$id_of_house','$id_of_information')";
        if(mysqli_query($conn,$sql_insert_information)){
          echo "add information successfully<br>";
        }
     }
  }
  
  $sql_insert_owner="INSERT INTO `owner`(`owner_id`, `house_id`) VALUES ('$owner_id','$id_of_house')";
  mysqli_query($conn,$sql_insert_owner);

  echo "<meta http-equiv=REFRESH CONTENT=2;url=house_management.php>";
}


?>




<p><span class="error">* required field.</span></p>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
  Name: <input type="text" name="name" value="<?php echo $name;?>">
  <span class="error">* <?php echo $nameErr;?></span>
  <br><br>

  

  Date: <input type="date" name="date" value="<?php echo $date;?>">
  <span class="error">* <?php echo $dateErr;?></span>
  <br><br>

  Price : <input type="text" name="price" value="<?php echo $price;?>">
  <span class="error">* <?php echo $priceErr;?></span>
  <br><br>
  
  Location : 

<?php
  $sql_get_location="select * from location_name where 1";
  $result_location=mysqli_query($conn,$sql_get_location);
  while($row=@mysqli_fetch_row($result_location)){
    ?>  <input type="radio" name="location" value="<?php echo $row[0]; ?>"><?php echo $row[1]; ?><?php 
  }
?>

<br>
  Information : 
  <?php
            $sql_get_information="SELECT * from information_name";
            $result_information=mysqli_query($conn,$sql_get_information);
            $i=0;
            while($row=@mysqli_fetch_row($result_information)){
?>
                <input type="checkbox" value="<?php echo $row[1]; ?>" name="<?php echo $row[0]; ?>" ><?php echo $row[1] ?>
<?php
                $i++;
                    if($i%5==0){echo "<br>";}
            }
?>



  <br><br>
  <input type="submit" name="submit" value="Submit">  

  <a href="house_management.php">取消</a>

</form>

<br><br>


</body>
</html>
