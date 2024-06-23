<?php

include ("include/connection.php");
$id=$_GET['id'];
$user=$_GET['user'];
$agent=$_GET['agent'];
 $amount=intval($_GET['amount']);

//$Query4=mysqli_query($con,"select amount from `tbl_wallet` where userid=$agent");


 $Query=mysqli_query($con,"update `recharge_request` set status=1 where id=$id");
 
 
 $Query2=mysqli_query($con,"update `tbl_wallet` set amount=amount+$amount where userid=$user");
 $Query3=mysqli_query($con,"update `tbl_wallet` set amount=amount-$amount where userid=$agent");
  $Query4=mysqli_query($con,"insert into `tbl_walletsummery`(userid,orderid,amount,type,actiontype) values($user,$id,$amount,'credit','recharge')");
 
header('Location:agent.php');



?>