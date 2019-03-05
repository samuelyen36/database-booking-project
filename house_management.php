<?php
session_start();
?>
<!DOCTYPE HTML>  
<html>
<head>
<style>
.error {color: #FF0000;}
table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
}
</style>
</head>
<body>  



<?php

if(!isset($_SESSION['account']))
{
        echo '<meta http-equiv=REFRESH CONTENT=0;url=no_permission.php>';

}

$servername = "dbhome.cs.nctu.edu.tw";
$dbusername = "yenst_cs";
$dbpassword = "10271027";
$dbname = "yenst_cs_hw3";
$this_user = $_SESSION['id'];
$flag=0;

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
else{
}
    
    echo '<a href="user_page.php?order=5">HomePage</a>       <a href="logout.php">登出</a>      <a href="add_house.php">新增</a> <br>';
    $login_id=$_SESSION['id'];
    $this_user=$_SESSION['id'];

    $sql_house= "SELECT * FROM house inner join owner on house.owner_id=owner.owner_id where house.id = owner.house_id and house.owner_id = $this_user ";


if($result_house = mysqli_query($conn,$sql_house)){
}
else{
    echo "not success\n";
}

?> <table style="width:100%">   <tr> <th>id</th><th>name</th><th>price</th><th>location</th><th>time</th><th>owner name</th><th>information</th><th> </th></tr>
    <?php
while($row_house = @mysqli_fetch_row($result_house)){
    if($row_house === 0){   //no houses
        echo "<br><br>there is no house in the data base";
        break;
    }

    else {
        $id_now=$row_house[0];
        $sql_location="SELECT location_name.name FROM location inner join location_name on location.location_id=location_name.id where house_id='$id_now'";
        
        $sql_owner="SELECT user_table.name from user_table inner join house on user_table.id=house.owner_id where house.id='$id_now'";
        $sql_information = "select information_name.name from information_name inner join information on information_name.id=information.information_id where information.house_id='$id_now' ";
        $result_location=mysqli_query($conn,$sql_location);
        $result_owner=mysqli_query($conn,$sql_owner);
        $result_information = mysqli_query($conn,$sql_information);
        $location_row_count=mysqli_num_rows($result_location);
        $information_row_count=mysqli_num_rows($result_information);
        //echo "id: $row_house[0]\t name: $row_house[1]\t price: $row_house[2]\t location: $row_house[3]\t time:$row_house[4]\t owner: $row_house[11]\t";
  ?><tr><th>   <?php   echo "$row_house[0]";   //id  ?>  </th>
      <th> <?php  echo "$row_house[1]";    //name  ?>  </th>
      <th> <?php  echo "$row_house[2]";    //price  ?>  </th>
      <th><?php   if($location_row_count==0) echo "not known";
      else{
            while($row_result1 = @mysqli_fetch_row($result_location)){
            echo "$row_result1[0]<br>";
            }
      }

      ?></th>
      
      <th> <?php  echo "$row_house[3]";    //time  ?>  </th>
      <th><?php   while($row_result2 = @mysqli_fetch_row($result_owner)){
            echo "$row_result2[0]<br>";
        }?></th>
      
     <th><?php if($information_row_count==0) echo "not known";  
     else{
            while($row_result3 = @mysqli_fetch_row($result_information)){
            echo "$row_result3[0]<br>";
            }
      } 
     ?></th>

         <th>  <a href="edit_house.php?house_id=<?php echo$row_house[0]?>"> edit this house <br></a> <a href="delete_house.php?house_id=<?php echo$row_house[0]?>"> delete this house <br></a> </th>
        </tr><?php
        $flag=1;
    }
}

if($flag==0){
    ?> <font size="8" color="red">you don't have a house yet</font>"<?php 
}
else{
    ?> <font size="8" color="red">These are your houses !</font>
<?php 
}

?> </table> <?php
    


        //echo '<a href="logout.php">登出</a>  <br><br>';






?>
</body>
</html>