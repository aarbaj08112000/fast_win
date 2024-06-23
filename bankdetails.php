<?php 
ob_start();
session_start();
include("include/connection.php");

@$userid=$_SESSION['frontuserid'];
@$name=$_POST['name'];
@$ifsc=$_POST['ifsc'];
@$bank=$_POST['bank'];
@$account=$_POST['account'];
@$upi=$_POST['upi'];
@$mobile=$_POST['mobile'];
@$email=$_POST['email'];
@$action="bank";
@$editid=$_POST['editid'];
$today = date("Y-m-d H:i:s");
$query="INSERT INTO `tbl_bankdetail`(`userid`,`name`,`ifsc`,`bankname`,`account`,upid,`mobile`,`whatsapp`,`email`,`type`,`status`) VALUES ($userid,'".$name."','".$ifsc."','".$bank."','".$account."','".$upi."','".$mobile."','".$mobile."','".$email."','".$action."',1)";
echo $query;
$withdrawalsql= mysqli_query($con,$query);



?>