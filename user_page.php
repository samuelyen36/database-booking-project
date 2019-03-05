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
   if(!isset($_SESSION['account'])){
echo '<meta http-equiv=REFRESH CONTENT=0;url=no_permission.php>';
}

$input_name=$input_owner=$input_time=$input_location=$input_price=$input_id=$input_checkin=$input_checkout=$location_id="";
$input_checkin=date("Y-m-d");
$input_checkout=date("Y-m-d");
$today=date("Y-m-d");
$name_bool=$id_bool=$owner_bool=0;
$empty_flag=0;

 $authority=$_SESSION['auth'];
 $this_user=$_SESSION['id'];
 $user_name=$_SESSION['_name'];
 $user_email=$_SESSION['_email'];
 $user_account = $_SESSION['account'];
 $select_limit=" ";
if($authority=='admin')  echo '<a href="member_management.php">會員管理</a> <a href="add_information.php">新增資訊</a> <a href="add_location.php">新增位置</a> <a href="delete_location.php">刪除位置</a> <a href="delete_information.php">刪除資訊</a> <a href="delete_location.php">刪除位置</a>';
    echo '<a href="house_management.php">房屋管理</a>       <a href="favorite.php">我的最愛</a>       <a href="logout.php">登出</a> <a  <a href="landlord.php">I am landlord</a>  <a href="tenant.php">I am tenant</a> <br>';

$servername = "dbhome.cs.nctu.edu.tw";
$dbusername = "yenst_cs";
$dbpassword = "10271027";
$dbname = "yenst_cs_hw3";

// Create connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
if(!empty($_GET['check_in']))   $input_checkin=$_GET['check_in'];
if(!empty($_GET['check_out']))  $input_checkout=$_GET['check_out'];
if(!empty($_GET['check_in']))   $checkin=date_create($_GET['check_in']);
if(!empty($_GET['check_out']))  $checkout=date_create($_GET['check_out']);
$curr= date_create($today);
echo "$today<br>";

if(empty($_GET['check_in']) or empty($_GET['check_out'])){
	$empty_flag=1;		//means the first enter
}
else{
	$empty_flag=0;
}


if(!empty($_GET['check_in']) and !empty($_GET['check_out'])){
if($checkin <= $curr or $checkout <= $curr)  {      //it is not later than today
    echo "illegal 1<br><br>";
    echo "<meta http-equiv=REFRESH CONTENT=0;url=illegal_one.php>";
}
if($checkin >= $checkout){      //check in day must be later than today
    echo "illegal 2<br><br>";
    echo "<meta http-equiv=REFRESH CONTENT=0;url=illegal_two.php>";
}
}

 //$diff_to_in=date_diff($today,$checkin);      //larger than zero is illegal
 //$diff_to_out=date_diff($today,$checkout);    //larger than zero is illegal
 //$diff_in_out=date_diff($checkout,$checkin);    //small than zero illegal



$select_info="select information.house_id from information_name inner join information on information_name.id = information.information_id where 1 ";   //no need to prevent sql injection,selecting in information table
$select_loca="select location.house_id from location_name inner join location on location_name.id = location.location_id where 1 ";   //no need to prevent sql injection,selecting in information table0
 $select_order="select house_id from book where check_in < '$input_checkout' and check_out > '$input_checkin'";  //illegal case


$select_owner="select id from house where 1 ";

if($empty_flag==1)	$select_all="select house.id , house.name , house.price , house.time , user_table.name from house left join user_table on house.owner_id = user_table.id where 1  ";		//no date input
else $select_all="select house.id , house.name , house.price , house.time , user_table.name from house left join user_table on house.owner_id = user_table.id where 1 and house.id not in ($select_order) ";


$info_flag=0;

