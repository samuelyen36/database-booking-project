<?php session_start(); ?>
<!DOCTYPE HTML>  
<html>
<head>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>  
<?php

unset($_SESSION['account']);
session_destroy();
echo '<meta http-equiv=REFRESH CONTENT=1;url=login.php>';

?>