<?php
ob_start();
session_start();
include("include/connection.php");
$uploaddir = 'uploads/';
$uploadfile = $uploaddir . basename($_FILES['ss']['name']);

echo '<pre>';
if (move_uploaded_file($_FILES['ss']['tmp_name'], $uploadfile)) {
    $userid=$_POST["userid"];
$agent=$_POST["agent"];
$amount=$_POST["amount"];

$query="insert into `recharge_request`(user_id,agent_id,amount,ss) value($userid,$agent,$amount,'".$uploadfile."')";
echo $query;
//echo $query;
$Query2=mysqli_query($con,$query);

header('Location:myaccount.php');
} else {
    echo "Possible file upload attack!\n";
}

echo 'Here is some more debugging info:';
print_r($_FILES);

print "</pre>";


?>