$sql_select_information="select * from information_name where 1 ";   //select the house_id which satisified the information that user want
  $all_information=mysqli_query($conn,$sql_select_information);
  while($row=@mysqli_fetch_row($all_information)){
    if(!empty($_GET[$row[0]])){         //information table 
        $info_flag=1;
        $id_of_information=$row[0];
        $select_info="select information.house_id from information_name inner join information on information_name.id=information.information_id where information_name.id = $id_of_information and information.house_id in ($select_info)";
        $select_all.="and house.id in($select_info)";
     }
}

if(!empty($_GET['location'])){
  $location_id=$_GET['location'];
  $select_loca="select location.house_id from location_name inner join location on location_name.id = location.location_id where location.location_id = '$location_id' and location.house_id in($select_loca)";
   $select_all.="and house.id in($select_loca)";
  }



if(!empty($_GET['input_name'])){
            $input_name=$_GET['input_name'];
            $name_bool=1;
           // echo "name: $input_name<br>";
            $_SESSION['set']=1;
            $select_all.="and house.name like concat ('%', ? ,'%') ";
    }

if(!empty($_GET['input_owner'])){      //name of the owner
        $input_owner=$_GET['input_owner'];
        $owner_bool=1;
       // echo "owner: $input_owner<br>";
        $_SESSION['set']=1;
        $select_all.="and user_table.name like concat ('%', ? ,'%') ";
    }

    if(!empty($_GET['input_time'])){       
        $input_time=$_GET['input_time'];
        $_SESSION['set']=1;
        $select_all.="and house.time = '$input_time' ";
    }

    if(!empty($_GET['input_id'])){
        $input_id=$_GET['input_id'];
        $id_bool=1;
       // echo "id: $input_id<br>";
        $_SESSION['set']=1;
        $select_all.="and house.id = ? ";
    }
    
    if(!empty($_GET['input_price'])){
        $input_price=$_GET['input_price'];
       // echo "price: $input_price<br>";
        $_SESSION['set']=1;
        if($input_price=='1') $select_all.="and house.price between 0 and 500 ";
        else if($input_price=='2') $select_all.="and house.price between 501 and 1000 ";
        else if($input_price=='3')    $select_all.="and house.price between 1001 and 1500 ";
        else $select_all.="and price > 1500 ";
    }

if(!empty($_GET['order'])){
$order = $_GET['order'];

if($order==5)   $select_all.="order by house.id asc ";
if($order == 4) $select_all.="order by house.time desc ";
if($order == 3) $select_all.="order by house.time asc ";
if($order == 2) $select_all.="order by house.price desc ";
if($order == 1) $select_all.="order by house.price asc ";
}
else{
    $order=5;
}
?>

    <form action="<?php echo $_SERVER['REQUEST_URI']; ?>?" method="GET">
                ID
                    <input type="text" name="input_id" value="<?php if($input_id!=" ")echo $input_id ?>" placeholder="ID">
                Name
                    <input type="text" name="input_name" value="<?php if($input_name!=" ")echo $input_name ?>" placeholder="Name">
               <br> Location : 
                    <?php
                      $sql_get_location="select * from location_name where 1";
                      $result_location=mysqli_query($conn,$sql_get_location);
                      while($row=@mysqli_fetch_row($result_location)){
                        ?>  <input type="radio" name="location" value="<?php echo $row[0]; ?>" <?php if($location_id==$row[0]) { ?> checked <?php } ?> ><?php echo $row[1]; ?><?php 
                      }
                    ?>
                <br>
                Price
                    <select  size=1 name="input_price">
                        <option value="" >Price</option>
                        <option value="1" <?php if($input_price=='1'){ ?>selected<?php } ?>>0~500</option>
                        <option value="2" <?php if($input_price=='2'){ ?>selected<?php } ?>>501~1000</option>
                        <option value="3" <?php if($input_price=='3'){ ?>selected<?php } ?>>1001~1500</option>
                        <option value="4" <?php if($input_price=='4'){ ?>selected<?php } ?>>1501~</option>
                    </select>
                Date
                    <input type="date" name="input_time" value="<?php if($input_time!=" ")echo $input_time ?>" placeholder="Time">
                Owner
                    <input type="text" name="input_owner" value="<?php if($input_owner!=" ") echo $input_owner ?>" placeholder="Owner"><br>
               
                Information
                <?php
                    $sql_get_information="SELECT * from information_name";
                    $result_information=mysqli_query($conn,$sql_get_information);
                    $i=0;
                    while($row=@mysqli_fetch_row($result_information)){
                ?>
                <input type="checkbox" value="<?php echo $row[1]; ?>" name="<?php echo $row[0]; ?>" <?php
                                                
                        if(!empty($_GET[$row[0]])){ ?> checked <?php } ?> ><?php echo $row[1] ?>
                    <?php
                    $i++;
                    if($i%5==0){echo "<br>";}
            }
                    ?><br>
                   
                    Checkin date
                    <input type="date" name="check_in" value=<?php echo"$input_checkin"?>  required> <br> 
                    Checkout date
                    <input type="date" name="check_out" value=<?php echo"$input_checkout"?> required> <br> 
                    <input class="input submit" type="submit" value="Search"> <br> 
                    </form>
                    <a href="<?php echo $_SERVER['REQUEST_URI']; ?>&&order=1" >&darr;</a>Price<a href="<?php echo $_SERVER['REQUEST_URI']; ?>&&order=2" >&uarr;</a>
                    <a href="<?php echo $_SERVER['REQUEST_URI']; ?>&&order=3">&darr;</a>Time<a href="<?php echo $_SERVER['REQUEST_URI']; ?>&&order=4">&uarr;</a>
                    <a href="<?php echo $_SERVER['REQUEST_URI']; ?>&&order=5" >ID</a>



