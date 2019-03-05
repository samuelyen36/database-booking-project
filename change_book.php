<?php
session_start();
?>

<a href="user_page.php">homepage</a> <a href="logout.php">logout</a><br><br>

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

function addDayswithdate($date,$days){
    $date = strtotime("+".$days." days", strtotime($date));
    return  date("Y-m-d", $date);
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
$today=date("Y-m-d");
$curr= date_create($today);

$this_house=$_GET['house_id'];
$this_user=$_SESSION['id'];
$book_id = $_GET['id'];
echo "<a href='tenant.php'> 回訂房頁面 </a><br>";
echo "this house: $this_house <br>";
echo "this user: $this_user";

if(!empty($_POST['checkin'])){
if(!empty($_POST['checkout'])){
$flag=0;

$input_checkin=$_POST['checkin'];
$input_checkout=$_POST['checkout'];

$checkin=date_create($_POST['checkin']);
$checkout=date_create($_POST['checkout']);

if($checkin <= $curr or $checkout <= $curr)  {      //it is not later than today
    echo "<font color = 'red' >the check in date or ckeck out date have to be later than today <br><br> </font>";
    $flag=1;
}
if($checkin >= $checkout){      //check in day must be later than today
    echo "<font color = 'red' >the ckeck out date must be later than check in date <br><br></font>";
    $flag=1;
}
}

if($flag==0){
$select_order="select house_id from book where check_in < '$input_checkout' and check_out > '$input_checkin' and house_id = ? and user_id <> ? ";

$sq="select house_id from book where check_in < '$input_checkout' and check_out > '$input_checkin' and house_id = $this_house and user_id <> $this_user ";


$stmt = $conn->prepare($select_order);
$stmt->bind_param("ii",$this_house,$this_user);

$stmt->execute();
$result = $stmt->get_result();		//if this equal to zero means this change is ok
$count = mysqli_num_rows($result);
$stmt->close();


if($count!=0){		//error,check in or check out invalid
	echo "<font color = 'red' >the checkin date or checkout date is illegal <br><br></font>";

	while(date_create($input_checkin)<=date_create($input_checkout)){
		$sql_check = "select house_id from book where check_in < '$input_checkin' and check_out > '$input_checkin' and user_id <> $this_user";
		if($result = mysqli_query($conn,$sql_check)){			
		}
		else{
			echo "query wrong<br>";
		}

		if(mysqli_num_rows($result)!=0){		//this date is already being booked
			echo "<font size='18' color='red'> $input_checkin has been booked <br> </font>";
		}
		//echo "$sql_check";
	//echo "$input_checkin<br>";
	$input_checkin=addDayswithdate($input_checkin,1);		
	}

}
else{

	$update_sql = "UPDATE `book` SET `check_in`= '$input_checkin' , `check_out`= '$input_checkout' WHERE id = $book_id ";
	echo "<div style ='font:50px/50px ;color:#ff0000'> <font size='18' color='red' > 訂單更換成功 </div> </font>\n\n\n";
	echo '<meta http-equiv=REFRESH CONTENT=2;url=tenant.php>';
	mysqli_query($conn,$update_sql);


}

}


}

?>



<form action="change_book.php?id=<?php echo "$book_id"?>&house_id=<?php echo $this_house ?>" method="POST">
checkin: 
<input type="date" name="checkin" value="<?php echo "$today" ?>" required>
checkout:
<input type="date" name="checkout" value="<?php echo "$today" ?>" required>
 <input type="submit">



</form>