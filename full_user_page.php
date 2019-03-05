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

 $authority=$_SESSION['auth'];
 $this_user=$_SESSION['id'];
 $user_name=$_SESSION['_name'];
 $user_email=$_SESSION['_email'];
if($authority=='admin')  echo '<a href="member_management.php">會員管理</a>';
    echo '<a href="house_management.php">房屋管理</a>       <a href="favorite.php">我的最愛</a>       <a href="logout.php">登出</a> <br>';

echo "姓名: $user_name 帳號 : $this_user 郵件 : $user_email";


$servername = "dbhome.cs.nctu.edu.tw";
$dbusername = "yenst_cs";
$dbpassword = "10271027";
$dbname = "yenst_cs_hw3";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
$select_info="select house_id from information where 1";   //no need to prevent sql injection,selecting in information table
$input_name=$input_owner=$input_time=$input_location=$input_price=$input_id="";
$info_flag=0;

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
    echo "<br>";
    if(!empty($_GET['inf0'])){
        $inf0=$_GET['inf0'];
        $select_info="select house_id from information where information= 'laundry facility' and house_id in ($select_info)";
        $info_flag=1;
        echo "$inf0<br>";
    }
    if(!empty($_GET['inf1'])){
        $inf1=$_GET['inf1'];
        $select_info="select house_id from information where information= 'wifi' and house_id in ($select_info)";
        $info_flag=1;
        echo "$inf1<br>";
    }
    if(!empty($_GET['inf2'])){
        $inf2=$_GET['inf2'];
        $select_info="select house_id from information where information= 'lockers' and house_id in ($select_info)";
        $info_flag=1;
        echo "$inf2<br>";
    }
    if(!empty($_GET['inf3'])){
        $inf3=$_GET['inf3'];
        $select_info="select house_id from information where information= 'kitchen' and house_id in ($select_info)";
        $info_flag=1;
        echo "$inf3<br>";
    }
    if(!empty($_GET['inf4'])){
        $inf4=$_GET['inf4'];
        $select_info="select house_id from information where information= 'elevator' and house_id in ($select_info)";
        $info_flag=1;
        echo "$inf4<br>";
    }
    if(!empty($_GET['inf5'])){
        $inf5=$_GET['inf5'];
        $select_info="select house_id from information where information= 'no smoking' and house_id in ($select_info)";
        echo "$inf5<br>";
    }
    if(!empty($_GET['inf6'])){
        $inf6=$_GET['inf6'];
        $select_info="select house_id from information where information= 'television' and house_id in ($select_info)";
        $info_flag=1;
        echo "$inf6<br>";
    }
    if(!empty($_GET['inf7'])){
        $inf7=$_GET['inf7'];
        $select_info="select house_id from information where information= 'breakfast' and house_id in ($select_info)";    
        $info_flag=1;
        echo "$inf7<br>";
    }
    if(!empty($_GET['inf8'])){
        $inf8=$_GET['inf8'];
$select_info="select house_id from information where information= 'toiletries provided' and house_id in ($select_info)";        
$info_flag=1;
echo "$inf8<br>";
    }
    if(!empty($_GET['inf9'])){
        $inf9=$_GET['inf9'];
    $select_info="select house_id from information where information= 'shuttle service' and house_id in ($select_info)";
       $info_flag=1;
        echo "$inf9<br>";
    }

    if($info_flag==1)   $select_all="select house.id , house.name , house.price , house.location , house.time , user_table.name from house left join user_table on house.owner_id = user_table.id where house.id in ($select_info) ";     //後面加指令
    if($info_flag==0)   $select_all="select house.id , house.name , house.price , house.location , house.time , user_table.name from house left join user_table on house.owner_id = user_table.id where 1 ";     //後面加指令
        //==0 means select no info


    if(!empty($_GET['input_name'])){
            $input_name=$_GET['input_name'];
            echo "name: $input_name<br>";
            $_SESSION['set']=1;
            $select_all.="and house.name like concat ('%','$input_name','%') ";
    }else { if(empty($_GET['order']))$input_name="";}

    if(!empty($_GET['input_owner'])){      //name of the owner
        $input_owner=$_GET['input_owner'];
        echo "owner: $input_owner<br>";
        $_SESSION['set']=1;
        $select_all.="and user_table.name like concat ('%','$input_owner','%') ";
    }else {if(empty($_GET['order'])) $input_owner="";}

    if(!empty($_GET['input_time'])){       
        $input_time=$_GET['input_time'];
        echo "time: $input_time<br>";
        $_SESSION['set']=1;
        $select_all.="and house.time = '$input_time' ";
    }else {if(empty($_GET['order'])) $input_time="";}

    if(!empty($_GET['input_location'])){
        $input_location=$_GET['input_location'];
        echo "location: $input_location<br>";
        $_SESSION['set']=1;
        $select_all.="and house.location like concat ('%','$input_location','%') ";
    }else {if(empty($_GET['order'])) $input_location="";}

    if(!empty($_GET['input_id'])){
        $input_id=$_GET['input_id'];
        echo "id: $input_id<br>";
        $_SESSION['set']=1;
        $select_all.="and house.id = $input_id ";
    }else {if(empty($_GET['order'])) $input_id="";}
    
    if(!empty($_GET['input_price'])){
        $input_price=$_GET['input_price'];
        echo "price: $input_price<br>";
        $_SESSION['set']=1;
        if($input_price=='1') $select_all.="and house.price between 0 and 500 ";
        else if($input_price=='2') $select_all.="and house.price between 501 and 1000 ";
        else if($input_price=='3')    $select_all.="and house.price between 1001 and 1500 ";
        else $select_all.="and price > 1500 ";

    }else {if(empty($_GET['order'])) $input_price="";}

    



    if(!empty($_GET['order'])){
        $_SESSION['order']=$_GET['order'];
    }

    if(isset($_SESSION['order'])){
        if($_SESSION['order']==1)   $select_all.="order by price asc ";
        else if($_SESSION['order']==2)  $select_all.="order by price desc ";
        else if ($_SESSION['order']==3) $select_all.="order by time asc ";
        else if($_SESSION['order']==4) $select_all.="order by time desc ";
    }
    else{
        $select_all.="order by house.id";       
    }

    if($result=mysqli_query($conn,$select_all)){        //using select all as query
    }
   ?>

