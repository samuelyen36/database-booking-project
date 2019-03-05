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
// define variables and set to empty values
$accountErr = $emailErr = $passwordErr = $conpasswordErr= $userErr=$authErr=  "";
$account = $email = $password= $conpassword = $user=$auth="";
$ori_acc="";
$Errmsg=0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["account"])) {
    $accountErr = "Account is required";
    $Errmsg=1;
  } else {
    $account = test_input($_POST["account"]);
    $ori_acc=$_POST['account'];
    // check if name only contains letters and whitespace
    
    //if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
     // $nameErr = "Only letters and white space allowed"; 
    //}

      if (preg_match("/\s/",$account)) {  
      $accountErr = "no space is allowed"; 
      $Errmsg=1;
    }

  }
  
  if (empty($_POST["email"])) {
    $emailErr = "Email is required";
    $Errmsg=1;
  } else {
    $email = test_input($_POST["email"]);
    // check if e-mail address is well-formed
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $emailErr = "Invalid email format"; 
      $Errmsg=1;
    }
  }
    
  if (empty($_POST["password"])) {
    $passwordErr = "password is required";
    $Errmsg=1;
  } else {
    $password = test_input($_POST["password"]);
    

     /* if (preg_match("/\s/",$password)) {
      $passwordErr = "no space is allowed"; 
      $Errmsg=1;
    }*/

  }

  if (empty($_POST["conpassword"])) {
    $conpasswordErr = "conpassword is required";
    $Errmsg=1;
  } else {
    $conpassword= test_input($_POST["conpassword"]);
    
    // check if name only contains letters and whitespace
    
    //if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
     // $nameErr = "Only letters and white space allowed"; 
    //}

      /*if (preg_match("/\s/",$conpassword)) {
      $conpasswordErr = "no space is allowed"; 
      $Errmsg=1;
    }*/

    if( $_POST["password"]!=$_POST["conpassword"]){
      $conpasswordErr="Confirm password is different with the password";
      $Errmsg=1;
    }

  }

  if (empty($_POST["user"])) {
    $userErr = "User is required";
    $Errmsg=1;
  }
  else{
    $user=$_POST["user"];
    
  }


  if (empty($_POST["auth"])) {
    $authErr = "auth should be selected";
    $Errmsg=1;
  } else {
    $auth = test_input($_POST["auth"]);
    
  }



}



function test_input($data) {
  //$data = trim($data);    //清除前後空白
  //$data = stripslashes($data);  //deleting \
  //$data = htmlspecialchars($data);
  return $data;
}

?>


<?php


if ($Errmsg == 0){
$Errtext=" ";
$servername = "dbhome.cs.nctu.edu.tw";
$dbusername = "yenst_cs";
$dbpassword = "10271027";
$dbname = "yenst_cs_hw3";


//$auth= "normal";

// Create connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
else{
}





$sql="SELECT `account` FROM `user_table` WHERE account=?";
$pre=$conn->prepare($sql);
$pre->bind_param("s",$account);
$pre->execute();
$result=$pre->get_result();

//$result = mysqli_query($conn,$sql);
/*if(mysqli_query($conn,$sql)){
  $result=mysqli_query($conn,$sql);
}
else{
  echo "the command doesn't work\n";
}*/


$row = mysqli_fetch_row($result);

if($row[0]==$ori_acc){
  //echo "this account has been used";
  //$accountErr="This account has been used";
  //echo "帳號重複\n";
  if(!empty($account))  $Errtext="帳號重複\n";
}

else{
  $sql = "INSERT INTO `user_table`(account, password, name,email, auth) VALUES (?,?,?,?,'$auth')";
  $pre=$conn->prepare($sql);
  $md5_password=md5($password);
  $pre->bind_param("ssss",$account,$md5_password,$user,$email);



  if($pre->execute()){   //success
    echo "註冊成功";
    echo "<meta http-equiv=REFRESH CONTENT=3;url=member_management.php>";
  }

  else{
    //echo "註冊失敗\n";
    if(!empty($account))  $Errmsg="註冊失敗\n";
  }

}

}

else{
$Errtext=" ";
}


?>



<p><span class="error">* required field.</span></p>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
  Account: <input type="text" name="account" value="<?php echo $account;?>">
  <span class="error">* <?php echo $accountErr;?></span>
  <br><br>

  Password: <input type="password" name="password" value="<?php echo $password;?>">
  <span class="error">* <?php echo $passwordErr;?></span>
  <br><br>

  Confirm Password: <input type="password" name="conpassword" value="<?php echo $conpassword;?>">
  <span class="error">* <?php echo $conpasswordErr;?></span>
  <br><br>

  user name: <input type="text" name="user" value="<?php echo $user;?>">
  <span class="error">* <?php echo $userErr;?></span>
  <br><br>

  E-mail: <input type="text" name="email" value="<?php echo $email;?>">
  <span class="error">* <?php echo $emailErr;?></span>
  <br><br>
  Auth: <input type="radio" name="auth" value="normal">Normal
      <input type="radio" name="auth" value="admin">Admin
      <span class="error">* <?php echo $authErr;?></span>
  <br><br>
  <br><br>
  <input type="submit" name="submit" value="Submit">  
</form>

<br> back to member_management page? click <a href="member_management.php">here</a>

<br><br>


<span class="error"> <?php echo $Errtext;?></span>



</body>
</html>
