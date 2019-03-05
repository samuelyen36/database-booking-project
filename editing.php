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

$Errtext=" ";
$sql_success;   //testing if this insert  work
$servername = "dbhome.cs.nctu.edu.tw";
$dbusername = "yenst_cs";
$dbpassword = "10271027";
$dbname = "yenst_cs_hw3";

// Create connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
$success=0;

$login_id=$_SESSION['id'];
$this_user=$_SESSION['id'];
$house_id=$_SESSION['edit_house'];





echo "<br><br>";

function test_input($data) {
  return $data;
}

$nameErr = $priceErr = $locationErr = $dateErr=   "";
$name = $price = $location= $date ="";
$ori_acc="";
$Errmsg=0;
echo "house id is $house_id<br>";


  if (empty($_POST["name"])) {
  } 
  else {
    $name = test_input($_POST["name"]);
    echo "name to be update $name <br>";;
    $sqln="update `house` set name = ? where `id` = ?";
    $pre=$conn->prepare($sqln);
    $pre->bind_param("si",$name,$house_id);
    if($pre->execute()){
      echo "update the name successfully as $name<br>";
      $sql_success=1;
      $pre->close();
    }
    else{
      echo "error occur<br>";
    echo("Error description: " . mysqli_error($conn));
    }
  }
  
  if (empty($_POST["price"])) {
  }
   else {
    $price = test_input($_POST["price"]);
    if(!preg_match("/^([0-9]+)$/", $_POST['price'])){
        echo "<font color='red'>Price can only contain integers </font>";
      }
    else{
   $sqlp="update `house` set price = ? where `id` = ?";
   $pre=$conn->prepare($sqlp);
   $pre->bind_param("ii",$price,$house_id);
    if($pre->execute()){
      echo "update the price successfully as $price<br>";
      $sql_success=1;
      $pre->close();
   }
    else{
   echo "error occur<br>";
  echo("Error description: " . mysqli_error($conn));
    }
  }}



  if (empty($_POST["date"])) {
  } 
  else {
    $date= test_input($_POST["date"]);
    $sqld="update `house` set time = '$date' where `id` = ?";
    $pre=$conn->prepare($sqld);
    $pre->bind_param("i",$house_id);
    if($pre->execute()){
      echo "update the date successfully as $date<br>";
      $sql_success=1;
      $pre->close();
    }
    else{
     echo "error occur<br>";
  echo("Error description: " . mysqli_error($conn));
    }
}

if(!empty($_POST['location'])){
  $location_id=$_POST['location'];

  $sql_delete_location="delete from location where house_id = ?";
  $ppre=$conn->prepare($sql_delete_location);
  $ppre->bind_param("i",$house_id);
  $ppre->execute();
  $ppre->close();

  $sql_inset_location="INSERT INTO `location`(`location_id`, `house_id`) VALUES ( ? , ? ) ";  //$location_id , $house_id
  $pre=$conn->prepare($sql_inset_location);
  $pre->bind_param("ii",$location_id,$house_id);

  if($pre->execute()){
    echo "edit location successfully<br>";
    $pre->close();
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

        $sql_check="select * from information where house_id = $house_id and information_id = $id_of_information";
        $result = mysqli_query($conn,$sql_check);
        $counting = mysqli_num_rows($result);

        if($counting == 0){   //the house doesn't have this information
            $sql_insert="INSERT INTO `information`(`information_id`, `house_id`) VALUES ($id_of_information,$house_id)";
            if(mysqli_query($conn,$sql_insert)){
              echo "insert $row[1] into house successfully<br>";
              continue;
            }
            else{
              echo "insert $row[1] into house fail<br>";
            }
        }
        else{   //the house doesn't have this information
            $sql_delete="DELETE FROM `information` WHERE information_id = $id_of_information and house_id = $house_id";
            if(mysqli_query($conn,$sql_delete)){
              echo "delete $row[1] from house successfully<br>";
              continue;
            }
            else{
              echo "delete $row[1] from house fail<br>";
            }
            
        }

        /*$sql_insert_information="INSERT INTO information(house_id,information_id) values('$id_of_house','$id_of_information')";
        if(mysqli_query($conn,$sql_insert_information)){
          echo "add information successfully<br>";
        }*/




     }
}



echo '<meta http-equiv=REFRESH CONTENT=5;url=house_management.php>';

?>


<?php 



$owner_id=" ";

  
  if(!empty($_POST['info0'])){
    $text0=$_POST['info0'];
    $sql = "select id from information where house_id = '$house_id' and information = '$text0'";
    $result = mysqli_query($conn,$sql);
    $row_count=$result->num_rows;

    if($row_count===0){   //insert the information
      $sql_tmp = "INSERT INTO `information`(information, house_id) VALUES ('$text0','$house_id')";
      if(mysqli_query($conn,$sql_tmp)){
        echo "add information $text0 successfully<br>";
      }
    }
    else{
      $sql_tmp = "delete from information where house_id = '$house_id' and information = '$text0'";
      if(mysqli_query($conn,$sql_tmp)){
        echo "delete information $text0 successfully<br>";
      }
    }
  }




?>

</form>

<br><br>


</body>
</html>
