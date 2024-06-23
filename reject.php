<?php

include ("include/connection.php");
$id=$_GET['id'];
$user=$_GET['user'];
$agent=$_GET['agent'];
$amount=intval($_GET['amount']);
 $Query=mysqli_query($con,"update `recharge_request` set status=2 where id=$id");
 
 header('Location:agent.php');



?>