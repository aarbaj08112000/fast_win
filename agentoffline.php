<?php 
include("include/connection.php");
$userid=$_POST['userid'];
if($_POST['userid']){
	$checkperiod_Query=mysqli_query($con,"update agent_online set status=0 where agent_id=$userid");
}
?>