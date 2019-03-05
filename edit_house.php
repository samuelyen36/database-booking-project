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



<font size="5">THIS IS THE HOUSE YOU WANT TO EDIT<br>if you want to edit the name,location,date,price,just enter the value you want to update in the form.<br>If you want it to remain the same,just leave it empty<br>If you select an information which is already exist,you will delete that information from your house<br>On the other hand,if you select an information which is not exist,you will add this information to your house<br><br></font>

<?php 
// define variables and set to empty values
if(!isset($_SESSION['account'])){
echo '<meta http-equiv=REFRESH CONTENT=0;url=no_permission.php>';
}


$nameErr = $priceErr = $locationErr = $dateErr=   "";
$name = $price = $location= $date ="";
$Errtext=" ";
$sql_success;   //testing if this insert  work
$servername = "dbhome.cs.nctu.edu.tw";
$dbusername = "yenst_cs";
$dbpassword = "10271027";
$dbname = "yenst_cs_hw3";
$auth= "normal";


$house_id=$_GET['house_id'];
$_SESSION['edit_house']=$house_id;
// Create connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
$success=0;

$login_id=$_SESSION['id'];
$this_user=$_SESSION['id'];



echo "<br><br>";
?> 


<form method="post" action="editing.php">  
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
