<?php
session_start();
include("include/connection.php");
$userid=$_SESSION['frontuserid'];

	$checkperiod_Query=mysqli_query($con,"update agent_online set status=0 where agent_id=$userid");

session_unset();
session_destroy();

header("location:login.php");
?>