<?php 
//echo "$select_info<br><br><br><br>";
//echo "$select_all <br><br><br><br>";
$select_limit = $select_all;
if(!isset($_GET['page'])){
    $page=1; 
}
else{
    $page = $_GET['page'];
}


$pre=$conn->prepare($select_all);       //name->owner->id


if($name_bool==0 && $owner_bool==0 && $id_bool==0)      {}
if($name_bool==0 && $owner_bool==0 && $id_bool==1)      {$pre->bind_param("s",$input_id);}
if($name_bool==0 && $owner_bool==1 && $id_bool==0)      {$pre->bind_param("s",$input_owner);}
if($name_bool==0 && $owner_bool==1 && $id_bool==1)      {$pre->bind_param("ss",$input_owner,$input_id);}
if($name_bool==1 && $owner_bool==0 && $id_bool==0)      {$pre->bind_param("s",$input_name);}
if($name_bool==1 && $owner_bool==0 && $id_bool==1)      {$pre->bind_param("ss",$input_name,$input_id);}
if($name_bool==1 && $owner_bool==1 && $id_bool==0)      {$pre->bind_param("ss",$input_name,$input_owner);}
if($name_bool==1 && $owner_bool==1 && $id_bool==1)      {$pre->bind_param("sss",$input_name,$input_owner,$input_id);}