<br><br><br>
    <form action="<?php echo $_SERVER['REQUEST_URI']; ?>?" method="GET">
                ID
                    <input  type="text" name="input_id" value="<?php echo $input_id ?>" placeholder="ID">
                Name
                    <input  type="text" name="input_name" value="<?php echo $input_name ?>" placeholder="Name">
                Price
                    <select  size=1 name="input_price">
                        <option value="" >Price</option>
                        <option value="1" <?php if($input_price=='1'){ ?>selected<?php } ?>>0~500</option>
                        <option value="2" <?php if($input_price=='2'){ ?>selected<?php } ?>>501~1000</option>
                        <option value="3" <?php if($input_price=='3'){ ?>selected<?php } ?>>1001~1500</option>
                        <option value="4" <?php if($input_price=='4'){ ?>selected<?php } ?>>1501~</option>
                    </select>
                Location
                    <input type="text" name="input_location" value="<?php if($input_location!=" ") echo $input_location ?>" placeholder="Location">
                Date
                    <input type="date" name="input_time" value="<?php if($input_time!=" ")echo $input_time ?>" placeholder="Time">
                Owner
                    <input type="text" name="input_owner" value="<?php if($input_owner!=" ") echo $input_owner ?>" placeholder="Owner"><br>
                    <input type="checkbox" value="laundry facility" name="inf0" <?php if($inf0){ ?>checked<?php } ?>>laundry facility&nbsp;
                    <input type="checkbox" value="wifi" name="inf1" <?php if($inf1){ ?>checked<?php } ?>>wifi&nbsp;
                    <input type="checkbox" value="lockers" name="inf2" <?php if($inf2){ ?>checked<?php } ?>>lockers&nbsp;
                    <input type="checkbox" value="kitchen" name="inf3" <?php if($inf3){ ?>checked<?php } ?>>kitchen&nbsp;
                    <input type="checkbox" value="elevator" name="inf4" <?php if($inf4){ ?>checked<?php } ?>>elevator&nbsp;
                    <input type="checkbox" value="no smoking" name="inf5" <?php if($inf5){ ?>checked<?php } ?>>no smoking&nbsp;
                    <input type="checkbox" value="television" name="inf6" <?php if($inf6){ ?>checked<?php } ?>>television&nbsp;
                    <input type="checkbox" value="breakfast" name="inf7" <?php if($inf7){ ?>checked<?php } ?>>breakfast&nbsp;
                    <input type="checkbox" value="toiletries provided" name="inf8" <?php if($inf8){ ?>checked<?php } ?>>toiletries provided&nbsp;
                    <input type="checkbox" value="shuttle service" name="inf9" <?php if($inf9){ ?>checked<?php } ?>>shuttle service<br>
                    <input class="input submit" type="submit" value="Search">
                    </form>
                    <a href="<?php echo $_SERVER['REQUEST_URI']; ?>&&order=1" >&darr;</a>Price<a href="<?php echo $_SERVER['REQUEST_URI']; ?>&&order=2" >&uarr;</a>
                    <a href="<?php echo $_SERVER['REQUEST_URI']; ?>&&order=3">&darr;</a>Time<a href="<?php echo $_SERVER['REQUEST_URI']; ?>&&order=4">&uarr;</a>
<?php echo "<br><br>$select_all<br>"; ?>
<?php echo "<br><br>$select_info<br>"; ?>

   <?php 


/*$sql = "SELECT * FROM `user_table`"; //在test資料表中選擇所有欄位 
$sql_house="select * from house left outer join information on house.id = information.house_id inner join user_table on house.owner_id = user_table.id";*/

        /*while($row = @mysqli_fetch_row($result)){
                if($_SESSION['account']==$row[0]){
                    echo "姓名：$row[2]<br>帳號：$row[0]<br>郵件：$row[3]<br>";
                    $_SESSION['id']=$row[5];
                }
        }*/
        //echo '<a href="logout.php">登出</a>  <br><br>';
   
        while($row = @mysqli_fetch_row($result)){
            $this_house_id=$row[0];
            $sql_info="select information from information where house_id = '$this_house_id'";      //no need to do prepare
            $result_info = mysqli_query($conn,$sql_info);
            $sql_favorite="select * from favorite where user_id = '$this_user' and favorite_id = '$this_house_id'";
            $result_favorite = mysqli_query($conn,$sql_favorite);
            $row_count=$result_favorite->num_rows;

            if($row_count===0){
            ?>  <a href="add_favorite.php?house_id=<?php echo$row_house[0]?>&userid=<?php echo$this_user?>"> add to favorite<br>  </a>
            <?php  }
            else{
            echo "<font size=\"5\" color=\"red\">This one is already in the favorite list<br></font>";
            }
             if($authority=='admin'){
                ?>
                <a href="delete_house.php?house_id=<?php echo$row[0]?>"> delete this house <br></a>
             <?php }


            echo "id: $row[0] , name : $row[1] , price : $row[2] , location : $row[3] , time : $row[4] , owner name: $row[5]<br>";
            echo "information : ";
            while($row_result = @mysqli_fetch_row($result_info)){
                echo "$row_result[0]";
            }
            echo "<br><br>";

        }
?>
</body>
</html>