$pre->execute();
if($result = $pre->get_result()){       //the former one is to get the number of rows
$pre->close();
$num_of_results=mysqli_num_rows($result);
//$thispage_per=$page;
$result_per_page=5;     //pagenate
$this_page_first_result = ($page-1)*$result_per_page;
/*$this_page_first_result=0;
$c=0;
for($c=0;$c<$page;$c=$c+1){
    $this_page_first_result =$this_page_first_result+$c;
}*/
 


?> <font size="8" color="red">there are <?php echo "$num_of_results" ?> results and you are now in <?php echo "$page"?> page !</font><br> <?php

if($num_of_results==0){
	echo "<div style ='font:50px/50px ;color:#ff0000'> <font size='18'> there is no house in the database </div> </font>\n\n\n";
}

$select_limit.=" limit $this_page_first_result , $result_per_page";
$pre = $conn->prepare($select_limit);

if($name_bool==0 && $owner_bool==0 && $id_bool==0)      {}
if($name_bool==0 && $owner_bool==0 && $id_bool==1)      {$pre->bind_param("s",$input_id);}
if($name_bool==0 && $owner_bool==1 && $id_bool==0)      {$pre->bind_param("s",$input_owner);}
if($name_bool==0 && $owner_bool==1 && $id_bool==1)      {$pre->bind_param("ss",$input_owner,$input_id);}
if($name_bool==1 && $owner_bool==0 && $id_bool==0)      {$pre->bind_param("s",$input_name);}
if($name_bool==1 && $owner_bool==0 && $id_bool==1)      {$pre->bind_param("ss",$input_name,$input_id);}
if($name_bool==1 && $owner_bool==1 && $id_bool==0)      {$pre->bind_param("ss",$input_name,$input_owner);}
if($name_bool==1 && $owner_bool==1 && $id_bool==1)      {$pre->bind_param("sss",$input_name,$input_owner,$input_id);}

$pre->execute();
$result = $pre->get_result();       //this one is to get the exact position of the query


while($row = @mysqli_fetch_row($result)){
    $this_house=$row[0];
    $sql_favorite="select * from favorite where user_id = '$this_user' and house_id = '$this_house'";
            $result_favorite = mysqli_query($conn,$sql_favorite);
            $row_count=mysqli_num_rows($result_favorite);
            if($empty_flag==0){
    ?>  <a href="add_book.php?checkin=<?php echo$input_checkin?>&checkout=<?php echo$input_checkout?>&house=<?php echo$this_house?>"> order </a> <br>
      <?php } if(!$row_count||$row_count==0){
       ?>  <a href="add_favorite.php?house_id=<?php echo$this_house?>&&userid=<?php echo$this_user?>"> add to favorite </a> <br>
   <?php }else{
        echo "<font  color=\"red\">This one is already<br> in the favorite list<br></font>";
    } ?>
    <?php
    if($authority=='admin'){
                ?>
                <a href="adelete.php?house_id=<?php echo$row[0]?>"> delete this house <br></a></th>
             <?php }
    $this_information="select name from information_name inner join information on information_name.id = information.information_id where information.house_id = $this_house";
    $this_location="select name from location_name inner join location on location_name.id = location.location_id where location.house_id = $this_house";
    $temp_information = mysqli_query($conn,$this_information);
    $temp_location = mysqli_query($conn,$this_location);
    $information_count=$temp_information->num_rows;
    $location_count=$temp_location->num_rows;


    echo "house_id: $row[0]<br>house_name: $row[1]<br>house_price: $row[2]<br>house_time: $row[3]<br>user_name: $row[4]";


    echo "<br>information : ";
    if($information_count==0){
        echo "<font  color=\"red\" size=\"5\" >unknown</font>";
    }else{
    while($row_info = @mysqli_fetch_row($temp_information)){
        echo "$row_info[0] ";
    }}


    echo "<br>location: ";
    if($location_count==0){
        echo "<font  color=\"red\" size=\"5\" >unknown</font>";
    }
    else{
    while($row_loco = @mysqli_fetch_row($temp_location)){
        echo "$row_loco[0] ";
    }}

    echo "<br><br><br><br>";
}
$n=0;
$number_f_pages = ceil($num_of_results/$result_per_page);
/*$number_f_pages=0;
while($num_of_results>=0){
    $n=$n+1;
    $num_of_results=$num_of_results-$n;
    $number_f_pages=$number_f_pages+1;
}*/

//=ceil(/$result_per_page);

for($page=1;$page<=$number_f_pages;$page=$page+1){
?>   <a href= <?php echo $_SERVER['REQUEST_URI'];?>&page=<?php echo "$page" ?>> <?php echo "$page"?> </a> <?php
}

}

?>


</body>
</html>
